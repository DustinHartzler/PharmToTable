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
 * Class Main
 *
 * @package TCB\Integrations\WooCommerce
 */
class Main {

	const POST_TYPE = 'product';

	/**
	 * Used as a proxy for calling woo functions only when the plugin is active
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic( $name, $arguments ) {

		if ( method_exists( __CLASS__, $name ) ) {
			return call_user_func_array( array( __CLASS__, $name ), $arguments );
		}

		if ( function_exists( $name ) && static::active() ) {
			/* dynamic call woocommerce functions if they exist */
			return call_user_func_array( $name, $arguments );
		}

		return false;
	}

	/**
	 * Add WooCommerce support
	 */
	public static function init() {
		if ( static::active() ) {
			static::includes();

			Hooks::add();

			Widgets::init();
			Shortcodes\Shop\Main::init();
			Shortcodes\Inline\Main::init();

			if ( current_theme_supports( 'tve-wc-mini-cart' ) ) {
				Shortcodes\MiniCart\Main::init();
			}
		}
	}

	/**
	 * @param string $subpath
	 *
	 * @return string
	 */
	public static function get_integration_path( $subpath = '' ) {
		return TVE_TCB_ROOT_PATH . 'inc/woocommerce/' . $subpath;
	}

	public static function includes() {
		$integration_path = static::get_integration_path();

		require_once $integration_path . 'classes/class-hooks.php';

		require_once $integration_path . 'classes/shortcodes/inline/class-main.php';
		require_once $integration_path . 'classes/shortcodes/shop/class-main.php';
		require_once $integration_path . 'classes/shortcodes/mini-cart/class-main.php';
		require_once $integration_path . 'classes/class-widgets.php';
	}

	/**
	 * Get all the data that we want to localize for WooCommerce
	 *
	 * @return array
	 */
	public static function get_localized_data() {
		$is_active = static::active();

		$data = array(
			'is_active' => $is_active ? 1 : 0,
		);

		/* localize the rest of the data only if woo is active */
		if ( $is_active ) {
			$data = array_merge(
				$data,
				array(
					'is_woo_page'             => static::is_woo_page(),
					'is_shop'                 => is_shop(),
					'shop_identifier'         => '.tcb-woo-shop',
					'sub_element_identifiers' => Shortcodes\Shop\Main::get_sub_element_identifier(),
				),
				Shortcodes\Inline\Main::get_localized_data()
			);
		}

		return $data;
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	public static function active() {
		return class_exists( 'WooCommerce', false );
	}

	/**
	 * Some hooks are not included by woo during ajax, so we include them manually by calling woo functions
	 */
	public static function init_frontend_woo_functionality() {
		if ( class_exists( 'WooCommerce' ) ) {
			\WooCommerce::instance()->frontend_includes();
		}

		/* initialize the cart - we don't have any logic here for that, but some plugins have hooks on it. */
		if ( function_exists( 'wc_load_cart' ) ) {
			wc_load_cart();
		}
	}

	/**
	 * Return WooCommerce shop url
	 *
	 * @return mixed
	 */
	public static function get_shop_url() {
		return wc_get_page_permalink( 'shop' );
	}

	/**
	 * Return WooCommerce cart url
	 *
	 * @return string
	 */
	public static function get_cart_url() {
		return wc_get_cart_url();
	}

	/**
	 * Return WooCommerce checkout url
	 *
	 * @return string
	 */
	public static function get_checkout_url() {
		return wc_get_checkout_url();
	}

	/**
	 * Check if we're on a woo page
	 *
	 * @return bool
	 */
	public static function is_woo_page() {
		return static::active() && ( is_shop() || is_cart() || is_checkout() || is_account_page() );
	}
}
