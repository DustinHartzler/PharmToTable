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

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Affiliate's first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ); ?></p>

<p><?php echo esc_html__( 'You\'re approved!', 'affiliate-for-woocommerce' ); ?></p>
<p><?php echo esc_html__( 'We\'re excited to have you as our affiliate partner. Here are the details you\'ll need to get started:', 'affiliate-for-woocommerce' ); ?></p>

<p><strong><?php echo esc_html__( 'Your affiliate ID: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_attr( $affiliate_id ); ?></p>

<p><strong><?php echo esc_html__( 'Your personal affiliated link: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_attr( $affiliate_link ); ?></p>

<p><strong><?php echo esc_html__( 'Your affiliate dashboard', 'affiliate-for-woocommerce' ); ?></strong></p>
<p><?php echo esc_html__( 'Login to ', 'affiliate-for-woocommerce' ); ?>
<a href="<?php echo esc_url( $my_account_afwc_url ); ?>" target="_blank"><?php esc_html_e( 'your affiliate dashboard', 'affiliate-for-woocommerce' ); ?></a>
<?php echo esc_html__( 'regularly. You will find our current promotion campaigns, marketing assets, complete record of your referrals and payouts there. You can fully manage your account there.', 'affiliate-for-woocommerce' ); ?></p>

<p><strong><?php echo esc_html__( 'Our products', 'affiliate-for-woocommerce' ); ?></strong></p>
<p><?php echo esc_html__( 'You can refer people using your affiliate link above, but you can also promote individual products if you like. ', 'affiliate-for-woocommerce' ); ?>
<a href="<?php echo esc_url( $shop_page ); ?>" target="_blank"><?php esc_html_e( 'Here\'s our complete product catalog', 'affiliate-for-woocommerce' ); ?></a>
</p>

<p><strong><?php echo esc_html__( 'Partnership and communication are important to us', 'affiliate-for-woocommerce' ); ?></strong></p>
<p><?php echo esc_html__( 'We value our partnership and are happy to help if you face any problems. We\'d also love to discuss any novel promotion ideas you may have. Feel free to reach out to us anytime.', 'affiliate-for-woocommerce' ); ?></p>

<p><strong><?php echo esc_html__( 'Personal note before signing off', 'affiliate-for-woocommerce' ); ?></strong></p>
<p><?php echo esc_html__( 'The most important thing I\'ve learnt working with our partners is that the best way to succeed is quickly start active promotions. If you postpone, you won\'t see results. If you take quick actions, you may as well become one of our our superstar partners! Look forward to working closely with you.', 'affiliate-for-woocommerce' ); ?></p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

?>
<p><?php echo esc_html__( 'Best Regards', 'affiliate-for-woocommerce' ); ?></p>
<?php

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
