(function( $ ) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

/* 
 * Wikidata references
 * Gestiona la selección de formatos de referenciación
 */
function updateIeeeFormat(ieeeFormat){
	
	if(ieeeFormat.checked == true){
		document.getElementById("wikidata-references-ieee_format").value = 1;
		document.getElementById("wikidata-references-harvard_format").checked = false;
		document.getElementById("wikidata-references-harvard_format").value = 0;
		document.getElementById("wikidata-references-simple_format").checked = false;
		document.getElementById("wikidata-references-simple_format").value = 0;
	}
}

/*
 * Wikidata references
 * Gestiona la selección de formatos de referenciación.
 */
function updateHarvardFormat(harvardFormat){
	
	if(harvardFormat.checked == true){
		document.getElementById("wikidata-references-harvard_format").value = 1;
		document.getElementById("wikidata-references-ieee_format").checked = false;
		document.getElementById("wikidata-references-ieee_format").value = 0;
		document.getElementById("wikidata-references-simple_format").checked = false;
		document.getElementById("wikidata-references-simple_format").value = 0;
	}
}

/*
 * Wikidata references
 * Gestiona la selección de formatos de referenciación
 */
function updateSimpleFormat(simpleFormat){
	
	if(simpleFormat.checked == true){
		document.getElementById("wikidata-references-simple_format").value = 1;
		document.getElementById("wikidata-references-ieee_format").checked = false;
		document.getElementById("wikidata-references-ieee_format").value = 0;
		document.getElementById("wikidata-references-harvard_format").checked = false;
		document.getElementById("wikidata-references-harvard_format").value = 0;
	}
}


function hello(){
	alert("hello");
}