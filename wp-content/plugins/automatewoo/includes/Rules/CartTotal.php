<?php
// phpcs:ignoreFile

namespace AutomateWoo\Rules;

use AutomateWoo\Cart;
use AutomateWoo\Data_Types;

defined( 'ABSPATH' ) || exit;

/**
 * CartTotal rule class.
 */
class CartTotal extends Abstract_Number {

	/** @var string  */
	public $data_item = Data_Types::CART;

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
