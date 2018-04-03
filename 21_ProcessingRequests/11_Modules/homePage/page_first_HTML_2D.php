﻿ <?php

 
require_once(DOCROOT . '/21_ProcessingRequests/_ProcessRequest.php');

/** 
	*	Step 1- Check permissions
	*	Step 2- Set responseType
	*	Step 3- Build the page block by block
	*	Step 4- Declare template files
	
	*	Below region-structure is for : responseType = Html
	*	regions > blocks > elements order  Each goes as rows(columns)
	*	Each domElement array must have: 	htmltag
	*	domElements may have these attributes: type, name, value, id, href, src, alt, widget, js, (string) style, (array) class, (array) attributes 
		
	*	$toDo : conditional attributes, removing attributes	: class attribute : will replace existing classes. 'addClass' will add class
	*
	DOM Structure:  
	A region > regions[horizontal][vertical] 
	A region > regions[h][v] 	domId will be generated/rendered by template like v_h (example 0_0) 
	A block > regions[h][v][h][v]	domId will be generated/rendered by template like v_h_v_h (example 0_0_0_0)
	An element > regions[h][v][h][v][X] - X maybe int or string. An element has no domId as default. Add id if necessary.
		All elements must have zero array with htmltag like regions[h][v][h][v][0] = array('htmltag' => 'div')
	
*/

class page_first_Html_2D extends eVe
{
	protected $permissionsRequired = array();
	
	function __CONSTRUCT($params, $datam = array()) {
		// Step 1- Set permissions : Page_first does not need anything extra. Default value is set in eVe class
			// $this->metam['permissionsRequired'] = array('securityPass' => 1);


// var_dump('<pre>parent---37:' , parent::getProperties());  exit;

$this->metam['toCaller']['okdok'] = 'okdok from page_first';	


// var_dump('<pre>this->metam--page_first - 42:' , $this->metam);
		
		parent::__Construct($params);
		
		// Depreciated in favor of mergeKid() in eVe class
		// @WARNING : If second array has the same key, 2nd value will be lost. So, use different keys when writing code for $toCaller, $toDeveloper.
		// $this->metam['toCaller'] = $params['toCaller'] + $this->metam['toCaller'];
		// $this->metam['toDeveloper'] =  $params['toDeveloper'] + $this->metam['toDeveloper'];

		// R***  page_first does not accept any incoming data at all
		$this->datam = $datam;
	
		$this->metam['requestName'] = $params['requestName'];
		$this->metam['moduleName'] = $params['moduleName'];
		$this->metam['processorClass'] = $params['processorClass'];

		// Step 2- Set responseType
			$this->metam['responseType'] = 'Html';

		
		//	Step 3- Build the page block by block
			$this->datam['pageTitle'] = 'WeeklyZip : Local to Global.';
			$this->datam['bodyAttributes'] = '';
			$this->datam['regions'] = array();
			
// var_dump('<pre>datam-61: ' , $this->datam);  exit;
			
			// This array will build (name => domId) pair all regions and blocks so we can pass the user friendly names to Javascript
			$regionNames = array();

				// build the page region by region
				// build each region block by block
				$this->region1();
					$regionNames['Header']['Region'] = 'r_0_0';
					$regionNames['Header']['Header block'] = 'b_0_0_0_0';
				$this->region2();
					$regionNames['Middle Region']['Region'] = 'r_1_0';
					$regionNames['Middle Region']['Navigation Block'] = 'b_1_0_0_0';
				$this->region3_1();
				$this->region3_2();
					$regionNames['Middle Region']['Region'] = 'r_1_1';
					$regionNames['Middle Region']['Summer Attractions Block'] = 'b_1_1_0_0';
					$regionNames['Middle Region']['Sunset Block'] = 'b_1_1_0_1';			
				

		// Step 4- Declaring template file names
			$this->metam['htmls'] = array(DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HttpHeader.php', 
			DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_renderHtmlBody.php', DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HtmlFooter.php', );

		
		if(!$this->status) {
			$this->status = 'ok';
		}
		
	}
	// end of __CONSTRUCT()
	
	/**
	*	This region has 1 block
	*	1st block:  logo, login_form, pageTitle
	*	
	*/
	protected function region1() {
		$thisBlock = array();
		
	// Row 1, Region 1, Block 1: We need 3 elements this time for $thisBlock: logo, login_form, pageTitle
		
		// logo
		$thisBlock[0][0] = array('htmltag' => 'Img', 'src' => '31_FormattingResponses/21_Images/world1.gif', 'alt' => 'Image: World', 'class' => array('logo'));		
		
		// login_form
		$thisBlock[1][0] = array('htmltag' => 'Form', 'name' => 'form_login', 'action' => 'form_process_login', 'class' => array('loginForm') );		
		// form array(row( array(columns)), row(array(columns)), etc. )
		
		// Form elements: children of form : array('loginName', 'submit', 'password')
		$thisBlock[1]['kids'][0][0] = array('htmltag' => 'Input', 'name' => 'loginName', 'htmltype' => 'text', 'value' => '', 'attributes' => array('maxlength' => '16', 'size' => '8'));	

		
		$thisBlock[1]['kids'][1][0] = array('htmltag' => 'Input', 'name' => 'submit', 'htmltype' => 'submit', 'value' => 'Login');	

		$thisBlock[1]['kids'][2][0] = array('htmltag' => 'input', 'name' => 'password', 'htmltype' => 'password', 'value' => '', 'attributes' => array('maxlength' => '16', 'size' => '8') );		

	
		// pageTitle
		$thisBlock[2][0] = array('htmltag' => 'H3', 'class' => array('title'));
		$thisBlock[2]['text'] = $this->datam['pageTitle'];

		
		// @toDo :  add js to class
		
		// block is completed
		$this->datam['regions'][0][0][0][0] = $thisBlock;
			
		
	}
	
	/**
	*	This region has 1 block
	*	1st block:  Navigation links
	*	
	*/
	protected function region2() {
			$thisBlock = array();
			// Row 2,  Region 1, Block 1: We need 5 elements this time for $thisBlock: Navigation and 4 links		
			// Navigation links
			$thisBlock[0][0] = array('htmltag' => 'Span', 'class' => array('subtitle'));
			$thisBlock[0]['text'] = 'Navigation';
			
			$thisBlock[1][0] = array('htmltag' => 'UL', 'attributes' => array('type' => 'circle'),  'class' => array('navigation'));
			
			$thisBlock[1]['kids'] = array();

			$thisBlock[1]['kids'][0][0] = array('htmltag' => 'A', 'href' => 'users', 'text' => 'Users', 'display' => 'br');
			$thisBlock[1]['kids'][1][0] = array('htmltag' => 'A', 'href' => 'myaccount', 'text' => 'My Account', 'display' => 'br');
			$thisBlock[1]['kids'][2][0] = array('htmltag' => 'A', 'href' => 'aboutus', 'text' => 'About Us', 'display' => 'br');
			$thisBlock[1]['kids'][3][0] = array('htmltag' => 'A', 'href' => 'help', 'text' => 'Help', 'display' => 'br');
	

		require_once(DOCROOT . '/1_Backbone/1_eVe/forms.php');
		$formsParams = array('objectName' => 'forms');
		$formsParams['parentObjectName'] = $this->metam['objectName'];
		$forms = new forms($formsParams);
		$curForm = $forms->getForm('Simple Login Form');
		$this->mergeKid($forms);
		$curForm['fields'] = $this->setFormValues($curForm['fields']);
		
// var_dump('<pre>curForm-124:', $curForm['fields']);	  exit;
	
		$thisBlock[3][0] = array('htmltag' => 'Form');
		$thisBlock[3]['formatType'] = 'db1';		
		$thisBlock[3]['form'] = $curForm['form'];		
		$thisBlock[3]['fields'] = $curForm['fields'];
		$thisBlock[3]['titles'] = $curForm['titles'];		

		
// var_dump('<pre>curForm-184:', $thisBlock[3]['kids']); exit;
		
		// block is completed
		$this->datam['regions'][1][0][0][0] = $thisBlock;

	}
	
	/**
	*	1st block of 3rd region
	*	1st block:  $toCaller, article with title
	*	
	*/
	protected function region3_1() {
			$thisBlock = array();
			$elementIndex = 0;
			// $toCaller will go here later before shipment. Javascript should replace the text here.
			$elementIndex++;
			$thisBlock[$elementIndex][0] = array('htmltag' => 'P', 'id' => 'toCaller', 'class' => array('toCaller'));
			$thisBlock[$elementIndex]['text'] = 'My messages will go here';
			
			// article title
			$thisBlock[$elementIndex][0] = array('htmltag' => 'P', 'class' => array('subtitle'));
			$thisBlock[$elementIndex]['text'] = 'Summer Attractions';

			// text : articleBody
			/*
				// task $articleBody = getarticleBody($articleTitle);
			*/		
			$elementIndex++;
			$articleBody = 'Each week, CNN profiles people who have faced a dramatic turning point in their life. Do you have a story to share? Go to CNN iReport to share it, and you could be featured here.
	See more stories like this and share your own on CNN.com/TurningPoints';
			
			$thisBlock[$elementIndex][0] = array('htmltag' => 'P', 'class' => array('articleBody'));
			$thisBlock[$elementIndex]['text'] = $articleBody;

			
		// block is completed
		$this->datam['regions'][1][1][0][0] = $thisBlock;
		$thisBlock = array();	
	

	}

	
	/**
	*	2nd block of 3rd region
	*	2nd block:  Sunset
	*	
	*/
	protected function region3_2() {
		$thisBlock = array();
		
		$thisBlock[0][0] = array('htmltag' => 'Asis');
		$thisBlock[0]['asis'] = '<p class="subtitle">Sunset</p>';
			 
		// image
		$thisBlock[1][0] = array('htmltag' => 'Img', 'src' => '31_FormattingResponses/21_Images/sunset.jpg', 'alt' => 'Img: Sunset.', 'class' => array('floatRight', 'imgWidget5_3'));		
		

		
		// block is completed
		$this->datam['regions'][1][1][0][1] = $thisBlock;
			

	}

	
	/**
	*	gets values from $this->datam
	*	Sets to the form elements values. Sets null if no value in $this->datam for that key.
	*	
	*/
	protected function setFormValues($formFields) {

		foreach($formFields as $key => $field) {
			
			if(!empty($this->datam[$key])) {
				$formFields[$key]['value'] = $this->datam[$key];
			}
			else {
				$formFields[$key]['value'] = '';
			}
		}

		return $formFields;

	
	}

	
}


