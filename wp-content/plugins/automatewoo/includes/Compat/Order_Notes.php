<?php
// phpcs:ignoreFile

namespace AutomateWoo\Compat;

aw_deprecated_class( Order_Notes::class, '5.2.0' );

/**
 * Provide order note methods that are compatible with changes introduced in WC 3.2.
 *
 * @class Order_Notes
 * @since 4.4
 *
 * @deprecated in 5.0.0
 */
class Order_Notes {

	/**
	 * Get order notes.
	 *
	 * @param  array $args Query arguments {
	 *     Array of query parameters.
	 *
	 *     @type string $limit         Maximum number of notes to retrieve.
	 *                                 Default empty (no limit).
	 *     @type int    $order_id      Limit results to those affiliated with a given order ID.
	 *                                 Default 0.
	 *     @type array  $order__in     Array of order IDs to include affiliated notes for.
	 *                                 Default empty.
	 *     @type array  $order__not_in Array of order IDs to exclude affiliated notes for.
	 *                                 Default empty.
	 *     @type string $orderby       Define how should sort notes.
	 *                                 Accepts 'date_created', 'date_created_gmt' or 'id'.
	 *                                 Default: 'id'.
	 *     @type string $order         How to order retrieved notes.
	 *                                 Accepts 'ASC' or 'DESC'.
	 *                                 Default: 'DESC'.
	 *     @type string $type          Define what type of note should retrieve.
	 *                                 Accepts 'customer', 'internal' or empty for both.
	 *                                 Default empty.
	 * }
	 * @return \stdClass[]              Array of stdClass objects with order notes details.
	 */
	static function get_order_notes( $args ) {
		return wc_get_order_notes( $args );
	}

	/**
	 * Get an order note
	 *
	 * @param  int|\WP_Comment $data Note ID (or WP_Comment instance for internal use only).
	 * @return \stdClass|null Object with order note details or null when does not exists.
	 */
	static function get_order_note( $data ) {
		return wc_get_order_note( $data );
	}
}
