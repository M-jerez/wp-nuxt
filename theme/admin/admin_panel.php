<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:42
 */

namespace wpnuxt;


class admin_panel {


	function __construct(){
		if(!is_admin())
			return;
		add_action( 'admin_menu', array( $this, 'remove_admin_items' ) );
	}




	/**
	 * Removes The Appereance menu from the WP admin panel, this way most of the Theme functionality is removed
	 */
	function remove_admin_items() {
		remove_menu_page( 'themes.php' );
//		add_menu_page(
//			"menus",
//			'Menus',
//			'edit_theme_options',
//			"nav-menus.php",
//			"",
//			"dashicons-menu",
//			61 );
	}
}