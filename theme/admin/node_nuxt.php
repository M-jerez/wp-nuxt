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
		add_action( 'wp_ajax_start-term-worker', array( $this, 'ajax_start_term_worker' ) );
	}



	function setNonce() {
		utils::setNonce($this->nonce_name);
	}

	function scripts() {


		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'termjs', "https://cdnjs.cloudflare.com/ajax/libs/xterm/2.9.2/xterm.min.js", array( "jquery" ) );
		wp_enqueue_script( 'wp-nuxt-term', get_template_directory_uri(). '/admin/pages/wp-nuxt-term.js', array( "jquery" ) );

		wp_enqueue_style( "termcss", "https://cdnjs.cloudflare.com/ajax/libs/xterm/2.9.2/xterm.min.css" );
		wp_enqueue_style( 'wp-nuxt-term', get_template_directory_uri() . '/admin/pages/wp-nuxt-term.css' );
	}

	/**
	 *
	 */
	function ajax_start_term_worker() {
		utils::ajax_call_init( $this->nonce_name );

		if ( current_user_can( 'edit_posts' ) ) {

			$node_path      = filter_var( $this->config["nuxt"]["node_path"], FILTER_SANITIZE_URL );
			$nuxt_root_path = filter_var( $this->config["nuxt"]["nuxt_root_path"], FILTER_SANITIZE_URL );

			if ( utils::test_nuxt_path( $nuxt_root_path ) && utils::test_node_path( $node_path ) ) {

				//TODO:

			} else {
				$response = json_encode( array( 'status'  => "fail",
				                                "message" => "incorrect values node_path or nuxt_root_path in the wp-nuxt-config.php file"
				) );
			}

		} else {
			header( 'HTTP/1.1 401 Unauthorized', true, 401 );
			$response = json_encode( array( 'error' => "not allowed" ) );
		}

		utils::ajax_call_finish( $this->nonce_name, $response );

	}


	public static function nuxt_generate( $node_path, $nuxt_path ) {
		ini_set( 'max_execution_time', 600 );
	}
}