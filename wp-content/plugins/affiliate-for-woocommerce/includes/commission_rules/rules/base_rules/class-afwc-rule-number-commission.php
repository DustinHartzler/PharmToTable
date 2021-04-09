<?php
/**
 * Class for Rule_Boolean
 *
 * @since       2.5.0
 * @version     1.0.2
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AFWC_Rule_Number_Commission' ) ) {

	/**
	 * Class for AFWC_Rule_Number_Commission of Affiliate For WooCommerce
	 */
	abstract class AFWC_Rule_Number_Commission extends AFWC_Rule {

		/**
		 * Constructor
		 *
		 * @param  array $props props.
		 */
		public function __construct( $props ) {
			parent::__construct( $props );
			$this->possible_operators = array_merge(
				$this->possible_operators,
				array(
					array(
						'op'    => 'gt',
						'label' => __( '>', 'affiliate-for-woocommerce' ),
						'type'  => 'single',
					),
					array(
						'op'    => 'gte',
						'label' => __( '>=', 'affiliate-for-woocommerce' ),
						'type'  => 'single',
					),
					array(
						'op'    => 'lt',
						'label' => __( '<', 'affiliate-for-woocommerce' ),
						'type'  => 'single',
					),
					array(
						'op'    => 'lte',
						'label' => __( '<=', 'affiliate-for-woocommerce' ),
						'type'  => 'single',
					),
				)
			);
		}

		/**
		 * Function to validate rule
		 *
		 * @param array $context_obj context_obj.
		 * @return true/false
		 */
		public function validate( $context_obj ) {
			$res     = false;
			$context = $context_obj->get_base_context();
			$current = $context[ $this->get_context_key() ];
			$value   = ! empty( $value ) ? $value : $this->value;
			if ( is_array( $current ) ) {
				$current = array_map(
					function( $c ) {
						return ( '' !== $c ) ? intval( $c ) : 0;
					},
					$current
				);
			} else {
				$current = ( '' !== $current ) ? intval( $current ) : 0;
			}

			if ( is_array( $value ) ) {
				$value = array_map(
					function( $v ) {
						return ( '' !== $v ) ? intval( $v ) : 0;
					},
					$value
				);
			} else {
				$value = ( '' !== $value ) ? intval( $value ) : 0;
			}

			switch ( $this->operator ) {
				case 'eq':
					$res = ( is_array( $current ) ) ? in_array( $current, $value, true ) : ( $current === $value );
					break;
				case 'neq':
					$res = ( is_array( $current ) ) ? ! in_array( $current, $value, true ) : ( $current !== $value );
					break;
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
				case 'gt':
					$res = ( $current > $value );
					break;
				case 'gte':
					$res = ( $current >= $value );
					break;
				case 'lt':
					$res = ( $current < $value );
					break;
				case 'lte':
					$res = ( $current <= $value );
					break;
			}

			if ( $res ) {
				$valid_ids = (array) $current;
				$context_obj->add_valid_ids( $this->get_context_key(), $valid_ids );
			}
			return $res;
		}

	}
}

