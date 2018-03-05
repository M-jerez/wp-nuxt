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
	private $config = null;

	function __construct() {
		if ( ! is_admin() ) {
			return;
		}
		$this->config = utils::loadConfig();
		if(!$this->config)
			return;
		add_action( 'admin_init', array( $this, 'setNonce' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 999 );
		add_action('wp_ajax_save-' . $this->nonce_name, array($this, 'saveSettings'));
	}


	function scripts( $hook ) {
		//enqueue only on menu edit page
		if ( $hook != $this->admin_page ) {
			return;
		}


		wp_enqueue_script( 'jquery' );


		wp_enqueue_script( 'wp-nuxt-admin', get_template_directory_uri(). '/admin/pages/wp-nuxt-admin.js', array( "jquery" ) );
		wp_enqueue_script( 'toastjs', "//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js", array( "jquery" ) );

		wp_enqueue_style( 'wp-nuxt-admin', get_template_directory_uri() . '/admin/pages/wp-nuxt-admin.css' );
		wp_enqueue_style( "spectre", "https://unpkg.com/spectre.css/dist/spectre.min.css" );
		wp_enqueue_style( "spectre-exp", "https://unpkg.com/spectre.css/dist/spectre-exp.min.css" );
		wp_enqueue_style( "toastcss", "//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" );
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
	 * Add the wp nuxt admin page
	 */
	function admin_menu() {
		$this->admin_page = add_menu_page(
			'Wp Nuxt Admin Page',
			'WP Nuxt',
			'manage_options',
			'wp-nuxt-admin-page',
			array( $this, 'render_admin_page' ),
			get_template_directory_uri() . "/assets/icon.png"
		);
	}


	/**
	 * this must be an ajax call
	 */
	function saveSettings() {
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
		if ( current_user_can( 'edit_posts' )) {
			//TODO   update config
			foreach ($this->config  as $key => $value ){
				$subfield = $value;
				foreach ($subfield  as $sub_key => $sub_value ){
						if(isset($_POST[$key][$sub_key])){
							$x = filter_var($_POST[$key][$sub_key], FILTER_SANITIZE_STRING);;
							$this->config[$key][$sub_key] = utils::on_off_true_false($x);
						}

				}
			}
			utils::saveConfig($this->config);
			$response = json_encode( array( 'status' => "success", "config" => $this->config ) );
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


	/**
	 * Updates the config file from the value in the post array
	 */
	static function updateConfigFromPOST(){

	}

	function render_admin_page() {
		global $nonce_name, $themeURL;
		$nonce_name = $this->nonce_name;
		$themeURL = get_template_directory_uri();
		include_once __DIR__ . "/pages/admin_page.php";
	}


}