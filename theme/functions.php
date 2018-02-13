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
	// docs : https://github.com/M-jerez/php-translation
	i18nMessages::setLocale( get_locale() );

	$wpnc_file  = __DIR__ . "/admin/wp-nuxt-config.php";
	$wpn_config = include( $wpnc_file );
	$wpn_config = false;
	if ( ! $wpn_config ) {

		utils::admin_error( g( "wp-nuxt cant read the config file at <code>%s</code>", $wpnc_file ));
	} else if ( utils::check_modules_config(
		$wpn_config,
		array( "cache", "node_nuxt", "rest", "sitemap" )
	) ) {
		new \wpnuxt\cache( $wpn_config['cache'] );
		new \wpnuxt\node_nuxt( $wpn_config['node_nuxt'] );
		new \wpnuxt\rest( $wpn_config['rest'] );
		new \wpnuxt\sitemap( $wpn_config['sitemap'] );
		new \wpnuxt\admin_panel();
	}
}



