<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:38
 */

namespace wpnuxt;

use wpnuxt\utils as utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class rest
 * Enhance rest functionality adding missing endpoints
 * @package wpnuxt
 */
class rest {


	private $config;

	function __construct() {
		$this->config = utils::loadConfig();
		if ( ! $this->config ) {
			return;
		}
		add_action( 'rest_endpoints', array( $this, 'remove_users_endpoint' ) );
		add_action( 'rest_api_init', array( $this, 'add_menus_endpoint' ) );
	}


	/**
	 * Removes the Users from the rest endpoint (this might be a security risk whrn insucure passwords are allowed)
	 *
	 * @param $endpoints
	 *
	 * @return mixed
	 */
	function remove_users_endpoint( $endpoints ) {
		if ( $this->config["rest"]["disable_users"] ) {
			if ( isset( $endpoints['/wp/v2/users'] ) ) {
				unset( $endpoints['/wp/v2/users'] );
			}
//			if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
//				unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
//			}
		}

		return $endpoints;
	}


	/**
	 * Adds the menus endpoints to the rest api
	 */
	function add_menus_endpoint() {

		if ( $this->config["rest"]["menus"] ) {
			register_rest_route(
				WPN_REST_URL,
				'/menus',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'api_list_menus' )
				) );


//			register_rest_route(
//				WPN_REST_URL,
//				'/menus/(?P<slug>[0-9a-zA-Z(-]+)',
//				array(
//					'methods'  => 'GET',
//					'callback' => array( $this, 'api_menu_by_id_slug' )
//				) );

			register_rest_route(
				WPN_REST_URL,
				'/menus/(?P<id>\d+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'api_menu_by_id_slug' )
				) );
		}
	}


	/**
	 * Returns the list of all menus
	 * @return array
	 */
	function api_list_menus() {
		$menus = array();
		foreach ( get_nav_menu_locations() as $menu_slug => $menu_id ) {

			$menu = $menu_id?self::get_menu_object($menu_id):false;
			$menus[ $menu_slug ] = $menu;
		}

		return $menus;
	}

	/**
	 * Returns the menu's data from the menu id
	 *
	 * @param $data
	 *
	 * @return array|null|stdClass|WP_Error|WP_Term
	 */
	function api_menu_by_id_slug( $data ) {

		$id =  $data['id']?intval($data['id']):$data['slug'];
		$menu = self::get_menu_object($id);

		if($menu){
			return $menu;
		}else{
			return new \WP_Error( 'rest_menu_invalid_id', 'Invalid Menu ID or SLUG', array( 'status' => 404 ) );

		}

	}





	static function get_menu_object( $id ) {
		$menu        = new \stdClass;

		$wp_menu = $id ? wp_get_nav_menu_object( $id ) : false;

		if($wp_menu){
			$menu->meta = $wp_menu;
			$items = wp_get_nav_menu_items( $id );
			$menu->items = $items?$items:array();
			$menu->items_tree = $items ?self::buildTree( $items, 0 ):array();

			return $menu;
		}else{
			return false;
		}
	}



	/**
	 * Modification of "Build a tree from a flat array in PHP"
	 *
	 * Authors: @DSkinner, @ImmortalFirefly and @SteveEdson
	 *
	 * @link https://stackoverflow.com/a/28429487/2078474
	 */
	static function buildTree( array &$elements, $parentId = 0 )
	{
		$branch = array();
		foreach ( $elements as &$element )
		{
			if ( $element->menu_item_parent == $parentId )
			{
				$children = self::buildTree( $elements, $element->ID );
				if ( $children )
					$element->child_items = $children;

				$branch[$element->ID] = $element;
				unset( $element );
			}
		}
		return $branch;
	}


}