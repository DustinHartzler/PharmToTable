<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="catalog-settings-wrap">
	<h2><?php esc_html_e( 'Facebook Catalog', 'woocommerce-conversion-tracking-pro' ); ?></h2>
	<?php
	$data_feed      = get_option( 'wcct_data_feed' );
	$auth           = wcct_get_auth();
	$username       = isset( $auth['username'] ) ? $auth['username'] : '';
	$password       = isset( $auth['password'] ) ? $auth['password'] : '';
	$authenticate   = isset( $auth['wcct-authenticate-enable'] ) ? $auth['wcct-authenticate-enable'] : '';
	?>
	<div class="wcct-catalog">
		<p class="description">
			<?php esc_html_e( 'Facebook Product Catalog or simply Catalog is the uploaded list of the products usually promoted by Facebook Dynamic Ads or Collection Ad Format. The list of products of the catalog normally includes information such as Product ID, Description, Name, URL, Image URL and other attributes that are required to create the Ads for those uploaded products.', 'woocommerce-conversion-tracking-pro' ); ?>
		</p>
		<h2 class="instruction-header"><?php esc_html_e( 'Setup Instructions', 'woocommerce-conversion-tracking-pro' ); ?></h2>
		<ol class="wcct-catalog-doc">
			<li>Go to <a href="https://www.facebook.com/products/" target="_blank"><?php esc_html_e( 'products catalog', 'woocommerce-conversion-tracking-pro' ); ?></a>.</li>
			<li>Click <strong>Create Catalog</strong>.</li>
			<li>Select <strong>E-commerce</strong>.</li>
			<li>Provide a catalog name and create catalog.</li>
			<li>Then, in the <strong>Data Sources</strong> tab, click <strong>Add Data Source</strong>.</li>
			<li>Select <strong>Set a Schedule</strong>, copy the following data feed URL and set it as facebook data feed url.</li>
			<li>Click start upload.</li>
		</ol>

		<p class="help">
			<?php
			/* translators: %s: catalog doc link */
			wp_kses_post( sprintf( __( '<a href="%s" target="_blank">Learn more</a> on setting up product catalog.', 'woocommerce-conversion-tracking-pro' ), 'https://wedevs.com/docs/woocommerce-conversion-tracking/facebook/facebook-product-catalog/?utm_source=wp-admin&utm_medium=inline-help&utm_campaign=wcct_docs&utm_content=FB_Product_Catalog' ) );
			?>
		</p>
	</div>

	<div class="wcct-catalog">
		<div class="wcct-product-form">
			<form action="" method="post" id="wcct-authenticate-form" >
				<table class="form-table">
					<tbody>
					<tr>
						<th><?php esc_html_e( 'Exclude Products', 'woocommerce-conversion-tracking-pro' ); ?></th>
						<td>
							<h4><?php esc_html_e( 'By Product Types:', 'woocommerce-conversion-tracking-pro' ); ?></h4>
							<?php foreach ( $product_type as $key => $value ) : ?>
								<?php
								$types  = isset( $data_feed['types'] ) ? $data_feed['types'] : array();
								$p_type = in_array( $key, $types ) ? $key : '';
								?>
								<fieldset>
									<label for="types-<?php echo esc_attr( $key ); ?>">
										<input type="checkbox" name="types[]" id="types-<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( $key, $p_type ); ?>>
										<?php echo esc_html( $value ); ?>
									</label>
								</fieldset>
							<?php endforeach ?>

							<h4><?php esc_html_e( 'By Product Categories:', 'woocommerce-conversion-tracking-pro' ); ?></h4>
							<?php
							$selected_cats = isset( $data_feed['product_cat'] ) ? $data_feed['product_cat'] : false;
							$page_id = 0;
							wcct_category_checklist( $page_id, $selected_cats, $attr = array(), $class = null );
							?>

							<?php wp_nonce_field( 'wcct-authenticate' ); ?>
							<input type="hidden" name="action" value="wcct_save_authenticate">
							<button class="button button-primary" id="wcct-authenticate-submit" style="margin-top:20px"><?php esc_html_e( 'Save Changes', 'woocommerce-conversion-tracking-pro' ); ?></button>
						</td>
					</tr>

					</tbody>
				</table>
			</form>
		</div>
	</div>

	<div class="wcct-catalog wcct-data-url">
		<p>
			<strong><?php esc_html_e( 'Product Data Feed URL', 'woocommerce-conversion-tracking-pro' ); ?></strong>
		</p>
		<input id="link" class="regular-text code" type="text" value="<?php echo esc_url( site_url( '/' ) ) . '?action=wcct-product-export'; ?>" readonly>

		<span class="wcct-copy-clipboard wcct-tooltip" data-clipboard-target="#link">Copy
			<span class="wcct-tooltiptext clip-board-tooltip"><?php esc_html_e( 'Copy to clipboard', 'woocommerce-conversion-tracking-pro' ); ?></span>
		</span>
	</div>
</div>
