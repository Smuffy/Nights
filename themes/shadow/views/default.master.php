<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
   <?php $this->RenderAsset('Head'); ?>
   <script src="http://static.wowhead.com/widgets/power.js" type="text/javascript"></script>
</head>
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
   <div id="Frame">
      <div id="Head">
         <div class="Menu">
				<h1><a class="Title" href="<?php echo Url('/'); ?>"><span><?php echo Gdn::Config('Garden.Title', 'Vanilla'); ?></span></a></h1>
            <?php
				
			      $Session = Gdn::Session();
					if ($this->Menu) {
						$this->Menu->AddLink('Dashboard', Gdn::Translate('Dashboard'), '/garden/settings', array('Garden.Settings.Manage'));
						$this->Menu->AddLink('Dashboard', Gdn::Translate('Users'), '/user/browse', array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete'));
						$this->Menu->AddLink('Activity', Gdn::Translate('Activity'), '/activity');
			         $Authenticator = Gdn::Authenticator();
						if ($Session->IsValid()) {
							$Name = $Session->User->Name;
							$CountNotifications = $Session->User->CountNotifications;
							if (is_numeric($CountNotifications) && $CountNotifications > 0)
								$Name .= '<span>'.$CountNotifications.'</span>';
								
							$this->Menu->AddLink('User', $Name, '/profile/{UserID}/{Username}', array('Garden.SignIn.Allow'));
							$this->Menu->AddLink('SignOut', Gdn::Translate('Sign Out'), $Authenticator->SignOutUrl(), FALSE, array('class' => 'NonTab SignOut'));
						} else {
							$this->Menu->AddLink('Entry', Gdn::Translate('Sign In'), $Authenticator->SignInUrl($this->SelfUrl), FALSE, array('class' => 'NonTab'), array('class' => 'SignInPopup'));
						}
						echo $this->Menu->ToString();
					}
				?>
            <div id="Search"><?php
					$Form = Gdn::Factory('Form');
					$Form->InputPrefix = '';
					echo 
						$Form->Open(array('action' => Url('/search'), 'method' => 'get')),
						$Form->TextBox('Search'),
						$Form->Button('Go', array('Name' => '')),
						$Form->Close();
				?></div>
         </div>
      </div>
      <div id="Body">
         <div id="Content"><?php $this->RenderAsset('Content'); ?></div>
         <div id="Panel"><?php $this->RenderAsset('Panel'); ?></div>
      </div>
      <div id="Foot">
			<div><?php
				printf(Gdn::Translate('Powered by %s'), '<a href="http://vanillaforums.org"><span>Vanilla</span></a>/<a href="http://vanillaforums.org"><span>Garden</span></a>');
			?></div>
		</div>
   </div>
	<?php $this->FireEvent('AfterBody'); ?>
</body>
</html>