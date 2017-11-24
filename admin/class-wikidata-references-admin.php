<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/admin
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */
class Wikidata_References_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	private $language;
	
	private $wikidata_url             = 'https://www.wikidata.org/entity/';
	private $wikidata_id_key          = 'wikidata_id';
	private $wikidata_link_key        = 'wikidata_link';
	private $wikidata_description_key = 'wikidata_description';
	private $taxonomy_post_tag        = 'post_tag';
	private $taxonomy_category        = 'category';
	
	private $type_html      = 'text/html';
	private $extension_json = '.json';
	private $type_json      = 'application/json';
	private $extension_php  = '.php';
	private $type_php       = 'application/vnd.php.serialized';
	private $extension_n3   = '.n3';
	private $type_n3        = 'text/n3';
	private $extension_ttl  = '.ttl';
	private $type_ttl       = 'text/turtle';
	private $extension_nt   = '.nt';
	private $type_nt        = 'application/n-triples';
	private $extension_rdf  = '.rdf';
	private $type_rdf       = 'application/rdf+xml';
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->language  = substr( get_locale(), 0, 2);
	}

	/**
	 * Register the stylesheets for the admin area.
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $is_winIE;
		/* Built-in wp function wp_style_add_data() just works for IE versions 9 or
		 * previous, so it won't work with IE 10-11
		 * wp_style_add_data( $this->plugin_name.'-ie', 'conditional', 'lt IE 9' );
		 */
		if ( $is_winIE ) {
			wp_enqueue_style( $this->plugin_name.'-ie', plugin_dir_url(__FILE__) . 'css/wikidata-references-admin-ie.css', array(), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wikidata-references-admin.css', array(), $this->version, 'all' );
		}
		
		//BOOTSTRAP
		wp_register_style( 'bootstrap-style', plugins_url( 'wikidata-references/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css' ) );
		wp_enqueue_style( 'bootstrap-style' );
		
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wikidata-references-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'bootstrap-script', plugins_url( 'wikidata-references/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js' ) );
		wp_enqueue_script( 'bootstrap-script' );
	}
	
	
	/**
	 * Wikidata references
	 * Register the administration menu for the Wikidata References plugin into the WordPress 
	 * Dashboard menu.
	 * Administration menus: http://codex.wordpress.org/Administration_Menus
	 * @since 1.0.0
	 */
	public function wkrf_add_plugin_admin_menu() {
		add_options_page( 'WikiData References for WordPress Setup', 'WikiData References', 'manage_options', 
				$this->plugin_name, array($this, 'wkrf_display_plugin_setup_page') );
	}
	
	/**
	 * WikiData References
	 * Add settings action link to the plugins page.
	 * @Since 1.0.0
	 * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 */
	public function wkrf_add_action_links( $links ) {
		$settings_link = array(
				'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		
		return array_merge( $settings_link, $links );
	}
	
	
	/**
	 * Wikidata References
	 * Render the settings page for the wikidata references plugin
	 * @since 1.0.0
	 */ 
	public function wkrf_display_plugin_setup_page() {
		include_once( 'partials/wikidata-references-admin-display.php' );
	}
	
	
	/**
	 * Wikidata references
	 * Saves/updates the values for wiki references setup options
	 * @since 1.0.0
	 */
	public function wkrf_setup_options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'wkrf_validate_wiki_references_setup' ) );
	}
	
	
	/**
	 * Wikidata References
	 * validates checkboxes' input from the wikidata references' setup page
	 * @since 1.0.0
	 */
	public function wkrf_validate_wiki_references_setup( $input ) {
		$valid = array();

		$valid ['wkrf_term_microformat_links_enable']       = ( isset( $input['wkrf_term_microformat_links_enable'] ) && ! empty( $input['wkrf_term_microformat_links_enable'] ) ) ? 1 : 0;
		$valid ['wkrf_wikidata_tag_title_link_enable']      = ( isset( $input['wkrf_wikidata_tag_title_link_enable'] ) && ! empty ( $input['wkrf_wikidata_tag_title_link_enable'] ) ) ? 1 : 0;
		$valid ['wkrf_wikidata_category_title_link_enable'] = ( isset( $input['wkrf_wikidata_category_title_link_enable'] ) && ! empty ( $input['wkrf_wikidata_category_title_link_enable'] ) ) ? 1 : 0;

		
		$valid ['wkrf_wikidata_link_enable'] = ( isset( $input['wkrf_wikidata_link_enable'] ) && ! empty ($input['wkrf_wikidata_link_enable'])) ? 1 : 0;
		$valid ['wkrf_wikidata_json_enable'] = ( isset( $input['wkrf_wikidata_json_enable'] ) && ! empty ($input['wkrf_wikidata_json_enable'])) ? 1 : 0;
		$valid ['wkrf_wikidata_n3_enable']   = ( isset( $input['wkrf_wikidata_n3_enable'] ) && ! empty ($input['wkrf_wikidata_n3_enable'])) ? 1 : 0;
		$valid ['wkrf_wikidata_nt_enable']   = ( isset( $input['wkrf_wikidata_nt_enable'] ) && ! empty ($input['wkrf_wikidata_nt_enable'])) ? 1 : 0;
		$valid ['wkrf_wikidata_php_enable']  = ( isset( $input['wkrf_wikidata_php_enable'] ) && ! empty ($input['wkrf_wikidata_php_enable'])) ? 1 : 0;
		$valid ['wkrf_wikidata_rdf_enable']  = ( isset( $input['wkrf_wikidata_rdf_enable'] ) && ! empty ($input['wkrf_wikidata_rdf_enable'])) ? 1 : 0;
		$valid ['wkrf_wikidata_ttl_enable']  = ( isset( $input['wkrf_wikidata_ttl_enable'] ) && ! empty ($input['wkrf_wikidata_ttl_enable'])) ? 1 : 0;
		
		
		
		return $valid;
	}
	
	
	
	/**
	 * Wikidata References
	 * Adds link value for each tag with an associated wikidata id value.
	 * Action hooked to wp_head hook, when a page header is loaded.
	 * Example of link added to head:
	 * <link rel="describedby" href="https://www.wikidata.org/entity/Q80228" type="text/html">
	 * @since 1.0.0
	 */
	public function wkrf_add_head_wikidata_taxonomy_links() {
	    $tags        = get_tags();
	    $categories  = get_categories();
	    $echo_enable = false;
	    $options     = get_option($this->plugin_name);
	    
	    //if a tag page, adds metadata
	    if ( is_tag() ) {
		    foreach( $tags as $tag ) {
		        //checks what tag archive page is
		    	if( is_tag( $tag->term_id, $tag->slug ) ) {
			        $wikidata_id   = get_term_meta( $tag->term_id, $this->wikidata_id_key, true );
			        $wikidata_link = get_term_meta( $tag->term_id, $this->wikidata_link_key, true );
					if( ! empty( $wikidata_id ) && ! empty( $wikidata_link ) ) {
			        	$echo_enable = true;
			        }
			        break;
		    	}
		    }
	    } elseif( is_category() ) {
	    	foreach( $categories as $category ) {
	    	    //checks what category archive page is
	    		if( is_category( $category->term_id, $category->term_id ) ) {
	    		    $wikidata_id   = get_term_meta( $category->term_id, $this->wikidata_id_key, true );
	    		    $wikidata_link = get_term_meta( $category->term_id, $this->wikidata_link_key, true );
	    			if( ! empty( $wikidata_id ) && ! empty( $wikidata_link ) ){
	    				$echo_enable = true;
	    			}
	    			break;
	    		}
	    	}
	    }
	    
	    if( $echo_enable ) {
	    	if( isset( $options['wkrf_wikidata_link_enable'] ) && $options['wkrf_wikidata_link_enable'] ) {
	    		$this->wkrf_echo_head_meta_link( 'describedby', $wikidata_link, $this->type_html );
	    	}
	    	if( isset( $options['wkrf_wikidata_json_enable'] ) && $options['wkrf_wikidata_json_enable'] ) {
	    		$this->wkrf_echo_head_meta_link( 'describedby', $wikidata_link.$this->extension_json, $this->type_json );
	    	}
	    	if( isset( $options['wkrf_wikidata_n3_enable'] ) && $options['wkrf_wikidata_n3_enable'] ) {
	    		$this->wkrf_echo_head_meta_link( 'describedby', $wikidata_link.$this->extension_n3, $this->type_n3 );
	    	}
	    	if( isset( $options['wkrf_wikidata_nt_enable'] ) && $options['wkrf_wikidata_nt_enable'] ) {
	    		$this->wkrf_echo_head_meta_link( 'describedby', $wikidata_link.$this->extension_nt, $this->type_nt );
	    	}
	    	if( isset( $options['wkrf_wikidata_php_enable'] ) && $options['wkrf_wikidata_php_enable'] ) {
	    		$this->wkrf_echo_head_meta_link( 'describedby', $wikidata_link.$this->extension_php, $this->type_php );
	    	}
	    	if( isset( $options['wkrf_wikidata_rdf_enable'] ) && $options['wkrf_wikidata_rdf_enable'] ) {
	    		$this->wkrf_echo_head_meta_link( 'describedby', $wikidata_link.$this->extension_rdf, $this->type_rdf );
	    	}
	    	if( isset( $options['wkrf_wikidata_ttl_enable'] ) && $options['wkrf_wikidata_ttl_enable'] ){
	    		$this->wkrf_echo_head_meta_link( 'describedby', $wikidata_link.$this->extension_ttl, $this->type_ttl );
	    	}
	    }
	    
	}
	
	/**
	 * Wikidata references
	 * Echoes a link html tag.
	 * @usedBy wkrf_add_head_wikidata_taxonomy_links()
	 * @param string $rel
	 * @param string $link
	 * @param string $type
	 */
	public function wkrf_echo_head_meta_link( $rel, $link, $type ) {
		echo '<link rel="'.$rel.'" href="'.$link.'" type="'.$type.'" />';
	}

	
	
	/**
	 * Wikidata References
	 * Adds a link to wikidata to the tag archive title
	 */
	public function wkrf_add_archive_title_wikidata_link( $content ) {
	    $term_title                               = single_term_title( '', false );
	    $options                                  = get_option( $this->plugin_name );
	    $wkrf_wikidata_tag_title_link_enable      = isset( $options ['wkrf_wikidata_tag_title_link_enable'] ) ? $options ['wkrf_wikidata_tag_title_link_enable'] : null;
	    $wkrf_wikidata_category_title_link_enable = isset( $options ['wkrf_wikidata_category_title_link_enable'] ) ? $options ['wkrf_wikidata_category_title_link_enable'] : null;
	    
	    if ( is_tag() && $wkrf_wikidata_tag_title_link_enable ) {
	        $taxonomy = 'post_tag';
	        $the_archive_title_prefix = __( 'Tag archives: ', $this->plugin_name );
	    } elseif ( is_category() && $wkrf_wikidata_category_title_link_enable ) {
	        $taxonomy = 'category';
	        $the_archive_title_prefix = __( 'Category archives: ', $this->plugin_name );
	    } else {
	        return $content;
	    }
	    
	    $term = get_term_by( 'name', $term_title, $taxonomy );
	    
	    $term_wikidata_id   = get_term_meta( $term->term_id, $this->wikidata_id_key, true );
	    $term_wikidata_link = get_term_meta( $term->term_id, $this->wikidata_link_key, true );
	    
	    if ( ! empty( $term_wikidata_id ) && ! empty( $term_wikidata_id ) ) {
	        $term_wikidata_id = '('.$term_wikidata_id.')';
	        $content = '<h1 class="page-title">'.$the_archive_title_prefix.
	        				'<a target="_blank"
                           		 title="'.$term_wikidata_link.'"
                           		 href='.$term_wikidata_link.' >'.$term_title.' '.$term_wikidata_id.
                           	'</a>
						</h1>';
	        return $content;
	    }
	    
	    return $content;
	    
	}
	
	
	/**
	 * Hooked to term_links-post_tag, will call wkrf_add_terms_links_schema 
	 * passing 'post_tag' as taxonomy
	 */
	function wkrf_add_post_tag_terms_schema( $links ) {
	    return $this->wkrf_add_terms_links_schema( $links, 'post_tag' );
	}
	
	/**
	 * Hooked to term_links-category, will call wkrf_add_terms_links_schema 
	 * passing 'category' as taxonomy
	 */
	function wkrf_add_category_terms_schema( $links ) {
	   return $this->wkrf_add_terms_links_schema( $links, 'category' );
	}
	
	/**
	 * Function that takes the term_links-$taxonomy links and adds to them 
	 * schema.org microformat.
	 * 
	 * @param unknown $links
	 * @return string[]|unknown[]|unknown
	 */
	function wkrf_add_terms_links_schema( $links, $taxonomy ){
	   $span_start             = '<span itemscope itemtype="http://schema.org/Thing">';
	   $span_end               = '</span>';
	   $schema_formatted_links = array();
	   
	   $options = get_option( $this->plugin_name );
	   $schema_enable = isset( $options ['wkrf_term_microformat_links_enable'] ) ? $options ['wkrf_term_microformat_links_enable'] : null;
	  //we just want to add it in these terms
	   if ( ! $schema_enable ) {
	   		return $links;
	   } elseif ( is_single() || is_page() || is_tax() || is_tag() || is_category() ) {
    	    foreach ( $links as $link ){
    	        $term_slug = preg_replace( '/<[\s\S]+?>/', '', $link );  //removes everything between '<' and '>' leaving just the slug
    	        $term      = get_term_by( 'slug', $term_slug, $taxonomy );
    	        
    	        if (empty( $term ) ) {
    	        	continue;
    	        } else {
    	        	$wikidata_link = get_term_meta( $term->term_id, $this->wikidata_link_key, true );
    	        }

    	        if ( empty( $wikidata_link ) ) {
    	            $schema_formatted_links[] = $link;
    	        } else {
    	        	/*
    	        	 * Inserts wikidata link into a schema.org frame
    	        	 * 		<span itemscope itemtype="http://schema.org/Thing">
    	        	 * 			<link itemprop="url" href="https://www.wikidata.org/entity/Q#######" >
    	        	 * 			<a itemprop="url" href="Wordpress_site_url/term/term-slug > TERM-SLUG </a>
    	        	 * 		</span>
    	        	 */
    	            $schema_link = '<link itemprop="url" href="'.$wikidata_link.'" />';
    	            $link        = str_replace('<a', '<a itemprop="sameAs"', $link);
    	            
    	            $schema_formatted_links[] = $span_start.$schema_link.$link.$span_end;
    	        }
    	        
    	    }
	    return $schema_formatted_links;
	    
	    } else {
	        return $links;
	    }
	}
	
	

	
	/**
	 * Tag column wikidata id, adds a column to term edit screen
	 * @param wp_object $columns
	 * @return $columns
	 */
	function wkrf_add_taxonomy_wikidata_column( $columns ) {
		if( ! current_user_can( 'manage_categories' ) ){
			return $content;
		}
		$columns ['wikidata_id'] = 'Wikidata ID';
		return $columns;
	}
	
	/**
	 * Tag Colmn CONTENT wikidata id
	 * @param unknown $content
	 * @return unknown
	 */
	function wkrf_add_taxonomy_wikidata_column_content( $content, $column_name, $term_id ){
		if( ! current_user_can( 'manage_categories' ) ){
			return $content;
		}
		$term          = get_term( $term_id );
		$term_taxonomy = $term->taxonomy;
		$wikidata_id = get_term_meta( $term_id, $this->wikidata_id_key, true );
		$wikidata_description = get_term_meta( $term_id, $this->wikidata_description_key, true );
		$wikidata_link = get_term_meta( $term_id, $this->wikidata_link_key, true ); 
		$wikidata_link = '<a target="_blank" href="'.esc_html__($wikidata_link).'">'.esc_html__($wikidata_id).'</a>';
		
		switch ( $column_name ) {
			case 'wikidata_id':
				$content = '<p>'.$wikidata_link.'</p><p>'.$wikidata_description.'</p>';
				break;
			default:
				break;
		}
		
		return $content;
	}
	
	/**
	 * Adds to sortable column
	 * @param unknown $columns
	 * @return unknown
	 */
	function wkrf_register_wikidata_sortable_column( $columns ) {
		if( ! current_user_can( 'manage_categories' ) ){
			return $content;
		}
		$columns ['wikidata_id'] = "wikidata_id";
		return $columns;
	}
	
	/**
	 * sorts column
	 * @param unknown $terms
	 * @param unknown $taxonomies
	 * @param unknown $args
	 * @return unknown|string
	 */
	function wrkf_sort_taxonomy_by_wikidata_id( $terms, $taxonomies, $args ) {
		global $pagenow;
		if ( ! is_admin() || ! current_user_can( 'manage_categories' ) ) {
			return $terms;
		} elseif ( is_admin() && $pagenow == 'edit-tags.php' && ( ( $taxonomies [0] == 'post_tag' ) || ( $taxonomies [0] == 'category' ) ) ){
			$orderby = isset( $_REQUEST ['orderby'] ) ? trim( wp_unslash( $_REQUEST ['orderby'] ) ) : 'wikidata_id';
			$order   = isset( $_REQUEST ['order'] )   ? trim( wp_unslash( $_REQUEST ['order'] ) )   : 'DESC';
			
			if ( $orderby == 'wikidata_id'){
				/*$terms['join'] .= " LEFT JOIN wp_options AS opt ON opt.option_name = concat('wikidata_id_".$taxonomies[0]."_',t.term_id)";
				$terms['orderby'] = "ORDER BY opt.option_value";
				$terms['order']   = $order;
				*/
				$terms ['join']   .= " LEFT JOIN wp_termmeta AS opt ON opt.term_id = t.term_id && opt.meta_key = 'wikidata_id'";
				$terms ['orderby'] = "ORDER BY opt.meta_value";
				$terms ['order']   = $order;
			}
		}
		
		
		return $terms;
	}
	
	
	
		
	/**
	 * Adds a field to Tag edit screen.
	 * Appends a field to the "new tag" form 
	 * @param unknown $term
	 */
	function wkrf_add_wikidata_id_taxonomy_field( $term ) {
		$taxonomy = get_current_screen()->taxonomy;
		?>
		<tr class ="form-field">
			<th scope="row">
				<label for="term_meta[wikidata_id]"><?php echo _e('Wikidata ID'); ?></label>
				<td>
					<input type="text" name="term_meta[wikidata_id]" id="term_meta[wikidata_id]" >
					<span class="dashicons dashicons-search" style="cursor:pointer" 
						  onclick="wkrf_modal_selection(getElementById('tag-slug').value,'term_meta[wikidata_id]')"></span>
					<input type="text" style="display:none" name="term_meta[wikidata_description]" id="term_meta[wikidata_description]" >
					<p class="description"> </p>
				</td>	
			</th>
		</tr>
		<div class="wrap">
			<div id="wkrf-modal-window" class="modal">
				  <!-- Modal content -->
				  <div id="wkrf-modal-window-content" class="modal-content  col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
				    <span id="wkrf-close" class="close col-md-12" onclick="wkrf_modal_selection_close()">&times;</span>
				    <!-- <p>Some text in the Modal..</p> -->
					    <div class="wkrf-modal-list-header col-md-12 row">
					    	<div class="col-md-3 col-xs-3"><h6><?php _e('Name') ;?></h6></div>	
					    	<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>	 
					    	<div class="col-md-7 col-xs-7"><h6><?php _e('Description'); ?></h6></div>   
					    </div>
					    <div id="wkrf_modal_window_message" class="wkrf_modal_window_message col-md-12">
					    	<div class="loader"></div>
					    </div>
				  </div>
			</div>
		</div>
	
		<?php 
	}
	
	
	/**
	 * Displays a field to fill with a wikidata id in tag edit
	 * screen
	 * @param wp_term $term
	 */
	function wkrf_edit_wikidata_id_taxonomy_field( $term ) {
		$term_taxonomy        = $term->taxonomy;
		$wikidata_id          = get_term_meta($term->term_id, $this->wikidata_id_key, true);
		$wikidata_description = empty($wikidata_id) ? null : get_term_meta($term->term_id, $this->wikidata_description_key, true);
		
		?>
		<tr class ="form-field">
			<th scope="row">
				<label for="term_meta[wikidata_id]"><?php echo _e('Wikidata ID'); ?></label>
				<td>
					<input type="text" name="term_meta[wikidata_id]" id="term_meta[wikidata_id]"
						   value="<?php if(isset($wikidata_id)){ echo $wikidata_id; } ?>" >
					<span class="dashicons dashicons-search" style="cursor:pointer" 
						  onclick="wkrf_modal_selection(getElementById('slug').value,'term_meta[wikidata_id]')"></span>
					<input type="text" style="display:none" name="term_meta[wikidata_description]" id="term_meta[wikidata_description]"
						   value="<?php if(isset($wikidata_description)){ echo $wikidata_description; } else{ echo "nada"; } ?>" >
					<p class="description"><?php echo $wikidata_description; ?></p>
				</td>	
			</th>
		</tr>
		<div class="wrap">
			<div id="wkrf-modal-window" class="modal">
				  <!-- Modal content -->
				  <div id="wkrf-modal-window-content" class="modal-content  col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
				    <span id="wkrf-close" class="close col-md-12" onclick="wkrf_modal_selection_close()">&times;</span>
				    <!-- <p>Some text in the Modal..</p> -->
					    <div class="wkrf-modal-list-header col-md-12 row">
					    	<div class="col-md-3 col-xs-3"><h6><?php _e('Name'); ?></h6></div>	
					    	<div class="col-md-2 col-xs-2"><h6><?php _e('Wikidata ID#'); ?></h6></div>	 
					    	<div class="col-md-7 col-xs-7"><h6><?php _e('Description'); ?></h6></div>   
					    </div>
					    <div id="wkrf_modal_window_message" class="wkrf_modal_window_message col-md-12">
					    	<div class="loader"></div>
					    </div>
				  </div>
			</div>
		</div>
	
		<?php 
	}
	
	/**
	 * function that will save a new term created at edit-tags.php
	 * @param unknown $term_id
	 * @param unknown $taxonomy_id
	 */
	function wkrf_add_new_term_wikidata_id( $term_id, $taxonomy_id ) {	
			$taxonomy = get_term( $taxonomy_id )->taxonomy;
			$this->wkrf_save_wikidata_taxonomy_fields( $term_id, $taxonomy );
	}
	
	/**
	 * Wikidata References
	 * Saves Wikidata ID into term meta when creating or updating a taxonomy
	 * term. 
	 * @param int $term_id
	 * @param string $taxonomy
	 */
	function wkrf_save_wikidata_taxonomy_fields( $term_id, $taxonomy ) {
		$term = get_term( $term_id );
		
		
		if (isset( $_POST ['term_meta'] ) && current_user_can( 'manage_categories' ) ) {
			$term_meta = array();
			
			$term_meta['wikidata_id']          = isset( $_POST ['term_meta']['wikidata_id'] ) ? $_POST ['term_meta']['wikidata_id'] : '';
			$term_meta['wikidata_description'] = isset( $_POST ['term_meta']['wikidata_description'] ) ? $_POST ['term_meta']['wikidata_description'] : '';
			
			if ( empty( $term_meta['wikidata_id'] ) ) {				
				delete_term_meta( $term_id, $this->wikidata_id_key );
				delete_term_meta( $term_id, $this->wikidata_link_key );
				delete_term_meta( $term_id, $this->wikidata_description_key );
				error_log( "INFO class:wkrf_save_wikidata_taxonomy_fields - deleted metadata for term_id: ".$term_id );
				
			} else {
				$wikidata_id = $this->wkrf_validate_wikidata_id( $term_meta['wikidata_id'] );
				if($wikidata_id != false){
					$wikidata_description = $this->wkrf_get_wikidata_description($wikidata_id, $this->language);
					update_term_meta( $term_id, $this->wikidata_id_key, $wikidata_id );
					update_term_meta( $term_id, $this->wikidata_link_key, $this->wikidata_url.$wikidata_id );
					update_term_meta( $term_id, $this->wikidata_description_key, $wikidata_description );
					error_log( "INFO class:wkrf_save_wikidata_taxonomy_fields - saved metadata for term_id: ".$term_id." wikidata_ID: ".$wikidata_id." description: ".$wikidata_description );
				}
				
			}
			
		} else {
			error_log( "ERROR class:wkrf_save_wikidata_taxonomy_fields - POST['term_meta'] is not set 
			when saving term id:".$term_id );
		}
	}
	
	/**
	 * Wikidata references
	 * checks if text introduced in wikidata id field is a valid wikidata ID
	 * returns wikidata id if valid, false if not.
	 * @param wikidata id ([Q][0-9]+ $wikidata_id
	 * @return valid wikidata id |boolean
	 */
	function wkrf_validate_wikidata_id( $wikidata_id ){
		$wikidata_id_pattern = '/[Q][0-9]+/';
		
		if ( preg_match( $wikidata_id_pattern, $wikidata_id, $matches) ){
			return $matches[0];
		}
		
		return false;
	}
	
	/**
	 * Wikidata references.
	 * Gets wikidata item description by wikidata id, gets item with wbgetentities wikidata api
	 * function in the locale language, if not found, tries to return it in English, if not found, 
	 * return null
	 * @param unknown $wikidata_id
	 * @param unknown $language
	 * @return mixed|NULL
	 */
	function wkrf_get_wikidata_description($wikidata_id, $language){
		$api_request = 'https://www.wikidata.org/w/api.php?action=wbgetentities&props=descriptions&ids='.$wikidata_id.'&languages='.$language.'&format=json';
		$en_api_request = 'https://www.wikidata.org/w/api.php?action=wbgetentities&props=descriptions&ids='.$wikidata_id.'&languages=en&format=json';
		
		//$api_response = file_get_contents($api_request);
		//error_log('json: '.$api_response);
		$api_response = json_decode( file_get_contents( $api_request ), true);
		
		if ( $api_response != null ){
			$response = $api_response['entities'][$wikidata_id]['descriptions'][$language]['value'];
			if ( $response != null){
				return $response;
			}
			elseif( $response == null ){
				
				$en_api_response = json_decode( file_get_contents( $en_api_request ), true);
				$response = $en_api_response['entities'][$wikidata_id]['descriptions']['en']['value'];
				if ( $response != null ){
					return $response;
				}
			}
			return null;
		}
		return null;
			
	}
	
	
}
