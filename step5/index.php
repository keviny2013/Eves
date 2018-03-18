<?php
/**
*	@name			:	index.php
*	@owner			: 	eVe
*	@description	:	Calls first page
*	@steps	 		: 	
*
*/

// R*** echo json_encode($_POST);	exit;


session_start();
	
$secured = [];

// Responding Ajax call	
// @toDo : security check and validation
if(!empty($_POST['requestName']) && $_POST['requestName'] == 'Form_First' 
	&& !empty($_POST['requestType']) && $_POST['requestType'] == 'Ajax') {
		
		// @toDo security check and validation
	$innerHTML = '<br><br><hr>' . $_POST['fname'] . ' ' . $_POST['lname']	. ' ' 
	. date("Y-m-d")  . '<br>' . $_POST['textarea_comment'];
	$response = array('tag' => 'asis', 'innerHTML' => $innerHTML );
	
	echo json_encode($response);
	exit;
	
}
else {


	require_once('backend/Page_First.php');

	$args = array();
	$page = new Page_First($args);
	echo $page->getBody();	
	
}
