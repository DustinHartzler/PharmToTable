<?php

namespace AutomateWoo;

defined( 'ABSPATH' ) || exit;

/**
 * Registry class for background processes
 */
class Background_Processes extends Registry {

	/**
	 * Static cache of includes.
	 *
	 * @var array
	 */
	public static $includes;

	/**
	 * Static cache of loaded objects.
	 *
	 * @var array
	 */
	public static $loaded = [];

	/**
	 * Load includes.
	 *
	 * @return array
	 */
	public static function load_includes() {
		$includes = [
			'events'                     => Background_Processes\Event_Runner::class,
			'setup_registered_customers' => Background_Processes\Setup_Registered_Customers::class,
			'setup_guest_customers'      => Background_Processes\Setup_Guest_Customers::class,
			'wishlist_item_on_sale'      => Background_Processes\Wishlist_Item_On_Sale::class,
			'workflows'                  => Background_Processes\Workflows::class,
			'tools'                      => Background_Processes\Tools::class,
		];

		return apply_filters( 'automatewoo/background_processes/includes', $includes );
	}

	/**
	 * Get all background processes.
	 *
	 * @return Background_Processes\Base[]
	 */
	public static function get_all() {
		return parent::get_all();
	}

	/**
	 * Get a background.
	 *
	 * @param string $name
	 *
	 * @return Background_Processes\Base|false
	 */
	public static function get( $name ) {
		return parent::get( $name );
	}

}
