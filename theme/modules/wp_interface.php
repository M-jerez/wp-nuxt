<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 05/03/2018
 * Time: 14:08
 */

namespace wpnuxt;
if ( ! defined( 'ABSPATH' ) ) exit;


class wp_interface {


	private  $config;
	function __construct(){
		$this->config = utils::loadConfig();
		if(!$this->config)
			return;

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}




	/**
	 * Configure wordpress interface depending on config options
	 */
	function admin_menu() {

		//remove "theme" tab form the admin panel
		if($this->config["wp_interface"]["disable_theme_settings"]){
			remove_menu_page( 'themes.php' );
		}


		//hide-show "menus" tab on the admin panel
		if($this->config["wp_interface"]["enable_menus"]){
			add_menu_page(
				"menus",
				'Menus',
				'edit_theme_options',
				"nav-menus.php",
				"",
				"dashicons-menu",
				61 );
		}
	}


}