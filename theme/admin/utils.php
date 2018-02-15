<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 13:37
 */

namespace wpnuxt;
if ( ! defined( 'ABSPATH' ) ) exit;

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

}