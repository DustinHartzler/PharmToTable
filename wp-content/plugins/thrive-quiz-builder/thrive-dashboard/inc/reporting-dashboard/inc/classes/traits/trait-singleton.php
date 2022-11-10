<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting\Traits;

trait Singleton {

	protected static $instance;

	/**
	 * @return mixed
	 */
	public static function instance() {
		if ( static::$instance === null ) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
