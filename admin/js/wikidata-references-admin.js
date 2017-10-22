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

var language;

jQuery(document).ready(function(){
	language = "en";
});


////////////MODAL BOX ///////////////////////
var modal;
var span;


jQuery(document).ready(function(){
	modal = document.getElementById("wkrf-modal-window"); //modal window
	/*
	 * If user clicks anywhere outside the modal window, it will be closed.
	 */
	window.onclick = function(event){
		if (event.target == modal){
			modal.style.display = "none";
			wkrf_modal_clear();
		}
	}
});


/*
 * "Closes" the modal window and clears its content.
 */
function wkrf_modal_selection_close(){
	modal = document.getElementById("wkrf-modal-window"); //modal window
	modal.style.display = "none";
	wkrf_modal_clear();
}

/*
 * "Opens" the modal window, sets its visibility to "block"
 */
function wkrf_modal_selection(term, input_id){
	wkrf_wikidata_search_by_tag(term, input_id);
	modal.style.display = "block";
}

/*
 * Adds a list of wikidata items to the modal box, each list item will trigger an 
 * onclick event to fill the input field related to the tag with the selected wikidata id.
 * @input: wikidata_search -> json with the wikidata websearch by term info.
 */
function wkrf_modal_create_list(tag_term, wikidata_search, input_id){
	var data = wikidata_search;
	var wiki_id;
	var description;
	
	var search_array = data.search;
	console.log(search_array);
	for(var found_item in search_array){
		wiki_id = search_array[found_item].id;
		description = search_array[found_item].description;
		wkrf_modal_add_list_elem(tag_term, wiki_id, description, input_id);
	}
	
}


/*
 * Adds a single list elem to the modal box, those elements are suppossed to be wikidata items 
 * related to the tag or category.
 */
function wkrf_modal_add_list_elem(tagname, wikidata_id, description, input_id){
	var wkrf_modal_window_content = document.getElementById("wkrf-modal-window-content");
	var appendix =  
		'<div class="wkrf-modal-list-item col-md-12 row" onclick=\"wkrf_fill_wiki_id(\''+input_id+'\',\''+wikidata_id+'\')" style="cursor:pointer"> ' +
			//'<span onclick="hello()"><i class="fa fa-search"></i></span>' +
			'<div class="col-md-3"  ><b>'+tagname+'</b></div>' +
		    	'<div class="col-md-2"><a target="_blank" href="https://www.wikidata.org/wiki/'+wikidata_id+'">'+wikidata_id+'</a></div>' +	 
		    	'<div class="col-md-7">'+ description +'</div>' +   
		    '</div>';
	wkrf_modal_window_content.innerHTML += appendix;
}

/*
 * Clears the content of the modal window so that it can be filled later.
 */
function wkrf_modal_clear(){
	var wkrf_modal_window_content = document.getElementById("wkrf-modal-window-content");
	wkrf_modal_window_content.innerHTML = 
		'<div class="wkrf-modal-list-item col-md-12 row">'+
    		'<div class="col-md-3 col-xs-3"><h6>Tag name</h6></div>'+	
    		'<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>'+	 
    		'<div class="col-md-7 col-xs-7"><h6>Description </h6></div>'+   
    	'</div>'
}

/*
 * Closes the modal window and fills an input box by id with a wikidata id.
 */
function wkrf_fill_wiki_id(input_id, wikidata_id){
	var input = document.getElementById(input_id);
	input.value = wikidata_id;
	wkrf_modal_selection_close();
}
////////////////////////////////////////////////




///////////////Wikidata api request/////////////
function wkrf_wikidata_search_by_tag(tag_term, input_id){
	var request = 'https://www.wikidata.org/w/api.php?action=wbsearchentities&origin=*&search='+tag_term+'&format=json&language='+language;
	
	jQuery.getJSON(request, function(data){
		wkrf_modal_create_list(tag_term, data, input_id);
	});
	
}


////////////////////////////////////////////////




//////Setup page javascript buttons admin///////

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


function hello(text){
	if(text == null){
		alert("hello");
	}
	else{
		alert("This will open a dialog to select an accurate wiki item for the tag: "+text);
	}
	
}

////////////////////////////////////////////////
