<?php

class TVD_Marketing_Utils {

	/**
	 * @param string $connection
	 * @param array  $data
	 *
	 * Example call => TVD_Marketing_Utils::update_tags(
												'convertkit',
												array(
												'email' => 'updateTagTest@sent.com',
												'tags'  => 'tag1,tag2',
												'extra' => array( 'list_identifier' => 703417 ),
												)
											)
	 * In order to work some apis will require a list_identifier param, while for others it is not required
	 *
	 * @return bool
	 */
	public static function update_tags( $connection, $data = array() ) {

		$api_instance = Thrive_Dash_List_Manager::connectionInstance( $connection );

		if ( true !== $api_instance instanceof Thrive_Dash_List_Connection_Abstract ) {
			return false;
		}

		$email = ! empty( $data['email'] ) ? $data['email'] : null;

		if ( empty( $email ) ) {
			return false;
		}

		$tags  = ! empty( $data['tags'] ) ? $data['tags'] : '';
		$extra = ! empty( $data['extra'] ) ? $data['extra'] : array();

		if ( is_array( $tags ) ) {
			$tags = implode( ',', $tags );
		}

		return $api_instance->updateTags( $email, $tags, $extra );
	}

	/**
	 * @param string $connection
	 * @param array  $data
	 *
	 * @return false|int
	 */
	public static function add_custom_fields( $connection, $data = array() ) {

		$api_instance = Thrive_Dash_List_Manager::connectionInstance( $connection );

		if ( true !== $api_instance instanceof Thrive_Dash_List_Connection_Abstract ) {
			return false;
		}

		$email = ! empty( $data['email'] ) ? $data['email'] : null;

		if ( empty( $email ) ) {
			return false;
		}

		$custom_fields = ! empty( $data['custom_fields'] ) && is_array( $data['custom_fields'] ) ? $data['custom_fields'] : array();
		$extra         = ! empty( $data['extra'] ) ? $data['extra'] : array();

		return $api_instance->addCustomFields( $email, $custom_fields, $extra );
	}
}
