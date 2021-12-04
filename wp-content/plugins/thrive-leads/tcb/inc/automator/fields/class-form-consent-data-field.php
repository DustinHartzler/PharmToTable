<?php

namespace TCB\Integrations\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Form_Consent_Field
 */
class Form_Consent_Data_Field extends \Thrive\Automator\Items\Data_Field {

	/**
	 * Field name
	 */
	public static function get_name() {
		return 'User consent form field';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Consent from the form data submitted by the user';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return 'Filter by user consent';
	}

	public static function get_id() {
		return 'user_consent';
	}

	public static function get_supported_filters() {
		return [ 'boolean' ];
	}

	public static function get_field_value_type() {
		return static::TYPE_BOOLEAN;
	}

	public static function get_dummy_value() {
		return 'TRUE';
	}
}
