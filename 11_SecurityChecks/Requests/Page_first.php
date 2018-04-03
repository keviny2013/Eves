<?php
/**
*	security on home  page parameters
*/

	/**
	*	None of home pages are the first page (page_first).
	*	page_first does not take any parameter.
	*	page_first have only 1 availableFormatTypes
	*	after security check will be directed to home page of request
	*/
	
	$permissionsRequired = array();
	$paramsRequired = array();
	// $paramsOptional = array('formatType');	
	
	$requestName = 'page_first';
	$moduleName = 'homePage';
	// $availableFormatTypes = array('Html_2D', 'zipped_Htm_2D', 'Json',  'zipped_Json', 'Xml', 'zipped_Xml', 'csv', 'zipped_csv', 'word', 'zipped_word',  'pdf', 'zipped_pdf', 'exel', 'zipped_excel' );
	$availableFormatTypes = array('Html_2D');
	
	// processorClass's will be:  'page_first_Html', 'page_first_Json', 'page_first_Xml'	
	
	
