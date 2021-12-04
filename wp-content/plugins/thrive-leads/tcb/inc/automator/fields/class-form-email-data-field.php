<?php

namespace TCB\Integrations\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Form_Email_Field
 */
class Form_Email_Data_Field extends \Thrive\Automator\Items\Data_Field {

	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Email form field';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Email from the form data submitted by the user';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return 'Filter by email';
	}

	public static function get_id() {
		return 'email';
	}

	public static function get_supported_filters() {
		return [ 'string_equals' ];
	}

	public static function get_validators() {
		return [ 'required', 'email' ];
	}

	public static function get_field_value_type() {
		return static::TYPE_STRING;
	}

	public static function get_dummy_value() {
		return 'john_doe@fakemail.com';
	}
}
