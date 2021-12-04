<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Status_Update
 */
class Woo_Order_Status_Update extends \Thrive\Automator\Items\Action {

	private $status;

	/**
	 * Get the action identifier
	 *
	 * @return string
	 */
	public static function get_id() {
		return 'woo/orderstatus';
	}

	/**
	 * Get the action name/label
	 *
	 * @return string
	 */
	public static function get_name() {
		return 'Update WooCommerce order status';
	}

	/**
	 * Get the action description
	 *
	 * @return string
	 */
	public static function get_description() {
		return 'Change the status of a WooCommerce order';
	}

	/**
	 * Get the action logo
	 *
	 * @return string
	 */
	public static function get_image() {
		return 'woo-update-order-status';
	}

	/**
	 * Get the name of app to which action belongs
	 *
	 * @return string
	 */
	public static function get_app_name() {
		return 'WooCommerce';
	}

	/**
	 * Array of action-field keys, required for the action to be setup
	 *
	 * @return array
	 */
	public static function get_required_action_fields() {
		return array( 'woo_order_status' );
	}

	/**
	 * Get an array of keys with the required data-objects
	 *
	 * @return array
	 */
	public static function get_required_data_objects() {
		return array( 'woo_order_data' );
	}

	public function prepare_data( $data = array() ) {
		if ( ! empty( $data['extra_data'] ) ) {
			$data = $data['extra_data'];
		}

		$this->status = $data['woo_order_status']['value'];
	}

	public function do_action( $data ) {

		if ( empty( $data['woo_order_data'] ) ) {
			return false;
		}

		$order = wc_get_order( $data['woo_order_data']->get_value( 'order_id' ) );

		if ( empty( $order ) ) {
			return false;
		}

		$order->set_status( $this->status, '', true );
	}

}
