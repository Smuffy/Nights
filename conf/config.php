<?php if (!defined('APPLICATION')) exit();

// Conversations
$Configuration['Conversations']['Version'] = '1.0';

// Database
$Configuration['Database']['Name'] = 'db36834_nv2010';
$Configuration['Database']['Host'] = 'internal-db.s36834.gridserver.com';
$Configuration['Database']['User'] = 'db36834';
$Configuration['Database']['Password'] = 'susan2956';

// EnabledApplications
$Configuration['EnabledApplications']['Skeleton'] = 'skeleton';
$Configuration['EnabledApplications']['Vanilla'] = 'vanilla';
$Configuration['EnabledApplications']['Conversations'] = 'conversations';
$Configuration['EnabledApplications']['Reptastic'] = 'reptastic';

// EnabledPlugins
$Configuration['EnabledPlugins']['HTMLPurifier'] = 'HtmlPurifier';
$Configuration['EnabledPlugins']['GravityInsights'] = 'GravityInsights';
$Configuration['EnabledPlugins']['VanillaInThisDiscussion'] = 'VanillaInThisDiscussion';
$Configuration['EnabledPlugins']['WhosOnline'] = 'WhosOnline';
$Configuration['EnabledPlugins']['Gravatar'] = 'Gravatar';

// Garden
$Configuration['Garden']['Title'] = 'Nights';
$Configuration['Garden']['Cookie']['Salt'] = 'A1ZLG2LC8M';
$Configuration['Garden']['Cookie']['Domain'] = 'nightsed.com';
$Configuration['Garden']['Version'] = '1.0';
$Configuration['Garden']['WebRoot'] = '';
$Configuration['Garden']['RewriteUrls'] = TRUE;
$Configuration['Garden']['Domain'] = 'http://nightsed.com/';
$Configuration['Garden']['CanProcessImages'] = TRUE;
$Configuration['Garden']['Messages']['Cache'] = 'arr:["Garden\/Settings\/Index"]';
$Configuration['Garden']['Installed'] = TRUE;
$Configuration['Garden']['RequiredUpdates'] = 'arr:[]';
$Configuration['Garden']['UpdateCheckDate'] = '1270749652';
$Configuration['Garden']['Theme'] = 'shadow';
$Configuration['Garden']['Locale'] = 'en-CA';
$Configuration['Garden']['Email']['SupportName'] = 'Smuffy';
$Configuration['Garden']['Email']['SupportAddress'] = 'smuffy@nightsed.com';
$Configuration['Garden']['Email']['UseSmtp'] = FALSE;
$Configuration['Garden']['Email']['SmtpHost'] = '';
$Configuration['Garden']['Email']['SmtpUser'] = '';
$Configuration['Garden']['Email']['SmtpPassword'] = '';
$Configuration['Garden']['Email']['SmtpPort'] = '25';
$Configuration['Garden']['Registration']['Method'] = 'Basic';
$Configuration['Garden']['Registration']['DefaultRoles'] = 'arr:["256"]';
$Configuration['Garden']['Registration']['CaptchaPrivateKey'] = '';
$Configuration['Garden']['Registration']['CaptchaPublicKey'] = '';
$Configuration['Garden']['Registration']['InviteExpiration'] = '-1 week';
$Configuration['Garden']['Registration']['InviteRoles'] = 'arr:{"8":"0","16":"0","32":"0","256":"0"}';
$Configuration['Garden']['Errors']['MasterView'] = 'deverror.master.php';

// Plugins
$Configuration['Plugins']['GettingStarted']['Categories'] = '1';
$Configuration['Plugins']['GettingStarted']['Plugins'] = '1';

// Routes
$Configuration['Routes']['DefaultController'] = 'categories';
$Configuration['Routes']['categories/General'] = 'categories/1/general';
$Configuration['Routes']['categories/Applications'] = 'categories/2/applications';
$Configuration['Routes']['categories/The+War+Room'] = 'categories/3/the+war+room';
$Configuration['Routes']['categories/Members+Only'] = 'categories/4/members+only';
$Configuration['Routes']['categories/Council+Chambers'] = 'categories/5/council+chambers';
$Configuration['Routes']['discussions'] = 'discussions';
$Configuration['Routes']['discussions/0/1/general'] = 'categories/1/general';
$Configuration['Routes']['discussions/0/2/applications'] = 'categories/2/applications';
$Configuration['Routes']['discussions/0/3/the+war+room'] = 'categories/3/the+war+room';
$Configuration['Routes']['discussions/0/4/members+only'] = 'categories/4/members+only';
$Configuration['Routes']['discussions/0/5/council+chambers'] = 'categories/5/council+chambers';

// Skeleton
$Configuration['Skeleton']['Version'] = '1.0';

// Vanilla
$Configuration['Vanilla']['Version'] = '2.0';
$Configuration['Vanilla']['Discussions']['PerPage'] = '100';
$Configuration['Vanilla']['Discussions']['Home'] = 'categories';
$Configuration['Vanilla']['Comments']['AutoRefresh'] = '300';
$Configuration['Vanilla']['Comments']['PerPage'] = '100';
$Configuration['Vanilla']['Categories']['Use'] = TRUE;
$Configuration['Vanilla']['Discussion']['SpamCount'] = '3';
$Configuration['Vanilla']['Discussion']['SpamTime'] = '60';
$Configuration['Vanilla']['Discussion']['SpamLock'] = '120';
$Configuration['Vanilla']['Comment']['SpamCount'] = '5';
$Configuration['Vanilla']['Comment']['SpamTime'] = '60';
$Configuration['Vanilla']['Comment']['SpamLock'] = '120';
$Configuration['Vanilla']['Comment']['MaxLength'] = '20000';

// WhosOnline
$Configuration['WhosOnline']['Frequency'] = '15';
$Configuration['WhosOnline']['Location']['Show'] = 'discussion';

// Last edited by Smuffy 2010-04-08 12:01:42