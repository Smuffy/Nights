<?php
// Define the plugin:
$PluginInfo['MyProfile'] = array(
   'Description' => 'Manage guild characters and alts.',
   'Version' => '0.1',
   'Author' => "Smuffy",
   'AuthorEmail' => 'smuffy@nightsed.com',
   'AuthorUrl' => 'http://nightsed.com',
   'HasLocale' => TRUE, // Does this plugin have any locale definitions?

);

class MyProfile implements Gdn_IPlugin {

	public $MyProfileModel;

	/**
	 * Add the 'MyProfile' tab to the profile.
	 *
	 * @param unknown_type $Sender
	 */
	public function ProfileController_AddProfileTabs_handler(&$Sender) {
		$Sender->AddProfileTab(Translate("Character"), "/profile/myprofile/view/".$Sender->User->UserID."/".Format::Url($Sender->User->Name));
		$Sender->AddProfileTab(Translate("CharacterEdit"), "/profile/myprofile/edit/".$Sender->User->UserID."/".Format::Url($Sender->User->Name));
	}

	/**
	 * Edit or view controller method...
	 *
	 * @param unknown_type $Sender
	 * @param unknown_type $params - three elements - ['edit' or 'view' | user_id | user_name]
	 */
	public function ProfileController_MyProfile_Create($Sender, $params) {
		$command = $params[0];
		$Sender->id = $params[1];
		$Sender->name = $params[2];
		
		$MyProfileModel = new Gdn_Model('MyProfile');
		$MyProfileData = $MyProfileModel->GetWhere(array('UserID' => $Sender->id));
		$MyProfile = $MyProfileData->FirstRow();

		$Sender->MyProfile = $MyProfile;
		// If command is view, then simply go to that view
		if($command == 'view') {
			$Sender->View = PATH_PLUGINS.DS.'MyProfile'.DS.'views'.DS.'myprofile_view.php';
		} else if($command == 'edit') {
			$Sender->Form->SetModel($MyProfileModel);
			// If the form has NOT been posted back, then show the edit form
			if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
				if(!empty($Sender->MyProfile)) {
					$Sender->Form->SetData($Sender->MyProfile);
				}
				$Sender->View = PATH_PLUGINS.DS.'MyProfile'.DS.'views'.DS.'myprofile_edit.php';
			} else {
				// Save the form
				if(!empty($Sender->MyProfile)) {
					$Sender->Form->SetFormValue('ProfileID', $Sender->MyProfile->ProfileID);
				}
				$Sender->Form->SetFormValue('UserID', $Sender->id);
				
				// Attempt to save the form values
				$UserID = $Sender->Form->Save();
//				error_log("UserID: $UserID");
				// If it saved, redirect to the new entry:
				if ($UserID !== FALSE)
					Redirect("/profile/$Sender->id/$Sender->name");

			}
		}
		$Sender->Render();
	}

	/**
	 *
	 * This method creates the database table needed with the correct fields.
	 * These fields will be used in the model automatically, so therefore these
	 * are the fields you need to change for your website. 
	 *
	 */
	public function Setup(){
		$Structure = Gdn::Structure();
      
      // Construct the user comment table.
      $Structure->Table('MyProfile')
      	 ->PrimaryKey('ProfileID')
         ->Column('UserID', 'int', FALSE, 'primary')
         ->Column('RealName', 'varchar(256)', FALSE)
         ->Column('Location', 'varchar(64)', FALSE)
         ->Column('Occupation', 'varchar(64)', FALSE)
         ->Column('AboutMe', 'varchar(4096)', TRUE)
         ->Set(FALSE, FALSE);
	}
}

Gdn::FactoryInstall('MyProfileModel','MyProfileModel',
PATH_PLUGINS.DS.'MyProfile'.DS.'class.myprofilemodel.php',
Gdn::FactoryInstance,NULL,FALSE);


?>
