<?php
/**
 * Provide some utilities for the tag names managing
 *
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0

 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/admin/partials
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */
class Wikidata_References_Utilities{ 
	
	public function __construct(){}
	
	/**
	 * Sanitizes text to be used as a search term for wikidata api,
	 * removes quotation marks, &, |, and =, so that the term to 
	 * search does not cause any problem with the url to send to
	 * wikidata api.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param      string    $input    Text to sanitize to be used as search term
	 */
	public function wkrf_sanitize_search_term($input){
		$name = str_replace("'", "", $input);
		$name = str_replace('"', '', $name);
		$name = str_replace('amp;', '', $name);
		$name = str_replace('&', '', $name);
		$name = str_replace('|', '', $name);
		$name = str_replace('=', '', $name);
		return $name;
	}
	
	/**
	 * Sanitizes text to be used as id or name in html tags.
	 * Removes quotation marks.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param      string    $input    Text to sanitize
	 */
	public function wkrf_sanitize_tag_name($input){
		$name = str_replace(" ", "_", $input);
		$name = str_replace("'", "", $name);
		$name = str_replace('"', '', $name);
		$name = str_replace('amp;', '', $name);
		return $name;
	}
	
}

?>