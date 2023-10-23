<?php
/**
 * Main class for PayPal Payout method.
 *
 * @package    affiliate-for-woocommerce/includes/payouts/
 * @since      6.28.0
 * @version    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_PayPal_Payout_Method' ) ) {

	/**
	 * Affiliate PayPal Payout method class.
	 */
	class AFWC_PayPal_Payout_Method {

		/**
		 * Main method to process payouts.
		 *
		 * @param array $params the params to initialize the payout.
		 *
		 * @throws Exception When payout failed.
		 * @return array|WP_Error The response.
		 */
		public function execute_payout( $params = array() ) {
			try {
				// Requirements checks.
				if ( empty( $params['affiliate_id'] ) || empty( $params['currency'] ) || empty( $params['referrals'] ) ) {
					throw new Exception( _x( 'Requirements are not provided for the PayPal Payout', 'Error message for missing requirements for PayPal payout', 'affiliate-for-woocommerce' ) );
				}

				// Validation.
				$result = $this->validate( $params );

				if ( is_wp_error( $result ) ) {
					throw new Exception( is_callable( array( $result, 'get_error_message' ) ) ? $result->get_error_message() : _x( 'PayPal payout validation failed', 'Error message for PayPal payout validation failed', 'affiliate-for-woocommerce' ) );
				}

				if ( true !== $result ) {
					throw new Exception( _x( 'Something went wrong', 'Error message for PayPal payout failed', 'affiliate-for-woocommerce' ) );
				}

				// Format the records to payout.
				$records = $this->get_records( absint( $params['affiliate_id'] ), $params['referrals'] );

				if ( is_wp_error( $records ) ) {
					throw new Exception( is_callable( array( $records, 'get_error_message' ) ) ? $records->get_error_message() : _x( 'PayPal payout failed', 'Error message for PayPal payout failed', 'affiliate-for-woocommerce' ) );
				}

				$paypal = AFWC_PayPal_API::get_instance();

				$payout_result = is_callable( array( $paypal, 'process_paypal_mass_payment' ) ) ? $paypal->process_paypal_mass_payment( array( $records ), $params['currency'] ) : array( 'ACK' => 'Error' );

				if ( is_wp_error( $payout_result ) || empty( $payout_result ) || ! is_array( $payout_result ) ) {
					/* translators: PayPal response message */
					throw new Exception( sprintf( _x( 'PayPal payout failed. Message: %1$s Response: %2$s.', 'payout failed debug message', 'affiliate-for-woocommerce' ), is_callable( array( $payout_result, 'get_error_message' ) ) ? $payout_result->get_error_message() : '', print_r( $payout_result, true ) ) ); // phpcs:ignore
				}

				if ( ! empty( $payout_result['ACK'] ) && 'Success' === $payout_result['ACK'] ) {
					return array(
						'success'  => true,
						'batch_id' => ! empty( $payout_result['batch_id'] ) ? $payout_result['batch_id'] : '',
						'amount'   => ! empty( $payout_result['amount'] ) ? floatval( $payout_result['amount'] ) : 0.00,
						'receiver' => ! empty( $records['email'] ) ? $records['email'] : '',
					);
				}

				throw new Exception( _x( 'Something went wrong', 'PayPal payout failed message', 'affiliate-for-woocommerce' ) );

			} catch ( Exception $e ) {
				if ( is_callable( array( $e, 'getMessage' ) ) ) {
					Affiliate_For_WooCommerce::get_instance()->log( 'error', $e->getMessage() );
				}
				return new WP_Error( 'afwc-paypal-payout-error', is_callable( array( $e, 'getMessage' ) ) ? $e->getMessage() : '' );
			}
		}

		/**
		 * Validate the records for payout.
		 *
		 * @param array $params The params.
		 *
		 * @throws Exception When Validation failed.
		 * @return bool|WP_Error Return true if validated otherwise WP_Error instance.
		 */
		public function validate( $params = array() ) {
			try {
				if ( empty( $params['currency'] ) ) {
					throw new Exception( _x( 'Currency is not provided', 'PayPal Payout validation failed message for currency', 'affiliate-for-woocommerce' ) );
				}

				$supported_currency = $this->get_supported_currency();

				if ( ! empty( $supported_currency ) && is_array( $supported_currency ) && ! in_array( $params['currency'], $supported_currency, true ) ) {
					/* translators: The currency  */
					throw new Exception( sprintf( _x( 'PayPal payout failed as %s currency is not supported.', 'PayPal payout provided currency unsupported message', 'affiliate-for-woocommerce' ), $params['currency'] ) );
				}

				do_action( 'afwc_paypal_payout_method_validation', $params );

				return true;
			} catch ( Exception $e ) {
				return new WP_Error( 'afwc-paypal-payout-validation-error', is_callable( array( $e, 'getMessage' ) ) ? $e->getMessage() : '' );
			}
		}

		/**
		 * Filter and arrange the records for payout.
		 *
		 * @param int   $affiliate_id The affiliate ID.
		 * @param array $records The records.
		 *
		 * @return array|WP_Error The array of response otherwise WP_Error instance if failed.
		 */
		public function get_records( $affiliate_id = 0, $records = array() ) {

			$affiliate_id = absint( $affiliate_id );

			if ( empty( $affiliate_id ) || empty( $records ) || ! is_array( $records ) ) {
				return array();
			}

			$paypal_email = get_user_meta( $affiliate_id, 'afwc_paypal_email', true );

			if ( empty( $paypal_email ) ) {
				/* translators: The affiliate ID */
				return new WP_Error( 'afwc-paypal-payout-email-not-found', sprintf( _x( 'PayPal email is not available for affiliate Id: %d', 'PayPal Payout validation failed message for PayPal email unavailability', 'affiliate-for-woocommerce' ), $affiliate_id ) );
			}

			return array(
				'id'        => $affiliate_id,
				'email'     => sanitize_email( $paypal_email ),
				'amount'    => ! empty( $records['amount'] ) ? floatval( $records['amount'] ) : 0,
				'unique_id' => 'afwc_mass_payment',
				'note'      => ! empty( $records['note'] ) ? $records['note'] : '',
			);
		}

		/**
		 * Get the PayPal Payout supported currency.
		 *
		 * @return array Array of currency ID.
		 */
		public function get_supported_currency() {
			return array( 'AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD' );
		}
	}
}
