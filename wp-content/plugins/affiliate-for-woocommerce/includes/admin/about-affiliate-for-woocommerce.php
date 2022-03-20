<?php
/**
 * Affiliate For WooCommerce About/Landing page
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       1.0.0
 * @version     1.2.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
?>
<style type="text/css">
	.wrap.about-wrap,
	.afw-faq .has-3-columns.feature-section.col.three-col {
		max-width: unset !important;
	}
	.about-wrap h1 {
		margin: 0.2em 0;
	}
</style>
<script type="text/javascript">
	jQuery( function(){
		jQuery('#toplevel_page_woocommerce').find('a[href$="admin.php?page=affiliate-for-woocommerce"]').addClass('current');
		jQuery('#toplevel_page_woocommerce').find('a[href$="admin.php?page=affiliate-for-woocommerce"]').parent().addClass('current');
	});
</script>
<div class="wrap about-wrap">
	<h1><?php echo esc_html__( 'Thank you for installing Affiliate for WooCommerce', 'affiliate-for-woocommerce' ) . ' ' . esc_html( $plugin_data['Version'] ) . '!'; ?></h1>
	<p class="about-text"><?php echo esc_html__( 'Glad to have you onboard. We hope the plugin adds to your success 🏆', 'affiliate-for-woocommerce' ); ?></p>
	<?php
	if ( ( afwc_is_plugin_active( 'affiliates/affiliates.php' ) || afwc_is_plugin_active( 'affiliates-pro/affiliates-pro.php' ) ) && defined( 'AFFILIATES_TP' ) ) {
		$tables            = $wpdb->get_results( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . AFFILIATES_TP ) . '%' ), ARRAY_A ); // phpcs:ignore
		$show_notification = get_option( 'show_migrate_affiliates_notification', 'yes' );
		// Note: To test migration uncomment following code.
		if ( ! empty( $tables ) && 'no' !== $show_notification ) {
			?>
				<div>
					<div>
				<?php echo esc_html__( 'We discovered that you are using another "Affiliates" plugin. Do you want to migrate your existing data to this new Affiliates for WooCommerce plugin?', 'affiliate-for-woocommerce' ); ?>
							<span class="migrate_affiliates_actions">
								<a href="
							<?php
							echo esc_url(
								add_query_arg(
									array(
										'page'         => 'affiliate-for-woocommerce-settings',
										'migrate'      => 'affiliates',
										'is_from_docs' => 1,
									),
									admin_url( 'admin.php' )
								)
							);
							?>
											" class="button-primary" id="migrate_yes" ><?php echo esc_html__( 'Yes, Migrate existing data.', 'affiliate-for-woocommerce' ); ?></a>
								<a href="
								<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'         => 'affiliate-for-woocommerce-settings',
											'migrate'      => 'ignore_affiliates',
											'is_from_docs' => 1,
										),
										admin_url( 'admin.php' )
									)
								);
								?>
											" class="button" id="migrate_no" ><?php echo esc_html__( 'No, I want to start afresh.', 'affiliate-for-woocommerce' ); ?></a>
							</span>
						<p><?php echo esc_html__( 'Note: Once you migrate from Affiliates plugin, please deactivate it. Affiliates and Affiliate for WooCommerce can\'t work simultaneously.', 'affiliate-for-woocommerce' ); ?></p>
					</div>
				</div>
				<?php
		}
	}
	?>
	<div class="changelog">
		<div class="about-text">
			<span style="font-size: 22px;"><?php echo esc_html__( 'To get started:', 'affiliate-for-woocommerce' ); ?></span>
			<br>
			<?php
				echo sprintf(
					/* translators: Link to the Affiliate For WooCommerce Settings */
					esc_html__( 'Review and update your Affiliate For WooCommerce %s', 'affiliate-for-woocommerce' ),
					'<a class="button-primary" target="_blank" href="' . esc_url(
						add_query_arg(
							array(
								'page' => 'wc-settings',
								'tab'  => 'affiliate-for-woocommerce-settings',
							),
							admin_url( 'admin.php' )
						)
					) . '">' . esc_html__( 'Settings &rarr;', 'affiliate-for-woocommerce' ) . '</a>'
				);
				?>
				<br>
				<?php
					echo sprintf(
						/* translators: Link to the Affiliate For WooCommerce Dashboard in admin */
						esc_html__( 'Access affiliate %s.', 'affiliate-for-woocommerce' ),
						'<a target="_blank" href="' . esc_url(
							add_query_arg(
								array(
									'page' => 'affiliate-for-woocommerce',
								),
								admin_url( 'admin.php' )
							)
						) . '">' . esc_html__( 'dashboard', 'affiliate-for-woocommerce' ) . '</a>'
					);
					?>
		</div>
		<hr>
		<div class="afw-faq">
			<h3><?php echo esc_html__( 'Quick links', 'affiliate-for-woocommerce' ); ?></h3>
			<div class="has-3-columns feature-section col three-col">
				<div class="column col">
					<h4><?php echo esc_html__( 'How do I add an affiliate?', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-5">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
				<div class="column col">
					<h4><?php echo esc_html__( 'Where do affiliates login / get their stats from?', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-9">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
				<div class="column col last-feature">
					<h4><?php echo esc_html__( "Where's the link an affiliate will use to refer to my site?", 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-11">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
			</div>
			<div class="has-3-columns feature-section col three-col">
				<div class="column col">
					<h4><?php echo esc_html__( 'How to customize referral link for an affiliate?', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-customize-affiliate-referral-link/">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
				<div class="column col">
					<h4><?php echo esc_html__( 'How to give coupons to affiliates instead of link for referral?', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-create-and-assign-coupons-to-affiliates/">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
				<div class="column col last-feature">
					<h4><?php echo esc_html__( 'How to manually assign / unassign an order to an affiliate?', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-assign-unassign-an-order-to-an-affiliate/">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
			</div>
			<div class="has-3-columns feature-section col three-col">
				<div class="column col">
					<h4><?php echo esc_html__( 'Set different commission rates for affiliates', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-set-different-affiliate-commission-rates-for-affiliates/">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
				<div class="column col">
					<h4><?php echo esc_html__( 'Set different affiliate commission rates for product or product category', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-set-different-affiliate-commission-rates-for-product-or-product-category/">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
				<div class="column col last-feature">
					<h4><?php echo esc_html__( 'FAQ\'s', 'affiliate-for-woocommerce' ); ?></h4>
					<p><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo sprintf( esc_html__( 'Check %s.', 'affiliate-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-23">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>' ); ?></p>
				</div>
			</div>
			<br>
			<div>
				<?php
					echo sprintf(
						/* translators: Link to the Affiliate For WooCommerce documentation */
						esc_html__( 'View detailed documentation from %s.', 'affiliate-for-woocommerce' ),
						'<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/">' . esc_html__( 'here', 'affiliate-for-woocommerce' ) . '</a>'
					);
					?>
			</div>
		</div>
	</div>
</div>
<?php
