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


	public $nonce_name = 'wp-nuxt-nonce';
	private $admin_page = null;


	function __construct() {
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'admin_init', array( $this, 'setNonce' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 999 );
	}


	function scripts( $hook ) {
		//enqueue only on menu edit page
		if ( $hook != $this->admin_page ) {
			return;
		}


		wp_enqueue_script( 'jquery' );


		wp_enqueue_script( 'wp-nuxt-admin', get_template_directory_uri(). '/admin/pages/wp-nuxt-admin.js', array( "jquery" ) );


		wp_enqueue_style( 'wp-nuxt-admin', get_template_directory_uri() . '/admin/pages/wp-nuxt-admin.css' );
		wp_enqueue_style( "jquery-ui", "https://unpkg.com/spectre.css/dist/spectre.min.css" );
		wp_enqueue_style( "jquery-ui", "https://unpkg.com/spectre.css/dist/spectre-exp.min.css" );
	}


	function setNonce() {
		//check admin and user capability
		if ( is_admin() && current_user_can( 'edit_posts' ) ) {
			//check is not ajax call
			if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
				setcookie( "$this->nonce_name", wp_create_nonce( $this->nonce_name ), time() + 3600 );
			}
		}
	}


	/**
	 * Removes The Appereance menu from the WP admin panel, this way most of the Theme functionality is removed
	 */
	function admin_menu() {
		remove_menu_page( 'themes.php' );
		$this->admin_page = add_menu_page(
			'Wp Nuxt Admin Page',
			'WP Nuxt',
			'manage_options',
			'wp-nuxt-admin-page',
			array( $this, 'render_admin_page' ),
			get_template_directory_uri() . "/assets/icon.png"
		);
	}



	function saveOption() {
		$nonce = $_COOKIE[ $this->nonce_name ];


		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if ( ! wp_verify_nonce( $nonce, $this->nonce_name ) ) {
			die ( 'Insecure Query!' );
		}

		//sets json header
		header( "Content-Type: application/json" );

		$response = '';

		// ignore the request if the current user doesn't have
		// sufficient permissions
		if ( current_user_can( 'edit_posts' ) ) {
			// get the submitted parameters
			$menu    = $_POST['menu'];
			$menu_id = $_POST['menu_id'];


			if ( $menu && $menu_id ) {

				$json = stripslashes( $menu ); //this operation is required as the json is send a post variable and not in the body
				// save file and generate success  response

				file_put_contents( self::getMenuPath( $menu_id ), $json );
				$response = json_encode( array( 'success' => true, "url" => self::getMenuURL( $menu_id ) ) );
			} else {
				//insufficient params
				header( 'HTTP/1.1 400 Bad Request', true, 400 );
				$response = json_encode( array( 'error' => "params menu & menu_id required" ) );
			}
		} else {
			header( 'HTTP/1.1 401 Unauthorized', true, 401 );
			$response = json_encode( array( 'error' => "not allowed" ) );
		}


		//set a new nonce so user can keep using ajax calls
		setcookie( $this->nonce_name, wp_create_nonce( $this->nonce_name ), time() + 3600 );

		// IMPORTANT: don't forget to "exit"
		// response output
		echo $response;
		exit;
	}


	function render_admin_page() {
		include_once __DIR__."/pages/main_page.php";
	}


}