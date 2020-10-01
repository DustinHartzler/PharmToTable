<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

class Thrive_Dash_List_Connection_EverWebinar extends Thrive_Dash_List_Connection_Abstract {

	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'webinar';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'EverWebinar';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'everwebinar' );
	}

	/**
	 * @return mixed|Thrive_Dash_List_Connection_Abstract
	 *
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid EverWebinar key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to EverWebinar using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'EverWebinar connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string
	 */
	public function testConnection() {
		try {
			$webinars = $this->getApi()->get_webinars();
			if ( ! $webinars ) {
				return false;
			}

			return true;
		} catch ( Thrive_Dash_Api_EverWebinar_Exception $e ) {
			return false;
		}
	}

	/**
	 * Compose name from email address
	 *
	 * @param        $email_address
	 * @param string $split
	 *
	 * @return string
	 */
	public function nameFromEmail( $email_address, $split = '@' ) {
		return ucwords( str_replace( array(
			'_',
			'.',
			'-',
			'+',
			',',
			':',
		), ' ', strtolower( substr( $email_address, 0, strripos( $email_address, $split ) ) ) ) );
	}

	/**
	 * add contact to list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return bool|mixed|Thrive_Dash_List_Connection_Abstract
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		$args = array( 'schedule' => 0 );
		if ( is_array( $arguments ) ) {

			if ( isset( $arguments['everwebinar_schedule'] ) ) {
				$args['schedule'] = $arguments['everwebinar_schedule'];
			}

			if ( isset( $arguments['email'] ) && ! empty( $arguments['email'] ) ) {
				$args['email'] = $arguments['email'];
			}

			if ( isset( $arguments['name'] ) && ! empty( $arguments['name'] ) ) {
				list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

				$args['first_name'] = $first_name;
				$args['last_name']  = $last_name;
			}

			if ( ! empty( $args['email'] ) && empty( $arguments['name'] ) ) {
				// First name is a required param, so we are building it for register forms with only email input
				$args['first_name'] = $this->nameFromEmail( $args['email'] );
			}

			if ( isset( $arguments['phone'] ) && ! empty( $arguments['phone'] ) ) {
				$args['phone'] = $arguments['phone'];
			}
		}

		try {

			$api    = $this->getApi();
			$webnar = $api->get_webinar_schedules( array( 'webinar_id' => $list_identifier ) );

			if ( isset( $webnar['schedules'] ) ) {
				$schedules = array_values( $webnar['schedules'] );

				$args['schedule'] = $schedules[0]['schedule_id'];
			}

			$api->register_to_webinar( $list_identifier, $args );
		} catch ( Exception $e ) {
			return $this->error( $e->getMessage() );
		}

		return true;
	}

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_extra_settings( $params = array() ) {
		$webinar_id = '';
		try {
			// Used on webinar select/change ajax [in admin Lead generation]
			if ( isset( $params['webinar_id'] ) ) {
				$webinar_id = $params['webinar_id'];
			} else {
				$webinars = $this->getApi()->get_webinars();
				if ( is_array( $webinars ) && isset( $webinars[0]['id'] ) ) {
					$webinar_id = $webinars[0]['id'];
				}
			}

			$params = $this->getApi()->get_webinar_schedules( array( 'webinar_id' => $webinar_id ) );
		} catch ( Thrive_Dash_Api_EverWebinar_Exception $e ) {
		}

		return $params;
	}

	/**
	 * @return mixed|Thrive_Dash_Api_EverWebinar
	 * @throws Thrive_Dash_Api_EverWebinar_Exception
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_EverWebinar( array(
				'apiKey' => $this->param( 'key' ),
			)
		);
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_EverWebinar $ever_webinar */
		$ever_webinar = $this->getApi();

		try {
			$list = $ever_webinar->get_webinars();

			return $list;
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}
	}
}
