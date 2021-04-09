<?php
/**
 * Affiliate New Conversion Email Content
 *
 * @version     1.0.1
 * @package     affiliate-for-woocommerce/templates/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/* translators: %s: admin's first name */
echo sprintf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $admin_name ) ) . "\n\n";

echo esc_html__( 'Please review and respond to this potential affiliate partner.', 'affiliate-for-woocommerce' ) . "\n\n";

echo esc_html__( 'Name: ', 'affiliate-for-woocommerce' ) . "\t" . esc_attr( $user_name ) . ' (' . esc_attr( $user_email ) . ')' . "\n\n";

if ( ! empty( $user_contact ) ) {
	echo esc_html__( 'Contact Information: ', 'affiliate-for-woocommerce' ) . "\t" . esc_attr( $user_contact ) . "\n\n";
}

if ( ! empty( $user_url ) ) {
	echo esc_html__( 'Website: ', 'affiliate-for-woocommerce' ) . "\t" . esc_attr( $user_url ) . "\n\n";
}

echo esc_html__( 'About user: ', 'affiliate-for-woocommerce' ) . "\t" . esc_attr( $user_desc ) . "\n\n";

echo esc_html__( 'Next Actions', 'affiliate-for-woocommerce' ) . "\n\n";
/* translators: %s: user's profile url*/
echo sprintf( esc_html__( 'Approve / reject / manage this affiliate : %s', 'affiliate-for-woocommerce' ), esc_url( $manage_url ) ) . "\n\n";
/* translators: %s: user's email*/
echo sprintf( esc_html__( 'Email %s and discuss details ', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ) . sprintf( esc_html__( '(%s)', 'affiliate-for-woocommerce' ), esc_html( $user_email ) ) . "\n\n";

/* translators: %s: dashboard url*/
echo sprintf( esc_html__( 'BTW, you can review and manage all affiliates here: %s', 'affiliate-for-woocommerce' ), esc_url( $dashboard_url ) ) . "\n\n";
echo esc_html__( 'You can process all pending requests from there.', 'affiliate-for-woocommerce' ) . "\n\n";

/* translators: %s: affiliate user's name*/
echo sprintf( esc_html__( 'Do respond promptly. %s is waiting!', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ) . "\n\n";

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo esc_html__( 'Thanks', 'affiliate-for-woocommerce' );

// Output the email footer.
echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
