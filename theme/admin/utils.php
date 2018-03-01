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

		file_put_contents( get_template_directory() . "/wp-nuxt-config.php", $code );
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

		$config_file      = get_template_directory() . "/wp-nuxt-config.php";
		self::$wpn_config = include( $config_file );

		if ( ! self::$wpn_config ) {
			self::$wpn_config_error = g( "wp-nuxt cant read the config file at <code>%s</code>", $config_file );
			self::admin_error( self::$wpn_config_error );
			return false;
		}

		return self::$wpn_config;
	}
}