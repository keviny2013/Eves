<?php

$htmlHead = '<!DOCTYPE html>
	<html>
	<head>
		<meta charset=UTF-8>
		<title>' . $this->protecteds['pageTitle'] . '</title>';
		
		// 	css files
	if(!empty($this->protecteds['cssFiles'])) {
		foreach($this->protecteds['cssFiles'] as $key => $cssFile) {
			$htmlHead .= '
			<link rel="stylesheet" type="text/css" href="frontend/css/' . $cssFile . '" >';
		}
	}
		// javascript files
	if(!empty($this->protecteds['jsFiles'])) {
		foreach($this->protecteds['jsFiles'] as $key => $jsFile) {
			$htmlHead .= '<script type="text/javascript" src="frontend/js/' . $jsFile . '"> </script>';
		}
	}
		
		// scripts before window.onload


	
		// window.onload
	$htmlHead .= '<script type="text/javascript" > window.onload = function() { '; 
	$htmlHead .=  ' eVe.render.allRegions(regions); ';
		if(!empty($this->protecteds['addJsCodeOnload'])) {
			foreach($this->protecteds['addJsCodeOnload'] as $key => $code) {
				$htmlHead .=  $code;
			}			
		}	
	$htmlHead .=  '  } </script>';
 
	$htmlHead .= '</head>';


