 <?php

 
// require_once(DOCROOT . '/21_ProcessingRequests/ProcessingRequest.php');

/** 
	*	Step 1- Check permissions
	*	Step 2- Set formatType
	*	Step 3- Build the page block by block
	*	Step 4- Declare template files
	
	*	Below region-structure is for : formatType = Html
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
		All elements must have zero array with htmltag like regions[h][v][h][v][0] = array('field_htmltag' => 'div')
	
*/

class page_first extends eVe
{
	protected $privates = array('objectName' => '',  'parentObjectName' => '',  'permissionsRequired' => array('all' => array(), 'in' => array(), 'notin' => array()), 'paramsRequired' => array('objectName', 'parentObject') );
	
	function __CONSTRUCT($params) {
		
		
		// Step 1- Set permissions : Page_first does not need anything extra. Default value is set in eVe class
				// $this->metam['permissionsRequired'] = array('securityPass' => 1);


			// var_dump('<pre>parent---37:' , parent::getProperties());  exit;

			// R***
			$this->metam['toCaller']['okdok'] = 'okdok from page_first';	


			// var_dump('<pre>this->metam--page_first - 42:' , $this->metam);
			
		$params = $this->checkParamsBasics($params);
		if(!$params || !$this->checkPermissions())
		{
			$this->status = 'exit';
			$this->securityFailed();
			return false;
		}

// exit;			
			// R***  page_first does not accept any incoming data at all
			// $this->datam = $datam;
			$this->metam = $this->metam + $params['metam'];
				
			$this->metam['requestName'] = $params['metam']['requestName'];
			$this->metam['moduleName'] = $params['metam']['moduleName'];
			$this->metam['processorClass'] = $params['metam']['processorClass'];

			
		//	Step 2- Build the page block by block
			$this->datam['pageTitle'] = 'WeeklyZip : Local to Global.';
			$this->datam['bodyAttributes'] = ' class="eVe_body" ';
			$this->datam['regions'] = array();
			
			// var_dump('<pre>datam-61: ' , $this->datam);  exit;
			
			// This array will build (name => domId) pair all regions and blocks so we can pass the user friendly names to Javascript
			$regionNames = array();
			

				// build the page region by region
				// build each region block by block
				$this->region1();
					$regionNames['Header']['Region'] = 'r_0_0';
					$regionNames['Header']['Header block'] = 'b_0_0_0_0';
/*					
				$this->region2();
					$regionNames['Middle Region']['Region'] = 'r_1_0';
					$regionNames['Middle Region']['Navigation Block'] = 'b_1_0_0_0';
				$this->region3_1();
				$this->region3_2();
					$regionNames['Middle Region']['Region'] = 'r_1_1';
					$regionNames['Middle Region']['Summer Attractions Block'] = 'b_1_1_0_0';
					$regionNames['Middle Region']['Sunset Block'] = 'b_1_1_0_1';	
*/
				$this->region4_1();
					$regionNames['Middle Region']['Region'] = 'r_2_0';
					$regionNames['Middle Region']['Create A Form'] = 'b_2_0_0_0';
			

		// Step 3- Declaring css files, js files and template file names
			// $this->datam['addCssFiles']
			// eVe::$publics['addCssFiles']['eve_first.css'] = '31_FormattingResponses/3_Css/eve_first.css';
			// eVe::$publics['addJsFiles']['eve_first.js'] = '31_FormattingResponses/2_Javascript/eve_first.js';				
			// eVe::$publics['addJsFiles']['eve_render.js'] = '31_FormattingResponses/2_Javascript/eve_render.js';
		

//  var_dump('<pre>data-102:', $this->datam); exit; 

			$datam = json_encode($this->datam);
			eVe::$publics['addJsBeforeLoad'][] = ' eVe.datam = ' . $datam . '; ';
			
			$this->metam['httpHeaderFile'] = DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HttpHeader.php';
						
			require_once($this->metam['httpHeaderFile']);
			$this->metam['emptyBody'] = $page_first_HttpHeader . '<body>';
			$this->metam['emptyBody'] .= '<body ' . $this->datam['bodyAttributes'] . '></body></html>';
			
	// R***		
			// $this->metam['htmls'] = array(	DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_renderHtmlBody.php'  );



		// @toDo : transfer addJs, addCss etc to parents : $this->mergeKid($renderHtmlTags);
		
		if(!$this->status) {
			$this->status = 'ok';
		}
		
	}
	// end of __CONSTRUCT()
	
	/**
	*	This region has 1 block
	*	1st block:  logo, login_form, pageTitle
	
		params: htmltag, element_classes, element_attributes, renderby, innerhtml
		$element['field_htmltag'] = '';
		$element['element_classes'] = '';
		$element['element_attributes'] = '';	
		$element['element_renderby'] = '';
		$element['element_innerhtml'] = '';		
	*	
	*/
	protected function region1() {
		$thisRegion = array();
		
		// logo
		$element = array();
		$field = array();
		$element['object_type'] = 'field';
		$element['object_renderby'] = 'field';		
		$field['field_htmltag'] = 'img';
		$field['field_htmltype'] = '';	
		$field['element_renderby'] = '';
		$field['field_name'] = 'mainlogo';	
		$field['element_classes'] = 'logo';
		$field['element_attributes'] = 'src="31_FormattingResponses/21_Images/world1.gif" alt="Image: World"';	
		$field['element_innerhtml'] = '';
		$element['field'] = $field;
		$thisRegion[0][0] = $element;

/*
		// login_form
		$form = array();
		$kids = array();
		$element = array();
		$element['field_htmltag'] = 'form';
		$element['field_htmltype'] = '';	
		$element['element_renderby'] = '';
		$element['field_name'] = 'form_login';		
		$element['element_classes'] = 'loginForm';
		$element['element_attributes'] = ' action="form_process_login"';	
		$element['element_innerhtml'] = '';
		
		
	// var rendered = '<p class="subtitle">' + element['form']['object_title'] + '</p> <form name="' + element['form']['object_name'] + '" action="object_process_' + element['form']['object_name'] + '" ';
	// rendered += element['form']['object_attributes'];

	
		$thisRegion[0][1] = $element;

		// loginName
		$element = array();		
		$element['field_htmltag'] = 'input';
		$element['field_htmltype'] = 'text';	
		$element['element_renderby'] = '';
		$element['field_name'] = 'loginName';
		$element['element_classes'] = '';
		$element['element_attributes'] = 'value="" maxlength="16" size="8"  ';	
		$element['element_innerhtml'] = '';
		// $thisRegion[0][2] = $element;
		// [0][0] would go to next row, [0][1] will be inline
		$kids[0][1] = $element;

		// submit button
		$element = array();
		$element['field_htmltag'] = 'input';
		$element['field_htmltype'] = 'submit';	
		$element['element_renderby'] = '';
		$element['field_name'] = 'submit';
		$element['element_classes'] = 'formSubmit';
		$element['element_attributes'] = 'value="Login"';	
		$element['element_innerhtml'] = '';
		// $thisRegion[0][3] = $element;
		$kids[0][2] = $element;		
		
		// password
		$element = array();
		$element['field_htmltag'] = 'input';
		$element['field_htmltype'] = 'password';	
		$element['element_renderby'] = '';
		$element['field_name'] = 'password';		
		$element['element_classes'] = '';
		$element['element_attributes'] = 'value=""  maxlength="16" size="8"';	
		$element['element_innerhtml'] = '';
		// $thisRegion[0][4] = $element;
		$kids[0][3] = $element;
		$thisRegion[0][1]['kids'] = $kids;
*/
		

		// pageTitle
		$element = array();
		$element['field_htmltag'] = 'h3';
		$element['field_htmltype'] = '';	
		$element['element_renderby'] = '';
		$element['field_name'] = 'pageTitle';		
		$element['element_classes'] = 'title';
		$element['element_attributes'] = '';	
		$element['element_innerhtml'] = $this->datam['pageTitle']; 
		$thisRegion[1][0] = $element;		


		// @toDo : $thisRegion[1][0]['innerHTML'] = getInnerHtml(1); from database
		// page motto		
		$element = array();
		$element['field_htmltag'] = 'p';
		$element['field_htmltype'] = '';	
		$element['element_renderby'] = '';
		$element['field_name'] = 'pageMotto';		
		$element['element_classes'] = '';
		$element['element_attributes'] = '';	
		$element['element_innerhtml'] = 'Tomorrow is another day.';; 
		$thisRegion[2][0] = $element;
 
		// @toDo : filter asis
		// as is		
		$element = array();
		$element['field_htmltag'] = 'asis';
		$element['field_htmltype'] = '';	
		$element['element_renderby'] = '';		
		$element['element_innerhtml'] = '<h4>@toDo : Javascript renderHtmlTags().<br>
		Move this page under formattingResponses because This page, page_first.php is doing formatting. </h4>';
		$thisRegion[3][0] = $element;
		
		// region was completed
		$this->datam['regions'][0][0] = $thisRegion;
		
	}
	
	/**
	*	This region has 1 block
	*	1st block:  Navigation links
	*	
	*/
	protected function region2() {
		return;
		
			$thisBlock = array();
			// Row 2,  Region 1, Block 1: We need 5 elements this time for $thisBlock: Navigation and 4 links		
			// Navigation links
			$thisBlock[0][0] = array('field_htmltag' => 'Span', 'class' => array('subtitle'));
			$thisBlock[0]['text'] = 'Navigation';
			
			$thisBlock[1][0] = array('field_htmltag' => 'UL', 'attributes' => array('type' => 'circle'),  'class' => array('navigation'));
			
			$thisBlock[1]['kids'] = array();

			$thisBlock[1]['kids'][0][0] = array('field_htmltag' => 'A', 'href' => 'users', 'text' => 'Users', 'display' => 'br');
			$thisBlock[1]['kids'][1][0] = array('field_htmltag' => 'A', 'href' => 'myaccount', 'text' => 'My Account', 'display' => 'br');
			$thisBlock[1]['kids'][2][0] = array('field_htmltag' => 'A', 'href' => 'aboutus', 'text' => 'About Us', 'display' => 'br');
			$thisBlock[1]['kids'][3][0] = array('field_htmltag' => 'A', 'href' => 'help', 'text' => 'Help', 'display' => 'br');
	

		require_once(DOCROOT . '/1_Backbone/1_eVe/forms.php');
		$formsParams = array();
		$formsParams['privates'] = array('objectName' => 'forms');
		$formsParams['privates']['parentObjectName'] = $this->privates['objectName'];
		$forms = new forms($formsParams);
		$curForm = $forms->getForm('Simple Login Form');
// 
var_dump('<pre>curForm-174--:', $curForm);	  exit;	
	
		if(empty($curForm) || empty($curForm['fields'])) {
			// block is completed
			$this->datam['regions'][1][0][0][0] = $thisBlock;
			return false;
		}	
		$this->mergeKid($forms);
		$curForm['fields'] = $this->setFormValues($curForm['fields']);
		
// var_dump('<pre>curForm-124:', $curForm['fields']);	  exit;
		
		$thisBlock[3][0] = array('field_htmltag' => 'Form');
		$thisBlock[3]['element_renderby'] = 'db1';		
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
			$thisBlock[$elementIndex][0] = array('field_htmltag' => 'P', 'id' => 'toCaller', 'class' => array('toCaller'));
			$thisBlock[$elementIndex]['text'] = 'My messages will go here';
			
			// article title
			$thisBlock[$elementIndex][0] = array('field_htmltag' => 'P', 'class' => array('subtitle'));
			$thisBlock[$elementIndex]['text'] = 'Summer Attractions';

			// text : articleBody
			/*
				// task $articleBody = getarticleBody($articleTitle);
			*/		
			$elementIndex++;
			$articleBody = 'Each week, CNN profiles people who have faced a dramatic turning point in their life. Do you have a story to share? Go to CNN iReport to share it, and you could be featured here.
	See more stories like this and share your own on CNN.com/TurningPoints';
			
			$thisBlock[$elementIndex][0] = array('field_htmltag' => 'P', 'class' => array('articleBody'));
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
		
		$thisBlock[0][0] = array('field_htmltag' => 'Asis');
		$thisBlock[0]['asis'] = '<p class="subtitle">Sunset</p>';
			 
		// image
		$thisBlock[1][0] = array('field_htmltag' => 'Img', 'src' => '31_FormattingResponses/21_Images/sunset.jpg', 'alt' => 'Img: Sunset.', 'class' => array('floatRight', 'imgWidget5_3'));		
		

		
		// block is completed
		$this->datam['regions'][1][1][0][1] = $thisBlock;
			

	}

		
	/**
	*	1st block of 4th region
	*	1st block:  create a form
	*	
	*/
	protected function region4_1() {
		$thisRegion = array();
		require_once(DOCROOT . '/1_Backbone/1_eVe/forms.php');
		$formsParams = array();
		$formsParams['privates'] = array('objectName' => 'forms');
		$formsParams['privates']['parentObjectName'] = $this->privates['objectName'];
		$forms = new forms($formsParams);
		$curForm = $forms->getForm('Create A form');
// var_dump('<pre>curForm-272--:', $curForm);	  exit;	
	
		if(!$curForm) {
			// block is completed
			$this->datam['regions'][2][0] = $thisRegion;
			return false;
		}
		
		$curForm['object_type'] = 'form';
		$curForm['object_renderby'] = '';
		
//  var_dump('<pre>curForm-267--:', $curForm);	  exit;
 
		$this->mergeKid($forms);
		
		$curForm['fields'] = $this->setFormValues($curForm['fields']);

		$thisRegion[0][0] = $curForm;

// var_dump('<pre>curForm-52:', $curForm);  exit;
		
// var_dump('<pre>curForm-291:', $curForm['form']);	  exit;		
	
		
		// block is completed
		$this->datam['regions'][2][0] = $thisRegion;
		
// var_dump('<pre>thisRegion-52:', $thisRegion);  exit;
		
		$thisRegion = array();	
	

	}

	
	
	
	/**
	*	gets values from $this->datam
	*	$fields must come in $fields[$h1][$v1] = $fields structure
	*	
	*/
	protected function setFormValues($fields) {

		foreach($fields as $h1 => $row) {
			foreach($row as $v1 => $field) {

// var_dump('<pre>field-307: ', $field);   // exit;	
			
					$field_name = $field['field_name'];
					if(!empty($this->datam[$field_name])) {
						$field['value'] = $this->datam[$field_name];
					}
					else {
						$field['value'] = '';
					}
					$fields[$h1][$v1] = $field;
			}
		}
		
		return $fields;
	}
	
}


