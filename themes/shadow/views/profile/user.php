<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
    if($this->ShadowModel->IsSignedUp($this->User->UserID) > 0) {
        $Class = '';
    } else {
        $Class = ' None';
    }//if
    
    $SignUp = '<span class="SignUp'.$Class.'">Raids: '.$this->ShadowModel->IsSignedUp($this->User->UserID).' signups</span>';
?>
<div class="User">
   <h1 class="Capitalize"><?php echo $this->User->Name; ?>
   <?php if($this->ShadowModel->Exists($this->User->UserID)) {?>
      <strong class="Badge <?php echo $this->ShadowModel->GetClass($this->User->UserID); ?>"><?php 
        if($this->ShadowModel->GetStatus($this->User->UserID) === '1') {
            echo $this->ShadowModel->GetReputation($this->User->UserID);
        } else {
            echo 'Inactive';
        }//if
      ?></strong> 
   <?php } ?>
   <?php 
      if ($Session->UserID == $this->User->UserID || $Session->UserID == 1) {
          echo $this->ShadowModel->GetDecayStatus($this->User->UserID); 
      }//end if
   ?>
   <?php 
      if($this->ShadowModel->Exists($this->User->UserID)) {   
          if ($this->ShadowModel->GetStatus($this->User->UserID) === '1') { 
            echo $SignUp; 
          }//if
      }//if
   ?>
   </h1>
   <?php
      if ($this->User->About != '') {
         echo '<div id="Status">'.Format::Display($this->User->About);
         if ($Session->UserID == $this->User->UserID && $this->User->About != '')
            echo ' - ' . Anchor('Clear', '/profile/clear/'.$Session->TransientKey());
            
         echo '</div>';
      }
   ?>
</div>