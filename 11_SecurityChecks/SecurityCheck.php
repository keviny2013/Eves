<?php
/*
	- remove R***
	- check for @toDO 
	
	function __CONSTRUCT : Calls a few security check methods. If fails, each method ads to (array) $this->securityIssues.
	function checkIp()
	function checkCookies()
	function checkLocalStorage()
	function checkPackage()
	
	No permission is needed to run securtyCheck so checkPermissions() is not needed.
	
	function authenticate($acceptables)	: For login like situations only
	
	// helper functions
	function checkPregMatch($patterns, $datam)
	function securityFailed()
	
*/

// echo '<h5>CheckUser.php</h5>' ;


class SecurityCheck extends eVe
{
	// $permissionsRequired same as parent class
	// R***
	protected $testMe = 'off';
	protected $privates = array('objectName' => 'kevinxxx',  'parentObjectName' => '',  'permissionsRequired' => array('all' => array(), 'in' => array(), 'excluded' => array()), 'paramsRequired' => array('objectName', 'parentObject') );

	
	public function __CONSTRUCT($params)
	{

//R*** 
$_SESSION['caller'] = '';
// 
		$params = $this->checkParamsBasics($params);
		if(!$params )
		{
			$this->status = 'exit';
			$this->securityFailed();
			return false;
		}
		

// var_dump('<pre>43--SecurityCheck:', $this->privates);  exit;
		
		$this->checkIp();
		
		$this->checkCookies();
		$this->checkLocalStorage();		

		$this->checkPackage();
		
		if(count($this->securityIssues)) {
			$this->status = 'exit';
			$this->securityFailed();
			return false;			
		}

		
		/** 
		* @toDo  : permissionsProvided must come from database		
		* 7 types of permissions: CRUD (Create, Read, Update, Delete), 
		* 5- Grant (Setting permissions) for people groups, 
		* 6- Assign members to groups, 
		* 7- Assign contents to groups : (array) medical (article, image, video, etc), gastroentology, heart, sports, politics, NY, FL, TX, etc.
			So, permissionsProvided may have: (int) 'securityPass', (array) 'content', (array) 'people';
		* (array) 'people': anonymous,  underInvestigation, banned, registered, loggedIn, editors, webmaster, , businessOwner, businessTeam, developer, ITteam,etc.
		*/
		if(!$this->status) {		
			if(!defined('PERMISSIONS_PROVIDED')) 
			{
				$_SESSION['caller'] = array();
				$permissionsProvided = array();
				
				// R***
				$permissionsProvided['role'] = 'Editor';
				$permissionsProvided['userGroup'] = 'Greater DC';
				$permissionsProvided['contentGroup'] = 'Politics';
					// $permissionsProvided['Loggedin'] = 'Yes';	

				$permissionsProvided = json_encode($permissionsProvided);	
			
			}

			define('PERMISSIONS_PROVIDED', $permissionsProvided);
			
			$this->status = 'ok';			
		}		
		
		return true;
	
	}
	

	/**
		@toDo	:	check given IP against internal DB (and external sources)
	*/
	private  function checkIp()
	{
		if(!empty($_SESSION['caller']['ip'])) {
			if($_SESSION['caller']['ip'] != $_SERVER['REMOTE_ADDR']) {
				$this->securityIssues['IP issue'] = '$_SESSION[\'caller\'][\'ip\'] does not match $_SERVER[\'REMOTE_ADDR\']. 
				<br>  $_SESSION[\'caller\'][\'ip\']: ' . $_SESSION['caller']['ip'] . '
				<br>  $_SERVER[\'REMOTE_ADDR\']: ' . $_SERVER['REMOTE_ADDR'];
				
				return false;
			}
			
			return true;
			
		}
		else {
			$_SESSION['caller']['ip'] = $_SERVER['REMOTE_ADDR'];
		}
		
		// @toDo	:	do more, use a DB table, localstorages to track IPs
		

		return true;
	}
	
	/**
		check safety through cookies
	*/
	private  function checkCookies()
	{
		//*** remove this. 
		$cookies = $_COOKIE;
		
		//	@toDo	:	write code for checkCookies()
		
		// $this->securityIssues[] = ''; 
		
		return true;
	}

	/**
		check safety through local storage
	*/
	private  function checkLocalStorage()
	{

		//	@toDo	:	write code for checkLocalStorage()
		
		// $this->securityIssues[] = '';
		
		return true;
	}

	/**
	*	checks $_GET, $_POST for security purposes
	*	$_GET is not allowed 
	*	page requests may come in URL as a foldername or in POST as pageName
	*/
	private function checkPackage()
	{
		// @toDO :  ifGet
		
		$this->isPageRequest();
		
		if(!empty($_POST)) {
			$this->ifPost();
		}

		// @toDO : internal request
		
		if(empty($this->metam['moduleName'])) {
			$this->metam['requestName'] = 'page_first';
			$this->metam['moduleName'] = 'homePage';			
			$this->metam['formatType'] = 'Html_2D';
		}
		

		return true;
		
	}
	
	
	/**
		check safety through local storage
	*/
	private function authenticate($acceptables)
	{
		// $this->metam['caller']['loginname'] = 
	
		// password check for login 
		require_once(DOCROOT . '/others/system/configForOfficer.php');
		// require_once(DOCROOT . '/others/system/SqliMyOfficer.php');
		
		$qry = "select count(*) from `users` u join `passes` p on p.`userid` = u.`id`
			WHERE (u.`mn` = '" . $acceptables['loginname'] . "') and  (p.`password` = '" . $acceptables['password'] . "')";
		
		$con = mysqli_connect("localhost",  securityofficer,  officerspass, "weeklyzip");
		if (mysqli_connect_errno()) {
		  return false;
		}
		
		$result = mysqli_query($con, $qry);
		if (!$result) {
		  return false;
		}
		
		$rows = mysqli_fetch_array($result);
		if(!empty($rows[0]) && $rows[0] == 1) {
			return true;
		}
		else {
			return false;
		}
		
	}


	/**
	*	@explanation :	checks size and characters 
	*	returns (array) $checked which includes $checked['acceptables'] and $checked['unacceptables']
	*
	*/
	protected function checkPregMatch($patterns, $datam) 
	{
		$checked = array();
		$checked['unacceptables'] = array();
		$checked['acceptables'] = array();
		$inputs = array();
		$cinput = '';
		$oks = array();
		foreach($patterns as $fieldName => $pattern) {
			if($pattern) {
				$inputs = $datam[$fieldName];
				if(is_array($inputs)) {
					$numRows = count($inputs);
					for($i = 0; $i < $numRows; $i++) {
						$cinput = $inputs[$i];
						if (!preg_match('/' . $pattern . '/i', $cinput) ) {
							if(empty($cinput)) {
								$checked['unacceptables'][$fieldName][] = 'Empty field';
							}
							else {
								$checked['unacceptables'][$fieldName][] = 'Illegal characters: ' . $cinput;
							}						
						} elseif($cinput) { 
							$checked['acceptables'][$fieldName] = $cinput;
						}		
					}
				}
				else {
					$cinput = $inputs;
					if (!preg_match('/' . $pattern . '/i', $cinput) ) {
						if(empty($cinput)) {
							$checked['unacceptables'][$fieldName] = 'Empty field';
						}
						else {
							$checked['unacceptables'][$fieldName] = 'Illegal characters: ' . $cinput;
						}						
					} elseif($cinput) { 
						$checked['acceptables'][$fieldName] = $cinput;
					}	
				}
			}
		}
			
		return $checked;
		
	}

		/** 
		* 
		* 	Check if URL includes any folder
		*	check request name is safe
		*	Check if file to check security for this request exists
		*	Get $pregCheckGet from check security file for this request
		*	Run security check on incoming $_GET datam using $pregCheckGet
		*
		*/
	private  function isPageRequest()
	{
		// var_dump('get: ', $_GET); exit;


		// preventing accessing subfolders but getting page name if exists
		if($_SERVER["REDIRECT_URL"] != "/2015/eVe/") {
			$len = strlen(PATH2HOME);
			$folder = substr($_SERVER["REDIRECT_URL"], $len);
			$keys = explode('/', $folder);
			// $keys = array('/', '{page name}');
			$countKeys = count($keys);
			if($countKeys == 2) {
 				if(!preg_match('/^\w+[\w|\n]*$/', $keys[1])) {	
					$this->securityIssues['illegal REDIRECT_URL'] = $_SERVER["REDIRECT_URL"];
				}
				else {
					/*
					*	Each page must be a seperate module. Otherwise just use ajax requests.
					*	Each module does not have to be a page.
					*/
					$fileName = DOCROOT . '/21_ProcessingRequests/11_Modules/' . $keys[1] .  '/' . $keys[1] . '.php';
// echo '<br>fileName1: ' . $fileName1;
					if(!file_exists($fileName1)) {
						$this->securityIssues['illegal REDIRECT_URL'] = $_SERVER["REDIRECT_URL"];			
						return false;			
					}
					else {					
						$this->metam['moduleName'] = $keys[1];					
						$this->metam['pageName'] = $keys[1];
						$this->metam['requestName'] = '';
						$this->metam['requestParameters'] = array();	
					}
				}
			}
			elseif($countKeys > 2) {
				$this->securityIssues['illegal REDIRECT_URL'] = $_SERVER["REDIRECT_URL"];
			}
		}
		
		if(!empty($_GET)) {
			$this->securityIssues['illegal GET'] = 	'<br><pre>' . var_export($_GET, true) . '</pre>';			
		}
		
		$_GET = array();
	
	}


		/**
		*  All POST requests are ajax requests sending json string
		*	check if request name is safe
		*	Check if file to check security exists
		*	Get $pregCheck from check security file for this request
		*	Run security check on incoming $_POST datam using $pregCheck
		*/
	private  function ifPost()
	{
		/**
		*  Decoding JSON datam and resetting $_POST
		*  @Warning: Technology might change about detecting ajax calls
		*	These a few lines below depend on this fact on July 2015:
		*	If it is an ajax call, $_POST will have only 1 element. key => value like this: 'json string' => ''
		*/
		if(count($_POST) == 1 && empty($_POST['request'])) {
			$keys = array_keys($_POST);
			$key0 = $keys[0];
			$postdatam = json_decode($key0, TRUE);
			$_POST = $postdatam;				
		}
			
		if(!empty($_POST['request'])) {
			if(!preg_match('/^\w+[\w|\n_]*$/', $_POST['request'])) {
				$this->securityIssues['request'] = 'Illegal POST request 104: ' . '<br><pre>' . var_export($_GET, true) . '</pre>';		
				return false;
			}
			$securityFile = DOCROOT . '/11_SecurityChecks/Requests/' . $_POST['request'] . '.php';
// echo '<br>securityFile: ' . $securityFile;			
			if(!file_exists($securityFile)) {
				$this->securityIssues['validation file'] = 'Validation file to check security does not exists. 114: ' . $securityFile;		
				return false;			
			}
		}
		else {
			$this->securityIssues['request'] = '$_POST[\'request\'] is empty: <pre>' . var_export($_POST, true) . '</pre>';				
			return false;
		}
		
		// validation and security check on incoming datam package
		require_once($securityFile);
		// File ($securityFile) must include these parameters. Parameters maybe empty.
		$this->metam['requestName'] = $_POST['request'];
		$this->metam['moduleName'] = $metam['moduleName'];
		$this->metam['pageName'] = $metam['pageName'];
		$this->metam['methodName'] = $metam['methodName'];		
		
		$requestProcessor = DOCROOT . '/21_ProcessingRequests/11_Modules/' . $this->metam['moduleName'] . '/' . $this->metam['pageName'] . '.php';
		if(!file_exists($requestProcessor)) {
			$this->securityIssues['requestProcessor'] = 'File to process request does not exists. 45: ' . $requestProcessor;		
			return false;
		}

		if(empty($metam)) {
			$this->issues['missing $metam'] = '$metam in request security check file is missing.';
			return false;
		}
		if(empty($this->metam)) {
			$$this->metam = $metam;
		}
		else {
			$merged = array_merge($metam, $this->metam);
			$this->metam = $merged;
		}
/*	
echo '<br>mainFile4request: ' . $this->metam['mainFile4request'];		
echo '<br>formatType: ' . $metam['formatType'];
echo '<br>this formatType: ' . $this->metam['formatType'];
exit;
*/

		
		$this->metam['permissionsRequired'] = $permissionsRequired;
		// calling eVe function
		$this->checkPermissions();
	
		$checked = array();
		if(!empty($pregCheck)) {
			$checked = $this->checkPregMatch($pregCheck, $incomingPackage);
			if(!empty($checked['unacceptables'])) {
				$this->status = 'Suspicious';
				$this->metam['toDeveloper']['checkPregMatch()'] = 'unacceptables';		
				$this->metam['toCaller']['unacceptables'] = $checked['unacceptables'];			
				return false;
			}
		}
		
		// @toDo : Step 4: Customized requests like login authentication  $toDo
			if($this->metam['request'] == 'authenticate') {
			$loggedin = $this->authenticate($checked['acceptables']);
			$checked['acceptables']['password'] = 'REMOVED';
			$checked['acceptables']['token'] = 'REMOVED';
			if(!$loggedin) {
				$this->status = 'NotAuthorized';
				$this->metam['toDeveloper']['authenticate'] = 'Credentials don\'t match.';		
				$this->addtoCaller('Authentication', 'failed');				
				return false;
			}
			$this->metam['caller']['loginname'] = $checked['acceptables']['loginname'];
			//  @toDo:	get groups and permissions of the caller
			// $this->metam['caller']['permissions'] = '';
			
			$_SESSION['caller'] = $this->metam['caller'];
			
		}
		
		 
		//	@toDo :	write token generating and checking code
		
		if(!empty($checked['acceptables'])) {
			$this->datam = $checked['acceptables'];
		}

		$_POST = array();
	
	
	}

	
	
}
