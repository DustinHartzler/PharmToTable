<?php
/**
 * Affiliate Pending Request Email Content (Affiliate - Pending Request)
 *
 * @package   affiliate-for-woocommerce/templates/plain/
 * @since     6.4.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/* translators: %s: Affiliate's first name */
echo sprintf( esc_html_x( 'Hi %s,', 'Greeting message for the affiliate user on affiliate pending request email', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ) . "\n\n";

echo esc_html_x( 'Thank you for signing up for our affiliate program.', 'thanking affiliate user for registering for the affiliate program', 'affiliate-for-woocommerce' ) . "\n\n";
echo esc_html_x( 'Your details are currently being reviewed by our team.', 'updating the status of affiliate request', 'affiliate-for-woocommerce' ) . "\n\n";
echo esc_html_x( 'Once approved, you will receive an email with further steps and guidelines.', 'informing about next step once approved', 'affiliate-for-woocommerce' ) . "\n\n";

if ( ! empty( $contact_email ) ) {
	echo sprintf(
		/* translators: %s: Email for contact email */
		esc_html_x( 'For any queries, kindly contact us on %s and we will help you out.', 'contact us for affiliate pending request email with link to contact affiliate manager', 'affiliate-for-woocommerce' ),
		esc_html( sanitize_email( $contact_email ) )
	);
} else {
	echo esc_html_x( 'For any queries, kindly reply to this email and we will help you out.', 'contact us for affiliate pending request email', 'affiliate-for-woocommerce' );
}
echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

// Output the email footer.
echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
