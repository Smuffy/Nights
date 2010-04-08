<?php if (!defined('APPLICATION')) exit(); ?>
<?php 
    $DecayBadge = '';
    if ($this->ShadowModel->IsDecayReady() === 'True')
        $DecayBadge = $this->ShadowModel->GetDecayNumber();
?>

<h2>Manage Reputation</h2>
<ul class="Tabs">
    <li><a href="/reptastic/manage">Activity</a></li>
    <li><a href="/manage/characters">All Characters</a></li>
    <li class="Active"><a href="/manage/decay">Process Decay <?php echo $DecayBadge; ?></a></li>
    <li><a href="/manage/history">Loot History</a></li>
</ul>

<?php
    echo $this->Form->Open();
    echo $this->Form->Errors();
?>
<ul class="Activities">
<?php $DataSet = $this->ShadowModel->GetDecayList(); foreach($DataSet->Result() as $Shadow) { ?>
    <li class="Activity">
        <div>
            <?php
                $Decay = floor($Shadow->Reputation * 0.25);
            ?>
            <strong><?php echo $Shadow->Name; ?></strong>
            <div class="Meta">Decay: <strong><?php echo $Decay; ?></strong> Last raided: <a>Week <?php echo $Shadow->LastRaid; ?></a></div>
            <a href="/manage/edit/<?php echo $Shadow->UserID; ?>" class="Button">Edit</a>
        </div>
    </li>       
<?php } ?>
</ul>
<div class="clearfix"></div>

<?php 
    if($this->ShadowModel->IsDecayReady() === 'True') { 
        echo $this->Form->Close('Process Decay'); 
    } else {
        echo '<p>Decay has been processed for this period.</p>';
    }//endif
?>
<br /><br />
<h2>Warning List</h2>
<ul class="Activities">
<?php $WarnList = $this->ShadowModel->GetWarnList(); foreach($WarnList->Result() as $Warn) { ?>
    <li class="Activity">
        <div>
        <strong><?php echo $Warn->Name; ?></strong>
        <div class="Meta">Last raided: <a>Week <?php echo $Warn->LastRaid; ?></a></div>
        <a href="/manage/edit/<?php echo $Warn->UserID; ?>" class="Button">Edit</a>
        </div>
    </li>
<?php } ?>
</ul>