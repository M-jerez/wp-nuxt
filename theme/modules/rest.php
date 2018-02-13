<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 12:38
 */

namespace wpnuxt;

define( "NUXT_PRESS_REST_NAME", __NAMESPACE__ . '/v1' );

/**
 * Class rest
 * Enhance rest functionality adding missing endpoints
 * @package wpnuxt
 */
class rest {


	private  $c;
	function __construct($config){
		if(!$config){
			utils::admin_error("wp-nuxt-config.php error. \$config['rest'] missing from the config file!");
			return;
		}
		$this->c = $config;
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
		if ( isset( $endpoints['/wp/v2/users'] ) ) {
			unset( $endpoints['/wp/v2/users'] );
		}
		if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
			unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}

		return $endpoints;
	}


	/**
	 * Adds the menus endpoints to the rest api
	 */
	function add_menus_endpoint() {
		register_rest_route(
			NUXT_PRESS_REST_NAME,
			'/menus',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'api_list_menus' )
			) );

		register_rest_route(
			NUXT_PRESS_REST_NAME,
			'/menus/(?P<id>[a-zA-Z(-]+)',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'api_menu_by_id' )
			) );
	}


	/**
	 * Returns the list of all menus
	 * @return array
	 */
	function api_list_menus() {
		$menus = [];
		foreach ( get_registered_nav_menus() as $menu_id => $menu_desc ) {
			$obj                 = new \stdClass;
			$obj->slug           = $menu_id;
			$obj->description    = $menu_desc;
			$menus[ $obj->slug ] = $obj;
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
	function api_menu_by_id( $data ) {
		$menu        = new \stdClass;
		$menu->items = [];
		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $data['id'] ] ) ) {
			$menu        = get_term( $locations[ $data['id'] ] );
			$menu->items = wp_get_nav_menu_items( $menu->term_id );
		}

		return $menu;
	}

}