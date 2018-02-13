<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 13:18
 */

namespace wpnuxt;


class sitemap {

	private  $c;
	function __construct($config){
		if(!$config){
			utils::admin_error("wp-nuxt-config.php error. \$config['sitemap'] missing from the config file!");
			return;
		}
		$this->c = $config;

	}
}