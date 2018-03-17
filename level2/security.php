<?php
/*
	- remove R***
	- check for @toDO 
*/

use eVe\securityCheck;
use eVe\eVe;
use eVe\DecryptAFile3;

// R***
error_reporting(E_ALL); ini_set("display_errors", 1);

spl_autoload_register(function ($class_name) {
	// echo '<pre>class_name-19:' . $class_name;	
    require_once $class_name . '.php';
});

session_start();

$info = '<p class="info"><h3>Level 2</h3>Added features:
<br>SecurityCheck : Validating request,
<br>Display Contact Form (Form - step 1)
<br>TESTs<br>

<br></p>';

$out = $info;

require_once('Step3/templates/writeMessages.php');
	
// decryption
// readLogs('debugger_encrypted_15141650603');  exit('<br>exit 26');


$params = array(
		'objectName' => 'secured'
		,  'child1Args' => array()		
	);
$secured = ${$params['objectName']} = new securityCheck($params);

if(!$secured) {
	// var_dump('<pre>No secured statics-38:' , eVe::$statics);
	
	exiteVe(array());
}
elseif($secured->get_fatalIssue()) {
	$out .= '<br>secured.php 43 fatalIssue: ' . $secured->get_fatalIssue() . '<br>';

}


$out .= '<hr>';
$out .= writeMessages($secured->get_toCaller(), 'Messages:');
$out .= '<hr>';
echo $out;
	



/**
* 	@toDo : Encryption needs improvement
*/
function readLogs($sourceFile) 
{
	// $sourceFile = 'toDeveloper_encrypted_1503783377';
	
	$params = array(
				'objectName' => 'decryptLogFile'
				, 'child1Args' => array(
					'key' => ''
					, 'sourceFile' => $sourceFile
				)
		);
		
	$decryptLogFile = ${$params['objectName']} = new DecryptAFile3($params);
		
	if($decryptLogFile->get_fatalIssue()) {
		exit('<br>Decryption failed');
	}
	else {
		exit('<br>Decryption completed successfully.');
	}
	
	return $decryptLogFile->get_fatalIssue();
	
}


/**
*
*/
function exiteVe($toCaller) 
{

	$addToHtml = writeMessages($toCaller);

	echo $addToHtml;
	
	exit('<br>exit eVe 87');
}