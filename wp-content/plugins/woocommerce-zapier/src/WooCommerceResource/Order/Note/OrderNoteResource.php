<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Order\Note;

use Automattic\WooCommerce\Utilities\OrderUtil;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Webhook\Payload;
use OM4\WooCommerceZapier\Webhook\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\Base;
use OM4\WooCommerceZapier\WooCommerceResource\Order\Note\OrderNote;
use OM4\WooCommerceZapier\WooCommerceResource\Order\Note\OrderNoteTaskCreator;
use WP_Comment;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Order Note resource type.
 *
 * @since 2.8.0
 */
class OrderNoteResource extends Base {

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * Controller instance.
	 *
	 * @var Controller
	 */
	protected $controller;

	/**
	 * Whether our hooks have been added.
	 * This is used to ensure that our hooks are only added once, even if this class is instantiated multiple times.
	 *
	 * @var bool
	 */
	protected static $hooks_added = false;

	/**
	 * Constructor.
	 *
	 * @param  FeatureChecker $checker    FeatureChecker instance.
	 * @param  Controller     $controller Controller instance.
	 */
	public function __construct( FeatureChecker $checker, Controller $controller ) {
		$this->checker    = $checker;
		$this->controller = $controller;
		$this->key        = OrderNoteTaskCreator::child_type();
		$this->name       = OrderNoteTaskCreator::child_name();

		if ( ! self::$hooks_added ) {
			add_action( 'woocommerce_order_note_added', array( $this, 'order_note_added' ) );
			add_action( 'delete_comment', array( $this, 'delete_comment' ) );
			add_action( 'deleted_comment', array( $this, 'deleted_comment' ), 10, 2 );
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array(
			new Trigger(
				'order_note.created',
				__( 'Order Note created', 'woocommerce-zapier' ),
				array( 'wc_zapier_order_note_created' )
			),
			new Trigger(
				'order_note.deleted',
				__( 'Order Note deleted', 'woocommerce-zapier' ),
				array( 'wc_zapier_order_note_deleted' )
			),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_payload() {
		return new Payload( $this->key, $this->controller );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id Order ID.
	 */
	public function get_admin_url( $resource_id ) {
		if ( $this->checker->is_hpos_enabled() ) {
			return OrderUtil::get_order_admin_edit_url( $resource_id );
		}
		return \admin_url( "post.php?post={$resource_id}&action=edit" );
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
	 * @param stdClass $object Order Note instance.
	 *
	 * @return int
	 */
	public function get_resource_id_from_object( $object ) {
		return $object->id;
	}

	/**
	 * Whenever a WooCommerce Order Note is created, fire the appropriate WooCommerce Zapier hook if the
	 * note being added is an order note.
	 *
	 * Executing during the `woocommerce_order_note_added` action.
	 *
	 * @since 2.9.0
	 *
	 * @param int $order_note_id Order note ID.
	 *
	 * @return void
	 */
	public function order_note_added( $order_note_id ) {
		$order_note_id = \absint( $order_note_id );
		if ( \is_null( OrderNote::find( $order_note_id ) ) ) {
			return;
		}

		/**
		 * Execute the WooCommerce Zapier handler for the Order Note created trigger rule.
		 *
		 * @internal
		 * @since 2.9.0
		 *
		 * @param int $order_note_id Order Note ID.
		 */
		do_action( 'wc_zapier_order_note_created', $order_note_id );
	}

	/**
	 * Whenever a WordPress comment is deleted, fire the appropriate WooCommerce Zapier hook if the
	 * comment being deleted is an order note.
	 *
	 * Executing during the `comment_deleted` action.
	 *
	 * @param int        $comment_id The Comment ID of the deleted comment.
	 * @param WP_Comment $comment The WP_Comment instance.
	 *
	 * @return void
	 */
	public function deleted_comment( $comment_id, $comment ) {
		$comment_id = \absint( $comment_id );
		if ( \is_null( OrderNote::find( $comment_id ) ) ) {
			return;
		}

		/**
		 * Execute the WooCommerce Zapier handler for the Order Note deleted trigger rule.
		 *
		 * @internal
		 * @since 2.8.0
		 *
		 * @param int $comment_id Order Note ID.
		 */
		do_action( 'wc_zapier_order_note_deleted', $comment_id );
	}

	/**
	 * When an order note is about to be deleted, store its parent order ID in a transient
	 * so that it can be accessed during async webhook delivery.
	 *
	 * @see \OM4\WooCommerceZapier\TaskHistory\Listener\TriggerListener::woocommerce_webhook_delivery()
	 *
	 * Executed on the `delete_comment` action, which fires *before* any WordPress comment
	 * (including order notes) are deleted.
	 *
	 * @param int $comment_id Comment ID being deleted.
	 *
	 * @return void
	 */
	public function delete_comment( $comment_id ) {
		$comment_id = \absint( $comment_id );
		$note       = OrderNote::find( $comment_id );
		if ( \is_null( $note ) ) {
			// Comment being deleted is *not* an order note.
			return;
		}
		\set_transient(
			"wc_zapier_order_note_{$comment_id}_parent_id",
			$note->order_id,
			WEEK_IN_SECONDS
		);
	}
}
