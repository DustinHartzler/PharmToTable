<?php
/**
 * Class for Rule_string
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/rules/base_rules/
 * @since       2.5.0
 * @version     2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Rule_String_Commission' ) ) {

	/**
	 * Class for AFWC_Rule_String_Commission of Affiliate For WooCommerce
	 */
	abstract class AFWC_Rule_String_Commission extends AFWC_Rule {

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
			$current     = ( ! empty( $context_key ) && ! empty( $context[ $context_key ] ) ) ? $context[ $context_key ] : '';

			$value = $this->value;

			if ( ! empty( $value ) && is_callable( array( $this, 'filter_values' ) ) ) {
				// Filter the values for comparison.
				$value = $this->filter_values( $value );
			}

			if ( ! empty( $current ) && is_array( $current ) ) {
				$current = array_filter( $current );
			}

			switch ( $this->operator ) {
				case 'in':
					if ( is_array( $value ) && is_array( $current ) ) {
						$intersection = array_intersect( $value, $current );
						$res          = ( count( $intersection ) >= 1 );
						$current      = $intersection;
					} else {
						$res = ( is_array( $value ) ) ? in_array( $current, $value, true ) : false;
					}
					break;
				case 'nin':
					if ( is_array( $value ) && is_array( $current ) ) {
						$intersection = array_intersect( $value, $current );
						$res          = ( count( $intersection ) <= 0 );
						$current      = $intersection;
					} else {
						$res = ( is_array( $value ) ) ? ! in_array( $current, $value, true ) : false;
					}
					break;
			}

			if ( $res ) {
				$valid_ids = (array) $current;
				if ( is_callable( array( $context_obj, 'add_valid_ids' ) ) ) {
					$context_obj->add_valid_ids( $context_key, $valid_ids );
				}
			}

			return $res;
		}
	}

}
