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
     * Check the sections within the config file, just to double check
	 * @param $config
	 * @param array $moduleNames
	 *
	 * @return bool
	 */
	static function check_modules_config($config,$moduleNames = array()){
	    $ok = true;
	    foreach ($moduleNames as $name){
		    if(!$config[$name]){
			    utils::admin_error($name);
			    $ok = false;
		    }
        }
        return $ok;
    }

	/**
     * Prints an admin error if the config for a module is lost
	 * @param $modulename
	 */
	static function wpnuxt_config_error($modulename){
		utils::admin_error(g("wp-nuxt-config.php error. config['%'] missing from the config file!",$modulename));
    }

	/**
	 * Prints and error on the admin panel
	 * @param $message
	 */
	static function admin_error($message){
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
	 * @param $message
	 */
	static function admin_success($message){
		add_action( 'admin_notices', function () use ( $message )  {
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo $message ?></p>
			</div>
			<?php
		} );
	}

}