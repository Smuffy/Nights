<?php if (!defined('APPLICATION')) exit(); ?>
<?php 
    $DecayBadge = '';
    if ($this->ShadowModel->IsDecayReady() === 'True')
        $DecayBadge = $this->ShadowModel->GetDecayNumber();
?>

<h2>Manage Reputation</h2>
<ul class="Tabs">
    <li class="Active"><a href="/reptastic/manage">Activity</a></li>
    <li><a href="/manage/characters">All Characters</a></li>
    <li><a href="/manage/decay">Process Decay <?php echo $DecayBadge; ?></a></li>
    <li><a href="/manage/history">Loot History</a></li>
</ul>

<ul class="Activities">
<?php $Session = Gdn::Session(); if ($Session->UserID === '1') { ?>
<?php echo $this->ShadowModel->GetLog('history'); ?>
<?php } else { ?>
<li class="Activity">You are not authorized to view activity.</li>
<?php } ?>
</ul>