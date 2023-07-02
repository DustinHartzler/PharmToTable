<?php
/**
 * My Account > Affiliate > Profile
 *
 * @package  affiliate-for-woocommerce/templates/my-account/
 * @since    5.7.0
 * @version  1.2.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Note: We do not recommend removing existing classes in HTML.

global $affiliate_for_woocommerce;

$referral_url_pattern = ( 'yes' === $afwc_use_pretty_referral_links ) ? ( $pname . '/' ) : ( '?' . $pname . '=' );

?>
<div id="afwc_resources_wrapper">
	<div id="afwc_referral_url_container">
		<p id="afwc_id_change_wrap">
			<?php
			echo esc_html_x( 'Your affiliate identifier is: ', 'label for affiliate identifier', 'affiliate-for-woocommerce' ) . '<code>' . esc_html( $affiliate_identifier ) . '</code>';
			if ( 'yes' === $afwc_allow_custom_affiliate_identifier ) {
				?>
				<a href="#" id="afwc_change_identifier" title="<?php echo esc_attr_x( 'Click to change', 'label for click action to change affiliate identifier', 'affiliate-for-woocommerce' ); ?>"><i class="fa fa-pencil-alt"></i></a>
		</p>
		<p id="afwc_id_save_wrap" style="display: none" ><?php echo esc_html_x( 'Change affiliate identifier: ', 'label to change affiliate identifier', 'affiliate-for-woocommerce' ); ?>
			<input type="text" id="afwc_ref_url_id" value="<?php echo esc_attr( $affiliate_identifier ); ?>"/>
			<a href="#" id="afwc_save_identifier" name="afwc_save_identifier" title="<?php echo esc_html_x( 'Save', 'save button', 'affiliate-for-woocommerce' ); ?>"><i class="fa fa-save"></i></a>
			<a href="#" id="afwc_cancel_change_identifier" name="afwc_cancel_change_identifier" title="<?php echo esc_attr_x( 'Cancel', 'label to cancel the action to change affiliate identifier', 'affiliate-for-woocommerce' ); ?>"><i class="fa fa-light fa-ban"></i></a>
		</p>
		<p id="afwc_id_msg" style="display: none"></p>
		<p id="afwc_save_id_loader" style="display: none"><img src="<?php echo esc_url( WC()->plugin_url() . '/assets/images/wpspin-2x.gif' ); ?>" ></p>
		<p><?php echo esc_html_x( 'You can change above identifier to anything like your name, brand name.', 'description for changing affiliate identifier', 'affiliate-for-woocommerce' ); ?></p>
				<?php
			}
			?>
		<p><?php echo esc_html_x( 'Your referral URL is: ', 'affiliate referral url label', 'affiliate-for-woocommerce' ); ?>
			<code id="afwc_affiliate_link_label" title="<?php echo esc_attr_x( 'Click to copy', 'click to copy label for referral url', 'affiliate-for-woocommerce' ); ?>" onclick="afwc_copy_affiliate_link_coupon(this)"><?php echo esc_url( trailingslashit( apply_filters( 'afwc_referral_redirection_url', home_url(), $affiliate_id, array( 'source' => $affiliate_for_woocommerce ) ) ) . $referral_url_pattern ); ?><span class="afwc_ref_id_span"><?php echo esc_attr( $affiliate_identifier ); ?></code>
		</p>
	</div>
	<div id="afwc_referral_coupon_container">
		<?php
		if ( 'yes' === $afwc_use_referral_coupons ) {
			$afwc_coupon          = is_callable( array( 'AFWC_Coupon', 'get_instance' ) ) ? AFWC_Coupon::get_instance() : null;
			$referral_coupon_code = ( ! empty( $afwc_coupon ) && is_callable( array( $afwc_coupon, 'get_referral_coupon' ) ) ) ? $afwc_coupon->get_referral_coupon( array( 'user_id' => $user_id ) ) : array();

			if ( empty( $referral_coupon_code ) ) {
				if ( ( ! empty( $affiliate_manager_contact_email ) ) ) {
					?>
						<p>
						<?php echo esc_html_x( 'Want an exclusive coupon to promote?', 'label to get affiliate coupon', 'affiliate-for-woocommerce' ); ?>
							<a href="mailto:<?php echo esc_attr( $affiliate_manager_contact_email ); ?>?subject=[Affiliate Partner] Send me an exclusive coupon&body=Hi%20there%0D%0A%0D%0APlease%20send%20me%20a%20affiliate%20coupon%20for%20running%20a%20promotion.%0D%0A%0D%0AThanks%0D%0A%0D%0A">
							<?php echo esc_html_x( 'Request affiliate manager for a coupon', 'label to request a coupon from affiliate manager', 'affiliate-for-woocommerce' ); ?>
							</a>
						</p>
						<?php
				}
			} else {
				echo esc_html_x( 'Your referral coupon details: ', 'affiliate referral coupon details', 'affiliate-for-woocommerce' );
				?>
				<table class="woocommerce-table shop_table afwc_coupons">
					<thead>
						<tr>
							<th>
							<?php echo esc_html_x( 'Coupon code', 'coupon code/name', 'affiliate-for-woocommerce' ); ?>
							</th>
							<th>
							<?php echo esc_html_x( 'Amount', 'coupon discount amount', 'affiliate-for-woocommerce' ); ?>
							</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if ( is_array( $referral_coupon_code ) ) {
						foreach ( $referral_coupon_code as $coupon_id => $coupon_code ) {
							$coupon_params = ( ! empty( $afwc_coupon ) && is_callable( array( $afwc_coupon, 'get_coupon_params' ) ) ) ? $afwc_coupon->get_coupon_params( $coupon_code ) : array();
							if ( ! empty( $coupon_params ) ) {
								if ( isset( $coupon_params['discount_amount'] ) && ! empty( $coupon_params['discount_type'] ) ) {
									$coupon_discount_amount = $coupon_params['discount_amount'];
									$coupon_discount_type   = $coupon_params['discount_type'];
									if ( in_array( $coupon_discount_type, array( 'percent', 'sign_up_fee_percent', 'recurring_percent' ), true ) ) {
										$coupon_with_discount = wp_kses_post( $coupon_discount_amount ) . '%';
									} else {
										$coupon_with_discount = wp_kses_post( AFWC_CURRENCY ) . wc_format_decimal( $coupon_discount_amount, wc_get_price_decimals() );
									}
									?>
									<tr>
										<td>
											<code id="afwc_referral_coupon" title="<?php echo esc_attr_x( 'Click to copy', 'click to copy label for coupon code', 'affiliate-for-woocommerce' ); ?>" onclick="afwc_copy_affiliate_link_coupon(this)"><?php echo esc_html( $coupon_code ); ?></code>
										</td>
										<td>
											<span>
												<?php
													echo esc_attr__( $coupon_with_discount ); // phpcs:ignore
												?>
											</span>
										</td>
									<?php
								}
							}
							?>
							</tr>
							<?php
						}
					}
					?>
					</tbody>
				</table>
				<?php
			}
		}
		?>
	</div>
	<div id="afwc_custom_referral_url_container">
		<p><strong><?php echo esc_html_x( 'Referral URL generator', 'label to generate custom referral url', 'affiliate-for-woocommerce' ); ?></strong></p>
		<p><?php echo esc_html_x( 'Page URL', 'label for page url', 'affiliate-for-woocommerce' ); ?>:
			<span id="afwc_custom_referral_url">
				<?php echo esc_url( trailingslashit( home_url() ) ); ?>
				<input type="text" id="afwc_affiliate_link" name="afwc_affiliate_link" placeholder="<?php echo esc_html_x( 'Enter target path here...', 'label to add any site page for custom referral url', 'affiliate-for-woocommerce' ); ?>">
				<?php echo wp_kses_post( $referral_url_pattern ); ?><span class="afwc_ref_id_span"><?php echo esc_attr( $affiliate_identifier ); ?></span>
			</span>
		</p>
		<p><?php echo esc_html_x( 'Referral URL: ', 'custom referral url', 'affiliate-for-woocommerce' ); ?>
			<code id="afwc_generated_affiliate_link" title="<?php echo esc_attr_x( 'Click to copy', 'click to copy label for custom referral url', 'affiliate-for-woocommerce' ); ?>" onclick="afwc_copy_affiliate_link_coupon(this)"><?php echo esc_url( trailingslashit( home_url() ) . $referral_url_pattern ); ?><span class="afwc_ref_id_span"><?php echo esc_attr( $affiliate_identifier ); ?></span></code>
		</p>
	</div>
	<?php
	if ( 'yes' === get_option( 'afwc_allow_paypal_email', 'no' ) ) {
		$afwc_paypal_email = get_user_meta( $user_id, 'afwc_paypal_email', true );
		?>
			<hr>
			<div id="afwc_payout_details_container">
				<form id="afwc_account_form" action="" method="post">
					<h4><?php echo esc_html_x( 'Payment setting', 'label for payout/payment setting', 'affiliate-for-woocommerce' ); ?></h4>
					<div id="afwc_payment_wrapper">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="afwc_affiliate_paypal_email"><?php echo esc_html_x( 'PayPal email address', 'label for PayPal email address for payouts', 'affiliate-for-woocommerce' ); ?></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="afwc_affiliate_paypal_email" id="afwc_affiliate_paypal_email" value="<?php echo esc_attr( $afwc_paypal_email ); ?>" /><br>
							<em><?php echo esc_html_x( 'You will receive your affiliate commission on the above PayPal email address.', 'description for PayPal email address payout', 'affiliate-for-woocommerce' ); ?></em>
						</p>
						<p>
							<button type="submit" id="afwc_save_account_button" name="afwc_save_account_button"><?php echo esc_html_x( 'Save', 'save button', 'affiliate-for-woocommerce' ); ?></button>
							<span class="afwc_save_account_status"></span>
						</p>
					</div>
				</form>
			</div>
			<?php
	}
	if ( ! empty( $affiliate_manager_contact_email ) ) {
		?>
		<div id="afwc_contact_admin_container">
			<?php echo esc_html_x( 'Have any queries?', 'label for any queries', 'affiliate-for-woocommerce' ); ?>
			<a href="mailto:<?php echo esc_attr( $affiliate_manager_contact_email ); ?>">
				<?php echo esc_html_x( 'Contact affiliate manager', 'label to contact affiliate manager', 'affiliate-for-woocommerce' ); ?>
			</a>
		</div>
		<?php
	}
	?>
</div>
<?php
