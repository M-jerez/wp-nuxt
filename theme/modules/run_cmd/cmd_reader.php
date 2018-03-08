<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 08/03/2018
 * Time: 13:21
 */

namespace wpnuxt;


class cmd_reader {


	function __construct( $shmid_key ) {

		if ( ! isset( $_GET["line_number"] ) ) {
			// fail: no missing parameter
			utils::json_response( "fail", "missing parameter line_number." );
			return;
		}

		if ( !sMem::isOpenMem( $shmid_key ) ) {
			// fail: no command running
			utils::json_response( "fail", "command output not found for shmid:$shmid_key ." );
			return;
		}



		$line = intval( filter_var( $_GET["line_number"], FILTER_SANITIZE_NUMBER_INT ), 10 );

		$shmid = sMem::openMemExisting($shmid_key);
		$this->getNewLines($shmid_key,$shmid,$line);
		sMem::closeMem($shmid,false);
	}


	function getNewLines($shmid_key,$shmid,$start_line) {
		$content = sMem::readMem($shmid);
		$data = array(
			"end_line" => 0,
			"output" => array(),
			"shmid" => $shmid_key
		);
		if($content){
			$lines = explode("\n",$content);
			$l = count($lines);
			$output = array();
			for ($i = $start_line; $i <= $l; $i++) {
				$output[] .= $lines[$i]."\n";
			}
			$data["end_line"] = $i;
			$data["output"] = $output;
			$data["shmid"] = $shmid;
			utils::json_response( "success", "output found" , $data );
		}else{
			utils::json_response( "success", "empty output" , $data );
		}
	}
}