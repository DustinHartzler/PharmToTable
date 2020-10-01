<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_SendOwl extends Thrive_Dash_List_Connection_Abstract {

	const sendowl_url = 'https://www.sendowl.com';

	/**
	 * Return the connection type
	 * @return String
	 */
	public static function getType() {
		return 'sellings';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'SendOwl';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'sendowl' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid SendOwl key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$url = self::sendowl_url;

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to SendOwl using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'SendOwl connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_SendOwl $so */

		$so = $this->getApi();

		try {
			$so->getProducts();
		} catch ( Thrive_Dash_Api_SendOwl_Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return bool|string true for success or string error message for failure
	 */
	public function addSubscriber( $list_identifier, $arguments ) {}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_SendOwl( array(
				'apiKey'    => $this->param( 'key' ),
				'secretKey' => $this->param( 'secret' ),
			)
		);
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		return array();
	}
}