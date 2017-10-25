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
	 * base of wikidata url
	 */
	
	
	public function wkrf_add_header_metadata_action(){
		//echo "<?php do_action( 'wkrf_add_header_tag_metadata'); ";
		
		//echo '<meta property="hello" content="hello" >';
	}
	
	/**
	 * Wikidata References
	 * adds meta value for each tag with an associated wikidata id value
	 * @since 1.0.0
	 */
	public function wkrf_add_header_tag_metadata(){
		$options = get_option($this->plugin_name);
		$tags = get_tags();
		$wikidata_url ='https://www.wikidata.org/wiki/';
		global $wp;
		
		foreach($tags as $tag){
			$tag_link = get_tag_link($tag->term_id);
			$tag_name = str_replace(" ", "_", $tag->name);
			$current_url = home_url (add_query_arg(array(), $wp->request)) . '/';
			
			if(($current_url == $tag_link) && isset($options[$tag_name])){
				echo '<meta property="dc.sameAs" content="'.$wikidata_url.$options[$tag_name].'" />';
			}
		}
		
		
//		echo '<meta property="tag link" content="'.
	}

}
