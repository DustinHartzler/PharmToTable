<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TVE\Dashboard\Automator;

use function get_user_by;
use function thrive_automator_register_action;
use function thrive_automator_register_action_field;
use function thrive_automator_register_data_field;
use function thrive_automator_register_data_object;
use function thrive_automator_register_trigger;
use function wc_get_order;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Main
 *
 * @package TVE\Dashboard\Automator
 */
class Main {

	public static function init() {
		if ( defined( 'THRIVE_AUTOMATOR_RUNNING' )
		     && ( ( defined( 'TVE_DEBUG' ) && TVE_DEBUG )
		          || ( defined( 'TAP_VERSION' ) && version_compare( TAP_VERSION, '1.0', '>=' ) ) ) ) {
			static::add_hooks();
		}
	}

	/**
	 * @param string $subpath
	 *
	 * @return string
	 */
	public static function get_integration_path( $subpath = '' ) {
		return TVE_DASH_PATH . '/inc/automator/' . $subpath;
	}

	public static function add_hooks() {

		static::load_apps();
		static::load_data_objects();
		static::load_fields();
		static::load_action_fields();
		static::load_actions();
		static::load_triggers();

		static::launch_woocommerce_extra_hooks();

		add_action( 'tap_output_extra_svg', array( 'TVE\Dashboard\Automator\Main', 'display_icons' ) );
	}

	public static function load_triggers() {
		foreach ( static::load_files( 'triggers' ) as $trigger ) {
			thrive_automator_register_trigger( new $trigger() );
		}
	}

	public static function load_actions() {
		foreach ( static::load_files( 'actions' ) as $action ) {
			thrive_automator_register_action( new $action() );
		}
	}

	public static function load_action_fields() {
		foreach ( static::load_files( 'action-fields' ) as $field ) {
			thrive_automator_register_action_field( new $field() );
		}
	}

	public static function load_fields() {
		foreach ( static::load_files( 'fields' ) as $field ) {
			thrive_automator_register_data_field( new $field() );
		}
	}

	public static function load_data_objects() {
		foreach ( static::load_files( 'data-objects' ) as $data_object ) {
			thrive_automator_register_data_object( new $data_object() );
		}
	}

	public static function load_apps() {
		foreach ( static::load_files( 'apps' ) as $app ) {
			thrive_automator_register_app( new $app() );
		}
	}

	public static function load_files( $type ) {
		$integration_path = static::get_integration_path( $type );

		$local_classes = array();
		foreach ( glob( $integration_path . '/*.php' ) as $file ) {

			if ( static::should_load( $file ) ) {
				require_once $file;

				$class = 'TVE\Dashboard\Automator\\' . static::get_class_name_from_filename( $file );

				if ( class_exists( $class ) ) {
					$local_classes[] = $class;
				}
			}
		}


		return $local_classes;
	}

	public static function get_class_name_from_filename( $filename ) {
		$name = str_replace( array( 'class-', '-action', '-trigger' ), '', basename( $filename, '.php' ) );

		return str_replace( '-', '_', ucwords( $name, '-' ) );
	}

	public static function display_icons() {
		include static::get_integration_path( 'icons.svg' );
	}

	public static function woo_exists() {
		return class_exists( 'WooCommerce' );
	}

	public static function should_load( $filename ) {
		$load = true;
		if ( strpos( basename( $filename, '.php' ), '-woo-' ) !== false && ! static::woo_exists() ) {
			$load = false;
		}

		return $load;
	}

	public static function launch_woocommerce_extra_hooks() {
		if ( static::woo_exists() ) {
			add_action( 'woocommerce_order_refunded', array(
				'TVE\Dashboard\Automator\Main',
				'do_woocommerce_refund_product_action',
			) );

			add_action( 'woocommerce_order_status_completed', array(
				'TVE\Dashboard\Automator\Main',
				'do_woocommerce_product_purchase_completed',
			) );

			add_action( 'woocommerce_order_status_processing', array(
				'TVE\Dashboard\Automator\Main',
				'do_woocommerce_product_purchase_processing',
			) );
		}
	}

	public static function do_woocommerce_refund_product_action( $order_id ) {
		$order = wc_get_order( $order_id );
		$user  = get_user_by( 'id', $order->get_report_customer_id() );
		foreach ( $order->get_items() as $product ) {
			if ( $product->get_quantity() != 0 ) {
				do_action( 'thrive_woo_product_refund', $product, $user );
			}
		}
	}

	public static function do_woocommerce_product_purchase_completed( $order_id ) {
		$order = wc_get_order( $order_id );
		$user  = get_user_by( 'id', $order->get_customer_id() );
		foreach ( $order->get_items() as $product ) {
			do_action( 'thrive_woo_product_purchase_completed', $product, $user );
		}
	}

	public static function do_woocommerce_product_purchase_processing( $order_id ) {
		$order = wc_get_order( $order_id );
		$user  = get_user_by( 'id', $order->get_customer_id() );
		foreach ( $order->get_items() as $product ) {
			do_action( 'thrive_woo_product_purchase_processing', $product, $user );
		}
	}

	/**
	 * Filter the data objects that might provide user data
	 */
	public static function get_email_data_sets() {
		$data_sets = apply_filters( 'tvd_automator_api_data_sets', [] );
		/**
		 * Make sure that user_data is always the last item
		 */
		$data_sets   = array_diff( $data_sets, [ 'user_data' ] );
		$data_sets[] = 'user_data';

		return $data_sets;
	}
}

add_action( 'thrive_automator_init', array( 'TVE\Dashboard\Automator\Main', 'init' ) );

