<?php if (!defined('APPLICATION')) exit();

class RaidingController extends ReptasticController {
   
    public $Uses = array('ShadowModel', 'Form');
   
    public function Index() {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Reputation->Start a Raid'));
        }
        
        $Session = Gdn::Session();
        if ($Session->CheckPermission('Garden.Users.Edit')) {
            $Raiders = $this->ShadowModel->GetActiveCharacters();
            
            $this->Form->SetModel($this->ShadowModel);
        
      
            if($this->Form->AuthenticatedPostBack() === FALSE) {
                $this->Form->SetData($Raiders);
                $SignUps = $this->ShadowModel->SignUpBonus();
                
                if ($SignUps !== FALSE)
                    $this->StatusMessage = $SignUps;
            } else {
                $FormValues = $this->Form->FormValues();
                $UserID = $this->ShadowModel->DefineRaid($FormValues);
        
                if ($UserID !== FALSE)
                    Redirect('/raiding/panel');
            }//if
            
            // Render the controller
            $this->Render();
        } else {
            Redirect('/discussions');
        }//end sessioncheck
    }
    
    public function Remove() {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Reputation->Remove Raiders'));
        }
        
        $Session = Gdn::Session();
        if ($Session->CheckPermission('Garden.Users.Edit')) {
            $Raiders = $this->ShadowModel->GetActiveCharacters();
            $this->Form->SetModel($this->ShadowModel);
        
      
            if($this->Form->AuthenticatedPostBack() === FALSE) {
                $this->Form->SetData($Raiders);
            } else {
                $FormValues = $this->Form->FormValues();
                $UserID = $this->ShadowModel->RemoveRaiders($FormValues);
            
                $PersonChanging = $Session->UserID;
                
                foreach($FormValues as $Key => $Value) {
                    if ($Key !== "TransientKey" && $Key !== "Remove") {
                        $TimeLeft = date("F j, Y, g:i a");
                        
                        if (array_key_exists('Remove_&_Alert', $FormValues)) {
                            AddActivity($Value, 'LeftEarly', 'Date: '.$TimeLeft.'.', $Value, '');
                        }//if
                    }//if
                }//foreach
              
                if ($UserID !== FALSE)
                    Redirect('/raiding/panel');
            }//if
            
            // Render the controller
            $this->Render();
        } else {
            Redirect('/discussions');
        }//if
    }
    
    public function Add() {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Reputation->Remove Raiders'));
        }
        
        $Session = Gdn::Session();
        if ($Session->CheckPermission('Garden.Users.Edit')) {
            $Raiders = $this->ShadowModel->GetActiveCharacters();
            $this->Form->SetModel($this->ShadowModel);
        
      
            if($this->Form->AuthenticatedPostBack() === FALSE) {
                $this->Form->SetData($Raiders);
            } else {
                $FormValues = $this->Form->FormValues();
                $UserID = $this->ShadowModel->AddRaiders($FormValues);
            
        
                if ($UserID !== FALSE)
                    Redirect('/raiding/panel');
            }//if

            // Render the controller
            $this->Render();
        } else {
            Redirect('/discussions');
        }//if
    }
    
    public function Panel() {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Reputation->Raid Panel'));
        }
        
        $Session = Gdn::Session();
        if ($Session->CheckPermission('Garden.Users.Edit')) {
            // Define tardy dropdown options
            $this->TardyOptions = array(
                '0' => 'On Time',
                '1' => '< 15 Minutes',
                '2' => '< 30 Minutes',
                '3' => '< 2 Hours',
                '4' => 'No Show'
            );
        
            $Raiders = $this->ShadowModel->GetActiveCharacters();
        
            $this->Form->SetModel($this->ShadowModel);
        
      
            if($this->Form->AuthenticatedPostBack() === FALSE) {
                $this->Form->SetData($Raiders);
            } else {
                $FormValues = $this->Form->FormValues();
                $PersonChanging = $Session->UserID;
                $UserID = '';
            
                if (array_key_exists('Attendance', $FormValues)) {
                    $Result = $this->ShadowModel->AwardPoints(5, 'Attendance');
                } else if (array_key_exists('Finish', $FormValues)) {
                    $Result = $this->ShadowModel->AwardPoints(55, 'Finish');
                } else if (array_key_exists('Progression', $FormValues)) {
                    $Result = $this->ShadowModel->AwardPoints(2, 'Progression');
                } else if (array_key_exists('Signup_Bonus', $FormValues)) {
                    $Result = $this->ShadowModel->AwardPoints(50, 'Signup Bonus');
                } else if (array_key_exists('Save', $FormValues)) {
                    foreach($FormValues as $Key => $Value) {
                        if ($Key === "UserID") {
                            $UserID = $Value;
                        } else if ($Key === "Tardy") {
                            if ($Value !== '0') 
                                AddActivity($UserID, 'RaidLate', '', $UserID, '');
                        }//if
                    }//foreach
                    $Result = $this->ShadowModel->SaveTardy($FormValues);
                }//if
            
                if ($Result !== FALSE)
                    $this->StatusMessage = $Result;
                    
                if (array_key_exists('Finish', $FormValues))
                    Redirect('/manage');
            }//if
        
            // Render the controller
            $this->Render();
        } else {
            Redirect('/discussions');
        }//if
    }
    
    public function Loot() {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Reputation->Raid Panel'));
        }
        
        $Session = Gdn::Session();
        if ($Session->CheckPermission('Garden.Users.Edit')) {
            $Raiders = $this->ShadowModel->GetActiveCharacters();
      
            $this->Form->SetModel($this->ShadowModel);
      
            if($this->Form->AuthenticatedPostBack() === FALSE) {
                $this->Form->SetData($Raiders);
            } else {
                $FormValues = $this->Form->FormValues();
                $PersonChanging = $Session->UserID;
                $UserID = '';
                $Percent = 0.2;
            
                if (array_key_exists('Off_Spec', $FormValues)) {
                    $Result = $this->ShadowModel->Loot($FormValues, 0);
                    $Type = '0';
                } else if (array_key_exists('MAIN_SPEC', $FormValues)) {
                    $Result = $this->ShadowModel->Loot($FormValues, $Percent);
                    $Type = '0.2';
                }//if
                
                if ($Result !== FALSE)
                    Redirect('/raiding/finalize/'.$Type);
            }//if
        
            // Render the controller
            $this->Render();
        } else {
            Redirect('/discussions');
        }//if
    }
    
    public function Finalize($Type = '') {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Reputation->Finalize Loot'));
        }
        
        $Session = Gdn::Session();
        if ($Session->CheckPermission('Garden.Users.Edit')) {
            $Rolls = $this->ShadowModel->GetRolls();
      
            $this->Form->SetModel($this->ShadowModel);
      
            if($this->Form->AuthenticatedPostBack() === FALSE) {
                $this->Form->SetData($Rolls);
            } else {
                $FormValues = $this->Form->FormValues();
                
                if (array_key_exists('Reroll', $FormValues)) {
                    $Result = $this->ShadowModel->Roll($Type);
                } else if (array_key_exists('Save_Results', $FormValues)) {
                    $ItemLink = $FormValues['ItemLink'];
                    $Result = $this->ShadowModel->SaveLoot($ItemLink);
                    if ($Result !== FALSE)
                        Redirect('/raiding/loot');
                }//if
                if ($Result !== FALSE)
                    $this->StatusMessage = $Result;
            }//if
        
            // Render the controller
            $this->Render();
        } else {
            Redirect('/discussions');
        }//if
    }
}