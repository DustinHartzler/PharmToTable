<?php

/**
 * Class Thrive_Dash_List_Connection_Abstract
 *
 * base class for all connections
 * acts as an high-level interface for the main functionalities exposed by the system
 */
abstract class Thrive_Dash_List_Connection_Abstract {
	/**
	 * @var array connection details (used for API calls)
	 */
	protected $_credentials = array();

	/**
	 * @var string internal key for the connection api
	 */
	protected $_key = null;

	/**
	 * @var mixed
	 */
	protected $_api;

	/**
	 * error message to be displayed in the editor
	 *
	 * @var string
	 */
	protected $_error = '';

	/**
	 * Transient name for API custom fields
	 *
	 * @var string
	 */
	public $_custom_fields_transient = '';

	/**
	 * Allowed custom fields for APIs that supports them
	 *
	 * @var array
	 */
	protected $_mapped_custom_fields = array();

	/**
	 * @param string $key
	 */
	public function __construct( $key ) {

		$this->_key                     = $key;
		$this->_custom_fields_transient = 'api_custom_fields_' . $key;
		$this->_setMappingCustomFields();
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
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		return '[email]';
	}

	/**
	 * get the API Connection code to use in calls
	 *
	 * @return mixed
	 */
	public function getApi() {
		if ( ! isset( $this->_api ) ) {
			$this->_api = $this->_apiInstance();
		}

		return $this->_api;
	}

	/**
	 * @return array
	 */
	public function getCredentials() {
		return $this->_credentials;
	}

	/**
	 * @param array $connectionDetails
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function setCredentials( $connectionDetails ) {
		$this->_credentials = $connectionDetails;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->_key;
	}

	/**
	 * @param string $key
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function setKey( $key ) {
		$this->_key = $key;

		return $this;
	}

	/**
	 * whether or not this list is connected to the service (has been authenticated)
	 *
	 * @return bool
	 */
	public function isConnected() {
		return ! empty( $this->_credentials );
	}

	/**
	 * @return bool
	 */
	public function isRelated() {
		return false;
	}

	/**
	 * get connection parameter by name
	 *
	 * @param string $field
	 * @param string $default
	 *
	 * @return mixed
	 */
	public function param( $field, $default = null ) {
		return isset( $this->_credentials[ $field ] ) ? $this->_credentials[ $field ] : $default;
	}

	/**
	 * set connection parameter
	 *
	 * @param string $field
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function setParam( $field, $value ) {
		$this->_credentials[ $field ] = $value;

		return $this;
	}

	/**
	 * setup a global error message
	 *
	 * @param string $message
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function error( $message ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $message;
		}

		return $this->_message( 'error', $message );
	}

	/**
	 * setup a global success message
	 *
	 * @param string $message
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public function success( $message ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}

		return $this->_message( 'success', $message );
	}

	/**
	 * save the connection details
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 * @see Thrive_Dash_List_Manager
	 *
	 */
	public function save() {
		Thrive_Dash_List_Manager::save( $this );

		return $this;
	}

	/**
	 * disconnect (remove) this API connection
	 */
	public function disconnect() {
		$this->beforeDisconnect();
		$this->setCredentials( array() );
		Thrive_Dash_List_Manager::save( $this );

		return $this;
	}

	/**
	 * Actions to take before a disconnection (can be overwritten by any API connection for different purposes)
	 *
	 * @return $this
	 */
	public function beforeDisconnect() {

		delete_transient( $this->_custom_fields_transient );

		return $this;
	}

	/**
	 * get the last error message in communicating with the api
	 *
	 * @return string the error message
	 */
	public function getApiError() {
		return $this->_error;
	}

	/**
	 * @return string the API connection title
	 */
	public abstract function getTitle();

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public abstract function outputSetupForm();

	/**
	 * should handle: read data from post / get, test connection and save the details
	 *
	 * on error return the error message
	 *
	 * on success return true
	 *
	 * @return mixed
	 */
	public abstract function readCredentials();

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public abstract function testConnection();

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public abstract function addSubscriber( $list_identifier, $arguments );

	/**
	 * get all Subscriber Lists from this API service
	 * it will first check a local cache for the existing lists
	 *
	 * @param bool $use_cache if true, it will read the lists from a local cache (wp options)
	 *
	 * @return array|bool for error
	 * @see self::_getLists()
	 */
	public function getLists( $use_cache = true ) {
		if ( ! $this->isConnected() ) {
			$this->_error = $this->getTitle() . ' ' . __( 'is not connected', TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}
		$cache = get_option( 'thrive_auto_responder_lists', array() );
		if ( ! $use_cache || ! isset( $cache[ $this->getKey() ] ) ) {
			$lists = $this->_getLists();
			if ( $lists !== false ) {
				$cache[ $this->getKey() ] = $lists;
				update_option( 'thrive_auto_responder_lists', $cache );
			}
		} else {
			$lists = $cache[ $this->getKey() ];
		}

		return $lists;
	}

	/**
	 * Check connection and get all groups for a specific list
	 *
	 * @param $list_id
	 *
	 * @return bool
	 */
	public function getGroups( $list_id ) {
		if ( ! $this->isConnected() ) {
			$this->_error = $this->getTitle() . ' ' . __( 'is not connected', TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}

		$params['list_id'] = $list_id;

		return $this->_getGroups( $params );
	}

	/**
	 * if an API instance has a special way of designating the list, it should override this method
	 * e.g. "Choose the mailing list you want your subscribers to be assigned to"
	 *
	 * @return string
	 */
	public function getListSubtitle() {
		return '';
	}

	/**
	 * get an array of warning messages (e.g. The access token will expire in xxx days. Click here to renew it)
	 *
	 * @return array
	 */
	public function getWarnings() {
		return array();
	}

	/**
	 * output any (possible) extra editor settings for this API
	 *
	 * @param array $params allow various different calls to this method
	 *
	 * @return array
	 */
	public function get_extra_settings( $params = array() ) {
		do_action( 'tvd_autoresponder_render_extra_editor_settings_' . $this->getKey() );

		return array();
	}

	/**
	 * output any (possible) extra editor settings for this API
	 *
	 * @param array $params allow various different calls to this method
	 */
	public function renderExtraEditorSettings( $params = array() ) {
		do_action( 'tvd_autoresponder_render_extra_editor_settings_' . $this->getKey() );

		return;
	}

	public function renderBeforeListsSettings( $params = array() ) {
		return;
	}

	public function getLogoUrl() {
		return TVE_DASH_URL . '/inc/auto-responder/views/images/' . $this->getKey() . '.png';
	}

	public function prepareJSON() {
		$properties = array(
			'key'             => $this->getKey(),
			'connected'       => $this->isConnected(),
			'credentials'     => $this->getCredentials(),
			'title'           => $this->getTitle(),
			'type'            => $this->getType(),
			'logoUrl'         => $this->getLogoUrl(),
			'success_message' => $this->customSuccessMessage(),
		);

		$properties['notification'] = TVE_Dash_InboxManager::instance()->get_by_slug( $this->getKey() );

		return $properties;
	}

	/**
	 * Custom message for success state
	 *
	 * @return string
	 */
	public function customSuccessMessage() {
		return '';
	}

	/**
	 * Can be overwritten by any api who needs custom data to be sent
	 *
	 * @param      $params
	 * @param bool $force
	 * @param bool $get_all
	 *
	 * @return array|mixed
	 */
	public function get_api_custom_fields( $params, $force = false, $get_all = false ) {

		$cache_data = get_transient( $this->_custom_fields_transient );

		return $cache_data ? $cache_data : array();
	}

	/**
	 * Get extra parameters or fields from apis.
	 *
	 * @param $func
	 * @param $params
	 *
	 * @return mixed
	 */
	public function get_api_extra( $func, $params ) {

		$extra = array();

		if ( method_exists( $this, $func ) ) {
			$extra = call_user_func_array( array( $this, $func ), array( $params ) );
		}

		return array(
			'extra'             => $extra,
			'api_custom_fields' => $this->get_api_custom_fields( $params ),
		);
	}

	/**
	 * Return an array with the lists, custom fields and extra settings
	 *
	 * @param array $params
	 * @param int   $force
	 *
	 * @return array
	 */
	public function get_api_data( $params = array(), $force = false ) {

		$transient = 'tve_api_data_' . $this->getKey();
		$data      = get_transient( $transient );

		if ( false === $force && tve_dash_is_debug_on() ) {
			$force = true;
		}

		if ( true === $force || false === $data ) {
			$data = array(
				'lists'          => $this->getLists( false ),
				'extra_settings' => $this->get_extra_settings( $params ),
				'custom_fields'  => $this->get_custom_fields( $params ),
			);

			set_transient( $transient, $data, MONTH_IN_SECONDS );
		}

		$data['api_custom_fields'] = $this->get_api_custom_fields( $params, $force );

		return $data;
	}

	/**
	 * Get API custom form fields. By default we have only name and phone
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_custom_fields( $params = array() ) {
		return array(
			array( 'id' => 'name', 'placeholder' => __( 'Name', TVE_DASH_TRANSLATE_DOMAIN ) ),
			array( 'id' => 'phone', 'placeholder' => __( 'Phone', TVE_DASH_TRANSLATE_DOMAIN ) ),
		);
	}

	/**
	 * output directly the html for a connection form from views/setup
	 *
	 * @param string $filename
	 * @param array  $data allows passing variables to the view file
	 */
	protected function _directFormHtml( $filename, $data = array() ) {
		include dirname( dirname( dirname( __FILE__ ) ) ) . '/views/setup/' . $filename . '.php';
	}

	/**
	 * @param $type
	 * @param $message
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	protected function _message( $type, $message ) {
		Thrive_Dash_List_Manager::message( $type, $message );

		return $this;
	}

	/**
	 * explode fullname into first name and last name
	 *
	 * @param string $full_name
	 *
	 * @return array
	 */
	protected function _getNameParts( $full_name ) {
		if ( empty( $full_name ) ) {
			return array( '', '' );
		}
		$parts = explode( ' ', $full_name );

		if ( count( $parts ) == 1 ) {
			return array(
				$parts[0],
				'',
			);
		}
		$last_name  = array_pop( $parts );
		$first_name = implode( ' ', $parts );

		return array(
			sanitize_text_field( $first_name ),
			sanitize_text_field( $last_name ),
		);
	}

	/**
	 * Compose name from email
	 *
	 * @param $email
	 *
	 * @return array
	 */
	protected function _getNameFromEmail( $email ) {

		if ( empty( $email ) || ! is_string( $email ) || false === strpos( $email, '@' ) ) {
			return array( '', '' );
		}

		$email_name = str_replace( array( '.', '_', '-', '+', '=' ), ' ', strstr( $email, '@', true ) );

		list( $first_name, $last_name ) = $this->_getNameParts( $email_name );

		if ( empty( $first_name ) ) {
			$first_name = $email_name;
		}

		if ( empty( $last_name ) ) {
			$last_name = $first_name;
		}

		return array(
			$first_name,
			$last_name,
		);
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected abstract function _apiInstance();

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool for error
	 */
	protected abstract function _getLists();

	/**
	 * Get API Videos URLs
	 *
	 * @return array
	 */
	public function apiVideosUrls() {

		$return    = array();
		$transient = get_transient( 'ttw_api_urls' );

		if ( ! empty( $transient ) && is_array( $transient ) ) {
			$return = (array) $transient;
		}

		return $return;
	}

	/**
	 * Displays the video link with his html structure
	 *
	 * @return mixed|string
	 */
	public function display_video_link() {

		$api_slug   = strtolower( str_replace( array( ' ', '-' ), '', $this->getKey() ) );
		$video_urls = $this->apiVideosUrls();
		if ( ! array_key_exists( $api_slug, $video_urls ) ) {
			return '';
		}

		return include dirname( dirname( dirname( __FILE__ ) ) ) . '/views/includes/video-link.php';
	}

	/**
	 * A connection can work with different versions of API
	 *
	 * @return false|mixed
	 */
	public function get_version() {

		$credentials = (array) $this->getCredentials();

		if ( ! empty( $credentials['version'] ) ) {
			return $credentials['version'];
		}

		return false;
	}

	/**
	 * Defines a common field structure to work with
	 *
	 * @param array $data Field data from the API
	 *
	 * @return array
	 */
	protected function normalize_custom_field( $data ) {

		return array(
			'id'    => $data['id'], //unique identifier
			'name'  => $data['name'], //should be name="" attribute for an input
			'type'  => $data['type'], //type for e.g. [url, text]
			'label' => $data['label'], //label to display for users
		);
	}

	/**
	 * Get the value of from email for email delivery services
	 *
	 * @return string
	 */
	public function get_email_param() {
		return $this->param( 'email', get_option( 'admin_email' ) );
	}

	/**
	 * Insert message to API error log
	 *
	 * @param string|int $list_identifier
	 * @param array      $data
	 * @param string     $error
	 *
	 * @return bool
	 */
	public function api_log_error( $list_identifier, $data, $error ) {

		if ( ! $list_identifier || ! $data || ! $error ) {
			return false;
		}

		global $wpdb;

		$return        = false;
		$api_log_table = $wpdb->prefix . 'tcb_api_error_log';
		$table_exists  = ! ! $wpdb->get_var( $wpdb->prepare( 'SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=%s', $api_log_table ) );

		if ( $table_exists ) {
			$log_data = array(
				'date'          => date( 'Y-m-d H:i:s' ),
				'error_message' => tve_sanitize_data_recursive( $error ),
				'api_data'      => serialize( tve_sanitize_data_recursive( $data ) ),
				'connection'    => $this->getKey(),
				'list_id'       => maybe_serialize( tve_sanitize_data_recursive( $list_identifier ) ),
			);

			$return = (bool) $wpdb->insert( $api_log_table, $log_data );
		}

		return $return;
	}

	/**
	 * Mapped custom fields setter
	 */
	protected function _setMappingCustomFields() {

		$this->_mapped_custom_fields = apply_filters(
			'tve_dash_mapped_custom_fields',
			array(
				array(
					'id'          => 'mapping_text',
					'placeholder' => __( 'Text', TVE_DASH_TRANSLATE_DOMAIN ),
				),
				array(
					'id'          => 'mapping_url',
					'placeholder' => __( 'URL', TVE_DASH_TRANSLATE_DOMAIN ),
				),
				array(
					'id'          => 'mapping_radio',
					'placeholder' => __( 'Radio', TVE_DASH_TRANSLATE_DOMAIN ),
				),
				array(
					'id'          => 'mapping_select',
					'placeholder' => __( 'Dropdown', TVE_DASH_TRANSLATE_DOMAIN ),
				),
				array(
					'id'          => 'mapping_checkbox',
					'placeholder' => __( 'Checkbox', TVE_DASH_TRANSLATE_DOMAIN ),
				),
				array(
					'id'          => 'mapping_textarea',
					'placeholder' => __( 'Textarea', TVE_DASH_TRANSLATE_DOMAIN ),
				),
				array(
					'id'          => 'mapping_file',
					'placeholder' => __( 'File upload', TVE_DASH_TRANSLATE_DOMAIN ),
				),
				array(
					'id'          => 'mapping_hidden',
					'placeholder' => __( 'Hidden', TVE_DASH_TRANSLATE_DOMAIN ),
				),
			)
		);
	}

	/**
	 * Global getter for the hardcoded allowed custom fields type
	 *
	 * @return array
	 */
	public function getMappedCustomFields() {

		return $this->_mapped_custom_fields;
	}

	/**
	 * Custom fields cache getter
	 *
	 * @return mixed
	 */
	protected function _get_cached_custom_fields() {

		return get_transient( $this->_custom_fields_transient );
	}

	/**
	 * Save custom fields
	 *
	 * @param array $custom_fields
	 *
	 * @return bool
	 */
	protected function _save_custom_fields( $custom_fields = array() ) {

		if ( empty( $custom_fields ) ) {
			return false;
		}

		$custom_fields = tve_sanitize_data_recursive( $custom_fields );

		return set_transient( $this->_custom_fields_transient, $custom_fields );
	}

	public function processField( $field ) {
		if ( is_array( $field ) ) {
			$field = join( ", ", $field );
		}

		return stripslashes( $field );
	}

	/**
	 * Whether or not the integration supports tags
	 *
	 * @return bool
	 */
	public function hasTags() {

		return false;
	}

	/**
	 * Get tags key for the api
	 *
	 * @return string
	 */
	protected function getTagsKey() {

		return $this->_key . '_tags';
	}

	/**
	 * Get tags key for the api
	 *
	 * @return array
	 */
	protected function getMappedFieldsIDs() {

		$mapped_fields = array_map(
			function ( $field ) {
				return $field['id'];
			},
			$this->_mapped_custom_fields
		);

		array_push( $mapped_fields, 'user_consent' );

		return $mapped_fields;
	}

	/**
	 * Push external tags in $data, EX: adds tags from tqb
	 *
	 * @param array|string $tags
	 * @param array        $data
	 *
	 * @return array
	 */
	public function pushTags( $tags, $data = array() ) {

		if ( ! $this->hasTags() && ( ! is_array( $tags ) || ! is_string( $tags ) ) ) {
			return $data;
		}

		$_key = $this->getTagsKey();

		if ( ! isset( $data[ $_key ] ) ) {
			$data[ $_key ] = '';
		}

		if ( is_array( $tags ) ) {
			$tags = implode( ', ', $tags );
		}

		$data[ $_key ] = empty( $data[ $_key ] )
			? $tags
			: $data[ $_key ] . ', ' . $tags;

		$data[ $_key ] = trim( $data[ $_key ] );

		return $data;
	}
}

