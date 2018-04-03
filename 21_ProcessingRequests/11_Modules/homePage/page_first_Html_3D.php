 <?php

 
require_once(DOCROOT . '/21_ProcessingRequests/_ProcessRequest.php');

/** 
	*	Step 1- Check permissions
	*	Step 2- Settings
	*	Step 3- Build the page
	
	*	Below structure is for : formatType = Html
	*	cube -> cubicles > blocks > elements -> children

*/

class page_first_Html extends eVe
{
	protected $permissionsRequired = array();
	
	function __CONSTRUCT($params, $datam = array()) {
		// Step 1- Set permissions : Page_first does not need anything extra. Default permissionsRequired value is set in eVe class
			// $this->metadatam['permissionsRequired'] = array('securityPass' => 1);
			
			// R*** means remove
			$this->metadatam['toCaller']['okdok'] = 'okdok from page_first';
		
			parent::__Construct($params);

			// R***  page_first does not accept any incoming data at all


		// Step 2- Settings
			$this->datam = $datam;
			$this->metadatam['formatType'] = 'Html';
			$this->metadatam['htmls'] = array(DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HttpHeader.php', 
			DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_renderHtmlBody.php', DOCROOT . '/31_FormattingResponses/1_Htmls/page_first_HtmlFooter.php', );
			
		
		//	Step 3- Build the page as cubes
			$this->datam['pageTitle'] = 'WeeklyZip : Local to Global.';
			$this->datam['bodyAttributes'] = '';
			
			$this->buildCubes();


		if(!$this->status) {
			$this->status = 'ok';
		}
		
	}
	// end of __CONSTRUCT()

	/**
	*	3 dimensional structure : cube -> cubicles > blocks > elements -> children
		x, y, z shows positions. Not width, height, length.
		A cube is defined like: $cubes[x1][y1][z1]
		A cube's properties: (array) meta, numCubicles,  (array) cubicles, 
		A cubicle is defined like: $cubes[x1][y1][z1][x2][y2][z2]
		A cube's properties: (array) meta, numElements, (array) elements, 
		
		An element is defined like: $cubes[x1][y1][z1] [x2][y2][z2] [x3][y3][z3]
		An element's properties: (array) meta, numChildren,  (array) children, 
		A child is defined like: $cubes[x1][y1][z1] [x2][y2][z2] [x3][y3][z3] [x4][y4][z4]
		A child's properties: (array) meta, (array) attributes, (array) classes, (array) csses, (array) javascripts
 

	*	
	*/
	protected function buildCubes() {
		$this->numCubes = 3;
		$this->datam['cubes'] = array();
			// This array will build (name => domId) pair all cubicles and blocks so we can pass the user friendly names to Javascript
			$cubicleNames = array();

				// build the page cubicle by cubicle
				// build each cubicle block by block
				$this->cubicle1();
					$cubicleNames['Header']['Region'] = 'r_0_0';
					$cubicleNames['Header']['Header block'] = 'b_0_0_0_0';
				$this->cubicle2();
					$cubicleNames['Middle Region']['Region'] = 'r_1_0';
					$cubicleNames['Middle Region']['Navigation Block'] = 'b_1_0_0_0';
				$this->cubicle3_1();
				$this->cubicle3_2();
					$cubicleNames['Middle Region']['Region'] = 'r_1_1';
					$cubicleNames['Middle Region']['Summer Attractions Block'] = 'b_1_1_0_0';
					$cubicleNames['Middle Region']['Sunset Block'] = 'b_1_1_0_1';			
		

		

	}


	@toDo : cubicles must be converted to 3D
	
	
	/**
	*	This cubicle has 1 block
	*	1st block:  logo, login_form, pageTitle
	*	
	*/
	protected function cubicle1() {
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
		$this->datam['cubes'][0][0][0][0] = $thisBlock;
			
		
	}
	
	/**
	*	This cubicle has 1 block
	*	1st block:  Navigation links
	*	
	*/
	protected function cubicle2() {
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
		$formsParams['parentObjectName'] = $this->metadatam['objectName'];
		$forms = new forms($formsParams);
		$curForm = $forms->getForm('Simple Login Form');
		$this->mergeKid($forms);
		$curForm['fields'] = $this->setFormValues($curForm['fields']);
		
// var_dump('<pre>curForm-124:', $curForm['fields']);	  exit;
	
		$thisBlock[3][0] = array('htmltag' => 'Form');
		$thisBlock[3]['formatType'] = 'db1';		
		$thisBlock[3]['form'] = $curForm['form'];		
		$thisBlock[3]['fields'] = $curForm['fields'];
		$thisBlock[3]['labels'] = $curForm['labels'];		

		
// var_dump('<pre>curForm-184:', $thisBlock[3]['kids']); exit;
		
		// block is completed
		$this->datam['cubes'][1][0][0][0] = $thisBlock;

	}
	
	/**
	*	1st block of 3rd cubicle
	*	1st block:  $toCaller, article with title
	*	
	*/
	protected function cubicle3_1() {
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
		$this->datam['cubes'][1][1][0][0] = $thisBlock;
		$thisBlock = array();	
	

	}

	
	/**
	*	2nd block of 3rd cubicle
	*	2nd block:  Sunset
	*	
	*/
	protected function cubicle3_2() {
		$thisBlock = array();
		
		$thisBlock[0][0] = array('htmltag' => 'Asis');
		$thisBlock[0]['asis'] = '<p class="subtitle">Sunset</p>';
			 
		// image
		$thisBlock[1][0] = array('htmltag' => 'Img', 'src' => '31_FormattingResponses/21_Images/sunset.jpg', 'alt' => 'Img: Sunset.', 'class' => array('floatRight', 'imgWidget5_3'));		
		

		
		// block is completed
		$this->datam['cubes'][1][1][0][1] = $thisBlock;
			

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


