<?php if (!defined('APPLICATION')) exit(); $Session = Gdn::Session(); ?>

<h2>Shadow Reputation</h2>
<div id="Status">
    <p>Below is an up-to-date list of Shadow Reputation by <em>main character</em>. You can sort by armor type using the tabs.</p>
</div>
<ul class="Tabs">
    <li><a href="/reputation">All Characters</a></li>
    <li>&raquo;</li>
    <li><a href="/reputation/cloth">Cloth</a></li>
    <li class="Active"><a href="/reputation/leather">Leather</a></li>
    <li><a href="/reputation/mail">Mail</a></li>
    <li><a href="/reputation/plate">Plate</a></li>
    <li>&raquo;</li>
    <li><a href="/reputation/conqueror">Conqueror</a></li>
    <li><a href="/reputation/protector">Protector</a></li>
    <li><a href="/reputation/vanquisher">Vanquisher</a></li>
</ul>

<ul class="Reputation">
<?php $DataSet = $this->ShadowModel->GetActiveCharacters(); foreach($DataSet->Result() as $Shadow) { 
    
    $ThisWeek = date('W');
    $LastRaid = $Shadow->LastRaid;
    
    if($LastRaid == $ThisWeek) {
        $LastRaid = 'This week';    
    } else if ($LastRaid == ($ThisWeek - 1)) {
        $LastRaid = '<span class="Yellow">Last week</span>';
    } else if ($LastRaid == ($ThisWeek - 2)) {
        $LastRaid = '<span class="Orange">Two weeks ago</span>';
    } else {
        $LastRaid = '<span class="Red">Decaying</span>';
    }
    
    $Class = $Shadow->Class;
    
    if($Class == 'Druid' || $Class == 'Rogue') { ?>
    <li class="<?php echo $Shadow->Class; ?><?php if($Shadow->UserID === $Session->UserID) { echo ' MyCharacter'; } ?>">
        <h3><?php echo $Shadow->Name; ?></h3>
        <p><?php echo $Shadow->Reputation; ?></p>
        <small><?php echo $LastRaid; ?></small>
    </li>        
<?php }} ?>
</ul>