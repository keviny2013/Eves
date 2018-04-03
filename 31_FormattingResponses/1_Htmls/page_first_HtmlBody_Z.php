<?php

try {
	$page_first_HtmlBody = '<body ' . $datam['bodyAttributes'] . '>';
	// $page_first_HtmlBody .= '<h3>pageTitle: ' . $datam['pageTitle'] . '<h3>';
		
			// 5 rows (r) and 5 columns (c), not mandatory
			$regions = array();
			for($r = 1; $r < 6; $r++) {
				for($c = 1; $c < 6; $c++) {
					$aRegion = 'region' . $r . '_' . $c;
					if(empty($datam['regions'][$aRegion]['content'])) {
						break;
					}
					$page_first_HtmlBody .= '<div id="' . $aRegion .'" class="region" style="' . $datam['regions'][$aRegion]['css'] . '" '  . $datam['regions'][$aRegion]['attributes'] . ' >' . $datam['regions'][$aRegion]['content'] . '</div>';
				}				
			}
			
} catch (Exception $e) {
	$toCaller[] = array('Error', 'Undefined error. 1101');	
	$toDeveloper[] = array('page_first_HtmlBody.php', $e->getMessage());		
}

	
