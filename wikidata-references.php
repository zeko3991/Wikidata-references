<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              zeko3991@gmail.com
 * @since             1.0.0
 * @package           Wikidata_References
 *
 * @wordpress-plugin
 * Plugin Name:       WikiData References for WordPress
 * Plugin URI:        
 * Description:       Associate your tags and categories with Wikidata Items and add some metadata to your website.
 * Version:           1.0.0
 * Author:            Ezequiel Barbudo Revuelto
 * Author URI:        https://profiles.wordpress.org/zeko3991
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wikidata-references
 * Domain Path:       /languages
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );

add_action('activated_plugin', 'save_error');
function save_error(){
	update_option('_1_plugin_error', '-'.ob_get_contents().'-');
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wikidata-references-activator.php
 */
function activate_wikidata_references() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wikidata-references-activator.php';
	Wikidata_References_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wikidata-references-deactivator.php
 */
function deactivate_wikidata_references() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wikidata-references-deactivator.php';
	Wikidata_References_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wikidata_references' );
register_deactivation_hook( __FILE__, 'deactivate_wikidata_references' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-wikidata-references.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wikidata_references() {
	$plugin = new Wikidata_References();
	$plugin->run();
}

run_wikidata_references();
?>