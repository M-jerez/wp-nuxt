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


	function __construct( $shmid, $CMD, $CWD ) {
		if(sMem::isOpenMem($shmid)){
			// error: mode runner and already executing
			utils::json_response( "fail", "command already running." );
			return;
		}


		set_time_limit( 0 );
		$data = $this->runCommand( $shmid, $CMD, $CWD );
		utils::json_response("success","command running",$data);
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
	function runCommand( $shmid, $CMD, $CWD ) {
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
		if ( is_resource( $process ) ) {
			$real_shmid = sMem::openMem( 0, self::$first_output, $shmid );
			$output  = $this->captureOutput($shmid, $pipes, $start_time, 1 );
			sMem::closeMem( $real_shmid );
		}
		fclose( $pipes[0] );
		fclose( $pipes[1] );
		fclose( $pipes[2] );

		$data["output"] = $output;
		$data["exit_status"] = proc_close( $process );

		return $data;
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