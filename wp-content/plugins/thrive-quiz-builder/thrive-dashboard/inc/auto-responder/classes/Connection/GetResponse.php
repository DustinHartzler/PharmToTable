<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 03.04.2015
 * Time: 19:44
 */
class Thrive_Dash_List_Connection_GetResponse extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'GetResponse';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'get-response' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$key     = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';
		$url     = ! empty( $_POST['connection']['url'] ) ? $_POST['connection']['url'] : '';
		$version = ! empty( $_POST['connection']['version'] ) ? $_POST['connection']['version'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid GetResponse key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		if ( $version && $version == 3 && empty( $url ) ) {
			return $this->error( __( 'You must provide a valid GetResponse V3 API URL', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to GetResponse using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'GetResponse connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		$gr = $this->getApi();
		/**
		 * just try getting a list as a connection test
		 */
		$credentials = $this->getCredentials();

		try {
			if ( ! $credentials['version'] || $credentials['version'] == 2 ) {
				/** @var Thrive_Dash_Api_GetResponse $gr */
				$gr->getCampaigns();
			} else {
				/** @var Thrive_Dash_Api_GetResponseV3 $gr */
				$gr->ping();
			}
		} catch ( Thrive_Dash_Api_GetResponse_Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * Instantiate the API code required for this connection
	 *
	 * @return Thrive_Dash_Api_GetResponse|Thrive_Dash_Api_GetResponseV3
	 */
	protected function _apiInstance() {
		if ( ! $this->param( 'version' ) || $this->param( 'version' ) == 2 ) {
			return new Thrive_Dash_Api_GetResponse( $this->param( 'key' ) );
		} else {
			$getresponse = new Thrive_Dash_Api_GetResponseV3( $this->param( 'key' ), $this->param( 'url' ) );

			$enterprise_param = $this->param( 'enterprise' );
			if ( ! empty( $enterprise_param ) ) {
				$getresponse->enterprise_domain = $this->param( 'enterprise' );

			}

			return $getresponse;
		}
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|false
	 */
	protected function _getLists() {

		/** @var Thrive_Dash_Api_GetResponse $gr */
		$gr = $this->getApi();

		try {
			$lists       = array();
			$items       = $gr->getCampaigns();
			$credentials = $this->getCredentials();

			if ( ! $credentials['version'] || $credentials['version'] == 2 ) {
				foreach ( $items as $key => $item ) {
					$lists [] = array(
						'id'   => $key,
						'name' => $item->name,
					);
				}
			} else {
				foreach ( $items as $item ) {
					$lists [] = array(
						'id'   => $item->campaignId,
						'name' => $item->name,
					);
				}
			}

			return $lists;
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
		}
	}

	/**
	 * add a contact to a list
	 *
	 * @param string $list_identifier
	 * @param array  $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {

		/** @var Thrive_Dash_Api_GetResponseV3 $api */
		$api         = $this->getApi();
		$credentials = $this->getCredentials();
		$return      = true;
		$version     = empty( $credentials['version'] ) ? 2 : (int) $credentials['version'];

		try {
			if ( 2 === $version ) {
				if ( empty( $arguments['name'] ) ) {
					$arguments['name'] = ' ';
				}
				/** @var Thrive_Dash_Api_GetResponse $api */
				$api->addContact( $list_identifier, $arguments['name'], $arguments['email'], 'standard', (int) $arguments['get-response_cycleday'] );
			} else {

				$params = array(
					'email'      => $arguments['email'],
					'dayOfCycle' => $arguments['get-response_cycleday'],
					'campaign'   => array(
						'campaignId' => $list_identifier,
					),
					'ipAddress'  => tve_dash_get_ip(),
				);

				if ( ! empty( $arguments['name'] ) ) {
					$params['name'] = $arguments['name'];
				}

				// Set / Create & set Phone as custom field
				if ( ! empty( $arguments['phone'] ) ) {
					$params = array_merge( $params, $this->setCustomPhone( $arguments, $params ) );
				}

				// Build custom fields data
				$existing_custom_fields = ! empty( $params['customFieldValues'] ) ? $params['customFieldValues'] : array();
				$mapped_custom_fields   = $this->buildMappedCustomFields( $arguments, $existing_custom_fields );

				if ( ! empty( $mapped_custom_fields ) ) {
					$params = array_merge( $params, $mapped_custom_fields );
				}

				try {
					/**
					 * this contact may be in other list but try to add it in the current on
					 */
					$api->addContact( $params );
				} catch ( Exception $e ) {

				}

				/**
				 * we're talking about the same email but
				 * it is the same contact in multiple list
				 */
				$contacts = $api->searchContacts(
					array(
						'query' => array(
							'email' => $params['email'],
						),
					)
				);

				if ( ! empty( $contacts ) ) {
					foreach ( $contacts as $contact ) {
						/**
						 * Update the subscriber only in current list
						 */
						if ( $contact->campaign->campaignId === $params['campaign']['campaignId'] ) {
							$api->updateContact( $contact->contactId, $params );
						}
					}
				}
			}
		} catch ( Exception $e ) {
			$return = $e->getMessage();
		}

		return $return;
	}

	/**
	 * Build or add to existing custom fields array
	 *
	 * @param array $args
	 * @param array $mapped_data
	 *
	 * @return array
	 */
	public function buildMappedCustomFields( $args, $mapped_data = array() ) {

		// Should be always base_64 encoded of a serialized array
		if ( empty( $args['tve_mapping'] ) || ! tve_dash_is_bas64_encoded( $args['tve_mapping'] ) || ! is_serialized( base64_decode( $args['tve_mapping'] ) ) || ! is_array( $mapped_data ) ) {
			return array();
		}

		$form_data = unserialize( base64_decode( $args['tve_mapping'] ) );

		if ( is_array( $form_data ) ) {

			$mapped_fields = $this->getMappedFieldsIDs();

			foreach ( $mapped_fields as $mapped_field_name ) {

				// Extract an array with all custom fields (siblings) names from form data
				// {ex: [mapping_url_0, .. mapping_url_n] / [mapping_text_0, .. mapping_text_n]}
				$cf_form_fields = preg_grep( "#^{$mapped_field_name}#i", array_keys( $form_data ) );

				// Matched "form data" for current allowed name
				if ( ! empty( $cf_form_fields ) && is_array( $cf_form_fields ) ) {

					// Pull form allowed data, sanitize it and build the custom fields array
					foreach ( $cf_form_fields as $cf_form_name ) {

						if ( empty( $form_data[ $cf_form_name ][ $this->_key ] ) ) {
							continue;
						}

						$mapped_api_id = $form_data[ $cf_form_name ][ $this->_key ];
						$cf_form_name  = str_replace( '[]', '', $cf_form_name );
						if ( ! empty( $args[ $cf_form_name ] ) ) {
							$args[ $cf_form_name ] = $this->processField( $args[ $cf_form_name ] );
						}

						array_push(
							$mapped_data,
							array(
								'customFieldId' => $mapped_api_id,
								'value'         => array( sanitize_text_field( $args[ $cf_form_name ] ) ),
							)
						);
					}
				}
			}
		}

		return ! empty( $mapped_data ) ? array( 'customFieldValues' => $mapped_data ) : array();
	}

	/**
	 * Set / create&set a new phone custom field
	 *
	 * @param       $arguments
	 * @param array $params
	 *
	 * @return array
	 */
	public function setCustomPhone( $arguments, $params = array() ) {

		if ( empty( $arguments ) || ! is_array( $params ) ) {
			return array();
		}

		$custom_fields = $this->getApi()->getCustomFields();

		if ( is_array( $custom_fields ) ) {

			$phone_field = array_values( wp_list_filter( $custom_fields, array( 'name' => 'thrvphone' ) ) );

			/**
			 * We use a custom field to add phone filed for getResponse
			 * This because getResponse has a strict validation for built in phone number and very often added contacts
			 * with this custom field will fail
			 */
			if ( empty( $phone_field ) ) {

				$field_args = array(
					'name'   => 'thrvphone',
					'type'   => 'number',
					'hidden' => false,
					'values' => array(),
				);

				$phone_field = $this->getApi()->setCustomField( $field_args );
			}

			if ( ! empty( $phone_field[0]->customFieldId ) ) {
				$phone_value = str_replace( array( '-', '+', ' ' ), '', trim( $arguments['phone'] ) );

				$params['customFieldValues'] = array(
					array(
						'customFieldId' => $phone_field[0]->customFieldId,
						'value'         => array( $phone_value ),
					),
				);
			}
		}

		return $params;
	}

	/**
	 * Render extra html API setup form
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_extra_settings( $params = array() ) {

		return $params;
	}

	/**
	 * Render extra html API setup form
	 *
	 * @param array $params
	 *
	 * @see api-list.php
	 *
	 */
	public function renderExtraEditorSettings( $params = array() ) {

		$this->_directFormHtml( 'getresponse/cycleday', $params );
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {

		return '[[email]]';
	}

	/**
	 * @param      $params
	 * @param bool $force
	 * @param bool $get_all
	 *
	 * @return array|mixed
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = false ) {

		// Serve from cache if exists and requested
		$cached_data = $this->_get_cached_custom_fields();
		if ( false === $force && ! empty( $cached_data ) ) {
			return $cached_data;
		}

		// Needed custom fields type [every API can have different naming type]
		$allowed_types = array(
			'text',
			'url',
		);

		$custom_data = array();

		try {
			/** @var Thrive_Dash_Api_GetResponseV3 $api */
			$custom_fields = $this->getApi()->getCustomFields();

			if ( is_array( $custom_fields ) ) {
				foreach ( $custom_fields as $field ) {
					if ( ! empty( $field->type ) && in_array( $field->type, $allowed_types, true ) ) {
						$custom_data[] = $this->normalize_custom_field( $field );
					}
				}
			}

			$this->_save_custom_fields( $custom_data );

		} catch ( Thrive_Dash_Api_GetResponse_Exception $e ) {
		}

		return $custom_data;
	}

	/**
	 * @param array $field
	 *
	 * @return array
	 */
	protected function normalize_custom_field( $field ) {

		$field = (object) $field;

		return array(
			'id'    => ! empty( $field->customFieldId ) ? $field->customFieldId : '',
			'name'  => ! empty( $field->name ) ? $field->name : '',
			'type'  => ! empty( $field->type ) ? $field->type : '',
			'label' => ! empty( $field->name ) ? $field->name : '',
		);
	}

	/**
	 * Append custom fields to defaults
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_custom_fields( $params = array() ) {

		$fields = array_merge( parent::get_custom_fields(), $this->_mapped_custom_fields );

		return $fields;
	}
}

