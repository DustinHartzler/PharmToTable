<?php
/**
 * Main class for Affiliate For WooCommerce, PayPal Gateway - MassPay API
 *
 * @package     affiliate-for-woocommerce/includes/gateway/paypal/
 * @since       4.0.0
 * @version     1.1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_PayPal_Masspay' ) ) {

	/**
	 * PayPal Masspay
	 */
	class AFWC_PayPal_Masspay extends AFWC_PayPal_API {

		const USE_PROXY  = false;
		const PROXY_HOST = '127.0.0.1';
		const PROXY_PORT = '8080';

		/**
		 * The key name for the payout method.
		 *
		 * @var string $subject
		 */
		public $key = 'paypal_masspay';

		/**
		 * The receiver type
		 *
		 * @var string $receiver_type
		 */
		public $receiver_type = 'EmailAddress';

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
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_PayPal_Masspay Singleton object of this class
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
			$this->init_credentials();
		}

		/**
		 * Initialize api credentials.
		 */
		public function init_credentials() {
			if ( false === $this->is_set_credentials( $this->key ) ) {
				// Set paypal_standard credentials for MassPay api call.
				$this->set_credentials( 'paypal_standard' );
			}

			$this->api_endpoint = $this->sandbox ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
		}

		/**
		 * Make payment
		 *
		 * @param  array $affiliates The affiliates.
		 * @return mixed $result
		 */
		public function make_payment( $affiliates = array() ) {

			$result = null;
			if ( is_array( $affiliates ) && count( $affiliates ) > 0 ) {
				$nvp_str = '';
				$j       = 0;
				// @TODO: encode data in nvpstr.
				foreach ( $affiliates as $key => $affiliate ) {
					if ( isset( $affiliate['email'] ) && '' !== $affiliate['email'] && isset( $affiliate['amount'] ) && 0 < floatval( $affiliate['amount'] ) ) {
						$receiver_mail = rawurlencode( $affiliate['email'] );
						$amount        = rawurlencode( floatval( $affiliate['amount'] ) );
						$unique_id     = rawurlencode( $affiliate['unique_id'] );
						$note          = rawurlencode( $affiliate['note'] );
						$nvp_str      .= "&L_EMAIL$j=$receiver_mail&L_AMT$j=$amount&L_UNIQUEID$j=$unique_id&L_NOTE$j=$note";
						++$j;
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

			$auth_mode  = '';
			$nvp_header = '';

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

			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );

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

		/**
		 * Check masspay status
		 *
		 * @return string|bool $result
		 */
		public function mass_pay_status() {
			$this->init_credentials();

			if ( false === $this->is_set_credentials( $this->key ) ) {
				return false;
			}

			$nvp_header = $this->get_nvp_header();
			$nvp_str    = "&L_EMAIL0=payee1@example.com&L_AMT0=1&EMAILSUBJECT=$this->email_subject&RECEIVERTYPE=$this->receiver_type&CURRENCYCODE=$this->currency";
			$nvp_str    = $nvp_header . $nvp_str;

			$result = $this->hash_call( 'MassPay', $nvp_str );

			if ( isset( $result['L_ERRORCODE0'] ) && ( '10007' === $result['L_ERRORCODE0'] || '10301' === $result['L_ERRORCODE0'] ) ) {
				return 'not_allowed';
			}

			return true;
		}
	}

}
