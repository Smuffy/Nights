<?php if (!defined('APPLICATION')) exit();

class HistoryController extends ReptasticController {
   
    public $Uses = array('ShadowModel', 'Form');
   
    public function Index($UserReference = '') {
        if ($this->Head) {
            $this->AddJsFile('discussions.js');
            $this->AddJsFile('bookmark.js');
            $this->AddJsFile('options.js');
            $this->AddCssFile('reptastic.css');
            $this->Head->AddRss('/rss/'.$this->SelfUrl, $this->Head->Title());
            $this->Head->Title(Translate('Loot History'));
        }//if
        
        $this->Profile = $UserReference;
        $this->Search = $UserReference;
                   
        if($this->Form->AuthenticatedPostBack() === FALSE) {
            //do something
        } else {
            $FormValues = $this->Form->FormValues();
            
            foreach($FormValues as $Key => $Value) {
                if ($Key === "Name") {
                    $this->Search = $Value;
                }//if
            }//foreach
        }//if
          
        // Render the controller
        $this->Render();
    }
}