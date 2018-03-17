<?php
// first page does not have any requirements except routine security check


$requiredArgs = array();
$optionalArgs = array();
$permissionsRequired = array();

$service = array(
		'requiredArgs' => $requiredArgs
		, 'optionalArgs' => $optionalArgs
		, 'permissionsRequired' => $permissionsRequired		
		, 'responseTypes' => array('json', 'ajax', 'Html_2D', 'Html_3D')
		, 'starterClass' => 'Step2/page_first/showPageFirst'
	);