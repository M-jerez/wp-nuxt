<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:39
 */

namespace wpnuxt;


class node_nuxt {


	private  $c;
	function __construct($config){
		if(!$config){
			utils::admin_error("wp-nuxt-config.php error. \$config['node_nuxt'] missing from the config file");
			return;
		}
		$this->c = $config;

	}
}