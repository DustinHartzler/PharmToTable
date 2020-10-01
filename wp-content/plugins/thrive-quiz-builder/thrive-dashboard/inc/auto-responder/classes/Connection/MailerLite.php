<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 15:33
 */
class Thrive_Dash_List_Connection_MailerLite extends Thrive_Dash_List_Connection_Abstract {
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
		return 'MailerLite';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'mailerlite' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid MailerLite key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to MailerLite using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( __( 'MailerLite connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_MailerLite $mailer */
		$mailer = $this->getApi();

		/**
		 * just try getting a list as a connection test
		 */

		try {
			/** @var Thrive_Dash_Api_MailerLite_Groups $groupsApi */
			$groupsApi = $mailer->groups();
			$groupsApi->get();
		} catch ( Thrive_Dash_Api_MailerLite_MailerLiteSdkException $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_MailerLite( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_MailerLite $api */
		$api = $this->getApi();

		try {
			/** @var Thrive_Dash_Api_MailerLite_Groups $groups_api */
			$groups_api = $api->groups();
			$groups_api->limit( 10000 );
			$lists_obj = $groups_api->get();

			$lists = array();
			foreach ( $lists_obj as $item ) {
				$lists [] = array(
					'id'   => $item->id,
					'name' => $item->name,
				);
			}

			return $lists;
		} catch ( Exception $e ) {
			$this->_error = $e->getMessage() . ' ' . __( 'Please re-check your API connection details.', TVE_DASH_TRANSLATE_DOMAIN );

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
	public function addSubscriber( $list_identifier, $arguments ) {
		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		/** @var Thrive_Dash_Api_MailerLite $api */
		$api = $this->getApi();

		$args['email'] = $arguments['email'];

		if ( ! empty( $first_name ) ) {
			$args['fields']['name'] = $first_name;
			$args['name']           = $first_name;
		}
		if ( ! empty( $last_name ) ) {
			$args['fields']['last_name'] = $last_name;
		}

		if ( isset( $arguments['phone'] ) ) {
			$args['fields']['phone'] = $arguments['phone'];

		}
		$args['resubscribe'] = 1;

		try {
			/** @var Thrive_Dash_Api_MailerLite_Groups $groupsApi */
			$groupsApi = $api->groups();

			$args['fields'] = array_merge( $args['fields'], $this->_generateCustomFields( $arguments ) );

			$groupsApi->addSubscriber( $list_identifier, $args );

			return true;
		} catch ( Thrive_Dash_Api_MailerLite_MailerLiteSdkException $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown MailerLite Error', TVE_DASH_TRANSLATE_DOMAIN );
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{$email}';
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

		$custom_data   = array();
		$allowed_types = array(
			'TEXT',
		);

		try {
			/** @var Thrive_Dash_Api_MailerLite $api */
			$custom_fields = $this->getApi()->fields()->get();

			if ( is_array( $custom_fields ) ) {
				foreach ( $custom_fields as $field ) {
					if ( ! empty( $field->type ) && in_array( $field->type, $allowed_types, true ) ) {
						$custom_data[] = $this->_normalize_custom_field( $field );
					}
				}
			}
		} catch ( Exception $e ) {
		}

		$this->_save_custom_fields( $custom_data );

		return $custom_data;
	}

	/**
	 * @param stdClass $field
	 *
	 * @return array
	 */
	public function _normalize_custom_field( $field ) {
		return array(
			'id'    => $field->id,
			'name'  => $field->title,
			'type'  => $field->type,
			'label' => $field->title,
		);
	}

	/**
	 * Generate custom fields array
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function _generateCustomFields( $args ) {
		$custom_fields = $this->get_api_custom_fields( array() );
		$ids           = $this->buildMappedCustomFields( $args );
		$result        = array();

		foreach ( $ids as $key => $id ) {
			$field = array_filter(
				$custom_fields,
				function ( $item ) use ( $id ) {
					return (int) $item['id'] === (int) $id['value'];
				}
			);

			$field = array_values( $field );

			if ( ! isset( $field[0] ) ) {
				continue;
			}

			$chunks               = explode( ' ', $field[0]['name'] );
			$chunks               = array_map( 'strtolower', $chunks );
			$field_key            = implode( '_', $chunks );
			$name         = strpos( $id['type'], 'mapping_' ) !== false ? $id['type'] . '_' . $key : $key;
			$cf_form_name         = str_replace( '[]', '', $name );
			$result[ $field_key ] = $this->processField( $args[ $cf_form_name ] );
		}

		return $result;
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

			if ( ! empty( $cf_form_fields ) && is_array( $cf_form_fields ) ) {

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
