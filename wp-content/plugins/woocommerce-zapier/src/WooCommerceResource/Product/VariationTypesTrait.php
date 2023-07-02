<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product;

defined( 'ABSPATH' ) || exit;

/**
 * Functions to help with product variations and their types.
 *
 * @since 2.5.0
 */
trait VariationTypesTrait {

	/**
	 * Get the variable product and variation types in an associated array.
	 * that are allowed to be used in Zapier.
	 *
	 * @see \WC_Product_Variation::get_type()
	 * @see \WC_Product_Variable::get_type()

	 * @since 2.5.1
	 *
	 * @return Array<string, string> The key is the variable product type, the value is the variation type.
	 */
	protected function get_variable_product_types_to_variation_types() {
		/**
		 * The product variation types that are allowed to be used in Zapier.
		 *
		 * @internal
		 * @since 2.5.1
		 *
		 * @param string[] $types The allowed product variation types.
		 */
		return apply_filters( 'wc_zapier_variable_product_types_to_variation_types', array( 'variable' => 'variation' ) );
	}

		/**
		 * Get the variable product types that are allowed to be used in Zapier.
		 *
		 * @since 2.5.1
		 *
		 * @return string[]
		 */
	protected function get_variable_product_types() {
		return \array_keys( $this->get_variable_product_types_to_variation_types() );
	}

	/**
	 * Get the product variation types that are allowed to be used in Zapier.
	 *
	 * @since 2.5.1
	 *
	 * @return string[]
	 */
	protected function get_product_variation_types() {
		return \array_values( $this->get_variable_product_types_to_variation_types() );
	}
}
