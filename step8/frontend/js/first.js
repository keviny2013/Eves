/**

	1- Passing PHP arrays or objects to Javascript: Dont use colon or semicolon so Javascript does not 
		have to do JSON.parse()
		Example: 
		$regionsJason = json_encode($regions);
		$addJsCodeBeforeLoad = array(' var regions = ' . $regionsJason . '; ');
		
	2- Order of javascript:
		1- first.js	( Use $jsFiles to add such files )
		2- render.js	( Use $jsFiles to add such files )
		3- additional javascript files or libraries	( Use $jsFiles to add such files )
			
		4- Javascript codes in html head. ( Use $addJsCodeBeforeLoad for this purpose. )
			Example: $addJsCodeBeforeLoad = array(' var regions = ' . $regionsJason . '; ');
		
		5- window.onload = function() ( in htmlHead.php )
			run render.js first
				then call eVe.init(). 
					Document.ready level tasks must be in eVe.init().
					
	3- Controlling links and form submits through eVe.init()
		
*/

alert("First, make sure Ckeditor folder exists under js/libraries.\nThen, fill in form fields and click submit button.\nAfter each submit, you should see the submitted data under the form.");

var eVe = {};
// Tracks ajax operations. Value comes from Ajax response.
eVe.ajaxUnsafe = false;

eVe.dumpStr = '';
eVe.dumpCountObj = 0;
eVe.dumpCountRows = 0;
eVe.dumpAssocLimit = 100;
		
/**
	Use eVe.init when you need to add something after (window.onload and rendering the page)
	Document.ready level tasks must be in eVe.init().
*/
eVe.init = function(){
	
	// Controlling links 
	eVe.controlLinks(document);

		// Controlling submit 
	eVe.controlForms(document);
	
	
	return true;
}


//--------------------  begin: validation operations ------------------------------


/*
*	control links in el
*/
eVe.controlLinks = function(el) {
	var els = el.getElementsByTagName('a');
	for(var i = 0; i < els.length; i++) {
		var el = els[i];
		els[i].onclick = function(event) {
			event.preventDefault();
			eVe.validateLink(this);
		}
	}
	
}


/*
*	control forms in el
*/
eVe.controlForms = function(el) {
	var forms = el.getElementsByTagName('form');
	var aform;
	if(forms.length > 0) {
		for(var i = 0; i < forms.length; i++) {
			aform = forms[i];
			forms[i].onsubmit = function(event) {
				event.preventDefault();
				var checked = eVe.validateForm(this);
	
				if(typeof(checked.unacceptables) != 'undefined' && checked.unacceptables.length > 0) {
					alert('Issue with inputs. Form can NOT be submitted');
					// @toDo : display messages
					console.log('unacceptables: ' + JSON.stringify(checked.unacceptables));						
					return false;
				}
				else if(typeof(checked.validateds) != 'undefined' && checked.validateds.length > 0) {
					alert('Form can be submitted');
					eVe.submitForm(aform, checked);
				}
				else {
					alert('There is nothing to submit');

				}
			}			
		}
	}

}

	
/*
*	@toDo : eVe.validateLink
*/
eVe.validateLink = function(el) {
	alert("This is function eVe.validateLink: \n" + location);
	
	return true;
}


/*
*	@toDo : area name 'comment' should not be hardcoded
Form validation through Javascript
*	
*/
eVe.validateForm = function(el) {
	if(!el.id) {
		return false;
	}
	var checked = {};
	var varName = 'pregCheck_' + el.id;
	var patterns = eVe[varName];
	varName = 'requireds_' + el.id;
	var requireds = eVe[varName];	
	if(typeof(patterns) !== 'object' || typeof(patterns.length) != 'undefined') {
		checked['unacceptables'] = [];
		checked['unacceptables'] = ['patterns', 'Missing patterns.'];
		return checked;
	}
	
	var kids = el.children;
	var datam = {};
	var kid, curname, curvalue;

	for(i = 0; i < kids.length; i++) {
		kid = kids[i];
		if(typeof(kid.name) != 'undefined') {
			if(kid.name == 'comment' )	{
				kid.value = CKEDITOR.instances.comment.getData();
				datam[kid.name] = kid.value;				
			}
			else {
			  datam[kid.name] = kid.value;
			}
		}
	
	}
	
	checked = eVe.checkPregMatch(patterns, datam, requireds);
	

	if(typeof(checked['unacceptables']) != 'undefined' && checked['unacceptables'].length > 0) {
		return checked;
	}
	var tokenName = 'token_' + el.id;
	var token = eVe[tokenName];
	var row;
	if(!token) {
		row = ['token', 'Missing token.' ];
		checked['unacceptables'].push(row);
		return checked;			
	}
	row = ['token', token ];
	checked['validateds'].push(row);	

	return checked;
	
}



/**
*	@explanation :	checks size and characters of datam against pattern
	Returns unmatched fields as array
*	Warning: Sensitive data like password must be protected from display
*/
eVe.checkPregMatch = function(patterns, datam, $requireds) {
	var checked = [];
	var unacceptables = [];
	var validateds = [];
	var inputs = [];
	var cinput, aMatch, fieldName;
	var row = [];
	for(fieldName in patterns) {
		if(patterns.hasOwnProperty(fieldName))	{	// requireds
			if(typeof(patterns[fieldName]) == 'undefined') {
				row = [fieldName, ''];
				validateds.push(row);				
				continue;
			}
			pattern = patterns[fieldName];
			
			var newpattern = new RegExp(pattern);		
			inputs = datam[fieldName];
			// if inputs is array like checkboxes, drop down, radio buttons, etc
			if(typeof(inputs) == 'object' && typeof(inputs.length) != 'undefined') {
				var numRows = inputs.length;
				for(i = 0; i < numRows; i++) {
					cinput = inputs[i];
					
					if(!cinput && requireds.indexOf(fieldName) < 0) {
						row = [fieldName, ''];
						validateds.push(row);	
						continue;
					}
					// if (!pattern.test(cinput)) {
					aMatch = cinput.match(newpattern);						
					if(!aMatch) {
						if(typeof(cinput) == 'undefined' || !cinput) {
							row = [fieldName, 'Empty field'];
							unacceptables.push(row);
						}
						else {
							row = [fieldName, 'Does not comply'];
							unacceptables.push(row);							
						}				
					} 
					else if(cinput) { 
						row = [fieldName, cinput];
						validateds.push(row);				
					}
				}
			}
			else if(typeof(inputs) != 'undefined'){
				cinput = inputs;
				if(!cinput && requireds.indexOf(fieldName) < 0) {
					row = [fieldName, ''];
					validateds.push(row);	
					continue;
				}
					
				// if (pattern.test(cinput)) {
				aMatch = cinput.match(newpattern);
				if(!aMatch) {
					if(typeof(cinput) == 'undefined' || !cinput) {
						row = [ fieldName, 'Empty field'];
						unacceptables.push(row);	
					}
					else {
						unacceptables[fieldName] = cinput + ' : Does not comply: ';
						row = [fieldName, 'Does not comply'];
						unacceptables.push(row);							
					} 
				}
				else if(cinput) { 
					row = [fieldName, cinput];
					validateds.push(row);						
				}				
			}
			else {
				row = [fieldName, 'Missing field'];
				unacceptables.push(row);				
				
			}
		}
	}
	
	if(typeof(unacceptables.length) !== 'undefined') {
		checked.unacceptables = unacceptables;
	}
	if(typeof(validateds.length) !== 'undefined') {
		checked.validateds = validateds;
	}	
	
	return checked;
	
}


/*
*	gets validateds form inputs and token
*/
eVe.submitForm = function(aform, checked){
	var callback = 'eVe.processAjaxResponse';
	var arow, fieldName;
	var replaceId = 'b_5_1';
	
	var url = 'index.php';
	var data = {};
	data['requestName'] = aform.getAttribute("action");
	
	for(i = 0; i < checked.validateds.length; i++) {
		arow = checked.validateds[i];
		data[arow[0]] = arow[1];
	}
	
	try
	{	
		// eVe.ajaxCall(curl, datam, replace);
		eVe.ajaxCall(url, data, replaceId);
	}
	catch(err)
	{
		// @toDo : save error locally and report
		alert('ERROR-249: ' + err);
	}
	
	
	return true;;

	
}


//--------------------  end: validation operations ------------------------------

//--------------------  begin: Ajax operations ------------------------------


/**
* 
* generic ajax call with customized callback function
	eVe.ajaxUnsafe = response['eVe.ajaxUnsafe']
	https://xhr.spec.whatwg.org/
*/
eVe.ajaxCall = function(url, data, replaceId) {
	if(eVe.ajaxUnsafe == true)
	{
		alert('Error-275: Unsafe to send Ajax ');
		return false;
	}
	
	data.requestType = 'Ajax';
	
    var params = typeof data == 'string' ? data : Object.keys(data).map( 
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) } 
		).join('&'); 

	
	
	 var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 
	 xhr.open('POST', url); 
     xhr.onreadystatechange = function() { 
	 
         if (xhr.readyState>3 && xhr.status==200) { eVe.processAjaxResponse(xhr.responseText, replaceId);  } 
     }; 
     xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); 
     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
     xhr.send(params);
	 
     return xhr; 
	
}	// end eVe.ajaxCall


/**
* 
* generic ajax call with customized callback function
	eVe.ajaxUnsafe = response['eVe.ajaxUnsafe']
	https://xhr.spec.whatwg.org/
*/
function postAjax(url, data, success) { 
     var params = typeof data == 'string' ? data : Object.keys(data).map( 
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) } 
		).join('&'); 

 
	 var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 
	 xhr.open('POST', url); 
     xhr.onreadystatechange = function() { 
         if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); } 
     }; 
     xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); 
     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
     xhr.send(params); 
	 
     return xhr; 
} 



/*
*	replace.parentNode.innerHTML = eVe.render.asis(response); 
*	
*/
eVe.processAjaxResponse = function(response, replaceId){
	response = response.trim();
	response = JSON.parse(response);
	var repBlock = document.getElementById(replaceId);	
	if(repBlock && typeof(response.innerHTML) != 'undefined') { 
		var cdiv = document.createElement('DIV'); 
		cdiv.innerHTML = eVe.render.asis(response);
		repBlock.appendChild(cdiv);
		eVe.controlLinks(repBlock);
		eVe.controlForms(repBlock);
	}
	
}




//--------------------  end: Ajax operations ------------------------------



/*
	recursive eVe.dumpAssoc(name, obj) limited to eVe.dumpAssocLimit objects 
	and eVe.dumpAssocLimit rows
	var eVe.dumpStr must be global and set to '' before calling eVe.dumpAssoc(name, obj)
	vars eVe.dumpCountObj and eVe.dumpCountRows must be global and set to 0 before calling eVe.dumpAssoc(name, obj)
	Example: If you want to dump countries : 
		set to any number: eVe.dumpAssocLimit = 1000; 
		reset:  eVe.dumpStr = '';
		reset: eVe.dumpCountObj = 0;
		reset: eVe.dumpCountRows = 0;

		eVe.dumpAssoc('countries', countries);
		console.log(eVe.dumpStr);
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
				if(eVe.dumpCountObj > eVe.dumpAssocLimit)	{
					alert('Reached to overall limit ' + eVe.dumpAssocLimit + ' rows at object: ' + name );
					return false;
				}
				eVe.dumpAssoc(key, row);
			}
			else {
				eVe.dumpCountRows++;
				if(eVe.dumpCountRows > eVe.dumpAssocLimit)
				{
					alert('Stopping at row ' + eVe.dumpAssocLimit +  '. Object: ' + name );
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
