<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 12/02/2018
 * Time: 18:03
 */


if(is_admin()){
	include __DIR__."/tools/i18nMessages.php";
	include __DIR__ . "/modules/cache.php";
	include __DIR__ . "/modules/node_nuxt.php";
	include __DIR__ . "/modules/rest.php";
	include __DIR__ . "/modules/sitemap.php";
	include __DIR__ . "/admin/admin_panel.php";
	include __DIR__ . "/admin/utils.php";

	//we are using our own custom i18n class so no need to generate pot files
	i18nMessages::setLocale(get_locale());

	$wpnc_file =  __DIR__ . "/admin/wp-nuxt-config.php";
	$wpn_config = include( $wpnc_file );


	if ( $wpn_config ) {
		new \wpnuxt\cache( $wpn_config['cache'] );
		new \wpnuxt\node_nuxt( $wpn_config['node_nuxt'] );
		new \wpnuxt\rest( $wpn_config['rest'] );
		new \wpnuxt\sitemap( $wpn_config['sitemap'] );
		new \wpnuxt\admin_panel();
	} else {
		\wpnuxt\utils::admin_error( g("wp-nuxt cant read the config file at <code>%s</code>", $wpnc_file));
	}
}



