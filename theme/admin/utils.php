<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 13:37
 */

namespace wpnuxt;


class utils {


	/**
	 * Prints and error on the admin panel
	 *
	 * @param $message
	 */
	static function admin_error( $message ) {
		add_action( 'admin_notices', function () use ( $message ) {
			?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo $message ?></p>
            </div>
			<?php
		} );
	}

	/**
	 * Prints a success message on the admin panel.
	 *
	 * @param $message
	 */
	static function admin_success( $message ) {
		add_action( 'admin_notices', function () use ( $message ) {
			?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo $message ?></p>
            </div>
			<?php
		} );
	}


	/**
	 * Returns a string to insert as the "value" attribute on an html input
	 *
	 * @param $value
	 *
	 * @return string
	 */
	static function getConfigValueAttr( $value ) {
		if ( ! empty( $value ) ) {
			return "value='$value'";
		} else {
			return "";
		}
	}

	/**
	 * Returns a string to insert as the "checked" property on an html input
	 *
	 * @param $value
	 *
	 * @return string
	 */
	static function getConfigCheckedAttr( $value ) {
		if ( ! empty( $value ) ) {
			return "checked";
		} else {
			return "";
		}
	}


	/**
	 * Transform "on" "off" strings into true false booleans
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	static function on_off_true_false( $value ) {
		if ( $value === "on" ) {
			return true;
		} else if ( $value === "off" ) {
			return false;
		} else {
			return $value;
		}
	}


	/**
	 * @param $config
	 */
	static function saveConfig( $config ) {
		$code = "<?php\n\n\nreturn " . var_export( $config, true ) . ";";

		file_put_contents( __DIR__ . "/../wp-nuxt-config.php", $code );
	}


	private static $wpn_config = null;
	private static $wpn_config_error = false;


	/**
	 * load the configuration file;
	 * @return mixed|null
	 */
	static function loadConfig() {
		if ( self::$wpn_config_error ) {
			return false;
		} else if ( self::$wpn_config ) {
			return self::$wpn_config;
		}

		$config_file      = __DIR__ . "/../wp-nuxt-config.php";
		self::$wpn_config = include( $config_file );

		if ( ! self::$wpn_config ) {
			self::$wpn_config_error = g( "wp-nuxt cant read the config file at <code>%s</code>", $config_file );
			self::admin_error( self::$wpn_config_error );
			return false;
		}

		return self::$wpn_config;
	}




	/**
	 * Test node path, check the path is an existing file and if so executes the command to get the node version -v
	 * @param $path
	 *
	 * @return array|bool  the nodejs version or false
	 */
	static function test_node_path($path){
	    $path = realpath($path);
		$output=array();
		$exit_code = 0;
		$is_file = file_exists($path);
		if(!$is_file)
			return false;
		exec("$path -v",$output,$exit_code);

		if($exit_code === 0){
			return $output;
		}else{
			return false;
		}
	}



	/**
	 * Test nuxt path, checks "/node_modules/.bin/nuxt" and "/nuxt.config.js" exist
	 * @param $path
	 *
	 * @return bool
	 */
	static function test_nuxt_path($nuxt_root){
		$nuxt_root = self::resolve_ABSPATH_path($nuxt_root);
		$exec_file = "/node_modules/.bin/nuxt";
		$conf_file = "/nuxt.config.js";
		$nuxt_exec_path =  $nuxt_root.$exec_file;
		$nuxt_conig_path =  $nuxt_root.$conf_file;
		$nexp = file_exists($nuxt_exec_path);
		$nconfp = file_exists($nuxt_conig_path);

		if(!is_dir($nuxt_root)){
			return false;
		}else if(!($nexp && $nconfp)){
			return false;
		}else{
			return true;
		}
	}


	/**
     * gets an absolute or path relative to the constant ABSPATH, andretruns its real path
	 * @param $path
	 */
	static function resolve_ABSPATH_path($path){

	    $rel_str = ABSPATH.$path;
	    $abs = $path;

        if(file_exists($rel_str)){
            return realpath($rel_str);
        }

        if(file_exists($abs)){
	        return realpath($abs);
        }

        return $path;
    }


	/**
     * function to be called at the beguining of every ajax call.
     * verifies nonce and set json content type
	 * @param $nonce_name
	 */
	static function ajax_call_init($nonce_name){

		//check admin and user capability
		$nonce = $_COOKIE[ $nonce_name ];


		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if ( ! wp_verify_nonce( $nonce, $nonce_name ) ) {
			die ( 'Insecure Query!' );
		}

		//sets json header
		header( "Content-Type: application/json" );
	}


	/** Funciton to be used to end and ajax call
     * Sets next nonce and returns response
	 * @param $nonce_name
	 * @param $response
	 */
	static function ajax_call_finish($nonce_name , $response = false){
		//set a new nonce so user can keep using ajax calls
		setcookie($nonce_name, wp_create_nonce( $nonce_name ), time() + 3600 );

		// IMPORTANT: don't forget to "exit"
		// response output
        if($response)
		    echo $response;
		exit;
	}




	/**
     * Creates a wordpress nonce
	 * @param $action
	 */
	static function setNonce($nonce_name) {
		//check admin and user capability
		if ( is_admin() && current_user_can( 'edit_posts' ) ) {
			//check is not ajax call
			if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
				setcookie($nonce_name, wp_create_nonce( $nonce_name ), time() + 3600 );
			}
		}
	}


	/**
     * return a json response
	 * @param $staus
	 * @param $message
	 * @param bool | array $data
	 */
	static function json_response( $staus, $message ,$data = false) {
		header( 'Content-Type: application/json' );
		echo json_encode( array(
			"status"  => $staus,
			"message" => $message,
            "data" => $data
		) );
	}


}