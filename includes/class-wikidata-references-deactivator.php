<?php

/**
 * Fired during plugin deactivation
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */
class Wikidata_References_Deactivator {
    
    const wikidata_id_key = 'wikidata_id';
    const wikidata_link_key = 'wikidata_link';

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        $taxonomies = get_taxonomies();
        
        foreach($taxonomies as $taxonomy){
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ]);
            
            self::wkrf_delete_post_tag_wiki_data($taxonomy, $terms);
        }
	}
	
	public static function wkrf_delete_post_tag_wiki_data($taxonomy, $taxonomy_terms){
	   
	    
	    foreach($taxonomy_terms as $term){
	        
	        delete_option("wikidata_id_".$taxonomy."_".$term->term_id);
	        delete_option("wikidata_description_".$taxonomy."_".$term->term_id);
	        delete_option("wikidata_link_".$taxonomy."_".$term->term_id);
	        
	        delete_term_meta($term->term_id, self::wikidata_id_key);
	        delete_term_meta($term->term_id, self::wikidata_link_key);
	        
	    }
	}

}
