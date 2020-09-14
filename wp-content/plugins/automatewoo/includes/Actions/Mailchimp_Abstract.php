<?php
// phpcs:ignoreFile

namespace AutomateWoo;

use AutomateWoo\Fields\Select;
use AutomateWoo\Fields\Text;
use AutomateWoo\Traits\TagField;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Mailchimp_Abstract
 */
abstract class Action_Mailchimp_Abstract extends Action {

	use TagField;

	function load_admin_details() {
		$this->group = __( 'MailChimp', 'automatewoo' );
	}


	/**
	 * @return Fields\Select
	 */
	function add_list_field() {
		$list_select = ( new Select() )
			->set_title( __( 'List', 'automatewoo' ) )
			->set_name( 'list' )
			->set_options( Integrations::mailchimp()->get_lists() )
			->set_required();

		$this->add_field( $list_select );
		return $list_select;
	}


	/**
	 * Get the MailChimp contact email field.
	 *
	 * @since 4.5
	 *
	 * @return Fields\Text
	 */
	function get_contact_email_field() {
		$field = ( new Text() )
			->set_name( 'email' )
			->set_title( __( 'Contact email', 'automatewoo' ) )
			->set_description( __( 'Use variables such as {{ customer.email }} here. If blank {{ customer.email }} will be used.', 'automatewoo' ) )
			->set_placeholder( '{{ customer.email }}' )
			->set_variable_validation();

		return $field;
	}


	/**
	 * Get the contact email option. Defaults to {{ customer.email }}.
	 *
	 * @since 4.5
	 *
	 * @return string|bool
	 */
	function get_contact_email_option() {
		$email = Clean::email( $this->get_option( 'email', true ) );

		if ( $email ) {
			return $email;
		}

		$customer = $this->workflow->data_layer()->get_customer();

		if ( ! $customer ) {
			return false;
		}

		return $customer->get_email();
	}

	/**
	 * Add a tags field to the action.
	 *
	 * @param string $name  (Optional) The name for the tag.
	 * @param string $title (Optional) The title to display for the tag.
	 *
	 * @return Text
	 */
	protected function add_tags_field( $name = null, $title = null ) {
		$tag = $this->get_tags_field( $name, $title )
			->set_description( __( 'Add multiple tags separated by commas. Please note that tags are not case-sensitive.', 'automatewoo' ) );

		$this->add_field( $tag );

		return $tag;
	}

	/**
	 * Validate that a contact is a member of a given list.
	 *
	 * @param string $email   The email address.
	 * @param string $list_id The list ID.
	 *
	 * @throws \Exception When the contact is not valid for the list.
	 */
	protected function validate_contact( $email, $list_id ) {
		if ( ! Integrations::mailchimp()->is_subscribed_to_list( $email, $list_id ) ) {
			throw new \Exception( __( 'Failed because contact is not subscribed to the list.', 'automatewoo' ) );
		}
	}
}
