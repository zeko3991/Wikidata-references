<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
    

    $wikidata_id_key          = 'wikidata_id';
    $wikidata_link_key        = 'wikidata_link';
    $wikidata_description_key = 'wikidata_description';

    $taxonomies = get_taxonomies();
    
    foreach($taxonomies as $taxonomy){
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
        
        $this->wkrf_delete_post_tag_wiki_data($taxonomy, $terms);
    }
    
    
    function wkrf_delete_post_tag_wiki_data($taxonomy, $taxonomy_terms){
        foreach($taxonomy_terms as $term){        
            delete_term_meta($term->term_id, $this->wikidata_id_key);
            delete_term_meta($term->term_id, $this->wikidata_link_key);
            delete_term_meta($term->term_id, $this->wikidata_description_key);
        }
    }
    
    delete_option('wikidata-references');



