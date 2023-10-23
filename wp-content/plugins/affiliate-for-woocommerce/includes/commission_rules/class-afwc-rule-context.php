<?php
/**
 * Class for rule group
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/
 * @since       2.7.0
 * @version     1.0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Rule_Context' ) ) {

	/**
	 * Class for AFWC_Rule_Context of Affiliate For WooCommerce
	 */
	class AFWC_Rule_Context {

		/**
		 * Variable to hold base context
		 *
		 * @var $base_context
		 */
		private $base_context;

		/**
		 * Variable to hold valid context
		 *
		 * @var $valid_context
		 */
		private $valid_context;

		/**
		 * Constructor
		 *
		 * @param  array $params params.
		 */
		public function __construct( $params = array() ) {
			$this->base_context  = $params;
			$this->valid_context = array();
		}

		/**
		 * Function to add valid ids
		 *
		 * @param string $key key to fetch.
		 * @param array  $ids ids to add.
		 */
		public function add_valid_ids( $key, $ids ) {
			$this->valid_context[ $key ] = ! empty( $this->valid_context[ $key ] ) ? $this->valid_context[ $key ] : array();
			$this->valid_context[ $key ] = array_merge( $this->valid_context[ $key ], $ids );
		}

		/**
		 * Function to get valid context
		 *
		 * @return array $valid_context
		 */
		public function get_valid_context() {
			return $this->valid_context;
		}

		/**
		 * Function to get base context
		 *
		 * @return array $base_context
		 */
		public function get_base_context() {
			return $this->base_context;
		}

		/**
		 * Function to get valid product ids
		 *
		 * @return array $valid_product_ids
		 */
		public function get_valid_product_ids() {
			$valid_product_ids = array();
			if ( ! empty( $this->valid_context['product_id'] ) ) {
				$valid_product_ids = array_merge( $valid_product_ids, $this->valid_context['product_id'] );
			}
			// add valid product ids for valid product categories.
			$valid_categories = ! empty( $this->valid_context['product_category'] ) ? $this->valid_context['product_category'] : array();

			if ( ! empty( $valid_categories ) ) {
				foreach ( $valid_categories as $cat_id ) {
					$valid_product_ids = array_merge( $valid_product_ids, $this->base_context['category_prod_id_map'][ $cat_id ] );
				}
			}

			if ( ! empty( $this->valid_context['affiliate_id'] ) || ! empty( $this->valid_context['affiliate_tag'] ) ) {
				$valid_product_ids = ( ! empty( $this->valid_context['product_id'] ) ) ? array_merge( $valid_product_ids, $this->valid_context['product_id'] ) : $valid_product_ids;
			}
			// return base context by default.
			$valid_product_ids = empty( $valid_product_ids ) ? array_merge( $valid_product_ids, $this->base_context['product_id'] ) : $valid_product_ids;
			return $valid_product_ids;
		}
	}

}
