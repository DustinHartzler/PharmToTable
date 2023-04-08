<?php
/**
 * Welcome email for affiliate (Affiliate - Welcome Email)
 *
 * @package     affiliate-for-woocommerce/templates/
 * @since       2.4.0
 * @version     1.1.1
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
<p><?php echo sprintf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ); ?></p>

<p><?php echo esc_html__( 'Your affiliate request has been approved.', 'affiliate-for-woocommerce' ); ?></p>
<p><?php echo esc_html__( 'We are excited to have you as our affiliate partner. Here are the details you will need to get started:', 'affiliate-for-woocommerce' ); ?></p>

<p><strong><?php echo esc_html__( 'Your affiliate ID: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_attr( $affiliate_id ); ?></p>

<p><strong><?php echo esc_html__( 'Your personal affiliated link: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_attr( $affiliate_link ); ?></p>

<p><strong><?php echo esc_html_x( 'Your affiliate dashboard', 'affiliate dashboard page text', 'affiliate-for-woocommerce' ); ?></strong></p>
<p>
	<?php
		/* translators: %1$s: Opening a tag for affiliate my account link %2$s: closing a tag for affiliate my account link */
		echo sprintf( esc_html_x( 'Log in to %1$syour affiliate dashboard%2$s regularly. You will find our current promotion campaigns, marketing assets, complete record of your referrals and payouts there. You can fully manage your account from the dashboard.', 'Message to view the affiliate dashboard', 'affiliate-for-woocommerce' ), '<a href="' . esc_url( $my_account_afwc_url ) . '">', '</a>' );
	?>
</p>

<p><strong><?php echo esc_html__( 'Our products', 'affiliate-for-woocommerce' ); ?></strong></p>
<p>
	<?php
		echo sprintf( esc_html__( 'You can refer people using your affiliate link. You can also promote individual products if you like.', 'affiliate-for-woocommerce' ) );
	if ( ! empty( $shop_page ) ) {
		/* translators: %1$s: Opening a tag for shop page link %2$s: closing a tag for shop page link */
		echo sprintf( esc_html__( ' %1$sHere is our complete product catalog%2$s.', 'affiliate-for-woocommerce' ), '<a href="' . esc_url( $shop_page ) . '">', '</a>' );
	}
	?>
</p>

<p><strong><?php echo esc_html__( 'Partnership and communication are important to us', 'affiliate-for-woocommerce' ); ?></strong></p>
<p><?php echo esc_html__( 'We value our partners, so we are happy to assist any time. We would also love to discuss any novel promotion ideas you may have. Feel free to reach out to us anytime.', 'affiliate-for-woocommerce' ); ?></p>

<p><strong><?php echo esc_html__( 'Personal note before signing off', 'affiliate-for-woocommerce' ); ?></strong></p>
<p><?php echo esc_html__( 'The most important thing I have learned working with our partners is that the best way to succeed is quickly to start active promotions. If you postpone, you will not see results. If you take quick action, you may as well become one of our superstar partners! Looking forward to working closely with you.', 'affiliate-for-woocommerce' ); ?></p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/**
 * Output the email footer
 *
 * @hooked WC_Emails::email_footer() Output the email footer.
 * @param string $email.
 */
do_action( 'woocommerce_email_footer', $email );
