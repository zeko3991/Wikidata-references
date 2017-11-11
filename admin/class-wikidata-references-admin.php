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
	
	
	/**
	 * Utilities class instance
	 * 
	 * @since 1.0.0
	 * @access private
	 */
	private $utilities;
	
	private $wikidata_url = 'https://www.wikidata.org/wiki/';
	private $wikidata_id_key = 'wikidata_id';
	private $wikidata_link_key = 'wikidata_link';
	private $taxonomy_post_tag = 'post_tag';
	private $taxonomy_category = 'category';
	
	private $extension_json = '.json';
	private $type_json = 'application/json';
	private $extension_php = '.php';
	private $type_php = 'application/vnd.php.serialized';
	private $extension_n3 = '.n3';
	private $type_n3 = 'text/n3';
	private $extension_ttl = '.ttl';
	private $type_ttl = 'text/turtle';
	private $extension_nt = '.nt';
	private $type_nt = 'application/n-triples';
	private $extension_rdf = '.rdf';
	private $type_rdf = 'application/rdf+xml';
	
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
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wikidata_References_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wikidata_References_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $is_winIE;
		
		
		if($is_winIE){
			/*CSS STYLE FOR INTERNET EXPLORER
			 *
			 * Built-in wp function wp_style_add_data() just works for IE versions 9 or 
			 * previous, so it won't work with IE 10-11
			 * wp_style_add_data( $this->plugin_name.'-ie', 'conditional', 'lt IE 9' );
			 */
			wp_enqueue_style( $this->plugin_name.'-ie', plugin_dir_url(__FILE__) . 'css/wikidata-references-admin-ie.css', array(), $this->version, 'all' );
		}
		else{
			//CSS STYLE
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wikidata-references-admin.css', array(), $this->version, 'all' );
		}
		
		//BOOTSTRAP
		wp_register_style('bootstrap-style', plugins_url('wikidata-references/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css'));
		wp_enqueue_style('bootstrap-style');
		
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wikidata_References_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wikidata_References_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wikidata-references-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script('bootstrap-script', plugins_url('wikidata-references/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js') );
		wp_enqueue_script('bootstrap-script');
	}
	
	
	/**
	 * Wikidata references
	 * Register the administration menu for the Wikidata References plugin into the WordPress 
	 * Dashboard menu.
	 * Administration menus: http://codex.wordpress.org/Administration_Menus
	 * @since 1.0.0
	 */
	public function wkrf_add_plugin_admin_menu(){
		add_options_page('WikiData References for WordPress Setup', 'WikiData References', 'manage_options', 
				$this->plugin_name, array($this, 'wkrf_display_plugin_setup_page') );
	}
	
	/**
	 * WikiData References
	 * Add settings action link to the plugins page.
	 * @Since 1.0.0
	 * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 */
	public function wkrf_add_action_links($links){
		$settings_link = array(
				'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		
		return array_merge($settings_link, $links);
		
		return $links;
	}
	
	
	/**
	 * Wikidata References
	 * Render the settings page for the wikidata references plugin
	 * @since 1.0.0
	 */ 
	public function wkrf_display_plugin_setup_page(){
		include_once( 'partials/wikidata-references-admin-display.php' );
	}
	
	
	/**
	 * Wikidata references
	 * Saves/updates the values for wiki references setup options
	 * @since 1.0.0
	 */
	public function wkrf_setup_options_update(){
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'wkrf_validate_wiki_references_setup'));
		$this->wkrf_add_meta_to_posts(null);
	}
	
	
	/**
	 * Wikidata References
	 * validates checkboxes' input from the wikidata references' setup page
	 * @since 1.0.0
	 */
	public function wkrf_validate_wiki_references_setup($input){
		$valid = array();
		
		//deprecated?
		$valid ['references_by_tag'] = (isset ( $input ['references_by_tag'] ) && ! empty ( $input ['references_by_tag'] )) ? 1 : 0;
		$valid ['references_footnote'] = (isset ( $input ['references_footnote'] ) && ! empty ( $input ['references_footnote'] )) ? 1 : 0;
		$valid ['ieee_format'] = (isset ( $input ['ieee_format'] ) && ! empty ( $input ['ieee_format'] )) ? 1 : 0;
		$valid ['harvard_format'] = (isset ( $input ['harvard_format'] ) && ! empty ( $input ['harvard_format'] )) ? 1 : 0;
		$valid ['simple_format'] = (isset ( $input ['simple_format'] ) && ! empty ( $input ['simple_format'] )) ? 1 : 0;
		
		//$valid['prueba'] = (isset($input['prueba']) && ! empty($input['prueba'])) ? $input['prueba'] : null;
		//metadata values
		$valid ['tag_title_link_enable'] = (isset($input['tag_title_link_enable']) && ! empty ($input['tag_title_link_enable'])) ? 1 : 0;
		$valid ['metadata_posts_enable'] = (isset($input['metadata_posts_enable']) && ! empty ($input['metadata_posts_enable'])) ? 1 : 0;
		$valid ['metadata_tags_enable'] = (isset($input['metadata_tags_enable']) && ! empty ($input['metadata_tags_enable'])) ? 1 : 0;
		

		
		return $valid;
	}
	
	
	
	/**
	 * Wikidata References
	 * Adds meta value for each tag with an associated wikidata id value.
	 * Action hooked to wp_head hook, when a page header is loaded.
	 * Checks if the page that is being loaded is a post or a tag page.
	 * 		-if a post: will add wiki metadata for every tag in the post, as
	 * 		 they are associated with a wikidata id at plugin setup page.
	 * 		-if a tag page: will add wiki metadata for that specific tag as
	 * 		 it is associated with a wikidata id.
	 * @since 1.0.0
	 */
	public function wkrf_add_head_wikidata_taxonomy_links(){
	    $tags = get_tags();
	    $categories = get_categories();
	    $wikidata_id;
	    $wikidata_link;
	    $echo_enable = false;
	    
	  /*  //if current url is a post, adds metadata depending of its tags
	    if(is_single(get_the_title())){									// if is a post
	        $post_tags = get_the_tags();								// gets the post tags list
	        if($post_tags){												// if list is not empty
	            foreach($post_tags as $post_tag){
	                //$tag_name = str_replace(" ", "_", $post_tag->name);
	                $tag_name = $this->utilities->wkrf_sanitize_tag_name($post_tag->name);
	                
	                // checks if the tag has an associated wikidata id
	                if(isset($options['tag-'.$tag_name])){
	                    echo '<meta property="test_meta_tag" content="'.$post_tag->name.'" />';  // debug info metadata
	                    echo '<meta property="dc.sameAs" content="'.$wikidata_url.$options['tag-'.$tag_name].'" />';
	                }
	                
	            }
	        }
	        return; //if current page is a post, will not check if its url coincides with a tag page url
	    }
	    */
	    //if a tag page, adds metadata
	    if(is_tag()){
		    foreach($tags as $tag){
		    	if(is_tag($tag->term_id, $tag->slug)){
			        $tag_slug = $tag->slug;
			        $tag_id = $tag->term_id;
			        //$tag_name = $this->utilities->wkrf_sanitize_tag_name($tag->name);
			        //$current_url = home_url (add_query_arg(array(), $wp->request)) . '/'; // gets current url
			        $wikidata_id = get_option("wikidata_id_".$this->taxonomy_post_tag."_".$tag_id);
			        $wikidata_link = get_option("wikidata_link_".$this->taxonomy_post_tag."_".$tag_id);
			        // if current and tag url coincide, and tag associated to a wikidata id
					if(!empty($wikidata_id) && !empty($wikidata_link)){
			        	//add_term_meta($tag->term_id, "key", "mi_value", true);
			        	$echo_enable = true;
			        }
			        break;
		    	}
		    }
	    }
	    else if(is_category()){
	    	foreach($categories as $category){
	    		if(is_category($category->term_id, $category->term_id)){
	    			$wikidata_id = get_option("wikidata_id_".$this->taxonomy_category."_".$category->term_id);
	    			$wikidata_link = get_option("wikidata_link_".$this->taxonomy_category."_".$category->term_id);
	    			//error_log("AAAA- "."wikidata_link_".$this->taxonomy_category."_".$category->slug);
	    			//error_log("AAAA- id:".$wikidata_id."   link:".$wikidata_link);
	    			if(!empty($wikidata_id) && !empty($wikidata_link)){
	    				$echo_enable = true;
	    			}
	    			break;
	    		}
	    	}
	    }
	    
	    if($echo_enable){
	    	$this->wkrf_echo_head_meta_link('alternate', $wikidata_link.$this->extension_json, $this->type_json);
	    	$this->wkrf_echo_head_meta_link('alternate', $wikidata_link.$this->extension_n3, $this->type_n3);
	    	$this->wkrf_echo_head_meta_link('alternate', $wikidata_link.$this->extension_nt, $this->type_nt);
	    	$this->wkrf_echo_head_meta_link('alternate', $wikidata_link.$this->extension_php, $this->type_php);
	    	$this->wkrf_echo_head_meta_link('alternate', $wikidata_link.$this->extension_rdf, $this->type_rdf);
	    	$this->wkrf_echo_head_meta_link('alternate', $wikidata_link.$this->extension_ttl, $this->type_ttl);
	    }
	    
	}
	
	
	public function wkrf_echo_head_meta_link($alternate, $link, $type){
		echo '<link rel="'.$alternate.'" href="'.$link.'" type="'.$type.'" />';
	}
	
	
	
    
	/**
	 * Wikidata References
	 * Adds a link to wikidata to the tag archive title
	 */
	public function wkrf_add_wiki_link_archive_title($content){
	    $options = get_option($this->plugin_name);
	    $term_title = single_term_title('', false);
	    $term_wikidata_id;
	    $term_wikidata_link;
	    $the_archive_title_prefix;
	    $term_taxonomy;
	    $term;
	    
	    if(is_tag()){
	        $taxonomy = 'post_tag';
	        $the_archive_title_prefix = __('Tag archives: ');
	    }
	    else if(is_category()){
	        $taxonomy = 'category';
	        $the_archive_title_prefix = __('Category archives: ');
	    }
	    else if(is_tax()){
	        //get taxonomy type and implement??
	        return $content;
	    }
	    else{
	        return $content;
	    }
	    
	    $term = get_term_by('name', $term_title, $taxonomy);
	    $term_wikidata_id = get_option("wikidata_id_".$taxonomy."_".$term->term_id);
	    $term_wikidata_link = get_option("wikidata_link_".$taxonomy."_".$term->term_id);
	    
	    if(!empty($term_wikidata_id) && !empty($term_wikidata_id)){
	        $term_wikidata_id = '('.$term_wikidata_id.')';
	        $content = '<h1 class="page-title">'.$the_archive_title_prefix.'<a target="_blank"
                            title="'.$term_wikidata_link.'"
                            href='.$term_wikidata_link.' >'.$term_title.' '.$term_wikidata_id.'</a></h1>';
	        return $content;
	    }

    	  
	    return $content;
	    
	}
	
	//function wkrf_add_wiki_link_taxonomy_terms($link, $term, $taxonomy){
	function wkrf_add_wiki_link_taxonomy_terms($links){
	   $span_start = '<span itemscope itemtype="http://schema.org/Thing">';
	   $span_end = '</span>';
	   $schema_formatted_links = array();
	   
	   // $meta = '<meta itemprop="url" content="'.$wikidata_link.'">';

	  // return '<a href="'.$link.'" title="holi">'.$term->name.' </a>';  
	//   if(is_single() || is_page() || is_tax()){
    	    foreach($links as $link){
    	        $term_slug = preg_replace('/<[\s\S]+?>/', '', $link);
    	        $term = get_term_by('slug', $term_slug, 'post_tag');
    	        $wikidata_link = get_option('wikidata_link_post_tag_'.$term->term_id);
    	        if(empty($wikidata_link)){
    	            $schema_formatted_links[] = $link;
    	        }
    	        else{
    	            $schema_link = '<link itemprop="url" href="'.$wikidata_link.'" />';
    	            $link = str_replace('<a', '<a itemprop="sameAs"', $link);
    	            
    	            $schema_formatted_links[] = $span_start.$schema_link.$link.$span_end;
    	        }
    	        
    	    }
	    return $schema_formatted_links;
	    //we just want to add it in these terms
	   // }
	   // return '<a href="'.$link.'" title="holi" rel="tag" >'.$term->name.'</a>';
	   //return 'https://www.google.es';
	  // return $links;
	}
	
	
	/**
	 * Adds generic metadata to posts
	 * If $post_id is null will update/delete all posts metadata, updating or 
	 * adding values if option "metadata_post_enable" is activated or deleting
	 * them from all posts if disabled.
	 * If $post_id is not null and refers to an existing post, will update / delete
	 * metadata from that post.
	 * @param int $post_id
	 */
	public function wkrf_add_meta_to_posts($post_id){
		//if called when saving a new post
		
		$options = get_option($this->plugin_name);
		$posts_list = get_posts(-1); //gets all posts
		
		//gets metadata values
		$metadata_posts_enable = isset($options['metadata_posts_enable']) ? $options['metadata_posts_enable'] : 0;
		$author = isset($options['author_meta']) ? $options['author_meta'] : null;
		$copyright = isset($options['copyright_meta']) ? $options['copyright_meta'] : null;
		$subject = isset($options['subject_meta']) ? $options['subject_meta'] : null;
		$description = isset($options['description_meta']) ? $options['description_meta'] : null;
		$keywords = isset($options['keywords_meta']) ? $options['keywords_meta'] : null;
		
		if($post_id == null){
			if($metadata_posts_enable){
				
				foreach($posts_list as $post){
					if($author != null){
						update_post_meta($post->ID, "author", $author);
					}
					if($copyright != null){
						update_post_meta($post->ID, "copyright", $copyright);
					}
					if($subject != null){
						update_post_meta($post->ID, "subject", $subject);
					}
					if($description != null){
						update_post_meta($post->ID, "description", $description);
					}
					if($keywords != null){
						update_post_meta($post->ID, "keywords", $keywords);
					}
				}			
			}
			else{
				foreach($posts_list as $post){
					delete_post_meta($post->ID, "author");
					delete_post_meta($post->ID, "copyright");
					delete_post_meta($post->ID, "subject");
					delete_post_meta($post->ID, "description");
					delete_post_meta($post->ID, "keywords");
				}
			}
		}
		else if($post_id != null){
			if($metadata_posts_enable){
				
				foreach($posts_list as $post){
					update_post_meta($post->ID, "author", $author);
					update_post_meta($post->ID, "copyright", $copyright);
					update_post_meta($post->ID, "subject", $subject);
					update_post_meta($post->ID, "description", $description);
					update_post_meta($post->ID, "keywords", $keywords);
				}
			}
			else{
				foreach($posts_list as $post){
					delete_post_meta($post->ID, "author");
					delete_post_meta($post->ID, "copyright");
					delete_post_meta($post->ID, "subject");
					delete_post_meta($post->ID, "description");
					delete_post_meta($post->ID, "keywords");
				}
			}
		}
	}
	

	
	/**
	 * Tag column wikidata id, adds a column to term edit screen
	 * @param wp_object $columns
	 * @return $columns
	 */
	function wkrf_add_taxonomy_wikidata_column($columns){
		$columns['wikidata_id'] = 'Wikidata ID';
		return $columns;
	}
	
	/**
	 * Tag Colmn CONTENT wikidata id
	 * @param unknown $content
	 * @return unknown
	 */
	function wkrf_add_taxonomy_wikidata_column_content($content, $column_name, $term_id){
		$term = get_term($term_id);
		$term_taxonomy = $term->taxonomy;
		$wikidata_id = get_option("wikidata_id_".$term_taxonomy."_".$term_id);
		switch($column_name){
			case 'wikidata_id':
				$content = $wikidata_id;
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
	function wkrf_register_wikidata_sortable_column($columns){
		$columns['wikidata_id'] = "wikidata_id";
		return $columns;
	}
	
	/**
	 * sorts column
	 * @param unknown $terms
	 * @param unknown $taxonomies
	 * @param unknown $args
	 * @return unknown|string
	 */
	function wrkf_sort_taxonomy_by_wikidata_id($terms, $taxonomies, $args){
		global $pagenow;
		if(!is_admin()){
			return $terms;
		}
		
		if(is_admin() && $pagenow == 'edit-tags.php' && (($taxonomies[0] == 'post_tag') || ($taxonomies[0] == 'category')) ){
			$orderby = isset($_REQUEST['orderby']) ? trim(wp_unslash($_REQUEST['orderby'])) : 'wikidata_id';
			$order = isset($_REQUEST['order'])   ? trim(wp_unslash($_REQUEST['order']))   : 'DESC';
			
			if($orderby == 'wikidata_id'){
				$terms['join'] .= " LEFT JOIN wp_options AS opt ON opt.option_name = concat('wikidata_id_".$taxonomies[0]."_',t.term_id)";
				$terms['orderby'] = "ORDER BY opt.option_value";
				$terms['order']   = $order;
			}
		}
		
		
		return $terms;
	}
	
	
	
		
	/**
	 * Adds a field to Tag edit screen.
	 * Appends a field to the "new tag" form 
	 * @param unknown $term
	 */
	function wkrf_add_wikidata_id_taxonomy_field($term){
		$taxonomy = get_current_screen()->taxonomy;
		?>
		<tr class ="form-field">
			<th scope="row">
				<label for="term_meta[wikidata_id]"><?php echo _e('Wikidata ID') ?></label>
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
					    	<div class="col-md-3 col-xs-3"><h6><?php _e($taxonomy);?> name</h6></div>	
					    	<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>	 
					    	<div class="col-md-7 col-xs-7"><h6><?php _e('Description')?></h6></div>   
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
	function wkrf_edit_wikidata_id_taxonomy_field($term){
		$term_taxonomy = $term->taxonomy;
		$wikidata_id = get_option("wikidata_id_".$term_taxonomy."_".$term->term_id);
		$wikidata_description = empty($wikidata_id) ? null : get_option("wikidata_description_".$term_taxonomy."_".$term->term_id);
		
		?>
		<tr class ="form-field">
			<th scope="row">
				<label for="term_meta[wikidata_id]"><?php echo _e('Wikidata ID') ?></label>
				<td>
					<input type="text" name="term_meta[wikidata_id]" id="term_meta[wikidata_id]"
						   value="<?php if(isset($wikidata_id)){ echo $wikidata_id; } ?>" >
					<span class="dashicons dashicons-search" style="cursor:pointer" 
						  onclick="wkrf_modal_selection(getElementById('slug').value,'term_meta[wikidata_id]')"></span>
					<input type="text" style="display:none" name="term_meta[wikidata_description]" id="term_meta[wikidata_description]"
						   value="<?php if(isset($wikidata_description)){ echo $wikidata_description; } else{ echo "nada"; } ?> " >
					<p class="description"><?php echo $wikidata_description; ?> </p>
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
					    	<div class="col-md-3 col-xs-3"><h6><?php _e($term_taxonomy); ?> name</h6></div>	
					    	<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>	 
					    	<div class="col-md-7 col-xs-7"><h6>Description </h6></div>   
					    </div>
				  </div>
			</div>
		</div>
	
		<?php 
	}
	
	function wkrf_add_new_term_wikidata_id($term_id, $taxonomy_id){	
			$taxonomy = get_term($taxonomy_id)->taxonomy;
			$this->wkrf_save_wikidata_taxonomy_fields($term_id, $taxonomy);
	}
	
	
	function wkrf_save_wikidata_taxonomy_fields($term_id, $taxonomy){
		$term = get_term($term_id);
		
		if (isset( $_POST['term_meta'])){
			$term_meta = array();
			$term_meta['wikidata_id'] = isset( $_POST['term_meta']['wikidata_id']) ? $_POST['term_meta']['wikidata_id'] : '';
			$term_meta['wikidata_description'] = isset( $_POST['term_meta']['wikidata_description']) ? $_POST['term_meta']['wikidata_description'] : '';
			
			if(empty($term_meta['wikidata_id'])){
				delete_option("wikidata_id_".$taxonomy."_".$term_id);
				delete_option("wikidata_description_".$taxonomy."_".$term_id);
				delete_option("wikidata_link_".$taxonomy."_".$term_id);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - deleted options for term_id: ".$term_id);
				
				delete_term_meta($term_id, $this->wikidata_id_key);
				delete_term_meta($term_id, $this->wikidata_link_key);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - deleted metadata for term_id: ".$term_id);
				
			}
			else{
			    update_option("wikidata_id_".$taxonomy."_".$term_id, $term_meta['wikidata_id']);
			    update_option("wikidata_description_".$taxonomy."_".$term_id, $term_meta['wikidata_description']);
			    update_option("wikidata_link_".$taxonomy."_".$term_id, $this->wikidata_url.$term_meta['wikidata_id']);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - saved options for term_id: ".$term_id." wikidata_ID: ".$term_meta['wikidata_id']);
				
				update_term_meta($term_id, $this->wikidata_id_key, $term_meta['wikidata_id']);
				update_term_meta($term_id, $this->wikidata_link_key, $this->wikidata_url.$term_meta['wikidata_id']);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - saved metadata for term_id: ".$term_id." wikidata_ID: ".$term_meta['wikidata_id']);
			}
		}
		else{
			error_log("ERROR class:wkrf_save_wikidata_taxonomy_fields - POST['term_meta'] is not set 
			when saving term id:".$term_id);
		}
	}
	
}
