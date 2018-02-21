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



// loads the config file
$config_file  = __DIR__ . "/wp-nuxt-config.php";
$module_names = array( "cache", "node_nuxt", "rest", "sitemap" );
$wpn_config   = include( $config_file );


if ( ! $wpn_config ) {
	$erro_message = g( "wp-nuxt cant read the config file at <code>%s</code>", $config_file );
	utils::admin_error( $erro_message );
	return;
}

if ( is_admin() ) {







	// load the components
	new \wpnuxt\cache( $wpn_config['cache'] );
	new \wpnuxt\node_nuxt( $wpn_config['node_nuxt'] );
	new \wpnuxt\rest( $wpn_config['rest'] );
	new \wpnuxt\sitemap( $wpn_config['sitemap'] );
	new \wpnuxt\admin_panel();
}else{

}
