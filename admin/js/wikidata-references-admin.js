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
	//language = navigator.language || navigator.userLanguage;
});

//alert(navigator.language || navigator.userLanguage);
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
	wkrf_wikidata_search_by_term(term, input_id);
	modal.style.display = "block";
}

function wkrf_display_modal_selection(){
	modal.style.display = "block";
}

/*
 * Adds a list of wikidata items to the modal box, each list item will trigger
 * an onclick event to fill the input field related to the tag with the selected
 * wikidata id. @input: wikidata_search -> json with the wikidata websearch by
 * term info.
 */
function wkrf_modal_create_list(term, wikidata_search, input_id) {
	var data = wikidata_search;
	var wiki_id;
	var description;

	var search_array = data.search;
	// console.log(search_array);
	// for each result in json, will add a list elem.
	for ( var found_item in search_array) {
		wiki_id = search_array[found_item].id;
		description = search_array[found_item].description;
		/*
		 * description = String(description).replace("'", "\\'"); description =
		 * description.replace('"', '\\"');
		 */

		description = String(description).replace(/"/g, '´');
		description = description.replace(/'/g, "\´");
		wkrf_modal_add_list_elem(term, wiki_id, description, input_id);
	}

	// if first elem of array is null, its empty, so We will show a message of
	// empty search.
	// (el buen hardcode)

	if ((search_array == null) || (search_array[0] == null)) {
		if(search_array == null){
			wkrf_modal_add_message('Term Slug is empty!');
		}
		else{
			wkrf_modal_add_message('No items matching your tag were found  <br>'
					+ '<img class="icon icons8-Sad" width="50" height="50"'
					+ 'src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAED0lEQVRoQ+'
					+ '2aj7EMQRDG+0WACBABIkAEiAARIAJEgAh4ESACRIAIEAEioH5X8131/pmdnpldd3Vlqrbevfd2Zvrrr7vn2749'
					+ 'sxMZZyeCw9YEctHMbprZdTO7ZWZX0uV99TH9ws8vZvbJzH6t4cw1gNw3s7vparHpnZlxnbdM1pweIE/N7MHI63g'
					+ 'Yb3PhabzuB2zBHIxxwaDGdzN71gqoBQgGvHYAMP5N8mptmAAKNh+b2bWECEAPkzPCJNUCeZE2ZYOv6bPiPrxp5kY'
					+ 'c9NIB4vOT6KJRIHjuQ0rk3ykE2GiLATuE2IUUmrcjBSEChLgmlPgJC+TFOPbXBsRehCvhxl73zIyQy44SEM8EIKC/'
					+ 'Ng9aQbI3YSswi8yUgHx2TPxLEAI/BnMj55UlIOTAoxROhwAxB+aVKzYDTDkgGE5yk9h83jonSuFHzhBmFABCbFIpc0'
					+ 'C+pXOC8rdVdSoZP/4/1YzyT9JfHf9zDgilj1Ob5MYTxzSIDJJ/4uA5IGJjlsIDo1LIT1gZA+GM4MxAdjDpGIdYQcZw1'
					+ 'uzGGAgq9E7SOvubjgyNnP3eK24PhJr9Mxl9KXDwcT/Jh+jjM8CJ3eiB2Tp/1k4PBIPeVoSV2POE8UyBxyKjZz7ll0cAp'
					+ 'AvrDEJL1ep5Em0lY/7M3AAbsBkZPfMntnpGhDJareYM4QCF+sjomT+JHg9EZRc9EznJe0IDoD3zOd/Qgdi5018eiDxUEp'
					+ 'LythKcMo10ID84fWuSnQLROn9gbw+QSPhsec9/IFt6t2XtLCPol8speSLJ3rL5WnOU7Hth21N+1zKqZR2Jx70m9EBqD8Sc'
					+ 'AWg1NsJrvm0K41ywzZmFVmodiwdirUTxRlCKeSwedx6XDAUU5ZfH12jJ1nqLEqVWNGpRAOAhneg/koFsJha4V+zAFoDJRwY'
					+ 'gmA+gyCiKRn/aDrR+ZnUW5NkFJhnEKwZFO48A4n71fznp2bfETlHGY4xu2h/9Cy5Sqwh9xbydCm0YOIIQU2cx2/JJa2vfxQc'
					+ 'r7lUZLolHleg1WkWEnRyx1CdQtSJ8mbMfc7pK3YoIKw0EdE2ZZYMVcwJRrBxjO2jCxhIQUUjiEWKHPukJNxqGFJiqBh0g1TIF'
					+ 'RKi13xU0+cm+kV7dMtWyar0cCsz424BsISg9RFW19ldmpOorjRIQbBt/6UL93jpnqr9cigABjGemVlLUEuUlT/jLpSgQGaMCwO'
					+ '+wQnmOSpISIColDT/lQTax5xaqBcIabIikkOgDEACR5SWdNLYBppH9HMICwDmB5KlyUAsQGcPmXALE3/WyAD/RYHMvDKCp9MKAb'
					+ '5QDAIc0fR/TA0SA8B7CD8+2DJhEZ3U1zdcAIuP1aoZ/qcazxX0kL+Gnl2r0qkeLAwZz1gTSbUzPAicD5C+nGA5CzmeI1AAAAABJR'
					+ 'U5ErkJggg==">'
					+ '<br><br>Look for an accurate id in <a target="_blank" href="https://www.wikidata.org">wikidata</a>');
			wkrf_modal_add_message('');
		}
		
	}

}

/*
 * Adds a message to the modal window.
 */
function wkrf_modal_add_message(message){
	var wkrf_modal_window_content = document.getElementById("wkrf-modal-window-content");
	wkrf_modal_window_content.innerHTML +=
		'<div id="wkrf_modal_window_message" class="wkrf_modal_window_message col-md-12">'
			+message+
		'</div>';
}


/*
 * Adds a single list elem to the modal box, those elements are suppossed to be wikidata items 
 * related to the tag or category.
 */
function wkrf_modal_add_list_elem(termname, wikidata_id, description, input_id){
	var wkrf_modal_window_content = document.getElementById("wkrf-modal-window-content");
	var appendix =  
		'<div class="wkrf-modal-list-item col-md-12 row" onclick=\"wkrf_fill_wiki_id(\''+input_id+'\',\''+wikidata_id+'\',\''+description+'\')" style="cursor:pointer"> ' +
			'<div class="col-md-3"  ><b>'+termname+'</b></div>' +
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
		'<span id="wkrf-close" class="close col-md-offset-10" onclick="wkrf_modal_selection_close()">&times;</span>'+
		'<div class="wkrf-modal-list-header col-md-12 row">'+
    		'<div class="col-md-3 col-xs-3"><h6>Tag name</h6></div>'+	
    		'<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>'+	 
    		'<div class="col-md-7 col-xs-7"><h6>Description </h6></div>'+   
    	'</div>'
}

/*
 * Closes the modal window and fills an input box by id with a wikidata id.
 */
function wkrf_fill_wiki_id(input_id, wikidata_id, description){
	var input_wiki_id = document.getElementById(input_id);
	input_wiki_id.value = wikidata_id;
	var input_wiki_description = document.getElementById(input_id.replace("_id", "_description"));
	input_wiki_description.value = description;
	wkrf_modal_selection_close();
}

////////////////////////////////////////////////




///////////////Wikidata api request/////////////
function wkrf_wikidata_search_by_term(term, input_id){
	// change:  &uselang=es (i.e) to change language results
//	https:www.wikidata.org/w/api.php?action=wbsearchentities&origin=*&search=term&format=json&language=en
	var request = 'https://www.wikidata.org/w/api.php?action=wbsearchentities&origin=*&search='+term+'&format=json&language='+language;
	console.log(request);
	jQuery.getJSON(request, function(data){
		console.log(data);
		wkrf_modal_create_list(term, data, input_id);
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
