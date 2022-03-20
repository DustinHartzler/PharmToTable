<?php
/**
 * ConvertKit API class.
 *
 * @package CKWC
 * @author ConvertKit
 */

/**
 * ConvertKit API class
 *
 * @package CKWC
 * @author ConvertKit
 */
class CKWC_API {

	/**
	 * ConvertKit API Key
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * ConvertKit API Secret
	 *
	 * @var string
	 */
	protected $api_secret;

	/**
	 * Save debug data to log
	 *
	 * @var  string
	 */
	protected $debug;

	/**
	 * Version of ConvertKit API
	 *
	 * @var string
	 */
	protected $api_version = 'v3';

	/**
	 * ConvertKit API URL
	 *
	 * @var string
	 */
	protected $api_url_base = 'https://api.convertkit.com/';

	/**
	 * Holds the log class for writing to the log file
	 *
	 * @var     WC_Logger
	 */
	private $log;

	/**
	 * Sets up the API with the required credentials.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $api_key        ConvertKit API Key.
	 * @param   string $api_secret     ConvertKit API Secret.
	 * @param   string $debug          Save data to log.
	 */
	public function __construct( $api_key = false, $api_secret = false, $debug = false ) {

		// Set API credentials and debugging.
		$this->api_key    = $api_key;
		$this->api_secret = $api_secret;
		$this->debug      = $debug;
		$this->log        = new WC_Logger();

	}

	/**
	 * Gets account information from the API.
	 *
	 * @since   1.4.2
	 *
	 * @return  mixed   WP_Error | array
	 */
	public function account() {

		$this->log( 'API: account()' );

		return $this->get(
			'account',
			array(
				'api_secret' => $this->api_secret,
			)
		);

	}

	/**
	 * Gets all subscription forms from the API.
	 *
	 * @since   1.4.2
	 *
	 * @return  mixed   WP_Error | array
	 */
	public function get_subscription_forms() {

		$this->log( 'API: get_subscription_forms()' );

		// Send request.
		return $this->get(
			'subscription_forms',
			array(
				'api_key' => $this->api_key,
			)
		);

	}

	/**
	 * Gets all forms from the API.
	 *
	 * @since   1.4.2
	 *
	 * @return  mixed   WP_Error | array
	 */
	public function get_forms() {

		$this->log( 'API: get_forms()' );

		// Get all forms and landing pages from the API.
		$forms = $this->get_forms_landing_pages();

		// If an error occured, log and return it now.
		if ( is_wp_error( $forms ) ) {
			$this->log( 'API: get_forms(): Error: ' . $forms->get_error_message() );
			return $forms;
		}

		return $forms['forms'];

	}

	/**
	 * Subscribes an email address to a form.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $form_id    Form ID.
	 * @param   string $email      Email Address.
	 * @param   string $first_name First Name.
	 * @param   mixed  $fields     Custom Fields (false|array).
	 * @return  mixed               WP_Error | array
	 */
	public function form_subscribe( $form_id, $email, $first_name = '', $fields = false ) {

		// Backward compat. if $email is an array comprising of email and name keys.
		if ( is_array( $email ) ) {
			_deprecated_function( __FUNCTION__, '1.4.2', 'form_subscribe( $form_id, $email, $first_name )' );
			$first_name = $email['name'];
			$email      = $email['email'];
		}

		$this->log( 'API: form_subscribe(): [ form_id: ' . $form_id . ', email: ' . $email . ', first_name: ' . $first_name . ' ]' );

		// Sanitize some parameters.
		$form_id    = trim( $form_id );
		$email      = trim( $email );
		$first_name = trim( $first_name );

		// Return error if no Form ID or email address is specified.
		if ( empty( $form_id ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'form_subscribe(): the form_id parameter is empty.', 'woocommerce-convertkit' ) );
		}
		if ( empty( $email ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'form_subscribe(): the email parameter is empty.', 'woocommerce-convertkit' ) );
		}

		// Build request parameters.
		$params = array(
			'api_key'    => $this->api_key,
			'email'      => $email,
			'first_name' => $first_name,
		);
		if ( $fields ) {
			$params['fields'] = $fields;
		}

		// Send request.
		$response = $this->post( 'forms/' . $form_id . '/subscribe', $params );

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: form_subscribe(): Error: ' . $response->get_error_message() );
			return $response;
		}

		/**
		 * Runs actions immediately after the email address was successfully subscribed to the form.
		 *
		 * @since   1.4.2
		 *
		 * @param   array   $response   API Response
		 * @param   string  $form_id    Form ID
		 * @param   string  $email      Email Address
		 * @param   string  $first_name First Name
		 * @param   mixed   $fields     Custom Fields (false|array)
		 */
		do_action( 'convertkit_api_form_subscribe_success', $response, $form_id, $email, $first_name, $fields );

		return $response;

	}

	/**
	 * Gets all landing pages from the API.
	 *
	 * @since   1.4.2
	 *
	 * @return  mixed   WP_Error | array
	 */
	public function get_landing_pages() {

		$this->log( 'API: get_landing_pages()' );

		// Get all forms and landing pages from the API.
		$forms = $this->get_forms_landing_pages();

		// If an error occured, log and return it now.
		if ( is_wp_error( $forms ) ) {
			$this->log( 'API: get_landing_pages(): Error: ' . $forms->get_error_message() );
			return $forms;
		}

		return $forms['landing_pages'];

	}

	/**
	 * Fetches all sequences from the API.
	 *
	 * @since   1.4.2
	 *
	 * @return  mixed   WP_Error | array
	 */
	public function get_sequences() {

		$this->log( 'API: get_sequences()' );

		$sequences = array();

		// Send request.
		$response = $this->get(
			'sequences',
			array(
				'api_key' => $this->api_key,
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: get_sequences(): Error: ' . $response->get_error_message() );
			return $response;
		}

		// If no sequences exist, return WP_Error.
		if ( ! isset( $response['courses'] ) ) {
			$this->log( 'API: get_sequences(): Error: No sequences exist in ConvertKit.', 'woocommerce-convertkit' );
			return new WP_Error( 'convertkit_api_error', __( 'No sequences exist in ConvertKit. Visit your ConvertKit account and create your first sequence.', 'woocommerce-convertkit' ) );
		}
		if ( ! count( $response['courses'] ) ) {
			$this->log( 'API: get_sequences(): Error: No sequences exist in ConvertKit.', 'woocommerce-convertkit' );
			return new WP_Error( 'convertkit_api_error', __( 'No sequences exist in ConvertKit. Visit your ConvertKit account and create your first sequence.', 'woocommerce-convertkit' ) );
		}

		foreach ( $response['courses'] as $sequence ) {
			$sequences[] = $sequence;
		}

		return $sequences;

	}

	/**
	 * Subscribes an email address to a sequence.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $sequence_id Sequence ID.
	 * @param   string $email       Email Address.
	 * @param   string $first_name  First Name.
	 * @param   mixed  $fields      Custom Fields (false|array).
	 * @return  mixed               WP_Error | array
	 */
	public function sequence_subscribe( $sequence_id, $email, $first_name = '', $fields = false ) {

		$this->log( 'API: sequence_subscribe(): [ sequence_id: ' . $sequence_id . ', email: ' . $email . ']' );

		// Sanitize some parameters.
		$sequence_id = trim( $sequence_id );
		$email       = trim( $email );
		$first_name  = trim( $first_name );

		// Return error if no Sequence ID or email address is specified.
		if ( empty( $sequence_id ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'sequence_subscribe(): the sequence_id parameter is empty.', 'woocommerce-convertkit' ) );
		}
		if ( empty( $email ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'sequence_subscribe(): the email parameter is empty.', 'woocommerce-convertkit' ) );
		}

		// Build request parameters.
		$params = array(
			'api_key'    => $this->api_key,
			'email'      => $email,
			'first_name' => $first_name,
		);
		if ( $fields ) {
			$params['fields'] = $fields;
		}

		// Send request.
		$response = $this->post( 'sequences/' . $sequence_id . '/subscribe', $params );

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: sequence_subscribe(): Error: ' . $response->get_error_message() );
			return $response;
		}

		/**
		 * Runs actions immediately after the email address was successfully subscribed to the sequence.
		 *
		 * @since   1.4.2
		 *
		 * @param   array   $response       API Response
		 * @param   string  $sequence_id    Sequence ID
		 * @param   string  $email          Email Address
		 * @param   mixed   $fields         Custom Fields (false|array)
		 */
		do_action( 'convertkit_api_sequence_subscribe_success', $response, $sequence_id, $email, $fields );

		return $response;

	}

	/**
	 * Fetches all tags from the API.
	 *
	 * @since   1.4.2
	 *
	 * @return  mixed   WP_Error | array
	 */
	public function get_tags() {

		$this->log( 'API: get_tags()' );

		$tags = array();

		// Send request.
		$response = $this->get(
			'tags',
			array(
				'api_key' => $this->api_key,
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: get_tags(): Error: ' . $response->get_error_message() );
			return $response;
		}

		// If no tags exist, return WP_Error.
		if ( ! isset( $response['tags'] ) ) {
			$this->log( 'API: get_tags(): Error: No tags exist in ConvertKit.', 'woocommerce-convertkit' );
			return new WP_Error( 'convertkit_api_error', __( 'No tags exist in ConvertKit. Visit your ConvertKit account and create your first tag.', 'woocommerce-convertkit' ) );
		}
		if ( ! count( $response['tags'] ) ) {
			$this->log( 'API: get_tags(): Error: No tags exist in ConvertKit.', 'woocommerce-convertkit' );
			return new WP_Error( 'convertkit_api_error', __( 'No tags exist in ConvertKit. Visit your ConvertKit account and create your first tag.', 'woocommerce-convertkit' ) );
		}

		foreach ( $response['tags'] as $tag ) {
			$tags[] = $tag;
		}

		return $tags;

	}

	/**
	 * Subscribes an email address to a tag.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $tag_id     Tag ID.
	 * @param   string $email      Email Address.
	 * @param   string $first_name First Name.
	 * @param   mixed  $fields     Custom Fields (false|array).
	 * @return  mixed               WP_Error | array
	 */
	public function tag_subscribe( $tag_id, $email, $first_name = '', $fields = false ) {

		$this->log( 'API: tag_subscribe(): [ tag_id: ' . $tag_id . ', email: ' . $email . ']' );

		// Sanitize some parameters.
		$tag_id     = trim( $tag_id );
		$email      = trim( $email );
		$first_name = trim( $first_name );

		// Return error if no Tag ID or email address is specified.
		if ( empty( $tag_id ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'tag_subscribe(): the tag_id parameter is empty.', 'woocommerce-convertkit' ) );
		}
		if ( empty( $email ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'tag_subscribe(): the email parameter is empty.', 'woocommerce-convertkit' ) );
		}

		// Build request parameters.
		$params = array(
			'api_key'    => $this->api_key,
			'email'      => $email,
			'first_name' => $first_name,
		);
		if ( $fields ) {
			$params['fields'] = $fields;
		}

		// Send request.
		$response = $this->post( 'tags/' . $tag_id . '/subscribe', $params );

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: tag_subscribe(): Error: ' . $response->get_error_message() );
			return $response;
		}

		/**
		 * Runs actions immediately after the email address was successfully subscribed to the tag.
		 *
		 * @since   1.4.2
		 *
		 * @param   array   $response   API Response
		 * @param   string  $tag_id     Tag ID
		 * @param   string  $email      Email Address
		 * @param   mixed   $fields     Custom Fields (false|array).
		 */
		do_action( 'convertkit_api_tag_subscribe_success', $response, $tag_id, $email, $fields );

		return $response;

	}

	/**
	 * Gets a subscriber by their email address.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $email  Email Address.
	 * @return  mixed           WP_Error | array
	 */
	public function get_subscriber_by_email( $email ) {

		$this->log( 'API: get_subscriber_by_email(): [ email: ' . $email . ']' );

		// Sanitize some parameters.
		$email = trim( $email );

		// Return error if email address is specified.
		if ( empty( $email ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'get_subscriber_by_email(): the email parameter is empty.', 'woocommerce-convertkit' ) );
		}

		// Send request.
		$response = $this->get(
			'subscribers',
			array(
				'api_secret'    => $this->api_secret,
				'email_address' => $email,
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: get_subscriber_by_email(): Error: ' . $response->get_error_message() );
			return $response;
		}

		// If no subscribers exist, return WP_Error.
		if ( ! absint( $response['total_subscribers'] ) ) {
			$error = new WP_Error(
				'convertkit_api_error',
				sprintf(
					/* translators: Email Address */
					__( 'No subscriber(s) exist in ConvertKit matching the email address %s.', 'woocommerce-convertkit' ),
					$email
				)
			);

			$this->log( 'API: get_subscriber_by_email(): Error: ' . $error->get_error_message() );

			return $error;
		}

		return $response['subscribers'][0];

	}

	/**
	 * Gets a subscriber by their ConvertKit subscriber ID.
	 *
	 * @since   1.4.2
	 *
	 * @param   int $subscriber_id  Subscriber ID.
	 * @return  mixed                   WP_Error | array
	 */
	public function get_subscriber_by_id( $subscriber_id ) {

		$this->log( 'API: get_subscriber_by_id(): [ subscriber_id: ' . $subscriber_id . ']' );

		// Sanitize some parameters.
		$subscriber_id = trim( $subscriber_id );

		// Return error if no Subscriber ID is specified.
		if ( empty( $subscriber_id ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'get_subscriber_by_id(): the subscriber_id parameter is empty.', 'woocommerce-convertkit' ) );
		}

		// Send request.
		$response = $this->get(
			'subscribers/' . $subscriber_id,
			array(
				'api_secret' => $this->api_secret,
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: get_subscriber_by_id(): Error: ' . $response->get_error_message() );
			return $response;
		}

		// If no subscriber exists, return WP_Error.
		if ( ! isset( $response['subscriber'] ) ) {
			$error = new WP_Error(
				'convertkit_api_error',
				sprintf(
					/* translators: Subscriber ID */
					__( 'No subscriber exist in ConvertKit matching the subscriber ID %s.', 'woocommerce-convertkit' ),
					$subscriber_id
				)
			);

			$this->log( 'API: get_subscriber_by_id(): Error: ' . $error->get_error_message() );

			return $error;
		}

		return $response['subscriber'];

	}

	/**
	 * Gets a list of tags for the given ConvertKit subscriber ID.
	 *
	 * @since   1.4.2
	 *
	 * @param   int $subscriber_id  Subscriber ID.
	 * @return  mixed                   WP_Error | array
	 */
	public function get_subscriber_tags( $subscriber_id ) {

		$this->log( 'API: get_subscriber_tags(): [ subscriber_id: ' . $subscriber_id . ']' );

		// Sanitize some parameters.
		$subscriber_id = trim( $subscriber_id );

		// Return error if no Subscriber ID is specified.
		if ( empty( $subscriber_id ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'get_subscriber_tags(): the subscriber_id parameter is empty.', 'woocommerce-convertkit' ) );
		}

		// Send request.
		$response = $this->get(
			'subscribers/' . $subscriber_id . '/tags',
			array(
				'api_key' => $this->api_key,
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: get_subscriber_tags(): Error: ' . $response->get_error_message() );
			return $response;
		}

		// If no tags exists, return WP_Error.
		if ( ! isset( $response['tags'] ) ) {
			$error = new WP_Error(
				'convertkit_api_error',
				sprintf(
					/* translators: Subscriber ID */
					__( 'No tags exist in ConvertKit for the subscriber ID %s.', 'woocommerce-convertkit' ),
					$subscriber_id
				)
			);

			$this->log( 'API: get_subscriber_tags(): Error: ' . $error->get_error_message() );

			return $error;
		}

		return $response['tags'];

	}

	/**
	 * Returns the subscriber's ID by their email address.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $email_address  Email Address.
	 * @return  mixed                   WP_Error | int
	 */
	public function get_subscriber_id( $email_address ) {

		// Get subscriber.
		$subscriber = $this->get_subscriber_by_email( $email_address );

		// If an error occured, log and return it now.
		if ( is_wp_error( $subscriber ) ) {
			return $subscriber;
		}

		// Return ID.
		return $subscriber['id'];

	}

	/**
	 * Unsubscribes an email address.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $email      Email Address.
	 * @return  mixed               WP_Error | array
	 */
	public function unsubscribe( $email ) {

		$this->log( 'API: unsubscribe(): [ email: ' . $email . ']' );

		// Sanitize some parameters.
		$email = trim( $email );

		// Return error if no email address is specified.
		if ( empty( $email ) ) {
			return new WP_Error( 'convertkit_api_error', __( 'unsubscribe(): the email parameter is empty.', 'woocommerce-convertkit' ) );
		}

		// Send request.
		$response = $this->post(
			'unsubscribe',
			array(
				'api_secret' => $this->api_secret,
				'email'      => $email,
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: unsubscribe(): Error: ' . $response->get_error_message() );
			return $response;
		}

		/**
		 * Runs actions immediately after the email address was successfully unsubscribed.
		 *
		 * @since   1.4.2
		 *
		 * @param   array   $response   API Response
		 * @param   string  $email      Email Address
		 */
		do_action( 'convertkit_api_form_unsubscribe_success', $response, $email );

		return $response;

	}

	/**
	 * Gets all custom fields from the API.
	 *
	 * @since   1.4.4
	 *
	 * @return  mixed   WP_Error | array
	 */
	public function get_custom_fields() {

		$this->log( 'API: get_custom_fields()' );

		$custom_fields = array();

		// Send request.
		$response = $this->get(
			'custom_fields',
			array(
				'api_key' => $this->api_key,
			)
		);

		// If an error occured, return WP_Error.
		if ( is_wp_error( $response ) ) {
			$this->log( 'API: get_custom_fields(): Error: ' . $response->get_error_message() );
			return $response;
		}

		// If no custom fields exist, return WP_Error.
		if ( ! isset( $response['custom_fields'] ) ) {
			$this->log( 'API: get_custom_fields(): Error: No custom fields exist in ConvertKit.', 'woocommerce-convertkit' );
			return new WP_Error( 'convertkit_api_error', __( 'No custom fields exist in ConvertKit. Visit your ConvertKit account and create your first custom field.', 'woocommerce-convertkit' ) );
		}
		if ( ! count( $response['custom_fields'] ) ) {
			$this->log( 'API: get_custom_fields(): Error: No custom fields exist in ConvertKit.', 'woocommerce-convertkit' );
			return new WP_Error( 'convertkit_api_error', __( 'No custom fields exist in ConvertKit. Visit your ConvertKit account and create your first custom field.', 'woocommerce-convertkit' ) );
		}

		foreach ( $response['custom_fields'] as $custom_field ) {
			$custom_fields[] = $custom_field;
		}

		return $custom_fields;

	}

	/**
	 * Get HTML from ConvertKit for the given Legacy Form ID.
	 *
	 * This isn't specifically an API function, but for now it's best suited here.
	 *
	 * @param   int $id     Form ID.
	 * @return  string          HTML
	 */
	public function get_form_html( $id ) {

		// Define Legacy Form URL.
		$url = add_query_arg(
			array(
				'k' => $this->api_key,
				'v' => 2,
			),
			'https://api.convertkit.com/forms/' . $id . '/embed'
		);

		// Get HTML.
		$body = $this->get_html( $url );

		return $body;

	}

	/**
	 * Get HTML from ConvertKit for the given Landing Page URL.
	 *
	 * This isn't specifically an API function, but for now it's best suited here.
	 *
	 * @param   string $url    URL of Landing Page.
	 * @return  string          HTML
	 */
	public function get_landing_page_html( $url ) {

		// Get HTML.
		$body = $this->get_html( $url, false );

		// Inject JS for subscriber forms to work.
		$scripts = new WP_Scripts();
		$script  = "<script type='text/javascript' src='" . trailingslashit( $scripts->base_url ) . "wp-includes/js/jquery/jquery.js?ver=1.4.0'></script>"; // phpcs:ignore
		$script .= "<script type='text/javascript' src='" . CKWC_PLUGIN_VERSION . 'resources/frontend/js/convertkit.js?ver=' . CKWC_PLUGIN_VERSION . "'></script>"; // phpcs:ignore
		$script .= "<script type='text/javascript'>/* <![CDATA[ */var convertkit = {\"ajaxurl\":\"" . admin_url( 'admin-ajax.php' ) . '"};/* ]]> */</script>'; // phpcs:ignore

		$body = str_replace( '</head>', '</head>' . $script, $body );

		return $body;

	}

	/**
	 * Create a Purchase.
	 *
	 * @since   1.4.2
	 *
	 * @param   array $purchase   Purchase Data.
	 * @return  mixed               WP_Error | array
	 */
	public function purchase_create( $purchase ) {

		$this->log( 'API: purchase_create(): [ purchase: ' . print_r( $purchase, true ) . ']' ); // phpcs:ignore

		$response = $this->post(
			'purchases',
			array(
				'api_secret' => $this->api_secret,
				'purchase'   => $purchase,
			)
		);

		if ( is_wp_error( $response ) ) {
			$this->log( 'API: purchase_create(): Error: ' . $response->get_error_message() );
		}

		/**
		 * Runs actions immediately after the purchase data address was successfully created.
		 *
		 * @since   1.4.2
		 *
		 * @param   array   $response   API Response
		 * @param   array   $purchase   Purchase Data
		 */
		do_action( 'convertkit_api_purchase_create_success', $response, $purchase );

		return $response;

	}

	/**
	 * Backward compat. function for updating Forms, Landing Pages and Tags in WordPress options table.
	 *
	 * @since   1.0.0
	 *
	 * @param   string $api_key    API Key.
	 * @param   string $api_secret API Secret.
	 */
	public function update_resources( $api_key, $api_secret ) { // phpcs:ignore

		// Warn the developer that they shouldn't use this function.
		_deprecated_function( __FUNCTION__, '1.4.2', 'refresh() in CKWC_Resource_Forms, CKWC_Resource_Landing_Pages and CKWC_Resource_Tags classes.' );

		// Initialize resource classes.
		$forms         = new CKWC_Resource_Forms();
		$landing_pages = new CKWC_Resource_Landing_Pages();
		$tags          = new CKWC_Resource_Tags();

		// Refresh resources by calling the API and storing the results.
		$forms->refresh();
		$landing_pages->refresh();
		$tags->refresh();

	}

	/**
	 * Backward compat. function for getting a ConvertKit subscriber by their ID.
	 *
	 * @since   1.4.2
	 *
	 * @param   int $id     Subscriber ID.
	 * @return  mixed           WP_Error | array
	 */
	public function get_subscriber( $id ) {

		// Warn the developer that they shouldn't use this function.
		_deprecated_function( __FUNCTION__, '1.4.2', 'get_subscriber_by_id()' );

		// Pass request to new function.
		return $this->get_subscriber_by_id( $id );

	}

	/**
	 * Backward compat. function for subscribing a ConvertKit subscriber to the given Tag.
	 *
	 * @since   1.4.2
	 *
	 * @param   int   $tag    Tag ID.
	 * @param   array $args   Arguments.
	 * @return  mixed           WP_Error | array
	 */
	public function add_tag( $tag, $args ) {

		// Warn the developer that they shouldn't use this function.
		_deprecated_function( __FUNCTION__, '1.4.2', 'tag_subscribe( $tag_id, $email_address )' );

		// Pass request to new function.
		return $this->tag_subscribe( $tag, $args['email'] );

	}

	/**
	 * Backward compat. function for fetching Legacy Form or Landing Page markup for the given URL.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $url    URL.
	 * @return  mixed           WP_Error | string
	 */
	public function get_resource( $url ) {

		// Warn the developer that they shouldn't use this function.
		_deprecated_function( __FUNCTION__, '1.4.2', 'get_form_html( $form_id ) or get_landing_page_html( $url )' );

		// Pass request to new function.
		return $this->get_landing_page_html( $url );

	}

	/**
	 * Backward compat. function for fetching Legacy Form or Landing Page markup for the given URL.
	 *
	 * @since   1.4.2
	 *
	 * @param   array $args   Arguments (single email key).
	 * @return  mixed           WP_Error | array
	 */
	public function form_unsubscribe( $args ) {

		// Warn the developer that they shouldn't use this function.
		_deprecated_function( __FUNCTION__, '1.4.2', 'unsubscribe( $email_address )' );

		// Pass request to new function.
		return $this->unsubscribe( $args['email'] );

	}

	/**
	 * Get HTML for the given URL.
	 *
	 * This isn't specifically an API function, but for now it's best suited here.
	 *
	 * @param   string $url    URL of Form or Landing Page.
	 * @param   bool   $body_only   Return HTML between <body> and </body> tags only.
	 * @return  string          HTML
	 */
	private function get_html( $url, $body_only = true ) {

		// Get HTML from URL.
		$result = wp_remote_get(
			$url,
			array(
				'Accept-Encoding' => 'gzip',
				'timeout'         => $this->get_timeout(),
				'user-agent'      => $this->get_user_agent(),
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Fetch HTTP response code and body.
		$http_response_code = wp_remote_retrieve_response_code( $result );
		$body               = wp_remote_retrieve_body( $result );

		// If the body appears to be JSON containing an error, the request for a Legacy Form
		// through api.convertkit.com failed, so return a WP_Error now.
		if ( $this->is_json( $body ) ) {
			$json = json_decode( $body );
			return new WP_Error(
				'convertkit_api_error',
				sprintf(
					/* translators: API Error Message */
					__( 'ConvertKit: %s', 'woocommerce-convertkit' ),
					$json->error_message
				)
			);
		}

		// Get just the scheme and host from the URL.
		$url_scheme           = wp_parse_url( $url );
		$url_scheme_host_only = $url_scheme['scheme'] . '://' . $url_scheme['host'];

		// Load the landing page HTML into a DOMDocument.
		libxml_use_internal_errors( true );
		$html = new DOMDocument();
		if ( $body_only ) {
			// Prevent DOMDocument from including a doctype on saveHTML().
			// We don't use LIBXML_HTML_NOIMPLIED, as it requires a single root element, which Legacy Forms don't have.
			$html->loadHTML( mb_convert_encoding( $body, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NODEFDTD );
		} else {
			$html->loadHTML( mb_convert_encoding( $body, 'HTML-ENTITIES', 'UTF-8' ) );
		}

		// Convert any relative URLs to absolute URLs in the HTML DOM.
		$this->convert_relative_to_absolute_urls( $html->getElementsByTagName( 'a' ), 'href', $url_scheme_host_only );
		$this->convert_relative_to_absolute_urls( $html->getElementsByTagName( 'link' ), 'href', $url_scheme_host_only );
		$this->convert_relative_to_absolute_urls( $html->getElementsByTagName( 'img' ), 'src', $url_scheme_host_only );
		$this->convert_relative_to_absolute_urls( $html->getElementsByTagName( 'script' ), 'src', $url_scheme_host_only );
		$this->convert_relative_to_absolute_urls( $html->getElementsByTagName( 'form' ), 'action', $url_scheme_host_only );

		// If the entire HTML needs to be returned, return it now.
		if ( ! $body_only ) {
			return $html->saveHTML();
		}

		// Remove some HTML tags that DOMDocument adds, returning the output.
		// We do this instead of using LIBXML_HTML_NOIMPLIED in loadHTML(), because Legacy Forms are not always contained in
		// a single root / outer element, which is required for LIBXML_HTML_NOIMPLIED to correctly work.
		return $this->strip_html_head_body_tags( $html->saveHTML() );

	}

	/**
	 * Determines if the given string is JSON.
	 *
	 * @since   1.4.4
	 *
	 * @param   string $string     Possible JSON String.
	 * @return  bool                Is JSON String.
	 */
	private function is_json( $string ) {

		json_decode( $string );
		return json_last_error() === JSON_ERROR_NONE;

	}

	/**
	 * Converts any relative URls to absolute, fully qualified HTTP(s) URLs for the given
	 * DOM Elements.
	 *
	 * @since   1.4.2
	 *
	 * @param   array  $elements   Elements.
	 * @param   string $attribute  HTML Attribute.
	 * @param   string $url        Absolute URL to prepend to relative URLs.
	 */
	private function convert_relative_to_absolute_urls( $elements, $attribute, $url ) {

		// Anchor hrefs.
		foreach ( $elements as $element ) {
			// Skip if the attribute's value is empty.
			if ( empty( $element->getAttribute( $attribute ) ) ) {
				continue;
			}

			// Skip if the attribute's value is a fully qualified URL.
			if ( filter_var( $element->getAttribute( $attribute ), FILTER_VALIDATE_URL ) ) {
				continue;
			}

			// Skip if this is a Google Font CSS URL.
			if ( strpos( $element->getAttribute( $attribute ), '//fonts.googleapis.com' ) !== false ) {
				continue;
			}

			// If here, the attribute's value is a relative URL, missing the http(s) and domain.
			// Prepend the URL to the attribute's value.
			$element->setAttribute( $attribute, $url . $element->getAttribute( $attribute ) );
		}

	}

	/**
	 * Strips <html>, <head> and <body> opening and closing tags from the given markup.
	 *
	 * @since   1.4.4
	 *
	 * @param   string $markup     HTML Markup.
	 * @return  string              HTML Markup
	 * */
	private function strip_html_head_body_tags( $markup ) {

		$markup = str_replace( '<html>', '', $markup );
		$markup = str_replace( '</html>', '', $markup );
		$markup = str_replace( '<head>', '', $markup );
		$markup = str_replace( '</head>', '', $markup );
		$markup = str_replace( '<body>', '', $markup );
		$markup = str_replace( '</body>', '', $markup );

		return $markup;

	}

	/**
	 * Gets all forms and landing pages from the API.
	 *
	 * @since   1.4.2
	 *
	 * @return  mixed   WP_Error | array
	 */
	private function get_forms_landing_pages() {

		// Send request.
		$response = $this->get(
			'forms',
			array(
				'api_key' => $this->api_key,
			)
		);

		// If an error occured, log and return it now.
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// If no forms exist.
		if ( ! isset( $response['forms'] ) ) {
			return new WP_Error(
				'convertkit_api_error',
				__( 'No forms exist in ConvertKit. Visit your ConvertKit account and create your first form.', 'woocommerce-convertkit' )
			);
		}

		// Iterate through forms, determining if each form is a form or landing page.
		$forms         = array();
		$landing_pages = array();
		foreach ( $response['forms'] as $form ) {
			// Skip archived forms.
			if ( isset( $form['archived'] ) && $form['archived'] ) {
				continue;
			}

			switch ( $form['type'] ) {
				case 'hosted':
					$landing_pages[ $form['id'] ] = $form;
					break;

				default:
					$forms[ $form['id'] ] = $form;
					break;
			}
		}

		return array(
			'forms'         => $forms,
			'landing_pages' => $landing_pages,
		);

	}

	/**
	 * Performs a GET request.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $endpoint       API Endpoint.
	 * @param   array  $params         Params.
	 * @return  mixed                   WP_Error | object
	 */
	private function get( $endpoint, $params ) {

		return $this->request( $endpoint, 'get', $params, true );

	}

	/**
	 * Performs a POST request.
	 *
	 * @since  1.4.2
	 *
	 * @param   string $endpoint       API Endpoint.
	 * @param   array  $params         Params.
	 * @return  mixed                   WP_Error | object
	 */
	private function post( $endpoint, $params ) {

		return $this->request( $endpoint, 'post', $params, true );

	}

	/**
	 * Main function which handles sending requests to the API using WordPress functions.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $endpoint                API Endpoint (required).
	 * @param   string $method                  HTTP Method (optional).
	 * @param   mixed  $params                  Params (array|boolean|string).
	 * @param   bool   $retry_if_rate_limit_hit Retry request if rate limit hit.
	 * @return  mixed                           WP_Error | object
	 */
	private function request( $endpoint, $method = 'get', $params = array(), $retry_if_rate_limit_hit = true ) {

		// Send request.
		switch ( $method ) {
			case 'get':
				$result = wp_remote_get(
					$this->add_params_to_url( $this->get_api_url( $endpoint ), $params ),
					array(
						'Accept-Encoding' => 'gzip',
						'timeout'         => $this->get_timeout(),
						'user-agent'      => $this->get_user_agent(),
					)
				);
				break;

			case 'post':
				$result = wp_remote_post(
					$this->get_api_url( $endpoint ),
					array(
						'Accept-Encoding' => 'gzip',
						'headers'         => array(
							'Content-Type' => 'application/json; charset=utf-8',
						),
						'body'            => wp_json_encode( $params ),
						'timeout'         => $this->get_timeout(),
						'user-agent'      => $this->get_user_agent(),
					)
				);
				break;
		}

		// If an error occured, log and return it now.
		if ( is_wp_error( $result ) ) {
			$this->log( 'API: Error: ' . $result->get_error_message() );
			return $result;
		}

		// Fetch HTTP response code and body.
		$http_response_code = wp_remote_retrieve_response_code( $result );
		$body               = wp_remote_retrieve_body( $result );
		$response           = json_decode( $body, true );

		// If the HTTP response code is 429, we've hit the API's rate limit of 120 requests over 60 seconds.
		if ( $http_response_code === 429 ) {
			// If retry on rate limit hit is disabled, return a WP_Error.
			if ( ! $retry_if_rate_limit_hit ) {
				return new WP_Error( 'convertkit_api_error', __( 'Rate limit hit.', 'woocommerce-convertkit' ) );
			}

			// Retry the request a final time, waiting 2 seconds before.
			sleep( 2 );
			return $this->request( $endpoint, $method, $params, false );
		}

		// If an error message or code exists in the response, return a WP_Error.
		if ( isset( $response['error'] ) ) {
			$this->log( 'API: Error: ' . $response['error'] . ': ' . $response['message'] );
			return new WP_Error( 'convertkit_api_error', $response['error'] . ': ' . $response['message'] );
		}

		return $response;

	}

	/**
	 * Returns the maximum amount of time to wait for
	 * a response to the request before exiting.
	 *
	 * @since   1.4.2
	 *
	 * @return  int     Timeout, in seconds.
	 */
	private function get_timeout() {

		$timeout = 10;

		/**
		 * Defines the maximum time to allow the API request to run.
		 *
		 * @since   2.2.9
		 *
		 * @param   int     $timeout    Timeout, in seconds.
		 */
		$timeout = apply_filters( 'convertkit_api_get_timeout', $timeout );

		return $timeout;

	}

	/**
	 * Gets a customized version of the WordPress default user agent; includes WP Version, PHP version, and ConvertKit plugin version.
	 *
	 * @since   1.4.2
	 *
	 * @return string User Agent
	 */
	private function get_user_agent() {

		// Include an unmodified $wp_version.
		require ABSPATH . WPINC . '/version.php';

		return sprintf(
			'WordPress/%1$s;PHP/%2$s;ConvertKitWooCommerce/%3$s;%4$s',
			$wp_version,
			phpversion(),
			CKWC_PLUGIN_VERSION,
			home_url( '/' )
		);

	}

	/**
	 * Returns the full API URL for the given endpoint.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $endpoint   Endpoint.
	 * @return  string              API URL
	 */
	private function get_api_url( $endpoint ) {

		return path_join( $this->api_url_base . $this->api_version, $endpoint );

	}

	/**
	 * Adds the supplied array of parameters as query arguments to the URL.
	 *
	 * @since   1.4.4
	 *
	 * @param   string $url        URL.
	 * @param   array  $params     Parameters for request.
	 * @return  string              URL with API Key or API Secret
	 */
	private function add_params_to_url( $url, $params ) {

		return add_query_arg( $params, $url );

	}

	/**
	 * Adds the given entry to the log file, if debugging is enabled.
	 *
	 * @since   1.4.2
	 *
	 * @param   string $entry  Log Entry.
	 */
	private function log( $entry ) {

		// Don't log this entry if debugging is disabled.
		if ( ! $this->debug ) {
			return;
		}

		// Pass the request to the log class.
		$this->log->add( 'convertkit', $entry );

	}

}