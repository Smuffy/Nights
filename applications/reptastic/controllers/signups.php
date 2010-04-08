<?php if (!defined('APPLICATION')) exit();

class SignupsController extends ReptasticController {
      
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
}