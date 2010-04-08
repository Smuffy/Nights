<?php if (!defined('APPLICATION')) exit(); ?>

<h2><a href="/raiding">Start a Raid</a> \ <a href="/raiding/panel">Raid Management</a> \ Add Raiders</h2>

<?php
    echo $this->Form->Open();
    echo $this->Form->Errors();
?>
<ul class="Activities">
<?php $DataSet = $this->ShadowModel->GetNotRaiding(); foreach($DataSet->Result() as $Shadow) { ?>
    <li class="Activity List">
        <h3 class="<?php echo $Shadow->Class; ?>"><?php echo $this->Form->CheckBox('User'.$Shadow->UserID, $Shadow->Name, array('value' => $Shadow->UserID)); ?></h3>
    </li>        
<?php } ?>
</ul>
<div class="clearfix"></div>
<div class="Center">
<?php
    echo $this->Form->Close('Add');
?>
</div>