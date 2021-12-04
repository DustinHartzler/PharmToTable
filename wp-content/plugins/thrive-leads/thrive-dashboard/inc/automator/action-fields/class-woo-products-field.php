<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Products_Field
 */
class Woo_Products_Field extends \Thrive\Automator\Items\Action_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Which product should be added to the order?';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Select products to add them to the order';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	/**
	 * $$value will be replaced by field value
	 * $$length will be replaced by value length
	 *
	 * @var string
	 */
	public static function get_preview_template() {
		return 'Product: $$value';
	}

	/**
	 * For multiple option inputs, name of the callback function called through ajax to get the options
	 */
	public static function get_options_callback() {
		$products = array();
		foreach ( \wc_get_products( array( 'limit' => - 1 ) ) as $key => $product ) {
			$id               = $product->get_id();
			$products[ $key ] = array(
				'label' => $product->get_name(),
				'id'    => $id,
			);
		}

		return $products;
	}

	public static function get_id() {
		return 'woo_products';
	}

	public static function get_type() {
		return 'autocomplete';
	}

	public static function is_ajax_field() {
		return true;
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
