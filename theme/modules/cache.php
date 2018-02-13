<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:38
 */

namespace wpnuxt;


class cache {

	private  $c;
	function __construct($config){
		if(!$config){
			utils::admin_error("wp-nuxt-config.php error. \$config['cache'] missing from the config file");
			return;
		}
		$this->c = $config;

	}

}