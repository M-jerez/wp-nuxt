<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 08/03/2018
 * Time: 13:17
 */

namespace wpnuxt;
use wpnuxt\utils as utils;

/**
 * Runs a command and Write it's output to shared mem
 * Class cmd_runner
 * @package wpnuxt
 */
class cmd_runner {

	static $first_output = "\e[42m\e[30m RUNNING NUXT GENERATE \e[39m\e[49m\n";

	static $exit_codes = array(
		0 => "command successful",
		1 => "General Error",
		2 => "Misuse of shell builtins",
		126 => "Command invoked cannot execute",
		127 => "command not found",
		128 => "Invalid argument to exit",
		137 => "Fatal error signal \"n\"",
		130 => "Script terminated by Control-C"
	);


	function __construct( $shmid_key, $CMD, $CWD ) {
		if(sMem::isOpenMem($shmid_key)){
			// error: mode runner and already executing
			utils::json_response( "fail", "command already running." );
			return;
		}


		set_time_limit( 0 );
		$this->runCommand( $shmid_key, $CMD, $CWD );

	}

	function exit($data){
		$c = $data["exit_code"];
		$exit_message = (self::$exit_codes[$c])?self::$exit_codes[$c]:"Unknown Error";
		if($data["exit_code"] != 0){
			utils::json_response("fail", $exit_message, $data);
		}else{
			utils::json_response("success","command running",$data);
		}
	}


	/**
	 * Executes a command and captures STDIN and STOUD
	 *
	 * @param $systemid
	 * @param $CMD
	 * @param $CWD
	 *
	 * @return array|null
	 */
	function runCommand( $shmid_key, $CMD, $CWD ) {
		$descriptorspec = array(
			0 => array( "pipe", "r" ),
			1 => array( "pipe", "w" ),
			2 => array( "pipe", "w" )
			//2 => array( "file", $file, "a" )
		);


		$start_time = time();
		$process    = proc_open( $CMD, $descriptorspec, $pipes, $CWD );
		$data = array(
			"output" => null,
			"exit_status" => null
		);
		$real_shmid = 0;
		if ( is_resource( $process ) ) {
			$shmid = sMem::openMem( $shmid_key);
			$output  = $this->captureOutput($shmid, $pipes, $start_time, 1 );
			sMem::closeMem( $shmid );
		}
		fclose( $pipes[0] );
		fclose( $pipes[1] );
		fclose( $pipes[2] );

		$data["output"] = $output;
		$data["exit_code"] = proc_close( $process );
		$data["shmid"] = $shmid_key;

		$this->exit($data);
	}


	/**
	 * @param $pipes
	 * @param $start_time
	 * @param $start_line
	 *
	 * @return array
	 */
	private function captureOutput($shmid, $pipes, $start_time, $start_line ) {
		$stdin  = $pipes[0];
		$stdout = $pipes[1];
		$stderr = $pipes[2];


		stream_set_blocking( $stdout, 0 );
		stream_set_blocking( $stderr, 0 );

		$outEof = false;
		$errEof = false;

		$data        = array();
		$sharedLines = array();
		$count       = $start_line;

		do {
			$read   = [ $stdout, $stderr ]; // [1]
			$write  = null; // [1]
			$except = null; // [1]

			// [1] need to be as variables because only vars can be passed by reference

			stream_select(
				$read,
				$write,
				$except,
				null, // seconds
				0 ); // microseconds

			$outEof = $outEof || feof( $stdout );
			$errEof = $errEof || feof( $stderr );

			if ( ! $outEof ) {
				$content = fgets( $stdout );
				if ( $content ) {
					$sharedLines[] = sMem::appendToMem( $shmid, $content );
					$data[]        = array(
						"time"    => time() - $start_time,
						"content" => $content
					);
					$count ++;
				}
			}

			if ( ! $errEof ) {
				$content = fgets( $stderr );
				if ( $content ) {
					$sharedLines[] = sMem::appendToMem( $shmid, $content );
					$data[]        = array(
						"time"    => time() - $start_time,
						"content" => $content
					);
					$count ++;
				}
			}
		} while ( ! $outEof || ! $errEof );


		sleep( 2 ); // sleep few seconds so ajax calls to reade have time to read all the lines

		return $data;
	}


}