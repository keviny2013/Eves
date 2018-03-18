<?php
/**
*	@name			:	index.php
*	@owner			: 	eVe
*	@description	:	Calls first page
*	@steps	 		: 	
*
*/

// echo json_encode($_POST);	exit;


session_start();
	
// this is ajax request
if(!empty($_POST)) {
	processPost();
}

require_once('backend/page_first.php');

$args = array();
$page = new page_first($args);
echo $page->getBody();


	
/**
*	@explanation :	checks size and characters 
*	returns (array) $checked which includes $checked['acceptables'] and $checked['unacceptables']
*
*/
function checkPregMatch($patterns, $datam) 
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
*	$_POST['requestName'] = $args[1];
*/
function processPost() {

	// security checks
	
	// @toDo : $objectId must come through ajax
	$objectId = 'token_comment';
	$current = date('u');
	$response = array();	
	if($_SESSION['tokens'][$objectId]['value'] !== $_POST['token'] 
		|| $_SESSION['tokens'][$objectId]['expires'] < $current ) {
		$response = array('ajaxUnsafe' => true);
		echo json_encode($response);
		exit;
	}
	unset($_POST['token']);

	$textarea = checkTextArea($_POST['textarea_comment']);
	unset($_POST['textarea_comment']);	
		
	$patterns = array(
		'requestName'	=> '^[a-zA-Z0-9 \-_]{3,32}$',
		'fname'	=> '^[a-zA-Z0-9 \-]{3,16}$',
		'lname'	=> '^[a-zA-Z0-9 \-]{3,16}$',
	);
	$checked = checkPregMatch($patterns, $_POST);
	$_POST = array();
	if(empty($checked['unacceptables'])) {
		$response = array('tag' => 'asis');
		$innerHTML = '';		
		$innerHTML .= '<br><br><hr>' . $checked['acceptables']['fname'] . ' ' . $checked['acceptables']['lname']
					. ' ' . date("Y-m-d")  . '<br>' . $textarea;
		$response['innerHTML'] = $innerHTML;
		echo json_encode($response);
		exit;			
	}
	else {
	// 	echo json_encode(var_export('<pre>unacceptables:', $checked['unacceptables']));	exit;
		
		$response = array('ajaxUnsafe' => true);
		echo json_encode($response);
		exit;
	}
	
}


/**
*	
*/
function checkTextArea($text) {
	// @toDo filter data that comes from textarea
	
	return $text;
	
}
