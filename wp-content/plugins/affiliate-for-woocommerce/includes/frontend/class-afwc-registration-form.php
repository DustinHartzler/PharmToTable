<?php
/**
 * Main class for Affiliates Registration
 *
 * @package     affiliate-for-woocommerce/includes/frontend/
 * @version     1.2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Registration_Form' ) ) {

	/**
	 * Main class for Affiliates Registration
	 */
	class AFWC_Registration_Form {

		/**
		 * Variable to hold instance of AFWC_Registration_Form
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Hide fields
		 *
		 * @var $hide_fields
		 */
		public $hide_fields;

		/**
		 * Read only fields
		 *
		 * @var $read_only_fields
		 */
		public $read_only_fields;

		/**
		 * Constructor
		 */
		private function __construct() {

			add_shortcode( 'afwc_registration_form', array( $this, 'render_registration_form' ) );
			add_action( 'wp_ajax_afwc_register_user', array( $this, 'request_handler' ) );
			add_action( 'wp_ajax_nopriv_afwc_register_user', array( $this, 'request_handler' ) );

			$this->hide_fields      = array( 'afwc_reg_first_name', 'afwc_reg_last_name', 'afwc_reg_password', 'afwc_reg_confirm_password' );
			$this->read_only_fields = array( 'afwc_reg_email' );
		}

		/**
		 * Get single instance of AFWC_Registration_Form
		 *
		 * @return AFWC_Registration_Form Singleton object of AFWC_Registration_Form
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Render AFWC_Registration_Form
		 *
		 * @param mixed $atts Form attributes.
		 * @return string $afwc_reg_form_html
		 */
		public function render_registration_form( $atts ) {

			$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : '';
			if ( is_admin() && ! empty( $current_screen ) && $current_screen->is_block_editor ) {
				return;
			}

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_enqueue_style( 'afwc-reg-form-style', AFWC_PLUGIN_URL . '/assets/css/afwc-reg-form.css', array(), $plugin_data['Version'] );
			wp_enqueue_script( 'afwc-reg-form-js', AFWC_PLUGIN_URL . '/assets/js/afwc-reg-form.js', array( 'jquery' ), $plugin_data['Version'], true );
			$afwc_reg_pre_data['ajaxurl']        = admin_url( 'admin-ajax.php' );
			$afwc_reg_pre_data['hp_success_msg'] = __( 'User registration successfull', 'affiliate-for-woocommerce' );
			$afwc_reg_pre_data['password_error'] = __( 'Password does not match', 'affiliate-for-woocommerce' );
			$afwc_reg_pre_data['invalid_url']    = __( 'Please add a valid URL', 'affiliate-for-woocommerce' );
			wp_localize_script( 'afwc-reg-form-js', 'afwc_reg_pre_data', $afwc_reg_pre_data );
			$afwc_reg_form_html = '';
			$user               = wp_get_current_user();
			$afwc_user_values   = array();
			$is_affiliate       = '';
			if ( is_object( $user ) && ! empty( $user->ID ) ) {
				$afwc_user_values['afwc_reg_email']            = ! empty( $user->user_email ) ? $user->user_email : '';
				$afwc_user_values['afwc_reg_first_name']       = ! empty( $user->first_name ) ? $user->first_name : '';
				$afwc_user_values['afwc_reg_last_name']        = ! empty( $user->last_name ) ? $user->last_name : '';
				$afwc_user_values['afwc_reg_password']         = ! empty( $user->user_pass ) ? $user->user_pass : '';
				$afwc_user_values['afwc_reg_confirm_password'] = ! empty( $user->user_pass ) ? $user->user_pass : '';
				$is_affiliate                                  = afwc_is_user_affiliate( $user );
			}
			if ( 'yes' === $is_affiliate && ! is_preview() ) {
				// redirect to affiliate tab.
				$endpoint            = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
				$my_account_afwc_url = wc_get_endpoint_url( $endpoint, '', wc_get_page_permalink( 'myaccount' ) );
				// add query var.
				?><script><?php echo( "location.href = '" . esc_url( $my_account_afwc_url ) . "';" ); ?></script>
				<?php
			} elseif ( 'no' === $is_affiliate && ! is_preview() ) {
				$afwc_admin_contact_email = get_option( 'afwc_contact_admin_email_address', '' );
				if ( ! empty( $afwc_admin_contact_email ) ) {
					$msg = sprintf(
						/* translators: Link for mailto affiliate manager*/
						esc_html__( 'Your previous request to join our affiliate program has been declined. Please contact the %s for more details.', 'affiliate-for-woocommerce' ),
						'<a target="_blank" href="mailto:' . esc_attr( $afwc_admin_contact_email ) . '">' . esc_html__( 'store admin', 'affiliate-for-woocommerce' ) . '</a>'
					);
				} else {
					$msg = esc_html__( 'Your previous request to join our affiliate program has been declined. Please contact the store admin for more details.', 'affiliate-for-woocommerce' );

				}
				echo '<div class="afwc-reg-form-msg">' . wp_kses_post( $msg ) . '</div>';
			} elseif ( 'pending' === $is_affiliate && ! is_preview() ) {
				echo '<div class="afwc-reg-form-msg">' . esc_html__( 'Your request is in moderation.', 'affiliate-for-woocommerce' ) . '</div>';
			} else {

				$form_fields = array();
				$form_fields = array(
					'afwc_reg_email'            => array(
						'type'     => 'email',
						'required' => 'required',
						'label'    => __( 'Email', 'affiliate-for-woocommerce' ),
						'value'    => ( ! empty( $afwc_user_values['afwc_reg_email'] ) ) ? $afwc_user_values['afwc_reg_email'] : '',
					),
					'afwc_reg_first_name'       => array(
						'type'     => 'text',
						'required' => '',
						'label'    => __( 'First Name', 'affiliate-for-woocommerce' ),
						'class'    => 'afwc_is_half',
						'value'    => ( ! empty( $afwc_user_values['afwc_reg_first_name'] ) ) ? $afwc_user_values['afwc_reg_first_name'] : '',
					),
					'afwc_reg_last_name'        => array(
						'type'     => 'text',
						'required' => '',
						'label'    => __( 'Last Name', 'affiliate-for-woocommerce' ),
						'class'    => 'afwc_is_half',
						'value'    => ( ! empty( $afwc_user_values['afwc_reg_last_name'] ) ) ? $afwc_user_values['afwc_reg_last_name'] : '',
					),
					'afwc_reg_contact'          => array(
						'type'     => 'text',
						'required' => '',
						'label'    => __( 'Phone Number / Skype ID / Best method to talk to you', 'affiliate-for-woocommerce' ),
					),
					'afwc_reg_website'          => array(
						'type'     => 'text',
						'required' => '',
						'label'    => __( 'Website', 'affiliate-for-woocommerce' ),
					),
					'afwc_reg_password'         => array(
						'type'     => 'password',
						'required' => 'required',
						'label'    => __( 'Password', 'affiliate-for-woocommerce' ),
						'class'    => 'afwc_is_half',
						'value'    => ( ! empty( $afwc_user_values['afwc_reg_password'] ) ) ? $afwc_user_values['afwc_reg_password'] : '',
					),
					'afwc_reg_confirm_password' => array(
						'type'     => 'password',
						'required' => 'required',
						'label'    => __( 'Confirm Password', 'affiliate-for-woocommerce' ),
						'class'    => 'afwc_is_half',
						'value'    => ( ! empty( $afwc_user_values['afwc_reg_confirm_password'] ) ) ? $afwc_user_values['afwc_reg_confirm_password'] : '',
					),
					'afwc_reg_desc'             => array(
						'type'     => 'textarea',
						'required' => 'required',
						'label'    => __( 'Tell us more about yourself and why you\'d like to partner with us (please include your social media handles, experience promoting others, tell us about your audience etc)', 'affiliate-for-woocommerce' ),
					),
					'afwc_reg_terms'            => array(
						'type'     => 'checkbox',
						'required' => 'required',
						'label'    => __( ' I accept all the terms of this program', 'affiliate-for-woocommerce' ),
					),

				);

				$afwc_reg_form_html = '<div class="afwc_reg_form_wrapper"><form action="#" id="afwc_registration_form">';
				// render fields.
				foreach ( $form_fields as $id => $field ) {
					$afwc_reg_form_html .= $this->field_callback( $id, $field );
				}

				// nonce for security.
				$nonce               = wp_create_nonce( AFWC_AJAX_SECURITY );
				$afwc_reg_form_html .= '<input type="hidden" name="afwc_registration" id="afwc_registration" value="' . $nonce . '"/>';
				// honyepot field.
				$hp_style            = 'position:absolute;top:-99999px;' . ( is_rtl() ? 'right' : 'left' ) . ':-99999px;z-index:-99;';
				$afwc_reg_form_html .= '<label style="' . $hp_style . '"><input type="text" name="afwc_hp_email"  tabindex="-1" autocomplete="-1" value=""/></label>';
				// loader.
				$loader_image = WC()->plugin_url() . '/assets/images/wpspin-2x.gif';
				// submit button.
				$afwc_reg_form_html .= '<div class="afwc_reg_field_wrapper"><input type="submit" name="submit" class="afwc_registration_form_submit" id="afwc_registration_form_submit" value="' . __( 'Submit', 'affiliate-for-woocommerce' ) . '"/><div class="afwc_reg_loader"><img src="' . esc_url( $loader_image ) . '" /></div></div>';
				// message.
				$afwc_reg_form_html .= '<div class="afwc_reg_message"></div>';
				$afwc_reg_form_html .= '</form></div>';
			}

			return $afwc_reg_form_html;

		}

		/**
		 * Function to render field
		 *
		 * @param int   $id Form ID.
		 * @param array $field Form field.
		 * @return string $field_html
		 */
		public function field_callback( $id, $field ) {
			$field_html = '';
			$required   = ! empty( $field['required'] ) ? $field['required'] : '';
			$class      = ! empty( $field['class'] ) ? $field['class'] : '';
			$read_only  = '';
			$value      = '';
			$user       = wp_get_current_user();
			if ( is_object( $user ) && ! empty( $user->ID ) ) {
				$read_only = in_array( $id, $this->read_only_fields, true ) ? 'readonly' : '';
				$class    .= ( in_array( $id, $this->hide_fields, true ) && ! is_preview() ) ? ' afwc_hide_form_field' : '';
				$value     = ! empty( $field['value'] ) ? $field['value'] : '';
			}

			switch ( $field['type'] ) {
				case 'text':
				case 'email':
				case 'password':
				case 'tel':
				case 'checkbox':
					$field_html = sprintf( '<input type="%1$s" id="%2$s" name="%2$s" %3$s class="afwc_reg_form_field" %4$s value="%5$s"/>', $field['type'], $id, $required, $read_only, $value );
					break;
				case 'textarea':
					$field_html = sprintf( '<textarea name="%1$s" id="%1$s" %2$s size="100" rows="5" cols="58" class="afwc_reg_form_field"></textarea>', $id, $required );
					break;
				default:
					$field_html = '';
					break;
			}
			if ( 'checkbox' === $field['type'] ) {
				$field_html = '<div class="afwc_reg_field_wrapper ' . $id . ' ' . $class . '"><label for="' . $id . '" class="afwc_' . $field['required'] . '">' . $field_html . $field['label'] . '</label></div>';
			} else {
				$field_html = '<div class="afwc_reg_field_wrapper ' . $id . ' ' . $class . '"><label for="' . $id . '" class="afwc_' . $field['required'] . '">' . $field['label'] . '</label>' . $field_html . '</div>';
			}
			return $field_html;

		}

		/**
		 * Function to handle all ajax request
		 */
		public function request_handler() {

			$response = array();
			$userdata = array();

			check_ajax_referer( AFWC_AJAX_SECURITY, 'security' );

			$params = array_map(
				function ( $request_param ) {
					return trim( wc_clean( wp_unslash( $request_param ) ) );
				},
				$_REQUEST
			);

			// Honeypot validation.
			$hp_key = 'afwc_hp_email';
			if ( ! isset( $params[ $hp_key ] ) || ! empty( $params[ $hp_key ] ) ) {
				$response['status']  = 'success';
				$response['message'] = __( 'You are are successfully registered.', 'affiliate-for-woocommerce' );
			} else {
				$user = wp_get_current_user();

				$userdata['user_email'] = $params['afwc_reg_email'];
				$userdata['user_pass']  = $params['afwc_reg_password'];
				$userdata['first_name'] = $params['afwc_reg_first_name'];
				$userdata['last_name']  = $params['afwc_reg_last_name'];
				$userdata['user_url']   = $params['afwc_reg_website'];
				if ( is_object( $user ) && ! empty( $user->ID ) ) {
					$user_id = $user->ID;
				} else {
					// check if user exists with email then return with message else register user.
					if ( email_exists( $params['afwc_reg_email'] ) > 0 ) {
						$user               = get_user_by( 'email', $params['afwc_reg_email'] );
						$is_affiliate       = afwc_is_user_affiliate( $user );
						$response['status'] = 'error';
						if ( 'not_registered' !== $is_affiliate ) {
							if ( 'pending' === $is_affiliate ) {
								$response['message'] = __( 'We have already received your request and will get in touch soon.', 'affiliate-for-woocommerce' );
							} elseif ( 'no' === $is_affiliate ) {
								$afwc_admin_contact_email = get_option( 'afwc_contact_admin_email_address', '' );
								if ( ! empty( $afwc_admin_contact_email ) ) {
									$msg = sprintf(
										/* translators: Link for mailto affiliate manager*/
										esc_html__( 'Your previous request to join our affiliate program has been declined. Please contact the %s for more details.', 'affiliate-for-woocommerce' ),
										'<a target="_blank" href="mailto:' . esc_attr( $afwc_admin_contact_email ) . '">' . esc_html__( 'store admin', 'affiliate-for-woocommerce' ) . '</a>'
									);
								} else {
									$msg = esc_html__( 'Your previous request to join our affiliate program has been declined. Please contact the store admin for more details.', 'affiliate-for-woocommerce' );

								}
								$response['message'] = $msg;
							} elseif ( 'yes' === $is_affiliate ) {
								$response['message'] = __( 'You are already registered with us as an affiliate.', 'affiliate-for-woocommerce' );
							}
							echo wp_json_encode( $response );
							exit;
						}
					}

					$user_id = ! empty( $user->ID ) ? $user->ID : '';

					if ( empty( $user_id ) ) {
						$afwc = Affiliate_For_WooCommerce::get_instance();
						if ( $afwc->is_wc_gte_36() ) {
							$username = wc_create_new_customer_username(
								$params['afwc_reg_email'],
								array(
									'first_name' => $params['afwc_reg_first_name'],
									'last_name'  => $params['afwc_reg_last_name'],
								)
							);
						}
						$userdata['user_login'] = ( ! empty( $username ) ) ? $username : $params['afwc_reg_email'];
						$user_id                = wp_insert_user( $userdata );
					}
				}

				// On success.
				if ( ! is_wp_error( $user_id ) ) {
					// add meta data phone, skype, description.
					$auto_add_affiliate = get_option( 'afwc_auto_add_affiliate', 'no' );
					$affiliate_status   = ( 'yes' === $auto_add_affiliate ) ? 'yes' : 'pending';
					update_user_meta( $user_id, 'afwc_is_affiliate', $affiliate_status );
					if ( ! empty( $params['afwc_reg_contact'] ) ) {
						update_user_meta( $user_id, 'afwc_affiliate_contact', $params['afwc_reg_contact'] );
					}
					update_user_meta( $user_id, 'afwc_affiliate_desc', $params['afwc_reg_desc'] );
					// notify affiliate manager for new affiliate registration.
					if ( 'pending' === $affiliate_status ) {
						// Send email to admin for new registration request.
						$userdata['user_login'] = ! empty( $userdata['user_login'] ) ? $userdata['user_login'] : ( ! empty( $user ) ? $user->user_login : '' );
						$mailer                 = WC()->mailer();
						if ( $mailer->emails['Afwc_New_Registration_Received']->is_enabled() ) {
							// Prepare args.
							$args = array(
								'user_id'      => $user_id,
								'userdata'     => $userdata,
								'user_contact' => $params['afwc_reg_contact'],
								'user_desc'    => $params['afwc_reg_desc'],
							);
							// Trigger email.
							do_action( 'afwc_new_registration_received_email', $args );
						}
					}
					$response['status'] = 'success';
					// save parent child relation if parent cookie present.
					$parent_affiliate_id = get_referrer_id();
					if ( ! empty( $parent_affiliate_id ) ) {
						// check if parent affiliate is valid.
						$is_parent_affiliate = afwc_is_user_affiliate( absint( $parent_affiliate_id ) );
						if ( 'yes' === $is_parent_affiliate ) {
							$parent_chain     = get_user_meta( $parent_affiliate_id, 'afwc_parent_chain', true );
							$new_parent_chain = ( ! empty( $parent_chain ) ) ? $parent_affiliate_id . ',' . $parent_chain : $parent_affiliate_id;
							update_user_meta( $user_id, 'afwc_parent_chain', $new_parent_chain );
						}
					}
					if ( 'yes' === $affiliate_status ) {
						$endpoint            = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
						$my_account_afwc_url = wc_get_endpoint_url( $endpoint, 'resources', wc_get_page_permalink( 'myaccount' ) );
						$msg                 = sprintf(
							/* translators: Link to the my account page */
							esc_html__( 'Congratulations, you are successfully registered as our affiliate. %s to find more details about affiliate program.', 'affiliate-for-woocommerce' ),
							'<a target="_blank" href="' . esc_url(
								$my_account_afwc_url
							) . '">' . esc_html__( 'Visit here', 'affiliate-for-woocommerce' ) . '</a>'
						);
					} else {
						$msg = __( 'We have received your request to join our affiliate program. We will review it and will get in touch with you soon!', 'affiliate-for-woocommerce' );
					}
					$response['message'] = $msg;
				} else {
					$response['status']  = 'error';
					$response['message'] = $user_id->get_error_message();
				}
			}
			echo wp_json_encode( $response );
			exit;

		}

	}

}

AFWC_Registration_Form::get_instance();
