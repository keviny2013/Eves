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
		
		5- window.onload = function() ( In htmlHead.php )
			run render.js first
				then call eVe.init(). 
					Document.ready level tasks must be in eVe.init().
					
	3- Controlling links and form submits through eVe.init()
		
*/

alert("First, make sure Ckeditor folder exists under js/libraries.\nThen, fill in form fields and click submit button.");

var eVe = {};
		
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
					// @toDo : maybe security issue
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
*	@toDo : area name 'textarea_comment' should not be hardcoded
Form validation through Javascript
*	
*/
eVe.validateForm = function(el) {
	if(!el.id) {
		return false;
	}
	var checked = [];
	var varName = 'pregCheck_' + el.id;
	var patterns = eVe[varName];
	if(typeof(patterns) !== 'object' || typeof(patterns.length) != 'undefined') {
		checked['unacceptables']['patterns'] = 'Missing patterns.';
		return checked;
	}
	
	var kids = el.children;
	var datam = {};
	var kid, curname, curvalue;

	for(i = 0; i < kids.length; i++) {
		kid = kids[i];
		if(typeof(kid.name) != 'undefined') {
			if(kid.name == 'textarea_comment' )	{
				kid.value = CKEDITOR.instances.textarea_comment.getData();
				datam[kid.name] = kid.value;				
			}
			else {
			  datam[kid.name] = kid.value;
			}
		}
	
	}
	
	var checked = eVe.checkPregMatch(patterns, datam);

// console.log('countun acceptables--126: ' + checked.unacceptables.length);	

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

// console.log('countun validateds--143: ' + checked.validateds.length);
	
	return checked;
	
}



/**
*	@explanation :	checks size and characters of datam against pattern
	Returns unmatched fields as array
*	Warning: Sensitive data like password must be protected from display
*/
eVe.checkPregMatch = function(patterns, datam) {
	var checked = [];
	var unacceptables = [];
	var validateds = [];
	var inputs = [];
	var cinput, aMatch, fieldName;
	var row = [];
	for(fieldName in patterns) {
		if(patterns.hasOwnProperty(fieldName))	{
			if(typeof(patterns[fieldName]) == 'undefined') {
				row = [fieldName, ''];
				validateds.push(row);				
				continue;
			}
			pattern = patterns[fieldName];
			
			var newpattern = new RegExp(pattern);		
			inputs = datam[fieldName];
			// if inputs is array
			if(typeof(inputs) == 'object' && typeof(inputs.length) != 'undefined') {
				var numRows = inputs.length;
				for(i = 0; i < numRows; i++) {
					cinput = inputs;
					
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
	var arow, fieldName;
	var replaceId = 'b_4_0';
	
	var curl = 'index.php?requestName=' + aform.getAttribute("action");
	var datam = {};
	datam['requestName'] = aform.getAttribute("action");
	
	var url = 'index.php';
	var data = {};
	data['requestName'] = aform.getAttribute("action");
	
	for(i = 0; i < checked.validateds.length; i++) {
		arow = checked.validateds[i];
		curl += '&' + arow[0] + '=' + encodeURIComponent(arow[1]) ;
		data[arow[0]] = arow[1];
	}

	try
	{	
		// eVe.ajaxCall(curl, datam, replace);
		alert('Form is ready for Ajax call.');
	}
	catch(err)
	{
		// @toDo : save error locally and report
		alert('ERROR-249: ' + err);
	}
	
	
	return true;;

	
}


//--------------------  end: validation operations ------------------------------


  