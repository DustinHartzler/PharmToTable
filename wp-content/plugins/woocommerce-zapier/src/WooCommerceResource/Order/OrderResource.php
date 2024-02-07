<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\WooCommerceResource\Order;

use Automattic\WooCommerce\Admin\Overrides\Order;
use Automattic\WooCommerce\Blocks\Domain\Services\DraftOrders;
use Automattic\WooCommerce\Utilities\OrderUtil;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Webhook\Trigger;
use OM4\WooCommerceZapier\Webhook\ZapierWebhook;
use OM4\WooCommerceZapier\WooCommerceResource\Base;
use OM4\WooCommerceZapier\WooCommerceResource\Order\OrderTaskCreator;
use WC_Order;
use WC_Webhook;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Order resource type.
 *
 * @since 2.1.0
 */
class OrderResource extends Base {

	/**
	 * Whether our hooks have been added.
	 * This is used to ensure that our hooks are only added once, even if this class is instantiated multiple times.
	 *
	 * @since 2.7.0
	 *
	 * @var bool
	 */
	protected static $hooks_added = false;

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * (Temporary) list of order ID(s) that are currently being deleted/trashed.
	 *
	 * @var array<int, true> List of order IDs that are currently being trashed.
	 */
	protected $orders_being_deleted = array();

	/**
	 * (Temporary) list of order ID(s) that are currently being restored from the trash.
	 *
	 * @var array<int, true> List of order IDs that are currently being restored from the trash.
	 */
	protected $orders_being_restored = array();

	/**
	 * Constructor.
	 *
	 * @since 2.7.0 Added $checker parameter.
	 *
	 * @param FeatureChecker $checker FeatureChecker instance.
	 */
	public function __construct( FeatureChecker $checker ) {
		$this->checker = $checker;
		$this->key     = OrderTaskCreator::resource_type();
		$this->name    = OrderTaskCreator::resource_name();

		if ( ! self::$hooks_added ) {
			add_action( 'woocommerce_order_status_changed', array( $this, 'order_status_changed' ), 10, 2 );
			add_action( 'woocommerce_before_trash_order', array( $this, 'woocommerce_before_trash_order' ) );
			add_action( 'woocommerce_untrash_order', array( $this, 'woocommerce_untrash_order' ) );
			add_filter( 'woocommerce_webhook_should_deliver', array( $this, 'woocommerce_webhook_should_deliver' ), 10, 3 );
			self::$hooks_added = true;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array_merge(
			array(
				new Trigger(
					'order.status_changed',
					__( 'Order status changed (any status)', 'woocommerce-zapier' ),
					array( 'wc_zapier_order_status_changed' )
				),
				// Order paid (previously New Order).
				new Trigger(
					'order.paid',
					__( 'Order paid', 'woocommerce-zapier' ),
					array( 'woocommerce_payment_complete' )
				),
			),
			$this->get_status_changed_dynamic_triggers()
		);
	}

	/**
	 * Dynamically create an "Order Status Changed to ..." Trigger Rule,
	 * one for each registered WooCommerce order status.
	 *
	 * @return Trigger[]
	 */
	protected function get_status_changed_dynamic_triggers() {
		$triggers = array();
		foreach ( $this->get_statuses() as $status => $status_label ) {
			$status_key = str_replace( '-', '_', sanitize_title_with_dashes( $status ) );
			$triggers[] = new Trigger(
				"order.status_changed_to_{$status_key}",
				// Translators: Order Status Name/Label.
				sprintf( __( 'Order status changed to %s', 'woocommerce-zapier' ), $status_label ),
				array( "woocommerce_order_status_{$status}" )
			);
		}
		return $triggers;
	}

	/**
	 * Get a list of all registered WooCommerce order statuses.
	 *
	 * This list excludes the following internal statuses:
	 * - The default order status (pending).
	 * - WooCommerce Blocks' "checkout-draft" order status.
	 *
	 * @return array<string, string> Status key excludes the 'wc-' prefix.
	 */
	protected function get_statuses() {
		$statuses = array();

		// List of statuses that should be excluded.
		$excluded_statuses = array(
			// The default order status (pending) because "Order created" is used for that.
			( new WC_Order() )->get_status(),

			/*
			 * WooCommerce Blocks' internal "checkout-draft" order status because
			 * these orders are not visible in the admin.
			 * Link: https://developer.woocommerce.com/2020/11/23/introducing-a-new-order-status-checkout-draft/
			 */
			DraftOrders::STATUS,
		);

		/**
		 * List of default WooCommerce order statuses.
		 * This list is used to exclude statuses that are not built into WooCommerce.
		 *
		 * @see wc_get_order_statuses()
		 */
		$default_statuses = array(
			'wc-pending',
			'wc-processing',
			'wc-on-hold',
			'wc-completed',
			'wc-cancelled',
			'wc-refunded',
			'wc-failed',
		);

		foreach ( \wc_get_order_statuses() as $status => $status_label ) {
			if ( ! in_array( $status, $default_statuses, true ) ) {
				// Status is not a default one built into WooCommerce.
				continue;
			}
			// Use the status without wc- internal prefix.
			$status = 'wc-' === substr( $status, 0, 3 ) ? substr( $status, 3 ) : $status;

			if ( ! in_array( $status, $excluded_statuses, true ) ) {
				$statuses[ $status ] = $status_label;
			}
		}
		return $statuses;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see: https://github.com/woocommerce/woocommerce/issues/39079
	 *
	 * @param array $topic_hooks Topic hooks.
	 */
	public function webhook_topic_hooks( $topic_hooks ) {
		if (
			isset( $topic_hooks['order.deleted'] ) &&
			! \in_array( 'woocommerce_trash_order', $topic_hooks['order.deleted'], true )
		) {
			$topic_hooks['order.deleted'][] = 'woocommerce_trash_order';
		}
		if (
			isset( $topic_hooks['order.restored'] ) &&
			! \in_array( 'woocommerce_untrash_order', $topic_hooks['order.restored'], true )
		) {
			$topic_hooks['order.restored'][] = 'woocommerce_untrash_order';
		}
		return $topic_hooks;
	}

	/**
	 * Whenever an Order status is changed, trigger the appropriate WooCommerce Zapier hook
	 * that will then trigger the appropriate Zapier webhook's Trigger Rule.
	 *
	 * Ensure that status changed trigger rules are not triggered when an Order is restored from trash.
	 *
	 * Executing during the `woocommerce_order_status_changed` action.
	 *
	 * @since 2.7.0
	 *
	 * @param int    $order_id   The Product ID.
	 * @param string $old_status The old Order status.
	 *
	 * @return void
	 */
	public function order_status_changed( $order_id, $old_status ) {
		if ( 'trash' === $old_status ) {
			// Skip status changes when Order is restored from trash.
			return;
		}

		/**
		 * Execute the WooCommerce Zapier handler for the Order status changed (any status) trigger rule.
		 *
		 * @internal
		 * @since 2.7.0
		 *
		 * @param int $arg Order ID.
		 */
		do_action( 'wc_zapier_order_status_changed', $order_id );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 2.7.0
	 *
	 * @param int $resource_id Resource ID.
	 */
	public function get_admin_url( $resource_id ) {
		if ( $this->checker->is_hpos_enabled() ) {
			return OrderUtil::get_order_admin_edit_url( $resource_id );
		}
		return \admin_url( "post.php?post={$resource_id}&action=edit" );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 2.7.0
	 */
	public function get_metabox_screen_name() {
		if ( $this->checker->is_hpos_enabled() ) {
			/**
			 * HPOS enabled screen name.
			 *
			 * @see \wc_get_page_screen_id()
			 *
			 * We don't use the above function because it is only available in an admin context.
			 */
			return 'woocommerce_page_wc-orders';
		}
		// HPOS disabled screen name.
		return 'shop_order';
	}

	/**
	 * Record when an order is being trashed.
	 *
	 * This is a workaround for a WooCommerce core change in 8.0 where the
	 * `order.updated` webhook event triggers when an order is trashed with HPOS
	 * enabled and sync enabled.
	 *
	 * Executed on the `woocommerce_before_trash_order` action, which fires
	 * *before* an order is trashed This action only exists on WooCommerce 8.0 and above.
	 *
	 * @see https://github.com/woocommerce/woocommerce/pull/37050
	 *
	 * @since 2.7.3
	 *
	 * @param int $order_id Order ID being trashed.
	 * @return void
	 */
	public function woocommerce_before_trash_order( $order_id ) {
		$this->orders_being_deleted[ intval( $order_id ) ] = true;
	}

	/**
	 * Record when an order is being restored from the trash.
	 *
	 * Executed on the `woocommerce_untrash_order` action (WooCommerce 7.2+ with HPOS enabled only),
	 * which fires *before* an order is untrashed.
	 *
	 * This is a workaround for a WooCommerce core bug where the `order.updated` webhook event triggers
	 * when an order is restored from the trash with HPOS enabled.
	 *
	 * @see https://github.com/woocommerce/woocommerce/issues/39079
	 *
	 * @since 2.7.0
	 *
	 * @param int $order_id Order ID being untrashed/restored.
	 * @return void
	 */
	public function woocommerce_untrash_order( $order_id ) {
		$this->orders_being_restored[ intval( $order_id ) ] = true;
	}

	/**
	 * Don't trigger the "Order Updated" or "Order Status Changed (any status)" trigger rules for an order
	 * that is currently being moved to or restored from the trash.
	 *
	 * This is also a workaround for several WooCommerce core bugs where:
	 *
	 * - The `order.updated` webhook event triggers when an order is trashed
	 *   with HPOS enabled and sync enabled (introduced in WC 8.0).
	 * - The `order.updated` webhook event triggers when an order is restored from the trash
	 *   with HPOS enabled and sync disabled.
	 *
	 * @see https://github.com/woocommerce/woocommerce/issues/39079
	 *
	 * @since 2.7.0
	 *
	 * @param bool       $should_deliver True if the webhook should be sent, or false to not send it.
	 * @param WC_Webhook $webhook The current webhook class.
	 * @param mixed      $arg First hook argument (usually the resource ID).
	 *
	 * @return bool
	 */
	public function woocommerce_webhook_should_deliver( $should_deliver, $webhook, $arg ) {
		$webhook = new ZapierWebhook( $webhook );
		if ( ! $webhook->is_zapier_webhook() ) {
			return $should_deliver;
		}

		if ( ! is_numeric( $arg ) ) {
			return $should_deliver;
		}

		if ( ! in_array( $webhook->get_topic(), array( 'order.updated', 'order.status_changed' ), true ) ) {
			return $should_deliver;
		}

		$order_id = intval( $arg );

		if ( isset( $this->orders_being_deleted[ $order_id ] ) ) {
			// Don't send the Order Updated webhook if the order is in the process of being trashed.
			return false;
		}

		if ( isset( $this->orders_being_restored[ $order_id ] ) ) {
			// Don't send the Order Updated / Order Status Changed webhooks if the order is
			// in the process of being restored from the trash.
			return false;
		}

		return $should_deliver;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param Order|WC_Order|WP_Post $object Order instance.
	 *
	 * @return int
	 */
	public function get_resource_id_from_object( $object ) {
		return is_callable( array( $object, 'get_id' ) ) ? $object->get_id() : $object->ID;
	}
}
