<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 9/15/2015
 * Time: 12:45 PM
 */
class Thrive_Dash_List_Connection_Drip extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{{ subscriber.email }}';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'Drip';
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
		$this->_directFormHtml( 'drip' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$token     = ! empty( $_POST['connection']['token'] ) ? $_POST['connection']['token'] : '';
		$client_id = ! empty( $_POST['connection']['client_id'] ) ? $_POST['connection']['client_id'] : '';

		if ( empty( $token ) || empty( $client_id ) ) {
			return $this->error( __( 'You must provide a valid Drip token and Client ID', 'thrive-dash' ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Drip using the provided Token and Client ID (<strong>%s</strong>)', 'thrive-dash' ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'Drip connected successfully', 'thrive-dash' ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		try {

			/** @var Thrive_Dash_Api_Drip $api */
			$api = $this->getApi();

			$accounts = $api->get_accounts();

			if ( empty( $accounts ) || ! is_array( $accounts ) ) {
				return __( 'Drip connection could not be validated!', 'thrive-dash' );
			}

			foreach ( $accounts['accounts'] as $account ) {
				if ( $account['id'] === $this->param( 'client_id' ) ) {
					return true;
				}
			}

			return false;

		} catch ( Exception $e ) {
			return $e->getMessage();
		}
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
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		$phone = ! empty( $arguments['phone'] ) ? $arguments['phone'] : '';

		$arguments['drip_optin'] = ! isset( $arguments['drip_optin'] ) ? 's' : $arguments['drip_optin'];
		$double_optin            = ! ( isset( $arguments['drip_optin'] ) && 's' === $arguments['drip_optin'] );

		$field_first_name = isset( $arguments['drip_first_name_field'] ) ? $arguments['drip_first_name_field'] : 'thrive_first_name';
		$field_last_name  = isset( $arguments['drip_last_name_field'] ) ? $arguments['drip_last_name_field'] : 'thrive_last_name';

		$url = wp_get_referer();

		try {
			/** @var Thrive_Dash_Api_Drip $api */
			$api         = $this->getApi();
			$proprieties = new stdClass();

			if ( isset( $first_name ) ) {
				$proprieties->{$field_first_name} = $first_name;
			}

			if ( isset( $last_name ) ) {
				$proprieties->{$field_last_name} = $last_name;
			}

			if ( isset( $phone ) ) {
				$proprieties->thrive_phone = $phone;
			}

			$tags = ! empty( $arguments['drip_tags'] ) ? explode( ',', $arguments['drip_tags'] ) : array();

			if ( isset( $arguments['drip_type'] ) && 'automation' === $arguments['drip_type'] ) {
				$proprieties->thrive_referer    = $url;
				$proprieties->thrive_ip_address = $_SERVER['REMOTE_ADDR'];

				if ( ! empty( $arguments['drip_field'] ) ) {
					foreach ( $arguments['drip_field'] as $field => $field_value ) {
						$proprieties->{$field} = $field_value;
					}
				}
			}

			if ( ! empty( $arguments['tve_mapping'] ) ) {
				$fields      = $this->prepare_api_custom_fields( $arguments );
				$proprieties = (object) array_merge( (array) $proprieties, $fields );
			}

			if ( ! empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					$api->apply_tag( $arguments['email'], $tag, $this->param( 'client_id' ) );
				}
			}

			$user = array(
				'account_id'    => $this->param( 'client_id' ),
				'campaign_id'   => $list_identifier,
				'email'         => $arguments['email'],
				'ip_address'    => $_SERVER['REMOTE_ADDR'],
				'custom_fields' => $proprieties,
			);

			if ( isset( $arguments['drip_type'] ) && 'list' === $arguments['drip_type'] ) {
				$user['double_optin'] = $double_optin;
			}

			$lead = $api->create_or_update_subscriber( $user );
			if ( empty( $user ) ) {
				return __( 'User could not be subscribed', 'thrive-dash' );
			}

			if ( ! isset( $arguments['drip_field'] ) || 'list' === $arguments['drip_type'] ) {

				$client = array_shift( $lead['subscribers'] );

				$api->subscribe_subscriber(
					array(
						'account_id'   => $this->param( 'client_id' ),
						'campaign_id'  => $list_identifier,
						'email'        => $client['email'],
						'double_optin' => $double_optin,
						'tags'         => $tags,
					)
				);
			}

			$api->record_event(
				array(
					'account_id' => $this->param( 'client_id' ),
					'action'     => 'Submitted a Thrive Leads form',
					'email'      => $arguments['email'],
					'properties' => $proprieties,
				)
			);

			return true;

		} catch ( Thrive_Dash_Api_Drip_Exception_Unsubscribed $e ) {

			//todo: rewrite this try
			try {

				$api->delete_subscriber( $user );

				return $this->addSubscriber( $list_identifier, $arguments );

			} catch ( Exception $e ) {

				return $e->getMessage();
			}
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Allow the user to choose whether to have a single or a double optin for the form being edited
	 * It will hold the latest selected value in a cookie so that the user is presented by default with the same option selected the next time he edits such a form
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function get_extra_settings( $params = array() ) {
		$processed_params = array();

		$params['optin'] = empty( $params['optin'] ) ? ( isset( $_COOKIE['tve_api_drip_optin'] ) ? $_COOKIE['tve_api_drip_optin'] : 'd' ) : $params['optin'];
		setcookie( 'tve_api_drip_optin', $params['optin'], strtotime( '+6 months' ), '/' );

		if ( ! empty( $params ) ) {
			foreach ( $params as $k => $v ) {
				if ( strpos( $k, 'field[' ) !== false ) {
					$key                                     = str_replace( 'field[', '', $k );
					$processed_params['proprieties'][ $key ] = $v;
				} else {
					$processed_params[ $k ] = $v;
				}
			}
		}

		return $processed_params;
	}

	/**
	 * Allow the user to choose whether to have a single or a double optin for the form being edited
	 * It will hold the latest selected value in a cookie so that the user is presented by default with the same option selected the next time he edits such a form
	 *
	 * @param array $params
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		$processed_params = array();
		$params['optin']  = empty( $params['optin'] ) ? ( isset( $_COOKIE['tve_api_drip_optin'] ) ? $_COOKIE['tve_api_drip_optin'] : 'd' ) : $params['optin'];
		setcookie( 'tve_api_drip_optin', $params['optin'], strtotime( '+6 months' ), '/' );
		if ( ! empty( $params ) ) {
			foreach ( $params as $k => $v ) {
				if ( strpos( $k, 'field[' ) !== false ) {
					$key                                     = str_replace( 'field[', '', $k );
					$processed_params['proprieties'][ $key ] = $v;
				} else {
					$processed_params[ $k ] = $v;
				}
			}
		}
		$this->_directFormHtml( 'drip/optin-type', $processed_params );
		$this->_directFormHtml( 'drip/proprieties', $processed_params );
	}

	public function renderBeforeListsSettings( $params = array() ) {

		$this->_directFormHtml( 'drip/select-type', $params );
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Drip( $this->param( 'token' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected function _getLists() {
		try {
			/** @var Thrive_Dash_Api_Drip $api */
			$api = $this->getApi();

			$campaigns = $api->get_campaigns(
				array(
					'account_id' => $this->param( 'client_id' ),
					'status'     => 'all',
				)
			);

			if ( empty( $campaigns ) || ! is_array( $campaigns ) ) {
				$this->_error = __( 'There is not Campaign in your Drip account to be fetched !', 'thrive-dash' );

				return false;
			}

			$lists = array();

			foreach ( $campaigns['campaigns'] as $campaign ) {
				$lists[] = array(
					'id'   => $campaign['id'],
					'name' => $campaign['name'],
				);
			}

			return $lists;

		} catch ( Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}
	}

	/**
	 * Append custom fields to defaults
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_custom_fields( $params = array() ) {

		return array_merge( parent::get_custom_fields(), $this->_mapped_custom_fields );
	}

	/**
	 * @param      $params
	 * @param bool $force
	 * @param bool $get_all
	 *
	 * @return array|mixed
	 * @throws Exception
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = false ) {

		return $this->getAllCustomFields( $force );
	}

	/**
	 * Gets a list of fields from API or from cache
	 *
	 * @param (bool) $force true for fresh data or false from cache
	 *
	 * @return array|mixed
	 * @throws Exception
	 */
	public function getAllCustomFields( $force ) {

		$custom_data = array();

		// Serves from cache if exists and requested
		$cached_data = $this->_get_cached_custom_fields();
		if ( false === $force && ! empty( $cached_data ) ) {
			return $cached_data;
		}

		/** @var Thrive_Dash_Api_Drip $api */
		$api = $this->getApi();

		// Build custom fields for every list
		$custom_fields = $api->get_custom_fields(
			array(
				'account_id' => $this->param( 'client_id' ),
			)
		);

		if ( is_array( $custom_fields ) ) {
			foreach ( $custom_fields as $field ) {
				$custom_data[] = $this->normalize_custom_field( $field );
			}
		}

		$this->_save_custom_fields( $custom_data );

		return $custom_data;
	}


	/**
	 * Brings an API field under a known form that TAr can understand
	 *
	 * @param string $field
	 *
	 * @return array
	 */
	protected function normalize_custom_field( $field ) {

		return array(
			'id'    => $field,
			'name'  => $field,
			'type'  => $field,
			'label' => $field,
		);
	}

	/**
	 * Based on custom inputs set in form and their mapping
	 * - prepares a custom fields for Drip
	 *
	 * @param array $arguments POST sent by optin form
	 *
	 * @return array with drip custom field name as key and the value of inputs filled by the visitor
	 */
	public function prepare_api_custom_fields( $arguments ) {

		$fields = array();
		if ( empty( $arguments['tve_mapping'] ) ) {
			return $fields;
		}

		$serialized = base64_decode( $arguments['tve_mapping'] );
		$mapping    = array();
		if ( $serialized ) {
			$mapping = maybe_unserialize( $serialized );
		}

		foreach ( $mapping as $name => $field ) {
			$name = str_replace( '[]', '', $name );
			if ( ! empty( $field[ $this->_key ] ) && ! empty( $arguments[ $name ] ) ) {
				$custom_field_name            = $field[ $this->_key ];
				$custom_field_value           = $arguments[ $name ];
				$fields[ $custom_field_name ] = is_array( $custom_field_value ) ? implode( ', ', $custom_field_value ) : $custom_field_value;
			}
		}

		return $fields;
	}
}
