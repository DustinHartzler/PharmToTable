<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 15:33
 */
class Thrive_Dash_List_Connection_Infusionsoft extends Thrive_Dash_List_Connection_Abstract {

	/**
	 * @var array Allowed custom fields
	 */
	protected $_custom_fields = array();

	/**
	 * Thrive_Dash_List_Connection_Infusionsoft constructor.
	 *
	 * @param $key
	 */
	public function __construct( $key ) {

		parent::__construct( $key );

		// DataType ID for text and website
		$this->_custom_fields = array(
			15 => 'text',
			18 => 'url',
		);
	}

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
		return 'Infusionsoft';
	}

	public function getListSubtitle() {
		return __( 'Choose your Tag Name List', 'thrive-dash' );
	}

	/**
	 * @return bool
	 */
	public function hasTags() {

		return true;
	}

	/**
	 * @param array|string $tags
	 * @param array        $data
	 *
	 * @return array
	 */
	public function pushTags( $tags, $data = array() ) {

		$data['tqb_tags'] = implode( ', ', $tags );

		return $data;
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'infusionsoft' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$client_id = ! empty( $_POST['connection']['client_id'] ) ? $_POST['connection']['client_id'] : '';
		$key       = ! empty( $_POST['connection']['api_key'] ) ? $_POST['connection']['api_key'] : '';

		if ( empty( $key ) || empty( $client_id ) ) {
			return $this->error( __( 'Client ID and API key are required', 'thrive-dash' ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( true !== $result ) {
			/* translators: %s: error message */
			$error = __( 'Could not connect to Infusionsoft using the provided credentials (<strong>%s</strong>)', 'thrive-dash' );

			return $this->error( sprintf( $error, $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return $this->success( 'Infusionsoft connected successfully' );
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/**
		 * just try getting a list as a connection test
		 */
		$result = $this->_getLists();

		if ( is_array( $result ) ) {
			return true;
		}

		/* At this point, $result will be a string */

		return $result;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed|Thrive_Dash_Api_Infusionsoft
	 * @throws Thrive_Dash_Api_Infusionsoft_InfusionsoftException
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Infusionsoft( $this->param( 'client_id' ), $this->param( 'api_key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {
		try {
			/** @var Thrive_Dash_Api_Infusionsoft $api */
			$api = $this->getApi();

			$query_data      = array(
				'GroupName' => '%',
			);
			$selected_fields = array( 'Id', 'GroupName' );
			$response        = $api->data( 'query', 'ContactGroup', 1000, 0, $query_data, $selected_fields );

			if ( empty( $response ) ) {
				return array();
			}

			$tags = $response;

			/**
			 * Infusionsoft has a limit of 1000 results to fetch, we should get all tags if the user has more
			 */
			$i = 1;
			while ( count( $response ) === 1000 ) {
				$response = $api->data( 'query', 'ContactGroup', 1000, $i, $query_data, $selected_fields );
				$tags     = array_merge( $tags, $response );
				$i ++;
			}

			$lists = array();

			foreach ( $tags as $item ) {
				$lists[] = array(
					'id'   => $item['Id'],
					'name' => $item['GroupName'],
				);
			}

			return $lists;

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
	 * @return bool|string true for success or string error message for failure
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		try {
			/** @var Thrive_Dash_Api_Infusionsoft $api */
			$api = $this->getApi();

			list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

			$data = array(
				'FirstName' => $first_name,
				'LastName'  => $last_name,
				'Email'     => $arguments['email'],
				'Phone1'    => $arguments['phone'],
			);

			$contact_id = $api->contact( 'addWithDupCheck', $data, 'Email' );

			if ( $contact_id ) {
				$api->APIEmail( 'optIn', $data['Email'], 'thrive opt in' );

				$today          = date( 'Ymj\TG:i:s' );
				$creation_notes = 'A web form was submitted with the following information:';
				$ip_address     = tve_dash_get_ip();

				if ( ! empty( $arguments['url'] ) ) {
					$creation_notes .= "\nReferring URL: " . $arguments['url'];
				}

				$creation_notes .= "\nIP Address: " . $ip_address;
				$creation_notes .= "\ninf_field_Email: " . $arguments['email'];
				$creation_notes .= "\ninf_field_LastName: " . $last_name;
				$creation_notes .= "\ninf_field_FirstName: " . $first_name;

				$add_note = array(
					'ContactId'         => $contact_id,
					'CreationDate'      => $today,
					'CompletionDate'    => $today,
					'ActionDate'        => $today,
					'EndDate'           => $today,
					'ActionType'        => 'Other',
					'ActionDescription' => 'Thrive Leads Note',
					'CreationNotes'     => $creation_notes,
				);

				$api->data( 'add', 'ContactAction', $add_note );

				if ( ! empty( $arguments['tve_affiliate'] ) ) {
					$api->data( 'add', 'Referral', array(
						'AffiliateId' => $arguments['tve_affiliate'],
						'ContactId'   => $contact_id,
						'DateSet'     => $today,
						'IPAddress'   => $ip_address,
						'Source'      => 'thrive opt in',
					) );
				}
			}

			$contact = $api->contact(
				'load',
				$contact_id,
				array(
					'Id',
					'Email',
					'Groups',
				)
			);

			$existing_groups = empty( $contact['Groups'] ) ? array() : explode( ',', $contact['Groups'] );

			if ( ! in_array( $list_identifier, $existing_groups ) ) {
				$api->contact( 'addToGroup', $contact_id, $list_identifier );
			}

			do_action( 'tvd_after_infusionsoft_contact_added', $this, $contact, $list_identifier, $arguments );

			// Update custom fields
			// Make another call to update custom mapped fields in order not to break the subscription call,
			// if custom data doesn't pass API custom fields validation
			if ( ! empty( $arguments['tve_mapping'] ) ) {
				$this->updateCustomFields( $contact_id, $arguments );
			}

			return true;

		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '~Contact.Email~';
	}

	/**
	 * Retrieve the contact's tags.
	 * Tags in Infusionsoft are named Groups
	 *
	 * @param int $contact_id
	 *
	 * @return array
	 */
	public function get_contact_tags( $contact_id ) {

		$tags = array();

		if ( empty( $contact_id ) ) {
			return $tags;
		}

		$api = $this->getApi();

		$query_data = array(
			'ContactId' => $contact_id,
		);

		$selected_fields = array(
			'GroupId',
			'ContactGroup',
		);

		$saved_tags = $api->data( 'query', 'ContactGroupAssign', 9999, 0, $query_data, $selected_fields );

		if ( ! empty( $saved_tags ) ) {
			/**
			 * set the group id as key in tags array and
			 * set as value the group name
			 */
			foreach ( $saved_tags as $item ) {
				$tags[ $item['GroupId'] ] = $item['ContactGroup'];
			}
		}

		return $tags;
	}

	/**
	 * Retrieve all tags(groups) form Infusionsoft for current connection
	 *
	 * @return array
	 */
	public function get_tags( $use_cache = true ) {

		$tags = array();

		if ( $use_cache ) {
			$lists = $this->getLists();
			foreach ( $lists as $list ) {
				$tags[ $list['id'] ] = $list['name'];
			}

			return $tags;
		}

		$api = $this->getApi();

		$query_data = array(
			'Id' => '%',
		);

		$selected_fields = array(
			'Id',
			'GroupName',
		);

		$saved_tags = $api->data( 'query', 'ContactGroup', 1000, 0, $query_data, $selected_fields );

		$data = $saved_tags;

		/**
		 * Infusionsoft has a limit of 1000 results to fetch, we should get all tags if the user has more
		 */
		$i = 1;
		while ( count( $saved_tags ) === 1000 ) {
			$saved_tags = $api->data( 'query', 'ContactGroup', 1000, $i, $query_data, $selected_fields );
			$data       = array_merge( $data, $saved_tags );
			$i ++;
		}

		if ( ! empty( $data ) ) {
			foreach ( $data as $item ) {
				$tags[ $item['Id'] ] = $item['GroupName'];
			}
		}

		return $tags;
	}

	/**
	 * Add a new Tag(Group) to Infusionsoft
	 *
	 * @param $tag_name
	 *
	 * @return int|null id
	 */
	public function create_tag( $tag_name ) {

		$query_data = array(
			'GroupName' => $tag_name,
		);

		$selected_fields = array(
			'Id',
			'GroupName',
		);

		$tags = $this->getApi()->data( 'query', 'ContactGroup', 1, 0, $query_data, $selected_fields );

		if ( is_array( $tags ) && ! empty( $tags ) ) {

			$tag = $tags[0];

			if ( isset( $tag['Id'] ) ) {

				return $tag['Id'];
			}
		}

		$id = $this->getApi()->data(
			'add',
			'ContactGroup',
			array(
				'GroupName' => $tag_name,
			)
		);

		$this->getLists( false );

		return ! empty( $id ) ? $id : null;
	}

	/**
	 * @param array $params  which may contain `list_id`
	 * @param bool  $force   make a call to API and invalidate cache
	 * @param bool  $get_all where to get lists with their custom fields
	 *
	 * @return array|mixed
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = false ) {

		$custom_fields = array();

		try {
			$custom_fields = $this->getAllCustomFields( $force );
		} catch ( Thrive_Dash_Api_Infusionsoft_InfusionsoftException $e ) {
		}

		return $custom_fields;
	}

	/**
	 * Get all custom fields
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

		// https://developer.infusionsoft.com/docs/table-schema/#DataFormField [custom fields Ids]
		// There is no IN operator in Infusionsoft XML-RPC that works with other fields beside id type, so will do separate calls bellow for each type
		// https://developer.infusionsoft.com/docs/xml-rpc/#data-query-a-data-table
		// Make sure we grab all custom fields [there are clients with more than 1k records.. been there, done that]

		foreach ( array_keys( $this->_custom_fields ) as $field_id ) {

			if ( empty( $field_id ) || ! is_int( $field_id ) ) {
				continue;
			}

			$custom_fields = $this->_getCustomFieldsById( $field_id, $custom_fields );
		}

		$this->_save_custom_fields( $custom_fields );

		return $custom_fields;
	}

	/**
	 * Get API custom text fields and append them to $custom_fields array
	 *
	 * @param int   $field_id
	 * @param array $custom_fields
	 *
	 * @return array
	 */
	protected function _getCustomFieldsById( $field_id, $custom_fields = array() ) {

		if ( empty( $field_id ) || ! is_int( $field_id ) || ! is_array( $custom_fields ) ) {
			return $custom_fields;
		}

		/** @var Thrive_Dash_Api_Infusionsoft $api */
		$api   = $this->getApi();
		$limit = 1000; // API pull limit
		$page  = 0;

		do {
			$response = $api->data(
				'query',
				'DataFormField',
				$limit,
				$page,
				array(
					'DataType' => (int) $field_id,
				),
				array(
					'GroupId',
					'Name',
					'Label',
				)
			);

			if ( ! empty( $response ) && is_array( $response ) ) {
				$custom_fields = array_merge( $custom_fields, array_map( array(
					$this,
					'normalize_custom_field',
				), $response ) );
			}
			$page ++;
		} while ( count( $response ) === $limit );

		return $custom_fields;
	}

	/**
	 * Normalize custom field data
	 *
	 * @param $field
	 *
	 * @return array
	 */
	protected function normalize_custom_field( $field ) {

		$field = (array) $field;

		return array(
			'id'    => isset( $field['Name'] ) ? $field['Name'] : '',
			'name'  => ! empty( $field['Label'] ) ? $field['Label'] : '',
			'type'  => ! empty( $field['DataType'] ) && array_key_exists( (int) $field['DataType'], $this->_custom_fields ) ? $this->_custom_fields[ $field['DataType'] ] : '',
			'label' => ! empty( $field['Label'] ) ? $field['Label'] : '',
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
	 * @param int   $contact_id
	 * @param array $arguments
	 *
	 * @return bool
	 */
	public function updateCustomFields( $contact_id, $arguments ) {

		$saved = false;

		if ( ! is_int( $contact_id ) || empty( $arguments ) ) {
			return $saved;
		}

		try {
			$custom_fields = $this->buildMappedCustomFields( $arguments );

			$api = $this->getApi();
			$api->contact(
				'update',
				$contact_id,
				$custom_fields
			);
			$saved = true;
		} catch ( Thrive_Dash_Api_Infusionsoft_InfusionsoftException $e ) {
			$this->api_log_error( $contact_id, array( 'infusion_custom_fields' => $custom_fields ), $e->getMessage() );
		}

		return $saved;
	}

	/**
	 * Creates and prepare the mapping data from the update call
	 *
	 * @param array $args          form arguments
	 * @param array $custom_fields array of custom fields where to append/update
	 *
	 * @return array
	 */
	public function buildMappedCustomFields( $args, $custom_fields = array() ) {

		if ( empty( $args['tve_mapping'] ) || ! tve_dash_is_bas64_encoded( $args['tve_mapping'] ) || ! is_serialized( base64_decode( $args['tve_mapping'] ) ) ) {
			return $custom_fields;
		}

		$mapped_form_data = unserialize( base64_decode( $args['tve_mapping'] ) );

		if ( is_array( $mapped_form_data ) ) {

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

						$mapped_form_field_id = $mapped_form_data[ $cf_form_name ][ $this->_key ];
						$cf_form_name         = str_replace( '[]', '', $cf_form_name );
						if ( ! empty( $args[ $cf_form_name ] ) ) {
							$args[ $cf_form_name ] = $this->processField( $args[ $cf_form_name ] );
						}

						// Build key => value pairs as the API needs
						$custom_fields[ '_' . $mapped_form_field_id ] = sanitize_text_field( $args[ $cf_form_name ] );
					}
				}
			}
		}

		return $custom_fields;
	}
}

