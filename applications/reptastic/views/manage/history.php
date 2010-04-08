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
    <li><a href="/manage/decay">Process Decay<?php echo $DecayBadge; ?></a></li>
    <li class="Active"><a href="/manage/history">Loot History</a></li>
</ul>

<ul class="Activities">
    <?php 
        $Filter = $this->Search;
        $DataSet = $this->ShadowModel->GetLootHistory($Filter);
        foreach($DataSet as $Loot) {
            $Name = $this->ShadowModel->GetName($Loot->UserID);
            if ($Name === '')
                $Name = $Loot->CharacterName;
    ?>
    <li class="Activity NoHover">
        <div>
            <a href="/profile/<?php echo $Loot->UserID; ?>/<?php echo $Loot->CharacterName; ?>" class="Capitalize"><?php echo $Name; ?></a> won <a href="<?php echo $Loot->ItemLink; ?>" class="epic"><?php echo $Loot->ItemName; ?></a>
            <div class="Meta">
                &nbsp; &nbsp; <?php echo $Loot->RollDate; ?> &nbsp; &nbsp; Item Cost: <u><?php echo $Loot->ItemCost; ?></u>
                <?php if ($Loot->Rollers !== '') { ?>
                <div class="Story Capitalize">
                    Others who rolled: <?php echo $Loot->Rollers; ?>
                </div>
                <?php } ?>
            </div>      
        </div>
    </li>
    <?php } ?>
</ul>