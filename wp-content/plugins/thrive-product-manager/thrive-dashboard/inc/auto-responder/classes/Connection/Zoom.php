<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class Thrive_Dash_List_Connection_Zoom extends Thrive_Dash_List_Connection_Abstract {

	const zoom_url = 'https://api.zoom.us/v2/';

	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function get_type() {
		return 'webinar';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return 'Zoom';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function output_setup_form() {
		$this->output_controls_html( 'zoom' );
	}

	/**
	 * @return mixed|Thrive_Dash_List_Connection_Abstract
	 */
	public function read_credentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? sanitize_text_field( $_POST['connection']['key'] ) : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid Zoom key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$url = self::zoom_url;

		$this->set_credentials( $this->post( 'connection' ) );

		$result = $this->test_connection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Zoom using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'Zoom connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function test_connection() {

		/** @var Thrive_Dash_Api_Zoom $api */
		$api = $this->get_api();

		try {
			$api->get_users();

			return true;
		} catch ( Thrive_Dash_Api_Zoom_Exception $e ) {
			return false;
		}
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return bool|string true for success or string error message for failure
	 */
	public function add_subscriber( $list_identifier, $arguments ) {
		$args = array();
		if ( isset( $arguments['email'] ) ) {
			$args['email'] = $arguments['email'];
		}

		if ( isset( $arguments['name'] ) ) {
			list( $first_name, $last_name ) = $this->get_name_parts( $arguments['name'] );

			$args['first_name'] = $first_name;
			$args['last_name']  = $last_name;
		}

		if ( isset( $arguments['phone'] ) ) {
			$args['phone'] = $arguments['phone'];
		}

		try {
			$this->get_api()->register_to_webinar( ! empty( $arguments['zoom_webinar'] ) ? $arguments['zoom_webinar'] : $list_identifier, $args );
		} catch ( Exception $e ) {
			return $this->error( $e->getMessage() );
		}

		return true;
	}

	/**
	 * @param array $params
	 *
	 * @return array|bool
	 */
	public function get_extra_settings( $params = array() ) {

		// Used on user select/change ajax [in admin Lead generation]
		if ( isset( $params['user_id'] ) ) {
			try {
				return $this->get_api()->get_webinars( $params['user_id'] );
			} catch ( Thrive_Dash_Api_Zoom_Exception $e ) {
				return array();
			}
		}

		try {
			$params['users'] = $this->get_api()->get_users();
		} catch ( Thrive_Dash_Api_Zoom_Exception $e ) {
		}

		return $params;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed|Thrive_Dash_Api_Zoom
	 */
	protected function get_api_instance() {
		return new Thrive_Dash_Api_Zoom( array(
				'apiKey'    => $this->param( 'key' ),
				'secretKey' => $this->param( 'secret' ),
			)
		);
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool
	 */
	protected function _get_lists() {
		/** @var Thrive_Dash_Api_Zoom $zoom */
		$zoom = $this->get_api();

		try {
			$list = $zoom->get_webinars();

			return $list;
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}

	}
}
