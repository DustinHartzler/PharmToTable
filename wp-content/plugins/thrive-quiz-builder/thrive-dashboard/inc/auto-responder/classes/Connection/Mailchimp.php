<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 02.04.2015
 * Time: 15:33
 */
class Thrive_Dash_List_Connection_Mailchimp extends Thrive_Dash_List_Connection_Abstract {

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
		return '*|EMAIL|*';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'Mailchimp';
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
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );
		if ( $related_api->isConnected() ) {
			$credentials = $related_api->getCredentials();
			$this->setParam( 'email', $credentials['email'] );
			$this->setParam( 'mandrill-key', $credentials['key'] );
		}

		$this->_directFormHtml( 'mailchimp' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed
	 */
	public function readCredentials() {
		$mandrill_key = ! empty( $_POST['connection']['mandrill-key'] ) ? $_POST['connection']['mandrill-key'] : '';

		if ( isset( $_POST['connection']['mailchimp_key'] ) ) {
			$_POST['connection']['mandrill-key'] = $_POST['connection']['key'];
			$_POST['connection']['key']          = $_POST['connection']['mailchimp_key'];
			$mandrill_key                        = $_POST['connection']['mandrill-key'];
		}

		if ( empty( $_POST['connection']['key'] ) ) {
			return $this->error( __( 'You must provide a valid Mailchimp key', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( sprintf( __( 'Could not connect to Mailchimp using the provided key (<strong>%s</strong>)', TVE_DASH_TRANSLATE_DOMAIN ), $result ) );
		}

		/**
		 * finally, save the connection details
		 */
		$this->save();

		/** @var Thrive_Dash_List_Connection_Mandrill $related_api */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );

		if ( ! empty( $mandrill_key ) ) {
			/**
			 * Try to connect to the email service too
			 */

			$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );
			$r_result    = true;
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

		/**
		 * Fetch all custom fields on connect so that we have them all prepared
		 * - TAr doesn't need to fetch them from API
		 */
		$this->get_api_custom_fields( array(), true, true );

		return $this->success( __( 'Mailchimp connected successfully', TVE_DASH_TRANSLATE_DOMAIN ) );
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

		try {
			/** @var Thrive_Dash_Api_Mailchimp $mc */
			$mc = $this->getApi();

			$mc->request( 'lists' );
		} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * disconnect (remove) this API connection
	 */
	public function disconnect() {

		$this->beforeDisconnect();
		$this->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $this );

		/**
		 * disconnect the email service too
		 */
		$related_api = Thrive_Dash_List_Manager::connectionInstance( 'mandrill' );
		$related_api->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $related_api );

		return $this;
	}

	/**
	 * Build interests object
	 *
	 * @param $list_identifier
	 * @param $arguments
	 *
	 * @return stdClass
	 */
	public function build_interests( $list_identifier, $arguments ) {

		$interests = new stdClass();

		if ( empty( $arguments ) || ! is_array( $arguments ) ) {
			return $interests;
		}

		if ( isset( $arguments['mailchimp_groupin'] ) && '0' !== (string) $arguments['mailchimp_groupin'] && ! empty( $arguments['mailchimp_group'] ) ) {
			$grouping              = array();
			$group_ids             = explode( ',', $arguments['mailchimp_group'] );
			$params['list_id']     = $list_identifier;
			$params['grouping_id'] = $arguments['mailchimp_groupin'];

			try {

				$grouping = $this->_getGroups( $params );
			} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
			}

			if ( ! empty( $grouping ) ) {

				foreach ( $grouping[0]->groups as $group ) {
					if ( in_array( (string) $group->id, $group_ids, true ) ) {
						$interests->{$group->id} = true;
					}
				}
			}
		}

		return $interests;
	}

	/**
	 * Build merge fields object
	 *
	 * @param $list_identifier
	 * @param $arguments
	 *
	 * @return stdClass
	 */
	public function build_merge_fields( $list_identifier, $arguments ) {

		$merge_fields = new stdClass();

		if ( empty( $list_identifier ) || empty( $arguments ) ) {
			return $merge_fields;
		}

		list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );

		// First name
		if ( ! empty( $first_name ) ) {
			$merge_fields->FNAME = $first_name;
		}

		// Last name
		if ( ! empty( $last_name ) ) {
			$merge_fields->LNAME = $last_name;
		}

		// Name
		if ( ! empty( $arguments['name'] ) ) {
			$merge_fields->NAME = $arguments['name'];
		}

		// Phone
		if ( ! empty( $arguments['phone'] ) ) {

			$phone_tag  = false;
			$api        = $this->getApi();
			$merge_vars = $this->getCustomFields( $list_identifier );

			foreach ( $merge_vars as $item ) {

				if ( 'phone' === $item->type || $item->name === $arguments['phone'] ) {
					$phone_tag                = true;
					$item_name                = $item->name;
					$item_tag                 = $item->tag;
					$merge_fields->$item_name = $arguments['phone'];
					$merge_fields->$item_tag  = $arguments['phone'];
				}
			}

			// Create phone merge field if not exists in mailchimp
			if ( false === $phone_tag ) {

				try {
					$api->request(
						'lists/' . $list_identifier . '/merge-fields',
						array(
							'name' => 'phone',
							'type' => 'phone',
							'tag'  => 'phone',
						),
						'POST'
					);

					$merge_fields->phone = $arguments['phone'];
				} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
				}
			}
		}

		return $merge_fields;
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

	/**
	 * Add the mapped custom fields to merge_fields obj
	 *
	 * @param $list_identifier
	 * @param $args
	 * @param $merge_fields
	 *
	 * @return mixed
	 */
	public function buildMappedCustomFields( $list_identifier, $args, $merge_fields ) {

		if ( empty( $args['tve_mapping'] ) || ! tve_dash_is_bas64_encoded( $args['tve_mapping'] ) || ! is_serialized( base64_decode( $args['tve_mapping'] ) ) ) {
			return $merge_fields;
		}

		$mapped_form_data = unserialize( base64_decode( $args['tve_mapping'] ) );

		if ( is_array( $mapped_form_data ) && is_object( $merge_fields ) && $list_identifier ) {

			// Cached and parsed custom fields from API
			$api_custom_fields = $this->buildCustomFieldsList();

			if ( empty( $api_custom_fields[ $list_identifier ] ) ) {
				return $merge_fields;
			}

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

						$mapped_form_field_id         = $mapped_form_data[ $cf_form_name ][ $this->_key ];
						$field_label                  = $api_custom_fields[ $list_identifier ][ $mapped_form_field_id ];

						$cf_form_name          = str_replace( '[]', '', $cf_form_name );
						if ( ! empty( $args[ $cf_form_name ] ) ) {
							$args[ $cf_form_name ] = $this->processField( $args[ $cf_form_name ] );
						}

						$merge_fields->{$field_label} = sanitize_text_field( $args[ $cf_form_name ] );
					}
				}
			}
		}

		return $merge_fields;
	}

	/**
	 * Build optin and status data
	 *
	 * @param $list_identifier
	 * @param $arguments
	 *
	 * @return array
	 */
	public function build_statuses( $list_identifier, $arguments ) {

		if ( empty( $list_identifier ) || empty( $arguments ) ) {
			return array( '', '' );
		}

		$status    = '';
		$user_hash = md5( strtolower( $arguments['email'] ) );
		$optin     = isset( $arguments['mailchimp_optin'] ) && 's' === $arguments['mailchimp_optin'] ? 'subscribed' : 'pending';

		$api = $this->getApi();

		try {

			$contact = $api->request( 'lists/' . $list_identifier . '/members/' . $user_hash, array(), 'GET' );

			if ( ! empty( $contact->status ) ) {
				$status = $contact->status;
			}

			if ( 'unsubscribed' === $contact->status ) {
				$optin = 'pending';
			}
		} catch ( Exception $exception ) {
		}

		return array(
			$optin,
			$status,
		);
	}

	/**
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return bool|mixed|string|void
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function addSubscriber( $list_identifier, $arguments ) {

		$arguments = (array) $arguments;

		if ( empty( $list_identifier ) || empty( $arguments ) ) {
			return __( 'Invalid arguments supplied in ' . __METHOD__, TVE_DASH_TRANSLATE_DOMAIN );
		}

		// Build optin and status
		list( $optin, $status ) = $this->build_statuses( $list_identifier, $arguments );

		$email     = strtolower( $arguments['email'] );
		$user_hash = md5( $email );

		/** @var Thrive_Dash_Api_Mailchimp $api */
		$api = $this->getApi();

		// Subscribe
		try {

			$data = array(
				'email_address' => $email,
				'status'        => ! empty( $status ) && 'subscribed' === $status ? $status : $optin,
				'merge_fields'  => $this->build_merge_fields( $list_identifier, $arguments ),
				'interests'     => $this->build_interests( $list_identifier, $arguments ),
				'status_if_new' => $optin,
			);

			// Add custom fields to this request cuz it's sending the email twice on double optin
			if ( ! empty( $arguments['tve_mapping'] ) ) {
				// Append custom fields to existing ones
				$data['merge_fields'] = $this->buildMappedCustomFields( $list_identifier, $arguments, $data['merge_fields'] );
			}

			// On double optin, send the tags directly to the body [known problems on mailchimp tags endpoint]
			if ( isset( $arguments['mailchimp_optin'] ) && 'd' === $arguments['mailchimp_optin'] && ! empty( $arguments['mailchimp_tags'] ) ) {
				$tags         = explode( ',', $arguments['mailchimp_tags'] );
				$data['tags'] = $tags;
			}

			$member = $this->get_contact( $list_identifier, $email );

			if ( $member ) { //update contact
				$api->request( 'lists/' . $list_identifier . '/members/' . $user_hash, $data, 'PUT' );
			} else { //create contact
				$api->request( 'lists/' . $list_identifier . '/members', $data, 'POST' );
			}
		} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
			// mailchimp returns 404 if email contact already exists?
			//$e->getMessage() ? $e->getMessage() : __( 'Unknown Mailchimp Error', TVE_DASH_TRANSLATE_DOMAIN );
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', TVE_DASH_TRANSLATE_DOMAIN );
		}

		// Add tags for other optin beside double
		if ( ! empty( $arguments['mailchimp_tags'] ) ) {
			try {
				$tags = explode( ',', $arguments['mailchimp_tags'] );
				$this->addTagsToContact( $list_identifier, $email, $tags );
			} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
				return __( 'Assign tag error: ' . $e->getMessage(), TVE_DASH_TRANSLATE_DOMAIN );
			}
		}

		return true;
	}

	/**
	 * @param $params
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	protected function _getGroups( $params ) {

		$return    = array();
		$groupings = new stdClass();
		/** @var Thrive_Dash_Api_Mailchimp $api */
		$api   = $this->getApi();
		$lists = $api->request( 'lists', array( 'count' => 1000 ) );

		if ( empty( $params['list_id'] ) && ! empty( $lists ) ) {
			$params['list_id'] = $lists->lists[0]->id;
		}

		foreach ( $lists->lists as $list ) {
			if ( (string) $list->id === (string) $params['list_id'] ) {
				$groupings = $api->request( 'lists/' . $params['list_id'] . '/interest-categories', array( 'count' => 1000 ) );
			}
		}

		if ( $groupings->total_items > 0 ) {
			foreach ( $groupings->categories as $grouping ) {
				//if we have a grouping id in the params, we should only get that grouping
				if ( isset( $params['grouping_id'] ) && $grouping->id !== $params['grouping_id'] ) {
					continue;
				}
				$groups = $api->request( 'lists/' . $params['list_id'] . '/interest-categories/' . $grouping->id . '/interests', array( 'count' => 1000 ) );

				if ( $groups->total_items > 0 ) {
					$grouping->groups = $groups->interests;
				}
				$return[] = $grouping;
			}
		}

		return $return;
	}

	/**
	 * Makes a request through Mailchimp API for getting custom fields for a list
	 *
	 * @param string $list
	 *
	 * @return array|string
	 */
	public function getCustomFields( $list ) {

		try {
			/** @var Thrive_Dash_Api_Mailchimp $api */
			$api = $this->getApi();

			$query      = array(
				'count' => 1000,
			);
			$merge_vars = $api->request( 'lists/' . $list . '/merge-fields', $query );

			if ( 0 === $merge_vars->total_items ) {
				return array();
			}
		} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Mailchimp Error', 'thrive-dash' );
		} catch ( Exception $e ) {
			return $e->getMessage() ? $e->getMessage() : __( 'Unknown Error', 'thrive-dash' );
		}

		return $merge_vars->merge_fields;
	}

	/**
	 * @param $list_id
	 * @param $email_address
	 * @param $tags
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function addTagsToContact( $list_id, $email_address, $tags ) {
		if ( ! $list_id || ! $email_address || ! $tags ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception( __( 'Missing required parameters for adding tags to contact', TVE_DASH_TRANSLATE_DOMAIN ) );

			return false;
		}

		$list_tags = $this->getListTags( $list_id );

		if ( is_array( $tags ) ) {
			foreach ( $tags as $tag_name ) {

				if ( isset( $list_tags[ $tag_name ] ) ) {
					// Assign existing tag to contact/subscriber
					$tag_id = $list_tags[ $tag_name ]['id'];

					try {
						$this->assignTag( $list_id, $tag_id, $email_address );
					} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
						$this->_error = $e->getMessage() . ' ' . __( 'Please re-check your API connection details.', TVE_DASH_TRANSLATE_DOMAIN );
					}

					continue;
				}

				try {
					// Create tag and assign it to contact/subscriber
					$this->createAndAssignTag( $list_id, $tag_name, $email_address );
				} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
				}
			}
		}
	}

	/**
	 * Get all tags of a list
	 *
	 * @param $list_id
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function getListTags( $list_id ) {

		$segments_by_name = array();
		$count            = 100; //default is 10
		$offset           = 0;
		$total_items      = 0;

		do {
			/** @var Thrive_Dash_Api_Mailchimp $api */
			$api = $this->getApi();

			$response = $api->request(
				'lists/' . $list_id . '/segments',
				array(
					'count'  => $count,
					'offset' => $offset,
				),
				'GET',
				true
			);

			if ( is_object( $response ) && ( isset( $response->total_items ) && $response->total_items > 0 ) ) {
				$total_items = $response->total_items;

				if ( empty( $response->segments ) ) {
					break;
				}

				foreach ( $response->segments as $segment ) {
					$segments_by_name[ $segment->name ]['id']   = $segment->id;
					$segments_by_name[ $segment->name ]['name'] = $segment->name;
					$segments_by_name[ $segment->name ]['type'] = $segment->type;
				}

				$offset += $count;
			}
		} while ( count( $segments_by_name ) < $total_items );

		return $segments_by_name;
	}

	/**
	 * @param $list_id
	 * @param $tag_id
	 * @param $email_address
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function assignTag( $list_id, $tag_id, $email_address ) {

		if ( ! $list_id || ! $tag_id || ! $email_address ) {
			throw new Thrive_Dash_Api_Mailchimp_Exception( __( 'Missing required parameters for adding tags to contact', TVE_DASH_TRANSLATE_DOMAIN ) );

			return false;
		}

		$save_tag = $this->getApi()->request( 'lists/' . $list_id . '/segments/' . $tag_id . '/members', array( 'email_address' => $email_address ), 'POST' );

		if ( is_object( $save_tag ) && isset( $save_tag->id ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $list_id
	 * @param $tag_name
	 * @param $email_address
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function createAndAssignTag( $list_id, $tag_name, $email_address ) {
		if ( ! $list_id || ! $tag_name || ! $email_address ) {
			return false;
		}

		try {
			$created_tag = $this->createTag( $list_id, $tag_name, $email_address );
		} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
		}

		if ( is_object( $created_tag ) && isset( $created_tag->id ) ) {
			return $this->assignTag( $list_id, $created_tag->id, $email_address );
		}

		return false;
	}

	/**
	 * @param $list_id
	 * @param $tag_name
	 * @param $email_address
	 *
	 * @return bool|object
	 */
	public function createTag( $list_id, $tag_name, $email_address ) {
		if ( ! $list_id || ! $tag_name || ! $email_address ) {
			return false;
		}

		$tag = $this->getApi()->request( 'lists/' . $list_id . '/segments', array(
			'name'           => $tag_name,
			'static_segment' => array()
		), 'POST' );

		return $tag;
	}

	/**
	 * Allow the user to choose whether to have a single or a double optin for the form being edited
	 * It will hold the latest selected value in a cookie so that the user is presented by default with the same option selected the next time he edits such a form
	 *
	 * @param array $params
	 *
	 * @return array
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function get_extra_settings( $params = array() ) {
		$params['optin'] = empty( $params['optin'] ) ? ( isset( $_COOKIE['tve_api_mailchimp_optin'] ) ? $_COOKIE['tve_api_mailchimp_optin'] : 'd' ) : $params['optin'];
		setcookie( 'tve_api_mailchimp_optin', $params['optin'], strtotime( '+6 months' ), '/' );
		$groups           = $this->_getGroups( $params );
		$params['groups'] = $groups;

		return $params;
	}

	/**
	 * Extract the info we need for custom fields based on list_id or API's first list_id
	 *
	 * @param string $list_id
	 *
	 * @return array
	 */
	public function get_custom_fields_for_list( $list_id ) {

		$extract = array();

		if ( empty( $list_id ) ) {
			return $extract;
		}

		// Needed custom fields type
		$allowed_types = array(
			'text',
			'url',
		);
		$custom_fields = $this->getCustomFields( $list_id );

		if ( is_array( $custom_fields ) ) {
			foreach ( $custom_fields as $field ) {
				$field = (object) $field; // just making sure we work with objects [APIs can change the structure]

				if ( ! empty( $field->type ) && in_array( $field->type, $allowed_types, true ) && 1 === (int) $field->public ) {
					$extract[] = $this->normalize_custom_field( $field );
				}
			}
		}

		return $extract;
	}

	/**
	 * Get all custom fields for all lists
	 *
	 * @param int $force
	 *
	 * @return array
	 */
	public function getAllCustomFields( $force ) {

		$custom_data = array();

		// Serve from cache if exists and requested
		$cached_data = $this->_get_cached_custom_fields();
		if ( false === $force && ! empty( $cached_data ) ) {
			return $cached_data;
		}

		// Build custom fields for every list
		$lists = $this->getLists( $force );

		foreach ( $lists as $list ) {
			if ( ! empty( $list['id'] ) ) {
				$custom_data[ $list['id'] ] = $this->get_custom_fields_for_list( $list['id'] );
			}
		}

		$this->_save_custom_fields( $custom_data );

		return $custom_data;
	}

	/**
	 * Grab api custom fields
	 *
	 * @param      $params
	 * @param bool $force
	 * @param bool $get_all
	 *
	 * @return array|mixed
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = false ) {

		$lists = $this->getAllCustomFields( $force );

		// Get custom fields for all list ids [used on localize in TAr]
		if ( true === $get_all ) {
			return $lists;
		}

		$list_id = isset( $params['list_id'] ) ? $params['list_id'] : null;

		if ( '0' === $list_id ) {
			$list_id = current( array_keys( $lists ) );
		}

		$fields = array(
			$list_id => $lists[ $list_id ],
		);

		return $fields;
	}

	/**
	 * Allow the user to choose whether to have a single or a double optin for the form being edited
	 * It will hold the latest selected value in a cookie so that the user is presented by default with the same option selected the next time he edits such a form
	 *
	 * @param array $params
	 *
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		$params['optin'] = empty( $params['optin'] ) ? ( isset( $_COOKIE['tve_api_mailchimp_optin'] ) ? $_COOKIE['tve_api_mailchimp_optin'] : 'd' ) : $params['optin'];
		setcookie( 'tve_api_mailchimp_optin', $params['optin'], strtotime( '+6 months' ), '/' );
		$groups           = $this->_getGroups( $params );
		$params['groups'] = $groups;
		$this->_directFormHtml( 'mailchimp/api-groups', $params );
		$this->_directFormHtml( 'mailchimp/optin-type', $params );
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed|Thrive_Dash_Api_Mailchimp
	 * @throws Thrive_Dash_Api_Mailchimp_Exception
	 */
	protected function _apiInstance() {
		return new Thrive_Dash_Api_Mailchimp( $this->param( 'key' ) );
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool
	 */
	protected function _getLists() {

		try {
			/** @var Thrive_Dash_Api_Mailchimp $mc */
			$mc = $this->getApi();

			$raw   = $mc->request( 'lists', array( 'count' => 1000 ) );
			$lists = array();

			if ( empty( $raw->total_items ) || empty( $raw->lists ) ) {
				return array();
			}
			foreach ( $raw->lists as $item ) {

				$lists [] = array(
					'id'   => $item->id,
					'name' => $item->name,
				);
			}

			return $lists;
		} catch ( Thrive_Dash_Api_Mailchimp_Exception $e ) {
			$this->_error = $e->getMessage() . ' ' . __( 'Please re-check your API connection details.', TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}
	}

	/**
	 * Makes an API request for an email into a specific list
	 *
	 * @param string $list_id
	 * @param string $email
	 *
	 * @return strClass|null contact if exists
	 */
	public function get_contact( $list_id, $email ) {

		/** @var Thrive_Dash_Api_Mailchimp $api */
		$api     = $this->getApi();
		$contact = null;
		try {
			$contact = $api->request( 'lists/' . $list_id . '/members/' . md5( $email ) );
		} catch ( Exception $e ) {

		}

		return $contact;
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
	 * @param array $field
	 *
	 * @return array
	 */
	protected function normalize_custom_field( $field ) {

		$field = (object) $field;

		return array(
			'id'    => isset( $field->merge_id ) ? $field->merge_id : '',
			'name'  => ! empty( $field->tag ) ? $field->tag : '',
			'type'  => ! empty( $field->type ) ? $field->type : '',
			'label' => ! empty( $field->name ) ? $field->name : '',
		);
	}
}
