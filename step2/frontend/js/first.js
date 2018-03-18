/**

	1- Passing PHP arrays or objects to Javascript: Dont use colon or semicolon so Javascript does not 
		have to do JSON.parse()
		Example: 
		$regionsJason = json_encode($regions);
		$addJsCodeBeforeLoad = array(' var regions = ' . $regionsJason . '; ');
		

		
*/


var eVe = {};



  