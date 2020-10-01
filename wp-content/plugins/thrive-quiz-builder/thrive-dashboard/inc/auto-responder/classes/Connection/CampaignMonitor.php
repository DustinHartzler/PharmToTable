<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Connection_CampaignMonitor extends Thrive_Dash_List_Connection_Abstract {
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
		return 'Campaign Monitor';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'campaignmonitoremail' );
		if ( $related_api->isConnected() ) {
			$this->setParam( 'new_connection', 1 );
		}

		$this->_directFormHtml( 'campaignmonitor' );
	}

	/**
	 * Just saves the key in the database for optin and email api
	 *
	 * @return string|void
	 */
	public function readCredentials() {

		$key = ! empty( $_POST['connection']['key'] ) ? $_POST['connection']['key'] : '';

		if ( empty( $key ) ) {
			return $this->error( __( 'You must provide a valid Campaign Monitor key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Campaign Monitor using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/** @var Thrive_Dash_List_Connection_CampaignMonitorEmail $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'campaignmonitoremail' );

		if ( isset( $_POST['connection']['new_connection'] ) && intval( $_POST['connection']['new_connection'] ) === 1 ) {
			/**
			 * Try to connect to the email service too
			 */
			$r_result = true;
			if ( ! $related_api->isConnected() ) {
				$r_result = $related_api->readCredentials();
			}

			if ( $r_result !== true ) {
				$this->disconnect();

				return $this->error( $r_result );
			}
		} else {
			/**
			 * let's make sure that the api was not edited and disconnect it
			 */
			$related_api->setCredentials( array() );
			Thrive_Dash_List_Manager::save( $related_api );
		}

		return $this->success( __( 'Campaign Monitor connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {

		/** @var Thrive_Dash_Api_CampaignMonitor $cm */
		$cm = $this->getApi();

		try {
			$clients = $cm->get_clients();
			$client  = current( $clients );
			$cm->get_client_lists( $client['id'] );
		} catch ( Exception $e ) {
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

		return new Thrive_Dash_Api_CampaignMonitor( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {

		$lists = array();

		try {
			/** @var Thrive_Dash_Api_CampaignMonitor $cm */
			$cm = $this->getApi();

			$clients = $cm->get_clients();
			$client  = current( $clients );

			$lists = $cm->get_client_lists( $client['id'] );
		} catch ( Exception $e ) {

		}

		return $lists;
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

		try {
			$subscriber = array();
			/** @var Thrive_Dash_Api_CampaignMonitor $cm */
			$cm = $this->getApi();

			$subscriber['EmailAddress']                           = $arguments['email'];
			$subscriber['Resubscribe']                            = true;
			$subscriber['RestartSubscriptionBasedAutoresponders'] = true;

			if ( ! empty( $arguments['name'] ) ) {
				$subscriber['Name'] = $arguments['name'];
			}

			/** @var Thrive_Dash_Api_CampaignMonitor_List $list */
			$list = $cm->get_list( $list_identifier );

			if ( ! empty( $arguments['phone'] ) ) {
				$custom_fields   = $list->get_custom_fields();
				$_list_has_phone = false;
				if ( ! empty( $custom_fields ) ) {
					foreach ( $custom_fields as $field ) {
						if ( isset( $field['name'] ) && $field['name'] === 'Phone' ) {
							$_list_has_phone = true;
							break;
						}
					}
				}

				if ( $_list_has_phone === false ) {
					$custom_field = array(
						'FieldName'                 => 'Phone',
						'DataType'                  => 'Number',
						'Options'                   => array(),
						'VisibleInPreferenceCenter' => true,
					);

					$list->create_custom_field( $custom_field );
				}
				$subscriber['CustomFields'] = array(
					array(
						'Key'   => 'Phone',
						'Value' => strval( $arguments['phone'] ),
					),
				);
			}

			if ( empty( $subscriber['CustomFields'] ) ) {
				$subscriber['CustomFields'] = array();
			}

			$_custom_fields = $this->_generate_custom_fields( array_merge( $arguments, array( 'list_id' => $list_identifier ) ) );

			$subscriber['CustomFields'] = array_merge( $subscriber['CustomFields'], $_custom_fields );

			$list->add_subscriber( $subscriber );
		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '[email]';
	}

	/**
	 * disconnect (remove) this API connection
	 */
	public function disconnect() {

		$this->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $this );

		/**
		 * disconnect the email service too
		 */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'campaignmonitoremail' );
		$related_api->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $related_api );

		return $this;
	}

	/**
	 * @param array $params  which may contain `list_id`
	 * @param bool  $force   make a call to API and invalidate cache
	 * @param bool  $get_all where to get lists with their custom fields
	 *
	 * @return array
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = true ) {

		$cached_data = $this->_get_cached_custom_fields();
		if ( false === $force && ! empty( $cached_data ) ) {
			return $cached_data;
		}

		/** @var Thrive_Dash_Api_CampaignMonitor $cm */
		$cm            = $this->getApi();
		$custom_data   = array();
		$lists         = array();
		$allowed_types = array(
			'Text',
		);

		try {
			$clients = $cm->get_clients();
			$client  = current( $clients );
			$lists   = $cm->get_client_lists( $client['id'] );
		} catch ( Exception $e ) {
		}

		foreach ( $lists as $list ) {
			$custom_data[ $list['id'] ] = array();

			try {
				$custom_fields = $cm->get_list_custom_fields( $list['id'] );

				foreach ( $custom_fields as $item ) {

					if ( isset( $item['DataType'] ) && in_array( $item['DataType'], $allowed_types ) ) {
						$custom_data[ $list['id'] ][] = $this->normalize_custom_field( $item );
					}
				}
			} catch ( Exception $e ) {
			}
		}

		$this->_save_custom_fields( $custom_data );

		return $custom_data;
	}

	/**
	 * @param array $field
	 *
	 * @return array
	 */
	protected function normalize_custom_field( $field = array() ) {

		return array(
			'id'    => ! empty( $field['Key'] ) ? $field['Key'] : '',
			'name'  => ! empty( $field['FieldName'] ) ? $field['FieldName'] : '',
			'type'  => ! empty( $field['DataType'] ) ? $field['DataType'] : '',
			'label' => ! empty( $field['FieldName'] ) ? $field['FieldName'] : '',
		);
	}


	/**
	 * Generate custom fields array
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function _generate_custom_fields( $args ) {
		$custom_fields = $this->get_api_custom_fields( array() );

		if ( empty( $custom_fields[ $args['list_id'] ] ) ) {
			return array();
		}

		$custom_fields = $custom_fields[ $args['list_id'] ];

		$mapped_custom_fields = $this->build_mapped_custom_fields( $args );
		$result               = array();

		foreach ( $mapped_custom_fields as $key => $custom_field ) {

			$field_key         = strpos( $custom_field['type'], 'mapping_' ) !== false ? $custom_field['type'] . '_' . $key : $key;

			$field_key          = str_replace( '[]', '', $field_key );
			if ( ! empty( $args[ $field_key ] ) ) {
				$args[ $field_key ] = $this->processField( $args[ $field_key ] );
			}

			$is_in_list = array_filter(
				$custom_fields,
				function ( $field ) use ( $custom_field ) {
					return $custom_field['value'] === $field['id'];
				}
			);

			if ( ! empty( $is_in_list ) && isset( $args[ $field_key ] ) ) {
				$result[] = array(
					'Key'   => $custom_field['value'],
					'Value' => sanitize_text_field( $args[ $field_key ] ),
				);
			}
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
	public function build_mapped_custom_fields( $args ) {
		$mapped_data = array();

		// Should be always base_64 encoded of a serialized array
		if ( empty( $args['tve_mapping'] ) || ! tve_dash_is_bas64_encoded( $args['tve_mapping'] ) || ! is_serialized( base64_decode( $args['tve_mapping'] ) ) ) {
			return $mapped_data;
		}

		$form_data = unserialize( base64_decode( $args['tve_mapping'] ) );

		$mapped_fields = $this->getMappedFieldsIDs();

		foreach ( $mapped_fields as $mapped_field_name ) {

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
