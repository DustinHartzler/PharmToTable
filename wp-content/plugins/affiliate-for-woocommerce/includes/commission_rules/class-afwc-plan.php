<?php
/**
 * Class for rule group
 *
 * @since       2.5.0
 * @version     1.0.1
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'AFWC_Plan' ) ) {

	/**
	 * Class for AFWC_Rule_Group of Affiliate For WooCommerce
	 */
	class AFWC_Plan extends AFWC_Rule_Group {

		/**
		 * Variable to hold amount
		 *
		 * @var $amount
		 */
		private $amount;

		/**
		 * Variable to hold commission_type
		 *
		 * @var $commission_type
		 */
		private $commission_type;

		/**
		 * Constructor
		 *
		 * @param  array $props props.
		 */
		public function __construct( $props ) {
			parent::__construct( $props );
			$this->amount         = $props['amount'];
			$this->commision_type = $props['type'];
		}

		/**
		 * Function to validate rule
		 *
		 * @param array $context context.
		 * @return true/false
		 */
		public function validate( $context ) {
			if ( is_a( $this->rules, 'AFWC_Rule_Group' ) ) {
				return $this->rules->validate( $context );
			} else {
				return parent::validate( $context );
			}
		}

	}

}
