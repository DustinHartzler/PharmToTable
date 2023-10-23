<?php
/**
 * Affiliate New Conversion Email Content (Affiliate - New Conversion Received)
 *
 * @package     affiliate-for-woocommerce/templates/
 * @since       2.3.0
 * @version     1.2.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Affiliate's first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $affiliate_name ) ); ?></p>

<p><?php echo esc_html__( '{site_title} just made a sale - thanks to you!', 'affiliate-for-woocommerce' ); ?></p>

<p><?php echo esc_html__( 'Here is the summary:', 'affiliate-for-woocommerce' ); ?></p>

<div style="margin-bottom: 20px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<?php
			// Provision to remove customer name.
			$is_show_customer_column = apply_filters( 'afwc_account_show_customer_column', true, array( 'source' => $email ) );
		if ( true === $is_show_customer_column ) {
			?>
				<tr>
					<th class="td" scope="row" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo esc_html__( 'Customer', 'affiliate-for-woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo wp_kses_post( $order_customer_full_name ); ?></td>
				</tr>
				<?php
		}
		?>
		<tr>
			<th class="td" scope="row" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo esc_html__( 'Order total', 'affiliate-for-woocommerce' ); ?></th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo wp_kses_post( $order_currency_symbol . '' . $order_total ); ?></td>
		</tr>
		<tr>
			<th class="td" scope="row" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo esc_html__( 'Commission earned', 'affiliate-for-woocommerce' ); ?></th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo wp_kses_post( $order_currency_symbol . '' . $order_commission_amount ); ?></td>
		</tr>
	</table>
</div>

<?php /* translators: %1$s: Opening a tag for affiliate my account link %2$s: closing a tag for affiliate my account link */ ?>
<p><?php printf( esc_html__( 'We have already updated %1$syour account%2$s to reflect this.', 'affiliate-for-woocommerce' ), '<a href="' . esc_url( $my_account_afwc_url ) . '" class="button alt link">', '</a>' ); ?>

<p><?php echo esc_html__( 'Thank you for promoting us. We look forward to sending another email like this very soon.', 'affiliate-for-woocommerce' ); ?></p>

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
 * @hooked WC_Emails::email_footer() Output the email footer
 * @param string $email.
 */
do_action( 'woocommerce_email_footer', $email );
