<?php
/**
*	@requestName	: 	form_process_simpleLogin
*	@explanation	:	security check and input validation on simpleLogin form 
*/

	$metam['requestName'] = 'form_process_simpleLogin';
	$metam['formatType'] = 'Json';	
	$metam['module'] = 'user';

	$paramsRequired = array('loginname', 'password', 'token');
	$permissionsRequired = array();

	$pregCheckPost = array(
		'loginname'	=> '^[a-zA-Z0-9 \-]{8,16}$',
		'password'	=> '^[a-zA-Z0-9 \-]{8,16}$',
		'token'	=> '^[a-zA-Z0-9\_\-\.]{11,64}$',
	);
	
	// echo 'This is form_process_simpleLogin.php';
	 

	