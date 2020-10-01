<?php


class Thrive_Dash_List_Connection_Wordpress extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'other';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'WordPress account';
	}

	/**
	 * this requires a special naming here, as it's about wordpress roles, not lists of subscribers
	 *
	 * @return string
	 */
	public function getListSubtitle() {
		return 'Choose the role which should be assigned to your subscribers';
	}


	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'wordpress' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		$this->setCredentials( array( 'e' => true ) );

		/**
		 * finally, save the connection details
		 */
		$this->save();

		return true;
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		/**
		 * wordpress integration is always supported
		 */
		return true;
	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		// no API instance needed here
		return null;
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array
	 */
	protected function _getLists() {

		$roles = array();

		foreach ( $this->_getRoles() as $key => $role_data ) {
			$roles[] = array(
				'id'   => $key,
				'name' => $role_data['name'],
			);
		}

		return $roles;
	}

	/**
	 * List of accepted roles
	 *
	 * @return array[]
	 */
	protected function _getRoles() {
		/* get_editable_roles only loaded in the admin sections */
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );
		}
		$user_roles = get_editable_roles();
		if ( ! isset( $user_roles['subscriber'] ) ) {
			return array();
		}

		return array(
			'subscriber' => $user_roles['subscriber'],
		);
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {

		if ( is_user_logged_in() ) {
			return $this->error( __( 'You are already logged in. Please Logout in order to create a new user.', TVE_DASH_TRANSLATE_DOMAIN ) );
		}
		if ( ! isset( $arguments['email'] ) || empty( $arguments['email'] ) || ! is_email( $arguments['email'] ) ) {
			return $this->error( __( 'Please enter a valid email address.', TVE_DASH_TRANSLATE_DOMAIN ) );
		}
		$username = $arguments['email'];
		$user_id  = username_exists( $username );

		/**
		 * if we already have this username
		 */
		if ( $user_id ) {
			$username              = $username . rand( 3, 5 );
			$user_id               = null;
			$arguments['username'] = $username;
		}

		/**
		 * check if passwords parameters exist and if they are the same in case they're two
		 */
		if ( isset( $arguments['password'] ) ) {
			if ( isset( $arguments['confirm_password'] ) && $arguments['password'] != $arguments['confirm_password'] ) {
				return $this->error( __( 'Passwords do not match', TVE_DASH_TRANSLATE_DOMAIN ) );
			}

			if ( ! $user_id && email_exists( $arguments['email'] ) == false ) {
				$user_data = apply_filters( 'tvd_create_user_data', array(
					'user_login' => $username,
					'user_pass'  => $arguments['password'],
					'user_email' => $arguments['email'],
				) );

				$user_id = wp_insert_user( $user_data );

			} else {
				return $this->error( __( 'Email or username are already used', TVE_DASH_TRANSLATE_DOMAIN ) );
			}

		} else {
			$user_id = register_new_user( $arguments['email'], $arguments['email'] );

		}

		if ( $user_id instanceof WP_Error ) {
			return $user_id->get_error_message();
		}

		if ( ! empty( $arguments['name'] ) ) {
			list( $first_name, $last_name ) = $this->_getNameParts( $arguments['name'] );
			update_user_meta( $user_id, 'first_name', $first_name );
			if ( $last_name ) {
				update_user_meta( $user_id, 'last_name', $last_name );
			}

			//WP has an hook for his action which expects 2 parameters
			//we fake the second param
			$old_data            = new stdClass();
			$old_data->user_pass = '';

			do_action( 'profile_update', $user_id, $old_data );
		}

		/**
		 * also, assign the selected role to the newly created user
		 */
		$user = new WP_User( $user_id );

		if ( array_key_exists( $list_identifier, $this->_getRoles() ) ) {
			$user->set_role( $list_identifier );
		} else {
			/**
			 * don't let new users get role from what admin had set in WP Settings, because user might have set Administrator role for new users
			 * - in case there is no accepted role
			 */
			$user->set_role( 'subscriber' );
		}

		do_action( 'tvd_after_create_wordpress_account', $user, $arguments );

		return true;

	}

	/**
	 * Get API custom form fields. By default we have only name and phone
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_custom_fields( $params = array() ) {
		return array(
			array( 'id' => 'name', 'placeholder' => __( 'Name', 'thrive-cb' ) ),
			array( 'id' => 'phone', 'placeholder' => __( 'Phone', 'thrive-cb' ) ),
			array( 'id' => 'password', 'placeholder' => __( 'Password', 'thrive-cb' ) ),
			array( 'id' => 'confirm_password', 'placeholder' => __( 'Confirm password', 'thrive-cb' ) ),
		);
	}
}
