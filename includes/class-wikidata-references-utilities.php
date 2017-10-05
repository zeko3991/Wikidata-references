<?php

/**
 * Defines the wikidata references utilities.
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 */

/**
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */

/**
 * Wikidata references
 * Swiss knife class for different things such as ... parsing text, for the moment.
 * @since 1.0.0
 */

class Wikidata_References_Utilities{

	function wkrf_parse_text_spaces($string, $substitute){
		return str_replace(" ", $substitute, $string);	
	}	
	
	
	
}

?>