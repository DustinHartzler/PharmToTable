<?php
/**
 * Main class for Affiliate For WooCommerce Gateway PayPal
 *
 * @since       1.0.0
 * @version     1.1.3
 *
 * @package     affiliate-for-woocommerce/includes/gateway/paypal/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Paypal' ) ) {

	/**
	 * Gateway PayPal
	 */
	class AFWC_Paypal {

		const USE_PROXY  = false;
		const PROXY_HOST = '127.0.0.1';
		const PROXY_PORT = '8080';

		/**
		 * The API username
		 *
		 * @var string $api_username
		 */
		public $api_username = null;

		/**
		 * The API password
		 *
		 * @var string $api_password
		 */
		public $api_password = null;

		/**
		 * The API signature
		 *
		 * @var string $api_signature
		 */
		public $api_signature = null;

		/**
		 * The receiver type
		 *
		 * @var string $receiver_type
		 */
		public $receiver_type = 'EmailAddress';

		/**
		 * The email address
		 *
		 * @var string $email_subject
		 */
		public $email_subject = 'EmailSubject';

		/**
		 * The subject
		 *
		 * @var string $subject
		 */
		public $subject = '';

		/**
		 * The version
		 *
		 * @var string $version
		 */
		public $version = '98.0';

		/**
		 * The currency
		 *
		 * @var string $currency
		 */
		public $currency = 'USD';

		/**
		 * The PayPal supported currencies
		 *
		 * @var array $paypal_supported_currency
		 */
		public static $paypal_supported_currency = array( 'AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD' );

		/**
		 * The API endpoint
		 *
		 * @var string $api_endpoint
		 */
		public $api_endpoint = '';
		// phpcs:disable
		// public $api_endpoint = 'https://api-3t.paypal.com/nvp';
		// phpcs:enable

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Paypal Singleton object of this class
		 */
		public static function get_instance() {

			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			$this->init_settings();

			add_action( 'wp_ajax_get_paypal_balance', array( $this, 'get_paypal_balance' ) );
		}

		/**
		 * Initialize settings
		 */
		public function init_settings() {

			$woocommerce_paypal_settings = get_option( 'woocommerce_paypal_settings' );

			$is_sandbox = ( ! empty( $woocommerce_paypal_settings['testmode'] ) ) ? $woocommerce_paypal_settings['testmode'] : 'yes';

			if ( 'yes' === $is_sandbox ) {
				$this->api_endpoint  = 'https://api-3t.sandbox.paypal.com/nvp';
				$this->api_username  = ( ! empty( $woocommerce_paypal_settings['sandbox_api_username'] ) ) ? $woocommerce_paypal_settings['sandbox_api_username'] : '';
				$this->api_password  = ( ! empty( $woocommerce_paypal_settings['sandbox_api_password'] ) ) ? $woocommerce_paypal_settings['sandbox_api_password'] : '';
				$this->api_signature = ( ! empty( $woocommerce_paypal_settings['sandbox_api_signature'] ) ) ? $woocommerce_paypal_settings['sandbox_api_signature'] : '';
			} else {
				$this->api_endpoint  = 'https://api-3t.paypal.com/nvp';
				$this->api_username  = ( ! empty( $woocommerce_paypal_settings['api_username'] ) ) ? $woocommerce_paypal_settings['api_username'] : '';
				$this->api_password  = ( ! empty( $woocommerce_paypal_settings['api_password'] ) ) ? $woocommerce_paypal_settings['api_password'] : '';
				$this->api_signature = ( ! empty( $woocommerce_paypal_settings['api_signature'] ) ) ? $woocommerce_paypal_settings['api_signature'] : '';
			}
		}

		/**
		 * Function to get api setting status
		 *
		 * @return array $status
		 */
		public function get_api_setting_status() {

			$paypal_setting_page_url = add_query_arg(
				array(
					'page'    => 'wc-settings',
					'tab'     => 'checkout',
					'section' => 'paypal',
				),
				admin_url( 'admin.php' )
			);

			$status = array(
				'value'    => 'no',
				'desc'     => __( 'Disabled', 'affiliate-for-woocommerce' ),
				/* translators: 1: Location of the API Credentials */
				'desc_tip' => sprintf( __( 'To enable, follow all the requirements mentioned %s.', 'affiliate-for-woocommerce' ), '<a href="https://docs.woocommerce.com/document/affiliate-for-woocommerce/how-to-payout-commissions-in-affiliate-for-woocommerce/#section-2" target="_blank">' . __( 'here', 'affiliate-for-woocommerce' ) . '</a>' ),
			);

			if ( ! empty( $this->api_username ) && ! empty( $this->api_password ) && ! empty( $this->api_signature ) ) {
				$status = array(
					'value'    => 'yes',
					'desc'     => __( 'Enabled', 'affiliate-for-woocommerce' ),
					/* translators: 1: Location of the API Credentials */
					'desc_tip' => sprintf( __( 'To disable, empty the API credentials %s', 'affiliate-for-woocommerce' ), '<a href="' . esc_url( $paypal_setting_page_url ) . '" target="_blank">' . __( 'here', 'affiliate-for-woocommerce' ) . '</a>' ),
				);
			}

			return $status;
		}

		/**
		 * Get PayPal balance
		 */
		public function get_paypal_balance() {

			check_ajax_referer( AFWC_AJAX_SECURITY, 'security' );

			$paypal_response = $this->get_balance();

			$response = array();

			if ( ! empty( $paypal_response['ACK'] ) && 'Success' === $paypal_response['ACK'] ) {

				$response = array(
					'amount'   => ( ! empty( $paypal_response['L_AMT0'] ) ) ? $paypal_response['L_AMT0'] : 0,
					'currency' => ( ! empty( $paypal_response['L_CURRENCYCODE0'] ) ) ? $paypal_response['L_CURRENCYCODE0'] : '',
				);

			}

			wp_send_json( $response );

		}

		/**
		 * Process PayPal mass payment
		 *
		 * @param  array  $affiliates The affiliates.
		 * @param  string $currency   The currency code.
		 * @return array  $response
		 */
		public function process_paypal_mass_payment( $affiliates, $currency ) {
			$response = $this->make_payment( $affiliates, $currency );
			return $response;
		}

		/**
		 * Get balance
		 *
		 * @return mixed $result
		 */
		public function get_balance() {
			$nvp_str  = '';
			$nvp_str .= $this->get_nvp_header();
			$result   = $this->hash_call( 'GetBalance', $nvp_str );

			return $result;
		}

		/**
		 * Make payment
		 *
		 * @param  array  $affiliates The affiliates.
		 * @param  string $currency   The currency code.
		 * @return mixed $result
		 */
		public function make_payment( $affiliates, $currency ) {

			$result = null;
			if ( count( $affiliates ) > 0 ) {
				$nvp_str        = '';
				$j              = 0;
				$this->currency = $currency;
				// @TODO: encode data in nvpstr.
				foreach ( $affiliates as $key => $affiliate ) {
					if ( isset( $affiliate['email'] ) && '' !== $affiliate['email'] && isset( $affiliate['amount'] ) && 0 < floatval( $affiliate['amount'] ) ) {
						$receiver_mail = rawurlencode( $affiliate['email'] );
						$amount        = rawurlencode( floatval( $affiliate['amount'] ) );
						$unique_id     = rawurlencode( $affiliate['unique_id'] );
						$note          = rawurlencode( $affiliate['note'] );
						$nvp_str      .= "&L_EMAIL$j=$receiver_mail&L_AMT$j=$amount&L_UNIQUEID$j=$unique_id&L_NOTE$j=$note";
						$j++;
					}
				}
				$nvp_str .= "&EMAILSUBJECT=$this->email_subject&RECEIVERTYPE=$this->receiver_type&CURRENCYCODE=$this->currency";

				$nvp_header = $this->get_nvp_header();
				$nvp_str    = $nvp_header . $nvp_str;

				$result = $this->hash_call( 'MassPay', $nvp_str );
			}

			return $result;
		}

		/**
		 * Get NVP headers
		 *
		 * @return string $nvp_header
		 */
		public function get_nvp_header() {

			if ( ! empty( $this->api_username ) && ! empty( $this->api_password ) && ! empty( $this->api_signature ) && ! empty( $this->subject ) ) {
				$auth_mode = 'THIRDPARTY';
			} elseif ( ! empty( $this->api_username ) && ! empty( $this->api_password ) && ! empty( $this->api_signature ) ) {
				$auth_mode = '3TOKEN';
			} elseif ( ! empty( $this->subject ) ) {
				$auth_mode = 'FIRSTPARTY';
			}

			switch ( $auth_mode ) {

				case '3TOKEN':
					$nvp_header = '&PWD=' . rawurlencode( $this->api_password ) . '&USER=' . rawurlencode( $this->api_username ) . '&SIGNATURE=' . rawurlencode( $this->api_signature );
					break;
				case 'FIRSTPARTY':
					$nvp_header = '&SUBJECT=' . rawurlencode( $this->subject );
					break;
				case 'THIRDPARTY':
					$nvp_header = '&PWD=' . rawurlencode( $this->api_password ) . '&USER=' . rawurlencode( $this->api_username ) . '&SIGNATURE=' . rawurlencode( $this->api_signature ) . '&SUBJECT=' . rawurlencode( $this->subject );
					break;
			}

			return $nvp_header;
		}

		// phpcs:disable

		/**
		 * The hash call
		 *
		 * @param  string $method_name  The method name.
		 * @param  string $nvp_str      The request params.
		 * @return array  $nvp_res_array
		 */
		public function hash_call( $method_name, $nvp_str ) {
			// declaring of global variables.
			// echo $API_Endpoint;.
			// setting the curl parameters.
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $this->api_endpoint );
			curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

			// turning off the server and peer verification(TrustManager Concept).
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			// if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
			// Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php.
			if ( self::USE_PROXY ) {
				curl_setopt( $ch, CURLOPT_PROXY, self::PROXY_HOST . ':' . self::PROXY_PORT );
			}

			// check if version is included in $nvp_str else include the version.
			if ( strlen( str_replace( 'VERSION=', '', strtoupper( $nvp_str ) ) ) === strlen( $nvp_str ) ) {
				$nvp_str = '&VERSION=' . rawurlencode( $this->version ) . $nvp_str;
			}

			$nvpreq = 'METHOD=' . $method_name . $nvp_str;

			// setting the nvpreq as POST FIELD to curl.
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $nvpreq );

			// getting response from server.
			$response = curl_exec( $ch );

			// convrting NVPResponse to an Associative Array.
			$nvp_res_array = $this->de_format_nvp( $response );
			$nvp_req_array = $this->de_format_nvp( $nvpreq );

			$_SESSION['nvpReqArray'] = $nvp_req_array;

			if ( curl_errno( $ch ) ) {
				// moving to display page to display curl errors.
				$_SESSION['curl_error_no']  = curl_errno( $ch );
				$_SESSION['curl_error_msg'] = curl_error( $ch );
				$location                   = 'APIError.php';
				header( "Location: $location" );
			} else {
				// closing the curl.
				curl_close( $ch );
			}

			return $nvp_res_array;
		}

		// phpcs:enable

		/**
		 * De-format $nvpstr
		 *
		 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
		 * It is usefull to search for a particular key and displaying arrays.
		 *
		 * @param  string $nvpstr The nvpstr.
		 * @return array  $nvp_array
		 */
		public function de_format_nvp( $nvpstr ) {

			$intial    = 0;
			$nvp_array = array();

			$nvpstr_len = strlen( $nvpstr );

			while ( $nvpstr_len ) {
				// postion of Key.
				$keypos = strpos( $nvpstr, '=' );
				// position of value.
				$valuepos = strpos( $nvpstr, '&' ) ? strpos( $nvpstr, '&' ) : strlen( $nvpstr );

				/* getting the Key and Value values and storing in a Associative Array */
				$keyval = substr( $nvpstr, $intial, $keypos );
				$valval = substr( $nvpstr, $keypos + 1, $valuepos - $keypos - 1 );
				// decoding the respose.
				$nvp_array[ urldecode( $keyval ) ] = urldecode( $valval );
				$nvpstr                            = substr( $nvpstr, $valuepos + 1, strlen( $nvpstr ) );
				$nvpstr_len                        = strlen( $nvpstr );
			}

			return $nvp_array;
		}

	}

}

return AFWC_Paypal::get_instance();
