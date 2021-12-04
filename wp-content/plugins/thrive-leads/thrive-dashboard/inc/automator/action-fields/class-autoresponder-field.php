<?php

namespace TVE\Dashboard\Automator;

use Thrive\Automator\Items\Action_Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Autoresponder_Field
 */
class Autoresponder_Field extends \Thrive\Automator\Items\Action_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Autoresponder';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Choose service from your list of registered APIs to use';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return 'Choose autoresponder';
	}

	/**
	 * $$value will be replaced by field value
	 * $$length will be replaced by value length
	 *
	 * @var string
	 */
	public static function get_preview_template() {
		return 'Autoresponder: $$value';
	}

	/**
	 * For multiple option inputs, name of the callback function called through ajax to get the options
	 */
	public static function get_options_callback() {
		$apis   = \Thrive_Dash_List_Manager::getAvailableAPIs( true, [
			'email',
			'webinar',
			'other',
			'recaptcha',
			'social',
			'sellings',
			'integrations',
			'email',
			'storage'
		] );
		$values = array();
		foreach ( $apis as $api ) {
			//email is seen as autoresponder
			if ( ! in_array( $api->getKey(), array( 'email', 'wordpress' ) ) ) {
				$values[ $api->getKey() ] = array( 'id' => $api->getKey(), 'label' => $api->getTitle() );
			}
		}

		return $values;
	}

	public static function get_id() {
		return 'autoresponder';
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
