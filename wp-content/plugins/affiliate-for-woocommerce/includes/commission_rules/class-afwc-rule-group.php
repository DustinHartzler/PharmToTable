<?php
/**
 * Class for rule group
 *
 * @since       2.5.0
 * @version     1.0.2
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AFWC_Rule_Group' ) ) {

	/**
	 * Class for AFWC_Rule_Group of Affiliate For WooCommerce
	 */
	class AFWC_Rule_Group {

		/**
		 * Variable to hold possible condition
		 *
		 * @var $condition
		 */
		protected $condition;

		/**
		 * Variable to hold possible rule
		 *
		 * @var $rule
		 */
		protected $rules;

		/**
		 * Constructor
		 *
		 * @param  array $props props.
		 */
		public function __construct( $props ) {
			$this->condition = ! empty( $props['condition'] ) ? $props['condition'] : 'AND';
			$this->rules     = ! empty( $props['rules'] ) ? AFWC_Registry::resolve_class( $props['rules'] ) : array();
		}

		/**
		 * Function to add rule
		 *
		 * @param array $rule rule.
		 */
		public function add( $rule ) {
			array_push( $this->rules, $rule );
		}

		/**
		 * Function to validate rule
		 *
		 * @param array $context context.
		 * @return true/false
		 */
		public function validate( $context ) {
			$done       = false;
			$res        = false;
			$i          = 0;
			$rule_count = count( $this->rules );
			$res_array  = array();
			while ( $i < $rule_count ) {
				$res_array[] = $this->rules[ $i ]->validate( $context );
				++$i;
			}
			$val = array_unique( $res_array );
			if ( 'OR' === $this->condition ) {
				$res = ( in_array( true, $val, true ) ) ? true : false;
			} elseif ( 'AND' === $this->condition ) {
				$res = ( in_array( false, $val, true ) ) ? false : true;
			}

			return $res;
		}
	}
}
