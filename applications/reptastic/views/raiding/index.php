<?php if (!defined('APPLICATION')) exit(); ?>

<?php
    echo $this->Form->Open();
    echo $this->Form->Errors();
    
    $Today = date('l');
    if ($Today === 'Wednesday') {
        $Day = $Today;
    } else {
        $Day = 'Tuesday';
    }//if
?>

<h2>Start a Raid</h2>
<ul class="Activities">
<?php if($this->ShadowModel->SignedUp($Day, 1) === 'Error') { 
    $DataSet = $this->ShadowModel->GetActiveCharacters(); foreach($DataSet->Result() as $Shadow) { ?>
        <li class="Activity List">
            <h3 class="<?php echo $Shadow->Class; ?>"><?php echo $this->Form->CheckBox('User'.$Shadow->UserID, $Shadow->Name, array('value' => $Shadow->UserID)); ?></h3>
        </li>        
    <?php } ?>
<?php } else { ?>
    <?php $DataSet = $this->ShadowModel->GetActiveCharacters(); foreach($DataSet->Result() as $Shadow) { ?>
        <li class="Activity List">
        <?php if ($this->ShadowModel->SignedUp($Day, $Shadow->UserID) === 'True') { ?>
            <h3 class="<?php echo $Shadow->Class; ?>"><?php echo $this->Form->CheckBox('User'.$Shadow->UserID, $Shadow->Name, array('value' => $Shadow->UserID, 'checked' => 'checked')); ?></h3>
        <?php } else { ?>
            <h3 class="<?php echo $Shadow->Class; ?>"><?php echo $this->Form->CheckBox('User'.$Shadow->UserID, $Shadow->Name, array('value' => $Shadow->UserID)); ?></h3>
        <?php } ?>
        </li>        
    <?php } ?>
<?php } ?>
</ul>
<div class="clearfix"></div>
<div class="Center">
<?php
    echo $this->Form->Close('Start Raid');
?>
</div>