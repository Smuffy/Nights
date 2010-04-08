<?php if (!defined('APPLICATION')) exit(); ?>

<h2>Raid Signups</h2>

<?php
    echo $this->Form->Open();
    echo $this->Form->Errors();
?>

<?php echo $this->ShadowModel->SignedUp('Tuesday', 1); ?>