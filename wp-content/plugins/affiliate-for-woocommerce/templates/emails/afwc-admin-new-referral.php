<?php
/**
 * Admin New Referral Email
 *
 * @package  affiliate-for-woocommerce/templates/emails/
 * @since    6.7.0
 * @version  1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';

?>
<h2><?php echo esc_html_x( 'Affiliate information', 'Affiliate section title', 'affiliate-for-woocommerce' ); ?></h2>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 0.5em;" border="1">
		<tr>
			<th class="td" scope="row" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo esc_html_x( 'Affiliate', 'Affiliate name', 'affiliate-for-woocommerce' ); ?></th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo wp_kses_post( $affiliate_display_name ); ?></td>
		</tr>
		<?php
		if ( ! empty( $campaign_id ) ) {
			?>
				<tr>
					<th class="td" scope="row" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo esc_html_x( 'Campaign', 'Campaign name and id', 'affiliate-for-woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo wp_kses_post( $campaign_name . ' (#' . $campaign_id . ')' ); ?></td>
				</tr>
				<?php
		}
		?>
		<tr>
			<th class="td" scope="row" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo esc_html_x( 'Commission earned', 'Commission earned amount', 'affiliate-for-woocommerce' ); ?></th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo wp_kses_post( $order_currency_symbol . '' . $commission_amount ); ?></td>
		</tr>
		<tr>
			<th class="td" scope="row" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo esc_html_x( 'Conversion medium', 'Commission conversion medium', 'affiliate-for-woocommerce' ); ?></th>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; "><?php echo wp_kses_post( ucfirst( $conversion_type ) ); ?></td>
		</tr>
	</table>
</div>
<?php
