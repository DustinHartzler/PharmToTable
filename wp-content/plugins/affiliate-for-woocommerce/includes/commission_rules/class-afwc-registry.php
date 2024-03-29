<?php
/**
 * Class for Registry
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/
 * @since       2.5.0
 * @version     1.2.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Registry' ) ) {

	/**
	 * Class for AFWC_Registry of Affiliate For WooCommerce
	 */
	class AFWC_Registry {

		/**
		 * Variable to hold default registry classes.
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
				'referral_medium'  => 'AFWC_Referral_Medium_Commission',
				'user_first_order' => 'AFWC_User_First_Order_Commission',
			),
		);

		/**
		 * Function to get registry
		 *
		 * @return $registry mixed
		 */
		public static function get_registry() {
			return array_merge(
				apply_filters(
					'afwc_commission_rule_registry',
					! empty( self::$registry ) ? self::$registry : array(),
					array( 'source' => new self() )
				),
				array( 'meta' => array( 'rule_group_titles' => self::get_rule_group_titles() ) )
			);
		}

		/**
		 * Function to get translatable group titles.
		 *
		 * @return array
		 */
		private static function get_rule_group_titles() {
			return apply_filters(
				'afwc_commission_rule_group_title',
				array(
					'affiliate' => _x( 'Affiliate', 'Commission group title for affiliate rules', 'affiliate-for-woocommerce' ),
					'product'   => _x( 'Product', 'Commission group title for product rules', 'affiliate-for-woocommerce' ),
					'medium'    => _x( 'Medium', 'Commission group title for medium rules', 'affiliate-for-woocommerce' ),
					'user'      => _x( 'User', 'Commission group title for user rules', 'affiliate-for-woocommerce' ),
				),
				array( 'source' => new self() )
			);
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
				$registry    = self::get_registry();
				if ( ! empty( $props_count ) ) {
					for ( $i = 0; $i < $props_count; $i++ ) {
						$r = $props[ $i ];
						if ( ! empty( $r['condition'] ) ) {
							$new1 = new AFWC_Rule_Group( $r );
							array_push( $rules_arr, $new1 );
						} elseif ( ! empty( $r['operator'] ) && ! empty( $r['type'] ) ) {
							$classname = ( ! empty( $registry ) && is_array( $registry ) && ! empty( $registry['rule'][ $r['type'] ] ) ) ? $registry['rule'][ $r['type'] ] : '';
							$new1      = ! empty( $classname ) && class_exists( $classname ) ? new $classname( $r ) : null;
							if ( ! empty( $new1 ) && is_object( $new1 ) ) {
								array_push( $rules_arr, $new1 );
							}
						}
					}
				}
				return $rules_arr;
			}
		}
	}

}
