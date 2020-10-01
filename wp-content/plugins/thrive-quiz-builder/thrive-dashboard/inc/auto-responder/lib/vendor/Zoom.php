<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

require_once dirname( __FILE__ ) . '/TdJwt/TdJwt.php';

class Thrive_Dash_Api_Zoom {

	/**
	 * the query string
	 */
	protected $_version = 'v2/';

	/**
	 * @var $apiKey
	 */
	protected $_api_key;

	/**
	 * @var
	 */
	protected $_secret_key;

	/**
	 * @var array
	 */
	protected $_headers = array();

	/**
	 * @var string
	 */
	protected $_host = 'https://api.zoom.us/';

	/**
	 * 300 is the max nr of users that can be pulled per request allowed by the API
	 *
	 * @var int
	 */
	protected $_user_nr = 300;

	/**
	 * @var int
	 */
	protected $_webinars_nr = 300;

	/**
	 * Users default page number
	 *
	 * @var int
	 */
	protected $_page_number = 1;

	/**
	 * Webinars default page number
	 *
	 * @var int
	 */
	protected $_w_page_number = 1;

	/**
	 * Thrive_Dash_Api_Zoom constructor.
	 *
	 * @param $options
	 */
	public function __construct( $options ) {
		$this->_api_key    = $options['apiKey'];
		$this->_secret_key = $options['secretKey'];
	}

	/**
	 * @param string $user_id
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_Zoom_Exception
	 */
	public function get_webinars( $user_id = '' ) {

		$webinars = array();

		// If there is no $userId sent, get the first user id [the owner of the zoom account] for displaying webinars
		if ( ! $user_id ) {
			$user_id = $this->get_owner_id();
		}

		// Grab all webinars
		do {

			$raw_webinars = $this->send( 'get', "users/{$user_id}/webinars?page_size={$this->_webinars_nr}&page_number={$this->_w_page_number}" );

			if ( ! is_array( $raw_webinars ) || empty( $raw_webinars['webinars'] ) ) {
				break;
			}

			$page_count  = $raw_webinars['page_count'];
			$total_items = $raw_webinars['total_records'];

			foreach ( $raw_webinars['webinars'] as $webinar ) {
				$webinars[] = array(
					'id'         => $webinar['id'],
					'name'       => $webinar['topic'] . ' (' . $webinar['start_time'] . ')',
					'start_time' => $webinar['start_time'],
				);
			}

			if ( $page_count <= $this->_w_page_number ) {
				break;
			}

			$this->_w_page_number ++;

		} while ( count( $webinars ) < $total_items );

		// Order webinars by date descending
		if ( count( $webinars ) > 0 ) {
			usort(
				$webinars,
				function ( $a, $b ) {
					return strtotime( $b['start_time'] ) - strtotime( $a['start_time'] );
				}
			);
		}

		return $webinars;
	}

	/**
	 * Returns the first user id from the Zoom account
	 *
	 * @return string
	 * @throws Thrive_Dash_Api_Zoom_Exception
	 */
	public function get_owner_id() {
		$user_id = '';
		$users   = $this->get_users();

		if ( count( $users ) > 0 ) {
			$user_id = $users[0]['id'];
		}

		return $user_id;
	}

	/**
	 * @return array
	 * @throws Thrive_Dash_Api_Zoom_Exception
	 */
	public function get_users() {
		$users       = array();
		$total_items = 0;

		do {

			$raw_users = $this->send( 'get', 'users?page_size=' . $this->_user_nr . '&page_number=' . $this->_page_number );

			if ( ! is_array( $raw_users ) || empty( $raw_users['users'] ) ) {
				break;
			}

			if ( is_array( $raw_users ) && isset( $raw_users['users'] ) ) {

				$page_count  = $raw_users['page_count'];
				$total_items = $raw_users['total_records'];

				foreach ( $raw_users['users'] as $user ) {

					// Only add PRO users [1 is the basic account type, API restriction for creating webinars]
					if ( ! empty( $user['type'] ) && $user['type'] > 1 ) {
						$users[] = array(
							'id'   => $user['id'],
							'name' => $user['first_name'] . ' ' . $user['last_name'],
						);
					}
				}

				if ( $page_count <= $this->_page_number ) {
					break;
				}

				$this->_page_number ++;
			}
		} while ( count( $users ) < $total_items );

		return $users;
	}

	/**
	 * @param       $method
	 * @param       $endpoint
	 * @param array $args
	 *
	 * @return array|mixed|object
	 * @throws Thrive_Dash_Api_Zoom_Exception
	 */
	public function send( $method, $endpoint, $args = array() ) {
		// The JWT token expires after 1 minute so it needs to be regenerated
		$this->_set_headers();

		switch ( strtoupper( $method ) ) {
			case 'GET':
				$fn = 'tve_dash_api_remote_get';
				break;
			default:
				$fn = 'tve_dash_api_remote_post';
				break;
		}

		$arguments = array(
			'body'      => isset( $args['body'] ) ? json_encode( $args['body'] ) : null,
			'timeout'   => 15,
			'headers'   => $this->_headers,
			'sslverify' => false,
		);

		$endpoint_url = $this->endpoint_url( $endpoint );

		$response = $fn( $endpoint_url, $arguments );

		return $this->_handle_response( $response );
	}

	/**
	 * Set headers
	 */
	private function _set_headers() {
		$this->_headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $this->generate_jwt(),
		);
	}

	/**
	 * Creates the JWT token from API key and secret
	 *
	 * @return string
	 */
	public function generate_jwt() {
		//Zoom API credentials from https://developer.zoom.us/me/
		$key    = $this->_api_key;
		$secret = $this->_secret_key;
		$token  = array(
			'iss' => $key,
			// The benefit of JWT is expiry tokens, we'll set this one to expire in 1 minute
			'exp' => time() + 60,
		);

		return TdJwt::encode( $token, $secret );
	}

	/**
	 * Build enpoint URL
	 *
	 * @param $endpoint
	 *
	 * @return string
	 */
	public function endpoint_url( $endpoint ) {
		return $this->_host . $this->_version . $endpoint;
	}

	/**
	 * @param $response
	 *
	 * @return array|mixed|object
	 * @throws Thrive_Dash_Api_Zoom_Exception
	 */
	protected function _handle_response( $response ) {
		if ( $response instanceof WP_Error ) {
			throw new Thrive_Dash_Api_Zoom_Exception( __( 'Failed connecting: ', 'thrive' ) . $response->get_error_message() );
		}

		if ( isset( $response['response']['code'] ) ) {
			switch ( $response['response']['code'] ) {
				case 200:
					return json_decode( $response['body'], true );
					break;
				case 400:

					// Add API response message due to webinar restritions from zoom UI that are not matching
					$resp_message = ! empty( $response['body'] ) && tve_is_json_encoded( $response['body'] ) ? json_decode( $response['body'], true ) : array();
					$message      = ! empty( $resp_message['message'] ) ? tve_sanitize_data_recursive( $resp_message['message'] ) : 'Missing a required parameter or calling invalid method';

					throw new Thrive_Dash_Api_Zoom_Exception( $message );
					break;
				case 401:
					throw new Thrive_Dash_Api_Zoom_Exception( 'Invalid API key provided!' );
					break;
				case 404:
					throw new Thrive_Dash_Api_Zoom_Exception( 'Can\'t find requested items' );
					break;
			}
		}

		return json_decode( $response['body'], true );
	}

	/**
	 * @param       $webinar_id
	 * @param array $args
	 *
	 * @return array|bool|mixed|object
	 * @throws Thrive_Dash_Api_Zoom_Exception
	 */
	public function register_to_webinar( $webinar_id, $args = array() ) {
		$arguments['body'] = $args;
		if ( ! $webinar_id ) {
			return false;
		}

		return $this->send( 'post', "webinars/{$webinar_id}/registrants", $arguments );
	}
}
