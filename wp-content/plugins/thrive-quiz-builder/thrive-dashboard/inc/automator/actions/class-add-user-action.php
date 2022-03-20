<?php

namespace TVE\Dashboard\Automator;

use Thrive\Automator\Items\Action;
use Thrive\Automator\Items\Action_Field;
use Thrive\Automator\Utils;
use Thrive_Dash_List_Manager;
use function Thrive\Automator\tap_logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Tag_User
 */
class Add_User extends Action {
	private $autoresponder;

	private $additional = array();

	/**
	 * Get the action identifier
	 *
	 * @return string
	 */
	public static function get_id() {
		return 'thrive/adduser';
	}

	/**
	 * Get the action name/label
	 *
	 * @return string
	 */
	public static function get_name() {
		return 'Add user in autoresponder';
	}

	/**
	 * Get the action description
	 *
	 * @return string
	 */
	public static function get_description() {
		return 'Add user to autoresponder';
	}

	/**
	 * Get the action logo
	 *
	 * @return string
	 */
	public static function get_image() {
		return 'tap-add-user';
	}

	/**
	 * Get the name of app to which action belongs
	 *
	 * @return string
	 */
	public static function get_app_name() {
		return 'Thrive Dashboard';
	}

	public static function get_required_data_objects() {
		return array( 'user_data', 'form_data', 'email_data' );
	}

	/**
	 * Array of action-field keys, required for the action to be setup
	 *
	 * @return array
	 */
	public static function get_required_action_fields() {
		return array( 'autoresponder' => array( 'mailing_list' ) );
	}

	public function prepare_data( $data = array() ) {
		if ( ! empty( $data['extra_data'] ) ) {
			$data = $data['extra_data'];
		}

		$this->autoresponder = $data['autoresponder']['value'];

		$this->build_subfield( $data['autoresponder']['subfield'] );
	}

	/**
	 * Init all subfields
	 *
	 * @param $data
	 */
	public function build_subfield( $data ) {
		foreach ( $data as $key => $subfield ) {
			if ( ! empty( $subfield['value'] ) ) {
				$this->additional[ $key ] = $subfield['value'];
			}
			if ( ! empty( $subfield['subfield'] ) ) {
				$this->build_subfield( $subfield['subfield'] );
			}
		}
	}

	public function do_action( $data ) {
		$email = '';
		/**
		 * Filter the data objects that might provide user data
		 */
		$data_sets = apply_filters( 'tvd_automator_api_data_sets', [] );
		/**
		 * Make sure that user_data is always the last item
		 */
		$data_sets   = array_diff( $data_sets, [ 'user_data' ] );
		$data_sets[] = 'user_data';

		global $automation_data;
		/**
		 * Try to get email for available data objects
		 */
		while ( ! empty( $data_sets ) && empty( $email ) ) {
			$set         = array_shift( $data_sets );
			$data_object = $automation_data->get( $set );
			if ( ! empty( $data_object ) && $data_object->can_provide_email() ) {
				$email = $data_object->get_provided_email();
			}
		}

		if ( empty( $email ) ) {
			return false;
		}
		$api_load = array( 'email' => $email );

		$apis = Thrive_Dash_List_Manager::getAvailableAPIs( true );

		$api = $apis[ $this->autoresponder ];
		if ( empty( $api ) ) {
			return false;
		}

		if ( ! empty( $this->additional['tag_input'] ) && $api->hasTags() ) {
			$tags = $this->additional['tag_input'];
			if ( is_array( $tags ) ) {
				$tags = implode( ', ', $tags );
			}
			$api_load[ $api->getTagsKey() ] = $tags;
		}

		if ( ! empty( $this->additional['tag_select'] ) && $api->hasTags() ) {
			$tags                           = $this->additional['tag_select'];
			$api_load[ $api->getTagsKey() ] = $tags;
		}

		if ( ! empty( $this->additional['optin'] ) && $api->hasTags() ) {
			$api_load[ $api->getOptinKey() ] = $this->additional['optin'];
		}

		if ( ! empty( $this->additional['form_list'] ) && $api->hasForms() ) {
			$load[ $api->getFormsKey() ] = $this->additional['form_list'];
		}

		$list_identifier = ! empty( $this->additional['mailing_list'] ) ? $this->additional['mailing_list'] : null;

		return $api->addSubscriber( $list_identifier, $api_load );
	}

	/**
	 * For APIs with forms add it as required field
	 *
	 * @param $data
	 *
	 * @return array|string[][]|string[][][]
	 */
	public static function get_action_mapped_fields( $data ) {
		$fields = static::get_required_action_fields();
		if ( property_exists( $data, 'autoresponder' ) ) {
			$api_instance = Thrive_Dash_List_Manager::connectionInstance( $data->autoresponder->value );
			if ( $api_instance->isConnected() && $api_instance->hasForms() ) {
				$fields = array( 'autoresponder' => array( 'mailing_list' => array( 'form_list' ) ) );
			}
		}

		return $fields;
	}


	public static function get_subfields( $field, $selected_value, $action_data ) {
		$api_instance = Thrive_Dash_List_Manager::connectionInstance( $selected_value );

		if ( ! $api_instance && property_exists( $action_data, 'autoresponder' ) ) {
			$api_instance = Thrive_Dash_List_Manager::connectionInstance( $action_data->autoresponder->value );
		}

		$fields = array();
		if ( $api_instance->isConnected() ) {
			$field_keys = $api_instance->get_automator_autoresponder_fields();

			if ( ! empty( $field_keys ) ) {
				$available_fields = Action_Field::get();
				foreach ( $field_keys as $subfield ) {
					$subfield_class = $available_fields[ $subfield ];
					$state_data     = $subfield_class::localize();
					if ( $subfield === 'tag_input' ) {
						$state_data['validators'] = array();
					}
					if ( Utils::is_multiple( $subfield_class::get_type() ) ) {
						$state_data['values'] = $subfield_class::get_options_callback( $selected_value );
					}
					$fields[ $state_data['id'] ] = $state_data;
				}
			}
			$fields = $api_instance->set_custom_autoresponder_fields( $fields, $field, $action_data );
		}

		return $fields;
	}

	/**
	 * Match all trigger that provice user/form data
	 *
	 * @param $trigger
	 *
	 * @return bool
	 */
	public static function is_compatible_with_trigger( $provided_data_objects ) {
		$action_data_keys = static::get_required_data_objects() ?: array();

		return count( array_intersect( $action_data_keys, $provided_data_objects ) ) > 0;
	}

	public function can_run( $data ) {
		$valid          = true;
		$available_data = array();
		global $automation_data;
		foreach ( static::get_required_data_objects() as $key ) {
			if ( ! empty( $automation_data->get( $key ) ) ) {
				$available_data[] = $key;
			}
		}

		if ( empty( $available_data ) ) {
			$valid = false;
			tap_logger()->register( [
				'key'         => static::get_id(),
				'id'          => 'data-not-provided-to-action',
				'message'     => 'Data object required by ' . static::class . ' action is not provided by trigger',
				'class-label' => tap_logger()->get_nice_class_name( static::class ),
			] );
		}

		return $valid;
	}

}
