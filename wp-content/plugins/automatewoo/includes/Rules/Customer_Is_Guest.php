<?php
// phpcs:ignoreFile

namespace AutomateWoo\Rules;

use AutomateWoo\Data_Types;

defined( 'ABSPATH' ) || exit;

/**
 * @class Customer_Is_Guest
 */
class Customer_Is_Guest extends Abstract_Bool {

	public $data_item = Data_Types::CUSTOMER;


	function init() {
		$this->title = __( 'Customer - Is Guest', 'automatewoo' );
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		$is_guest = ! $customer->is_registered();

		switch ( $value ) {
			case 'yes':
				return $is_guest;
				break;
			case 'no':
				return ! $is_guest;
				break;
		}
	}

}
