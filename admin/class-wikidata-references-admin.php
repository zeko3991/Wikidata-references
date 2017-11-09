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
		
		
		require_once('partials/wikidata-references-admin-utilities.php');
		$this->utilities = new Wikidata_References_Utilities();
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
		
		
		
		//FONT-AWESOME
		wp_register_style('font-awesome', plugins_url('wikidata-references/font-awesome-4.7.0/css/font-awesome.min.css') );
		wp_enqueue_style('font-awesome');
		
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
		$this->wkrf_add_meta_to_tags();
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
		$valid ['author_meta'] = (isset ($input['author_meta']) && ! empty ($input['author_meta'])) ? $input['author_meta'] : null;
		$valid ['copyright_meta'] = (isset ($input['copyright_meta'])  && ! empty ($input['copyright_meta'])) ? $input['copyright_meta'] : null;
		$valid ['subject_meta'] = (isset ($input['subject_meta']) && ! empty ($input['subject_meta'])) ? $input['subject_meta'] : null;
		$valid ['description_meta'] = (isset ($input['description_meta']) && ! empty ($input['description_meta'])) ? $input['description_meta'] : null;
		$valid ['keywords_meta'] = (isset ($input['keywords_meta']) && ! empty ($input['keywords_meta'])) ? $input['keywords_meta'] : null;
		$valid ['metadata_enable'] = (isset ($input['metadata_enable']) && ! empty ($input['metadata_enable'])) ? 1 : 0;
		$valid ['tag_title_link_enable'] = (isset($input['tag_title_link_enable']) && ! empty ($input['tag_title_link_enable'])) ? 1 : 0;
		$valid ['metadata_posts_enable'] = (isset($input['metadata_posts_enable']) && ! empty ($input['metadata_posts_enable'])) ? 1 : 0;
		$valid ['metadata_tags_enable'] = (isset($input['metadata_tags_enable']) && ! empty ($input['metadata_tags_enable'])) ? 1 : 0;
		
		//wikidata ids by tag validation
		$tags = get_tags();
		//foreach tag, finds if there is an option related to its name. If found, will take the value from input.
		foreach ($tags as $elem){
			$name = $this->utilities->wkrf_sanitize_tag_name($elem->name);
			$valid['tag-'.$name] = (isset($input['tag-'.$name]) && !empty($input['tag-'.$name])) ? $input['tag-'.$name] : null;
			//tag description, only available if there is an associated id
			if($valid['tag-'.$name]){
				$valid['description-'.$name] = (isset($input['description-'.$name]) && !empty($input['description-'.$name])) ? $input['description-'.$name] : null;
			}
			else{
				$valid['description-'.$name] = null;
			}
			 
		}
		
		return $valid;
	}
	
	
	/**
	 * Wikidata References
	 * Adds metadata to head from the metadata values added in plugin setup.
	 */
	public function wkrf_add_header_metadata(){
	    global $post;
	    $options = get_option($this->plugin_name);
	    if($options['metadata_enable']){
	        if(isset($options['author_meta'])){
	            echo '<meta name="author" content="'.$options['author_meta'].'" />';
	            //$result = add_post_meta($post->ID, "key", "value", true);
	           // echo '<meta name="inserting_post_meta" content="'.$result.'" />';
	        }
	        if(isset($options['copyright_meta'])){
	            echo '<meta name="copyright" content="'.$options['copyright_meta'].'" />';
	        }
	        if(isset($options['subject_meta'])){
	            echo '<meta name="subject" content="'.$options['subject_meta'].'" />';
	        }
	        if(isset($options['description_meta'])){
	            echo '<meta name="description" content="'.$options['description_meta'].'" />';
	        }
	        if(isset($options['keywords_meta'])){
	            echo '<meta name="keywords" content="'.$options['keywords_meta'].'" />';
	        }
	    }
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
	public function wkrf_add_header_tag_metadata(){
	    $options = get_option($this->plugin_name);
	    $tags = get_tags();
	    $wikidata_url ='https://www.wikidata.org/wiki/';
	    global $wp;
	    
	    //if current url is a post, adds metadata depending of its tags
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
	    
	    //if a tag page, adds metadata
	    foreach($tags as $tag){
	        $tag_link = get_tag_link($tag->term_id);					// gets url for specific tag
	        $tag_slug = $tag->slug;
	        //$tag_name = $this->utilities->wkrf_sanitize_tag_name($tag->name);
	        $current_url = home_url (add_query_arg(array(), $wp->request)) . '/'; // gets current url
	        $wikidata_id = get_option("wikidata_id_post_tag_".$tag_slug);
			$wikidata_link = get_option("wikidata_link_post_tag_".$tag_slug);
			error_log("wikidata link: ".$wikidata_link);
	        // if current and tag url coincide, and tag associated to a wikidata id
	        if(($current_url == $tag_link) && isset($wikidata_id)){
	        	//add_term_meta($tag->term_id, "key", "mi_value", true);
	            echo '<meta property="dc.sameAs" content="'.$wikidata_link.'" />';
	        }
	    }
	    
	}
	
	
	
	
    
	/**
	 * Wikidata References
	 * Adds a link to wikidata to the tag archive title
	 */
	public function wkrf_change_tag_archive_title($content){
	    global $wp;
	    $options = get_option($this->plugin_name);
	    $tags = get_tags();
	    $wikidata_url ='https://www.wikidata.org/wiki/';
	    $the_archive_title = __('Tag archives: ');
	    $tag_title_link_enable = isset($options['tag_title_link_enable']) ? $options['tag_title_link_enable'] : false;
	    //if a tag page, adds link 
	    
	    if($tag_title_link_enable){
    	    foreach($tags as $tag){
    	        $tag_link = get_tag_link($tag->term_id);					// gets url for specific tag
    	        $tag_name = $this->utilities->wkrf_sanitize_tag_name($tag->name);
    	        $current_url = home_url (add_query_arg(array(), $wp->request)) . '/'; // gets current url
    	   
    	        // if current and tag url coincide, and tag associated to a wikidata id
    	        if(($current_url == $tag_link) && isset($options['tag-'.$tag_name])){
    	            $content = '<h1 class="page-title">'.$the_archive_title.'<a target="_blank" 
                                    title="'.$wikidata_url.$options['tag-'.$tag_name].'" 
                                    href='.$wikidata_url.$options['tag-'.$tag_name].' >'.$tag->name.'</a></h1>';
    	            return $content;
    	        }
    	    }
	    }
    	  
	    return $content;
	    
	}

	
	
	
	
	/**
	 * Adds wikidata meta data to all tags.
	 * Will add a link to wikidata term related to the tag name.
	 * @param unknown $tag_list
	 */
	public function wkrf_add_meta_to_tags(){
		
		$tag_list = get_tags();
		$options = get_option($this->plugin_name);
		$wikidata_id_key = 'wikidata_id';
		$wikidata_link_key = 'wikidata_link';
		$wikidata_url ='https://www.wikidata.org/wiki/';
		$metadata_tags_enable = isset($options['metadata_tags_enable']) ? $options['metadata_tags_enable'] : 0;
		
		if($metadata_tags_enable){
			foreach($tag_list as $tag){
				$tag_id = $tag->term_id;
				$tag_link = get_tag_link($tag_id);
				$tag_name = $this->utilities->wkrf_sanitize_tag_name($tag->name);
				if(isset($options['tag-'.$tag_name])){
					$wikidata_tag_link = $wikidata_url.$options['tag-'.$tag_name];
					update_term_meta($tag_id, $wikidata_id_key, $options['tag-'.$tag_name]);
					update_term_meta($tag_id, $wikidata_link_key, $wikidata_tag_link);
				}
				else{
					delete_term_meta($tag_id, $wikidata_id_key);
					delete_term_meta($tag_id, $wikidata_link_key);
				}
			}
		}
		else{
			foreach($tag_list as $tag){
				$tag_id = $tag->term_id;
				delete_term_meta($tag_id, $wikidata_id_key);
				delete_term_meta($tag_id, $wikidata_link_key);
			}
		}
		
		
		$obj = get_queried_object();
		//error_log("engancho cosas: slug->".$obj->slug." term_id->".$obj->term_id);
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
	
	
	function wkrf_render_tag_wiki_column(){
		$term_id = $_GET['tag_ID'];
		$term = get_term_by('id', $term_id, 'taxonomy');
		//$meta = get_option("taxonomy_{$term_id}");
		//Insert HTML and form elements here
	}
	

	
	/**
	 * Tag column wikidata id, adds a column to term edit screen
	 * @param wp_object $columns
	 * @return $columns
	 */
	function wkrf_add_post_tag_wikidata_column($columns){
		$columns['wikidata_id'] = 'Wikidata ID';
		return $columns;
	}
	
	/**
	 * Tag Colmn CONTENT wikidata id
	 * @param unknown $content
	 * @return unknown
	 */
	function wkrf_add_post_tag_wikidata_column_content($content, $column_name, $term_id){
		$term = get_term($term_id);
		$term_slug = $term->slug;
		$wikidata_id = get_option("wikidata_id_post_tag_".$term_slug);
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
	 * Adds a field to Tag edit screen.
	 * Appends a field to the "new tag" form 
	 * @param unknown $term
	 */
	function wkrf_add_wikidata_id_tag_field($term){
		
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
					    	<div class="col-md-3 col-xs-3"><h6>Tag name</h6></div>	
					    	<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>	 
					    	<div class="col-md-7 col-xs-7"><h6>Description </h6></div>   
					    </div>
				  </div>
			</div>
		</div>
	
		<?php 
	}
	
	
	/**
	 * Displays a field to fill with a wikidata id in tag edit
	 * screen
	 * @param unknown $term
	 */
	function wkrf_edit_wikidata_id_tag_field($term){
		//$term = get_term($term);
		$term_slug = $term->slug;
		$wikidata_id = get_option("wikidata_id_post_tag_".$term->slug);
		$wikidata_description = empty($wikidata_id) ? null : get_option("wikidata_description_post_tag_".$term_slug);
		
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
					    	<div class="col-md-3 col-xs-3"><h6>Tag name</h6></div>	
					    	<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>	 
					    	<div class="col-md-7 col-xs-7"><h6>Description </h6></div>   
					    </div>
				  </div>
			</div>
		</div>
	
		<?php 
	}
	
	function wkrf_add_new_tag_wikidata_id($term_id, $taxonomy_id){	
			$taxonomy = get_term($taxonomy_id);
			$this->wkrf_save_wikidata_taxonomy_fields($term_id, 'post_tag');
			
	}
	
	
	function wkrf_save_wikidata_taxonomy_fields($term_id, $taxonomy){
		$term = get_term($term_id);
		$term_slug= $term->slug;
		
		if (isset( $_POST['term_meta'])){
			echo "dale";
			$term_meta = array();
			$term_meta['wikidata_id'] = isset( $_POST['term_meta']['wikidata_id']) ? $_POST['term_meta']['wikidata_id'] : '';
			$term_meta['wikidata_description'] = isset( $_POST['term_meta']['wikidata_description']) ? $_POST['term_meta']['wikidata_description'] : '';
			
			if(empty($term_meta['wikidata_id'])){
				delete_option("wikidata_id_".$taxonomy."_".$term_slug);
				delete_option("wikidata_description_".$taxonomy."_".$term_slug);
				delete_option("wikidata_link_".$taxonomy."_".$term_slug);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - deleted options for term_id: ".$term_id);
				
				delete_term_meta($term_id, $this->wikidata_id_key);
				delete_term_meta($term_id, $this->wikidata_link_key);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - deleted metadata for term_id: ".$term_id);
				
			}
			else{
				update_option("wikidata_id_".$taxonomy."_".$term_slug, $term_meta['wikidata_id']);
				update_option("wikidata_description_".$taxonomy."_".$term_slug, $term_meta['wikidata_description']);
				update_option("wikidata_link_".$taxonomy."_".$term_slug, $this->wikidata_url.$term_meta['wikidata_id']);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - deleted options for term_id: ".$term_id." wikidata_ID: ".$term_meta['wikidata_id']);
				
				update_term_meta($term_id, $this->wikidata_id_key, $term_meta['wikidata_id']);
				update_term_meta($term_id, $this->wikidata_link_key, $this->wikidata_url.$term_meta['wikidata_id']);
				error_log("INFO class:wkrf_save_wikidata_taxonomy_fields - deleted metadata for term_id: ".$term_id." wikidata_ID: ".$term_meta['wikidata_id']);
			}
		}
		else{
			error_log("ERROR class:wkrf_save_wikidata_taxonomy_fields - POST['term_meta'] is not set 
			when saving term id:".$term_id);
		}
	}
	
}
