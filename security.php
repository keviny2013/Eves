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

$info = '<p class="info"><h3>Level 1</h3>Level 1 covers:<br>eVe, <br>SecurityCheck up to validating request,<br>Encrypting logs<br>
<br>TESTs<br>
- secure.php: change object name \'secure\' to something like \'sec!ure\' to generate error log. Refresh the browser page. <br>
Error logs. Encrypting and decrypting:<br>
- After error pages are created, find the latest 2 encrypted error log files under folder \'Common/Logs\'. Copy name of the latest file named like 
\'debugger_encrypted_xxxx\' or \'toDeveloper_encrypted_xxxx\'<br>
- Fine decryption code line which starts with \'readLogs\'. Uncomment this line.
- Replace log file name (\'debugger_encrypted_15141650603\') with the fie name that you copied.
- Refresh the browser\'s window.
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