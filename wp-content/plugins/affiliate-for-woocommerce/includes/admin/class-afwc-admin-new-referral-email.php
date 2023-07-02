<?php
/**
 * Main class for Admin New Referral details in emails for new order.
 *
 * @package  affiliate-for-woocommerce/includes/admin/
 * @since    6.7.0
 * @version  1.0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_New_Referral_Email' ) ) {

	/**
	 * The Admin New Conversion Received Email class
	 */
	class AFWC_Admin_New_Referral_Email {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'woocommerce_email_order_meta', array( $this, 'affiliate_referral_details_email' ), 10, 4 );
		}

		/**
		 * Function to add affiliate referral details in WooCommerce New Order and WooCommerce Subscriptions New Renewal Order email.
		 *
		 * @param WC_Order $order         Order instance.
		 * @param bool     $sent_to_admin If should sent to admin.
		 * @param bool     $plain_text    If is plain text email.
		 * @param object   $email         The Email object.
		 */
		public function affiliate_referral_details_email( $order = null, $sent_to_admin = false, $plain_text = false, $email = null ) {

			// Return if setting is disabled.
			if ( 'no' === get_option( 'afwc_add_referral_in_admin_emails', 'no' ) ) {
				return;
			}

			$email_id = ( is_object( $email ) && ! empty( $email->id ) ) ? $email->id : '';
			if ( empty( $email_id ) ) {
				return;
			}

			$allowed_emails = apply_filters( 'afwc_allowed_emails_for_referral_details', array( 'new_order' ), array( 'source' => $this ) );
			if ( ! in_array( $email_id, $allowed_emails, true ) ) {
				return;
			}

			if ( ! $order instanceof WC_Order ) {
				return;
			}

			$is_commission_recorded = $order->get_meta( 'is_commission_recorded', true );
			if ( 'yes' !== $is_commission_recorded ) {
				return;
			}

			$order_id          = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : 0;
			$afwc_api          = new AFWC_API();
			$affiliate_details = is_callable( array( $afwc_api, 'get_affiliate_by_order' ) ) ? $afwc_api->get_affiliate_by_order( $order_id, 'all' ) : array();

			if ( empty( $affiliate_details ) || ! is_array( $affiliate_details ) ) {
				return;
			}

			$affiliate_id           = ! empty( $affiliate_details['affiliate_id'] ) ? $affiliate_details['affiliate_id'] : 0;
			$affiliate_info         = get_userdata( $affiliate_id );
			$affiliate_display_name = ! empty( $affiliate_info->display_name ) ? $affiliate_info->display_name : $affiliate_info->user_nicename;

			$order_currency_symbol = ! empty( $affiliate_details['currency_id'] ) ? get_woocommerce_currency_symbol( $affiliate_details['currency_id'] ) : get_woocommerce_currency_symbol();
			$commission_amount     = ! empty( $affiliate_details['amount'] ) ? $affiliate_details['amount'] : 0.00;

			$campaign_id = ! empty( $affiliate_details['campaign_id'] ) ? $affiliate_details['campaign_id'] : 0;
			if ( ! empty( $campaign_id ) ) {
				global $wpdb;
				$campaign_name = $wpdb->get_var( // phpcs:ignore
									$wpdb->prepare( // phpcs:ignore
											"SELECT title
												FROM {$wpdb->prefix}afwc_campaigns
												WHERE id = %d",
										$campaign_id
									)
				);
			}

			$conversion_type = ! empty( $affiliate_details['type'] ) ? $affiliate_details['type'] : '';

			if ( $plain_text ) {
				include AFWC_PLUGIN_DIRPATH . '/templates/emails/plain/afwc-admin-new-referral.php'; // phpcs:ignore
			} else {
				include AFWC_PLUGIN_DIRPATH . '/templates/emails/afwc-admin-new-referral.php'; // phpcs:ignore
			}

		}

	}

}

return new AFWC_Admin_New_Referral_Email();
