 <?php

 
require_once(DOCROOT . '/21_ProcessingRequests/_ProcessRequest.php');

/**
	*	14_Tasks for caller: 
	*	display simple login form
	*	process simple login form
	*	display registration form
	*	process registration form
	*	send email verification
	*	process email verification	
	*	display edit registration form
	*	process edit registration form
	
	*	14_Tasks for admin:
	
	* 	add javascript to control all events including clicking links
*/
class user extends processRequest
{
	
	function __CONSTRUCT($params) {
		
		parent::__Construct($params);
		
		require_once(DOCROOT . '/21_ProcessingRequests/45_Tools/searchGoogle.php');
// echo 'fileName-14: ' . DOCROOT . '/21_ProcessingRequests/45_Tools/searchGoogle.php'; 	exit;

		$params = array();
		

		/*
			//	region1_1: display Logo, title, login form
			$this->datam['pageTitle'] = 'eVe For Developers';
			$this->datam['regions']['region1_1']['content'] = '<img src="31_FormattingResponses/21_Images/world1.gif' . '" alt="World image." > <div style="position: relative; float: left; display: inline;" > <h3>' . $this->datam['pageTitle'] . '</h3> </div>';
			$this->datam['regions']['region1_1']['css'] = 'height:8em;';
			require_once(DOCROOT . '/31_FormattingResponses/1_Htmls/);
			$task = 
			
			//	region2_1: display search form
			//   region3_1: Get Google results for caller's current location. Display results 
			// 	add javascript to control all events including clicking links		
			
		
			
			$this->datam['regions']['region2_1']['content'] = 'Region: region row 1 - column 1 <h3>Welcome to my home page</h3>';		
			$this->datam['regions']['region2_1']['css'] = 'width:48%; height:10em;';		

			$this->datam['regions']['region2_2']['content'] = 'Region: region row 1 - column 1 <h3>Welcome to my home page</h3>';		
			$this->datam['regions']['region2_2']['css'] = 'width:48%; height:10em;';

			
			$this->datam['regions']['region3_1']['content'] = 'Region:<p>Testing Google soon</p>';
			$this->datam['regions']['region3_1']['css'] = 'width:48%; height:10em;';	
		
			$this->datam['regions']['region3_2']['content'] = 'Region:<p>Testing Google soon</p>';		
			$this->datam['regions']['region3_2']['css'] = 'width:98%;';
			
			// pages must have html, ajax requests do not have to
			$this->metam['htmls'] = array(DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HttpHeader.php', 
		DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HtmlBody.php', DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HtmlFooter.php', );
		
		*/
		
		unset($params);

	}
	
}


