<?php

namespace AutomateWoo\Exceptions;

use InvalidArgumentException;

defined( 'ABSPATH' ) || exit;

/**
 * InvalidPreviewData exception class.
 *
 * @since   4.9.2
 * @package AutomateWoo\Exceptions
 */
class InvalidPreviewData extends InvalidArgumentException implements UserFacingException {

	/**
	 * Creates a new instance of the exception when an action can't be previewed.
	 *
	 * @return static
	 */
	public static function invalid_action() {
		return new static( __( 'This action can not be previewed.', 'automatewoo' ) );
	}

	/**
	 * Creates a new instance of the exception with a generic message.
	 *
	 * @return static
	 */
	public static function generic() {
		return new static( __( 'There was an error generating the preview.', 'automatewoo' ) );
	}

	/**
	 * Creates a new instance of the exception when a valid order isn't found.
	 *
	 * @return static
	 */
	public static function order_not_found() {
		return new static( __( 'No valid preview order was found.', 'automatewoo' ) );
	}

}
