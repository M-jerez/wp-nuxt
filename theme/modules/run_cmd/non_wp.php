<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 08/03/2018
 * Time: 19:08
 */


include __DIR__ . '/../../autoloader.php';
spl_autoload_register('autoloader::loader');



$config = \wpnuxt\utils::loadConfig();

$node_path      = filter_var( $config["nuxt"]["node_path"], FILTER_SANITIZE_URL );
$nuxt_root_path = filter_var( $config["nuxt"]["nuxt_root_path"], FILTER_SANITIZE_URL );

$nuxt_root_path = "/Applications/AMPPS/www/mjerez/wp-nuxt/nuxt-test-instance";
$shmid = ftok( __FILE__, "0" );

$CWD = $nuxt_root_path;
$CMD = "$node_path $nuxt_root_path/node_modules/.bin/nuxt  generate";


if ( $_GET["mode"] == "runner" ) {
	// success: mode runner and not execurting
	new \wpnuxt\cmd_runner( $shmid, $CMD, $CWD );
} else if ( $_GET["mode"] === "reader" ) {
	// success: mode reader and all parameters ok
	new \wpnuxt\cmd_reader( $shmid );
} else {
	// fail: no missing parameter
	\wpnuxt\utils::json_response( "fail", "missing parameters." );
}