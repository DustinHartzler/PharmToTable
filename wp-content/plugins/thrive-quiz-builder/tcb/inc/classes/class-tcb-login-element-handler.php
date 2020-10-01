<?php

/**
 * Class TCB_Login_Element_Handler
 *
 * Handle Login element submit
 */
class TCB_Login_Element_Handler {

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			add_action( 'wp_ajax_nopriv_tve_login_submit', array( $this, 'submit' ) );
			add_action( 'wp_ajax_tve_login_submit', array( $this, 'submit' ) );
		}

		add_action( 'tcb_login_action_login', array( $this, 'action_login' ) );
		add_action( 'tcb_login_action_recover_password', array( $this, 'action_recover_password' ) );
		add_filter( 'tcb_dynamiclink_data', array( $this, 'dynamiclink_data' ), 100 );

		add_shortcode( 'thrive_login_form_shortcode', array( $this, 'login_form_shortcode' ) );
	}

	/**
	 * Remove actions and filters that might affect submit action
	 */
	private function _clear() {

		global $WishListMemberInstance;

		$is_wl = class_exists( 'WishListMember3', false ) && $WishListMemberInstance instanceof WishListMember3;

		if ( true === $is_wl ) {
			remove_action( 'template_redirect', array( $WishListMemberInstance , 'Process' ), 1 );
		}
	}

	/**
	 * Handle Submit action
	 */
	public function submit() {

		$this->_clear();

		$data = $_POST;

		if ( isset( $data['custom_action'] ) ) {

			/**
			 * Fire a hook for each action of the Login Element
			 */
			do_action( 'tcb_login_action_' . $data['custom_action'], $data );
		}

		wp_send_json( array( 'error' => 'ERROR!! No handler provided for the request' ) );
	}

	/**
	 * Handle Login Action for Login Element
	 *
	 * @param array $data
	 */
	public function action_login( $data ) {
		$args['user_login']    = isset( $data['username'] ) ? sanitize_text_field( $data['username'] ) : '';
		$args['user_password'] = isset( $data['password'] ) ? sanitize_text_field( $data['password'] ) : '';
		$args['remember']      = ! empty( $data['remember_me'] );

		/**
		 * Do not send back the password
		 */
		unset( $data['password'] );

		$user = wp_signon( $args );

		$data['success'] = $user instanceof WP_User;
		$data['errors']  = $user instanceof WP_Error ? $user->get_error_messages() : array();

		/**
		 * Allow other plugins to manipulate the response
		 *
		 * @param array $data array of data to be sent back
		 *
		 * @return array
		 */
		$data = apply_filters( 'tcb_after_user_logged_in', $data );

		wp_send_json( $data );
	}

	/**
	 * Handle Forgot Password Action for Login Element
	 *
	 * @param array $data
	 */
	public function action_recover_password( $data ) {
		$data['success'] = false;
		$data['errors']  = array();

		$user_data = $this->get_user( sanitize_text_field( $data['login'] ) );

		if ( ! empty( $user_data['errors'] ) ) {

			$data['errors'] = $user_data['errors'];

			wp_send_json( $data );
		}

		$key = get_password_reset_key( $user_data['user'] );

		if ( is_wp_error( $key ) ) {
			$data['errors']['nokey'] = __( 'Failed to reset password. Please try again', 'thrive-cb' );

			wp_send_json( $data );
		}

		$result = $this->send_recover_pass_msg( $user_data['user'], $key );

		$data['success'] = $result;

		if ( true !== $result ) {
			$data['errors']['failed_email'] = __( 'Failed to send password recovery email!', 'thrive-cb' );
		}

		wp_send_json( $data );
	}

	/**
	 * Send Recover Password Message
	 *
	 * @param WP_User $user
	 * @param string  $key
	 *
	 * @return bool
	 */
	public function send_recover_pass_msg( WP_User $user, $key ) {
		if ( is_multisite() ) {
			$site_name = get_network()->site_name;
		} else {
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		ob_start();

		include tve_editor_path( 'inc/views/actions/login/recover-password.php' );

		$message = ob_get_contents();

		ob_end_clean();

		/**
		 * Password Recover Email Subject
		 */
		$title = sprintf( __( '[%s] Password Reset' ), $site_name );

		return wp_mail( $user->user_email, wp_specialchars_decode( $title ), $message, 'Content-Type: text/html; charset=UTF-8' );
	}

	/**
	 * @param string $data
	 *
	 * @return array
	 */
	public function get_user( $data ) {

		$response = array();

		$response['user']   = '';
		$response['errors'] = array();

		if ( empty( $data ) || ! is_string( $data ) ) {
			$response['errors']['empty_username'] = __( 'Enter a username or email address.', 'thrive-cb' );

			return $response;
		}

		$data      = sanitize_text_field( $data );
		$field     = strpos( $data, '@' ) ? 'email' : 'login';
		$user_data = get_user_by( $field, $data );

		if ( ! $user_data instanceof WP_User ) {
			$response['errors']['invalidcombo'] = __( 'Invalid username or email.', 'thrive-cb' );
		}

		$response['user'] = $user_data;

		return $response;
	}

	/**
	 * Get available actions for Login element
	 *
	 * @return array
	 */
	public static function get_post_login_actions() {

		$actions = array(
			array(
				'key'          => 'redirect',
				'label'        => __( 'Redirect to Custom URL', 'thrive_cb' ),
				'icon'         => 'url',
				'preview_icon' => 'redirect-resp',
			),
			array(
				'key'          => 'noRedirect',
				'label'        => __( 'No Redirect', 'thrive_cb' ),
				'icon'         => 'wordpress',
				'preview_icon' => 'redirect-resp',
			),
			array(
				'key'          => 'showMessage',
				'label'        => __( 'Show success notification', 'thrive_cb' ),
				'icon'         => 'url',
				'preview_icon' => 'redirect-resp',
			),
		);

		/**
		 * Allows dynamically modifying post login actions.
		 *
		 * @param array $actions array of actions to be filtered
		 *
		 * @return array
		 */
		return apply_filters( 'tcb_post_login_actions', $actions );
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function dynamiclink_data( $data ) {

		$data['Login Form'] = array(
			'links'     => array(
				0 => array(
					'bk_to_login' => array(
						'name' => 'Back to Login',
						'url'  => '',
						'show' => 1,
						'id'   => 'bk_to_login',
					),
					'pass_reset'  => array(
						'name' => 'Password Reset',
						'url'  => '',
						'show' => 1,
						'id'   => 'forgot_password',
					),
					'logout'      => array(
						'name' => 'Logout',
						'url'  => wp_logout_url(),
						'show' => 1,
						'id'   => 'logout',
					),
					'login'       => array(
						'name' => 'Log In',
						'url'  => '',
						'show' => 1,
						'id'   => 'login',
					),
				),
			),
			'shortcode' => 'thrive_login_form_shortcode',
		);

		return $data;
	}

	/**
	 * @param $args
	 *
	 * @return string
	 */
	public function login_form_shortcode( $args ) {

		if ( ! isset( $args['id'] ) ) {

			return '';
		}

		$data = 'javascript:void(0)';

		switch ( $args['id'] ) {
			case 'logout':
				$data = wp_logout_url();
				break;
			default;
				break;
		}

		return $data;
	}
}

new TCB_Login_Element_Handler();
