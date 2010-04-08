<?php if (!defined('APPLICATION')) exit(); ?>

<style>
<!--

.myprofile_field_left {
	padding: 0px 10px 0px 0px;
}

-->
</style>


<div class="MyProfile"><?php
// If no profile, tell them so and don't attempt to display.
if(empty($this->MyProfile)) {
	echo "Your profile hasn't been set up yet!";
} else {
	?> <br>

<table>
	<tr>
		<td class="myprofile_field_left">Real Name:</td>
		<td><b><?php echo $this->MyProfile->RealName ?></b></td>
	</tr>
	<tr>
		<td class="myprofile_field_left">Location:</td>
		<td><b><?php echo $this->MyProfile->Location ?></b></td>
	</tr>
	<tr>
		<td class="myprofile_field_left">Occupation:</td>
		<td><b><?php echo $this->MyProfile->Occupation ?></b></td>
	</tr>
</table>
<br>
<?php if(!empty($this->MyProfile->AboutMe)) { ?>
<h2>About Me</h2>
<p><?php 

		// Poor man's HTML formatting - any newlines are replaced with <br>s for display.
		// There must be a better way to do this.
        $AboutMe = str_replace("\n", "<br/>",$this->MyProfile->AboutMe);

echo $AboutMe ?>

<?php
}
}

// If we are looking at our own page, or are an admin who can edit this user, show the link to
// edit this profile.
$Session = Gdn::Session();
if($Session->UserID == $this->id || $Session->CheckPermission('Garden.Users.Edit')) {
?>


<br><br>
<p><?php echo Anchor('Edit My Profile', "profile/myprofile/edit/". $this->id . "/".$this->name); 

}
?>


</div>
