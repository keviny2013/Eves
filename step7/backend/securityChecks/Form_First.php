<?php

	/**
	*	token and tokenid are declared (in securityCheck class) as required for all requests 
	*	so, they are  not listed here
	*/

		// methods to check security and validate the data
	$validated = array();
	$requireds = array('fname', 'lname', 'comment');
	$patterns = array(
		'fname'	=> '^[a-zA-Z0-9 \-]{3,16}$',
		'lname'	=> '^[a-zA-Z0-9 \-]{3,16}$',
	);
 
	$checkTextArea = array('comment');