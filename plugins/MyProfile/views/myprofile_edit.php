<?php if (!defined('APPLICATION')) exit(); ?>
<div class="MyProfile"><?php

// Initialize the Form
echo $this->Form->Open();
echo $this->Form->Errors();

// Display the form
?>
<h2>General Information</h2>
<table>
	<tr>
		<td>Real Name:</td>
		<td><?php echo $this->Form->TextBox('RealName');?></td>
	</tr>
	<tr>
		<td>Location:</td>
		<td><?php echo $this->Form->TextBox('Location');?></td>
	</tr>
	<tr>
		<td>Occupation:</td>
		<td><?php echo $this->Form->TextBox('Occupation'); ?></td>
	</tr>
</table>
<br>
<h2>About Me</h2>
<?php echo $this->Form->TextBox('AboutMe', array('MultiLine' => true, 
						'rows'=>'40')) ?>
<br>
<?php 

// Close the form
echo $this->Form->Close('Save'); 

?></div>
