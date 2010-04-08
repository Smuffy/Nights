<?php if (!defined('APPLICATION')) exit();

class ReputationController extends ReptasticController {
   
   public $Uses = array('Database', 'ShadowModel', 'UserModel', 'Form');
   
   public function Index() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }
      
      // Render the controller
      $this->Render();
   }
   
   public function Cloth() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }    
      
      // Render the controller
      $this->Render();
   }
   
   public function Leather() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }  
      
      // Render the controller
      $this->Render();
   }
   
   public function Mail() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }    
      
      // Render the controller
      $this->Render();
   }
   
   public function Plate() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }    
      
      // Render the controller
      $this->Render();
   }
   
   public function Conqueror() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }    
      
      // Render the controller
      $this->Render();
   }
   
   public function Protector() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }    
      
      // Render the controller
      $this->Render();
   }
   
   public function Vanquisher() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Shadow Reputation'));
      }    
      
      // Render the controller
      $this->Render();
   }
   
   public function Policy() {
      if ($this->Head) {
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
         $this->AddJsFile('options.js');
         $this->AddCssFile('reptastic.css');
         $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
         $this->Head->Title(Translate('Loot Policy'));
      }    
      
      // Render the controller
      $this->Render();
   }
   
   public function Playground() {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Reputation->Raid Panel'));
        }
        $this->Winner = '';
        
        $Session = Gdn::Session();
        if ($Session->IsValid()) {
            $Raiders = $this->ShadowModel->GetActiveCharacters();
      
            $this->Form->SetModel($this->ShadowModel);
      
            if($this->Form->AuthenticatedPostBack() === FALSE) {
                $this->Form->SetData($Raiders);
            } else {
                $FormValues = $this->Form->FormValues();
            
                if (array_key_exists('Roll', $FormValues)) {
                    $Result = $this->ShadowModel->TestLoot($FormValues, 0);
                } else if (array_key_exists('Re-roll', $FormValues)) {
                    $Result = $this->ShadowModel->TestLoot($FormValues, 0);
                }//if
                if ($Result !== FALSE)
                     $this->Winner = $Result;
            }//if
        
            // Render the controller
            $this->Render();
        } else {
            Redirect('/discussions');
        }//if
    }
   
   public function Shd_GetList($ArmorType = 'All') {
      $SQL = Gdn::SQL();
   
      $DataSet = $SQL->Select('Name, ShadowRep, RaiderRole, RaiderStatus, LastRaid, UserID')
        ->From('User')
        ->Where('RaiderStatus >', '0')
        ->Get();
      
      $UserList = '<ul class="Reputation">'; 
      $ThisWeek = date('W');
        
      foreach ($DataSet->Result() as $Shadow) {      
        $RaiderRole = $Shadow->RaiderRole;
        switch ($RaiderRole) {
            case 1:
                $TranslatedRole = 'plate';
                break;
            case 2:
                $TranslatedRole = 'cloth';
                break;
            case 3:
                $TranslatedRole = 'leather';
                break;
            case 4:
                $TranslatedRole = 'mail';
                break;
        }
        $LastRaid = $Shadow->LastRaid;
        if ($LastRaid == $ThisWeek) {
            $LastRaid = 'This week';
        } else if ($LastRaid < $ThisWeek) {
            if ($LastRaid == ($ThisWeek - 1)) {
                $LastRaid = 'Last week';
            } else {
                $LastRaid = "<span>Decaying</span>";
            }
        } else {
            $LastRaid = "<span>Decaying</span>";
        }
        
        if ($ArmorType == 'All' || $ArmorType == NULL) {
            $UserList .= '<li class="' . $TranslatedRole . '">' . '<h3>' . $Shadow->Name . '</h3>' . '<p>' . $Shadow->ShadowRep . '</p>' . '<small>' . $LastRaid . '</small></li>';
        } else if ($ArmorType == 'Cloth' && $TranslatedRole == 'cloth') {
            $UserList .= '<li class="' . $TranslatedRole . '">' . '<h3>' . $Shadow->Name . '</h3>' . '<p>' . $Shadow->ShadowRep . '</p>' . '<small>' . $LastRaid . '</small></li>';
        } else if ($ArmorType == 'Leather' && $TranslatedRole == 'leather') {
            $UserList .= '<li class="' . $TranslatedRole . '">' . '<h3>' . $Shadow->Name . '</h3>' . '<p>' . $Shadow->ShadowRep . '</p>' . '<small>' . $LastRaid . '</small></li>';
        } else if ($ArmorType == 'Mail' && $TranslatedRole == 'mail') {
            $UserList .= '<li class="' . $TranslatedRole . '">' . '<h3>' . $Shadow->Name . '</h3>' . '<p>' . $Shadow->ShadowRep . '</p>' . '<small>' . $LastRaid . '</small></li>';
        } else if ($ArmorType == 'Plate' && $TranslatedRole == 'plate') {
            $UserList .= '<li class="' . $TranslatedRole . '">' . '<h3>' . $Shadow->Name . '</h3>' . '<p>' . $Shadow->ShadowRep . '</p>' . '<small>' . $LastRaid . '</small></li>';
        }
      }
      
      $UserList .= '</ul>';
      
      echo $UserList;
   }

}