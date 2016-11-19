<?php
/**
 * Plugged functions
 * Any functions declared here are overwriting counterparts from a plugin or Storefront core.
 *
 * @package storechild
 */

/**
 * Cart Link
 * @since  1.0.0
 */
if ( ! function_exists( 'storefront_cart_link' ) ) {
	function storefront_cart_link() {
		?>
			<div class="bistro-header-count count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></div>
		<?php
	}
}