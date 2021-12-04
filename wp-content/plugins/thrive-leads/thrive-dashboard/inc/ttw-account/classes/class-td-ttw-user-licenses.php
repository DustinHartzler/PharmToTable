<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Class TD_TTW_User_Licenses
 *
 * @property string status
 */
class TD_TTW_User_Licenses {

	use TD_Magic_Methods;

	use TD_Singleton;

	use TD_TTW_Utils;

	const NAME = 'td_ttw_licenses_details';

	const RECHECK_KEY = 'td_recheck_license';

	const TTB_TAG = 'ttb';

	const CACHE_LIFE_TIME = 28800; //8 hours

	private $_licenses_instances = array();

	private function __construct() {

		$this->_data = get_transient( self::NAME );

		$this->_init_licenses_instances();

		if ( ! empty( $_REQUEST[ self::RECHECK_KEY ] ) ) {
			$this->recheck_license();
		}
	}

	private function _init_licenses_instances() {

		foreach ( (array) $this->_data as $item ) {
			$tag = ! empty( $item['tag'] ) ? $item['tag'] : '';

			if ( is_array( $tag ) ) {
				$tag = in_array( self::TTB_TAG, $tag )
					? self::TTB_TAG
					: TD_TTW_License::MEMBERSHIP_TAG;
			}

			if ( ! empty( $tag ) && empty( $this->_licenses_instances[ $tag ] ) ) {
				$this->_licenses_instances[ $tag ] = new TD_TTW_License( $item );
			}
		}
	}

	/**
	 * Check if there is an active membership
	 *
	 * @return bool
	 */
	public function has_active_membership() {

		return $this->has_membership() && $this->is_membership_active();
	}

	/**
	 * Check if the membership license is active
	 *
	 * @return bool
	 */
	public function is_membership_active() {

		return $this->get_license( TD_TTW_License::MEMBERSHIP_TAG )->is_active();
	}

	/**
	 * Check if the membership license is invalid
	 *
	 * @return bool
	 */
	public function is_membership_invalid() {

		return $this->get_license( TD_TTW_License::MEMBERSHIP_TAG )->is_invalid();
	}

	/**
	 * Get available licenses
	 *
	 * @return array
	 */
	public function get() {

		return $this->_licenses_instances;
	}

	/**
	 * Check if a license exists by tag
	 *
	 * @param $tag
	 *
	 * @return bool
	 */
	public function has_license( $tag ) {

		return isset( $this->_licenses_instances[ $tag ] );
	}

	/**
	 * Check if there is any membership license
	 *
	 * @return bool
	 */
	public function has_membership() {

		return $this->has_license( TD_TTW_License::MEMBERSHIP_TAG );
	}

	/**
	 * Get membership type license
	 *
	 * @return TD_TTW_License
	 */
	public function get_membership() {

		return $this->get_license( TD_TTW_License::MEMBERSHIP_TAG );
	}

	/**
	 * Get license instance based on a tag
	 *
	 * @param string $tag
	 *
	 * @return TD_TTW_License
	 */
	public function get_license( $tag ) {

		if ( isset( $this->_licenses_instances[ $tag ] ) ) {
			return $this->_licenses_instances[ $tag ];
		}

		return new TD_TTW_License( [] );
	}

	/**
	 * Get license details
	 *
	 * @return array
	 */
	public function get_licenses_details() {

		if ( ! TD_TTW_Connection::get_instance()->is_connected() ) {
			return array();
		}

		$licenses_details = $this->_get_connection_licenses( TD_TTW_Connection::get_instance() );

		$this->_data = $licenses_details;

		$this->_init_licenses_instances();

		return $licenses_details;
	}

	/**
	 * Recheck license details
	 */
	public function recheck_license() {

		delete_transient( self::NAME );
		remove_query_arg( self::RECHECK_KEY );

		$this->get_licenses_details();

		if ( $this->has_membership() && $this->is_membership_active() ) {
			add_action( 'admin_notices', array( $this, 'success_notice' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'fail_notice' ) );
		}
	}

	public function success_notice() {

		TD_TTW_Messages_Manager::render( 'success-notice' );
	}

	public function fail_notice() {

		TD_TTW_Messages_Manager::render( 'expired-notice' );
	}

	/**
	 * Get recheck license url
	 *
	 * @return string
	 */
	public function get_recheck_url() {

		if ( isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) === TD_TTW_Update_Manager::NAME ) {
			$url = ! empty( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '';
		} else {
			$url = admin_url( 'plugins.php' );
		}

		return add_query_arg( array(
			TD_TTW_User_Licenses::RECHECK_KEY => 1,
		), $url );
	}

	/**
	 * Render licenses screen
	 *
	 * @param false $return
	 *
	 * @return false|string
	 */
	public function render( $return = false ) {

		ob_start();
		include $this->path( 'templates/header.phtml' );
		include $this->path( 'templates/licences/list.phtml' );
		include $this->path( 'templates/debugger.phtml' );
		$html = ob_get_clean();

		if ( $return === true ) {
			return $html;
		}

		echo $html; //phpcs:ignore
	}

	/**
	 * Based on current connection a request is made to TTW for assigned licenses
	 *
	 * @param TD_TTW_Connection $connection
	 *
	 * @return array
	 */
	protected function _get_connection_licenses( TD_TTW_Connection $connection ) {

		if ( ! $connection->is_connected() ) {
			return array();
		}

		$licenses = get_transient( self::NAME );

		if ( $licenses !== false ) {

			return $licenses;
		}

		$params = array(
			'user_id'       => $connection->ttw_id,
			'user_site_url' => get_site_url(),
		);

		$route   = '/api/v1/public/get_licenses_details';
		$request = new TD_TTW_Request( $route, $params );
		$request->set_header( 'Authorization', $connection->ttw_salt );

		$proxy_request = new TD_TTW_Proxy_Request( $request );
		$response      = $proxy_request->execute( '/tpm/proxy' );

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		if ( ! is_array( $body ) || ! isset( $body['success'] ) || $body['success'] === false ) {

			set_transient( self::NAME, array(), self::CACHE_LIFE_TIME );

			return array();
		}

		$licenses_details = $body['data'];

		set_transient( self::NAME, $licenses_details, self::CACHE_LIFE_TIME );

		return $licenses_details;
	}

	/**
	 * Check if there is any TTB license that allows updates - memberships are not included here
	 *
	 * @return bool
	 */
	public function can_update_ttb() {

		return $this->get_license( self::TTB_TAG )->can_update();
	}
}
