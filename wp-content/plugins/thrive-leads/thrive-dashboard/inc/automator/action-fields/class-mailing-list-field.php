<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Mailing_List_Field
 */
class Mailing_List_Field extends \Thrive\Automator\Items\Action_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Add the user to the following list';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Select an autoresponder mailing list to add the user to';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return 'Choose list';
	}

	/**
	 * $$value will be replaced by field value
	 * $$length will be replaced by value length
	 *
	 * @var string
	 */
	public static function get_preview_template() {
		return 'List: $$value';
	}

	/**
	 * For multiple option inputs, name of the callback function called through ajax to get the options
	 */
	public static function get_options_callback() {
		$values = array();
		$args   = func_get_args();
		if ( ! empty( $args ) ) {
			$api          = $args[0];
			$api_instance = \Thrive_Dash_List_Manager::connectionInstance( $api );
			if ( $api_instance && $api_instance->isConnected() ) {

				$values = $api_instance->getLists( false );
				if ( $api_instance->hasForms() ) {
					$forms = $api_instance->getForms();
					foreach ( $values as $key => $list ) {
						$values[ $key ]['values'] = $forms[ $list['id'] ];
					}
				}
			}

		}

		return $values;
	}

	public static function get_id() {
		return 'mailing_list';
	}

	public static function get_type() {
		return 'select';
	}

	public static function is_ajax_field() {
		return true;
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
