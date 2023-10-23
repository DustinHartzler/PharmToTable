<?php
/**
 * My Account > Affiliate > Reports
 *
 * @package  affiliate-for-woocommerce/templates/my-account/
 * @since    6.25.0
 * @version  1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$referrals_colspan                  = ( true === $is_show_customer_column ) ? 4 : 3;
$paid_commission_percentage_style   = ( empty( $paid_commission_percentage ) ) ? 'display:none;' : '';
$unpaid_commission_percentage_style = ( empty( $unpaid_commission_percentage ) ) ? 'display:none;' : '';
?>

<div id="afwc_dashboard_wrapper">
	<div id="afwc_top_row_container">
		<div id="afwc_date_range_container">
			<input type="text" readonly="readonly" id="afwc_from" name="afwc_from" value="<?php echo ( ! empty( $date_range['from'] ) ) ? esc_attr( $date_range['from'] ) : ''; ?>" placeholder="<?php echo esc_attr_x( 'From', 'From placeholder for date field of my account', 'affiliate-for-woocommerce' ); ?>">
			-
			<input type="text" readonly="readonly" id="afwc_to" name="afwc_to" value="<?php echo ( ! empty( $date_range['to'] ) ) ? esc_attr( $date_range['to'] ) : ''; ?>" placeholder="<?php echo esc_attr_x( 'To', 'To placeholder for date field of my account', 'affiliate-for-woocommerce' ); ?>">
		</div>
	</div>
	<?php if ( ! empty( $paid_commission_percentage ) || ! empty( $unpaid_commission_percentage ) ) { ?>
		<div id="afwc_commission">
			<div id="afwc_commission_lbl" class="afwc_kpis_text"><?php echo esc_html_x( 'Total Commissions', 'Label for total commissions in my account', 'affiliate-for-woocommerce' ); ?>:</div>
			<div id="afwc_commission_container">
				<div id="afwc_commission_bar">
					<div id="afwc_paid_commission" class="fill_green" style="<?php echo esc_html( $paid_commission_percentage_style ) . 'width:' . esc_html( $paid_commission_percentage ) . '%'; ?>"></div>
					<div id="afwc_unpaid_commission" class="fill_orange" style="<?php echo esc_html( $unpaid_commission_percentage_style ) . 'width:' . esc_html( $unpaid_commission_percentage ) . '%'; ?>"></div>
				</div>
				<div id="afwc_commission_stats">
					<?php
					// TODO: can fetch commission statuses from function.
					if ( ! empty( $paid_commission_percentage ) ) {
						?>
						<div id="afwc_commission_stats_paid" class="afwc_kpis_text"><?php echo esc_html_x( 'Paid', 'Label for paid commissions in my account', 'affiliate-for-woocommerce' ) . ': ' . wp_kses_post( wc_price( ! empty( $kpis['paid_commission'] ) ? floatval( $kpis['paid_commission'] ) : 0 ) ); ?></div>
					<?php } if ( ! empty( $unpaid_commission_percentage ) ) { ?>
						<div id="afwc_commission_stats_unpaid" class="afwc_kpis_text"><?php echo esc_html_x( 'Unpaid', 'Label for unpaid commissions in my account', 'affiliate-for-woocommerce' ) . ': ' . wp_kses_post( wc_price( ! empty( $kpis['unpaid_commission'] ) ? floatval( $kpis['unpaid_commission'] ) : 0 ) ); ?></div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<div id="afwc_kpis_container">
		<div class="afwc_kpis_inner_container">
			<div id="afwc_kpi_gross_commission" class="afwc_kpi first">
				<div class="container_parent_left flex_center">
					<div class="afwc_kpis_icon_container">
						<i class="fas fa-dollar-sign afwc_kpis_icon"></i>
					</div>
				</div>
				<div id="afwc_gross_commission" class="afwc_kpis_data flex_center">
					<div class="container_parent_right">
						<span class="afwc_kpis_price">
							<?php echo wp_kses_post( wc_price( $gross_commission ) ); ?> • <span class="afwc_kpis_number"><?php echo ! empty( $kpis['number_of_orders'] ) ? esc_html( $kpis['number_of_orders'] ) : 0; ?></span>
						</span>
						<p class="afwc_kpis_text"><?php echo esc_html_x( 'Gross Commission', 'Label for gross commissions in my account', 'affiliate-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div id="afwc_kpi_refunds" class="afwc_kpi second">
				<div class="container_parent_left flex_center">
					<div class="afwc_kpis_icon_container">
						<i class="fas fa-thumbs-down afwc_kpis_icon"></i>
					</div>
				</div>
				<div id="afwc_refunds" class="afwc_kpis_data flex_center">
					<div class="container_parent_right">
						<span class="afwc_kpis_price">
							<?php echo wp_kses_post( wc_price( ! empty( $refunds['refund_amount'] ) ? floatval( $refunds['refund_amount'] ) : 0 ) ); ?> • <span class="afwc_kpis_number"><?php echo ! empty( $kpis['rejected_count'] ) ? esc_html( $kpis['rejected_count'] ) : 0; ?></span>
						</span>
						<p class="afwc_kpis_text"><?php echo esc_html_x( 'Refunds', 'Label for refund amount in my account', 'affiliate-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div id="afwc_kpi_net_commission" class="afwc_kpi third">
				<div class="container_parent_left flex_center">
					<div class="afwc_kpis_icon_container">
						<i class="fas fa-hand-holding-usd afwc_kpis_icon"></i>
					</div>
				</div>
				<div id="afwc_net_commission" class="afwc_kpis_data flex_center">
					<div class="container_parent_right">
						<span class="afwc_kpis_price">
							<?php echo wp_kses_post( wc_price( $net_commission ) ); ?> • <span class="afwc_kpis_number"><?php echo esc_html( ( ! empty( $kpis['paid_count'] ) ? intval( $kpis['paid_count'] ) : 0 ) + ( ! empty( $kpis['unpaid_count'] ) ? intval( $kpis['unpaid_count'] ) : 0 ) ); ?></span>
						</span>
						<p class="afwc_kpis_text"><?php echo esc_html_x( 'Net Commission', 'Label for net commission in my account', 'affiliate-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div id="afwc_kpi_sales" class="afwc_kpi fourth">
				<div class="container_parent_left flex_center">
					<div class="afwc_kpis_icon_container">
						<i class="fas fa-coins afwc_kpis_icon"></i>
					</div>
				</div>
				<div id="afwc_sales" class="afwc_kpis_data flex_center">
					<div class="container_parent_right">
						<span class="afwc_kpis_price">
							<?php echo wp_kses_post( wc_price( ! empty( $kpis['sales'] ) ? floatval( $kpis['sales'] ) : 0 ) ); ?>
						</span>
						<p class="afwc_kpis_text"><?php echo esc_html_x( 'Sales', 'Label for number of sales in my account', 'affiliate-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div id="afwc_kpi_clicks" class="afwc_kpi fifth">
				<div class="container_parent_left flex_center">
					<div class="afwc_kpis_icon_container">
						<i class="fas fa-hand-point-up afwc_kpis_icon"></i>
					</div>
				</div>
				<div id="afwc_clicks" class="afwc_kpis_data flex_center">
					<div class="container_parent_right">
						<span class="afwc_kpis_price">
							<?php echo ! empty( $visitors['visitors'] ) ? esc_html( $visitors['visitors'] ) : 0; ?>
						</span>
						<p class="afwc_kpis_text"><?php echo esc_html_x( 'Visitors', 'Label for visitor count in my account', 'affiliate-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div id="afwc_kpi_conversion" class="afwc_kpi sixth afwc_kpi_last">
				<div class="container_parent_left flex_center">
					<div class="afwc_kpis_icon_container">
						<i class="fas fa-handshake afwc_kpis_icon"> </i>
					</div>
				</div>
				<div id="afwc_conversion" class="afwc_kpis_data flex_center">
					<div class="container_parent_right">
						<span class="afwc_kpis_price">
							<?php echo esc_html( number_format( ( ( ! empty( $visitors['visitors'] ) ) ? ( intval( $customers_count['customers'] ) * 100 / intval( $visitors['visitors'] ) ) : 0 ), 2 ) ) . '%'; ?> • <span class="afwc_kpis_number"><?php echo ! empty( $kpis['number_of_orders'] ) ? esc_html( $kpis['number_of_orders'] ) : 0; ?></span>
						</span>
						<p class="afwc_kpis_text"><?php echo esc_html_x( 'Conversion', 'Label for conversion in my account', 'affiliate-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="afwc-referrals-section">
		<div class="afwc-table-header"><?php echo esc_html_x( 'Referrals', 'Referrals section title', 'affiliate-for-woocommerce' ); ?></div>
		<table class='afwc_referrals'>
			<thead>
			<?php
			if ( ! empty( $referral_headers ) && is_array( $referral_headers ) ) {
				echo '<tr>';
				foreach ( $referral_headers as $key => $referral_header ) {
					?>
					<th class="<?php esc_attr( $key ); ?>"><?php echo esc_html( $referral_header ); ?></th>
					<?php
				}
				echo '</tr>';
			}
			?>
			</thead>
			<tbody>
				<?php if ( ! empty( $referrals['rows'] ) && is_array( $referrals['rows'] ) && ! empty( $referral_headers ) && is_array( $referral_headers ) ) { ?>
					<?php
					foreach ( $referrals['rows'] as $referral ) {
						echo '<tr>';
						foreach ( $referral_headers as $key => $referral_header ) {
							if ( 'customer_name' === $key ) {
								$customer_name = ! empty( $referral[ $key ] ) ? ( ( strlen( $referral[ $key ] ) > 20 ) ? substr( $referral[ $key ], 0, 19 ) . '...' : $referral[ $key ] ) : '';
								?>
									<td class="<?php echo esc_attr( $key ); ?>" data-title="<?php echo esc_attr( $referral_header ); ?>" title="<?php echo ( ! empty( $referral[ $key ] ) ) ? esc_html( $referral[ $key ] ) : ''; ?>"><?php echo esc_html( $customer_name ); ?></td>
								<?php
							} elseif ( 'status' === $key ) {
								$referral_status = ( ! empty( $referral[ $key ] ) ) ? $referral[ $key ] : '';
								?>
									<td class="<?php echo esc_attr( $key ); ?>" data-title="<?php echo esc_attr( $referral_header ); ?>"><div class="<?php echo esc_attr( 'text_' . ( ! empty( $referral_status ) ? afwc_get_commission_status_colors( $referral_status ) : '' ) ); ?>"><?php echo esc_html( ( ! empty( $referral_status ) ) ? afwc_get_commission_statuses( $referral_status ) : '' ); ?></div></td>
								<?php } else { ?>
									<td class="<?php echo esc_attr( $key ); ?>" data-title="<?php echo esc_attr( $referral_header ); ?>"><?php echo ! empty( $referral[ $key ] ) ? wp_kses_post( $referral[ $key ] ) : ''; ?></td>
								<?php } ?>
							<?php
						}
						echo '</tr>';
						?>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td class="empty-table" colspan="<?php echo esc_attr( $referrals_colspan ); ?>"><?php echo esc_html_x( 'No data to display', 'message to show when no referrals data', 'affiliate-for-woocommerce' ); ?></td>
					</tr>
				<?php } ?>
			</tbody>
			<?php if ( ! empty( $referrals['total_count'] ) && ! empty( $referrals['rows'] ) && ( intval( $referrals['total_count'] ) > count( $referrals['rows'] ) ) ) { ?>
				<tfoot>
					<tr>
						<td colspan="<?php echo esc_attr( $referrals_colspan ); ?>">
							<a id="afwc_load_more_referrals" data-max_record="<?php echo esc_attr( $referrals['total_count'] ); ?>"><?php echo esc_html_x( 'Load more', 'Referral load more link text in my account', 'affiliate-for-woocommerce' ); ?></button>
						</td>
					</tr>
				</tfoot>
			<?php } ?>
		</table>
	</div>
	<div class="afwc-products-section">
		<div class="afwc-table-header"><?php echo esc_html_x( 'Products', 'Product section title', 'affiliate-for-woocommerce' ); ?></div>
		<table class="afwc_products">
			<thead>
			<?php
			if ( ! empty( $product_headers ) && is_array( $product_headers ) ) {
				echo '<tr>';
				foreach ( $product_headers as $key => $product_header ) {
					?>
					<th class="<?php esc_attr( $key ); ?>"><?php echo esc_html( $product_header ); ?></th>
					<?php
				}
				echo '</tr>';
			}
			?>
			</thead>
			<tbody>
				<?php if ( ! empty( $products['rows'] ) && is_array( $products['rows'] ) && ! empty( $product_headers ) && is_array( $product_headers ) ) { ?>
					<?php
					foreach ( $products['rows'] as $product ) {
						echo '<tr>';
						foreach ( $product_headers as $key => $product_header ) {
							?>
							<td class="<?php echo esc_attr( $key ); ?>" data-title="<?php echo esc_attr( $product_header ); ?>"><?php echo ! empty( $product[ $key ] ) ? wp_kses_post( $product[ $key ] ) : ''; ?></td>
							<?php
						}
						echo '</tr>';
						?>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td class="empty-table" colspan="3"><?php echo esc_html_x( 'No data to display', 'message to show when no products data', 'affiliate-for-woocommerce' ); ?></td>
					</tr>
				<?php } ?>
			</tbody>
			<?php if ( ! empty( $products['rows'] ) && ! empty( $products['total_count'] ) && ( intval( $products['total_count'] ) > count( $products['rows'] ) ) ) { ?>
				<tfoot>
					<tr>
						<td colspan="3">
							<a id="afwc_load_more_products" data-max_record="<?php echo esc_attr( intval( $products['total_count'] ) ); ?>"><?php echo esc_html_x( 'Load more', 'Products load more link text in my account', 'affiliate-for-woocommerce' ); ?></a>
						</td>
					</tr>
				</tfoot>
			<?php } ?>
		</table>
	</div>
	<div class="afwc-payouts-section">
		<div class="afwc-table-header"><?php echo esc_html_x( 'Payout History', 'Payout history section title', 'affiliate-for-woocommerce' ); ?></div>
		<table class="afwc_payout_history">
			<thead>
			<?php
			if ( ! empty( $payout_headers ) && is_array( $payout_headers ) ) {
				echo '<tr>';
				foreach ( $payout_headers as $key => $payout_header ) {
					?>
					<th class="<?php esc_attr( $key ); ?>"><?php echo esc_html( $payout_header ); ?></th>
					<?php
				}
				echo '</tr>';
			}
			?>
			</thead>
			<tbody>
				<?php if ( ! empty( $payouts['payouts'] ) && is_array( $payouts['payouts'] ) && ! empty( $payout_headers ) && is_array( $payout_headers ) ) { ?>
					<?php
					foreach ( $payouts['payouts'] as $payout ) {
						echo '<tr>';
						foreach ( $payout_headers as $key => $payout_header ) {
							?>
							<td class="<?php echo esc_attr( $key ); ?>" data-title="<?php echo esc_attr( $payout_header ); ?>"><?php echo ! empty( $payout[ $key ] ) ? wp_kses_post( $payout[ $key ] ) : ''; ?></td>
							<?php
						}
						echo '</tr>';
						?>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td class="empty-table" colspan="4"><?php echo esc_html_x( 'No data to display', 'message to show when no payouts data', 'affiliate-for-woocommerce' ); ?></td>
					</tr>
				<?php } ?>
			</tbody>
			<?php if ( ! empty( $payouts['total_count'] ) && ! empty( $payouts['payouts'] ) && ( intval( $payouts['total_count'] ) > count( $payouts['payouts'] ) ) ) { ?>
				<tfoot>
					<tr>
						<td colspan="4">
							<a id="afwc_load_more_payouts" data-max_record="<?php echo esc_attr( intval( $payouts['total_count'] ) ); ?>"><?php echo esc_html_x( 'Load more', 'Payout load more link text in my account', 'affiliate-for-woocommerce' ); ?></button>
						</td>
					</tr>
				</tfoot>
			<?php } ?>
		</table>
	</div>
</div>
