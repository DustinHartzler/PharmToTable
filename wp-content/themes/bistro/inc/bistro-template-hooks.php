<?php
/**
 * Bistro hooks
 *
 * @package bistro
 */

add_action( 'init', 'bistro_hooks' );

/**
 * Add and remove Bistro/Storefront functions.
 *
 * @return void
 */
function bistro_hooks() {
	remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper',       42 );
	remove_action( 'storefront_header', 'storefront_primary_navigation',               50 );
	remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );

	add_action( 'storefront_header', 'storefront_primary_navigation',                  20 );

	add_filter( 'woocommerce_add_to_cart_fragments',     'bistro_cart_link_fragment' );

	add_action( 'woocommerce_shop_loop_subcategory_title', 'bistro_product_category_description_title_wrap', 5 );
	add_action( 'woocommerce_shop_loop_subcategory_title', 'bistro_product_category_description', 15 );
	add_action( 'woocommerce_shop_loop_subcategory_title', 'bistro_wrapper_close', 20 );

	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action( 'woocommerce_after_shop_loop_item', 'bistro_rating_button_wrapper', 6 );
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 7 );
	add_action( 'woocommerce_after_shop_loop_item', 'bistro_wrapper_close', 11 );

	add_action( 'woocommerce_before_shop_loop_item_title', 'bistro_image_wapper', 9 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'bistro_wrapper_close', 11 );
}
