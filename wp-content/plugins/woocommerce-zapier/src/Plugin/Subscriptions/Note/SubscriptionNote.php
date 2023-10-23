<?php

namespace OM4\WooCommerceZapier\Plugin\Subscriptions\Note;

use WC_DateTime;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Represents an individual Subscription Note.
 *
 * @since 2.9.0
 */
class SubscriptionNote {

	/**
	 * Note ID.
	 *
	 * @var int
	 */
	public $id;

	/**
	 * The Subscription ID this note belongs to.
	 *
	 * @var int
	 */
	public $subscription_id;

	/**
	 * The date/time the note was created.
	 *
	 * @var WC_DateTime
	 */
	public $date_created;

	/**
	 * The subscription note content.
	 *
	 * @var string
	 */
	public $content;

	/**
	 * Whether this note is a customer note, or a private/admin note.
	 *
	 * @var bool
	 */
	public $is_customer_note;

	/**
	 * The username/name who added this note.
	 *
	 * @var string
	 */
	public $added_by;

	/**
	 * Build a subscription note from an existing ID.
	 *
	 * @param int $note_id The ID of the subscription note to retrieve.
	 *
	 * @return SubscriptionNote|null
	 */
	public static function find( $note_id ) {
		// WooCommerce core's `wc_get_order_note()` function does not ensure the comment ID is an order note,
		// so validate that first.
		$comment = \get_comment( $note_id );
		if ( is_null( $comment ) || 'order_note' !== $comment->comment_type ) {
			return null;
		}

		// Retrieve the note using WooCommerce core's `wc_get_order_note()` function.
		$note_data = \wc_get_order_note( $comment );
		if ( \is_null( $note_data ) || ! is_a( $note_data, stdClass::class ) ) {
			return null;
		}

		$subscription = \wcs_get_subscription( (int) $comment->comment_post_ID );
		if ( false === $subscription ) {
			return null;
		}

		$note                   = new self();
		$note->id               = $note_data->id;
		$note->subscription_id  = (int) $comment->comment_post_ID;
		$note->date_created     = $note_data->date_created;
		$note->content          = $note_data->content;
		$note->is_customer_note = $note_data->customer_note;
		$note->added_by         = $note_data->added_by;

		return $note;
	}
}
