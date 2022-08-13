<?php
/**
 * Main class for Affiliate For WooCommerce, PayPal Gateway - PayPal Payout
 *
 * @package     affiliate-for-woocommerce/includes/gateway/paypal/
 * @since       4.0.0
 * @version     1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_PayPal_Payout' ) ) {

	/**
	 * PayPal Payout
	 */
	class AFWC_PayPal_Payout extends AFWC_PayPal_API {

		/**
		 * The key name for the payout method.
		 *
		 * @var string $key
		 */
		public $key = 'paypal_payout';

		/**
		 * The receiver type
		 *
		 * @var string $receiver_type
		 */
		public $receiver_type = 'EMAIL';

		/**
		 * The endpoint.
		 *
		 * @var string $api_endpoint
		 */
		public $api_endpoint = null;

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_PayPal_Payout Singleton object of this class
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

			if ( false === $this->is_set_credentials( $this->key ) ) {
				$this->set_credentials();
			}

		}

		/**
		 * Process PayPal payment
		 *
		 * @param  array $affiliates The affiliates.
		 * @return Object|WP_Error  $response
		 */
		public function make_payment( $affiliates = array() ) {

			if ( empty( $affiliates ) ) {
				return new WP_Error( 422, _x( 'Missing affiliate details.', 'payout error message', 'affiliate-for-woocommerce' ) );
			}

			$items = $this->get_items( $affiliates );

			if ( empty( $items ) ) {
				return new WP_Error( 422, _x( 'Missing payout data.', 'payout error message', 'affiliate-for-woocommerce' ) );
			}

			$token = $this->get_token();

			if ( is_wp_error( $token ) || empty( $token['access_token'] ) ) {
				return $token;
			}

			$request = wp_remote_post(
				$this->api_endpoint . '/payments/payouts',
				array(
					'headers'     => array(
						'Content-Type'  => 'application/json',
						'Authorization' => 'Bearer ' . $token['access_token'],
					),
					'timeout'     => 45,
					'httpversion' => '1.1',
					'body'        => wp_json_encode(
						array(
							'sender_batch_header' => array(
								'sender_batch_id' => md5( Affiliate_For_WooCommerce::uniqid( 'afwc_payout' ) ),
								'email_subject'   => ! empty( $this->email_subject ) ? $this->email_subject : _x( 'You have a payout!', 'payout email title', 'affiliate-for-woocommerce' ),
							),
							'items'               => $items,
						)
					),
				)
			);

			$body = wp_remote_retrieve_body( $request );
			$code = wp_remote_retrieve_response_code( $request );

			if ( is_wp_error( $request ) ) {
				/* translators: 1: Error code */
				Affiliate_For_WooCommerce::get_instance()->log( 'error', sprintf( _x( 'Payment request failed with error code: %s', 'payout error message', 'affiliate-for-woocommerce' ), $code ) );

				return $request;

			} elseif ( 201 === $code ) {
				$result = json_decode( $body, true );

				$amounts          = array_column( $items, 'amount' );
				$result['amount'] = ! empty( $amounts ) ? array_sum( array_column( $amounts, 'value' ) ) : 0.00;

				return $this->format_result( $result );

			} else {
				$message = wp_remote_retrieve_response_message( $request );
				/* translators: 1: Error code 2: Error message */
				Affiliate_For_WooCommerce::get_instance()->log( 'error', sprintf( _x( 'Payment request failed with error code: %1$s - %2$s', 'payout error message', 'affiliate-for-woocommerce' ), $code, $message ) );

				return new WP_Error( $code, $message );

			}

		}

		/**
		 * Get formatted items for api body.
		 *
		 * @param  array $affiliates The affiliates.
		 * @return array $items
		 */
		public function get_items( $affiliates = array() ) {
			$items = array();
			// Check if affiliate details are exists.
			if ( is_array( $affiliates ) && ! empty( $affiliates ) ) {

				foreach ( $affiliates as $affiliate ) {

					// Check if amount and recipient email is exists.
					if ( ! empty( $affiliate['amount'] ) && ! empty( $affiliate['email'] ) ) {

						$items[] = array(
							'recipient_type' => $this->receiver_type,
							'amount'         => array(
								'value'    => $this->get_formatted_amount( $affiliate['amount'] ),
								'currency' => $this->currency,
							),
							'receiver'       => ( ! empty( $affiliate['email'] ) ) ? sanitize_email( $affiliate['email'] ) : '',
							'note'           => ( ! empty( $affiliate['note'] ) ) ? sanitize_textarea_field( $affiliate['note'] ) : '',
							'sender_item_id' => ( ! empty( $affiliate['unique_id'] ) ) ? $affiliate['unique_id'] : '',
						);
					}
				}
			}

			return $items;
		}

		/**
		 * Get formatted result for api response.
		 *
		 * @param  array $response The response.
		 * @return array
		 */
		public function format_result( $response = array() ) {
			if ( empty( $response ) ) {
				return array();
			}

			if ( ! empty( $response['batch_header'] ) ) {
				return array(
					'ACK'      => 'Success',
					'batch_id' => ! empty( $response['batch_header']['payout_batch_id'] ) ? $response['batch_header']['payout_batch_id'] : '',
					'amount'   => ! empty( $response['amount'] ) ? $response['amount'] : 0.00,
				);
			}
			return array();
		}

		/**
		 * Get the formated amount for payout.
		 *
		 * @see https://developer.paypal.com/docs/api/payments.payouts-batch/v1/#definition-currency
		 * @see https://developer.paypal.com/reference/currency-codes/#link-currencycodes
		 *
		 * @param  int|string $amount The amount value.
		 *
		 * @return int|float
		 */
		public function get_formatted_amount( $amount = 0 ) {

			if ( empty( $amount ) ) {
				return 0;
			}

			if ( in_array( $this->currency, array( 'HUF', 'JPY', 'TWD' ), true ) ) {
				return intval( $amount );
			}

			return number_format( floatval( $amount ), 2, '.', '' );
		}

	}

}
