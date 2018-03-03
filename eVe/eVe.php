<?php

namespace eVe;
use eVe\eVe_Abstract;
use eVe\Encrypt3;
use eVe\DecryptAFile3;


abstract class eVe 
{
	protected const ENV = 'development';
	protected const OS = 'Windows';
	protected const DOCROOT = 'C:/WebProjects/2017/eVeCurrent/level1/';
	protected const WEBROOT = 'http://localhost:8021/';
	
	// @toDo : confidentials must be handled in better way
	protected const  CONFIDENTIALS = array('logKey' => 'PpaaSswOrd');
	
	// objectName is also args required but not included in argsRequired to prevent duplicate check
	private $privates = array(
		'objectName' => ''
		,  'parentObjectName' => ''	
		, 'child1Args' => array()
		,  'permissionsRequired' => array()		
		, 'PASSPORT' => NULL
		
		, 'argsRequired' => array(
			   'objectName' => array(
					'method' => 'v_FileName'
					, 'pattern' => ''
				)
			   , 'parentObjectName' => array(
					'method' => 'v_FileName'
					, 'pattern' => ''
				)				
			)
			
		,	'argsOptional' => array()

		, 'metaIn' => array()
		, 'dataIn' => array()
		, 'metaOut' => array()
		, 'dataOut' => array()

	);
	
	protected $lastPrivates;
	
	// fatalIssue : system and security issue which ends with no response to caller
	protected $protecteds = array(
		'fatalIssue' => ''
		, 'eVeTracks' => array() 
		, 'securityIssues' => array()
		, 'toCaller' => array('Application started')	
		, 'addCssCode' => array(), 'addCssFiles' => array()
		, 'addJsCode' => array(), 'addJsFiles' => array()
		
	);


	public $publics = array();

	static $statics = array(
		'objectsStarted' => array()
		, 'endOfConstructor' => array()
		, 'endOfDestructor' => array()
	); 
	

	public function __CONSTRUCT($params) {

		// 1- validate object name
		if(empty($params['objectName'])) {
			$this->operationFailed('Missing object name', 'unsafe');
			return false;		
		}
		
		$this->set_lastPrivates();
		$this->lastPrivates['objectName'] = $params['objectName'];
		
		// require_once('validationTools.php');
		
		if(!self::v_FileName($params['objectName'])) {
			$this->operationFailed('Invalid object name: ' . $params['objectName'], 'unsafe');
			return false;	
		}
		
		// 2- register object for debugging purpose
		eVe::$statics['objectsStarted'][] = array($params['objectName'], date('U'));
		
		// 3- validate required arguments
		if(self::v_Args($this->lastPrivates['argsRequired'], $this->lastPrivates['argsOptional'], $params) !== TRUE) {
			$this->operationFailed('failed v_Args()', 'unsafe');
			return false;
		}
		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = 'passed v_Args()';
		
		$this->set_privates($params);
		$params = array();

		// fatal issues should call $this->operationFailed() immediately where it is happened
		$this->child1();

		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = 'eVe __CONSTRUCT completed.' ;
		
		eVe::$statics['endOfConstructor'][] = array($this->lastPrivates['objectName'], date("U"));
		
		return true;
	
	}





	protected function child1() 
	{

		return true;
	}

	 
	//---------------------- get properties starts ------------------------------

	public function get_fatalIssue() {

		return $this->protecteds['fatalIssue'];		
	}

	public function get_toCaller() {
		if(self::ENV === 'development') {
			return array_merge($this->protecteds['toCaller'], $this->get_eVeTracks(), $this->get_passport());
		}
		else {
			return array($this->protecteds['toCaller'], 'ToCaller:');
		}
		
		return true;		
	}
	
	protected function get_result() {
		return array('metaOut' => $this->lastPrivates['metaOut']
			, 'dataOut' => $this->lastPrivates['dataOut']
		);
		
	}	
	
	// IMPORTANT: copy this to each child class
	protected function set_lastPrivates() {
		$this->lastPrivates = $this->privates;
	
		return true;
	}

	// IMPORTANT: copy this to each child class	
	protected function set_privates($params) {
		if(empty($params)) {
			return true;
		}
		
		foreach($this->lastPrivates as $key => $val) {
			if(isset($params[$key])) {
				$this->lastPrivates[$key] = $params[$key];
			}
		}
		
		$this->privates = $this->lastPrivates;
		
		return true;
		
	}
	
	
	protected function get_confidentials() {
		return $this->confidentials;
	}

	protected function get_eVeTracks() {
		return $this->protecteds['eVeTracks'];		
	}


	//---------------------- end: get properties  ----------------------------
	

	//---------------------  start: errors and debugging  --------------------


	/**
	*	fatalIssue: 
			If "unsafe" or "system error" no response must be sent to Caller.
			So, leave fatalIssue blank if issue is not fatal 
			
			In all other cases: fatalIssue must be blank BUT toCaller may tell the Caller what went wrong
	*/
	protected function operationFailed($issue, $fatalIssue)
	{
		if($fatalIssue) {
			$this->protecteds['fatalIssue'] = $fatalIssue;
			$this->protecteds['toCaller'][] = 'Error: Sorry an issue occurred in the system.';
		}

		if(!empty($this->lastPrivates['objectName'])) {
			$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = $issue;
		}
		else {
			$this->protecteds['eVeTracks']['Class: ' . get_class($this)][] = $issue;
		}

			
		// @toDo : Save suscpicious activities so caller and IP can be trackable.
		
		return true;
	}
	
	protected function addtoCaller($key = 'Error', $message = 'Program was failed!')
	{
		$this->protecteds['toCaller'][$key] = $message;	
	}

	
	public function __DESTRUCT()
	{
		
		// This prevents endless loop : ( So, same object name cannot be used twice )
		if(in_array($this->lastPrivates['objectName'], eVe::$statics['endOfDestructor'])) 
		{
			return true;
		}  
		eVe::$statics['endOfDestructor'][] = $this->lastPrivates['objectName'];
		
		$write = 11;
	
		if(!empty($this->protecteds['fatalIssue']) )
		{	
			$write = 21;
			$toDeveloper0 = array(
				'source' => '__DESTRUCT',
				'time' => date('Ymd-His') . ' ' . date('U'),
				'PHP_SELF' => $_SERVER['PHP_SELF'],
				'_FILE_' =>  __FILE__,
				'_LINE_' => __LINE__,
				'__CLASS__' => __CLASS__,
				'get_class()' => get_class(),				
				'__FUNCTION__' => __FUNCTION__,					
				'get_class(\$this)' => get_class($this),
				'objectName' => $this->lastPrivates['objectName'],
				'parentObjectName' => $this->lastPrivates['parentObjectName'],
			);
			
			$toDeveloper1 = array($this->protecteds['eVeTracks'], $toDeveloper0);

			// writing to toDeveloper log
			$write = $this->writeToLogFile('toDeveloper', $toDeveloper1);
			
			if($write === false) {
				echo '<h4>Error 529: Please report to admin: '  . date('Ymd-His') . '</h4>';	
			}
				
			// debugging
			if(self::ENV === 'development')
			{
				if($write === false)
				{
					$this->debugging_writeFailed('toDeveloper', $toDeveloper1);
				}				
				$write = 31;			
				$backtrace0 = array(
					'time' => date('Ymd-His') . ' ' . date('U'),
					'source' => 'debug_backtrace() at __DESTRUCT', 
					'get_class(\$this)' => get_class($this),
					'get_class()' => get_class()
				);
				$backtrace = array($backtrace0, debug_backtrace());

				// writing debugging details / backtrace
				$write = $this->writeToLogFile('debugger', $backtrace);
				if($write === false) {
					$this->debugging_writeFailed('debugger', $backtrace);	
				}
			}
			
			echo '<h4>Error 521: Please report to admin: ' . date('Ymd-His') . '</h4>';

		}
		
	}
	

	// @toDo	move this to folder around: Format Html2D section 
	protected function writeToLogFile($fileName, $datam)
	{
		if(empty($fileName) || empty($datam) )
		{
			return false;
		}
		$logFileIssue = '';
		$curFile = '';
		
		$cfile = self::DOCROOT . 'Common/Logs/' . $fileName;
		
		/* check the size of the file   */
		if(file_exists($cfile))
		{
			$csize = filesize($cfile);
			
			/* if filesize is bigger than 1 MB, elseif 500KB  */
			if($csize > 10000000)
			{
				exit('<h3>SYSTEM ERROR 561: Please report to web admin.</h3>');

			}
			else {
				$curFile = file_get_contents($cfile);
					
				if($csize > 5000000)
				{
					$logFileIssue = '<h3>ERROR 121: Please report to web admin..</h3>';					
				}
			}
		}
		if($logFileIssue) { 
			echo $logFileIssue;   
		}
		$params = array(
				'objectName' => 'encryptIt'
				,  'parentObjectName' => $this->lastPrivates['objectName']
				, 'child1Args' => array(
						'metaIn' => array(
								'logKey' => self::CONFIDENTIALS['logKey']
								, 'fileName' => $fileName	
							)					
						, 'dataIn' => var_export($datam, TRUE)
					)
			);

		
		// We need this override to make destruct work
		if(!v_FileName($params['parentObjectName'])) {
			$params['parentObjectName'] = 'Invalid_parentObjectName';
		}
	
		$encryptIt = new Encrypt3($params);

		$encryptItResult = $encryptIt->get_result();
		
		if(empty($encryptItResult['metaOut']['status']) || $encryptItResult['metaOut']['status'] !== 'Success') {
			// R*** : wont work in destructor : $this->protecteds['toCaller'] = 'SYSTEM ERROR 381: Please report to web admin.';
			echo '<h4>Error 381: Please report to web admin.' . date('Ymd-His') . '</h4>';
		}
		else {
			// R*** : wont work in destructor : $this->protecteds['toCaller'][] = 'Log file saved.';
			echo '<h4>Error 382: Please report to web admin.'  . date('Ymd-His') . '</h4>';
		}
		
		return true;
		
	}


	public static function debugging_writeFailed($log, array $toLog)
	{	
		if(self::ENV !== 'development') {
			return false;
		}
		
		echo '<div style="border: 3px yellow solid;">';
		echo '<h4>eVe WARNING: Cannot write to log file: '  . $log . '</h4>';
		echo 'at eVe.php 187 $this->writeToLogFile() <br> <pre>';
		var_dump($log, $toLog);
		echo '</pre></div>';
	}


	
	
	//------------------------ end: errors and debugging  ------------------------------------

	//---------------------- starts: validation tools -------------------------------------

	 /**
		returns 'true' 
			or '!Security issue' 
			or array of issues
	*/
	 public static function v_Args($requireds, $optionals, $given) 
	 {
		 $issues = array();
		 
		 // checking requireds
		 if(is_array($requireds)) {
			 foreach($requireds as $key1 => $row1) {			 
				if(!isset($given[$key1])) { 		
					$issues[$key1] = 'Missing value.';
					continue;
				}
				$isValid = self::v_Field($given[$key1], $row1['pattern'], $row1['method']);
				if($isValid === TRUE) {		
					continue;
				}
				else {
					$issues[$key1] = $given[$key1];
				}	
			 }
		 }	 

		 // checking optionals
		 if(is_array($optionals)) {	
	 
			 foreach($optionals as $key2 => $row2) {	 
				if(!isset($given[$key2])) { 
					continue;
				}
				$isValid = v_Field($given[$key2], $row2['pattern'], $row2['method']);
				if($isValid === TRUE) {				
					continue;
				}
				else {
					$issues[$key2] = $given[$key2];
				}					
			 }
		 }
		 
		 // checking unexpected variables
		 if(is_array($given)) {	 
			 foreach($given as $key3 => $val3) {
				if(!isset($requireds[$key3]) && !isset($optionals[$key3])) {
					$issues[$key3] = $val3;
				}
			 }
		 }
		 
		 if(!empty($issues)) {
	var_dump('<pre>validationTools.php issues-55:' , $issues);		 
			return $issues;
		 }
						 
		 return TRUE;
	 }
		 
	public static function v_Field($val, $pattern = '', $method = '') 
	{
		if($pattern) {
			if (!preg_match('/' . $pattern . '/i', $val) ) {
				return false;
			} 
			return true;
		}
		elseif($method) {
			if(function_exists($method)) {
				if(!$method($val)) {
					return false;
				}
			}
			elseif(!self::$method($val)) {
				return false;
			}			
		}
		
		return true;
		
	}

	public static function v_FileName($name)
	{
		if(strlen($name) > 64)
		{
			return false;
		}
		return preg_match('/^\w+[\w|\n]*$/', $name);
	}

	public static function v_passport($params)
	{
		if(get_class($params) === 'eVe\Passport') {
			return true;
		}
		
		return false;
	}

	public static function v_Encrypt3($params) 
	{

		$issues = '';
		if(!is_array($params['metaIn']) || empty($params['dataIn']) || empty($params['metaIn']['logKey']) || empty($params['metaIn']['fileName']) ) {
			return false;
		}

		if(!v_FileName($params['metaIn']['fileName'])) {
			
			return false;
		}

		return true;
		
	}	

	public static function v_Decrypt3($params) 
	{
		$issues = '';
		if( !isset($params['key']) || empty($params['sourceFile']) || !v_FileName($params['sourceFile'])) {		
			return false;
		}

		return true;
		
	}


	// @toDo
	public static function v_checkPermissions($permissionsRequired, $permissionsProvided)
	{
		$noPermission = '';
		$permit = '';
		$level = '';
		

		if(empty($permissionsRequired['requiredAll']) && empty($permissionsRequired['in']) 
		&& empty($permissionsRequired['notIn']) ) {
			return true;
		}
		
		// @toDo
		exit('<br>validationTools.php exit 236');		

		if(!empty($permissionsRequired) )
		{

			// @toDo : all, in, notin
			// @toDo : byreference = array('requiredAll' => array(), 'in' => array(), 'notin' => array()); 
		
				if(!empty($permissionsRequired['requiredAll'])) {
						$allRequireds = $permissionsRequired['requiredAll'];
						foreach($allRequireds as $permit => $level) {
							if(empty($permissionsProvided->$permit) ||  $permissionsProvided->$permit != $allRequireds[$permit]) {
								$noPermission = 'yes';
								break;

							}
						}
				}

				if(!empty($permissionsRequired['notin'])) {
					$notin = $permissionsRequired['notin'];
					foreach($notin as $permit => $level) {
						if(!empty($permissionsProvided->$permit) &&  $permissionsProvided->$permit == $notin[$permit]) {
							$noPermission = 'yes';
							break;
						}
					}
				}

		}

		// If passed the v_checkPermissions test
		if(!$noPermission) {
			return true;
		}
		
		return false;
		
	}

	//---------------------- ends: validation tools -------------------------------------

	
}
