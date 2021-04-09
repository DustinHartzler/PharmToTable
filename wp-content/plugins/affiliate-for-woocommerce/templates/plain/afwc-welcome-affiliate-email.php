<?php
/**
 * Welcome email for affiliate
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

/* translators: %s: Affiliate's first name */
echo sprintf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ) . "\n\n";

echo wp_kses_post( __( 'You\'re approved!', 'affiliate-for-woocommerce' ) ) . "\n\n";
echo wp_kses_post( __( 'We\'re excited to have you as our affiliate partner. Here are the details you\'ll need to get started:', 'affiliate-for-woocommerce' ) ) . "\n\n";

echo esc_html__( 'Your affiliate ID:', 'affiliate-for-woocommerce' ) . "\t" . esc_attr( $affiliate_id ) . "\n\n";

echo esc_html__( 'Your personal affiliated link:', 'affiliate-for-woocommerce' ) . "\t" . esc_attr( $affiliate_link ) . "\n\n";

echo esc_html__( 'Your affiliate dashboard', 'affiliate-for-woocommerce' ) . "\n";
echo esc_html__( 'Login to your affiliate dashboard regularly.', 'affiliate-for-woocommerce' ) . "\n";
echo esc_html__( 'You will find our current promotion campaigns, marketing assets, complete record of your referrals and payouts there.', 'affiliate-for-woocommerce' ) . "\n";
/* translators: %s: Affiliate's account link */
echo sprintf( esc_html__( 'You can fully manage your account there: %s', 'affiliate-for-woocommerce' ), esc_html( $my_account_afwc_url ) ) . "\n\n";

echo esc_html__( 'Our Products', 'affiliate-for-woocommerce' ) . "\n";
echo esc_html__( 'You can refer people using your affiliate link above, but you can also promote individual products if you like.', 'affiliate-for-woocommerce' ) . "\n";
/* translators: %s: Shop page link */
echo sprintf( wp_kses_post( __( 'Here\'s our complete product catalog : %s', 'affiliate-for-woocommerce' ) ), esc_html( $shop_page ) ) . "\n\n";

echo esc_html__( 'Partnership and communication are important to us.', 'affiliate-for-woocommerce' ) . "\n";
echo esc_html__( 'We value our partnership and are happy to help if you face any problems.', 'affiliate-for-woocommerce' ) . "\n";
echo wp_kses_post( __( 'We\'d also love to discuss any novel promotion ideas you may have. Feel free to reach out to us anytime.', 'affiliate-for-woocommerce' ) ) . "\n\n";

echo esc_html__( 'Personal note before signing off', 'affiliate-for-woocommerce' ) . "\n";
echo wp_kses_post( __( 'The most important thing I\'ve learnt working with our partners is that the best way to succeed is quickly start active promotions. ', 'affiliate-for-woocommerce' ) ) . "\n";
echo wp_kses_post( __( 'If you postpone, you won\'t see results. ', 'affiliate-for-woocommerce' ) ) . "\n";
echo esc_html__( 'If you take quick actions, you may as well become one of our our superstar partners! Look forward to working closely with you.', 'affiliate-for-woocommerce' ) . "\n\n";

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo esc_html__( 'Best Regards', 'affiliate-for-woocommerce' ) . "\n\n";

// Output the email footer.
echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
