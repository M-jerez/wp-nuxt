<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:39
 */

namespace wpnuxt;
if ( ! defined( 'ABSPATH' ) ) exit;

class node_nuxt {


	private  $config;
	function __construct(){
		$this->config = utils::loadConfig();
		if(!$this->config)
			return;
		add_action('wp_ajax_run-nuxt-generate', array($this, 'nuxt_generate'));
	}




	function nuxt_generate(){
		ini_set('max_execution_time', 600);

	}
}