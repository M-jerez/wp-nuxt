<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:42
 */

namespace wpnuxt;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class admin_panel {


	function __construct() {
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}


	/**
	 * Removes The Appereance menu from the WP admin panel, this way most of the Theme functionality is removed
	 */
	function admin_menu() {
		remove_menu_page( 'themes.php' );
		add_menu_page(
			'Wp Nuxt Admin Page',
			'WP Nuxt',
			'manage_options',
			'wp-nuxt-admin-page',
			array( $this, 'render_admin_page' ),
			get_template_directory_uri() . "/assets/icon.png"
		);
	}


	function render_admin_page() {
		echo "<h3>Wp Nuxt Options</h3>";
	}


}