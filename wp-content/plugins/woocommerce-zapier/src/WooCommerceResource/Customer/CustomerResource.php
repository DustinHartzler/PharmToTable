<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Customer;

use OM4\WooCommerceZapier\Exception\InvalidImplementationException;
use OM4\WooCommerceZapier\WooCommerceResource\Base;
use OM4\WooCommerceZapier\WooCommerceResource\Customer\CustomerTaskCreator;
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
		$this->key  = CustomerTaskCreator::resource_type();
		$this->name = CustomerTaskCreator::resource_name();
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
	 * @since 2.7.1
	 *
	 * @param WC_Customer $object Customer instance.
	 * @return int
	 */
	public function get_resource_id_from_object( $object ) {
		return $object->get_id();
	}
}
