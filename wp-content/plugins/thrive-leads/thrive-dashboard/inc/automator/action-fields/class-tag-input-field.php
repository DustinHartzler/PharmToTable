<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Tag_Input_Field
 */
class Tag_Input_Field extends \Thrive\Automator\Items\Action_Field {

	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Add the following tags to the user';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Which tag(s) would you like to apply?  Use a comma to add multiple tags. Example:- tag1, tag2';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	/**
	 * $$value will be replaced by field value
	 * $$length will be replaced by value length
	 *
	 * @var string
	 */
	public static function get_preview_template() {
		return '$$length tags';
	}

	public static function get_id() {
		return 'tag_input';
	}

	public static function get_type() {
		return 'tags';
	}

	public static function get_validators() {
		return array( 'required' );
	}

	public static function allow_dynamic_data() {
		return true;
	}
}
