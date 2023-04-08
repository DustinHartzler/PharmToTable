<?php
/**
 * Affiliate Pending Request Email Content (Affiliate - Pending Request)
 *
 * @package   affiliate-for-woocommerce/templates/
 * @since     6.4.0
 * @version   1.0.0
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
<p><?php echo sprintf( esc_html_x( 'Hi %s,', 'Greeting message for the affiliate user on affiliate pending request email', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ); ?></p>

<p><?php echo esc_html_x( 'Thank you for signing up for our affiliate program.', 'thanking affiliate user for registering for the affiliate program', 'affiliate-for-woocommerce' ); ?></p>
<p><?php echo esc_html_x( 'Your details are currently being reviewed by our team.', 'updating the status of affiliate request', 'affiliate-for-woocommerce' ); ?></p>
<p><?php echo esc_html_x( 'Once approved, you will receive an email with further steps and guidelines.', 'informing about next step once approved', 'affiliate-for-woocommerce' ); ?></p>

<p>
<?php
if ( ! empty( $contact_email ) ) {
	echo sprintf(
		/* translators: %1$s: Opening tag for anchor tag  %1$s: Closing tag for anchor tag */
		esc_html_x( 'For any queries, kindly %1$scontact us%2$s and we will help you out.', 'contact us for affiliate pending request email with link to contact affiliate manager', 'affiliate-for-woocommerce' ),
		'<a href="mailto:' . esc_attr( sanitize_email( $contact_email ) ) . '">',
		'</a>'
	);
} else {
	echo esc_html_x( 'For any queries, kindly reply to this email and we will help you out.', 'contact us for affiliate pending request email', 'affiliate-for-woocommerce' );
}

?>
	</p>

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
