<?php if (!defined('APPLICATION')) exit(); ?>

<h2><a href="/raiding">Start a Raid</a> \ <a href="/raiding/panel">Raid Administration</a> \ <a href="/raiding/loot">Loot Panel</a> \ Finalize Results</h2>

<?php
    $Session = Gdn::Session();
    echo $this->Form->Open();
    echo $this->Form->Errors();
?>
<ul class="Activities">
    <?php $DataSet = $this->ShadowModel->GetRolls(); $Number = 1; foreach($DataSet->Result() as $Roll) { ?>
    <li class="Activity<?php if($Roll->Roll === '1') { echo ' Winner'; } ?><?php if ($Roll->Weight === '1') { echo ' Favored'; } ?>">
        <?php echo $Number; $Number++; ?>. <strong class="Title"><?php echo $this->ShadowModel->GetName($Roll->UserID); ?></strong> 
        <div class="Meta">
            <strong class="Badge <?php echo $this->ShadowModel->GetClass($Roll->UserID); ?>"><?php echo $this->ShadowModel->GetReputation($Roll->UserID); ?></strong> &nbsp; &nbsp; Weight: <strong><?php echo $Roll->Weight; ?></strong> &nbsp; &nbsp; <u><?php echo $Roll->Chance; ?>%</u> &nbsp; &nbsp;
            <?php if($Roll->Roll === '1') { 
                $Reputation = ($this->ShadowModel->GetReputation($Roll->UserID)) - $Roll->Cost;
            ?>
            <div class="Story">
            <?php if ($Roll->Cost === '0') { ?>
                <textarea onclick="this.focus();this.select();" class="Script">/script SendChatMessage("<?php echo strtoupper($this->ShadowModel->GetName($Roll->UserID)); ?> is the winner! *** Please make your payment to the repair officer before the end of the raid. ***", "RAID_WARNING");</textarea>                    
            <?php } else { ?>
                <textarea onclick="this.focus();this.select();" class="Script">/script SendChatMessage("<?php echo strtoupper($this->ShadowModel->GetName($Roll->UserID)); ?> is the winner! Item cost: <?php echo $Roll->Cost; ?>. Reputation total: <?php echo $Reputation; ?>.", "RAID_WARNING");</textarea>
            <?php } ?>
            </div>
            <div class="Extras">
                <a href="http://www.wowhead.com/?search=<?php echo str_replace(' ', '+', $Roll->ItemName); ?>" target="_blank"><?php echo $Roll->ItemName; ?></a>
            </div>
            <?php } ?>
        </div>
    </li>
    <?php } ?>
</ul>
<div class="Right">
<?php 
    if ($Session->UserID === '1') 
        echo $this->Form->Button('Reroll', array('class' => 'Button FloatLeft'));
?>
<?php
    echo $this->Form->TextBox('ItemLink');
    echo $this->Form->Close('Save Results');
?>
</div>
<div class="clearfix"></div>