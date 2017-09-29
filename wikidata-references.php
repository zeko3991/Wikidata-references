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
 * Plugin URI:        zeko3991@gmail.com
 * Description:       Incluye referencias a artículos de wikidata de forma rápida y sencilla mientras editas tus entradas.
 * Version:           1.0.0
 * Author:            Ezequiel Barbudo Revuelto
 * Author URI:        zeko3991@gmail.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wikidata-references
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );

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
 * Wikidata References
 * The class responsible for defining and displaying a metabox in the editing post area for wikidata
 * references plugin.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wikidata-references-metabox.php';



//////////////////////////////////////////////////////////////////////////////////
/*
add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {

		var data = {
			'action': 'my_action',
			'whatever': 1234
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			alert('Got this from the server: ' + response);
		});
	});
	</script> <?php
}

	add_action( 'wp_ajax_my_action', 'my_action' );
	
	function my_action() {
		global $wpdb; // this is how you get access to the database
		
		$whatever = intval( $_POST['whatever'] );
		
		$whatever += 10;
		
		echo $whatever;
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	*/
	////////////////////////////////////////////////////////////////////////

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

