<?php
/*
@name			:	displayFunctions.php 
@description	:	common functions to render datam for output

passed the test on: *** 

*/


/*  
	var_dump style loops
	you can  write the result into log files
*/
function varDumpLoop($cparent, $arr, $ctabs)
{
	global $countLoop;
	
	$countLoop++;
	if ($countLoop > 100)
	{
		return;
	}
	
	$out = '';
	$out .= '<br>' . str_repeat('--', $ctabs).$cparent;
	
	$tabs = $ctabs;
	$parent = $cparent;
	
	foreach($arr as $key => $value)
	{
		if (is_object($value))
		{
			$value = (array) $value;
		}
		
		if (is_array($value))
		{
			if(empty($cparent))
			{
				$cparent = $key;
			}
			else
			{
				$cparent = $parent . '[' . $key . ']';	
			}
			$ctabs++;
			$out .= varDumpLoop($cparent, $value, $ctabs);
		}
		else
		{
			$ctabs++;
			$out .= '<br>' . str_repeat('...', $ctabs). $cparent . '[' . $key . ']'	. ' : ' . $value;		
		}
		
		$cparent = $parent;
		$ctabs = $tabs;
	}
	
	$parent = '';
	$ctabs = 0;
	
	return $out;
}


/* single column table list */
function simpleList($title = null, array $arr)
{
// var_dump('102 simpleList', $arr);	
	if(!is_array($arr) || count($arr) < 1)
		return false;
	
	$out = '<hr><h4>' . $title . '</h4>';
	
	$out .= '<table>';
	foreach($arr as $key => $val)
	{
		$out .= '<tr><td>' . $key . '</td><td>';
		if(is_array($val))
		{
			$out .= '<span style="color:yellow">...Array</span>';
		}
		elseif(is_object($val))
		{
			$out .= '<span style="color:yellow">...Object</span>';
		}
		else
		{			
			$out .=  $val;
		}
			
		$out .= '</td></tr>'; 
	}
	
	$out .= '</table>';
	
	return $out;
	
}

		