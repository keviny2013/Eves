<?php

	/*
	$htmls = $params['htmls'];
	$shipment = '';
	$numArray = count($htmls);
	if($numArray) {
		for($i = 0; $i < $numArray; $i++) {
			$cHtml = $htmls[$i];
			require_once($cHtml);
			$pos = strrpos($cHtml, '/') + 1;
			$cHtml = substr($cHtml, $pos);
			$pos = strrpos($cHtml, '.');
			$cHtml = substr($cHtml, 0, $pos);
		
			// $shipment .= $$cHtml;
		}
	
	}
	*/

// var_dump('datam---23:' , $datam);  exit;
	
	$shipment = array();
	/**
	*	$tips : if there is an error:  $shipment['error'] = '';
	*	$tips :  key 'innerdatam' is used to prevent any conflicts with datam, especially in javascript ajaxCall()
	*/
	$shipment['innerdatam'] = $datam;
	unset($datam);
	$shipment = json_encode($shipment);
	
	
	
