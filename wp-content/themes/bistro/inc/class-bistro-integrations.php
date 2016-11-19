<?php
/**
 * Bistro_Integrations Class
 * Provides integrations with Storefront extensions by removing/changing incompatible controls/settings. Also adjusts default values
 * if they need to differ from the original setting.
 *
 * @author   WooThemes
 * @package  Bistro
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Bistro_Integrations' ) ) {
	/**
	 * The Bistro Integrations class
	 */
	class Bistro_Integrations {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'edit_controls' ), 99 );
			add_action( 'after_switch_theme', array( $this, 'edit_theme_mods' ) );
		}

		/**
		 * Remove unused/incompatible controls from the Customizer to avoid confusion
		 *
		 * @param array $wp_customize the Customizer object.
		 * @return void
		 */
		public function edit_controls( $wp_customize ) {
			/**
			 * Storefront Designer
			 */
			$wp_customize->remove_control( 'sd_header_layout' );
			$wp_customize->remove_control( 'sd_button_flat' );
			$wp_customize->remove_control( 'sd_button_shadows' );
			$wp_customize->remove_control( 'sd_button_background_style' );
			$wp_customize->remove_control( 'sd_button_rounded' );
			$wp_customize->remove_control( 'sd_button_size' );
			$wp_customize->remove_control( 'sd_header_layout_divider_after' );
			$wp_customize->remove_control( 'sd_button_divider_1' );
			$wp_customize->remove_control( 'sd_button_divider_2' );
			$wp_customize->remove_control( 'sd_header_sticky_navigation' );

			$wp_customize->remove_control( 'sp_homepage_category_columns' );
			$wp_customize->remove_control( 'sp_homepage_recent_products_columns' );
			$wp_customize->remove_control( 'sp_homepage_featured_products_columns' );
			$wp_customize->remove_control( 'sp_homepage_top_rated_products_columns' );
			$wp_customize->remove_control( 'sp_homepage_on_sale_products_columns' );
			$wp_customize->remove_control( 'sp_homepage_best_sellers_products_columns' );
		}

		/**
		 * Remove any pre-existing theme mods for settings that are incompatible with Bookshop.
		 *
		 * @return void
		 */
		public function edit_theme_mods() {
			/**
			 * Storefront WooCommerce Customiser
			 */
			remove_theme_mod( 'swc_homepage_category_columns' );
			remove_theme_mod( 'swc_homepage_recent_products_columns' );
			remove_theme_mod( 'swc_homepage_featured_products_columns' );
			remove_theme_mod( 'swc_homepage_top_rated_products_columns' );
			remove_theme_mod( 'swc_homepage_on_sale_products_columns' );
			remove_theme_mod( 'swc_homepage_best_sellers_products_columns' );
		}
	}
}

return new Bistro_Integrations();
