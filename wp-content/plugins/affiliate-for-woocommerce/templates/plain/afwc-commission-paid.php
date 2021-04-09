<?php
/**
 * Affiliate Payout Sent Email Content
 *
 * @version     1.0.0
 * @package     affiliate-for-woocommerce/templates/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/* translators: %s: Affiliate's first name */
echo sprintf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $affiliate_name ) ) . "\n\n";

echo esc_html__( 'Congratulations on your successful referrals. We just processed your commission payout.', 'affiliate-for-woocommerce' ) . "\n\n";

echo "\n----------------------------------------\n\n";

echo esc_html__( 'Period: ', 'affiliate-for-woocommerce' ) . "\t " . esc_html( $start_date ) . esc_html__( ' to ', 'affiliate-for-woocommerce' ) . esc_html( $end_date ) . "\n";

echo esc_html__( 'Successful orders: ', 'affiliate-for-woocommerce' ) . "\t " . esc_html( $total_orders ) . "\n";

echo esc_html__( 'Commission: ', 'affiliate-for-woocommerce' ) . "\t " . wp_kses_post( $currency_symbol . '' . $commission_amount ) . "\n";

if ( 'paypal' === $payment_gateway && ! empty( $paypal_receiver_email ) ) {
	echo esc_html__( 'PayPal email: ', 'affiliate-for-woocommerce' ) . "\t " . esc_html( $paypal_receiver_email ) . "\n";
}

if ( ! empty( $payout_notes ) ) {
	echo esc_html__( 'Additional notes: ', 'affiliate-for-woocommerce' ) . "\t " . esc_html( $payout_notes ) . "\n\n";
}

echo "\n----------------------------------------\n\n";

/* translators: %s: Affiliate's my account link */
echo sprintf( esc_html__( 'We\'ve already updated your account with this info. You can login to your affiliate dashboard to track all referrals, payouts and campaigns: %s' ), esc_url( $my_account_afwc_url ) ) . "\n\n";

echo esc_html__( 'We look forward to sending bigger payouts to you next time! Keep promoting, keep living a life you love!', 'affiliate-for-woocommerce' ) . "\n\n";

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo esc_html__( 'Best regards from your friends at,', 'affiliate-for-woocommerce' ) . "\n\n";

// Output the email footer.
echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
