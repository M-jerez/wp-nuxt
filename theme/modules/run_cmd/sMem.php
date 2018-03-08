<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 08/03/2018
 * Time: 13:14
 */

namespace wpnuxt;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class sMem {

	static $default_mem_item_size = 4096;

	/**
	 * Returns true if the shared memory is exists (Process is running)
	 * returns false otherwise.
	 * @param $shid_target
	 *
	 * @return bool
	 */
	static function isOpenMem( $shmid ) {
		@$shid = shmop_open( $shmid, "a", 0666, 0 );
		return !empty($shid);
	}


	/**
	 * Opens or Create a new shared memory block, (read and write mode)
	 * @param $systemid
	 * @param $text
	 *
	 * @return mixed
	 */
	static function openMem( $shmid, $text ) {
		$shmid = shmop_open( $shmid, 'c', 0755, self::$default_mem_item_size );
		return $shmid;
	}


	static function readMem( $shmid ) {
		$data = shmop_read( $shmid, 0,  shmop_size($shmid));
		if($data){
			return self::str_from_mem($data);
		}else{
			return "";
		}
	}


	/**
	 * Overrides The shared Menory content for the given $shmid
	 *
	 * @param $shmid
	 * @param $text
	 *
	 * @return mixed
	 */
	static function writeMem( $shmid, $text  ) {
		shmop_delete( $shmid );
		$newtext = self::str_to_nts($text);
		$length = shmop_write( $shmid, $newtext, 0 );
		return $length;
	}



	static function appendToMem( $shmid, $text ) {
		$current = self::readMem($shmid);
		$newtext = self::str_to_nts($current . $text);
		$length = shmop_write( $shmid, $newtext, 0 );
		return $length;
	}


	/** Delete And  Close a Shared memory Block
	 * @param $shmid
	 */
	static function closeMem( $shmid ) {
		shmop_delete( $shmid );
		shmop_close( $shmid );
	}


	/**
	 * string to memory
	 * @param $value
	 *
	 * @return string
	 */
	private static function str_to_nts($value) {
		return "$value\0";
	}

	/**
	 * string form memory
	 * @param $value
	 *
	 * @return mixed
	 */
	private static function str_from_mem(&$value) {
		$i = strpos($value, "\0");
		if ($i === false) {
			return $value;
		}
		$result =  substr($value, 0, $i);
		return $result;
	}

}