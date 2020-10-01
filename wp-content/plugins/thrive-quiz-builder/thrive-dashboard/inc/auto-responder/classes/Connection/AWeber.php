<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 03.04.2015
 * Time: 17:31
 */
class Thrive_Dash_List_Connection_AWeber extends Thrive_Dash_List_Connection_Abstract {
	const APP_ID = '10fd90de';
	const CONSUMER_KEY = 'AkkjPM2epMfahWNUW92Mk2tl';
	const CONSUMER_SECRET = 'V9bzMop78pXTlPEAo30hxZF7dXYE6T6Ww2LAH95m';

	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * @return bool
	 */
	public function hasTags() {

		return true;
	}

	/**
	 * get the authorization URL for the AWeber Application
	 *
	 * @return string
	 */
	public function getAuthorizeUrl() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber      = $this->getApi();
		$callbackUrl = admin_url( 'admin.php?page=tve_dash_api_connect&api=aweber' );

		list ( $requestToken, $requestTokenSecret ) = $aweber->getRequestToken( $callbackUrl );

		update_option( 'thrive_aweber_rts', $requestTokenSecret );

		return $aweber->getAuthorizeUrl();
	}

	/**
	 * @return bool|void
	 */
	public function isConnected() {
		return $this->param( 'token' ) && $this->param( 'secret' );
	}

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'AWeber';
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'aweber' );
	}

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error, it should register an error message (and redirect?)
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber = $this->getApi();

		$aweber->user->tokenSecret  = get_option( 'thrive_aweber_rts' );
		$aweber->user->requestToken = $_REQUEST['oauth_token'];
		$aweber->user->verifier     = $_REQUEST['oauth_verifier'];

		try {
			list( $accessToken, $accessTokenSecret ) = $aweber->getAccessToken();
			$this->setCredentials( array(
				'token'  => $accessToken,
				'secret' => $accessTokenSecret,
			) );
		} catch ( Exception $e ) {
			$this->error( $e->getMessage() );

			return false;
		}

		$result = $this->testConnection();
		if ( $result !== true ) {
			$this->error( sprintf( __( 'Could not test AWeber connection: %s', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );

			return false;
		}

		$this->save();

		/**
		 * Fetch all custom fields on connect so that we have them all prepared
		 * - TAr doesn't need to fetch them from API
		 */
		$this->get_api_custom_fields( array(), true, true );

		return true;
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber = $this->getApi();

		try {
			$account = $aweber->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
			$isValid = $account->lists;

			return true;
		} catch ( Exception $e ) {
			return $e->getMessage();
		}

	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_AWeber( self::CONSUMER_KEY, self::CONSUMER_SECRET );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber = $this->getApi();

		try {
			$lists   = array();
			$account = $aweber->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
			foreach ( $account->lists as $item ) {
				/** @var Thrive_Dash_Api_AWeber_Entry $item */
				$lists [] = array(
					'id'   => $item->data['id'],
					'name' => $item->data['name'],
				);
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
	 * @param       $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {

		try {
			/** @var Thrive_Dash_Api_AWeber $aweber */
			$aweber  = $this->getApi();
			$account = $aweber->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
			$listURL = "/accounts/{$account->id}/lists/{$list_identifier}";
			$list    = $account->loadFromUrl( $listURL );

			# create a subscriber
			$params = array(
				'email'      => $arguments['email'],
				'name'       => $arguments['name'],
				'ip_address' => tve_dash_get_ip(),
			);

			if ( isset( $arguments['url'] ) ) {
				$params['custom_fields']['Web Form URL'] = $arguments['url'];
			}
			// create custom fields
			$custom_fields = $list->custom_fields;

			try {
				$custom_fields->create( array( 'name' => 'Web Form URL' ) );
			} catch ( Exception $e ) {
			}

			if ( ! empty( $arguments['phone'] ) && ( $phone_field_name = $this->phoneCustomFieldExists( $list ) ) ) {
				$params['custom_fields'][ $phone_field_name ] = $arguments['phone'];
			}

			if ( ! empty( $arguments['aweber_tags'] ) ) {
				$params['tags'] = explode( ',', trim( $arguments['aweber_tags'], ' ,' ) );
				$params['tags'] = array_map( 'trim', $params['tags'] );
			}

			if ( ( $existing_subscribers = $list->subscribers->find( array( 'email' => $params['email'] ) ) ) && $existing_subscribers->count() === 1 ) {
				$subscriber              = $existing_subscribers->current();
				$subscriber->name        = $params['name'];
				$subscriber->ad_tracking = $params['name'];
				if ( ! empty( $params['custom_fields'] ) ) {
					$subscriber->custom_fields = $params['custom_fields'];
				}
				if ( empty( $params['tags'] ) || ! is_array( $params['tags'] ) ) {
					$params['tags'] = array();
				}
				$tags = array_values( array_diff( $params['tags'], $subscriber->tags->getData() ) );

				if ( ! empty( $tags ) ) {
					$subscriber->tags = array(
						'add' => $tags,
					);
				}

				$new_subscriber = $subscriber->save() == 209;
			} else {
				$new_subscriber = $list->subscribers->create( $params );
			}

			if ( ! $new_subscriber ) {
				return sprintf( __( "Could not add contact: %s to list: %s", TVE_DASH_TRANSLATE_DOMAIN ), $arguments['email'], $list->name );
			}

			// Update custom fields
			// Make another call to update custom mapped fields in order not to break the subscription call,
			// if custom data doesn't pass API custom fields validation
			if ( ! empty( $arguments['tve_mapping'] ) ) {
				$this->updateCustomFields( $list_identifier, $arguments, $params );
			}

		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	protected function phoneCustomFieldExists( $list ) {
		$customFieldsURL = $list->custom_fields_collection_link;
		$customFields    = $list->loadFromUrl( $customFieldsURL );
		foreach ( $customFields as $custom ) {
			if ( stripos( $custom->name, 'phone' ) !== false ) {
				//return the name of the phone custom field cos users can set its name as: Phone/phone/pHone/etc
				//used in custom_fields for subscribers parameters
				/** @see addSubscriber */
				return $custom->name;
			}
		}

		return false;
	}

	/**
	 * output any (possible) extra editor settings for this API
	 *
	 * @param array $params allow various different calls to this method
	 */
	public function get_extra_settings( $params = array() ) {
		return $params;
	}

	/**
	 * output any (possible) extra editor settings for this API
	 *
	 * @param array $params allow various different calls to this method
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		$this->_directFormHtml( 'aweber/tags', $params );
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '{!email}';
	}

	/**
	 * @param array $params which may contain `list_id`
	 * @param bool $force make a call to API and invalidate cache
	 * @param bool $get_all where to get lists with their custom fields
	 *
	 * @return array
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = true ) {

		$lists = $this->getAllCustomFields( $force );

		// Get custom fields for all list ids [used on localize in TAr]
		if ( true === $get_all ) {
			return $lists;
		}

		$list_id = isset( $params['list_id'] ) ? $params['list_id'] : null;

		if ( '0' === $list_id ) {
			$list_id = current( array_keys( $lists ) );
		}

		return array( $list_id => $lists[ $list_id ] );
	}

	/**
	 * Get all custom fields by list id
	 *
	 * @param $force calls the API and invalidate cache
	 *
	 * @return array|mixed
	 */
	public function getAllCustomFields( $force ) {

		// Serve from cache if exists and requested
		$cached_data = $this->_get_cached_custom_fields();

		if ( false === $force && ! empty( $cached_data ) ) {
			return $cached_data;
		}

		$custom_fields = array();
		$lists         = $this->_getLists();

		if ( is_array( $lists ) ) {
			foreach ( $lists as $list ) {

				if ( empty( $list['id'] ) ) {
					continue;
				}

				$custom_fields[ $list['id'] ] = $this->getCustomFieldsByListId( $list['id'] );
			}
		}

		$this->_save_custom_fields( $custom_fields );

		return $custom_fields;
	}

	/**
	 * Get custom fields by list id
	 *
	 * @param $list_id
	 *
	 * @return array
	 */
	public function getCustomFieldsByListId( $list_id ) {

		$fields = array();

		if ( empty( $list_id ) ) {
			return $fields;
		}

		try {
			$account  = $this->getApi()->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
			$list_url = "/accounts/{$account->id}/lists/{$list_id}";
			$list_obj = $account->loadFromUrl( $list_url );

			// CF obj
			$custom_fields_url = $list_obj->custom_fields_collection_link;
			$custom_fields     = $list_obj->loadFromUrl( $custom_fields_url );

			foreach ( $custom_fields as $custom_field ) {

				if ( ! empty( $custom_field->data['name'] ) && ! empty( $custom_field->data['id'] ) ) {

					$fields[] = $this->_normalize_custom_field( $custom_field->data );
				}
			}
		} catch ( Thrive_Dash_Api_AWeber_Exception $e ) {
		}

		return $fields;
	}

	/**
	 * Normalize custom field data
	 *
	 * @param $field
	 *
	 * @return array
	 */
	protected function _normalize_custom_field( $field ) {

		$field = (array) $field;

		return array(
			'id'    => isset( $field['id'] ) ? $field['id'] : '',
			'name'  => ! empty( $field['name'] ) ? $field['name'] : '',
			'type'  => '', // API does not have type
			'label' => ! empty( $field['name'] ) ? $field['name'] : '',
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

	/**
	 * Call the API in order to update subscriber's custom fields
	 *
	 * @param $list_identifier
	 * @param $arguments
	 * @param $data
	 *
	 * @return bool
	 */
	public function updateCustomFields( $list_identifier, $arguments, $data ) {

		$saved = false;

		if ( ! $list_identifier || empty( $arguments ) || empty( $data['email'] ) ) {
			return $saved;
		}

		/** @var Thrive_Dash_Api_AWeber $aweber */
		$aweber   = $this->getApi();
		$account  = $aweber->getAccount( $this->param( 'token' ), $this->param( 'secret' ) );
		$list_url = "/accounts/{$account->id}/lists/{$list_identifier}";
		$list     = $account->loadFromUrl( $list_url );

		$custom_fields        = $this->buildMappedCustomFields( $list_identifier, $arguments );
		$existing_subscribers = $list->subscribers->find( array( 'email' => $data['email'] ) );
		if ( $existing_subscribers && $existing_subscribers->count() === 1 ) {
			$subscriber                = $existing_subscribers->current();
			$subscriber->custom_fields = $custom_fields;
			$saved                     = $subscriber->save();
		}

		if ( ! $saved ) {
			$this->api_log_error( $list_identifier, $custom_fields, __( 'Could not update custom fields', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		return $saved;
	}

	/**
	 * Creates and prepare the mapping data from the subscription form
	 *
	 * @param       $list_identifier
	 * @param       $args
	 * @param array $custom_fields
	 *
	 * @return array
	 */
	public function buildMappedCustomFields( $list_identifier, $args, $custom_fields = array() ) {

		if ( empty( $args['tve_mapping'] ) || ! tve_dash_is_bas64_encoded( $args['tve_mapping'] ) || ! is_serialized( base64_decode( $args['tve_mapping'] ) ) ) {
			return $custom_fields;
		}

		$mapped_form_data = unserialize( base64_decode( $args['tve_mapping'] ) );

		if ( is_array( $mapped_form_data ) && $list_identifier ) {
			$api_custom_fields = $this->buildCustomFieldsList();

			// Loop trough allowed custom fields names
			foreach ( $this->getMappedFieldsIDs() as $mapped_field_name ) {

				// Extract an array with all custom fields (siblings) names from the form data
				// {ex: [mapping_url_0, .. mapping_url_n] / [mapping_text_0, .. mapping_text_n]}
				$cf_form_fields = preg_grep( "#^{$mapped_field_name}#i", array_keys( $mapped_form_data ) );

				// Matched "form data" for current allowed name
				if ( ! empty( $cf_form_fields ) && is_array( $cf_form_fields ) ) {

					// Pull form allowed data, sanitize it and build the custom fields array
					foreach ( $cf_form_fields as $cf_form_name ) {

						if ( empty( $mapped_form_data[ $cf_form_name ][ $this->_key ] ) ) {
							continue;
						}

						$args[ $cf_form_name ] = $this->processField( $args[ $cf_form_name ] );

						$mapped_form_field_id          = $mapped_form_data[ $cf_form_name ][ $this->_key ];
						$field_label                   = $api_custom_fields[ $list_identifier ][ $mapped_form_field_id ];

						$cf_form_name          = str_replace( '[]', '', $cf_form_name );
						if ( ! empty( $args[ $cf_form_name ] ) ) {
							$args[ $cf_form_name ] = $this->processField( $args[ $cf_form_name ] );
						}
						$custom_fields[ $field_label ] = sanitize_text_field( $args[ $cf_form_name ] );
					}
				}
			}
		}

		return $custom_fields;
	}

	/**
	 * Create a simpler structure with [list_id] => [ field_id => field_name]
	 *
	 * @return array
	 */
	public function buildCustomFieldsList() {

		$parsed = array();

		foreach ( $this->getAllCustomFields( false ) as $list_id => $merge_field ) {
			array_map(
				function ( $var ) use ( &$parsed, $list_id ) {
					$parsed[ $list_id ][ $var['id'] ] = $var['name'];
				},
				$merge_field
			);
		}

		return $parsed;
	}
}
