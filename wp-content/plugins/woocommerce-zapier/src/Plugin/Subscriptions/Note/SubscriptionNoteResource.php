<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Subscriptions\Note;

use Automattic\WooCommerce\Utilities\OrderUtil;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\Controller;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\SubscriptionNote;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\SubscriptionNoteTaskCreator;
use OM4\WooCommerceZapier\Webhook\Payload;
use OM4\WooCommerceZapier\Webhook\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\Base;
use WC_REST_Subscription_notes_Controller;
use WP_Comment;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Subscription Note resource type.
 *
 * @since 2.9.0
 */
class SubscriptionNoteResource extends Base {

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
		$this->key        = SubscriptionNoteTaskCreator::child_type();
		$this->name       = SubscriptionNoteTaskCreator::child_name();

		if ( ! self::$hooks_added ) {
			add_action( 'woocommerce_order_note_added', array( $this, 'subscription_note_added' ) );
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
				'subscription_note.created',
				__( 'Subscription Note created', 'woocommerce-zapier' ),
				array( 'wc_zapier_subscription_note_created' )
			),
			new Trigger(
				'subscription_note.deleted',
				__( 'Subscription Note deleted', 'woocommerce-zapier' ),
				array( 'wc_zapier_subscription_note_deleted' )
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
	 * @param int $resource_id Subscription ID.
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
	 * @param stdClass $object Subscription Note instance.
	 *
	 * @return int
	 */
	public function get_resource_id_from_object( $object ) {
		return $object->id;
	}

	/**
	 * Whenever a Woo Subscriptions Note is created, fire the appropriate WooCommerce Zapier hook if the
	 * note being added is a subscription note.
	 *
	 * Executing during the `woocommerce_order_note_added` action.
	 *
	 * @param int $subscription_note_id  Subscription note ID.
	 *
	 * @return void
	 * @since 2.9.0
	 */
	public function subscription_note_added( $subscription_note_id ) {
		$subscription_note_id = \absint( $subscription_note_id );
		if ( \is_null( SubscriptionNote::find( $subscription_note_id ) ) ) {
			return;
		}

		/**
		 * Execute the WooCommerce Zapier handler for the Subscription Note created trigger rule.
		 *
		 * @internal
		 * @since 2.9.0
		 *
		 * @param int $subscription_note_id Subscription Note ID.
		 */
		do_action( 'wc_zapier_subscription_note_created', $subscription_note_id );
	}

	/**
	 * Whenever a WordPress comment is deleted, fire the appropriate WooCommerce Zapier hook if the
	 * comment being deleted is a subscription note.
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
		if ( \is_null( SubscriptionNote::find( $comment_id ) ) ) {
			return;
		}

		/**
		 * Execute the WooCommerce Zapier handler for the Subscription Note deleted trigger rule.
		 *
		 * @internal
		 * @since 2.9.0
		 *
		 * @param int $comment_id Subscription Note ID.
		 */
		do_action( 'wc_zapier_subscription_note_deleted', $comment_id );
	}

	/**
	 * When a subscription note is about to be deleted, store its parent subscription ID in a transient
	 * so that it can be accessed during async webhook delivery.
	 *
	 * @see \OM4\WooCommerceZapier\TaskHistory\Listener\TriggerListener::woocommerce_webhook_delivery()
	 *
	 * Executed on the `delete_comment` action, which fires *before* any WordPress comment
	 * (including subscription notes) are deleted.
	 *
	 * @param int $comment_id Comment ID being deleted.
	 *
	 * @return void
	 */
	public function delete_comment( $comment_id ) {
		$comment_id = \absint( $comment_id );
		$note       = SubscriptionNote::find( $comment_id );
		if ( \is_null( $note ) ) {
			// Comment being deleted is *not* a subscription note.
			return;
		}
		\set_transient(
			"wc_zapier_subscription_note_{$comment_id}_parent_id",
			$note->subscription_id,
			WEEK_IN_SECONDS
		);
	}
}
