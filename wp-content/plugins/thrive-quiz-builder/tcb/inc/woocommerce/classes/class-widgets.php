<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\Integrations\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Widgets
 *
 * @package TCB\Integrations\WooCommerce
 */
class Widgets {
	/* */
	public static function init() {
		static::add_actions();
		static::add_filters();
	}

	public static function add_actions() {
		add_action( 'tcb_ajax_before_widget_render', array( __CLASS__, 'tcb_ajax_before_widget_render' ) );
	}

	public static function add_filters() {
		add_filter( 'tve_include_widgets_in_editor', '__return_true' );

		add_filter( 'tcb_editor_widgets', array( __CLASS__, 'tcb_editor_widgets' ), 10 );

		add_filter( 'tcb_widget_element_category', array( __CLASS__, 'tcb_widget_element_category' ), 10, 2 );

		add_filter( 'tcb_widget_element_icon', array( __CLASS__, 'tcb_widget_element_icon' ), 10, 2 );

		add_filter( 'tcb_widget_data_widget_woocommerce_widget_cart', array( __CLASS__, 'cart_widget_data' ) );

		add_filter( 'sidebars_widgets', array( __CLASS__, 'sidebars_widgets' ) );
	}

	/**
	 * Filter allowed widgets in the editor
	 *
	 * @param $widgets
	 *
	 * @return mixed
	 */
	public static function tcb_editor_widgets( $widgets ) {

		$widgets = array_filter( $widgets, static function ( $widget ) {
			/* @var \WP_Widget $widget */
			return strpos( $widget->id_base, 'woocommerce' ) !== false && strpos( $widget->id_base, 'filter' ) === false;
		} );

		return $widgets;
	}

	/**
	 * Group WooCommerce widgets in one category
	 *
	 * @param string     $category
	 * @param \WP_Widget $widget
	 *
	 * @return string
	 */
	public static function tcb_widget_element_category( $category, $widget ) {

		if ( strpos( $widget->id_base, 'woocommerce' ) !== false ) {
			$category = 'WooCommerce';
		}

		return $category;
	}

	/**
	 * Custom icons for WooCommerce icons
	 *
	 * @param string     $icon
	 * @param \WP_Widget $widget
	 *
	 * @return string
	 */
	public static function tcb_widget_element_icon( $icon, $widget ) {

		if ( strpos( $widget->id_base, 'woocommerce' ) !== false ) {
			$icon = (string) $widget->id_base;
		}

		return $icon;
	}

	/**
	 * If the cart is empty, temporarily add a few products to the cart so we can show some content in the editor
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public static function cart_widget_data( $data ) {
		$woo_cart = wc()->cart->get_cart();

		if ( empty( $woo_cart ) ) {
			$dummy_products = get_posts( array(
				'posts_per_page' => 3,
				'post_type'      => 'product',
				'orderby'        => 'rand',
			) );

			foreach ( $dummy_products as $product ) {
				try {
					WC()->cart->add_to_cart( $product->ID );
				} catch ( \Exception $e ) {
					if ( $e->getMessage() ) {
						wc_add_notice( $e->getMessage(), 'error' );
					}
				}
			}

			ob_start();
			woocommerce_mini_cart();
			$mini_cart = ob_get_clean();

			/* replace the empty wrapper with the populated equivalent */
			$data['content'] = str_replace(
				'<div class="widget_shopping_cart_content"></div>',
				'<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				$data['content'] );

			$data['cart_is_empty'] = empty( $woo_cart );

			/* empty the cart to remove the products that we just added */
			WC()->cart->empty_cart();
		}

		return $data;
	}

	/**
	 * Set the query as being the shop when trying to render widgets
	 */
	public static function tcb_ajax_before_widget_render() {
		if ( isset( $_GET['widget'] ) && strpos( $_GET['widget'], 'woocommerce', true ) !== false && ! empty( $_GET['query'] ) ) {

			/** @var \WP_Query */
			global $wp_query;
			/* set the global query the same one we we're editing on so we can convert shortcodes better */

			$wp_query->query( $_GET['query'] );
			if ( $wp_query->is_singular() ) {
				/* product page */
				$wp_query->the_post();
				wc_setup_product_data( get_the_ID() );
			} else {
				/* shop page */
				\WooCommerce::instance()->query->product_query( $wp_query );
			}
		}
	}

	/**
	 * Filter for sidebar widgets. On product pages we always want to track recent viewed because widgets can be used outside of the sidebar.
	 *
	 * @param array $sidebars_widgets
	 *
	 * @return array
	 */
	public static function sidebars_widgets( $sidebars_widgets ) {

		if ( ! is_admin() && is_product() ) {
			$sidebars_widgets['dummy-widgets'] = array( 'woocommerce_recently_viewed_products' );
		}

		return $sidebars_widgets;
	}
}
