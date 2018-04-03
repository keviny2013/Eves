<?php

		// 		var_dump('<pre>this->metam-- - 54:' , $this->metam);  // exit;
		
	$htmls = $metam['htmls'];
	$this->shipment = '';
	$numArray = count($htmls);
	if($numArray) {
		for($i = 0; $i < $numArray; $i++) {
			$cHtml = $htmls[$i];
			require_once($cHtml);
			$pos1 = strrpos($cHtml, '/') + 1;
			$cHtml = substr($cHtml, $pos1);
			$pos1 = strrpos($cHtml, '.');
			$cHtml = substr($cHtml, 0, $pos1);
		
			$this->shipment .= $$cHtml;
		}
	
	}
	
// echo 'httpHeaderFile: ' . $metam['httpHeaderFile'];  exit;

	require_once($metam['httpHeaderFile']); 
	
	$this->shipment = $page_first_HttpHeader . $this->shipment;