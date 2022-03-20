<?php

namespace TCB\Integrations\Automator;

use Thrive\Automator\Items\Data_Object;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class Submit_Form extends \Thrive\Automator\Items\Trigger {

	public static function get_id() {
		return 'thrive/submitform';
	}

	public static function get_wp_hook() {
		return 'tcb_api_form_submit';
	}

	public static function get_provided_data_objects() {
		return [ 'form_data', 'user_data' ];
	}

	public static function get_hook_params_number() {
		return 1;
	}

	public static function get_app_name() {
		return 'Thrive Architect';
	}

	public static function get_name() {
		return 'Form submit';
	}

	public static function get_description() {
		return 'Triggers on each connection of a form on submit';
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
		$aut_id       = 0;
		if ( method_exists( Submit_Form::class, 'get_automation_id' ) ) {
			$aut_id = $this->get_automation_id();
		}

		if ( ! empty( $params ) ) {
			$form_data = $params[0];
			/* get all registered data objects and see which ones we use for this trigger */
			$data_object_classes = Data_Object::get();

			if ( empty( $data_object_classes['form_data'] ) ) {
				/* if we don't have a class that parses the current param, we just leave the value as it is */
				$data_objects['form_data'] = $form_data;
			} else {
				/* when a data object is available for the current parameter key, we create an instance that will handle the data */
				$data_objects['form_data'] = new $data_object_classes['form_data']( $form_data, $aut_id );
			}

			$user_data = null;
			/**
			 * try to match email with existing user
			 */
			if ( ! empty( $form_data['email'] ) ) {
				$matched_user = get_user_by( 'email', $form_data['email'] );
				if ( ! empty( $matched_user ) ) {
					$user_data = tvd_get_current_user_details( $matched_user->ID );
				}
			}
			if ( ! empty( $user_data ) ) {
				if ( empty( $data_object_classes['user_data'] ) ) {
					$data_objects['user_data'] = $user_data;
				} else {
					$data_objects['user_data'] = new $data_object_classes['user_data']( $user_data, $aut_id );
				}
			}
		}

		return $data_objects;
	}
}
