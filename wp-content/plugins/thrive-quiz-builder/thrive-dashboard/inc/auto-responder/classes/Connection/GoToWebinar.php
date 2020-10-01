<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 06.05.2015
 * Time: 17:29
 */
class Thrive_Dash_List_Connection_GoToWebinar extends Thrive_Dash_List_Connection_Abstract {

	/**
	 * @var string
	 */
	private $_consumer_key = 'Mtm8i2IdR2mOkAY3uVoW5f4TdGaBxpkY';

	/**
	 * @var string
	 */
	private $_consumer_secret = 'qjr8KW9Ga6G2AJjE';

	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'webinar';
	}

	/**
	 * check if the expires_at field is in the past
	 * GoToWebinar auth access tokens expire after about one year
	 *
	 * @return bool
	 */
	public function isExpired() {
		if ( ! $this->isConnected() ) {
			return false;
		}

		$expires_at = $this->param( 'expires_at' );

		return time() > $expires_at;
	}

	/**
	 * get the expiry date and time user-friendly formatted
	 */
	public function getExpiryDate() {
		return date( 'l, F j, Y H:i:s', $this->param( 'expires_at' ) );
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'GoToWebinar';
	}

	/**
	 * these are called webinars, not lists
	 *
	 * @return string
	 */
	public function getListSubtitle() {
		return __( 'Choose from the following upcoming webinars', TVE_DASH_TRANSLATE_DOMAIN );
	}


	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'gotowebinar' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$email    = $_POST['gtw_email'];
		$password = $_POST['gtw_password'];

		if ( empty( $email ) || empty( $password ) ) {
			return $this->error( __( 'Email and password are required', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$v = array(
			'version'    => ! empty( $_POST['connection']['version'] ) ? $_POST['connection']['version'] : '',
			'versioning' => ! empty( $_POST['connection']['versioning'] ) ? $_POST['connection']['versioning'] : '',
		);

		/** @var Thrive_Dash_Api_GoToWebinar $api */
		$api = $this->getApi();

		try {
			$api->directLogin( $email, $password, $v );

			$credentials = $api->getCredentials();

			// Add inbox notification for v2 connection
			if ( TD_Inbox::instance()->api_is_connected( $this->getKey() ) && ! empty( $credentials['version'] ) && 2 === (int) $credentials['version'] && ! empty( $credentials['versioning'] ) ) {

				$this->add_notification( 'added_v2' );

				// Remove notification from api connection
				TVE_Dash_InboxManager::instance()->remove_api_connection( $this->getKey() );
			}

			$this->setCredentials( $credentials );

			/**
			 * finally, save the connection details
			 */
			$this->save();

			return $this->success( 'GoToWebinar connected successfully' );

		} catch ( Thrive_Dash_Api_GoToWebinar_Exception $e ) {
			return $this->error( sprintf( __( 'Could not connect to GoToWebinar using the provided data (%s)', TVE_DASH_TRANSLATE_DOMAIN ), $e->getMessage() ) );
		}
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	public function add_notification( $type = '' ) {

		if ( empty( $type ) ) {
			return false;
		}

		$message       = array();
		$inbox_manager = TVE_Dash_InboxManager::instance();

		switch ( $type ) {
			case 'added_v2':
				$message = array(
					'title' => __( 'Your GoToWebinar Connection has been Updated!', TVE_DASH_TRANSLATE_DOMAIN ),
					'info'  => 'Good job - you\'ve just upgraded your GoToWebinar connection to 2.0.<br /><br />
							You don\'t need to make any changes to your existing forms - they will carry on working as before. <br /><br /> 
							However, we highly recommend that you sign up through one of your webinar forms to make sure that everything is working as expected.<br /><br />
							If you experience any issues, let our <a href="https://thrivethemes.com/forums/forum/general-discussion/" target="_blank">support team</a> know and we\'ll get to the bottom of this for you. <br /><br />
							From your team at Thrive Themes ',
					'type'  => TD_Inbox_Message::TYPE_INBOX,
				);

				break;
		}

		if ( empty( $message ) ) {
			return false;
		}

		try {
			$message_obj = new TD_Inbox_Message( $message );
			$inbox_manager->prepend( $message_obj );
			$inbox_manager->push_notifications();
		} catch ( Exception $e ) {
		}
	}

	/**
	 * @return mixed|string
	 */
	public function getUsername() {
		$credentials = (array) $this->getCredentials();
		if ( ! empty( $credentials['username'] ) ) {
			return $credentials['username'];
		}

		return '';
	}

	/**
	 * @return mixed|string
	 */
	public function getPassword() {
		$credentials = (array) $this->getCredentials();
		if ( ! empty( $credentials['password'] ) ) {
			return $credentials['password'];
		}

		return '';
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		return true;
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		/** @var Thrive_Dash_Api_GoToWebinar $api */
		$api   = $this->getApi();
		$phone = isset( $arguments['phone'] ) ? $arguments['phone'] : null;

		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		if ( empty( $last_name ) ) {
			$last_name = $first_name;
		}

		if ( empty( $first_name ) && empty( $last_name ) ) {
			list( $first_name, $last_name ) = $this->_getNameFromEmail( $arguments['email'] );
		}

		try {
			$api->registerToWebinar( $list_identifier, $first_name, $last_name, $arguments['email'], $phone );

			return true;
		} catch ( Thrive_Dash_Api_GoToWebinar_Exception $e ) {
			return $e->getMessage();
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 *
	 * @return int the number of days in which this token will expire
	 */
	public function expiresIn() {
		$expires_at = $this->param( 'expires_at' );
		$diff       = (int) ( ( $expires_at - time() ) / ( 3600 * 24 ) );

		return $diff;
	}

	/**
	 * check if the connection is about to expire in less than 30 days or it's already expired
	 */
	public function getWarnings() {
		if ( ! $this->isConnected() ) {
			return array();
		}

		$fix = '<a href="' . admin_url( 'admin.php?page=tve_dash_api_connect' ) . '#edit/' . $this->getKey() . '">' . __( 'Click here to renew the token', TVE_DASH_TRANSLATE_DOMAIN ) . '</a>';

		if ( $this->isExpired() ) {

			return array(
				sprintf( __( 'Thrive API Connections: The access token for %s has expired on %s.', TVE_DASH_TRANSLATE_DOMAIN ), '<strong>' . $this->getTitle() . '</strong>', '<strong>' .
				                                                                                                                                                              $this->getExpiryDate() . '</strong>' ) . ' ' . $fix . '.',
			);
		}

		$diff = $this->expiresIn();

		if ( $diff > 30 ) {
			return array();
		}

		$message = $diff == 0
			?
			__( 'Thrive API Connections: The access token for %s will expire today.', TVE_DASH_TRANSLATE_DOMAIN )
			:
			( $diff == 1
				?
				__( 'Thrive API Connections: The access token for %s will expire tomorrow.', TVE_DASH_TRANSLATE_DOMAIN )
				:
				__( 'Thrive API Connections: The access token for %s will expire in %s days.', TVE_DASH_TRANSLATE_DOMAIN ) );

		return array(
			sprintf( $message, '<strong>' . $this->getTitle() . '</strong>', '<strong>' . $diff . '</strong>' ) . ' ' . $fix . '.',
		);
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed|Thrive_Dash_Api_GoToWebinar
	 * @throws Thrive_Dash_Api_GoToWebinar_Exception
	 */
	protected function _apiInstance() {

		$access_token = $organizer_key = null;
		$settings     = array();

		if ( $this->isConnected() && ! $this->isExpired() ) {
			$access_token  = $this->param( 'access_token' );
			$organizer_key = $this->param( 'organizer_key' );

			$settings = array(
				'version'       => $this->param( 'version' ),
				'versioning'    => $this->param( 'versioning' ), // used on class instances from [/v1/, /v2/ etc] namespace folder
				'expires_in'    => $this->param( 'expires_in' ),
				'auth_type'     => $this->param( 'auth_type' ),
				'refresh_token' => $this->param( 'refresh_token' ),
				'username'      => $this->param( 'username' ),
				'password'      => $this->param( 'password' ),
			);
		}
		$settings['auth_key'] = base64_encode( $this->_consumer_key . ':' . $this->_consumer_secret );

		return new Thrive_Dash_Api_GoToWebinar( $this->_consumer_key, $access_token, $organizer_key, $settings );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_GoToWebinar $api */
		$api   = $this->getApi();
		$lists = array();

		try {
			$all = $api->getUpcomingWebinars();

			foreach ( $all as $item ) {

				preg_match( '#register/(\d+)$#', $item['registrationUrl'], $m );

				$id_from_registration_url = isset( $m[1] ) ? $m[1] : '';

				$lists [] = array(
					'id'   => ! empty( $item['webinarKey'] ) ? $item['webinarKey'] : $id_from_registration_url,
					'name' => $item['subject'] . ' (' . date( 'Y-m-d H:i:s', strtotime( $item['times'][0]['startTime'] ) ) . ')',
				);
			}

			return $lists;
		} catch ( Thrive_Dash_Api_GoToWebinar_Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}

	}

}
