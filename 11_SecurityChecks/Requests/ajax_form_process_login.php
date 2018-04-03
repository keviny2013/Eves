<?php
/**
*	@requestName	: 	authenticate
*	@explanation	:	security check on login / authenticate  parameters
*/

	$metam['requestName'] = 'authenticate';
	$metam['processType'] = 'db select';
	$metam['formatType'] = 'Html';	
	$metam['module'] = 'user';

	$paramsRequired = array('loginname', 'password', 'token');
	$permissionsRequired = array();

	$pregCheck = array(
		'loginname'	=> '^[a-zA-Z0-9 \-]{8,16}$',
		'password'	=> '^[a-zA-Z0-9 \-]{3,16}$',
		'token'	=> '^[a-zA-Z0-9\_\-\.]{11,64}$',
	);
	
	