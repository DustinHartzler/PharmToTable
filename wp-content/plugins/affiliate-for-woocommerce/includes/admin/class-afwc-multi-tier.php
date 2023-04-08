<?php
/**
 * Main class for Multi Tier
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       5.4.0
 * @version     1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Multi_Tier' ) ) {

	/**
	 * Class to handle Multi-Tier.
	 */
	class AFWC_Multi_Tier {

		/**
		 * Variable to hold instance of AFWC_Multi_Tier
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of AFWC_Multi_Tier
		 *
		 * @return AFWC_Multi_Tier Singleton object of AFWC_Multi_Tier
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Function to get children data for an affiliate.
		 *
		 * @param array $args The arguments.
		 *
		 * @return array children data for an affiliate.
		 */
		public function get_children_data( $args = array() ) {

			$current_affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? intval( $args['affiliate_id'] ) : 0;
			if ( empty( $current_affiliate_id ) ) {
				return array();
			}

			$children = afwc_get_children( $current_affiliate_id, true );
			if ( empty( $children ) || ! is_array( $children ) ) {
				return array();
			}

			// create an array for child affiliate and it's immediate parent.
			$affiliate_immediate_parent = array();
			foreach ( $children as $child => $parent ) {
				$affiliate_immediate_parent[ $child ] = ( ! empty( $parent[0] ) ) ? intval( $parent[0] ) : 0;
			}
			if ( empty( $affiliate_immediate_parent ) || ! is_array( $affiliate_immediate_parent ) ) {
				return array();
			}

			$tree = $this->build_tree( $affiliate_immediate_parent, $current_affiliate_id );
			if ( ! empty( $tree ) && is_array( $tree ) ) {
				return array( $tree );
			}

		}

		/**
		 * Function to build tree structure for multi tier chain data.
		 *
		 * @param array $parent_child The array of child and immediate parent.
		 * @param int   $affiliate_id The affiliate user id.
		 * @param array $final The resulting array.
		 *
		 * @return array children for an affiliate.
		 */
		public function build_tree( $parent_child = array(), $affiliate_id = 0, $final = array() ) {
			$children = array();

			foreach ( $parent_child as $child_id => $parent_id ) {
				if ( ! empty( $parent_id ) ) {
					if ( intval( $parent_id ) !== intval( $affiliate_id ) ) {
						continue;
					}

					$children[] = $this->build_tree( $parent_child, $child_id );
				}
			}

			$affiliate_user = get_user_by( 'id', $affiliate_id );

			return array(
				'id'       => $affiliate_id,
				'name'     => $affiliate_user instanceof WP_User ? $affiliate_user->display_name : $affiliate_id,
				'children' => $children,
			);
		}

	}
}
