<?php
/**
 * Bistro template functions.
 *
 * @package bistro
 */

if ( ! function_exists( 'bistro_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function bistro_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		storefront_cart_link();
		$fragments['.bistro-header-count'] = ob_get_clean();

		return $fragments;
	}
}

/**
 * Specified how many categories to display on the homepage
 *
 * @param array $args The arguments used to control the layout of the homepage category section.
 */
function bistro_homepage_categories( $args ) {
	$args['limit']   = 6;
	$args['columns'] = 3;

	return $args;
}

/**
 * Specified how many categories to display on the homepage
 *
 * @param array $args The arguments used to control the layout of the homepage category section.
 */
function bistro_homepage_products( $args ) {
	$args['limit']   = 9;
	$args['columns'] = 3;

	return $args;
}

/**
 * Display the product category description
 */
function bistro_product_category_description( $category ) {
	$cat_id      = $category->term_id;
	$prod_term   = get_term( $cat_id, 'product_cat' );
	$description = $prod_term->description;

	echo '<div class="shop_cat_desc">' . $description . '</div>';
}

/**
 * Category description wrapper
 */
function bistro_product_category_description_title_wrap() {
	echo '<section class="bistro-category-title-description-wrap">';
}

/**
 * Product loop - image wrapper
 */
function bistro_image_wapper() {
	echo '<section class="image-wrap">';
}

/**
 * Close wrappers
 */
function bistro_wrapper_close() {
	echo '</section>';
}

/**
 * Rating / add to cart button wrapper
 * @return void
 */
function bistro_rating_button_wrapper() {
	echo '<section class="bistro-rating-cart-button">';
}