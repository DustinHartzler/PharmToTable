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
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/10/2015
 * Time: 4:59 PM
 */
class Thrive_Dash_List_Connection_ArpReach extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function get_type() {
		return 'autoresponder';
	}

	/**
	 * @return string the API connection title
	 */
	public function get_title() {
		return 'ArpReach';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function output_setup_form() {
		$this->output_controls_html( 'arpreach' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 */
	public function read_credentials() {
		$url     = ! empty( $_POST['connection']['url'] ) ? sanitize_text_field( $_POST['connection']['url'] ) : '';
		$app_key = ! empty( $_POST['connection']['api_key'] ) ? sanitize_text_field( $_POST['connection']['api_key'] ) : '';

		if ( empty( $url ) || empty( $app_key ) ) {
			return $this->error( __( "Invalid URL or API key", TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$lists = ! empty( $_POST['connection']['lists'] ) ? map_deep( $_POST['connection']['lists'], 'sanitize_text_field' ) : array();

		$lists = array_filter( $lists );

		if ( empty( $lists ) ) {
			return $this->error( __( 'Please provide at least one list for your subscribers', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$credentials = array( 'lists' => $lists, 'api_key' => $app_key, 'url' => $url );

		$this->set_credentials( $credentials );

		if ( $this->test_connection() !== true ) {
			return $this->error( __( "Invalid URL or API key", TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->save();

		return $this->success( __( 'ArpReach connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function test_connection() {
		try {
			/** @var Thrive_Dash_Api_ArpReach $api */
			$api = $this->get_api();

			return strtolower( $api->test_connection()->status ) === 'ok';

		} catch ( Exception $e ) {
			$this->error( $e->getMessage() );

			return false;
		}
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function get_api_instance() {
		return new Thrive_Dash_Api_ArpReach( $this->param( 'url' ), $this->param( 'api_key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _get_lists() {
		try {
			$lists = array();

			foreach ( $this->param( 'lists' ) as $id ) {
				$lists[] = array(
					'id'   => $id,
					'name' => "#" . $id,
				);
			}

			return $lists;

		} catch ( Exception $e ) {

		}

		return null;
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function add_subscriber( $list_identifier, $arguments ) {
		try {

			list( $first_name, $last_name ) = explode( " ", ! empty( $arguments['name'] ) ? $arguments['name'] : ' ' );

			$params = array(
				'email'      => $arguments['email'],
				'phone'      => ! empty( $arguments['phone'] ) ? $arguments['phone'] : '',
				'first_name' => $first_name,
				'last_name'  => $last_name,
				'lists'      => json_encode( array(
					array(
						'list'         => $list_identifier,
						'status'       => 1,
						'next_message' => 2,
					),
				) ),
			);

			/** @var Thrive_Dash_Api_ArpReach $api */
			$api = $this->get_api();

			//add contact
			$api->addContact( $params );
			$api->addToList( $list_identifier, $params );

			return true;
		} catch ( Thrive_Dash_Api_ArpReach_ContactException_Exists $e ) {
			// make sure the contact is updated
			$api->editContact( $params );
			// add contact to the list
			$api->addToList( $list_identifier, $params );

			return true;
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function get_email_merge_tag() {
		return '{EMAIL_ADDRESS}';
	}
}
