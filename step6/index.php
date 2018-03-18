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
	
$secured = [];

	// security check and validation
if(!empty($_POST)) {
	try{
		require_once('backend/SecurityCheck.php');
		$securityCheck = new SecurityCheck();
	}
	catch(Exception $e) {
		exit('exit 25: ' . $e->getMessage());
	}
	$secured = $securityCheck->getProperties();

	unset($securityCheck);
	
	if($secured['status'] == 'unsafe') {
		$response = array('tag' => 'asis', 'innerHTML' => '<p class="error">System error</p>' );
		exit(json_encode($response));
	}
	
	// processing request

	
	$method = $secured['requestName'];
	require_once('backend/requests/' . $method . '.php');
	$request = new $method($secured['posts']);
	$out = $request->getOut();
	echo $out;
	
}
else {


	require_once('backend/Page_First.php');

	$args = array();
	$page = new Page_First($args);
	echo $page->getBody();	
	
}


// @toDo  list($secured['toCaller']);
// @toDo writeToLog('todeveloper', $securityCheck->toDeveloper);



/** 
*	@objectId : Maybe formid or something else	
*	@rule	:	Register token in session with time and IP
*/
function  generateToken($objectId, $expires) {
	$chars = array( 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', ' _', '-', '5', '6', '7', '8', '9', '10', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r');
	$random_keys = array_rand($chars, 7);
	$replaced = array_replace($random_keys, $chars);
	$token = implode($replaced);
	$expires = date('u') + $expires;
	$_SESSION['tokens'][$objectId] = array('value' => $token, 'expires' => $expires);
	
	return $token;
}

