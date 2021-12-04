<?php
/**
 * Main class for Affiliates Registration
 *
 * @package     affiliate-for-woocommerce/includes/frontend/
 * @version     1.2.5
 */

use Pelago\Emogrifier;

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
		 * Form fields
		 *
		 * @var $form_fields
		 */
		public $form_fields;

		/**
		 * Constructor
		 */
		private function __construct() {
			add_shortcode( 'afwc_registration_form', array( $this, 'render_registration_form' ) );
			add_action( 'wp_ajax_afwc_register_user', array( $this, 'request_handler' ) );
			add_action( 'wp_ajax_nopriv_afwc_register_user', array( $this, 'request_handler' ) );

			$this->hide_fields      = array( 'afwc_reg_first_name', 'afwc_reg_last_name', 'afwc_reg_password', 'afwc_reg_confirm_password' );
			$this->read_only_fields = array( 'afwc_reg_email' );

			add_filter( 'wp_ajax_afwc_modify_form_fields', array( $this, 'afwc_modify_form_fields' ) );
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

				// Registration form fields filter.
				$this->form_fields = get_option( 'afwc_form_fields', true );
				// fill up values.
				foreach ( $this->form_fields as $key => $field ) {
					if ( ! empty( $afwc_user_values[ $key ] ) ) {
						$this->form_fields[ $key ]['value'] = $afwc_user_values[ $key ];
					}
				}

				$afwc_reg_form_html = '<div class="afwc_reg_form_wrapper"><form action="#" id="afwc_registration_form">';
				// render fields.
				foreach ( $this->form_fields as $id => $field ) {
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
			$show       = ! empty( $field['show'] ) ? $field['show'] : '';
			$read_only  = '';
			$value      = '';
			$user       = wp_get_current_user();
			if ( is_object( $user ) && ! empty( $user->ID ) ) {
				$read_only = in_array( $id, $this->read_only_fields, true ) ? 'readonly' : '';
				$class    .= ( ( in_array( $id, $this->hide_fields, true ) && ! is_preview() ) || ( ! $show && empty( $required ) ) ) ? ' afwc_hide_form_field' : '';
				$value     = ! empty( $field['value'] ) ? $field['value'] : '';
			}

			$class .= ( ! $show && empty( $required ) && ! strpos( $class, 'afwc_hide_form_field' ) ) ? ' afwc_hide_form_field' : '';
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
				$field_html = '<div class="afwc_reg_field_wrapper ' . $id . ' ' . $class . '"><label for="' . $id . '" class="afwc_' . $field['required'] . '">' . $field_html . wp_kses_post( $field['label'] ) . '</label></div>';
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
					$parent_affiliate_id = afwc_get_referrer_id();
					if ( ! empty( $parent_affiliate_id ) ) {
						// check if parent affiliate is valid.
						$is_parent_affiliate = afwc_is_user_affiliate( absint( $parent_affiliate_id ) );
						if ( 'yes' === $is_parent_affiliate ) {
							$parent_chain     = get_user_meta( $parent_affiliate_id, 'afwc_parent_chain', true );
							$new_parent_chain = ( ! empty( $parent_chain ) ) ? $parent_affiliate_id . '|' . $parent_chain : $parent_affiliate_id . '|';
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

		/**
		 * Function to modify form fields
		 */
		public function afwc_modify_form_fields() {
			check_ajax_referer( 'afwc-modify-form-fields', 'security' );
			// format ajax input and save in the DB.
			$form_fields = get_option( 'afwc_form_fields', true );
			$form_fields = array_map(
				function ( $form_fields ) {
					if ( empty( $form_fields['required'] ) ) {
						$form_fields['show'] = false;
					}
					return $form_fields;
				},
				$form_fields
			);
			$params      = array_map(
				function ( $request_param ) {
					return wp_unslash( $request_param );
				},
				$_REQUEST
			);
			foreach ( $params as $key => $param ) {
				if ( strpos( $key, '_label' ) ) {
					$id = str_replace( '_label', '', $key );
					if ( ! empty( $form_fields[ $id ] ) ) {
						$form_fields[ $id ]['label'] = $param;
					}
				} elseif ( strpos( $key, '_show' ) ) {
					$id = str_replace( '_show', '', $key );
					if ( ! empty( $form_fields[ $id ] ) ) {
						$form_fields[ $id ]['show'] = true;
					}
				}
			}
			update_option( 'afwc_form_fields', $form_fields, 'no' );
		}

		/**
		 * Function to render for settings
		 */
		public static function reg_form_settings() {
			$form_fields = get_option( 'afwc_form_fields', true );
			// loader.
			$loader_image = WC()->plugin_url() . '/assets/images/wpspin-2x.gif';
			?>
			<script type="text/javascript">
				jQuery(document).on('submit', '#afwc-form-settings', function(e) {
					e.preventDefault();
					jQuery('.afwc-form-save-loader').show();
					var form = jQuery(this);
					jQuery(form).find('input[type="submit"]').attr('disabled', true);
					var formData = {};
					jQuery.each(form.serializeArray(), function() {
						formData[this.name] = this.value;
					});
					formData['action'] = 'afwc_modify_form_fields';
					formData['security'] = '<?php echo esc_js( wp_create_nonce( 'afwc-modify-form-fields' ) ); ?>'
					jQuery.ajax({
						type: 'POST',
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						data: formData,
						dataType: 'json',
						success: function(response) {
							jQuery('.afwc-form-save-loader').hide();
							jQuery(form).find('input[type="submit"]').attr('disabled', false);
							jQuery('.afwc-form-save-msg').text('<?php esc_attr_e( 'Settings saved sucessfully.', 'affiliate-for-woocommerce' ); ?>').css('color', '#008000').show();
							setTimeout(function () {
								jQuery('.afwc-form-save-msg').hide();
							}, 5000);
						},
						error: function (){
							jQuery('.afwc-form-save-loader').hide();
							jQuery('.afwc-form-save-msg').text('<?php esc_attr_e( 'Something went wrong. Please try again after some time.', 'affiliate-for-woocommerce' ); ?>').css('color', '#d60f00').show();
							setTimeout(function () {
								jQuery('.afwc-form-save-msg').hide();
							}, 5000);
						}
					});
				});
			</script>
			<style type="text/css">
				#afwc-form-settings table{
					width: 60%;
				}
				#afwc-form-settings th{
					text-align: left;
				}
				#afwc-form-settings .afwc_first_col{
					width: 10%;
				}
				#afwc-form-settings .afwc_second_col input, #afwc-form-settings .afwc_second_col textarea{
					width: 50%;
				}
				.afwc-form-save-loader, .afwc-form-save-msg{
					display: none;
				}
				.afwc-form-save-msg{
					padding: 0.5em;
					font-size: 1.1em;
					font-weight: bold;
				}
			</style>
			<form id="afwc-form-settings">
				<h3><?php esc_attr_e( 'Affiliate registration form settings', 'affiliate-for-woocommerce' ); ?></h3>
				<table> 
				<tr>
					<th><label><?php esc_attr_e( 'Show', 'affiliate-for-woocommerce' ); ?></label></th>
					<th><label><?php esc_attr_e( 'Label', 'affiliate-for-woocommerce' ); ?></label></th>
				</tr>
				<?php
				foreach ( $form_fields as $id => $field ) {
					$required = ( ! empty( $field['required'] ) && 'required' === $field['required'] ) ? 'disabled' : '';
					$show     = ( ! empty( $field['show'] ) && $field['show'] ) ? 'checked' : '';
					?>
					<tr>
						<td class="afwc_first_col"><input type="checkbox" name="<?php echo esc_attr( $id ) . '_show'; ?>" <?php echo esc_attr( $required ); ?> <?php echo esc_attr( $show ); ?>/></td>
						<td class="afwc_second_col">
						<?php if ( 'afwc_reg_terms' === $id ) { ?>
						<textarea name="<?php echo esc_attr( $id ) . '_label'; ?>"><?php echo esc_attr( $field['label'] ); ?></textarea>
							<?php
						} else {
							?>
						<input type="text" name="<?php echo esc_attr( $id ) . '_label'; ?>" value="<?php echo esc_attr( $field['label'] ); ?>"/>
						<?php } ?>
						</td>
					</tr>
					<?php
				}
				?>
				<tr><td><input type="submit" class="button-primary" value="<?php echo esc_attr__( 'Save', 'affiliate-for-woocommerce' ); ?>"/></td></tr>
				</table>
				<div class="afwc-form-save-loader"><img src="<?php echo esc_url( $loader_image ); ?>"></div>
				<div class="afwc-form-save-msg"></div>
			</form>
			<?php
		}


	}

}

AFWC_Registration_Form::get_instance();
