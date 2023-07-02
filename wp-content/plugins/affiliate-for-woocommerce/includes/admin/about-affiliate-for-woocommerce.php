<?php
/**
 * Affiliate For WooCommerce About/Landing page
 *
 * @package   affiliate-for-woocommerce/includes/admin/
 * @since     1.0.0
 * @version   1.2.5
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
?>
<style type="text/css">
	.wrap.about-wrap,
	.has-2-columns.feature-section.col.two-col {
		max-width: unset !important;
	}
	.afw .has-2-columns.feature-section.col.two-col {
		margin-top: 1rem;
	}
	.about-wrap .column {
		margin-left: 0;
	}
	.about-text h2 {
		text-align: left;
		margin: 1rem 0;
	}
	.afw-faq li {
		margin-bottom: 1rem;
	}
	.afw-faq {
		margin-bottom: 2rem !important;
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
	<div class="changelog afw">
		<div class="has-2-columns feature-section col two-col">
			<div class="column col">
				<p class="about-text"><?php echo esc_html__( 'Glad to have you onboard. We hope the plugin adds to your success 🏆', 'affiliate-for-woocommerce' ); ?></p>
			</div>
			<div class="column col last-feature">
				<p align="right">
					<a target="_blank" href="<?php echo esc_url( 'https://woocommerce.com/my-account/create-a-ticket/' ); ?>"><?php echo esc_html_x( 'Contact us', 'link to contact support', 'affiliate-for-woocommerce' ); ?></a> | 
					<a target="_blank" href="<?php echo esc_url( 'https://woocommerce.com/document/affiliate-for-woocommerce/' ); ?>"><?php echo esc_html_x( 'Docs', 'link to documentation', 'affiliate-for-woocommerce' ); ?></a>
				</p>
			</div>
		</div>
		<div class="about-text feature-section">
			<h2><?php echo esc_html__( 'To get started:', 'affiliate-for-woocommerce' ); ?></h2>
			<br>
			<?php
				echo sprintf(
					/* translators: Link to the Affiliate For WooCommerce Settings */
					esc_html__( '1. Review and update your Affiliate For WooCommerce %s', 'affiliate-for-woocommerce' ),
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
						esc_html__( '2. Access affiliate %s.', 'affiliate-for-woocommerce' ),
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
				<br>
				<?php
					echo sprintf(
						/* translators: Link to the Affiliate For WooCommerce Plans Dashboard in admin */
						esc_html__( '3. Setup default commission rate for Storewide Default Commission in %s.', 'affiliate-for-woocommerce' ),
						'<a target="_blank" href="' . esc_url(
							add_query_arg(
								array(
									'page' => 'affiliate-for-woocommerce#!/plans',
								),
								admin_url( 'admin.php' )
							)
						) . '">' . esc_html__( 'plans', 'affiliate-for-woocommerce' ) . '</a>'
					);
					?>
		</div>
		<div class="afw-faq">
			<h3><?php echo esc_html__( 'How to\'s', 'affiliate-for-woocommerce' ); ?></h3>
			<ol>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-5">' . esc_html__( 'How do I add/make a user an affiliate?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-9">' . esc_html__( 'Where do affiliates login and get their stats from?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-11">' . esc_html__( 'Where\'s the link an affiliate will use to refer to my site?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-customize-affiliate-referral-link/">' . esc_html__( 'How to find, customize and share an affiliate referral link', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-create-and-assign-coupons-to-affiliates/">' . esc_html__( 'How to give coupons for referral to affiliates?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-assign-unassign-an-order-to-an-affiliate/">' . esc_html__( 'How to manually assign/unassign an order to an affiliate?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-create-affiliate-commission-plans/">' . esc_html__( 'How to create affiliate commission plans', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-set-different-affiliate-commission-rates-for-affiliates/">' . esc_html__( 'How to set different affiliate commission rates for affiliates', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-set-different-affiliate-commission-rates-for-product-or-product-category/">' . esc_html__( 'How to set different affiliate commission rates for product or product category', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-set-up-a-multilevel-referral-multi-tier-affiliate-program/">' . esc_html__( 'How to set up a multilevel referral/multi-tier affiliate program?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-set-up-lifetime-commissions/">' . esc_html__( 'How to set up lifetime commissions', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-export-affiliate-data-to-csv/">' . esc_html__( 'How to export affiliate data to CSV?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/how-to-payout-commissions-in-affiliate-for-woocommerce/">' . esc_html__( 'How to payout commissions to affiliates and check all the processed payouts?', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
				<li><?php /* translators: Link to the Affiliate For WooCommerce Doc */ echo '<a target="_blank" href="https://woocommerce.com/document/affiliate-for-woocommerce/#section-26">' . esc_html__( 'FAQ\'s', 'affiliate-for-woocommerce' ) . '</a>'; ?></li>
			</ol>
		</div>
		<p><?php echo esc_html__( 'To re-visit this page, go to Plugins > Affiliate For WooCommerce and click on "Getting started".', 'affiliate-for-woocommerce' ); ?></p>
	</div>
</div>
<?php
