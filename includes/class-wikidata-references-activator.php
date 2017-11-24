<?php

/**
 * Fired during plugin activation
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wikidata_References
 * @subpackage Wikidata_References/includes
 * @author     Ezequiel Barbudo Revuelto <zeko3991@gmail.com>
 */
class Wikidata_References_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		error_log('errores activacion: '.ob_get_contents());
	}

}
