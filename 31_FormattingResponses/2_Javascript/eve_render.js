/**
	This page is dedicated to rendering sanitized and formatted data into HTML
	Incoming data  
		1- must be sanitized for safety
		2- must be formatted to meet DOM structure
		3- must be converted to json string

	Passing PHP arrays or objects to Javascript: 
		Dont use colon or semicolon so 
			Javascript does not have to do JSON.parse()
				PHP: this->datam['addJs'][] = " eVe.datam.regions = " . json_encode(regions) . "; ";

				
*/

// var CKEDITOR_BASEPATH = '1_Backbone\\11_Libraries\\ckeditor\\';
eVe.jsAfterRender = [];
	 
/** 
*	This method renders eVe.datam.regions and replaces with body.innerHTML
	To dump the data :
		eVe.dumpStr = '';
		eVe.dumpAssoc('regions', eVe.datam.regions);
		console.log(eVe.dumpStr);	
*	3 steps:  
		1- renderHtmlBody -> 
		2- eVe.renderRegion(aregion) -> 
		3- renderEement (mostly one function for each HTML tag))
*	functions are named like: render_htmltag_htmltype_renderbyOption
*	renderby is customized option. Customized render and display. Examples:
	render_input_radio_yesno , render_input_radio_dual (2 options)
	render_form__ : means no htmltype no renderby
	render_form__horizontal : means no htmltype. renderby = horizontal
	render_form__sharedQuestions : renderby = sharedQuestions
*/
eVe.renderHtmlBody = function(){
	
	// console.log('number of regions: ' + eVe.datam.regions.length);
		
	var regions = eVe.datam.regions;
		
	var h1, v1, row1, regionId, region, columnNumber;
	var renderHtmlBody = '';
	for(h1 in regions)	{
	if(regions.hasOwnProperty(h1))	{
		var row1 = regions[h1];
		columnNumber = 0;		
		for(v1 in row1)	{
		if(row1.hasOwnProperty(v1))	{			
				region = row1[v1];
				regionId = 'r_' + h1 + '_' + v1;
				// 	console.log('regionId: ' + regionId);

				renderHtmlBody += '<div id="' + regionId + '" class="region ';
				columnNumber++;
				if(columnNumber === 1) {
					// adding class block
					renderHtmlBody += ' block"';
				}
				else {
					// adding class inline
					renderHtmlBody += ' inline';
				}
				renderHtmlBody += '" >';

				renderHtmlBody += eVe.render_loop(region, regionId);
				renderHtmlBody += '</div>';					
		}
		}
	}
	}
	
     document.getElementsByTagName('body')[0].innerHTML = renderHtmlBody;

	 // CKEDITOR.replace("form_help");  is not working otherwise
	 if(eVe.jsAfterRender.length > 0) {
		 var thisEval = '';
		 for(var i = 0; i < eVe.jsAfterRender.length; i++) {
			thisEval = eVe.jsAfterRender[i];
			eval(thisEval);
		 }
	 }
	 
	 // eVe.init();
	 
	return true;
}


/**
*	loops through an element (parent)
*/
eVe.render_loop = function (parent, regionId) {
	if(typeof(parent) !== 'object' || !parent) {
		return;
	}
	

		
	var h2, v2, row2, element, obj, method, params, object_renderby, object_type;
	var rendered = '';
	
	for(h2 in parent)	{
	// console.log('regionId: ' + regionId);			
	if(parent.hasOwnProperty(h2))	{
		row2 = parent[h2];			
		for(v2 in row2)	{
		if(row2.hasOwnProperty(v2))	{			
			element = row2[v2];

			if(typeof(element['object_type']) != 'undefined') {
				object_type = element['object_type'];
				object_renderby = element['object_renderby'];
				params = { 'object_type' : object_type, 'renderby' : object_renderby, 'element' : element };
// console.log('element-114: ' + h2 + ' : ' + v2 + ' : ' + element['object_type']);				
				rendered += eVe.renderAnObject(params);
			}			
			else if(typeof(element['field_htmltag']) != 'undefined') {
// console.log('element-118: ' + h2 + ' : ' + v2 + ' : ' + element['field_htmltag']);				
				rendered += eVe.renderAtag(element);
			}
			else if(typeof(element['kids']) != 'undefined') {
// console.log('kids-122: ' + h2 + ' : ' + v2 + ' : ' + element['kids'].length);				
				rendered += eVe.renderKidsInGrid(element['kids'], element['labels'], element['options']);
			}
			else {
				alert('Error 126');
				return false;

			}
			
		}
		}
	
	}	
	}
	return rendered;
	
}			




/**
*	extracting method and calling a method
*/
eVe.renderAnObject = function (params) {
		
	var renderElement = '';
	var objecttype = params['object_type'];
	method = 'render_' + params['object_type'];
	if(params['object_renderby']) {
		method += '_' + params['object_renderby'];
	}
	// 	console.log('method--135: ' + method);						
	if( eVe.method_exists(eVe, method)) {
// console.log('method-156: ' + method);		
		renderElement += eVe[method](params['element']);
	}
	else {
// console.log('method-158: ' + method);		
		renderElement += eVe.render_others(params['element']);
	}
	
// console.log('renderElement-160: ' + renderElement);	
	
	return renderElement;

}	


/**
*	extracting method and calling a method
*	all fields must have element['field_htmltag']
*/
eVe.renderAtag = function (element, options) {
	
	if(typeof(element['field_htmltag']) == 'undefined' || !element['field_htmltag']) {
		return;
	}
	var renderElement = '';	
	method = 'render_' + element['field_htmltag'];
	if(typeof(element['field_htmltype']) != 'undefined' && element['field_htmltype']) {
		method += '_' + element['field_htmltype'];
	}	
	if(element['element_renderby']) {
		method += '_' + element['element_renderby'];
	}

	// 	console.log('method--155: ' + method);						
	if( eVe.method_exists(eVe, method)) {
		renderElement += eVe[method](element, options);
	}
	else {
		renderElement += eVe.render_others(element, options);
	}

// console.log('renderElement-193: ' + renderElement);
	
	return renderElement;

}	



/**
*	Checks if a function exists
*
*/
eVe.method_exists = function(obj, method) {
  // http://kevin.vanzonneveld.net
  // +   original by: Brett Zamir (http://brett-zamir.me)

  if (typeof obj === 'string') {
		if(typeof window[obj] === 'undefined') {
			 return false;
		}	  
		return window[obj] && typeof window[obj][method] === 'function';
  }

  return typeof obj[method] === 'function';
}





// ----------  begin: methods for OBJECTs and customized options (renderBy) -------------------------------------


/**
*	Displaying elements in grids. Needs titles as seperate array.
*/
eVe.renderKidsInGrid = function (fields, options) {
	var rendered =  '';
	var h1, row, v1, el, id_fields, title, columnNumber;

	for(h1 in fields)	{	
	// console.log('h1: ' + h1);
	if(fields.hasOwnProperty(h1))	{
		row = fields[h1];
		rendered +=  '<div class="row">';
		columnNumber = 0;
		for(v1 in row) {
		if(row.hasOwnProperty(v1))	{					
				el = row[v1];
				if(!el) {
					return ' ';
				}
				// 		console.log('line 214: ' + columnNumber);
// console.log('el 214: ' + el);				
				rendered +=  '<div class="cell ';
				columnNumber++;
				if(columnNumber === 1) {
					// adding class block
					rendered += ' block"';
				}
				else {
					// adding class inline
					rendered += ' inline';
				}
				rendered +=  '">';			
					id_fields = el['id_fields'];

					if(typeof(el['element_labelposition']) == 'undefined') {
						rendered += '<label></label>';
					}
					else if(!el['element_labelposition']) {
						rendered += '<label></label>';
					}					
					else {
				
							if(el['element_labelposition'] == 'beforeLeft') {
								rendered +=  '<label for="' + el['field_name'] + '" ' + el['element_labelattributes'] + ' >' + el['field_label'] + '</label> ';
								rendered += eVe.renderAtag(el, options);
							}
							else if(el['element_labelposition'] == 'beforeAbove') {
								rendered +=  '<label for="' + el['field_name'] + '" ' + el['element_labelattributes'] + ' >' + el['field_label'] + '</label> <br>';
								rendered += eVe.renderAtag(el, options);
							}				
							else if(el['element_labelposition'] == 'afterRight') {
								rendered += eVe.renderAtag(el, options);
								rendered +=  '<label for="' + el['field_name'] + '" ' + el['element_labelattributes'] + ' >' + el['field_label'] + '</label> ';	
							}
							else if(el['element_labelposition'] == 'afterBelow') {
								rendered += eVe.renderAtag(el, options);
								rendered +=  '<br> <label for="' + el['field_name'] + '" ' + el['element_labelattributes'] + ' >' + el['field_label'] + '</label> ';	
							}
	
					}

						
				rendered += '</div>';	// end of cell div					

		}
		}
		rendered +=  '</div>';	// end of row div			
	}
	}
	// console.log('rendered-237: ' + rendered);
	return rendered;
	
}


/**
*	all fields must have field['field']['field_htmltag']
*	field structure: 
		1- all field objects will have: field['field'] settings come from DB 'eVe_fields' table
		2- field title comes from $titles which comes from DB 'eVe_titles' table
		3- object info related settings will be in the root like:  field['object_type']
	no kids
	
*/
eVe.render_field_Z = function (element, options) {
	
	return eVe.renderAtag(element['field'], options);
	
}



/**
*	form, fields and titles are coming in certain structure
	check DB 'objects', 'fields', 'elements', 'options' tables where objects.object_type = 'form'
*
*/
eVe.render_form = function (element) {
	var rendered = '<p class="subtitle">' + element['form']['object_label'] + '</p> <form name="' + element['form']['object_name'] + '" action="object_process_' + element['form']['object_name'] + '" ';
	if(element['object_attributes']) {
		rendered += element['object_attributes'];
	}
	rendered += '  method="POST"  >';
	rendered += eVe.renderKidsInGrid(element['fields'], element['options']);

	rendered += ' </form> ';
	
	return rendered;	

}


// ----------  end: methods for each OBJECT and customized options (renderBy) -------------------------------------

// ----------  begin: methods for TAGs and customized options (renderBy) -------------------------------------

			
/**
*	'toDo : add fılter for malicious text / code
*/
eVe.render_asis = function (element) {

		var rendered = element['element_innerhtml'];

		// console.log('render_asis__: ' + rendered);

		return rendered;
}


//  CKEDITOR.replace("createAform"); 
/**
*
*/	
eVe.render_button = function (element) {
		
		var rendered = '<' + element['field_htmltag'] + ' name="' + element['field_name'] + '" ';

		if(element['field_htmltype']) {
			rendered += ' type="' + element['field_htmltype'] + '" ';
		}
		
		if(element['element_classes']) {
			rendered += ' class="' + element['element_classes'] + '" ';
		}
		
		if(element['element_attributes']) {		
			rendered += element['element_attributes'];
		}
		rendered += ' >';		

		rendered += '</' + element['field_name'] + '>';

		// 	console.log('render_button: ' + rendered);

		return rendered;
		
}


/**
*  
*/
eVe.render_select = function (element, options) {

		var option_id_fields = element['element_id_fields'];
		var thisOptions = options[option_id_fields];
		// console.log('option_name: ' + ' : ' + thisOptions[0]['option_name']);		

		
		var rendered = '<select  name="' + element['field_name'] + '">';
		rendered += '<option value="" ></option>';
		
		for(var i = 0; i < thisOptions.length; i++) {
			rendered += '<option value="' + thisOptions[i]['id_options'] + '" >' +   thisOptions[i]['option_name'] + '</option>';
		}

		rendered += '</select>';
		
		// 		console.log('render_img: ');

		return rendered;
}



	
/**
*	
*
*/
eVe.render_textarea_small = function (element) {
	
	// CKEDITOR_BASEPATH = '1_Backbone\\11_Libraries\\ckeditor\\';
	// eVe.loadScript('1_Backbone\\11_Libraries\\ckeditor\\ckeditor.js');
		
	rendered = '';

	rendered += ' <textarea name="' + element['field_name'] + '"  type="small" >' + element['value'] + '</textarea>';

	// to replace textarea with Ckeditor
	eVe.jsAfterRender = [];
	eVe.jsAfterRender.push('CKEDITOR.replace("' + element['field_name'] + '");  ');
	
	
		if(eVe.method_exists('CKEDITOR', 'replace')) {
			// This script does not work
			rendered += '<script type="text/javascript" > CKEDITOR.replace("' + element['field_name'] + '");  </script>'; 
		}
		else {
			alert('Text editor is missing');		
		}
	
	
	return rendered;
	
}	


//  CKEDITOR.replace("createAform"); 
/**
*
*/	
eVe.render_others = function (element) {
		
		var rendered = '<' + element['field_htmltag'] + ' name="' + element['field_name'] + '" ';

		if(element['field_htmltype']) {
			rendered += ' type="' + element['field_htmltype'] + '" ';
		}
		
		if(element['element_classes']) {
			rendered += ' class="' + element['element_classes'] + '" ';
		}
		
		if(element['element_attributes']) {		
			rendered += element['element_attributes'];
		}
		rendered += ' >';		
		
		if(typeof(element['element_innerhtml']) != 'undefined' && element['element_innerhtml']) {
			rendered += element['element_innerhtml'];			
		}
		rendered += '</' + element['field_htmltag'] + '>';

		// 		console.log('render_others: ' + rendered);

		return rendered;
}


/**
*
*/
eVe.render_img_z = function (element) {

		var rendered = '<img ' + element['element_attributes'];

		if(typeof(element['element_classes']) != 'undefined') {
			rendered += ' class="' + element['element_classes'] + '"';			
		}
		rendered += '>';
		
		// 		console.log('render_img: ');

		return rendered;
}




//  CKEDITOR.replace("createAform"); 
/**
*
*/	
eVe.render_input_z = function (element) {
		
		var rendered = '<input ' + element['element_attributes'];

		if(element['element_classes']) {
			rendered += ' class=" ' + element['element_classes'] + '" ';
		}
		rendered += '>';
		if(typeof(element['element_innerhtml']) != 'undefined' && element['element_innerhtml']) {
			rendered += element['element_innerhtml'];			
		}
		rendered += '</' + element['field_htmltag'] + '>';

		// 		console.log('render_others: ' + rendered);

		return rendered;
}


