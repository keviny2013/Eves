<?php

namespace eVe;
use eVe\eVe;
use eVe\passport;

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
					'codeGroup' => 'frontPage'
					, 'requestName' => 'page_first'
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
		$this->protecteds['eVeTracks'][$this->lastPrivates['objectName']][] = '@toDo : validateRequest()';
		$this->passport['validatedRequest'] = $returned;

		$this->protecteds['toCaller'][] = 'Request validation did not run.';

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
		
		return $request;

	}

	
}

