<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>
<div class="state-asset-optimization state state-no-search">
	<span class="label tcb-hide"><?php echo esc_html__( 'Asset Optimization', 'thrive-cb' ); ?></span>
	<div class="">
		<section class="asset-optimization-message">
			<span class="message"><?php echo esc_html__( 'These optimization settings apply to WordPress core or 3rd party assets and may affect 3rd party plugin or theme functionality.', 'thrive-cb' ); ?></span>
			<br>
			<span class="message"><?php echo esc_html__( 'Please read the documentation for each and thoroughly test your website after making any changes to them.', 'thrive-cb' ); ?></span>
		</section>
		<section class="asset-optimization-settings">
			<?php
			$is_selected_gutenberg = get_post_meta( get_the_id(), \TCB\Lightspeed\Gutenberg::DISABLE_GUTENBERG, true );
			?>
			<label class="asset-optimization-label"><?php echo esc_html__( 'Gutenberg assets', 'thrive-cb' ); ?></label>
			<select name="<?php echo \TCB\Lightspeed\Gutenberg::DISABLE_GUTENBERG; ?>" data-fn="selectAssetOptimization" class="change">
				<option value="inherit" <?php selected( $is_selected_gutenberg, 'inherit' ); ?>><?php echo esc_html__( 'Inherit', 'thrive-cb' ); ?> </option>
				<option value="enable" <?php selected( $is_selected_gutenberg, '1' ); ?>><?php echo esc_html__( 'Enable', 'thrive-cb' ); ?> </option>
				<option value="disable" <?php selected( $is_selected_gutenberg, '0' ); ?>><?php echo esc_html__( 'Disable', 'thrive-cb' ); ?> </option>
			</select>
			<?php if ( \TCB\Integrations\WooCommerce\Main::active() ): ?>
				<?php
				$is_selected_woocommerce = get_post_meta( get_the_id(), \TCB\Lightspeed\Woocommerce::DISABLE_WOOCOMMERCE, true );
				?>
				<label class="asset-optimization-label"><?php echo esc_html__( 'WooCommerce assets', 'thrive-cb' ); ?></label>
				<select name="<?php echo TCB\Lightspeed\Woocommerce::DISABLE_WOOCOMMERCE; ?>" data-fn="selectAssetOptimization" class="change">
					<option value="inherit" <?php selected( $is_selected_woocommerce, 'inherit' ); ?>><?php echo esc_html__( 'Inherit', 'thrive-cb' ); ?> </option>
					<option value="enable" <?php selected( $is_selected_woocommerce, '1' ); ?>><?php echo esc_html__( 'Enable', 'thrive-cb' ); ?> </option>
					<option value="disable" <?php selected( $is_selected_woocommerce, '0' ); ?>><?php echo esc_html__( 'Disable', 'thrive-cb' ); ?> </option>
				</select>
			<?php endif; ?>
		</section>
	</div>
</div>
