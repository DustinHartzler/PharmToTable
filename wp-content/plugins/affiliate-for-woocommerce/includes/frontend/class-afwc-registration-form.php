<?php
/**
 * Main class for Affiliates Registration
 *
 * @package     affiliate-for-woocommerce/includes/frontend/
 * @since       1.8.0
 * @version     1.4.3
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
			add_action( 'wp_ajax_afwc_register_user', array( $this, 'register_user' ) );
			add_action( 'wp_ajax_nopriv_afwc_register_user', array( $this, 'register_user' ) );
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

			// Return if block editor/Gutenberg to prevent printing response messages on the admin side.
			$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : '';
			if ( is_admin() && ! empty( $current_screen ) && $current_screen->is_block_editor() ) {
				return;
			}

			ob_start();

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_enqueue_style( 'afwc-reg-form-style', AFWC_PLUGIN_URL . '/assets/css/afwc-reg-form.css', array(), $plugin_data['Version'] );
			wp_enqueue_script( 'afwc-reg-form-js', AFWC_PLUGIN_URL . '/assets/js/afwc-reg-form.js', array( 'jquery', 'wp-i18n' ), $plugin_data['Version'], true );
			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'afwc-reg-form-js', 'affiliate-for-woocommerce', AFWC_PLUGIN_DIR_PATH . 'languages' );
			}

			wp_localize_script( 'afwc-reg-form-js', 'afwcRegistrationFormParams', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

			$afwc_reg_form_html = '';
			$afwc_user_values   = array();
			$is_affiliate       = '';

			$user = wp_get_current_user();
			if ( is_object( $user ) && ! empty( $user->ID ) ) {
				$afwc_user_values['afwc_reg_email'] = ! empty( $user->user_email ) ? $user->user_email : '';
				$is_affiliate                       = afwc_is_user_affiliate( $user );
			}
			if ( 'yes' === $is_affiliate ) {
				$endpoint            = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
				$my_account_afwc_url = wc_get_endpoint_url( $endpoint, '', wc_get_page_permalink( 'myaccount' ) );
				$msg                 = sprintf(
					/* translators: Link for affiliate's dashboard */
					esc_html__( 'You are already registered as our affiliate. Please %s to access your affiliate dashboard.', 'affiliate-for-woocommerce' ),
					'<a href="' . esc_attr( $my_account_afwc_url ) . '">' . esc_html__( 'click here', 'affiliate-for-woocommerce' ) . '</a>'
				);
				$afwc_reg_form_html = '<div class="afwc-reg-form-msg">' . wp_kses_post( $msg ) . '</div>';
			} elseif ( 'no' === $is_affiliate ) {
				$afwc_admin_contact_email = get_option( 'afwc_contact_admin_email_address', '' );
				if ( ! empty( $afwc_admin_contact_email ) ) {
					$msg = sprintf(
						/* translators: mailto link to contact affiliate manager */
						esc_html__( 'Your previous request to join our affiliate program has been declined. Please %s for more details.', 'affiliate-for-woocommerce' ),
						'<a target="_blank" href="mailto:' . esc_attr( $afwc_admin_contact_email ) . '">' . esc_html__( 'email affiliate manager', 'affiliate-for-woocommerce' ) . '</a>'
					);
				} else {
					$msg = esc_html__( 'Your previous request to join our affiliate program has been declined. Please contact the store admin for more details.', 'affiliate-for-woocommerce' );
				}
				$afwc_reg_form_html = '<div class="afwc-reg-form-msg">' . wp_kses_post( $msg ) . '</div>';
			} elseif ( 'pending' === $is_affiliate ) {
				$afwc_reg_form_html = '<div class="afwc-reg-form-msg">' . esc_html__( 'Your request is in moderation.', 'affiliate-for-woocommerce' ) . '</div>';
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
				$nonce               = wp_create_nonce( 'afwc-register-affiliate' );
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

			ob_get_clean();
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
			$readonly   = '';
			$value      = '';
			$user       = wp_get_current_user();
			if ( $user instanceof WP_User && ! empty( $user->ID ) ) {
				$affiliate_registration = AFWC_Registration_Submissions::get_instance();

				$field_key = str_replace( 'reg_', '', $id ); // TODO: code can be removed after DB migration to update the field id.

				$readonly = ! empty( $affiliate_registration->readonly_fields ) && is_array( $affiliate_registration->readonly_fields ) && in_array( $field_key, $affiliate_registration->readonly_fields, true ) ? 'readonly' : '';
				$value    = ! empty( $field['value'] ) ? $field['value'] : '';
				if ( ! empty( $affiliate_registration->hide_fields ) && is_array( $affiliate_registration->hide_fields ) && in_array( $field_key, $affiliate_registration->hide_fields, true ) ) {
					$class        .= ' afwc_hide_form_field';
					$required      = '';
					$field['type'] = 'hidden';
				}
			}

			$class .= ( ! $show && empty( $required ) && ! strpos( $class, 'afwc_hide_form_field' ) ) ? ' afwc_hide_form_field' : '';
			switch ( $field['type'] ) {
				case 'text':
				case 'email':
				case 'password':
				case 'tel':
				case 'checkbox':
				case 'hidden':
					$field_html = sprintf( '<input type="%1$s" id="%2$s" name="%2$s" %3$s class="afwc_reg_form_field" %4$s value="%5$s"/>', $field['type'], $id, $required, $readonly, $value );
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
		 * Function to register affiliate user.
		 */
		public function register_user() {

			check_ajax_referer( 'afwc-register-affiliate', 'security' );

			$response = array();
			$userdata = array();

			$params = array_map(
				function ( $request_param ) {
					return wc_clean( wp_unslash( $request_param ) );
				},
				$_REQUEST
			);

			// Honeypot validation.
			$hp_key = 'afwc_hp_email';
			if ( ! isset( $params[ $hp_key ] ) || ! empty( $params[ $hp_key ] ) ) {
				wp_send_json(
					array(
						'status'  => 'success',
						'message' => _x( 'You are successfully registered.', 'affiliate registration message', 'affiliate-for-woocommerce' ),
					)
				);
			}

			$saving_fields = array( 'afwc_reg_email', 'afwc_reg_first_name', 'afwc_reg_last_name', 'afwc_reg_contact', 'afwc_reg_website', 'afwc_reg_password', 'afwc_reg_desc' );

			$additional_fields_title = array(
				'afwc_reg_contact' => esc_html_x( 'Way to contact', 'label for registration contact field', 'affiliate-for-woocommerce' ),
				'afwc_reg_desc'    => esc_html_x( 'About affiliate', 'label for registration description field', 'affiliate-for-woocommerce' ),
			);
			// Normalize form data.
			$fields = array();
			foreach ( $params as $id => $field ) {

				if ( ! in_array( $id, $saving_fields, true ) ) {
					continue;
				}

				$fields[] = array(
					'key'   => str_replace( 'reg_', '', $id ),
					'value' => $field,
					'label' => ! empty( $additional_fields_title[ $id ] ) ? $additional_fields_title[ $id ] : '',
				);
			}

			$affiliate_registration = AFWC_Registration_Submissions::get_instance();
			$response               = is_callable( array( $affiliate_registration, 'register_user' ) ) ? $affiliate_registration->register_user( $fields ) : array();

			wp_send_json(
				array(
					'status'         => ! empty( $response['status'] ) ? $response['status'] : 'error',
					'invalidFieldId' => ! empty( $response['invalid_field_id'] ) ? $response['invalid_field_id'] : '',
					'message'        => ! empty( $response['message'] ) ? $response['message'] : _x( 'Something went wrong.', 'affiliate registration message', 'affiliate-for-woocommerce' ),
				)
			);

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
				jQuery(document).on('submit', '#afwc-form-settings', async function(e) {
					e.preventDefault();
					jQuery('.afwc-form-save-loader').show();
					let form = jQuery(this);
					let formData = {};
					form.find('input[type="submit"]').attr('disabled', true);
					jQuery.each(form.serializeArray(), function() {
						formData[this.name] = this.value;
					});
					formData['action'] = 'afwc_modify_form_fields';
					formData['security'] = '<?php echo esc_js( wp_create_nonce( 'afwc-modify-form-fields' ) ); ?>'
					let responseMsg = '';
					await jQuery.ajax({
						type: 'POST',
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						data: formData,
						dataType: 'json',
						success: function(response) {
							responseMsg = '<?php esc_attr_e( 'Settings saved successfully.', 'affiliate-for-woocommerce' ); ?>';
							jQuery('.afwc-form-save-msg').css('color', '#008000')
						},
						error: function (){
							responseMsg = '<?php esc_attr_e( 'Something went wrong. Please try again after some time.', 'affiliate-for-woocommerce' ); ?>';
							jQuery('.afwc-form-save-msg').css('color', '#d60f00');
						}
					});
					if(responseMsg){
						jQuery('.afwc-form-save-msg').text(responseMsg).show();
					}
					form.find('input[type="submit"]').attr('disabled', false);
					jQuery('.afwc-form-save-loader').hide();
					setTimeout(function () {
						jQuery('.afwc-form-save-msg').hide();
					}, 5000);
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
