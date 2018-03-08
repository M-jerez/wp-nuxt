<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 08/03/2018
 * Time: 13:21
 */

namespace wpnuxt;


class cmd_reader {


	function __construct( $systemid ) {
		if(!sMem::isOpenMem($systemid)){
			// fail: no command running
			utils::json_response( "fail", "command not running." );
		}

		if(!isset( $_GET["line_number"] )){
			// fail: no missing parameter
			utils::json_response( "fail", "missing parameter line_number." );
		}

	}
}