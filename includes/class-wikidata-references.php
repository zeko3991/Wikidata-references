<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */

//include 'class-wikidata-references-metabox.php';

class Wikidata_References {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wikidata_References_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_VERSION' ) ) {
			$this->version = PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wikidata-references';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wikidata_References_Loader. Orchestrates the hooks of the plugin.
	 * - Wikidata_References_i18n. Defines internationalization functionality.
	 * - Wikidata_References_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wikidata-references-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wikidata-references-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wikidata-references-admin.php';

		$this->loader = new Wikidata_References_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wikidata_References_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Wikidata_References_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wikidata_References_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		//activate metabox.
		//$metabox = new Wikidata_References_metabox();
		
		/*
		 * Wikidata references
		 */
		//Add menu item
		$this->loader->add_action('admin_menu', $plugin_admin, 'wkrf_add_plugin_admin_menu');
		//Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path(__DIR__) . $this->plugin_name. '.php' );
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'wkrf_add_action_links');

		//Save/update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'wkrf_setup_options_update');
		
		//adds wikidata tag metadata to head
		$this->loader->add_action('wp_head', $plugin_admin, 'wkrf_add_head_wikidata_taxonomy_links', 1, 1);
		//changes the tag archive title for a link to wikidata
		$this->loader->add_filter('get_the_archive_title', $plugin_admin, 'wkrf_add_wiki_link_archive_title', 10, 1);
		
		//$this->loader->add_filter('term_links', $plugin_admin, 'wkrf_add_wiki_link_taxonomy_terms', 10, 1);
		$this->loader->add_filter('term_links-post_tag', $plugin_admin, 'wkrf_add_wiki_link_taxonomy_terms', 10, 1);
		//adds metadata to a post when saved
		$this->loader->add_action('save_post', $plugin_admin, 'wkrf_add_meta_to_posts', 1, 1);

		
		//TAG WIKIDATA ASSOCIATION IN TAG EDIT SCREEN
		
			//Wikidata id post tag column
		$this->loader->add_filter('manage_edit-post_tag_columns', $plugin_admin, 'wkrf_add_taxonomy_wikidata_column');
		$this->loader->add_filter('manage_post_tag_custom_column', $plugin_admin, 'wkrf_add_taxonomy_wikidata_column_content', 10, 3);
		$this->loader->add_filter('manage_edit-post_tag_sortable_columns', $plugin_admin, 'wkrf_register_wikidata_sortable_column');
			
			
			//add new/edit tag form
		$this->loader->add_action('add_tag_form_fields', $plugin_admin, 'wkrf_add_wikidata_id_taxonomy_field', 10, 1);
		$this->loader->add_action('edit_tag_form_fields', $plugin_admin, 'wkrf_edit_wikidata_id_taxonomy_field', 10, 1);
		$this->loader->add_action('edited_terms', $plugin_admin, 'wkrf_save_wikidata_taxonomy_fields', 10, 2);
		$this->loader->add_action('create_post_tag', $plugin_admin, 'wkrf_add_new_term_wikidata_id', 10, 2);
		////////////////////////////////////////////////////
		
			//Wikidata id category column
		$this->loader->add_filter('manage_edit-category_columns', $plugin_admin, 'wkrf_add_taxonomy_wikidata_column');
		$this->loader->add_filter('manage_category_custom_column', $plugin_admin, 'wkrf_add_taxonomy_wikidata_column_content', 10, 3);
		$this->loader->add_filter('manage_edit-category_sortable_columns', $plugin_admin, 'wkrf_register_wikidata_sortable_column');
		
		$this->loader->add_action('category_add_form_fields', $plugin_admin, 'wkrf_add_wikidata_id_taxonomy_field', 10 , 1);
		$this->loader->add_action('edit_category_form_fields', $plugin_admin, 'wkrf_edit_wikidata_id_taxonomy_field', 10, 1);
		$this->loader->add_action('edited_terms', $plugin_admin, 'wkrf_save_wikidata_taxonomy_fields', 10, 2);
		$this->loader->add_action('create_category', $plugin_admin, 'wkrf_add_new_term_wikidata_id', 10, 2);
		
			//Taxonomy (post_tag and category) column sort 
		$this->loader->add_filter('terms_clauses', $plugin_admin, 'wrkf_sort_taxonomy_by_wikidata_id', 10, 3);
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wikidata_References_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	
	


}
