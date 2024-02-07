<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product;

use OM4\WooCommerceZapier\Webhook\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductTaskCreator;
use WC_Product;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Product resource type.
 *
 * @since 2.1.0
 */
class ProductResource extends CustomPostTypeResource {

	/**
	 * Whether our hooks have been added.
	 * This is used to ensure that our hooks are only added once, even if this class is instantiated multiple times.
	 *
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	protected static $hooks_added = false;

	/**
	 * {@inheritDoc}
	 */
	public function __construct() {
		$this->key                 = ProductTaskCreator::resource_type();
		$this->name                = ProductTaskCreator::resource_name();
		$this->metabox_screen_name = 'product';

		if ( ! self::$hooks_added ) {
			add_action( 'woocommerce_product_set_stock_status', array( $this, 'product_stock_status_changed' ), 10, 3 );
			add_action( 'woocommerce_variation_set_stock_status', array( $this, 'product_stock_status_changed' ), 10, 3 );
			add_action( 'woocommerce_product_set_stock', array( $this, 'product_stock_quantity_set' ) );
			add_action( 'woocommerce_variation_set_stock', array( $this, 'product_stock_quantity_set' ) );
			self::$hooks_added = true;
		}
	}

	/**
	 * Load the resource's triggers.
	 *
	 * @since 2.6.0
	 *
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array_merge(
			array(
				new Trigger(
					'product.stock_low',
					__( 'Product stock low', 'woocommerce-zapier' ),
					array( 'wc_zapier_product_stock_low' )
				),
				new Trigger(
					'product.stock_status_changed',
					__( 'Product stock status changed (any status)', 'woocommerce-zapier' ),
					array( 'wc_zapier_product_stock_status_changed' )
				),
			),
			$this->get_stock_status_changed_dynamic_triggers()
		);
	}

	/**
	 * Dynamically create a "Product stock status changed to ..." Trigger Rule,
	 * one for each registered WooCommerce product stock status.
	 *
	 * @since 2.6.0
	 *
	 * @return Trigger[]
	 */
	protected function get_stock_status_changed_dynamic_triggers() {
		$triggers = array();
		foreach ( $this->get_stock_statuses() as $status => $status_label ) {
			$status_key = str_replace( '-', '_', sanitize_title_with_dashes( $status ) );
			$triggers[] = new Trigger(
				"product.stock_status_changed_to_{$status_key}",
				// Translators: Product Stock Status Name/Label.
				sprintf( __( 'Product stock status changed to %s', 'woocommerce-zapier' ), strtolower( $status_label ) ),
				array( "wc_zapier_product_stock_status_changed_to_{$status}" )
			);
		}
		return $triggers;
	}

	/**
	 * Get a list of all registered WooCommerce product stock statuses.
	 *
	 * @since 2.6.0
	 *
	 * @return array<string, string>
	 */
	protected function get_stock_statuses() {
		return \wc_get_product_stock_status_options();
	}

	/**
	 * Whenever a Product's stock status is changed, trigger the appropriate WooCommerce Zapier hook
	 * that will then trigger the appropriate Zapier webhook's Trigger Rule.
	 *
	 * Also ensure that stock status changed trigger rules are not triggered when a Product is first created.
	 *
	 * Executing during the `woocommerce_product_set_stock_status` or `woocommerce_variation_set_stock_status` action.
	 *
	 * @since 2.6.0
	 *
	 * @param int        $product_id The Product ID.
	 * @param string     $new_stock_status The new stock status.
	 * @param WC_Product $product Product instance.
	 *
	 * @return void
	 */
	public function product_stock_status_changed( $product_id, $new_stock_status, WC_Product $product ) {
		if ( \array_key_exists( 'date_created', $product->get_changes() ) ) {
			// Product is *just* being created, so don't trigger the webhook.
			return;
		}

		/**
		 * Execute the WooCommerce Zapier handler for the Product stock status changed (any status) trigger rule.
		 *
		 * @internal
		 * @since 2.6.0
		 *
		 * @param int $arg Product ID.
		 */
		do_action( 'wc_zapier_product_stock_status_changed', $product_id );

		/**
		 * Execute the WooCommerce Zapier handler for the Product stock status changed to "X" trigger rule.
		 *
		 * @internal
		 * @since 2.6.0
		 *
		 * @param int $arg Product ID.
		 */
		do_action( "wc_zapier_product_stock_status_changed_to_$new_stock_status", $product_id );
	}

	/**
	 * Whenever a Product's stock quantity is changed, trigger the appropriate WooCommerce Zapier hook
	 * that will then trigger the "Product stock low" trigger rule.
	 *
	 * Also ensure that "Product stock low" trigger rule is not triggered when a Product is first created.
	 *
	 * Whenever a Product's stock quantity is changed we're testing the quantity against the low stock settings.
	 *
	 * Also ensure that the previous stock quantity was above the low stock threshold.
	 *
	 * Executing during the `woocommerce_product_set_stock` or `woocommerce_variation_set_stock` action.
	 *
	 * @since 2.6.0
	 *
	 * @param WC_Product $product Product instance.
	 *
	 * @return void
	 */
	public function product_stock_quantity_set( WC_Product $product ) {
		if ( \array_key_exists( 'date_created', $product->get_changes() ) ) {
			// Product is *just* being created, so don't trigger the webhook.
			return;
		}

		$previous_stock_amount  = $product->get_data()['stock_quantity'];
		$current_stock_amount   = $product->get_stock_quantity();
		$low_stock_threshold    = wc_get_low_stock_amount( $product );
		$out_of_stock_threshold = absint( get_option( 'woocommerce_notify_no_stock_amount', 0 ) );

		$previous_stock_is_not_low      = $previous_stock_amount > $low_stock_threshold;
		$stock_is_above_no_stock_amount = $current_stock_amount > $out_of_stock_threshold;
		$new_stock_is_low               = $current_stock_amount <= $low_stock_threshold;

		if ( $previous_stock_is_not_low && $stock_is_above_no_stock_amount && $new_stock_is_low ) {
			/**
			 * Execute the WooCommerce Zapier handler for the "Product stock low" trigger rule.
			 *
			 * @internal
			 * @since 2.6.0
			 *
			 * @param int $arg Product ID.
			 */
			do_action( 'wc_zapier_product_stock_low', $product->get_id() );
		}
	}
}
