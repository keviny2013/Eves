<?php

namespace eVe;
use eVe\eVe;

class securityCheck extends eVe
{
	private $confidentials = array();
	
	private $privates = array(
		'objectName' => ''
		,  'parentObjectName' => ''
		,  'permissionsRequired' => array()		
		,  'child1Args' => array()
		
		, 'argsRequired' => array(
			   'objectName' => array(
					'method' => 'v_FileName'
					, 'pattern' => ''
				)
				, 'child1Args' => array(
					'method' => 'is_array'
					, 'pattern' => ''
				)
			)
		,  'argsOptional' => array()			
		
		, 'metaIn' => array()
		, 'dataIn' => array()
		, 'metaOut' => array()
		, 'dataOut' => array()

	);


	private $passport = array(
				'caller' => array(
					'ip' => ''
					, 'userId' => ''
					, 'userName' => ''					
				)
				, 'privileges' => array()
				, 'validatedRequest' => array()
			);	

			

	protected function child1() {

		if( $this->checkIp() !== TRUE) {
			$this->operationFailed('failed checkIp()', 'unsafe');
			return false;			
		}
		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = 'passed checkIp()';
		
		if( $this->checkCookies() !== TRUE) {
			$this->operationFailed('failed checkCookies()', 'unsafe');
			return false;			
		}
		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = 'passed checkCookies()';
		
		
		if( $this->checkLocalStorage() !== TRUE) {
			$this->operationFailed('failed checkLocalStorage()', 'unsafe');
			return false;			
		}
		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = 'passed checkLocalStorage()';

		$this->passport['caller'] = $this->get_caller1();
		
		$incomingRequest = $this->analyzeIncomingRequest();
		// @toDO : internal incomingRequest
		if(empty($incomingRequest)) {
			$firstPage = array(
					'codeGroup' => 'page_first'
					, 'requestName' => 'showPageFirst'
					, 'responseType' => 'Html_2D'
				);
			$returned = $this->validateRequest($firstPage);				
		}
		else {
			$returned = $this->validateRequest($incomingRequest);
		}

		if(!$returned) {
			$this->operationFailed('failed validateRequest()', 'unsafe');
			return false;				
		}
		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = 'passed validateRequest()';
		$this->passport['validatedRequest'] = $returned;

		$this->protecteds['toCaller'][] = 'Request validation completed.';

		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = 'child1 completed.' ;

		
		return true;		
		
	}
	
	// IMPORTANT: copy this to each child class
	protected function set_lastPrivates() {
		$this->lastPrivates = $this->privates;

		return;
	}

	// IMPORTANT: copy this to each child class	
	protected function set_privates($params) {
		if(empty($params)) {
			return true;
		}
		
		foreach($this->lastPrivates['argsRequired'] as $key => $val) {
			if(isset($params[$key])) {
				$this->lastPrivates[$key] = $params[$key];
			}
		}
		foreach($this->lastPrivates['argsOptional'] as $key2 => $val2) {
			if(isset($params[$key2])) {
				$this->lastPrivates[$key2] = $params[$key2];
			}
		}
		
		$this->privates = $this->lastPrivates;
		
		return true;
		
	}


	protected function get_passport() {
		if(__CLASS__ == 'eVe\Step2' || __CLASS__ == 'eVe\securityCheck') {
			
			// R*** $passport2 = new Passport($this->passport);
			
			return $this->passport;

		}
		
		return false;
	}	
	
	protected function checkIp()
	{
		return true;
	}
	
	protected function checkCookies()
	{
		
		return true;
	}

	protected function checkLocalStorage()
	{
		
		return true;
	}
	
	// @toDo  get caller groups, privileges and roles from DB
	protected function get_caller1()
	{
		// @R*** @toDo
		$caller = array(
					'ip' => $_SERVER['REMOTE_ADDR']
					, 'userId' => 'Kev'
					, 'userName' => 'kevy!yil'				
				);
				
		return $caller;
		
		// qry = 'SELECT ';

	}
	

	protected function analyzeIncomingRequest()
	{
		$incomingRequest = array();

		$incomingRequest2 = $this->analyzeUrl();
		$_GET = array();
		if(!$incomingRequest2) {
			return false;
		}
		
		if(!empty($_POST)) {
			$incomingRequest = $this->analyzePost();
			$_POST = array();
		}
		// last one overrides the previous one
		if(is_array($incomingRequest2)) {
			$incomingRequest = array_merge($incomingRequest2, $incomingRequest);			
		}
		
		
		return $incomingRequest;
		
	}

	// @toDo : we should allow $_GET and site map	
	protected function analyzeUrl()
	{
		$incomingRequest = array();

		if(empty($_SERVER["REQUEST_URI"]) || $_SERVER["REQUEST_URI"] == '/') {
			return true;
		}

		// clean urls only
		if(!empty($_GET)) {
			$this->protecteds['fatalIssue'] = 'unsafe1';
			$this->protecteds['eVeTracks'][] = 'Unexpected variable in URL';
			return false;
		}

		$keys = explode('/', trim($_SERVER["REQUEST_URI"], '/'));

		$countKeys = count($keys);

		for($i = 0; $i < $countKeys; $i++) {			
			if(!preg_match('/^[a-zA-Z0-9\-_=.]*$/', $keys[$i])) {
				$toDeveloper['illegal_uri'] = $_SERVER["REQUEST_URI"];	
				
				$this->protecteds['toDeveloper']['illegal _SERVER REQUEST_URI'] = $_SERVER["REQUEST_URI"];
				$this->protecteds['toDeveloper']['illegal uri-204'] = $keys[$i];	
				$this->protecteds['fatalIssue'] = 'unsafe2';				
				return false;
			}			
		}
		
		if($countKeys == 1) {
			$incomingRequest['requestName'] = $keys[0];
		}
		elseif($countKeys == 2) {
			$incomingRequest['codeGroup'] = $keys[0];
			$incomingRequest['requestName'] = $keys[1];
		}
		elseif($countKeys > 2) {
			$incomingRequest['codeGroup'] = $keys[0];
			$incomingRequest['requestName'] = $keys[1];
			$incomingRequest['params'] = array_slice($keys, 2);
		}

		return $incomingRequest;
		
	}


	/**
	*	@toDo
	*/
	protected  function analyzePost()
	{

		return $_POST;
	
	}

	/**
	*	@toDo
	*/
	protected function validateRequest($request)
	{
/*
echo '<br>request:<br>';
var_dump('<pre>', $request);
echo '<br>';

exit('<br>exit at SecurityCheck.php  271');
*/


// echo '<br>codeGroup-305: ' . $request['codeGroup'];
// echo '<br>requestName-306: ' . $request['requestName'];
		
		if(!empty($request['codeGroup'])) {
			$securityFile = self::DOCROOT . 'Step1/' . $request['codeGroup'] . '/' . $request['requestName'] . '.php';	
		}
		elseif(!empty($request['requestName'])) {
			$securityFile = self::DOCROOT . 'Step1/' . $request['requestName'] . '.php';	
		}
	
		if(!file_exists($securityFile)) {
			// exit('System error' . $securityFile);
			$this->protecteds['toDeveloper']['Missing file'] = 'Security file does not exists. 987: ' . $securityFile;
			$this->protecteds['eVeStatus'] = 'unsafe3';
			return false;
		}
		
// echo '<br>securityFile-327: ' . $securityFile;
// var_dump('<pre>toDeveloper-325: ', $this->protecteds['toDeveloper']);
// exit('<br>securityCheck.php : exit 317');

		require_once($securityFile);
		if(empty($service)) {
			$this->protecteds['toDeveloper']['missing $request'] = '$request in request security check file is missing.';
			$this->protecteds['eVeStatus'] = 'unsafe';			
			return false;
		}
		
// exit('<br>exit 286');
		
		$this->checkArgs($service['requiredArgs'], $request);

		$this->lastPrivates['permissionsRequired'] = $service['permissionsRequired'];
		$this->checkPermissions();

// exit('<br>exit 328');
		
		$step2File = self::DOCROOT . $service['starterClass'] . '.php';
		if(!file_exists($step2File)) {
// 
exit('<br>exit 348-missing step2File: ' . $step2File);
			
			$this->protecteds['toDeveloper']['step2File'] = 'File does not exists. 45: ' . $step2File;
			$this->protecteds['eVeStatus'] = 'unsafe';			
			return false;
		}
 //  exit('<br>exit 359-step2File: ' . $step2File);
 
		$request['step2File'] = $step2File;
		$request['starterClass'] = $service['starterClass'];
		
		// $request = array_merge($request, $service);
		if(empty($request["responseType"])) {
			$request["responseType"] = 'Html_2D';
			
		} elseif(!in_array($request["responseType"], $service["responseTypes"])) {
			$this->protecteds['toDeveloper']['unsupported response type'] = $request["responseType"];		
			$this->protecteds['toCaller'][] = 'Response type not supported';
			$this->protecteds['eVeStatus'] = 'unsafe';			
			return false;
		}
// 
var_dump('<pre>request-375: ', $request);
		
		return $request;
		
		return $request;

	}

	
}

