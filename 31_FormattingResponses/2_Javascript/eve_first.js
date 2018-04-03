/**

	1- Passing PHP arrays or objects to Javascript: Dont use colon or semicolon so Javascript does not have to do JSON.parse()
				$this->datam['addJs'][] = " eVe.regionNames = " . json_encode($regionNames) . "; ";

				
*/


// alert('This is first.js');

var eVe = {};

// Used in eVe.ajaxCall()
eVe.unsafe = false;
// last DOM id that was subject to ajax call
eVe.lastAjaxRegion = '';

// These 3 are needed by eVe.dumpAssoc() to process recursive loops (like var_dump in PHP)
eVe.dumpStr = '';
eVe.dumpCountObj = 0;
eVe.dumpCountRows = 0;

// scripts to be run by eval
eVe.evals = [];

/**
*	Warning:  window.onload = function(){  is declared in page_first_HttpHeader.php. It includes $addJsOnload s and calls eVe.init()
*		window.onload = function(){
			... some javascript code...
			eVe.init
		}
	so use eVe.init when you need to add something after window.onload
*/
eVe.init  = function(){
	// 	alert('This is init');
	// 	console.log('regions: ' + eVe.datam.regions.length);
	
	// Controlling submit buttons
	var els = document.getElementsByClassName('formSubmit');
	if(els) {
		for(var i = 0; i < els.length; i++) {
			var el = els[i];
			els[i].onclick = function(event) {
				event.preventDefault();
				eVe.validate(el.parentNode);
			}
		}
	}
	
	// Controlling links 
	// @toDo :  ajaxRedirect();
	var els = document.getElementsByTagName('a');
	for(var i = 0; i < els.length; i++) {
		var el = els[i];
		els[i].onclick = function(event) {
			//R*** 
			event.preventDefault();
			eVe.validate(this);
			// @toDo :  ajaxRedirect();
		}
	}


	if(eVe.evals.length > 0) {
		var thisEval = '';
		for(var i = 0; i < eVe.evals.length; i++) {
			thisEval = eVe.evals[i];
			// eval(thisEval);
		}
	}
	
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


/**
*	@description : Generic ajax call. Requires url to call and a customized callback function
	http://xhr.spec.whatwg.org/.
*/
eVe.ajaxCall = function (cUrl, datamstr, callback) {
	if(eVe.unsafe == true)
	{
		eVe.unsafeRepeat++;
		alert('Sorry cannot process ajax call.');
		return;
	}
	
	var xmlhttp;
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		 xmlhttp=new XMLHttpRequest();
	}
	else
	{	// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)	{
	
			//R*** 	alert('xmlhttp.responseText: ' + "\n" + xmlhttp.responseText);	
			
			if(xmlhttp.responseText) {			
				var response = JSON.parse(xmlhttp.responseText);
				if(typeof response['error'] !== 'undefined')
				{
					alert("Error: \n" + response['error']);
				}
				response = response['innerdatam'];
			}
			else {			
				alert('No datam returned from the server');
			}
			callback(response);	
	
		}
		/*
		else {			
			alert('Sorry, cannot get response from server. ');
			console.log('xmlhttp.status: ' + xmlhttp.status);
		}
		*/
	}
// alert(datamstr);

	// false means not async
	xmlhttp.open("POST", cUrl, true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("charset", "utf-8");
	xmlhttp.send(datamstr);
	
}	// end eVe.ajaxCall



/*
	recursive eVe.dumpAssoc(name, obj) limited to 200 objects and 200 rows
	var eVe.dumpStr must be global and set to '' before calling eVe.dumpAssoc(name, obj)
	vars eVe.dumpCountObj and eVe.dumpCountRows must be global and set to 0 before calling eVe.dumpAssoc(name, obj)
	Example:
		eVe.dumpStr = '';
		eVe.dumpCountObj = 0;
		eVe.dumpCountRows = 0;
		eVe.dumpStr = eVe.dumpAssoc('countries', countries);
*/
eVe.dumpAssoc = function(name, obj) {
	eVe.dumpStr += "\n\r" + name + "\n\r" ;
	
	for(key in obj) {
		if(obj.hasOwnProperty(key))	{
			var row = obj[key];
			// if object
			if(typeof(row) 	== 'object') {
				// if array : 	if( typeof(row.length) != 'undefined')
				eVe.dumpCountObj++;
				if(eVe.dumpCountObj > 199)	{
					alert('Stopping at 200th object.');
					return false;
				}
				eVe.dumpAssoc(key, row);
			}
			else {
				eVe.dumpCountRows++;
				if(eVe.dumpCountRows > 199)
				{
					alert('Stopping at 200th row.');
					return false;
				}			
				eVe.dumpStr += key + ' : ' + row + "\n";
			}
		}
		/*
		else it is a method/function, etc.
		*/
	}
	
	return true;
	// the result is eVe.dumpStr a global object
}



/**
*	@explanation :	checks size and characters 
*	Does not exclude the password from display
*/
eVe.checkPregMatch = function(patterns, datam) {
	var checked = {};
	checked['unacceptable'] = {};
	checked['sanitized'] = {};
	var inputs = {};
	var cinput, aMatch;
	var oks = {};
	var countUnacceptable = 0;
	for(fieldName in patterns) {
		if(patterns.hasOwnProperty(fieldName))	{
			pattern = patterns[fieldName];
			var newpattern = new RegExp(pattern);			
			inputs = datam[fieldName];
			// if array
			if(typeof(inputs) == 'object' && typeof(inputs.length) != 'undefined') {
				var numRows = inputs.length;
				for(i = 0; i < numRows; i++) {
					cinput = inputs;
					
					// if (!pattern.test(cinput)) {
					aMatch = cinput.match(newpattern);						
					if(!cinput.match(newpattern)) {
						if(typeof(cinput) == 'undefined' || !cinput) {
							checked['unacceptable'][fieldName] = 'Empty field';
							countUnacceptable++;
						}
						else {
							checked['unacceptable'][fieldName] = cinput + ' : Does not comply: ';
							countUnacceptable++;
						}				
					} 
					else if(cinput) { 
						checked['sanitized'][fieldName] = cinput;
					}
				}
			}
			else if(typeof(inputs) != 'undefined'){
				cinput = inputs;
				
				// if (pattern.test(cinput)) {
				aMatch = cinput.match(newpattern);
// alert("fieldName pattern inputs aMatch: \n" + fieldName + " : \n" + newpattern + " : \n" + inputs + " : \n" + aMatch );
 
				if(!aMatch) {
					if(typeof(cinput) == 'undefined' || !cinput) {
						checked['unacceptable'][fieldName] = 'Empty field';
						countUnacceptable++;
					}
					else {
						checked['unacceptable'][fieldName] = cinput + ' : Does not comply: ';
						countUnacceptable++;
					} 
				}
				else if(cinput) { 
					checked['sanitized'][fieldName] = cinput;
				}				
			}
			else {
				checked['unacceptable'][fieldName] = 'Missing field';
				countUnacceptable++;
			}
		}
	}
	
	checked['countUnacceptable'] = countUnacceptable;
	
	// token
	if(!countUnacceptable && datam['token']) {

		
		var pattern = /^[a-zA-Z0-9\_\-\.]{11,64}$/;
		aMatch = datam['token'].match(pattern);
		if(aMatch) {
			checked['sanitized']['token'] = eval();
		}
		else {
			checked['unacceptable']['token'] = 'Token issue';
			checked['countUnacceptable'] = 1;
		}
	}
	
	return checked;
	
}



/*
*/
eVe.validate = function(el){
	//R*** 
	alert('@toDo : Links will be handled by Javascript: ');
	// R***
	return;
	
	
	if(!el.id) {
		return false;
	}
	var varName = 'pregCheck_' + el.id;
	var patterns = eval(varName);
	var kids = el.children;
	var datam = {};
	var kid, curname, curvalue;

	for(i = 0; i < kids.length; i++) {
		kid = kids[i];
		datam[kid.name] = kid.value;
	}
	
	var checked = eVe.checkPregMatch(patterns, datam);
	if (!checked['countUnacceptable']) {
		var phpprocessor = 'form_process_' + el.id;
		checked['sanitized']['request'] = phpprocessor;
		// name of the token variable for this form
		var token = 'token_' + el.id;
		checked['sanitized']['token'] = eval(token);
// alert('request: ' + checked['sanitized']['request'] + "\n loginname: " + checked['sanitized']['loginname'] + "\n password: " + checked['sanitized']['password'] + "\n token: " + checked['sanitized']['token']);
		var datamstr = JSON.stringify(checked['sanitized']); 
		cUrl = 'localhost/2015/eVe/';
		eVe.lastAjaxRegion = el.id;
		cUrl = '#';
		eVe.ajaxCall(cUrl, datamstr, eVe.processAjaxResponse);

	}
	else {
		eVe.dumpStr = '';
		eVe.dumpStr = eVe.dumpAssoc(el.id, checked['unacceptable']);
		alert(eVe.dumpStr);
	}
	
	return checked;
	
}


/*
*	@description : Processes the response returned from ajaxcalss
*	we need a wrapper div around each form with: id=div_{form_id}
*	OR we need response should return DOM id as response['region']
*	
*/
eVe.processAjaxResponse = function(response){

	//R*** alert('response: ' + response);

	if(typeof(response['region']) != 'undefined') {
		document.getElementById(response['region']).innerHtml = response;
		// document.getElementById(response['region']).innerHtml = response;
	}
	else {
		var wrapperDivId = 'div_' + eVe.lastAjaxRegion;
		// document.getElementById(wrapperDivId).innerHTML = response;
		document.getElementById(wrapperDivId).innerHTML = response;
	}
	
}

