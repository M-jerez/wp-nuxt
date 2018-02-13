<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 12/02/2018
 * Time: 18:03
 */

use \wpnuxt\utils;

if ( is_admin() ) {

	include __DIR__ . "/tools/i18nMessages.php";
	include __DIR__ . "/modules/cache.php";
	include __DIR__ . "/modules/node_nuxt.php";
	include __DIR__ . "/modules/rest.php";
	include __DIR__ . "/modules/sitemap.php";
	include __DIR__ . "/admin/admin_panel.php";
	include __DIR__ . "/admin/utils.php";

	//adds very basic internationalization functionality to the theme.
	// wp __()  and pot files are too complicated to mantain.
	// this library uses he functions p() and g() to print and get the translates messages;
	// docs : https://github.com/M-jerez/php-translation
	i18nMessages::setLocale( get_locale() );


	// loads the config file
	$config_file  = __DIR__ . "/admin/wp-nuxt-config.php";
	$module_names = array( "cache", "node_nuxt", "rest", "sitemap" );
	$wpn_config   = include( $config_file );


	if ( ! $wpn_config ) {

		$erro_message = g( "wp-nuxt cant read the config file at <code>%s</code>", $config_file );
		utils::admin_error( $erro_message );

	} else if ( utils::check_modules_config( $wpn_config, $module_names ) ) {

		// if all cofiguration is correct loads the modules
		new \wpnuxt\cache( $wpn_config['cache'] );
		new \wpnuxt\node_nuxt( $wpn_config['node_nuxt'] );
		new \wpnuxt\rest( $wpn_config['rest'] );
		new \wpnuxt\sitemap( $wpn_config['sitemap'] );
		new \wpnuxt\admin_panel();
	}
}



