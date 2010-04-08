    _____   _______ ____________  _____________
   /  _/ | / / ___//  _/ ____/ / / /_  __/ ___/
   / //  |/ /\__ \ / // / __/ /_/ / / /  \__ \ 
 _/ // /|  /___/ // // /_/ / __  / / /  ___/ / 
/___/_/ |_//____/___/\____/_/ /_/ /_/  /____/  
                                               
------------------------------- by GRAVITY ----

Welcome to Gravity Insights Community Analytics Plugin for Vanilla Forums 2. 
Please follow the instructions outlined below to activate your plugin. 

For additional features or support please email us anytime at insights@gravity.com


INSTALLATION ----------------------------------- 

1. Upload the GravityInsights directory to your webroot/plugins directory. After the upload it should look like: webroot/plugins/GravityInsights

2. Access your admin panel (settings). 
	* On the left hand side click on AddOns -> Plugins 
	* You should now see Gravity Insights in your plugin list
	* Click on the Enable link next to Gravity Insights to enable it
	* It should import properly, once it's enabled you're done. You
	  may now log into your insights account (please note it may be a day before your account is fully up and running with every feature)
	
	
	
FAQ --------------------------------------------

Q: How to I exclude certain forums or categories I don't want reports on?
A: In the GravityInsights/ directory there is a insightsconfig.php file. In there you'll see a line for 
$config['$hidden_forums'] = array();
You can put in an array of category ids you don't want data captured for. Once you save that file and re-upload it those forums will be ignored by the plugin.

Q: How do I uninstall the plugin?
A: Just click Disable in your plugin manager page, then remove the GravityInsights directory from your webroot/plugins directory. All clean.
