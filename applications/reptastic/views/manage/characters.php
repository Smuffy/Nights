<?php if (!defined('APPLICATION')) exit(); ?>
<?php 
    $DecayBadge = '';
    if ($this->ShadowModel->IsDecayReady() === 'True')
        $DecayBadge = $this->ShadowModel->GetDecayNumber();
?>
<script type="text/javascript">
    $('#Inactive').hide();
</script>

<h2>Manage Reputation</h2>
<ul class="Tabs">
    <li><a href="/reptastic/manage">Activity</a></li>
    <li class="Active"><a href="/manage/characters">All Characters</a></li>
    <li><a href="/manage/decay">Process Decay<?php echo $DecayBadge; ?></a></li>
    <li><a href="/manage/history">Loot History</a></li>
</ul>

<ul class="Reputation">
<?php $DataSet = $this->ShadowModel->GetActiveCharacters(); foreach($DataSet->Result() as $Shadow) { 
    
    $ThisWeek = date('W');
    $LastRaid = $Shadow->LastRaid;
    
    if($LastRaid == $ThisWeek) {
        $LastRaid = 'This week';    
    } else if ($LastRaid == ($ThisWeek - 1)) {
        $LastRaid = 'Last week';
    } else {
        $LastRaid = '<span class="Red">Decaying</span>';
    } ?>
    <li class="inactive">
        <h3 class="<?php echo $Shadow->Class; ?>"><?php echo $Shadow->Name; ?></h3>
        <small><a href="/manage/edit/<?php echo $Shadow->UserID; ?>" class="Popable Button">Edit</a></small>
    </li>        
<?php } ?>
</ul>
<div class="clearfix"></div>
<br />
<div class="Center"><a href="/manage/add" class="Button">+ Add Character</a></div>
<br />
<div id="Inactive">
<h2>Inactive Characters</h2>
<ul class="Reputation">
<?php $DataSet = $this->ShadowModel->GetAllCharacters(); foreach($DataSet->Result() as $Shadow) { 
    
    $ThisWeek = date('W');
    $LastRaid = $Shadow->LastRaid;
    
    if($LastRaid == $ThisWeek) {
        $LastRaid = 'This week';    
    } else if ($LastRaid == ($ThisWeek - 1)) {
        $LastRaid = 'Last week';
    } else {
        $LastRaid = '<span class="Red">Decaying</span>';
    } 
    
    $Status = $Shadow->Status; 
    
    if($Status == 0) {?>
    <li class="inactive">
        <h3 class="inactive"><?php echo $Shadow->Name; ?></h3>
        <small><form action="/manage/edit/<?php echo $Shadow->UserID; ?>" method="get"><input type="Submit" value="Edit" class="Button" /></form></small>
    </li>        
<?php }} ?>
</ul>
</div>