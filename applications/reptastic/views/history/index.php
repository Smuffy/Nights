<?php if (!defined('APPLICATION')) exit(); 
    $Filter = $this->Search;
    $Profile = $this->Profile;
?>
<script src="http://static.wowhead.com/widgets/power.js"></script>

<?php if($Profile === '') { ?>
<h2><a href="/reputation">Shadow Reputation</a> \ Loot History</h2>

<?php
    echo $this->Form->Open();
    echo $this->Form->Errors();
?>
<h2 class="Center">
<?php
    echo $this->Form->TextBox('Name', array('value' =>'Search', 'onclick' => "javascript:this.value = '';"));
    echo $this->Form->Close('Search');
?></h2>

<?php } ?>
<ul class="Activities">
    <?php 
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
                    Others: <?php echo $Loot->Rollers; ?>
                </div>
                <?php } ?>
            </div>      
        </div>
    </li>
    <?php } ?>
</ul>