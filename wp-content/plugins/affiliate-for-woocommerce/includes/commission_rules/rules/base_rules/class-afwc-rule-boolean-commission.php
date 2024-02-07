<?php
/**
 * Class for Rule_Boolean
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/rules/base_rules/
 * @since       2.5.0
 * @version     1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Rule_Boolean_Commission' ) ) {

	/**
	 * Class for AFWC_Rule_Boolean_Commission of Affiliate For WooCommerce
	 */
	abstract class AFWC_Rule_Boolean_Commission extends AFWC_Rule {

		/**
		 * Constructor
		 *
		 * @param  array $props props.
		 */
		public function __construct( $props ) {
			parent::__construct( $props );
			$this->possible_operators = array(
				array(
					'op'    => 'eq',
					'label' => _x( 'is', 'Equal to operator label for commission plan', 'affiliate-for-woocommerce' ),
					'type'  => 'select',
				),
			);
			$this->possible_values    = array( 'yes', 'no' );
		}

		/**
		 * Function to validate rule
		 *
		 * @param object $context_obj The context Object.
		 *
		 * @return bool Return true if validated, otherwise false.
		 */
		public function validate( $context_obj = null ) {
			$res = false;

			if ( empty( $context_obj ) ) {
				return $res;
			}

			$context     = is_callable( array( $context_obj, 'get_base_context' ) ) ? $context_obj->get_base_context() : array();
			$context_key = is_callable( array( $this, 'get_context_key' ) ) ? $this->get_context_key() : '';
			$current     = ( ! empty( $context_key ) && is_array( $context ) && isset( $context[ $context_key ] ) ) ? $context[ $context_key ] : '';

			$value = $this->value;

			switch ( $this->operator ) {
				case 'eq':
					$res = $current === $value;
					break;
				case 'neq':
					$res = $current !== $value;
					break;
			}

			return $res;
		}
	}
}
