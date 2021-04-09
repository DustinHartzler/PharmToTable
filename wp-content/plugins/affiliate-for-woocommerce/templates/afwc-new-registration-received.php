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

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: admin's first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $admin_name ) ); ?></p>

<p><?php echo esc_html__( 'Please review and respond to this potential affiliate partner.', 'affiliate-for-woocommerce' ); ?></p>

<p><strong><?php echo esc_html__( 'Name: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_attr( $user_name ) . ' (' . esc_attr( $user_email ) . ')'; ?></p>

<?php if ( ! empty( $user_contact ) ) { ?>
<p><strong><?php echo esc_html__( 'Contact Information: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_attr( $user_contact ); ?></p>
<?php } ?>

<?php if ( ! empty( $user_url ) ) { ?>
<p><strong><?php echo esc_html__( 'Website: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_url( $user_url ); ?></p>
<?php } ?>

<p><strong><?php echo esc_html__( 'About user: ', 'affiliate-for-woocommerce' ); ?></strong><?php echo esc_html( $user_desc ); ?></p>

<p><strong><?php echo esc_html__( 'Next Actions', 'affiliate-for-woocommerce' ); ?></strong>
<ul>
	<li><a href="<?php echo esc_url( $manage_url ); ?>"  target="_blank" ><?php esc_html_e( 'Approve / reject / manage this affiliate', 'affiliate-for-woocommerce' ); ?></a></li>
	<?php /* translators: %s: Affiliate's first name */ ?>
	<li><a href="mailto:<?php echo esc_attr( $user_email ); ?>" target="_blank" ><?php printf( esc_html__( 'Email %s and discuss details', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ); ?></a></li>
</ul>
</p>

<p><?php echo esc_html__( 'BTW, you can ', 'affiliate-for-woocommerce' ); ?><a href="<?php echo esc_url( $dashboard_url ); ?>" ><?php esc_html_e( 'review and manage all affiliates here.', 'affiliate-for-woocommerce' ); ?></a>
<?php echo esc_html__( 'You can process all pending requests from there.', 'affiliate-for-woocommerce' ); ?>
</p>

<p><?php echo esc_html__( 'Do respond promptly. ', 'affiliate-for-woocommerce' ); ?><strong><?php echo esc_attr( $user_name ); ?></strong><?php echo esc_html__( ' is waiting!', 'affiliate-for-woocommerce' ); ?></p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

?>
<p><?php echo esc_html__( 'Thanks', 'affiliate-for-woocommerce' ); ?></p>
<?php

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
