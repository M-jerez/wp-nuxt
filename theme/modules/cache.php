<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:38
 */

namespace wpnuxt;
if ( ! defined( 'ABSPATH' ) ) exit;

class cache {

	private  $c;
	function __construct($config){
		$this->c = $config;

	}

}