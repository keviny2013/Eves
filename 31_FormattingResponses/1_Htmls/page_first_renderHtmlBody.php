<?php

/*
*	Loop through regions > blocks > elements	
*	$rendered = new render($thisElement);
	$renderedProperties = $rendered->getProperties();
	$page_first_renderHtmlBody .= $renderedProperties['datam']['html'];
*
*
*/

$page_first_renderHtmlBody = '<body ' . $datam['bodyAttributes'] . '>';

require_once(DOCROOT . '/31_FormattingResponses/17_Tools/renderHtmlTags.php');
$renderParams = array();

$renderParams['privates']['objectName'] = 'renderHtmlTags';
$renderParams['privates']['parentObjectName'] = $this->privates['objectName'];
$renderHtmlTags = new renderHtmlTags($renderParams);

$regions = $datam['regions'];


// @ toDo : poisition start at: x - y - z : maybe linear or angle
//  @ toDo : distance: 			 width - height - length 


foreach($regions as $h1 => $row1) {
	foreach($row1 as $v1 => $region) {
		// $region = $regions[$h1][$v1];
		$domId = 'r_' . $h1 . '_' . $v1;
		$page_first_renderHtmlBody .= '<div id="' . $domId . '" class="region ';
		if($v1 === 0) {
			// adding class block
			$page_first_renderHtmlBody .= ' block"';
		}
		else {
			// adding class inline
			$page_first_renderHtmlBody .= ' inline';
		}
		$page_first_renderHtmlBody .= '>';
		
// var_dump('<pre>region-65:', $region);		exit;

		
		foreach($region as $h2 => $row2) {
			foreach($row2 as $v2 => $element) {
				// $element = $regions[$h1][$v1][$h2][$v2];
				if($v2 === 0) {
					// adding class block
					$element['element_classes'] .= ' block';
				}
				else {
					// adding class inline
					$element['element_classes'] .= ' inline';
				}
				if(!empty($element['field_htmltag'])) {
					
					$method = 'render_' . $element['field_htmltag'] . '_' . $element['field_htmltype'] . '_' . $element['element_renderby'];
					if(method_exists($renderHtmlTags, $method)) {
						$page_first_renderHtmlBody .= $renderHtmlTags->$method($element);
					}
					else {
						$page_first_renderHtmlBody .= $renderHtmlTags->render_others($element);
					}

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
		$page_first_renderHtmlBody .= '</div>'; 		
	}


} 
	
// R***  echo '<br>line 78: <br>' . $page_first_renderHtmlBody;  exit;



