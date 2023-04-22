<?php
/**
 * Admin New Referral Email
 *
 * @package   affiliate-for-woocommerce/templates/emails/plain/
 * @since     6.7.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo esc_html_x( 'Affiliate information', 'Affiliate section title', 'affiliate-for-woocommerce' ) . "\n\n";

echo esc_html_x( 'Affiliate', 'Affiliate name', 'affiliate-for-woocommerce' ) . "\t " . wp_kses_post( $affiliate_display_name ) . "\n";

if ( ! empty( $campaign_id ) ) {
	echo esc_html_x( 'Campaign', 'Campaign name and id', 'affiliate-for-woocommerce' ) . "\t " . wp_kses_post( $campaign_name . ' (#' . $campaign_id . ')' ) . "\n";
}

echo esc_html_x( 'Commission earned', 'Commission earned amount', 'affiliate-for-woocommerce' ) . "\t " . wp_kses_post( $order_currency_symbol . '' . $commission_amount ) . "\n";

echo esc_html_x( 'Conversion medium', 'Commission conversion medium', 'affiliate-for-woocommerce' ) . "\t " . wp_kses_post( ucfirst( $conversion_type ) ) . "\n";

echo "\n\n----------------------------------------\n\n";
