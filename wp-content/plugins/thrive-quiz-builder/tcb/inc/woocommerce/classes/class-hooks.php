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
 * Class Hooks
 *
 * @package TCB\Integrations\WooCommerce
 */
class Hooks {
	public static function add() {
		static::add_actions();
		static::add_filters();
	}

	public static function add_actions() {
		add_action( 'tcb_editor_iframe_after', array( __CLASS__, 'tcb_editor_iframe_after' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'wp_print_footer_scripts' ), 9 );

		add_action( 'tcb_output_components', array( __CLASS__, 'tcb_output_components' ) );

		add_action( 'rest_api_init', array( __CLASS__, 'rest_api_init' ) );
	}

	public static function add_filters() {
		add_filter( 'wp_list_categories', array( __CLASS__, 'wp_list_categories' ), 10, 2 );

		add_filter( 'tve_frontend_options_data', array( __CLASS__, 'tve_frontend_data' ) );
	}

	/**
	 * Include WooCommerce icons for sidebar elements
	 */
	public static function tcb_editor_iframe_after() {
		include TVE_TCB_ROOT_PATH . 'inc/woocommerce/assets/icons.svg';
	}

	/**
	 * Enqueue scripts needed by WooCommerce
	 */
	public static function enqueue_scripts() {
		if ( ! is_admin() ) {
			wp_enqueue_script( 'selectWoo' );
			wp_enqueue_style( 'select2' );
		}
	}

	/**
	 * Add some backbone templates for the editor.
	 */
	public static function wp_print_footer_scripts() {
		if ( TCB_Editor()->is_inner_frame() ) {
			$templates = tve_dash_get_backbone_templates( TVE_TCB_ROOT_PATH . 'inc/woocommerce/views/backbone' );

			tve_dash_output_backbone_templates( $templates, 'tve-woocommerce-' );
		}
	}

	/**
	 * Include WooCommerce editor components
	 */
	public static function tcb_output_components() {
		$path  = TVE_TCB_ROOT_PATH . 'inc/woocommerce/views/components/';
		$files = array_diff( scandir( $path ), array( '.', '..' ) );

		foreach ( $files as $file ) {
			include $path . $file;
		}
	}

	/**
	 * Initialize the rest api class
	 */
	public static function rest_api_init() {
		require_once TVE_TCB_ROOT_PATH . 'inc/woocommerce/classes/class-rest-api.php';

		Rest_Api::register_routes();
	}

	/**
	 * Remove parenthesis from category count
	 *
	 * @param String $output
	 * @param array  $args
	 *
	 * @return string|string[]
	 */
	public static function wp_list_categories( $output, $args ) {

		if ( ! empty( $args['walker'] ) && $args['walker'] instanceof \WC_Product_Cat_List_Walker ) {
			$output = preg_replace( '/(class=\"count\"[^>]*>)\D*(\d*)[^<]*/', '$1$2', $output );
		}

		return $output;
	}

	/**
	 * Add some data to the frontend localized object
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public static function tve_frontend_data( $data ) {
		$data['woo_shop_route'] = get_rest_url( get_current_blog_id(), 'tcb/v1' . '/woo' );

		return $data;
	}
}
