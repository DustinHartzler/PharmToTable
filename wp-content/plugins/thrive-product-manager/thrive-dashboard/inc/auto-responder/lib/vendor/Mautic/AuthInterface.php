<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */
interface Thrive_Dash_Api_Mautic_AuthInterface {
	/**
	 * Make a request to server using the supported auth method
	 *
	 * @param string $url
	 * @param array $parameters
	 * @param string $method
	 * @param array $settings
	 *
	 * @return array
	 */
	public function makeRequest( $url, array $parameters = array(), $method = 'GET', array $settings = array() );

	/**
	 * Check if current authorization is still valid
	 *
	 * @return bool
	 */
	public function isAuthorized();
}
