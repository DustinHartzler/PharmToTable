<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product;

use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use WC_Product;
use WP_Error;
use WP_REST_Request;
use WP_REST_Server;

/**
 * Common methods for product update controllers (Price and Stock)
 */
trait ProductUpdatesTrait {

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'args'   => array(),
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Check if a given request has the necessary permissions to update a product.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		$product = $this->get_product( $request );

		if ( \is_wp_error( $product ) || ! $product ) {
			// Unable to find product using request criteria.
			// Allow the error to be handled by the update_item() method.
			return true;
		}

		$post_type = 'product';

		if ( \in_array( $product->get_type(), $this->get_product_variation_types(), true ) ) {
			$post_type = 'product_variation';
		}

		if ( ! wc_rest_check_post_permissions( $post_type, 'edit', $product->get_id() ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_edit', __( 'Sorry, you are not allowed to edit this resource.', 'woocommerce-zapier' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Get the Product for the given ID or SKU.
	 *
	 * @param WP_REST_Request $request The incoming request.
	 *
	 * @return WC_Product|WP_Error|null|false
	 */
	public function get_product( $request ) {
		if ( '' === $request['product_value'] ) {
			return new WP_Error( 'woocommerce_rest_product_invalid_product_value', __( 'Product value must be specified.', 'woocommerce-zapier' ), array( 'status' => 400 ) );
		}
		if ( 'sku' === $request['product_identifier'] ) {
			if ( ! wc_product_sku_enabled() ) {
				return new WP_Error( 'woocommerce_rest_product_sku_not_enabled', __( 'SKU must be enabled.', 'woocommerce-zapier' ), array( 'status' => 400 ) );
			}
			$product = \wc_get_product( \wc_get_product_id_by_sku( $request['product_value'] ) );
		} elseif ( 'id' === $request['product_identifier'] ) {
			$product = \wc_get_product( $request['product_value'] );
		} else {
			return new WP_Error( 'woocommerce_rest_product_invalid_product_identifier', __( 'Invalid product identifier.', 'woocommerce-zapier' ), array( 'status' => 404 ) );
		}
		return $product;
	}

	/**
	 * Return the product_identifier and product_value scheme pieces.
	 *
	 * @return array
	 */
	public function get_common_schema_fields() {
		return array(
			// 'product_identifier'.
			array(
				'description' => __( 'Select how to find the existing product or variation to update', 'woocommerce-zapier' ),
				'type'        => 'string',
				'enum'        => array(
					'sku',
					'id',
				),
				'context'     => array( 'edit' ),
				'required'    => true,
				'wcz_meta_v1' => array(
					'field_properties' => array(
						'alters_dynamic_fields' => true,
						'default'               => 'sku',
						'label'                 => __( 'Product or Variation Identifier', 'woocommerce-zapier' ),
					),
					'enum_labels'      => array(
						'sku' => __( 'SKU', 'woocommerce-zapier' ),
						'id'  => __( 'ID', 'woocommerce-zapier' ),
					),
				),
			),
			// 'product_value'.
			array(
				'description' => __( 'Input the SKU or ID of the product or variation to update', 'woocommerce-zapier' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
				'required'    => true,
				'wcz_meta_v1' => array(
					'depends_on' => array(
						array(
							'field'   => 'product_identifier',
							'changes' => array(
								array(
									'property' => 'label',
									'mapping'  => array(
										'sku' => __( 'SKU of the Product or Variation', 'woocommerce-zapier' ),
										'id'  => __( 'ID of the Product or Variation', 'woocommerce-zapier' ),
									),
								),
								array(
									'property' => 'help_text',
									'mapping'  => array(
										'sku' => __( 'The SKU of the product or variation to update', 'woocommerce-zapier' ),
										'id'  => __( 'The ID of the product or variation to update', 'woocommerce-zapier' ),
									),
								),
							),
						),
					),
				),
			),
		);
	}
}
