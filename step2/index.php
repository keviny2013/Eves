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
	
require_once('backend/page_first.php');

$firstPage = new page_first();
echo $firstPage->getBody();


