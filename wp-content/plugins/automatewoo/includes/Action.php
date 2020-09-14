<?php

namespace AutomateWoo;

use AutomateWoo\Fields\Field;

/**
 * Abstract Class Action
 *
 * All workflow actions extend this class.
 */
abstract class Action {

	/**
	 * The action's unique name/slug.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The data items required by the action.
	 *
	 * @var array
	 */
	public $required_data_items = [];

	/**
	 * The action's title.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * The action's description.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * The action's group.
	 *
	 * @var string
	 */
	public $group;

	/**
	 * The action's fields objects.
	 *
	 * @var Field[]
	 */
	public $fields;

	/**
	 * Array containing the action's option values.
	 *
	 * @var array
	 */
	public $options;

	/**
	 * The workflow the action belongs to.
	 *
	 * This prop may not be set depending on the context.
	 *
	 * @var Workflow
	 */
	public $workflow;

	/**
	 * Knows if admin details have been loaded.
	 *
	 * @var bool
	 */
	protected $has_loaded_admin_details = false;

	/**
	 * Called when an action should be run.
	 *
	 * @throws \Exception When an error occurs.
	 */
	abstract public function run();

	/**
	 * Action constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * This method no longer has an explicit purpose and is deprecated.
	 *
	 * @deprecated
	 */
	public function init() {}

	/**
	 * Method to load the action's fields.
	 */
	public function load_fields() {}

	/**
	 * Method to set the action's admin props.
	 *
	 * Admin props include: title, group and description.
	 */
	protected function load_admin_details() {}

	/**
	 * Loads the action's admin props.
	 */
	protected function maybe_load_admin_details() {
		if ( ! $this->has_loaded_admin_details ) {
			$this->load_admin_details();
			$this->has_loaded_admin_details = true;
		}
	}

	/**
	 * Get the action's title.
	 *
	 * @param bool $prepend_group
	 * @return string
	 */
	public function get_title( $prepend_group = false ) {
		$this->maybe_load_admin_details();
		$group = $this->get_group();
		$title = $this->title ?: '';

		if ( $prepend_group && $group !== __( 'Other', 'automatewoo' ) ) {
			return $group . ' - ' . $title;
		}

		return $title;
	}

	/**
	 * Get the action's group.
	 *
	 * @return string
	 */
	public function get_group() {
		$this->maybe_load_admin_details();
		return $this->group ? $this->group : __( 'Other', 'automatewoo' );
	}

	/**
	 * Get the action's description.
	 *
	 * @return string
	 */
	public function get_description() {
		$this->maybe_load_admin_details();
		return $this->description ?: '';
	}

	/**
	 * Get the action's name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name ?: '';
	}

	/**
	 * Set the action's name.
	 *
	 * @param string $name
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Get the action's description HTML.
	 *
	 * @return string
	 */
	public function get_description_html() {
		if ( ! $this->get_description() ) {
			return '';
		}

		return '<p class="aw-field-description">' . $this->get_description() . '</p>';
	}

	/**
	 * Adds a field to the action.
	 *
	 * Should only be called in the load_fields() method.
	 *
	 * @param Field $field
	 */
	protected function add_field( $field ) {
		$field->set_name_base( 'aw_workflow_data[actions]' );
		$this->fields[ $field->get_name() ] = $field;
	}

	/**
	 * Removes a field from the action.
	 *
	 * Should only be called in the load_fields() method.
	 *
	 * @param string $field_name
	 */
	protected function remove_field( $field_name ) {
		unset( $this->fields[ $field_name ] );
	}

	/**
	 * Gets specific field belonging to the action.
	 *
	 * @param string $name
	 *
	 * @return Field|false
	 */
	public function get_field( $name ) {
		$this->get_fields();

		if ( ! isset( $this->fields[ $name ] ) ) {
			return false;
		}

		return $this->fields[ $name ];
	}

	/**
	 * Gets the action's fields.
	 *
	 * @return Field[]
	 */
	public function get_fields() {
		if ( ! isset( $this->fields ) ) {
			$this->fields = [];
			$this->load_fields();
		}

		return $this->fields;
	}

	/**
	 * Set the action's options.
	 *
	 * @param array $options
	 */
	public function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * Returns an option for use when running the action.
	 *
	 * Option value will already have been sanitized by it's field ::sanitize_value() method.
	 *
	 * @param string $field_name
	 * @param bool   $process_variables
	 * @param bool   $allow_html
	 *
	 * @return mixed Will vary depending on the field type specified in the action's fields.
	 */
	public function get_option( $field_name, $process_variables = false, $allow_html = false ) {
		$value = $this->get_option_raw( $field_name );

		// Process the option value only if it's a string
		// The value will almost always be a string but it could be a bool if the field is checkbox
		if ( $value && is_string( $value ) ) {
			if ( $process_variables ) {
				$value = $this->workflow->variable_processor()->process_field( $value, $allow_html );
			} elseif ( ! $allow_html ) {
				$value = html_entity_decode( wp_strip_all_tags( $value ) );
			}
		}

		return apply_filters( 'automatewoo_action_option', $value, $field_name, $process_variables, $this );
	}

	/**
	 * Get an option for use when editing the action.
	 *
	 * The value will be already sanitized by the field object.
	 * This is used to displaying an option value for editing.
	 *
	 * @since 4.4.0
	 *
	 * @param string $field_name
	 *
	 * @return mixed
	 */
	public function get_option_raw( $field_name ) {
		if ( isset( $this->options[ $field_name ] ) ) {
			return $this->options[ $field_name ];
		}

		return false;
	}

	/**
	 * Used to dynamically load option values for an action field.
	 *
	 * @param string       $field_name
	 * @param string|false $reference_field_value
	 *
	 * @return array
	 */
	public function get_dynamic_field_options( $field_name, $reference_field_value = false ) {
		return [];
	}

	/**
	 * Check requirements for the action.
	 *
	 * Runs before an action is loaded in the admin area.
	 */
	public function check_requirements() {}

	/**
	 * Display a warning in the admin area.
	 *
	 * @param string $message
	 */
	public function warning( $message ) {
		if ( ! is_admin() ) {
			return;
		}
		?>
		<script type="text/javascript">
			alert('ERROR: <?php echo esc_html( $message ); ?>');
		</script>
		<?php
	}

	/**
	 * Get text for action deprecation warning.
	 *
	 * @return string
	 */
	protected function get_deprecation_warning() {
		return __( 'THIS ACTION IS DEPRECATED AND SHOULD NOT BE USED.', 'automatewoo' );
	}

	/**
	 * Does this action have a preview ability?
	 *
	 * To enable preview for an action simply add a preview() method.
	 *
	 * @since 4.4.0
	 *
	 * @return bool
	 */
	public function can_be_previewed() {
		return method_exists( $this, 'preview' );
	}

}
