<?php if (!defined('APPLICATION')) exit(); ?>

<h2><a href="/raiding">Start a Raid</a> \ Raid Administration</h2>

<ul class="Activities">
<?php 
    $DataSet = $this->ShadowModel->GetRaid();
    $Number = 1; 
    foreach($DataSet->Result() as $Shadow) { ?>
    <li class="Activity">
        <?php echo $Number; $Number++; ?>. <strong><?php echo $Shadow->Name; ?></strong> 
        <div class="Meta Raider">
            <strong class="Badge <?php echo $Shadow->Class; ?>"><?php echo $Shadow->Reputation; ?></strong> &nbsp; &nbsp; Added: <strong><?php echo $Shadow->Added; ?></strong> &nbsp; &nbsp; <u><?php echo $this->ShadowModel->GetTardy($Shadow->UserID); ?></u>
        </div>
        <div class="Extras">
            <?php echo $this->Form->Open(); ?>
            <?php echo $this->Form->Errors(); ?>
            <?php echo $this->Form->Hidden('UserID', array('value' => $Shadow->UserID)); ?>
            <?php echo $this->Form->DropDown('Tardy', $this->TardyOptions, array('class' => 'Tardy')); ?>
            <?php echo $this->Form->Close('Save'); ?>
        </div>
    </li>        
<?php
    } 
?>
</ul>
<div class="clearfix"></div>
<div class="Center Administration">
<form method="post" action="/raiding/remove">
<input type="submit" value="Remove Raiders" class="Button" />
</form>
<form method="post" action="/raiding/add" class="PadRight">
<input type="submit" value="Add Raiders" class="Button" />
</form>

<form method="post" action="/raiding/loot" class="PadRight">
<input type="submit" value="Loot Panel" class="Button" />
</form>

<?php 
    echo $this->Form->Open();
    echo $this->Form->Close('Attendance');
?>
<?php 
    echo $this->Form->Open();
    echo $this->Form->Close('Finish');
?>
<?php 
    echo $this->Form->Open(array('class' => 'PadRight'));
    echo $this->Form->Close('Progression');
?>

<?php 
    echo $this->Form->Open();
    echo $this->Form->Close('Signup Bonus');
?>
</div>