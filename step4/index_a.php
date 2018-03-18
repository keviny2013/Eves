<?php
/**
*	@name			:	index.php
*	@owner			: 	eVe
*	@description	:	Calls first page
*	@steps	 		: 	
*
*/



	
	// this is ajax request
	if(!empty($_SERVER['PATH_INFO'])) {
		// 
		echo $_SERVER['PATH_INFO']['fname'];  exit;
		var_export('<pre>get: ', $_SERVER['PATH_INFO']); exit;
		
		$response = '';
		$args = explode('/', $_SERVER['PATH_INFO']);
		
		// security check
		$patterns = array(
			'fname'	=> '^[a-zA-Z0-9 \-]{8,16}$',
			'lname'	=> '^[a-zA-Z0-9 \-]{8,16}$',
			'textarea_comment'	=> '^[a-zA-Z0-9 \-]{3,1600}$',
			'token'	=> '^[a-zA-Z0-9\_\-\.]{11,64}$',
		);
		$checked = checkPregMatch($patterns, $args);
		
		if(empty($checked['unacceptables'])) {
			$response .= '<br>First Name: ' . $args['fname'];
			$response .= '<br>Last Name: ' . $args['fname'];
			$response .= '<br>Comment: ' . $args['fname'];
			echo json_encode('This is ajax response');
			exit;			
		}
		else {
			$response = array('ajaxUnsafe' => true);
			echo json_encode($response);
			exit;
		}
		

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
