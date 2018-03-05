<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 05/03/2018
 * Time: 14:31
 */

namespace wpnuxt;
if ( ! defined( 'ABSPATH' ) ) exit;


class theme_setup {


	function __construct(){
		add_action( 'after_setup_theme', array( $this, 'wpnuxt_setup' ) );
	}


	function  wpnuxt_setup(){
		$this->register_menus();
		$this->image_sizes();
	}



	function register_menus(){
		register_nav_menu( 'primary', g( 'Primary Menu') );
		register_nav_menu( 'sidebar', g( 'Sidebar Menu') );
		register_nav_menu( 'footer', g( 'Footer Menu') );
	}


	/**
	 * configure WordPress to use modern images sizes
	 */
	function image_sizes(){
		add_image_size( 'thumbnail', 320, 320, false );
		add_image_size( 'mobile', 640, 480, false );
		add_image_size( 'medium', 1024, 768, false );
		add_image_size( 'large', 1920, 1080, false );
		add_image_size( 'xlarge', 2560, 1440, false );
	}
}