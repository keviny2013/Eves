<?php

// @toDO	Remove html from here, use theme ( an array of html element, html2D template)




/**
	@toDo	move this to folder around: Format Html2D section 
*	@process : 
*		Displays $title
*		Loops through $messages and displays $key : $message on each line
*
*/
function writeMessages($messages, $title = 'Messages') 
{
	if(empty($messages)) {
		return '';
	}
	
	$out = '';
	if($title) {
		$out .= '<p class="toCaller"> <span class="subtitle">' . $title . '</span> ';
	}
	
	// @toDo
	// echo '<br>gettype($messages) : ' . gettype($messages) ;
	
	if(gettype($messages) == 'object') {
		$messages = (array) $messages;
	}
	
	if(!is_array($messages)  && !empty($messages)) {
		$out .= '<br>34: ' . $messages;
		return $out;
	}

	foreach($messages as $key => $message) {
		if(gettype($message) == 'object') {
			$message = (array) $message;
		}
		if(is_array($message)) {
			if(!empty($message)) {
				$out .= '<br>' . writeMessages($message, $key);
			}			
		} 
		else {
			$out .= '<br>' . $key . ' : ' . $message;
		}
	}
	
	$out .= '</p>'; 
	
	return $out;
}


