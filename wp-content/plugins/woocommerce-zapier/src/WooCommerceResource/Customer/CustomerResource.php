<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Customer;

use OM4\WooCommerceZapier\WooCommerceResource\Base;
use WC_Customer;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Customer resource type.
 *
 * @since 2.1.0
 */
class CustomerResource extends Base {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->key  = 'customer';
		$this->name = __( 'Customer', 'woocommerce-zapier' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_metabox_screen_name() {
		return null;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id Resource ID.
	 */
	public function get_admin_url( $resource_id ) {
		return admin_url( "user-edit.php?user_id={$resource_id}" );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id  Resource ID.
	 * @param int $variation_id Variation ID.
	 */
	public function get_description( $resource_id, $variation_id = 0 ) {
		$object = new WC_Customer( $resource_id );
		if ( $object->get_id() > 0 ) {
			return \sprintf(
					// translators: WooCommerce customer name. 1: Customer ID. 2: Customer Name.
				_x( 'Customer #%1$d (%2$s)', 'Customer resource definition description.', 'woocommerce-zapier' ),
				$object->get_id(),
				\trim(
					\sprintf(
						// translators: 1: Customer first name. 2: Customer last name.
						_x( '%1$s %2$s', 'Customer name.', 'woocommerce-zapier' ),
						$object->get_first_name(),
						$object->get_last_name()
					)
				)
			);
		}
		return null;
	}
}
