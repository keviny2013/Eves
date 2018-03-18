/**
	This page is dedicated to rendering structured data into HTML
	Incoming data must be a Javascript object named as 'regions'

*	eVe.render.allRegions() method renders regions, blocks, elements and replaces them with body.innerHTML
*	Steps:  
		1- looping through regions: eVe.render.allRegions
		2- looping through a region: eVe.render.aRegion 
		3- looping through a block: eVe.render.aBlock
		4- render each html tag: eVe.render.{tag}
			examples: eVe.render.form, eVe.render.a, eVe.render.ul
			Exception (asis is not an html tag): eVe.render.asis
			Customized renders: Use custom tag names like eVe.render.form_login, eVe.render.form_registration
			, eVe.render.form_horizontal, eVe.render.form1, eVe.render.form2,  etc.
			
		5- Replace body.innerHTML
	
*/


eVe.jsAfterRender = [];
eVe.renderHtmlBody = '';
eVe.render = [];



/**		
*	This is the main function to start rendering. 
*	This method is called in htmlHead.php
*/
eVe.render.allRegions = function(regions){
		
	var region, regionId, classes, attributes;
	var regionnumber = 0;
	for(var i=0; i < regions.length; i++) {
		region = regions[i];
		regionnumber = i + 1;
		regionId = 'r_' + regionnumber;
		
		region['regionAttr']['classes'].push('region');
		classes =  eVe.implodeClasses(region['regionAttr']['classes']);
		attributes = eVe.implodeAttr(region['regionAttr']['attributes']);
		
		eVe.renderHtmlBody += '<div id="' + regionId + '" ' + classes + ' ' + attributes + ' >';
			eVe.renderHtmlBody += eVe.render.aRegion(region['blocks'], regionnumber);
		eVe.renderHtmlBody += '</div>';					
	}

	document.getElementsByTagName('body')[0].innerHTML = eVe.renderHtmlBody;

	 
	return true;
}


/**
*	loops through blocks in a region
*/
eVe.render.aRegion = function (blocks, regionnumber) {
	var block, blockId, classes, attributes;
	var rendered = '';
	var cid = 0;	
	for(var i=0; i < blocks.length; i++) {
		block = blocks[i];
		cid = i + 1;
		blockId = 'b_' + regionnumber + '_' + cid;

		block['blockAttr']['classes'].push('block');
		classes =  eVe.implodeClasses(block['blockAttr']['classes']);
		attributes = eVe.implodeAttr(block['blockAttr']['attributes']);
		
		rendered += '<div id="' + blockId + '" ' + classes + ' ' + attributes + ' >';
			rendered += eVe.render.aBlock(block['elements']);
		rendered += '</div>';					
	}
	
	return rendered;
	
}

/**
*	loops through elements of a block
*/
eVe.render.aBlock = function (els) {
	var method = '';
	var rendered = '';
	
	for(var i=0; i < els.length; i++) {
		el = els[i];
		if(typeof(el) !== 'object' || typeof(el.tag) == 'undefined'  || !el.tag) {
			alert('Error 94: Missing tag.');
			return false;
		}
		
		method = el.tag;
		if( eVe.method_exists(eVe.render, method)) {
			rendered += eVe.render[method](el);
		}
		else {
			alert('Error 95: Method does not exist: ' + method);		
			rendered += eVe.render.others(el);
		}		

	
	}	
	
	
	return rendered;

	
}			


//---------- start: utility methods ---------------------------------------------------------

/**
*	Checks if a function exists
  http://kevin.vanzonneveld.net
  original by: Brett Zamir (http://brett-zamir.me)
*
*/
eVe.method_exists = function(obj, method) {


  if (typeof obj === 'string') {
		if(typeof window[obj] === 'undefined') {
			 return false;
		}	  
		return window[obj] && typeof window[obj][method] === 'function';
  }

  return typeof obj[method] === 'function';
  
}


/**
*	Minimized filter for asis content
*/
eVe.filter = function (str) {
    
	return String(str).replace("script", '_script');


}



/**
*	More filters: Converts selected risky characters to htmlentities
*/
eVe.htmlEntities = function (str) {
    
	return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

}




/*
*
*/
eVe.implodeClasses = function(classes) {
	var str = '';
	
	if(typeof(classes.length) == 'undefined' || classes.length < 1) {
		return ' ';
	}
	
	for(var i = 0; i < classes.length; i++) {
		str += classes[i] + ', ';
	}
	str = ' class="' + str.substr(0, (str.length - 2)) + '"';
	
	return str;

}



/*
*
*/
eVe.implodeAttr = function(attributes) {
	var val;
	var str = '';

	for(key in attributes) {
	if(attributes.hasOwnProperty(key)) {
		str += ' ' + key + '="' + attributes[key] + '" ';
		
	}
	}
	
	return str;

}


//----------- end: utility methods ----------------------------------------------------------------


/**
*
*/	
eVe.render.others = function (el) {
	
		el.text = eVe.htmlEntities(el.text);
		
		var rendered = '<' + el['tag'] + ' ';
		
		if(el['class']) {
			rendered += ' class="' + el['class'] + '" ';
		}
		
		if(el['attributes']) {		
			rendered += ' ' + el['attributes'] + ' ';
		}
		rendered += ' >';		
		
		rendered += el.text;
		
		rendered += '</' + el['tag'] + '>';
		
		return rendered;
}



/**
*
*/	
eVe.render.div = function (el) {
		
		el.text = eVe.htmlEntities(el.text);
		
		var rendered = '<' + el['tag'] + ' ';
		
		if(el['class']) {
			rendered += ' class="' + el['class'] + '" ';
		}
		
		if(el['attributes']) {		
			rendered += ' ' + el['attributes'] + ' ';
		}
		rendered += ' >';		
		
		rendered += el.text;
		
		rendered += '</' + el['tag'] + '>';
		
		return rendered;
}


/**
*
*/	
eVe.render.p = function (el) {
		
		el.text = eVe.htmlEntities(el.text);
		
		var rendered = '<' + el['tag'] + ' ';
		
		if(el['class']) {
			rendered += ' class="' + el['class'] + '" ';
		}
		
		if(el['attributes']) {		
			rendered += ' ' + el['attributes'] + ' ';
		}
		rendered += ' >';		
		
		rendered += el.text;
		
		rendered += '</' + el['tag'] + '>';
		
		return rendered;
}


/**
*
*/	
eVe.render.a = function (el) {
		
		el.text = eVe.htmlEntities(el.text);
		
		var rendered = '<a href=' + el.href;

		if(el['attributes']) {		
			rendered += ' ' + el['attributes'] + ' ';
		}
		rendered += ' >';		
		
		rendered += el.text;
		
		rendered += '</a>';
		
		return rendered;
}


/**
*
*/	
eVe.render.h1 = function (el) {
	return eVe.render.hs(el);

}
/**
*
*/	
eVe.render.h3 = function (el) {
	return eVe.render.hs(el);

}
/**
*
*/	
eVe.render.h4 = function (el) {
	return eVe.render.hs(el);

}

/**
*
*/	
eVe.render.hr = function (el) {
	return '<hr>';

}

/**
*
*/	
eVe.render.hs = function (el) {
		
		el.text = eVe.htmlEntities(el.text);
		
		var rendered = '<' + el['tag'] + ' ';
		
		if(el['class']) {
			rendered += ' class="' + el['class'] + '" ';
		}
		
		if(el['attributes']) {		
			rendered += ' ' + el['attributes'] + ' ';
		}
		rendered += ' >';		
		
		rendered += el.text;
		
		rendered += '</' + el['tag'] + '>';
		
		return rendered;
}


/**
*
*/	
eVe.render.img = function (el) {
		
		el.text = eVe.htmlEntities(el.text);
		
		var rendered = '<img src="' + el.src + '" alt="' + el.alt + '" ';
		
		if(el['class']) {
			rendered += ' class="' + el['class'] + '" ';
		}
		
		if(el['attributes']) {		
			rendered += ' ' + el['attributes'] + ' ';
		}
		rendered += ' >';		
		
		rendered += '</img>';
		
		return rendered;
}



/**
*	For HTML codes to copy as is
*/	
eVe.render.asis = function (el) {

	var filtered = eVe.filter(el.innerHTML);
	return filtered;

	
}


