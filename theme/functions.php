<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 12/02/2018
 * Time: 18:03
 */
if ( ! defined( 'ABSPATH' ) ) exit;



if ( is_admin() ) {

	include __DIR__ . '/autoloader.php';
	spl_autoload_register('autoloader::loader');


	//adds very basic internationalization functionality to the theme.
	// wp __()  and pot files are too complicated to mantain.
	// this library uses he functions p() and g() to print and get the translates messages;
	// docs : https://github.com/M-jerez/php-translation
	i18nMessages::setLocale( get_locale() );


	// load the components
	new \wpnuxt\cache( $wpn_config['cache'] );
	new \wpnuxt\node_nuxt( $wpn_config['node_nuxt'] );
	new \wpnuxt\rest( $wpn_config['rest'] );
	new \wpnuxt\sitemap( $wpn_config['sitemap'] );
	new \wpnuxt\admin_panel();
}
