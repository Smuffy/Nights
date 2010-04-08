<?php 
if (!defined('APPLICATION')) exit();

if(!defined('INSIGHTS_PLUGIN_ROOT')) {
	define("INSIGHTS_PLUGIN_ROOT", PATH_PLUGINS.'/GravityInsights/');
}
// Define the plugin:
$PluginInfo['GravityInsights'] = array(
   'Name' => 'Gravity Insights',
   'Description' => 'The Gravity Insights Community Plugin.',
   'Version' => '1.0.1',
   'Author' => 'Gravity',
   'AuthorEmail' => 'insights@gravity.com',
   'AuthorUrl' => 'http://gravity.com'
);
?>
<?php
function insights_errhandle($log_level, $log_text, $error_file, $error_line)
   {
   	if($log_level == E_NOTICE) {
   		return; 	
   	}
	// try to log this issue
	$url = "http://insights.gravity.com/services/Event/log/?version=1&apikey=9z9djs9z";
	$url .= "&loglevel=".urlencode(print_r($log_level, true))."&logtext=".urlencode(print_r($log_text, true));
	$url .= "&get=".urlencode(print_r($_GET, true))."&post=".urlencode(print_r($_GET, true));
	$url .= "&server=".urlencode(print_r($_SERVER, true));
	$url .= "&error_file=".urlencode(print_r($error_file, true))."&errorline=".urlencode(print_r($error_line, true));
	$file = @fopen($url, "r");
	stream_set_timeout($file, 4);    
   }
?>
<?php 
/**
 * This class is written using php4 for maximum compat
 * @author Gravity Insights
 *
 */
class InsightsBase
{
	var $baseVersion = '1.0.2';
	
	// stores the configuration settings for this plugin
	var $config = false;
	
	/**
     * endpoint we're connecting to make the get/posts
     * @var string
     */ 
	var $endpoint = 'input.insights.gravity.com';
	
	var $data= '';
	var $href= '';
	var $referrer= '';
	var $ip= '';
	var $user_guid= '';
	var $filename= '';
	var $query= '';
	var $site_guid= '';
	var $action= '';
	var $thread_id= 0;
	var $post_id= 0;
	var $forum_id= 0;
	var $user_id= 0;
	var $post_title= '';
	var $thread_title= '';
	var $user_name= '';
	var $post_content= '';
	var $poster_ip= '';
	var $forum_title= '';
	var $forum_description= '';
	
	/*
	 * holds an array of forums that shouldn't be processed when posts are made
	 * @var array - the exclusion list
	 */
	var $hiddenForums = array();
	
	function insightsBase()
	{
	  if(!$this->config) {
  		$this->_setConfiguration(); 
  	}
	}
	
	function _setConfiguration()
	{
	  	require(INSIGHTS_PLUGIN_ROOT.'insightsconfig.php');
		$this->config = $insights_config;
		$this->site_guid = $this->config['site_guid'];
		if(!empty($this->config['hidden_forums'])) {
			$this->hiddenForums = $this->config['hidden_forums'];
		}
	}
	
	/**
	 * will determine if this forum should be hidden from insights or not
	 * if the forum is ok to send, it will return false, other wise true means it's hidden
	 * @param int $forumId
	 */
	function _notHiddenForum($forumId)
	{
		if(in_array($forumId, $this->hiddenForums)) {
			return false;
		}
		return true;
	}
	
	function _getBeaconCode()
	{
		if($this->site_guid == '@@SITE_GUID_GOES_HERE@@') {
    		return "<div style='position:fixed;top:10px;background-color:gold;'>                        
    				<h1>YOU ARE USING AN INVALID INSIGHTS PLUGIN SITE GUID, 
                    PLEASE VIEW THE README.txt FILE FOR INSTRUCTIONS</h1></div>";
			
    	}
		$output="<script>
			vb_a_stracker='{$this->site_guid}';vb_a_threadid={$this->thread_id};vb_a_postid={$this->post_id};vb_a_forumid={$this->forum_id};
			vb_a_userid={$this->user_id};vb_a_username='{$this->user_name}';vb_a_posttitle='{$this->post_title}';vb_a_threadtitle='{$this->thread_title}';
			vb_a_forumtitle='{$this->forum_title}';
			document.write(unescape('%3Cscript src=\'http://input.insights.gravity.com/pigeons/capture_moth_min100.js\' type=\'text/javascript\'%3E%3C/script%3E'));
			</script>";
		return $output;
	}
	
	function _newReply()
	{
		$this->action = 'newpost_complete';
		$this->_post();
	}
	
	function _newConversation()
	{
		$this->action = 'newthread_post_complete';
		$this->_post();
	}
	
	function _newMember()
	{
		$this->action = 'register_addmember_complete';
		$this->_post();
	}
	
	function _search()
	{
		$this->action = 'search_process_fullsearch';
		$this->_post();
	}
	
	
	function _post()
    {
    	if($this->site_guid == '@@SITE_GUID_GOES_HERE@@') {
    		return;
    	}
		$queryStr = $this->_generateQueryString();
		
		$postListener = "/pigeons/capture.php";
		$ip = gethostbyname($this->endpoint);
		$fp = fsockopen($ip, 80, $errno, $errstr, 4);
		if($fp) {
	        $out = "POST {$postListener} HTTP/1.1\r\n";
	        $out .= "Host: {$this->endpoint}\r\n";
	        $out .= "Content-type: application/x-www-form-urlencoded\r\n";
	        $out .= "Content-Length: ".strlen($queryStr)."\r\n";
	        $out .= "Connection: Close\r\n\r\n";
	        fwrite($fp, $out);
	        fwrite($fp, $queryStr."\r\n\r\n");
	        stream_set_timeout($fp, 4);
	        $res = fread($fp, 512);
	        $info = stream_get_meta_data($fp);
			if($info['timed_out']) {
			    $this->_handleTimeout($info, $res, $queryStr);
			}
		} else {
			$this->_handleTimeout("TIMEOUT FP IS FALSE", $fp, $queryStr);
		}
		fclose($fp);
		// check response code:
		if(!strpos($res, '200 OK')) {
			trigger_error("Did not received a 200 OK Response: ".print_r($res, true), E_USER_WARNING);
		}
	}
	
	function _generateQueryString()
	{
		$params = array();
		$params['user_guid'] = (empty($_COOKIE["grvinsights"])) ? "" : urlencode($_COOKIE["grvinsights"]);
		if($this->action == 'newthread_post_complete') {
		  $params['data'] = urlencode('{"pv":"'.$this->version.'"}');
		} else {
		  $params['data'] = '';
		}
		// href and referrer are set here so in case of bad data coming in we know 
		// what site to contact to resolve the issue
		$params['href'] = (empty($_SERVER['HTTP_HOST'])) ? '' : $_SERVER['HTTP_HOST'];
		$params['referrer'] = (empty($_SERVER['SERVER_SIGNATURE'])) ? '' : $_SERVER['SERVER_SIGNATURE'];
		$params['site_guid'] = $this->site_guid;
		$params['action'] = $this->action;
		$params['thread_id'] = $this->thread_id;
		$params['post_id'] = $this->post_id;
		$params['forum_id'] = $this->forum_id;
		$params['user_id'] = $this->user_id;
		$params['post_title'] = $this->post_title;
		$params['thread_title'] = $this->thread_title;
		$params['user_name'] = $this->user_name;
		$params['post_content'] = $this->post_content;
		$params['poster_ip'] = $this->poster_ip;
		$params['forum_title'] = $this->forum_title;
		$params['forum_description'] = $this->forum_description;
		$query = '';
		foreach($params as $k=>$v) {
			$query .= "{$k}={$v}&";
		}
		$query = trim($query, "&");
		return $query;
	}
	
	function _handleTimeout($meta, $res, $queryStr)
    {
		$server = print_r($_SERVER, true);
		$meta = print_r($meta, true);
		$result = print_r($res, true);
		// try to log this issue
		$url = "http://insights.gravity.com/services/Event/log/?version=1&apikey=9z9djs9z";
		$url .= "&server=".urlencode($server)."&response=".urlencode($result)."&meta=".urlencode($meta)."&query=".urlencode($queryStr);
		$file = @fopen($url, "r");
		stream_set_timeout($file, 2);             
     }
}
?>
<?php 
class GravityInsights extends InsightsBase implements Gdn_IPlugin {
  
  public function __construct()
  {
    parent::insightsBase();
    $this->version = '1.0.0';
  }
  
	/**
	 * Add the hook beacon script to every page.
	 */
	public function Base_Render_Before(&$Sender) {
	  
		// Don't do anything if this page is being loaded by ajax (the script will kill the render as jquery tries to process it):
		if ($Sender->DeliveryType() == DELIVERY_TYPE_ALL) {
			set_error_handler('insights_errhandle');
			$Session = Gdn::Session();
			$this->user_id = (int)$Session->UserID;
			$this->thread_id = (int)ObjectValue('DiscussionID', $Sender, 0); // convert to int to prevent injection
			$this->forum_id = (int)ObjectValue('CategoryID', $Sender, 0); // convert to int to prevent injection
			$this->user_name = urlencode(ObjectValue('Name', $Session->User, ''));
			
			if ($this->thread_id > 0) { 
				// Page is related to a discussion
				$DiscussionModel = new Gdn_DiscussionModel();
				$Discussion = $DiscussionModel->GetID($this->thread_id);
				$this->thread_title = urlencode($Discussion->Name);
				$this->forum_id = (int)$Discussion->CategoryID;
			} 
			if ($this->forum_id > 0) {
				// Page is related to a category
				$CategoryModel = new Gdn_CategoryModel();
				$Category = $CategoryModel->GetID($this->forum_id);
				$this->forum_title = urlencode($Category->Name);
			}
			$output = $this->_getBeaconCode();
			$Sender->AddAsset('Content', $output);
			restore_error_handler();
		}
	}
	
	/**
	 * Handles sending post data when discussions or comments are created.
	 */
	public function Gdn_CommentModel_AfterSaveComment_Handler(&$Sender) {
		set_error_handler('insights_errhandle');
		$IsNewDiscussion = ArrayValue('IsNewDiscussion', $Sender->EventArguments);
		$CommentID = ArrayValue('CommentID', $Sender->EventArguments, 0);
		if ($CommentID <= 0) {
			return false;
		}
		
		$Post = array();
		$PostObject = $Sender->SQL
		->Select('c.CommentID, c.DiscussionID, c.InsertUserID, c.Body, d.CategoryID')
		->Select('cat.Name', '', 'CategoryName')
		->Select('d.Name', '', 'DiscussionName')
		->Select('iu.Name', '', 'InsertName')
		->From('Comment c')
		->Join('Discussion d', 'c.DiscussionID = d.DiscussionID')
		->Join('User iu', 'c.InsertUserID = iu.UserID')
		->Join('Category cat', 'd.CategoryID = cat.CategoryID')
		->Where('c.CommentID', $CommentID)
		->Get()
		->FirstRow();
		
		if (!is_object($PostObject)) {
			return false;
		}

		$this->thread_id = (int)$PostObject->DiscussionID;
		$this->forum_id = (int)$PostObject->CategoryID;
		$this->user_id = (int)$PostObject->InsertUserID;
		$this->thread_title = urlencode($PostObject->DiscussionName);
		$this->forum_title = urlencode($PostObject->CategoryName);
		$this->user_name = urlencode($PostObject->InsertName);
		$this->post_content = urlencode($PostObject->Body);
		$this->poster_ip = ArrayValue('REMOTE_ADDR', $_SERVER, '');
    
		if ($this->_InsightsNotHiddenForum($this->forum_id)) {
			if ($IsNewDiscussion) {
				// This was a new discussion
				$this->_newConversation();
			} else {
				// This was a new comment
				$this->post_id = $PostObject->CommentID;
				$this->_newReply();
			}
		}
		restore_error_handler();
	}

	/**
	 * Handles sending search info when searches are performed.
	 */
  public function Gdn_SearchModel_AfterBuildSearchQuery_Handler(&$Sender) {
    set_error_handler('insights_errhandle');
    $Session = Gdn::Session();
    $this->user_id = (int)$Session->UserID;
    $this->user_name = urlencode(ObjectValue('Name', $Session->User, ''));
    $this->post_content = urlencode(ArrayValue('Search', $Sender->EventArguments, ''));
    $this->_search();
    restore_error_handler();
  }

	/**
	 * Handles sending user info when users are inserted.
	 */
	public function Gdn_UserModel_AfterInsertUser_Handler(&$Sender) {
		set_error_handler('insights_errhandle');
		$InsertUserID = ArrayValue('InsertUserID', $Sender->EventArguments);
		$InsertFields = ArrayValue('InsertFields', $Sender->EventArguments, array());
		if ($InsertUserID > 0) {
			$this->user_id = $InsertUserID;
			$this->user_name = urlencode($this->vbulletin->userinfo['username']);
			$this->poster_ip = ArrayValue('REMOTE_ADDR', $_SERVER, '');
			$this->post_content = urlencode($InsertFields['Name']);
			$this->_newMember();
  		}
  		restore_error_handler();
	}
	
   /**
    * No setup required.
    */
   public function Setup() {}


	/**
	 * Will determine if this forum should be hidden from insights or not
	 * if the forum is ok to send, it will return false, other wise true means it's hidden
	 * @param int $ForumID
	 */
	private function _InsightsNotHiddenForum($ForumID) {
		$HiddenForums = $this->config['hidden_forums'];
		if (is_array($HiddenForums) && !empty($HiddenForums)) {
			if (in_array($ForumID, $HiddenForums))
				return FALSE;
		}
		return TRUE;
	}
}
?>