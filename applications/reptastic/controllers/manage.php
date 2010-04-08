<?php if (!defined('APPLICATION')) exit();

class ManageController extends ReptasticController {
      
   public $Uses = array('Database', 'ShadowModel', 'Gdn_ActivityModel', 'Form', 'Session');
   
   public $Name;
   
   /**
    * A boolean value indicating if discussion options should be displayed when
    * rendering the discussion view.
    *
    * @var boolean
    */
   
   public function Index() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->AddJsFile('activity.js');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Manage Reputation -> Activity'));
      }   
         
      $Session = Gdn::Session();
      if ($Session->CheckPermission('Garden.Users.Edit')) {
        // Render the controller
        $this->Render();
      } else {
        Redirect('/discussions');
      }//if
   }
   
   public function Characters() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Manage Reputation -> Characters'));
      }
      
      $Session = Gdn::Session();
      if ($Session->CheckPermission('Garden.Users.Edit')) {
          // Render the controller
          $this->Render();
      } else {
          Redirect('/discussions');
      }//if
   }
   
   public function History() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Manage Reputation -> History'));
      }     
      
      $this->Search = '';
      
      $Session = Gdn::Session();
      if ($Session->CheckPermission('Garden.Users.Edit')) {
          // Render the controller
          $this->Render();
      } else {
          Redirect('/discussions');
      }//if
   }
   
   public function Edit($UserReference = '') {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Manage Reputation -> History'));
      }     
      
      $Session = Gdn::Session();
      if ($Session->CheckPermission('Garden.Users.Edit')) {
          $ShadowModel = new Gdn_Model('Shadow');
          $ShadowData = $ShadowModel->GetWhere(array('UserID' => $UserReference))->FirstRow();
      
          $this->Form->SetModel($ShadowModel);
          $this->Form->AddHidden('UserID', $UserReference);
      
          // Define class dropdown options
          $this->ClassOptions = array(
             'Death Knight' => Gdn::Translate('DK'),
             'Druid' => Gdn::Translate('Druid'),
             'Hunter' => Gdn::Translate('Hunter'),
             'Mage' => Gdn::Translate('Mage'),
             'Paladin' => Gdn::Translate('Paladin'),
             'Priest' => Gdn::Translate('Priest'),
             'Rogue' => Gdn::Translate('Rogue'),
             'Shaman' => Gdn::Translate('Shaman'),
             'Warlock' => Gdn::Translate('Warlock'),
             'Warrior' => Gdn::Translate('Warrior')
          );
      
          if ($this->Form->AuthenticatedPostBack() === FALSE) {
            $this->Form->SetData($ShadowData);
          } else {
            $PersonChanging = $Session->UserID;
            $PersonChanged = $this->Form->FormValues();
                $PersonsID = $PersonChanged['UserID'];
        
            $FormerReputation = $this->ShadowModel->GetReputation($PersonsID);
            $Form = $this->Form->FormValues();
                $NewReputation = $Form['Reputation'];
            $Delta = $NewReputation - $FormerReputation;
        
            if ($Form['Reason'] !== '') {
                $Reason = 'Reason for change: '.$Form['Reason'];
            } else {
                $Reason = ' ';
            }
        
            //If Reputation changes, add activity
            if ($Delta !== 0) {      
                if ($Delta > 0) {
                    $TheChange = '+'.$Delta.' Shadow Reputation. '.$Reason;
                } else {
                    $TheChange = $Delta.' Shadow Reputation. '.$Reason;
                }//if
                //Add the notification to the Activity ticker.
                AddActivity($PersonChanging, 'ReputationChange', $TheChange, $PersonsID, '');
                
                //Log change
                $this->ShadowModel->ChangeLog($PersonsID, $Form['Reason']);
            }//if
        
            $FormerStatus = $this->ShadowModel->GetStatus($PersonsID);
            $NewStatus = $Form['Status'];
            //If Status changes, add activity
            if ($FormerStatus > $NewStatus) {
                //Add the notification to the Activity ticker.
                AddActivity($PersonChanging, 'DeactiveRaider', '', $PersonsID, '');
            } else if ($FormerStatus < $NewStatus) {
                //Add the notification to the Activity ticker.
                AddActivity($PersonChanging, 'ActiveRaider', '', $PersonsID, '');
            }//if
        
            $CharacterName = $this->ShadowModel->GetName($PersonsID);
            $OldClass = $this->ShadowModel->GetClass($PersonsID);
            $NewName = $Form['Name'];
            $NewClass = $Form['Class'];
            //If Name changes, add activity
            if ($CharacterName !== $NewName) {
                //Add the notification to the Activity ticker.
                $NameStory = 'New character name: '.$NewName.' <small>('.$NewClass.')</small>.';
                AddActivity($PersonChanging, 'MainCharacter', $NameStory, $PersonsID, '');
            } else if ($NewClass !== $OldClass) {
                //Add the notification to the Activity ticker.
                $NameStory = 'New class: <strong>'.$NewClass.'</strong>.';
                AddActivity($PersonsID, 'NewClass', $NameStory, $PersonsID, '');
            }//if
        
            $UserID = $this->Form->Save();
        
            if ($UserID !== FALSE)
                Redirect('/manage/characters');
          }//if
      
          // Render the controller
          $this->Render();
       } else {
          Redirect('/discussions');
       }//if
   }
   
   public function Add($UserReference = '') {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Manage Reputation -> Add Character'));
      } 
      
      $Session = Gdn::Session();
      if ($Session->CheckPermission('Garden.Users.Edit')) {          
          if ($UserReference !== '') {
            $UserID = $this->ShadowModel->AddCharacter($UserReference);
            $this->StatusMessage = $UserID; 
          }//if
      
          // Render the controller
          $this->Render();
       } else {
          Redirect('/discussions');
       }//if
    }
   
   public function Decay() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Manage Reputation -> Process Decay'));
      } 
      
      $Session = Gdn::Session();
      if ($Session->CheckPermission('Garden.Users.Edit')) {
          if ($this->Form->AuthenticatedPostBack() === FALSE) {
            //Do something
          } else {
            $UserID = $this->ShadowModel->ProcessDecay(); 
            $this->StatusMessage = $UserID;
          }//if
          // Render the controller
          $this->Render();
       } else {
          Redirect('/discussions');
       }//if
    }
}























