<?php

namespace AutomateWoo;

defined( 'ABSPATH' ) || exit;

/**
 * Registry class for background processes
 *
 * @deprecated in 5.2.0 use ActionScheduler jobs instead.
 * @see Jobs\AbstractActionSchedulerJob;
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
		return apply_filters_deprecated( 'automatewoo/background_processes/includes', [ [] ], '6.0.0', '\AutomateWoo\Jobs\AbstractActionSchedulerJob' );
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
