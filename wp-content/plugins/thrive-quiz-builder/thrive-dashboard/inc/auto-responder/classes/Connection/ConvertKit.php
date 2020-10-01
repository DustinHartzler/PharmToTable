<?php

/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 15/9/2015
 * Time: 10:09 AM
 */
class Thrive_Dash_List_Connection_ConvertKit extends Thrive_Dash_List_Connection_Abstract {
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
		return 'ConvertKit / Seva';
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
		$this->_directFormHtml( 'convertkit' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid ConvertKit API Key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to ConvertKit: %s', TVE_DASH_TRANSLATE_DOMAIN ), $this->_error ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'ConvertKit connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		return is_array( $this->_getLists() );
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return Thrive_Dash_Api_ConvertKit
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_ConvertKit( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * ConvertKit has both sequences and forms
	 *
	 * @return array|string for error
	 */
	protected function _getLists() {
		/**
		 * just try getting the lists as a connection test
		 */
		try {

			/** @var $api Thrive_Dash_Api_ConvertKit */
			$api = $this->getApi();

			$lists = array();

			$data = $api->getForms();
			if ( ! empty( $data ) ) {
				foreach ( $data as $form ) {
					if ( ! empty( $form['archived'] ) ) {
						continue;
					}
					$lists[] = array(
						'id'   => $form['id'],
						'name' => $form['name'],
					);
				}
			}

			return $lists;

		} catch ( Thrive_Dash_Api_ConvertKit_Exception $e ) {
			$this->_error = $e->getMessage();

			return false;
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
		try {
			/** @var $api Thrive_Dash_Api_ConvertKit */
			$api = $this->getApi();

			$arguments['custom_fields_ids'] = $this->buildMappedCustomFields( $arguments );
			$arguments['fields']            = $this->_generateCustomFields( $arguments );

			$api->subscribeForm( $list_identifier, $arguments );

		} catch ( Exception $e ) {

			return $e->getMessage();
		}

		return true;
	}

	/**
	 * Get custom fields
	 *
	 * @return array
	 */
	public function getCustomFields() {
		/**  @var Thrive_Dash_Api_ConvertKit $api */
		$api    = $this->getApi();
		$fields = $api->getCustomFields();

		return isset( $fields['custom_fields'] ) ? $fields['custom_fields'] : array();
	}

	/**
	 * @param array $args
	 *
	 * @return object
	 * @throws Thrive_Dash_Api_ConvertKit_Exception
	 */
	protected function _generateCustomFields( $args ) {
		/**  @var Thrive_Dash_Api_ConvertKit $api */
		$api      = $this->getApi();
		$fields   = $this->_getCustomFields( false );
		$response = array();
		$ids      = $this->buildMappedCustomFields( $args );

		foreach ( $fields as $field ) {
			foreach ( $ids as $key => $id ) {
				if ( (int) $field['id'] === (int) $id['value'] ) {

					/**
					 * Ex cf: ck_field_84479_first_custom_field
					 * Needed Result: first_custom_field
					 */
					$_name = $field['name'];
					$_name = str_replace( 'ck_field_', '', $_name );
					$_name = explode( '_', $_name );

					unset( $_name[0] );

					$_name              = implode( '_', $_name );
					$name               = strpos( $id['type'], 'mapping_' ) !== false ? $id['type'] . '_' . $key : $key;
					$cf_form_name       = str_replace( '[]', '', $name );
					$response[ $_name ] = $this->processField( $args[ $cf_form_name ] );
				}
			}
		}

		if ( ! empty( $args['phone'] ) ) {
			$phone_fields      = $api->phoneFields( $args['phone'] );
			$response['phone'] = isset( $phone_fields['phone'] ) ? $phone_fields['phone'] : '';
		}

		return (object) $response;
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{{subscriber.email_address}}';
	}

	/**
	 * @param $force
	 *
	 * @return array
	 */
	protected function _getCustomFields( $force ) {

		// Serve from cache if exists and requested
		$cached_data = $this->_get_cached_custom_fields();
		if ( false === $force && ! empty( $cached_data ) ) {
			return $cached_data;
		}

		/** @var $api Thrive_Dash_Api_ConvertKit */
		$api = $this->getApi();

		$fields = $api->getCustomFields();
		$fields = isset( $fields['custom_fields'] ) ? $fields['custom_fields'] : array();

		foreach ( $fields as $key => $field ) {
			$fields[ $key ] = $this->normalize_custom_field( $field );
		}

		$this->_save_custom_fields( $fields );

		return $fields;
	}

	/**
	 * @param      $params
	 * @param bool $force
	 * @param bool $get_all
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_ConvertKit_Exception
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = false ) {

		$response = $this->_getCustomFields( $force );
		$response = is_array( $response ) ? $response : array();

		return $response;
	}

	protected function normalize_custom_field( $data ) {
		$data['type'] = 'text';

		return parent::normalize_custom_field( $data );
	}

	/**
	 * Build mapped custom fields array based on form params
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public function buildMappedCustomFields( $args ) {
		$mapped_data = array();

		// Should be always base_64 encoded of a serialized array
		if ( empty( $args['tve_mapping'] ) || ! tve_dash_is_bas64_encoded( $args['tve_mapping'] ) || ! is_serialized( base64_decode( $args['tve_mapping'] ) ) ) {
			return $mapped_data;
		}

		$form_data = unserialize( base64_decode( $args['tve_mapping'] ) );

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

					$field_id = str_replace( $mapped_field_name . '_', '', $cf_form_name );

					$mapped_data[ $field_id ] = array(
						'type'  => $mapped_field_name,
						'value' => $form_data[ $cf_form_name ][ $this->_key ],
					);
				}
			}
		}

		return $mapped_data;
	}
}
