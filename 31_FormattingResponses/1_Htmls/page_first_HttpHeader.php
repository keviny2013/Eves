<?php

// var_dump('<pre>css: ' , eVe::$publics['addJsFiles']);  exit;

$page_first_HttpHeader = '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>' . $this->datam['pageTitle'] . '</title>';
	
	$page_first_HttpHeader .= '<link rel="stylesheet" type="text/css" href="31_FormattingResponses/3_Css/eve_first.css" >';
			
	if(!empty(eVe::$publics['addCssFiles'])) {
		foreach(eVe::$publics['addCssFiles'] as $key => $cssPath) {
			$page_first_HttpHeader .= '
			<link rel="stylesheet" type="text/css" href="' . $cssPath . '" >';
		}
	}
	
	if(!empty(eVe::$publics['addCss'])) {
		$page_first_HttpHeader .= '<style>' . implode(" ", eVe::$publics['addCss']) . '</style>';
	}	
	
	$page_first_HttpHeader .= '<script type="text/javascript" src="31_FormattingResponses/2_Javascript/eve_first.js"> </script>';
	$page_first_HttpHeader .= '<script type="text/javascript" src="31_FormattingResponses/2_Javascript/eve_render.js"> </script>';
			
	if(!empty(eVe::$publics['addJsFiles'])) {
		foreach(eVe::$publics['addJsFiles'] as $key => $jsPath) {
			$page_first_HttpHeader .= '<script type="text/javascript" src="' . $jsPath . '"> </script>';
		}
	}

	// these JS runs before document.onload
	$page_first_HttpHeader .= '<script type="text/javascript" >  '; 
	if(!empty(eVe::$publics['addJsBeforeLoad'])) {
		$page_first_HttpHeader .=  implode(" ", eVe::$publics['addJsBeforeLoad']) ;
		// Javascript already recognizes regionNames and blockNames as array(object). No need to JSON.parse.
	}
	$page_first_HttpHeader .=  ' </script>';	
	
	// these JS runs after window.onload
	$page_first_HttpHeader .= '<script type="text/javascript" > window.onload = function() { '; 
	if(!empty(eVe::$publics['addJsOnload'])) {
		$page_first_HttpHeader .=  implode(" ", eVe::$publics['addJsOnload']) ;
		// Javascript already recognizes regionNames and blockNames as array(object). No need to JSON.parse.
	}
	$page_first_HttpHeader .=  ' eVe.renderHtmlBody();   eVe.init();  } </script>';
	
$page_first_HttpHeader .= '</head>';



	
