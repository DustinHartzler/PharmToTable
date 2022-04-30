<?php
/**
 * LearnDash class for displaying the setup wizard.
 *
 * @package    LearnDash
 * @subpackage Search
 * @since      4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LearnDash_Setup_Wizard' ) ) {
	/**
	 * Setup wizard class.
	 */
	class LearnDash_Setup_Wizard {
		/**
		 * The opened status, can be completed, ongoing or closed.
		 */
		const STATUS_KEY = 'learndash_setup_wizard_status';

		const STATUS_COMPLETED = 'completed';
		const STATUS_ONGOING   = 'ongoing';
		const STATUS_CLOSED    = 'closed';

		const DATA_KEY = 'learndash_setup_wizard';

		const CERTIFICATE_BUILDER_SLUG   = 'learndash-certificate-builder/learndash-certificate-builder.php';
		const COURSE_GRID_SLUG           = 'learndash-course-grid/learndash_course_grid.php';
		const WOOCOMMERCE_SLUG           = 'woocommerce/woocommerce.php';
		const LEARNDASH_WOOCOMMERCE_SLUG = 'learndash-woocommerce/learndash_woocommerce.php';

		const HANDLE = 'learndash-setup-wizard';

		const LICENSE_KEY       = 'nss_plugin_license_sfwd_lms';
		const LICENSE_EMAIL_KEY = 'nss_plugin_license_email_sfwd_lms';

		const FINAL_ADMIN_REDIRECT_PAGE = 'admin.php?page=learndash_lms_overview';

		/**
		 * Array of LearnDash plugins slug.
		 *
		 * @var array LearnDash plugins slug.
		 */
		private static $learndash_plugins_slug = array( 'learndash-certificate-builder', 'learndash-course-grid', 'learndash-woocommerce' );

		/**
		 * The single instance of the class.
		 */
		public function __construct() {
			if ( ! is_admin() ) {
				return;
			}

			add_action( 'shutdown', array( $this, 'wp_shutdown' ) );
			add_action( 'admin_init', array( $this, 'redirect_after_activation' ), 1 );
			add_action( 'admin_menu', array( $this, 'register_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_init', array( $this, 'dismiss' ) );
			add_action( 'wp_ajax_learndash_setup_wizard_verify_license', array( $this, 'verify_license' ) );
			add_action( 'wp_ajax_learndash_setup_wizard_save_data', array( $this, 'save_data' ) );
			add_action( 'wp_ajax_learndash_finalize', array( $this, 'finalize_setup' ) );
			add_action( 'admin_post_stripe_connect_wizard_process', array( $this, 'enable_stripe_connect_and_redirect' ) );
		}

		/**
		 * Enable stripe connect and then redirect to the wizard again.
		 */
		public function enable_stripe_connect_and_redirect() {
			// enable stripe connect.
			if ( LearnDash_Settings_Section_Stripe_Connect::is_stripe_connected() ) {
				LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Stripe_Connect', 'enabled', 'yes' );
			}

			// force update wizard data.
			$this->update_data( 'charge', 'yes' );
			$this->update_data( 'charge_method', 'stripe' );

			learndash_safe_redirect( admin_url( 'admin.php?page=' . self::HANDLE ) );
		}

		/**
		 * Check when we need to show the wizard and set an option for that.
		 */
		public function wp_shutdown() {
			$learndash_activated = defined( 'LEARNDASH_ACTIVATED' ) && LEARNDASH_ACTIVATED;
			if ( ! $learndash_activated || ! $this->should_display() ) {
				return;
			}

			update_option( 'learndash_setup_wizard_redirect', true );
		}

		/**
		 * Redirect to the setup wizard after an activation.
		 */
		public function redirect_after_activation() {
			$should_redirect = get_option( 'learndash_setup_wizard_redirect', false );
			if ( ! $should_redirect ) {
				return;
			}

			delete_option( 'learndash_setup_wizard_redirect' );
			learndash_safe_redirect( admin_url( 'admin.php?page=' . self::HANDLE ) );
		}

		/**
		 * Dismiss
		 */
		public function dismiss() {
			if ( ! isset( $_GET['page'] ) || ! isset( $_GET['dismiss'] ) || ! isset( $_GET['nonce'] ) ) {
				return;
			}

			if ( self::HANDLE !== sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
				return;
			}

			$nonce = sanitize_text_field( wp_unslash( $_GET['nonce'] ) );

			if ( ! wp_verify_nonce( $nonce, 'ld_setup_wizard_dismiss' ) ) {
				return;
			}

			update_option( self::STATUS_KEY, self::STATUS_CLOSED );

			learndash_safe_redirect( admin_url() );
		}

		/**
		 * Ajax endpoint for finalize the setup.
		 */
		public function finalize_setup() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( empty( $nonce ) ) {
				wp_send_json_error();
			}

			if ( ! wp_verify_nonce( $nonce, 'ld_setup_wizard_finalize' ) ) {
				wp_send_json_error();
			}

			$data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$done = false;

			switch ( $data['step'] ) {
				case 'create_registration_pages':
					// enable anyone can register option and create registration pages.
					update_option( 'users_can_register', true );
					$this->create_registration_pages();
					break;
				case 'process_course_listing':
					// download and setup course grid, create course listing page.
					if ( 'multiple' === $data['courses_amount'] ) {
						$this->create_courses_listing_page();
					}
					// install course grid plugin.
					if ( 'true' === $data['course_grid'] ) {
						$ret = $this->maybe_install_a_plugin( self::COURSE_GRID_SLUG );
						if ( true === $ret ) {
							activate_plugin( self::COURSE_GRID_SLUG );
						}
					}
					break;
				case 'process_certificate_builder':
					// install certificate builder plugin.
					if ( 'true' === $data['certificate_builder'] ) {
						$ret = $this->maybe_install_a_plugin( self::CERTIFICATE_BUILDER_SLUG );
						if ( true === $ret ) {
							activate_plugin( self::CERTIFICATE_BUILDER_SLUG );
						}
					}
					break;
				case 'process_woo':
					// install woocommerce plugin and learndash add-on.
					if ( 'true' === $data['woocommerce'] ) {
						$ret = $this->maybe_install_a_plugin( self::WOOCOMMERCE_SLUG );
						if ( true === $ret ) {
							activate_plugin( self::WOOCOMMERCE_SLUG );
						}
						$ret = $this->maybe_install_a_plugin( self::LEARNDASH_WOOCOMMERCE_SLUG );
						if ( true === $ret ) {
							activate_plugin( self::LEARNDASH_WOOCOMMERCE_SLUG );
						}
					}
					break;
				case 'update_settings':
					// user from email address.
					$from_email = 'yes' === $data['use_registered_email'] ? $data['email'] : filter_var( $data['notification_email'], FILTER_VALIDATE_EMAIL );
					LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Emails_Sender_Settings', 'from_email', $from_email );

					// group access and management.
					if ( is_array( $data['course_type'] ) && in_array( 'group_courses', $data['course_type'], true ) ) {
						// optional group settings.
						LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Groups_CPT', 'public', isset( $data['public_group'] ) && $data['public_group'] ? 'yes' : '' );
						LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Groups_CPT', 'has_archive', isset( $data['group_archive_page'] ) && $data['group_archive_page'] ? 'yes' : '' );
						LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Groups_Group_Leader_User', 'manage_courses_enabled', isset( $data['manage_user'] ) && $data['manage_user'] ? 'yes' : '' );
						LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Groups_Group_Leader_User', 'groups_autoenroll_managed', isset( $data['group_auto_enroll'] ) && $data['group_auto_enroll'] ? 'yes' : '' );
						LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Groups_Group_Leader_User', 'courses_autoenroll', isset( $data['course_auto_enroll'] ) && $data['course_auto_enroll'] ? 'yes' : '' );
						LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Groups_Group_Leader_User', 'bypass_course_limits', isset( $data['bypass_course_limit'] ) && $data['bypass_course_limit'] ? 'yes' : '' );
					}

					update_option( self::STATUS_KEY, self::STATUS_COMPLETED );
					$done = true;
					break;
			}

			// preparing return data.
			$result_data = array(
				'completed' => $done,
			);
			if ( $done ) {
				$result_data['redirect'] = admin_url( self::FINAL_ADMIN_REDIRECT_PAGE );
			}

			wp_send_json_success( $result_data );
		}

		/**
		 * Install a plugin
		 *
		 * @param string $slug The plugin slug.
		 *
		 * @return array|bool|object|WP_Error
		 */
		protected function maybe_install_a_plugin( string $slug ) {
			$plugins = get_plugins();

			if ( isset( $plugins[ $slug ] ) && is_plugin_inactive( $slug ) ) {
				// this plugin is installed but not activate, do it.
				activate_plugin( $slug );
				return true;
			}

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}

			$slug = dirname( $slug );
			$api  = plugins_api(
				'plugin_information',
				array(
					'slug' => $slug,
				)
			);

			if ( is_wp_error( $api ) ) {
				return $api;
			}

			$status = install_plugin_install_status( $api );

			if ( 'install' === $status['status'] ) {
				// install it.
				return $this->install( $slug );
			}

			return false;
		}

		/**
		 * Install a plugin
		 *
		 * @param string $slug Plugin slug.
		 * @return bool|WP_Error
		 */
		public function install( string $slug ) {
			// prepare for install.
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			include_once ABSPATH . 'wp-admin/includes/theme-install.php';
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			include_once ABSPATH . 'wp-admin/includes/file.php';

			$skin = new \WP_Ajax_Upgrader_Skin();

			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => sanitize_key( $slug ),
					'fields' => array( 'sections' => false ),
				)
			);

			if ( is_wp_error( $api ) ) {
				return false;
			}

			$upgrade_er = new \Plugin_Upgrader( $skin );
			$result     = $upgrade_er->install( $api->download_link );

			if ( true === $result ) {
				return true;
			}

			return new \WP_Error( 'err', implode( PHP_EOL, $upgrade_er->skin->get_upgrade_messages() ) );
		}

		/**
		 * Create courses listing page
		 *
		 * @return void
		 */
		protected function create_courses_listing_page() {
			wp_insert_post(
				array(
					'post_title'   => __( 'Courses', 'learndash' ),
					'post_content' => '<!-- wp:learndash/ld-course-list /-->',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				)
			);
		}

		/**
		 * Creating registration page and registration success page.
		 */
		protected function create_registration_pages() {
			$settings = LearnDash_Settings_Section::get_section_settings_all( 'LearnDash_Settings_Section_Registration_Pages' );

			$post_status = isset( $settings['registration'] ) && ! empty( $settings['registration'] ) ? get_post_status( $settings['registration'] ) : '';
			if ( 'publish' !== $post_status ) {
				$settings['registration'] = wp_insert_post(
					array(
						'post_title'   => __( 'Registration', 'learndash' ),
						'post_content' => '<!-- wp:learndash/ld-registration /-->',
						'post_status'  => 'publish',
						'post_type'    => 'page',
					)
				);
				LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Registration_Pages', 'registration', $settings['registration'] );
			}

			$post_status = isset( $settings['registration_success'] ) && ! empty( $settings['registration_success'] ) ? get_post_status( $settings['registration_success'] ) : '';
			if ( 'publish' !== $post_status ) {
				$settings['registration_success'] = wp_insert_post(
					array(
						'post_title'   => __( 'Registration Success', 'learndash' ),
						'post_content' => '<!-- wp:paragraph -->' .
															'<p>' . __( 'Welcome', 'learndash' ) . '</p>' .
															'<!-- /wp:paragraph -->',
						'post_status'  => 'publish',
						'post_type'    => 'page',
					)
				);
			}
			LearnDash_Settings_Section::set_section_setting( 'LearnDash_Settings_Section_Registration_Pages', 'registration_success', $settings['registration_success'] );
		}

		/**
		 * An ajax endpoint for saving wizard data. When the
		 * user mve to next step, we will store the current state in the db.
		 */
		public function save_data() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'ld_setup_wizard_save_data' ) ) {
				wp_send_json_error();
			}

			$data = isset( $_POST['data'] )
				? $this->sanitize_text_fields( wp_unslash( $_POST['data'] ) ) // phpcs:ignore
				: array();

			foreach ( $data as $key => $val ) {
				$this->update_data( $key, $val );
			}

			update_option( self::STATUS_KEY, self::STATUS_ONGOING );
		}

		/**
		 * Ajax endpoint, for trigger a request to validate the license key.
		 */
		public function verify_license() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'ld_setup_wizard_verify_license' ) ) {
				wp_send_json_error();
			}

			$email       = isset( $_POST['email'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['email'] ) ) ) : '';
			$license_key = isset( $_POST['license_key'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['license_key'] ) ) ) : '';

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) || empty( $license_key ) ) {
				wp_send_json_error();
			}

			update_option( self::LICENSE_KEY, $license_key );
			update_option( self::LICENSE_EMAIL_KEY, $email );

			$license_status = false;

			$updater_sfwd_lms = learndash_get_updater_instance( true );
			if ( ( $updater_sfwd_lms ) && ( is_a( $updater_sfwd_lms, 'nss_plugin_updater_sfwd_lms' ) ) ) {
				/**
				 * Remove the time to check timestamp. Within the getRemote_license() method
				 * is calls time_to_recheck_license() which uses this option to determine if
				 * the license needs to be checked again.
				 */
				delete_option( 'nss_plugin_check_sfwd_lms' );

				$license_status = $updater_sfwd_lms->getRemote_license();
			}

			// The return from getRemote_license() is literally "1" (string) for valid. Anything else is invalid.
			if ( '1' !== $license_status ) {
				wp_send_json_error();
			}

			// Store the data.
			$this->update_data( 'step', 2 );
			update_option( self::STATUS_KEY, self::STATUS_ONGOING );

			wp_send_json_success();
		}

		/**
		 *
		 * Update the wizard data.
		 *
		 * @param string $key Data key.
		 * @param mixed  $value Data value.
		 * @param string $option_name Option name. Default: 'learndash_setup_wizard'.
		 */
		private function update_data( string $key, $value, string $option_name = self::DATA_KEY ): void {
			$data = get_option( $option_name, array() );

			if ( ! is_array( $data ) ) {
				$data = array();
			}

			$data[ $key ] = $value;

			update_option( $option_name, $data );
		}

		/**
		 * Register the script
		 */
		public function enqueue_scripts() {
			$screen = get_current_screen();
			if ( is_object( $screen ) && 'toplevel_page_learndash-setup-wizard' === $screen->id ) {
				wp_register_style(
					self::HANDLE,
					LEARNDASH_LMS_PLUGIN_URL . 'assets/js/setup-wizard/dist/css/style.css',
					array(),
					constant( 'SCRIPT_DEBUG' ) === true ? time() : LEARNDASH_VERSION
				);
				wp_enqueue_style( self::HANDLE );

				wp_register_script(
					self::HANDLE,
					LEARNDASH_LMS_PLUGIN_URL . 'assets/js/setup-wizard/dist/js/index.js',
					array( 'react', 'react-dom', 'wp-i18n' ),
					constant( 'SCRIPT_DEBUG' ) === true ? time() : LEARNDASH_VERSION,
					true
				);
				$data = get_option( self::DATA_KEY );

				wp_localize_script(
					self::HANDLE,
					'ldSetupWizard',
					array(
						'urls'    => array(
							'assets'         => LEARNDASH_LMS_PLUGIN_URL . 'assets/js/setup-wizard/dist/',
							'dismiss'        => admin_url(
								wp_sprintf(
									'admin.php?page=%s&dismiss=true&nonce=%s',
									self::HANDLE,
									wp_create_nonce( 'ld_setup_wizard_dismiss' )
								)
							),
							'support'        => 'https://support.learndash.com',
							'stripe_connect' => LearnDash_Settings_Section_Stripe_Connect::generate_connect_url( admin_url( 'admin-post.php?action=stripe_connect_wizard_process' ) ),
						),
						'nonces'  => array(
							'verify'   => wp_create_nonce( 'ld_setup_wizard_verify_license' ),
							'save'     => wp_create_nonce( 'ld_setup_wizard_save_data' ),
							'finalize' => wp_create_nonce( 'ld_setup_wizard_finalize' ),
						),
						'data'    => array(
							'scene'                 => $data['scene'] ?? 'step-1',
							'email'                 => $data['email'] ?? get_option( self::LICENSE_EMAIL_KEY, '' ),
							'license_key'           => $data['license_key'] ?? get_option( self::LICENSE_KEY, '' ),
							'use_registered_email'  => $data['use_registered_email'] ?? 'yes',
							'notification_email'    => $data['notification_email'] ?? '',
							'license_validated'     => $data['license_validated'] ?? 'no',
							'courses_amount'        => $data['courses_amount'] ?? 'single',
							'course_type'           => $data['course_type'] ?? array(),
							'group_access'          => $data['group_access'] ?? 'no',
							'group_leader'          => $data['group_leader'] ?? 'no',
							'charge'                => $data['charge'] ?? 'no',
							'charge_method'         => $data['charge_method'] ?? '',
							'stripe_connected'      => LearnDash_Settings_Section_Stripe_Connect::is_stripe_connected(),
							'stripe_webhook_notice' => wp_kses_post( LearnDash_Settings_Section_Stripe_Connect::get_stripe_webhook_notice() ),
						),
						'plugins' => array(
							'certificate_builder' => is_plugin_active( self::CERTIFICATE_BUILDER_SLUG ),
							'course_grid'         => is_plugin_active( self::COURSE_GRID_SLUG ),
							'woocommerce'         => is_plugin_active( self::WOOCOMMERCE_SLUG ),
						),
					)
				);

				wp_enqueue_script( self::HANDLE );
			}
		}

		/**
		 * Output the html root for react app.
		 */
		public function render() {
			?>
			<div id="learndash-setup-wizard"></div>
			<?php
		}

		/**
		 * Register the admin page to the tree.
		 */
		public function register_menu() {
			add_menu_page(
				__( 'Setup Wizard', 'learndash' ),
				__( 'Setup Wizard', 'learndash' ),
				LEARNDASH_ADMIN_CAPABILITY_CHECK,
				self::HANDLE,
				array( $this, 'render' )
			);

			// Hide the admin menu item, the page stays available.
			remove_menu_page( self::HANDLE );
		}

		/**
		 * If there is no license key stored in the database, then we should show up the wizard
		 *
		 * @return bool
		 */
		protected function should_display(): bool {
			$wizard_status = get_option( self::STATUS_KEY );

			if ( self::STATUS_CLOSED === $wizard_status || self::STATUS_COMPLETED === $wizard_status ) {
				return false;
			}

			// The wizard is in progress, but closed by an accident or something like that.
			if ( self::STATUS_ONGOING === $wizard_status ) {
				return true;
			}

			if (
				empty( get_option( self::LICENSE_KEY ) ) ||
				empty( get_option( self::LICENSE_EMAIL_KEY ) )
			) {
				return true;
			}

			// closed by default.
			return false;
		}

		/**
		 * Sanitize an array recursively.
		 *
		 * @param array $array Array.
		 *
		 * @return array
		 */
		private function sanitize_text_fields( array $array ): array {
			foreach ( $array as &$value ) {
				$value = is_array( $value )
					? $this->sanitize_text_fields( $value )
					: sanitize_text_field( $value );
			}

			return $array;
		}
	}

	new LearnDash_Setup_Wizard();
}