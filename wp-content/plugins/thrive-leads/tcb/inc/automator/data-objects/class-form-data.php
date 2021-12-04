<?php

namespace TCB\Integrations\Automator;

use TCB\inc\helpers\FormSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Form_Data
 */
class Form_Data extends \Thrive\Automator\Items\Data_Object {

	/**
	 * Get the data-object identifier
	 *
	 * @return string
	 */
	public static function get_id() {
		return 'form_data';
	}

	/**
	 * Array of field object keys that are contained by this data-object
	 *
	 * @return array
	 */
	public static function get_fields() {
		return [ 'user_consent', 'email', 'name', 'phone', 'post_id', 'form_identifier' ];
	}

	public static function create_object( $param ) {
		$post_data = [];

		$allowed_keys = [
			'name',
			'email',
			'user_consent',
			'phone',
			'url',
			'post_id',
		];

		foreach ( $param as $key => $value ) {
			if ( in_array( $key, $allowed_keys ) || strpos( $key, 'mapping_' ) !== false ) {
				$post_data[ $key ] = $value;
			}
		}
		if ( isset( $param['_tcb_id'] ) ) {
			$form = FormSettings::get_one( $param['_tcb_id'] );

			$post_data['form_identifier'] = $form->form_identifier;
		}

		return $post_data;
	}

	public function can_provide_email() {
		return true;
	}

	public function get_provided_email() {
		return $this->get_value( 'quiz_user_email' );
	}

}
