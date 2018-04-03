<?php


/**
*
*	This code will convert arrays to HTML code. Arrays must represent a DOM element.
*	- These DOM elements may have childreN; Table, form, ul, ol, div, p
*	- Methods will be called from (page_first)_renderHtmlBody.php file as. $method = 'render' . $htmltag . '_' . $element['element_renderby'];
*	So, we will have renderForm, renderA, renderImg, etc methods and 
*	We might have custom 'element_renderby's like renderImg_100, renderImg_400, renderImg_1000, etc.
*
*/
class renderHtmlTags extends eVe
{

	protected $request = '';

	public function __CONSTRUCT($params)
	{
		parent::__Construct($params);
		
	// var_dump('<pre>img:', $element); exit;
	
		
	}



	
	public function render_img__($element) {
// var_dump('<pre>element: ' , $element); exit;

		$rendered = '<img ' . $element['element_attributes'];

		if($element['element_classes']) {
			$rendered .= ' class="' . $element['element_classes'] .'"';			
		}
		$rendered .= '>';

// echo '<br>' . $rendered; 
// echo '<br>rendered-41: ' . htmlspecialchars($rendered); exit;

		return $rendered;
	}


	
	/**
	*	method: POST
	*
	*/
	public function render_form__($element) {
// 
var_dump('<pre>element-52: ' , $element); exit;
		
		$rendered = '<form name="' . $element['field_name'] . '" '	. $element['element_attributes'];
		if($element['element_classes']) {
			$rendered .= ' class="' . $element['element_classes'] .'"';			
		} 
		$rendered .= ' method="POST">';

		$rendered .= $this->renderKids($element['kids']);
		
		$rendered .= ' </form> ';
// echo 'rendered: ' . htmlspecialchars($rendered);
// echo '<br>' . $rendered; exit;
		
		return $rendered;
	}	


	
	/**
	*	New line after element: $el[0]['display'] == 'br'
	*/
	protected function renderKids($kids) {
		$rendered =  '';
		
	// var_dump('<pre>kids-65:', $kids);		exit;
	
		foreach($kids as $h3 => $row3) {
			foreach($row3 as $v3 => $element) {
				// $kid = $regions[$h1][$v1][$h2][$v2][$h3][$v3];
	// 	var_dump('<pre>htmltag-65:', $element);		exit;
				if($v3 === 0) {
					// adding class block
					$element['element_classes'] .= ' block';
				}
				else {
					// adding class inline
					$element['element_classes'] .= ' inline';
				}
				if(!empty($element['field_htmltag'])) {
					
					$method = 'render_' . $element['field_htmltag'] . '_' . $element['field_htmltype'] . '_' . $element['element_renderby'];
					$rendered .= $this->$method($element);

				}
				else {
	// var_dump('<pre>htmltag-65:', $element);		exit;						
					$this->metam['toDeveloper']['page_first_renderHtmlBody.php-45'] = 'htmltag is missing: domId: ' . $domId;
					// R***						
					//<pre>' . var_export($datam, true) . '</pre><br>';
					// var_dump('<pre>datam:', $datam);  exit;	
				}
			}
			
		}	
		
		return $rendered;
		
	}



	
	public function render_input_text_($element) {
// var_dump('<pre>element-157: ' , $element); exit;

		
		$rendered = '<input  name="' . $element['field_name'] . '" type="' . $element['field_htmltype'] . '" '	. $element['element_attributes'];
		
		if($element['element_classes']) {
			$rendered .= ' class="' . $element['element_classes'] .'"';			
		} 
		
		$rendered .= '>';
		

		return $rendered;
	}

	
	public function render_input_password_($element) {
		
		return $this->render_input_text_($element);
		
	}
	
	public function render_input_submit_($element) {
		
		return $this->render_input_text_($element);
		
	}	

	public function render_button_submit_($element) {
		
		$element['element_innerhtml'] = $element['field_name'];
		return $this->render_others($element);
		
	}	

	
	public function render_others($element) {
// var_dump('<pre>element-157: ' , $element); exit;
		
		$rendered = '<'	. $element['field_htmltag'] . ' ' . $element['element_attributes'];
		if($element['element_classes']) {
			$rendered .= ' class="' . $element['element_classes'] .'"';			
		} 
		$rendered .= '>';
		$rendered .= $element['element_innerhtml'] .'</' . $element['field_htmltag'] . '>';			

		return $rendered;
	}

	/**
	*	 @toDo : filter asis
	*	'Asis' is not a real HTML tag. We use it for texts, markups, etc.
	*	if $element['field_htmltag'] = 'asis' : $element['asis'] will be displayed as is
	*/
	public function render_asis__($element) {
		
		$rendered .= $element['asis'];

		return $rendered;
		
	}

	
//---------------------------------------------------------------------------------------------------------------	



	/**
	*	@todDo : formFields come from database
	*
	*/
	public function render_form__db1($element) {
		
// R***  var_dump('<pre>renderobject_db1: element-238:', $element);  // exit;

		$rendered = '<p class="subtitle">' . $element['form']['object_title'] . '</p> <form 
		name="' . $element['form']['object_name'] . '" 
		action="object_process_' . $element['form']['object_name'] . '" ';
		$rendered .= $element['form']['object_attributes'];
		$rendered .= '  method="POST"  >';
		
		// var_dump('<pre>fields--221: ', $element['fields']);   // exit;
		
		/*
			if($element['form']['name'] != 'simpleLogin') {
			 var_dump('<pre>fields--221: ', $element['fields']);   // exit;
			}
		*/
 
		$rendered .= $this->renderKidsInGrid($element['fields'], $element['titles']);
	
	
		$rendered .= ' </form> ';

		
		return $rendered;	


	}


	
	/**
	*	Displaying elements in grids
	*/
	protected function renderKidsInGrid($fields, $titles) {
		$rendered =  '';

// var_dump('<pre>fields--219:', $fields); exit;
		
		foreach($fields as $h1 => $row) {
			$rendered .=  '<div class="row">';
			foreach($row as $v1 => $el) {
				// $el = $fields[$h1][$v1];	
					$rendered .=  '<div class="cell">';
					$id_fields = $el['id_fields'];
// var_dump('<pre>td:', $el); exit;
						
					if(!empty( $titles[$id_fields])) {
						$title = $titles[$id_fields];
						$rendered .=  '<label for="' . $el['field_name'] . '" ' . $title['title_attributes'] . ' > ' 
						. $title['title_title'] . '</label>';
					}
					else {
						$rendered .= '<label></label>';
					}
						
// var_dump('<pre>el-363: ', $el); // exit;

					$rendered .= $this->renderByTag($el);
					$rendered .= '</div>';	// end of cell div
						
						/*
						if($title['position'] == 'before') {
							$rendered .=  '<label for="' . $el['name'] . '" ' . $title['attributes'] . ' > ' . $title['title'] . '</label> ';
							$rendered .= $this->renderByTag($el);
						}
						else if($title['position'] == 'before above') {
							$rendered .=  '<label for="' . $el['name'] . '" ' . $title['attributes'] . ' > ' . $title['title'] . '</title> <br>';
							$rendered .= $this->renderByTag($el);
						}				
						else if($title['position'] == 'after') {
							$rendered .= $this->renderByTag($el);
							$rendered .=  '<label for="' . $el['name'] . '" ' . $title['attributes'] . ' > ' . $title['title'] . '</title> ';
						}
						else if($title['position'] == 'after below') {
							$rendered .= $this->renderByTag($el);
							$rendered .=  '<br><label for="' . $el['name'] . '" ' . $title['attributes'] . ' > ' . $title['title'] . '</title> <br>';
						}
						*/

			}
		$rendered .=  '</div>';	// end of row div
		
		}

		
// echo '<br>Ok: ' . htmlspecialchars($rendered); exit;
	
	
		return $rendered;
		
	}

	
	/**
	*	Displaying elements in grids
	*/
	protected function renderByTag($element) {
// var_dump('<pre>el-412:', $el); exit;	
	

		$method = 'render_' . $element['field_htmltag'] . '_' . $element['field_htmltype'] . '_' . $element['element_renderby'];
		if(method_exists($this, $method)) {		
			$rendered .= $this->$method($element);	
		}
		else {
			$rendered .= $this->render_others($element);
		}
					
		return $rendered;
	
	}	
	

		
	/**
	*	
	*
	*/
	public function render_textarea__small($element) {
// var_dump('<pre>renderTextarea-211: ' , $element); exit;	
		$rendered = '';

		eVe::$publics['addJsFiles']['ckeditor.js'] = '1_Backbone/11_Libraries/ckeditor/ckeditor.js';
			
		$rendered .= '<textarea name="' . $element['field_name'] . '" type="small" >' . $element['value'] . '</textarea>';

		// $this->datam
		eVe::$publics['addJsOnload']['replacing textarea with ckeditor'] = 'CKEDITOR.replace("' . $element['field_name'] . '"); ';

		return $rendered;
		
	}	


	

// -----------------------------------------------------------------------------------------------

	/**
	*	fieldset is used to group the fields
	*/
	public function renderFieldset($element) {

		$rendered = '';
		
		
		return $rendered;
		
	}	

	
	public function renderH3($element) {
		$rendered = '<h3 ';
		$rendered .= $element['element_attributes'];
		$rendered .= '>' . $element['value'] . '</h3>';
		
		return $rendered;
		
	}
	
	public function renderP($element) {

		$rendered = '<p ';
		
		$rendered .= $this->getAttributes($element);
		
		$rendered .= '>';
		
		$rendered .= $element['text'];
		
		$rendered .= '</p>';


		return $rendered;
		
	}
	
	public function renderA($element) {
		$rendered = '<a href="' . $element['href'] . '" ';
		$rendered .= $this->getAttributes($element);
		$rendered .= '>' . $element['text'] . '</a>';

		return $rendered;
	}
	
	
	public function renderSpan($element) {

		$rendered = '<span ';
		
		$rendered .= $this->getAttributes($element);
		
		$rendered .= '>';
		
		$rendered .= $element['text'];
		
		$rendered .= '</span>';


		return $rendered;
		
	}

	
	public function renderRadio($element) {
// var_dump('<pre>radio-128: ' , $element);

		$rendered = '';
		if(strpos($element['element_renderby'], 'vertical')) {
			$rendered .= '<br>';
		}		
		// yesno radios
		if(strpos($element['element_renderby'], 'yesno') === 0) {
			$rendered .= 'Yes <input name="' . $element['name'] . '" value="1" type="radio" ' . $element['attributes'] . '  >';
			if(strpos($element['element_renderby'], 'vertical')) {
				$rendered .= '<br>';
			}
			$rendered .= 'No <input name="' . $element['name'] . '" value="2" type="radio" ' . $element['attributes'] . '  >';			
		}
		// status radios
		elseif(strpos($element['element_renderby'], 'status') === 0) {
			$rendered .= 'On <input name="' . $element['field_name'] . '" value="1" type="radio" ' . $element['element_attributes'] . '  >';
			if(strpos($element['element_renderby'], 'vertical')) {
				$rendered .= '<br>';
			}
			$rendered .= 'Off <input name="' . $element['field_name'] . '" value="2" type="radio" ' . $element['element_attributes'] . '  >';			
		}		
		
		return $rendered;
	}



	public function renderButton($element) {
		
// var_dump('<pre>element-renderButton: ' , $element); exit;

		$rendered = '<button name="' . $element['field_name'] . '" value="'	. $element['field_name'] . '" type="' 
		. $element['field_htmltype'] . '" ' . $element['element_attributes'] . ' >' . $element['field_name'] . '</button>';

	
		return $rendered;
	}

	
	/**
	*	'li's maybe another HTML element with a HTMLtag 
	*	otherwise will be printed as is: Plain text or 
	*	markup must be controlled during security check using htmlspecialchars if necessary
	*/
	public function renderUL($element) {

		
		$rendered = ' <ul ';
		$rendered .= $this->getAttributes($element);
		$rendered .= '>';

		$rendered .= $this->renderKids($element['kids']);
		
		$rendered .= ' </ul> ';
		
		return $rendered;
	}
	


	/**
	*	
	*
	*/
	public function renderSmallTextarea($element) {
// var_dump('<pre>radio-230: ' , $element);
		
		$rendered = '';
		$rendered .= '<textarea name="' . $element['field_name'] . '" type="small" >'
		 . $element['value'] . '</textarea>';
		

		
		return $rendered;
	}	

	
	/**
	*	method: POST
	*
	*/
	public function renderForm($element) {
		$rendered = '<form ';
		$rendered .= $element['element_attributes'];
		$rendered .= '>';

		$rendered .= $this->renderKids($element['kids']);
		
		$rendered .= ' </form> ';
// echo 'rendered: ' . htmlspecialchars($rendered); exit;
		
		return $rendered;
	}	

	
	// -------------------------  no tag functions

	
	/**
	*	Handles 4 parameters:  (array) attributes, (array) class, (string) style and (string) js
	*/
	protected function getAttributes($element) {
		
		$rendered = ' ';
		
		if(!empty($element['attributes'])) {
			foreach($element['attributes'] as $key => $val) {
				$rendered .= ' ' . $key . '="' . $val . '", ';
			}
			$rendered = trim($rendered, ', ');
		}

		
		if(!empty($element['class'])) {
			$rendered .= ' class="';
// echo '<br>class: ' .  var_dump($element['class']);
			
			foreach($element['class'] as $key => $val) {
				$rendered .= $val . ' ';
			}
			$rendered = trim($rendered, ' ');
			$rendered .= '" ';
		}
		
		if(!empty($element['style'])) {
			$rendered .= ' ' . $element['style'];
		}
		
		if(!empty($element['js'])) {
			$rendered .= ' ' . $element['js'];
		}
		
		return $rendered;
		
	}


	
	/**
	*	Displaying elements in grids
	*/
	protected function generateLabel($el) {
var_dump('<pre>el-411: ', $el); // 
exit;
		
		$rendered = '<label for="' . $el[0]['name'] . '" >' . $el[0]['name'] . '</label>';
		
	
	}

	
}
	
