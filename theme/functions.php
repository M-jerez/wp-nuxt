<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 12/02/2018
 * Time: 18:03
 */
if ( ! defined( 'ABSPATH' ) ) exit;

include __DIR__ . '/autoloader.php';
spl_autoload_register('autoloader::loader');
//adds very basic internationalization functionality to the theme.
// wp __()  and pot files are too complicated to mantain.
// this library uses he functions p() and g() to print and get the translates messages;
// docs : https://github.com/M-jerez/php-translation
i18nMessages::setLocale( get_locale() );


define( "WPN_REST_URL",   'wpnuxt/v2' );

// loads the config file






if ( is_admin() ) {

	// load the admin components
	new \wpnuxt\node_nuxt();
	new \wpnuxt\rest();
	new \wpnuxt\admin_panel();

	new \wpnuxt\theme_setup();
	new \wpnuxt\wp_interface();

	//new \wpnuxt\sitemap();
}else{

}





