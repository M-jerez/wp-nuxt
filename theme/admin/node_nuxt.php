<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:39
 */

namespace wpnuxt;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class node_nuxt {


	private $config;
	public $nonce_name = 'wp-nuxt-term';

	function __construct() {
		$this->config = utils::loadConfig();
		if ( ! $this->config ) {
			return;
		}
		add_action( 'admin_init', array( $this, 'setNonce' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 999 );
		add_action( 'wp_ajax_wpnuxt-cmd-generate', array( $this, 'nuxt_generate' ) );
		add_action( 'wp_ajax_wpnuxt-cmd-read', array( $this, 'nuxt_read' ) );
		add_action( 'admin_footer', array( $this, 'render_xterm_area' ) );
	}


	function setNonce() {
		utils::setNonce( $this->nonce_name );
	}

	function scripts() {


		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'termjs', "https://cdnjs.cloudflare.com/ajax/libs/xterm/2.9.2/xterm.min.js", array( "jquery" ) );
		wp_enqueue_script( 'termjs-fit', "https://cdnjs.cloudflare.com/ajax/libs/xterm/2.9.2/addons/fit/fit.min.js", array( "jquery" ) );
		wp_enqueue_script( 'wp-nuxt-term', get_template_directory_uri() . '/admin/pages/wp-nuxt-xterm.js', array( "jquery" ) );


		wp_enqueue_style( "termcss", "https://cdnjs.cloudflare.com/ajax/libs/xterm/2.9.2/xterm.min.css" );
		wp_enqueue_style( 'wp-nuxt-term', get_template_directory_uri() . '/admin/pages/wp-nuxt-xterm.css' );
	}


	/**
	 * Run the NUxt Generate Command
	 */
	function nuxt_generate() {
		utils::ajax_call_init( $this->nonce_name );

		if ( current_user_can( 'edit_posts' ) ) {

			$node_path      = filter_var( $this->config["nuxt"]["node_path"], FILTER_SANITIZE_URL );
			$nuxt_root_path = filter_var( $this->config["nuxt"]["nuxt_root_path"], FILTER_SANITIZE_URL );

			if ( utils::test_nuxt_path( $nuxt_root_path ) && utils::test_node_path( $node_path ) ) {

				//TODO:

				$shmid = ftok( __FILE__, "0" );

				$CWD = $nuxt_root_path;
				$CMD = "$nuxt_root_path/node_modules/.bin/nuxt  generate";


				if ( $_GET["mode"] == "runner" ) {
					// success: mode runner and not execurting
					new \wpnuxt\cmd_runner( $shmid, $CMD, $CWD );
				} else if ( $_GET["mode"] === "reader" ) {
					// success: mode reader and all parameters ok
					new \wpnuxt\cmd_reader( $shmid );
				} else {
					// fail: no missing parameter
					utils::jsonresponse( "fail", "missing parameters." );
				}

			} else {
				$response = json_encode( array(
					'status'  => "fail",
					"message" => "incorrect values node_path or nuxt_root_path in the wp-nuxt-config.php file. \n"
					             . "node path: $node_path \n"
					             . "node root: $nuxt_root_path "
				) );
			}

		} else {
			header( 'HTTP/1.1 401 Unauthorized', true, 401 );
			$response = json_encode( array( 'error' => "not allowed" ) );
		}

		utils::ajax_call_finish( $this->nonce_name, $response );
	}


	function render_xterm_area() {
		global $themeURL;
		$themeURL = get_template_directory_uri();
		include_once __DIR__ . "/pages/xterm_page.php";
	}

}
