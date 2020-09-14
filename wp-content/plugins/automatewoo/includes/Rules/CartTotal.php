<?php
// phpcs:ignoreFile

namespace AutomateWoo\Rules;

use AutomateWoo\Cart;

defined( 'ABSPATH' ) || exit;

/**
 * CartTotal rule class.
 */
class CartTotal extends Abstract_Number {

	/** @var array  */
	public $data_item = 'cart';

	public $support_floats = true;


	function init() {
		$this->title = __( 'Cart - Total', 'automatewoo' );
	}


	/**
	 * @param Cart $cart
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $cart, $compare, $value ) {
		return $this->validate_number( $cart->get_total(), $compare, $value );
	}

}
