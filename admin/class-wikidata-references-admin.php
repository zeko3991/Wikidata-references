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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wikidata-references-admin.css', array(), $this->version, 'all' );

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
	}
	
	
	/**
	 * Wikidata References
	 * validates checkboxes' input from the wikidata references' setup page
	 * @since 1.0.0
	 */
	public function wkrf_validate_wiki_references_setup($input){
		$valid = array();
		
		$valid['references_by_tag'] = (isset($input['references_by_tag']) && !empty($input['references_by_tag'])) ? 1 : 0;
		$valid['references_footnote'] = (isset($input['references_footnote']) && !empty($input['references_footnote'])) ? 1 : 0;
		$valid['ieee_format'] = (isset($input['ieee_format']) && !empty($input['ieee_format'])) ? 1 : 0;
		$valid['harvard_format'] = (isset($input['harvard_format']) && !empty($input['harvard_format'])) ? 1 : 0;
		return $valid;
	}

}
