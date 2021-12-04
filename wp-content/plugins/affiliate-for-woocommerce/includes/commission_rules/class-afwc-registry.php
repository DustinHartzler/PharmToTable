<?php
/**
 * Class for Registry
 *
 * @since       2.5.0
 * @version     1.0.2
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AFWC_Registry' ) ) {

	/**
	 * Class for AFWC_Registry of Affiliate For WooCommerce
	 */
	class AFWC_Registry {

		/**
		 * Variable to hold possible registry classes
		 *
		 * @var $registry
		 */
		private static $registry = array(
			'rules' => array( 'AFWC_Rule_Group', 'rule' ),
			'rule'  => array(
				'affiliate'        => 'AFWC_Affiliate_Commission',
				'affiliate_tag'    => 'AFWC_Affiliate_Tag_Commission',
				'product'          => 'AFWC_Product_Commission',
				'product_category' => 'AFWC_Product_Category_Commission',
			),

		);

		/**
		 * Function to get registry
		 *
		 * @return $registry mixed
		 */
		public static function get_registry() {
			return self::$registry;
		}

		/**
		 * Function to resolve class name
		 *
		 * @param array $props props.
		 * @return array $rule_arr.
		 */
		public static function resolve_class( $props ) {
			if ( ! empty( $props['condition'] ) ) {
				return new AFWC_Rule_Group( $props );
			} else {

				$rules_arr   = array();
				$props_count = ! empty( $props ) ? count( $props ) : 0;
				if ( ! empty( $props_count ) ) {
					for ( $i = 0; $i < $props_count; $i++ ) {
						$r = $props[ $i ];
						if ( ! empty( $r['condition'] ) ) {
							$new1 = new AFWC_Rule_Group( $r );
							array_push( $rules_arr, $new1 );
						} elseif ( ! empty( $r['operator'] ) && ! empty( $r['type'] ) ) {
							$classname = self::$registry['rule'][ $r['type'] ];
							$new1      = new $classname( $r );
							array_push( $rules_arr, $new1 );
						}
					}
				}
				return $rules_arr;
			}
		}

	}

}
