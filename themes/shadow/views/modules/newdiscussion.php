<?php if (!defined('APPLICATION')) exit();
echo Anchor(Gdn::Translate('Start a New Discussion'), '/post/discussion'.(array_key_exists('CategoryID', $Data) ? '/'.$Data['CategoryID'] : ''), 'NewDiscussion');