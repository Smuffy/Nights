<?php if (!defined('APPLICATION')) exit(); ?>
<?php 
    $DecayBadge = '';
    if ($this->ShadowModel->IsDecayReady() === 'True')
        $DecayBadge = $this->ShadowModel->GetDecayNumber();
?>

<h2>Manage Reputation</h2>
<ul class="Tabs">
    <li><a href="/reptastic/manage">Activity</a></li>
    <li><a href="/manage/characters">All Characters</a> &raquo; </li>
    <li class="Active"><a href="#">Add</a></li>
    <li><a href="/manage/decay">Process Decay<?php echo $DecayBadge; ?></a></li>
    <li><a href="/manage/history">Loot History</a></li>
</ul>
<ul class="Reputation">
<?php $DataSet = $this->ShadowModel->GetNewAccounts(); foreach($DataSet as $Key => $Shadow) { ?>
    <li class="inactive">
        <h3 class="inactive"><a href="/profile/<?php echo $Key; ?>/<?php echo $Shadow; ?>" target="_blank" title="<?php echo $Shadow; ?>"><?php echo substr($Shadow, 0, 11); ?></a></h3> 
        <small>
            <a href="/manage/add/<?php echo $Key; ?>" class="Popable Button">Add</a>
        </small>
    </li>        
<?php } ?>
</ul>