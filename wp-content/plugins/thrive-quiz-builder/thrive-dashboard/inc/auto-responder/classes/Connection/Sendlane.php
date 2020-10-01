<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/21/2018
 * Time: 13:37
 */

class Thrive_Dash_List_Connection_Sendlane extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'SendLane';
	}

	/**
	 * @return bool
	 */
	public function hasTags() {

		return true;
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'sendlane' );
	}

	/**
	 * @return mixed|Thrive_Dash_List_Connection_Abstract
	 */
	public function readCredentials() {

		$connection = $_POST['connection'];

		if ( empty( $connection['api_url'] ) || empty( $connection['api_key'] ) || empty( $connection['hash_key'] ) ) {
			return $this->error( __( 'All fields are required!', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $connection );
		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( __( 'Could not connect to SendLane using the provided details', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'SendLane connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * @return bool
	 */
	public function testConnection() {

		return is_array( $this->_getLists() );
	}

	/**
	 * @return mixed|Thrive_Dash_Api_Sendlane
	 * @throws Thrive_Dash_Api_Sendlane_Exception
	 */
	protected function _apiInstance() {
		$api_url  = $this->param( 'api_url' );
		$api_key  = $this->param( 'api_key' );
		$hash_key = $this->param( 'hash_key' );

		return new Thrive_Dash_Api_Sendlane( $api_key, $hash_key, $api_url );
	}

	/**
	 * @return array|bool
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_Sendlane $api */
		$api    = $this->getApi();
		$result = $api->call( 'lists' );

		$api->setConnectionStatus( $result['status'] );

		/**
		 * Invalid connection
		 */
		if ( ! isset( $result['data'] ) || ! is_array( $result['data'] ) ) {
			return false;
		}

		/**
		 * Valid connection but no lists found
		 */
		if ( isset( $result['data']['info'] ) ) {
			return array();
		}

		/**
		 * Add id and name fields for each list
		 */
		foreach ( $result['data'] as $key => $list ) {
			$result['data'][ $key ]['id']   = $list['list_id'];
			$result['data'][ $key ]['name'] = $list['list_name'];
		}

		return $result['data'];
	}

	/**
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed|string
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		/** @var Thrive_Dash_Api_Sendlane $api */
		$api  = $this->getApi();
		$args = array(
			'list_id'    => $list_identifier,
			'email'      => $arguments['email'],
			'first_name' => $first_name,
			'last_name'  => $last_name,
		);

		if ( isset( $arguments['sendlane_tags'] ) ) {
			$args['tag_names'] = trim( $arguments['sendlane_tags'] );
		}

		if ( isset( $arguments['phone'] ) ) {
			$args['phone'] = $arguments['phone'];
		}

		return $api->call( 'list-subscriber-add', $args );
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return 'VAR_EMAIL';
	}
}