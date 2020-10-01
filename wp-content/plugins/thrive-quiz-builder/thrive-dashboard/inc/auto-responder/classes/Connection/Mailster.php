<?php
/**
 * Created by PhpStorm.
 * User: Andrei L
 * Date: 6/5/2019
 * Time: 15:31
 */

class Thrive_Dash_List_Connection_Mailster extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * @return string
	 */
	public function getTitle() {
		return 'Mailster';
	}

	public function outputSetupForm() {
		$this->_directFormHtml( 'mailster' );
	}

	/**
	 * @return bool|mixed|string|Thrive_Dash_List_Connection_Abstract
	 */
	public function readCredentials() {

		if ( false === $this->pluginInstalled() ) {
			return __( 'Mailster plugin not installed or activated', TVE_DASH_TRANSLATE_DOMAIN );
		}

		$this->setCredentials( array( 'connected' => true ) );

		$result = $this->testConnection();

		if ( true !== $result ) {
			return $this->error( '<strong>' . $result . '</strong>)' );
		}

		$this->save();

		return true;
	}

	/**
	 * @return bool|string
	 */
	public function testConnection() {

		if ( false === $this->pluginInstalled() ) {
			return __( 'Mailster plugin not installed or activated', TVE_DASH_TRANSLATE_DOMAIN );
		}

		return true;
	}

	/**
	 * Add subscriber
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return bool|string
	 */
	public function addSubscriber( $list_identifier, $arguments ) {

		if ( false === $this->pluginInstalled() ) {
			return __( 'Mailster plugin not installed or activated', TVE_DASH_TRANSLATE_DOMAIN );
		}

		$mailster_instance = mailster( 'subscribers' );

		$args = array(
			'email'  => $arguments['email'],
			'status' => isset( $arguments['mailster_optin'] ) && 'd' === $arguments['mailster_optin'] ? 0 : 1,
		);

		if ( ! empty( $arguments['name'] ) ) {
			list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );
			$args['firstname'] = $first_name;
			$args['lastname']  = $last_name;
		}

		$subscriber = $mailster_instance->get_by_mail( $arguments['email'] );

		$subscriber_id = is_object( $subscriber )
			? $mailster_instance->update( $args, true, true )
			: $mailster_instance->add( $args );

		if ( null !== $subscriber_id ) {
			$mailster_instance->assign_lists( $subscriber_id, $list_identifier );
			$mailster_instance->add_custom_value( $subscriber_id, $this->_get_custom_fields_from_args( $arguments ) );

			return true;
		}

		return __( 'Mailster failed to add the subscriber', TVE_DASH_TRANSLATE_DOMAIN );
	}

	/**
	 * Get the custom fields from available args
	 *
	 * @param $args
	 *
	 * @return array
	 */
	private function _get_custom_fields_from_args( $args ) {
		$result = array();

		foreach ( $this->get_custom_fields() as $field ) {
			if ( isset( $args[ $field['id'] ] ) ) {
				$result[ $field['id'] ] = $args[ $field['id'] ];
			}
		}

		return $result;
	}

	protected function _apiInstance() {
	}

	/**
	 * @return bool|string|array
	 */
	protected function _getLists() {
		if ( false === $this->pluginInstalled() ) {
			return __( 'Mailster plugin not installed or activated', TVE_DASH_TRANSLATE_DOMAIN );
		}

		$lists = array();

		foreach ( mailster( 'lists' )->get() as $list ) {
			$lists[] = array(
				'id'   => $list->ID,
				'name' => $list->name,
			);
		}

		return $lists;
	}

	/**
	 * Chack if Mailster plugin is installed and activated
	 *
	 * @return bool
	 */
	public function pluginInstalled() {
		return function_exists( 'mailster' );
	}

	/**
	 * Get custom fields
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_custom_fields( $params = array() ) {

		/**
		 * Add our default custom fields
		 */
		foreach ( array( 'name', 'phone' ) as $field ) {
			mailster()->add_custom_field( $field );
		}

		$fields   = mailster()->get_custom_fields();
		$fields   = wp_list_filter( $fields, array( 'type' => 'textfield' ) );
		$response = array();

		foreach ( $fields as $key => $field ) {

			if ( ! empty( $field['name'] ) ) {
				$response[] = array(
					'id'          => $key,
					'placeholder' => $field['name'],
				);
			}
		}

		return $response;
	}
}
