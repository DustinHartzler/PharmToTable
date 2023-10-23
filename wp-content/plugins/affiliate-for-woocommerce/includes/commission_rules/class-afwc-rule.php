<?php
/**
 * Class for Rule
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/
 * @since       2.5.0
 * @version     1.0.5
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Rule' ) ) {

	/**
	 * Class for AFWC_Rule of Affiliate For WooCommerce
	 */
	abstract class AFWC_Rule {

		/**
		 * Variable to hold possible operators
		 *
		 * @var $possible_operators
		 */
		protected $possible_operators;

		/**
		 * Variable to hold valid_rule_product_ids
		 *
		 * @var $valid_rule_product_ids
		 */
		protected $valid_rule_product_ids;

		/**
		 * Variable to hold operator
		 *
		 * @var $operator
		 */
		protected $operator;

		/**
		 * Variable to hold current rule
		 *
		 * @var $current
		 */
		protected $current;

		/**
		 * Variable to hold value
		 *
		 * @var $value
		 */
		protected $value;

		/**
		 * Variable to hold possible_values
		 *
		 * @var $possible_values
		 */
		protected $possible_values;

		/**
		 * Variable to hold props
		 *
		 * @var $props
		 */
		protected $props;

		/**
		 * Constructor
		 *
		 * @param  array $props props.
		 */
		public function __construct( $props ) {
			$this->props    = $props;
			$this->operator = ! empty( $props['operator'] ) ? $props['operator'] : array();
			$this->value    = ! empty( $props['value'] ) ? $props['value'] : array();

			$this->possible_operators = array(
				array(
					'op'    => 'eq',
					'label' => __( 'is', 'affiliate-for-woocommerce' ),
					'type'  => 'single',
				),
				array(
					'op'    => 'neq',
					'label' => __( 'is not', 'affiliate-for-woocommerce' ),
					'type'  => 'single',
				),
				array(
					'op'    => 'in',
					'label' => __( 'any of', 'affiliate-for-woocommerce' ),
					'type'  => 'multi',
				),
				array(
					'op'    => 'nin',
					'label' => __( 'none of', 'affiliate-for-woocommerce' ),
					'type'  => 'multi',
				),
			);

			$this->valid_rule_product_ids = array();
		}

		/**
		 * Function to get possible values
		 *
		 * @return $possible_values mixed
		 */
		public function get_possible_values() {
			return $this->possible_values;
		}

		/**
		 * Function to set possible values
		 *
		 * @param array $v mixed.
		 */
		public function set_possible_values( $v ) {
			$this->possible_values = $v;
		}

		/**
		 * Function to get possible values
		 *
		 * @return $possible_operators mixed
		 */
		public function get_possible_operators() {
			return $this->possible_operators;
		}

		/**
		 * Exclude operators from possible_operator
		 *
		 * @param array $operators list of exclude operator.
		 */
		public function exclude_operators( $operators = array() ) {
			if ( empty( $operators ) || ! is_array( $operators ) ) {
				return;
			}
			$this->possible_operators = array_values(
				array_filter(
					$this->possible_operators,
					function ( $item ) use ( $operators ) {
						return ! in_array( $item['op'], $operators, true );
					}
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
			$value   = $this->value;
			switch ( $this->operator ) {
				case 'eq':
					$res = ( is_array( $current ) ) ? in_array( $value, $current, true ) : ( $current === $value );
					break;
				case 'neq':
					$res = ( is_array( $current ) ) ? ! in_array( $value, $current, true ) : ( $current !== $value );
					break;
				case 'in':
					if ( is_array( $value ) && is_array( $current ) ) {
						$intersection = array_filter(
							$value,
							function ( $x ) {
								return in_array( $x, $current, true );
							}
						);
						$res          = ( count( $intersection ) >= 1 );
						$current      = $intersection;
					} else {
						$res = is_array( $value ) ? in_array( $current, $value, true ) : ( ( is_array( $current ) ) ? in_array( $value, $current, true ) : true );
					}
					break;
				case 'nin':
					if ( is_array( $value ) && is_array( $current ) ) {
						$intersection = array_filter(
							$value,
							function ( $x ) {
								return in_array( $x, $current, true );
							}
						);
						$res          = ( count( $intersection ) <= 0 );
						$current      = $intersection;
					} else {
						$res = ( is_array( $value ) ) ? ! in_array( $current, $value, true ) : ( ( is_array( $current ) ? ! in_array( $value, $current, true ) : false ) );
					}
					break;
			}
			if ( $res ) {
				$_ar[] = (array) $current;
				$context_obj->add_valid_ids( $this->get_context_key(), $_ar[] );
			}
			return $res;
		}

		/**
		 * Function to validate rule
		 *
		 * @return $context_key
		 */
		abstract protected function get_context_key();
	}

}
