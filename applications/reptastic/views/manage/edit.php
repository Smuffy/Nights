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
    <li class="Active"><a href="#">Edit</a></li>
    <li><a href="/manage/decay">Process Decay <?php echo $DecayBadge; ?></a></li>
    <li><a href="/manage/history">Loot History</a></li>
</ul>

<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<br />
<h2>Edit a Character</h2>
<ul class="EditCharacter">
   <li>
      <?php
         echo $this->Form->Label('Character', 'Name');
         echo $this->Form->TextBox('Name');
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Shadow Reputation', 'Reputation');
         echo $this->Form->TextBox('Reputation');
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('ReasonForChange', 'Reason');
         echo $this->Form->TextBox('Reason');
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Last Raid', 'LastRaid');
         echo $this->Form->TextBox('LastRaid');
      ?>
   </li>
   <li class="Checkboxes">
      <?php
         echo $this->Form->CheckBox('Status', "Character is ACTIVE?", array('value' => '1'));
      ?>
   </li>   
   <li class="Class">
      <?php
         echo $this->Form->Label('Class', 'Class'); ?>
      <div class="ClassList">
      <?php
         echo $this->Form->RadioList('Class', $this->ClassOptions)
      ?>
      </div>
   </li>
</ul>
<div class="Save">
<?php echo $this->Form->Close('Save', 'or <a href="/manage/characters">Cancel</a>'); ?>
</div>