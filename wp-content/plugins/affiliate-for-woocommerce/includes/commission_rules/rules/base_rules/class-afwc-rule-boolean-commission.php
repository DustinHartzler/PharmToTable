<?php
/**
 * Class for Rule_Boolean
 *
 * @since       2.5.0
 * @version     1.0.0
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
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
					'op'   => 'eq',
					'type' => 'single',
				),
			);
			$this->possible_values    = array( true, false );
		}

	}
}

