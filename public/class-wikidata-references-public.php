<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/public
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */
class Wikidata_References_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wikidata-references-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wikidata-references-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Wikidata References
	 * Adds metadata to head from the metadata values added in plugin setup.
	 */
	public function wkrf_add_header_metadata(){
	    $options = get_option($this->plugin_name);
	    if($options['metadata_checkbox']){
	        if(isset($options['author_meta'])){
	            echo '<meta name="author" content="'.$options['author_meta'].'" />';
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
		
		//test, echoes all plugin saved options
		/*
		foreach($options as $option){
			echo'<meta property="option" content="'.$option.'" />';
		}
		*/
		
	}
	
	
	
	public function wkrf_add_tag_title_microdata($content){
		//$old_title = '#<h1 class="page-title">(.*?)</h1>#';
		$old_title = '/shop/';
		//$old_title = '/Etiqueta: shop/';
		
		//$old_title = '/<p>nueva entrada<\/p>/';
		//$old_title = '/<header class="page-header">(.*?)<\/header>/';
		//$new_title = '<h1 class="page-title">New title</h1>';
		$new_title = 'shoop';
		//$new_title = '<h1 class="page-titleee">';
		//$new_title = '<p>Changed this using "the content" hooks</>';
		//$new_header= get_header();
		//error_log('-------------------------');
		error_log('header: '.$content);
		//error_log('-------------------------');
		$content= preg_replace($old_title, $new_title, $content);
		//$header= 'helo'.$header;
		error_log("body: ".$content);
		//$content= $content.' <meta property="holi" content="holi" />';
		//$content = $content.' donde entra esto?';
		//$content = $content.' \n '.single_tag_title();
		//$content = $content.'Changed this using "the_title" hook';
		return $content;
		
	}

}
