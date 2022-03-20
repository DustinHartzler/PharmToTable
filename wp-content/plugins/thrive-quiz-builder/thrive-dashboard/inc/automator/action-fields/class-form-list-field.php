<?php

namespace TVE\Dashboard\Automator;

use Thrive\Automator\Items\Action_Field;
use Thrive_Dash_List_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Form_List_Field
 */
class Form_List_Field extends Action_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return __( 'Select the form', TVE_DASH_TRANSLATE_DOMAIN );
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return __( 'Choose the form you want to use', TVE_DASH_TRANSLATE_DOMAIN );
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return __( 'Choose form', TVE_DASH_TRANSLATE_DOMAIN );
	}

	/**
	 * $$value will be replaced by field value
	 * $$length will be replaced by value length
	 *
	 *
	 * @return string
	 */
	public static function get_preview_template() {
		return 'Form: $$value';
	}

	public static function get_id() {
		return 'form_list';
	}

	public static function get_type() {
		return 'select';
	}

	public static function get_options_callback() {
		$args         = func_get_args();
		$values       = array();
		$api_instance = Thrive_Dash_List_Manager::connectionInstance( $args[0] );
		if ( $api_instance && $api_instance->isConnected() && $api_instance->hasForms() ) {
			$values = $api_instance->getForms();
		}

		return $values;
	}
}
