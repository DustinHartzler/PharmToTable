<?php

namespace TVE\Dashboard\Automator;

use Thrive\Automator\Items\Data_Object;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class Register_Form_Submit extends \Thrive\Automator\Items\Trigger {

	public static function get_id() {
		return 'thrive/registerform';
	}

	public static function get_wp_hook() {
		return 'thrive_register_form_through_wordpress_user';
	}

	public static function get_provided_data_objects() {
		return [ 'user_data', 'form_data' ];
	}

	public static function get_hook_params_number() {
		return 2;
	}

	public static function get_app_name() {
		return 'Thrive Architect';
	}

	public static function get_name() {
		return 'Registration form submitted';
	}

	public static function get_description() {
		return 'Triggers only when a new user is created after submitting a registration form. This trigger does not fire if the user account already existed.';
	}

	public static function get_image() {
		return 'tap-architect-logo';
	}

	/**
	 * Override default method so we manually init user data if we can match the form's email with an existing user
	 *
	 * @param array $params
	 *
	 * @return array
	 * @see Automation::start()
	 */
	public function process_params( $params = array() ) {

		$data_objects = array();
		$aut_id = $this->get_automation_id();

		if ( ! empty( $params ) ) {
			$data_object_classes = Data_Object::get();
			if ( empty( $data_object_classes['user_data'] ) ) {
				$data_objects['user_data'] = $params[0];
			} else {
				$data_objects['user_data'] = new $data_object_classes['user_data']( $params[0], $aut_id );
			}
			if ( ! empty( $params[1] ) ) {
				$form_data = $params[1];
				if ( empty( $data_object_classes['form_data'] ) ) {
					/* if we don't have a class that parses the current param, we just leave the value as it is */
					$data_objects['form_data'] = $form_data;
				} else {
					/* when a data object is available for the current parameter key, we create an instance that will handle the data */
					$data_objects['form_data'] = new $data_object_classes['form_data']( $form_data, $aut_id );
				}
			}
		}

		return $data_objects;
	}
}
