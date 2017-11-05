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
		//add_action('added_option', array($this, 'wkrf_add_meta_by_tags'));
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
	//	$this->wkrf_add_meta_by_tags();
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
		
		$valid['prueba'] = (isset($input['prueba']) && ! empty($input['prueba'])) ? $input['prueba'] : null;
		//metadata values
		$valid ['author_meta'] = (isset ($input['author_meta']) && ! empty ($input['author_meta'])) ? $input['author_meta'] : null;
		$valid ['copyright_meta'] = (isset ($input['copyright_meta'])  && ! empty ($input['copyright_meta'])) ? $input['copyright_meta'] : null;
		$valid ['subject_meta'] = (isset ($input['subject_meta']) && ! empty ($input['subject_meta'])) ? $input['subject_meta'] : null;
		$valid ['description_meta'] = (isset ($input['description_meta']) && ! empty ($input['description_meta'])) ? $input['description_meta'] : null;
		$valid ['keywords_meta'] = (isset ($input['keywords_meta']) && ! empty ($input['keywords_meta'])) ? $input['keywords_meta'] : null;
		$valid ['metadata_checkbox'] = (isset ($input['metadata_checkbox']) && ! empty ($input['metadata_checkbox'])) ? 1 : 0;
		$valid ['tag_title_link_checkbox'] = (isset($input['tag_title_link_checkbox']) && ! empty ($input['tag_title_link_checkbox'])) ? 1 : 0;
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
	    if($options['metadata_checkbox']){
	        if(isset($options['author_meta'])){
	            echo '<meta name="author" content="'.$options['author_meta'].'" />';
	            $result = add_post_meta($post->ID, "key", "value", true);
	            echo '<meta name="inserting_post_meta" content="'.$result.'" />';
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
	                $tag_name = str_replace(" ", "_", $post_tag->name);
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
	        $tag_name = str_replace(" ", "_", $tag->name);
	        $current_url = home_url (add_query_arg(array(), $wp->request)) . '/'; // gets current url
	        
	        // if current and tag url coincide, and tag associated to a wikidata id
	        if(($current_url == $tag_link) && isset($options['tag-'.$tag_name])){
	            echo '<meta property="dc.sameAs" content="'.$wikidata_url.$options['tag-'.$tag_name].'" />';
	        }
	    }
	    
	}
	
	
    
	/**
	 * Wikidata References
	 * Adds microdata and a link to wikidata tag
	 */
	public function wkrf_change_tag_archive_title($content){
	    global $wp;
	    $options = get_option($this->plugin_name);
	    $tags = get_tags();
	    $wikidata_url ='https://www.wikidata.org/wiki/';
	    $the_archive_title = __('Tag archives: ');
	    //if a tag page, adds link 
	    
	    if($options['tag_title_link_checkbox']){
    	    foreach($tags as $tag){
    	        $tag_link = get_tag_link($tag->term_id);					// gets url for specific tag
    	        $tag_name = str_replace(" ", "_", $tag->name);
    	        $current_url = home_url (add_query_arg(array(), $wp->request)) . '/'; // gets current url
    	   
    	        // if current and tag url coincide, and tag associated to a wikidata id
    	        if(($current_url == $tag_link) && isset($options['tag-'.$tag_name])){
    	            $content = '<h1 class="page-title">'.$the_archive_title.'<a target="_blank" href='.$wikidata_url.$options['tag-'.$tag_name].' >'.$tag->name.'</a></h1>';
    	            return $content;
    	        }
    	    }
	    }
    	  
	    return $content;
	    
	}


	


}
