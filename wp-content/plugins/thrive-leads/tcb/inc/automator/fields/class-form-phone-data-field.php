<?php

namespace TCB\Integrations\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Form_Phone_Field
 */
class Form_Phone_Data_Field extends \Thrive\Automator\Items\Data_Field {

	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Phone form field';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Phone from the form data submitted by the user';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return 'Filter by phone';
	}

	public static function get_id() {
		return 'phone';
	}

	public static function get_supported_filters() {
		return [ 'string_ec' ];
	}

	public static function get_validators() {
		return [ 'phone' ];
	}

	public static function get_field_value_type() {
		return static::TYPE_STRING;
	}

	public static function get_dummy_value() {
		return '07906578743';
	}

}
