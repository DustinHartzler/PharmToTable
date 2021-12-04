<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-leads
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/* Display TL Groups on Shop page */
add_filter( 'thrive_leads_is_page', 'tve_leads_is_shop_template' );
add_filter( 'thrive_leads_current_post', 'tve_leads_get_shop_template' );

/**
 * Checks if the page is a shop page
 *
 * @param $is_page
 *
 * @return bool|mixed
 */
function tve_leads_is_shop_template( $is_page ) {
	if ( class_exists( 'WooCommerce', false ) && is_shop() ) {
		$is_page = true;
	}

	return $is_page;
}

/**
 * Returns the shop page
 *
 * @param $post
 *
 * @return array|mixed|\WP_Post|null
 */
function tve_leads_get_shop_template( $post ) {
	if ( class_exists( 'WooCommerce', false ) && is_shop() ) {
		$post = get_post( wc_get_page_id( 'shop' ) );
	}

	return $post;
}
