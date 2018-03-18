<?php
//securityCheck.php

/**
*
*/
class SecurityCheck 
{

	protected $protecteds = array();

	private $privates = array(
		'status' => '', 'toCaller' => array(), 'toDeveloper' => array(), 'gets' => array()
		, 'posts' => array(), 'requestName' => '',  'requestType' => '', 'method' => ''
	);
	
	public $publics = array();
	
	
	function __CONSTRUCT() {
		if(!empty($_POST)) {
			$this->processPost();
		}
		else {
			// @toDo process as home page
			return;
		}
		
		$_POST = array();
		$_GET = array();

		if(empty($this->privates['status']) ) {
			$this->privates['status'] = 'Ok';
		}
	}
	
	
	/**
	*	$_POST['requestName'] = $args[1];
	*/
	public function processPost()
	{
			// check required params
		$requireds = array('requestName', 'requestType', 'tokenid');
		$patterns = array(
			'requestName'	=> '^[a-zA-Z0-9 \-_]{3,32}$',
			'requestType'	=> '^[a-zA-Z0-9 \-_]{3,32}$',
			'tokenid'		=>	'^[a-zA-Z0-9\-_]{3,16}$',
		);
		$checked = $this->checkPregMatch($patterns, $_POST, $requireds);
		if(empty($checked['unacceptables'])) {
			$this->privates['requestName'] = $checked['acceptables']['requestName'];
			$this->privates['requestType'] = $checked['acceptables']['requestType'];
			$tokenid = $checked['acceptables']['tokenid'];			
			unset($_POST['requestName']);
			unset($_POST['requestType']);
			unset($_POST['tokenid']);
			unset($checked);
		}
			// @toDo security issue. record, track and act
		else {
			$this->privates['toDeveloper'][] = array('request' => 'Issue with request name or type.');
			$this->privates['toCaller'][] = array('Error 29', 'Sorry an error occured.');
			$this->privates['status'] = 'unsafe2';
			// @toDo record, track and act			
			$_POST = array();			
			return;
		}		
		
			// validate token
		$current = date('u');
		$response = array();
		if($_SESSION['tokens'][$tokenid]['value'] !== $_POST['token'] 
			|| $_SESSION['tokens'][$tokenid]['expires'] < $current ) {
			$this->privates['toDeveloper'][] = array('request' => 'Token issue 51.');
			$this->privates['toCaller'][] = array('Error 28', 'Sorry an error occured.');
			$this->privates['status'] = 'unsafe3';
			return;			
		}

			// get spesific security requirements for this request
		$requireds = array();
		$patterns = array();			
		require_once('backend/securityChecks/' . $this->privates['requestName'] . '.php');
			// @toDo security check texts, textareas
		if(!empty($checkTextArea)) {
			foreach($checkTextArea as $key => $val) {
				$this->privates['posts'][$val] = $this->checkTextArea($_POST[$val]);
				unset($_POST[$val]);
			}
		}
		
			// $patterns and $requireds come from file under backend/securityChecks/
		$checked = $this->checkPregMatch($patterns, $_POST, $requireds);
		if(empty($checked['unacceptables'])) {
			// $this->privates['status'] = 'validated1';
			$this->privates['posts'] = array_merge($checked['acceptables'], $this->privates['posts']);
		}
			// @toDo security issue. record, track and act	
		elseif($checked['security'] == 'unsafe') {
			$this->privates['toDeveloper'][] = array('request' => 'Security issue with data.');
			$this->privates['toCaller'][] = array('Error 30', 'Sorry an error occured.');
			$this->privates['status'] = 'unsafe1';
			$_POST = array();			
			return;
		
		}
		else {
				// @toDo loop through unacceptables and display error for each field		
			$this->privates['toCaller'][] = array('invalid entry', $checked['unacceptables']);
			$this->privates['status'] = 'invalid entry1';		
		}
		
	}

	
	/**
	*	
	*/
	public function getProperties()
	{
		return $this->privates;
	}
	

	/**
	*	@toDo filter data that comes from textarea
	*/
	function checkTextArea($text) 
	{
		
		return trim($text);
		
	}



		
	/**
	*	1- Checks size and characters of $datam against $pattern
		2- Checks if required
	
	*	3- returns 3 values:
		$checked['security'] : is unsafe only if there are unexpected keys in $datam
		$checked['unacceptables'] : assoc array of field names that does NOT match pattern
		$checked['acceptables'] : assoc array of field names that match pattern
	*
	*/
	public static function checkPregMatch($patterns, $datam, $requireds) 
	{
		$checked = array();
		$checked['security'] = '';
		$checked['unacceptables'] = array();
		$checked['acceptables'] = array();
		$inputs = array();
		$cinput = '';
		$oks = array();
		foreach($patterns as $fieldName => $pattern) {
			if($pattern) {
				$inputs = $datam[$fieldName];
				$datam[$fieldName] = '';
				// select, checkboxes, radios might have array of values
				if(is_array($inputs)) {
					$numRows = count($inputs);
					for($i = 0; $i < $numRows; $i++) {
						$cinput = $inputs[$i];
						if(!$cinput && !in_array($fieldName, $requireds)) {
							continue;
						}
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
					if(!$cinput && !in_array($fieldName, $requireds)) {
						continue;
					}
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

		if(count($datam[$fieldName]) > 0) {
			$checked['security'] = 'unsafe';
		}
		
		
		return $checked;
		
	}

	
}
