<?php
/**
 * Main class for Contact Form 7 (CF7) integration.
 *
 * @package     affiliate-for-woocommerce/includes/integration/contact-form-7/
 * @since       5.4.0
 * @version     1.1.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CF7_AFWC_Compatibility' ) ) {

	/**
	 * CF7_AFWC_Compatibility class.
	 */
	class CF7_AFWC_Compatibility {

		/**
		 * Variable to hold instance of CF7_AFWC_Compatibility.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		private function __construct() {
			// Add the form tags.
			add_action( 'wpcf7_init', array( $this, 'add_form_tags' ) );

			// Generate the tags for affiliate registration fields.
			add_action( 'wpcf7_admin_init', array( $this, 'add_tag_generator' ), 56, 0 );

			// Action for before sending the email to a user.
			add_action( 'wpcf7_before_send_mail', array( $this, 'before_send_mail' ), 10, 3 );

			// Register the affiliate registration response messages to CF7.
			add_filter( 'wpcf7_messages', array( $this, 'registration_response' ), 10, 1 );

			// Validation for password field.
			add_filter( 'wpcf7_validate_afwc_password', array( $this, 'password_validation' ), 10, 2 );
			add_filter( 'wpcf7_validate_afwc_password*', array( $this, 'password_validation' ), 10, 2 );

			// Submission posted data.
			add_filter( 'wpcf7_posted_data', array( $this, 'posted_data' ) );

			// Enqueue script.
			add_action( 'wpcf7_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Field validation.
			add_filter( 'wpcf7_validate', array( $this, 'skip_validation' ), 10, 2 );
		}

		/**
		 * Get single instance of CF7_AFWC_Compatibility.
		 *
		 * @return CF7_AFWC_Compatibility Singleton object of CF7_AFWC_Compatibility
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Method for Password tag validation.
		 *
		 * @param object $result The result object.
		 * @param string $tag    The tag.
		 *
		 * @return object.
		 */
		public function password_validation( $result = null, $tag = '' ) {

			$current_form = wpcf7_get_current_contact_form();

			// Validate nonce if nonce is created by the CF7 form.
			if ( $current_form instanceof WPCF7_ContactForm && is_callable( array( $current_form, 'nonce_is_active' ) ) && $current_form->nonce_is_active() && is_user_logged_in() ) {
				if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['_wpnonce'] ) ), 'wp_rest' ) ) { // phpcs:ignore 
					return $result;
				}
			}

			if ( empty( $tags ) ) {
				return $result;
			}

			$tag = new WPCF7_FormTag( $tag );

			if ( empty( $tag->name ) ) {
				return $result;
			}

			$name = $tag->name;

			$value = ! empty( $_POST[ $name ] ) ? wc_clean( wp_unslash( $_POST[ $name ] ) ) : ''; // phpcs:ignore

			if ( empty( $value ) && is_callable( array( $result, 'invalidate' ) ) && is_callable( array( $tag, 'is_required' ) ) && $tag->is_required() ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
			}

			return $result;
		}

		/**
		 * Skip validation for hidden fields for logged in user.
		 *
		 * @param object $result The result object.
		 * @param array  $tags   The array of tag.
		 *
		 * @return object.
		 */
		public function skip_validation( $result = null, $tags = array() ) {

			$current_form = wpcf7_get_current_contact_form();

			// Validate nonce if nonce is created by the CF7 form.
			if ( $current_form instanceof WPCF7_ContactForm && is_callable( array( $current_form, 'nonce_is_active' ) ) && $current_form->nonce_is_active() && is_user_logged_in() ) {
				if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['_wpnonce'] ) ), 'wp_rest' ) ) { // phpcs:ignore 
					return $result;
				}
			}

			/** Prevent the execution if
			 *   - the current form is not an affiliate registration form or
			 *   - tags are not provided.
			 *   - email is not provided.
			 *   - provided email is already registered.
			*/
			if ( empty( $tags ) || ! $this->is_afwc_form( $current_form ) || empty( $_POST['afwc_email'] ) || ! email_exists( wc_clean( wp_unslash( $_POST['afwc_email'] ) ) ) ) { // phpcs:ignore 
				return $result;
			}

			$invalid_fields = is_callable( array( $result, 'get_invalid_fields' ) ) ? $result->get_invalid_fields() : array();

			$affiliate_registration = AFWC_Registration_Submissions::get_instance();

			if ( empty( $affiliate_registration->hide_fields ) || ! is_array( $invalid_fields ) || empty( $invalid_fields ) ) {
				return $result;
			}

			$return_result = new WPCF7_Validation();

			foreach ( $invalid_fields as $invalid_field_key => $invalid_field_data ) {
				// skip validation for affiliate hidden fields.
				if ( in_array( $invalid_field_key, $affiliate_registration->hide_fields, true ) ) {
					continue;
				}

				foreach ( $tags as $tag ) {
					if ( ( ! empty( $tag['name'] ) ? $tag['name'] : '' ) === $invalid_field_key && is_callable( array( $return_result, 'invalidate' ) ) ) {
						$return_result->invalidate( $tag, ! empty( $invalid_field_data['reason'] ) ? $invalid_field_data['reason'] : '' );
					}
				}
			}

			return $return_result;
		}

		/**
		 * Register the cf7 form tags.
		 *
		 * @return void.
		 */
		public function add_form_tags() {

			wpcf7_add_form_tag(
				array( 'afwc_password', 'afwc_password*' ),
				array( $this, 'render_password_fields' ),
				array( 'name-attr' => true )
			);
		}

		/**
		 * Register the form tag generator in cf7 editor.
		 *
		 * @return void.
		 */
		public function add_tag_generator() {

			$contact_form = wpcf7_get_current_contact_form();

			if ( empty( $current_form ) || ! $current_form instanceof WPCF7_ContactForm ) {
				$post = ! empty( $_REQUEST['post'] ) ? wc_clean( wp_unslash( $_REQUEST['post'] ) ) : 0; // phpcs:ignore
				$contact_form = ( ! empty( $post ) && is_callable( array( 'WPCF7_ContactForm', 'get_instance' ) ) ) ? WPCF7_ContactForm::get_instance( $post ) : null;
			}

			if ( ! $contact_form instanceof WPCF7_ContactForm || false === $this->is_afwc_form( $contact_form ) ) {
				return;
			}

			$fields = is_callable( array( 'AFWC_Registration_Submissions', 'get_default_fields' ) ) ? AFWC_Registration_Submissions::get_default_fields() : array();

			if ( empty( $fields ) ) {
				return;
			}

			foreach ( $fields as $field_key => $field ) {
				wpcf7_add_tag_generator(
					$field_key,
					/* translators: form tag name */
					sprintf( esc_html_x( 'Affiliate Registration Form: %s', 'Affiliate registration tag name', 'affiliate-for-woocommerce' ), $field ),
					'wpcf7_tag_' . $field_key,
					array( $this, 'tag_generator_context' )
				);
			}
		}

		/**
		 * Render the CF7 password field.
		 *
		 * @param WPCF7_FormTag $tag The WPCF7 form tag object.
		 *
		 * @return string The input rendering string.
		 */
		public function render_password_fields( $tag = null ) {
			if ( ! $tag instanceof WPCF7_FormTag || empty( $tag->name ) ) {
				return '';
			}

			$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-text' );

			$class = ( ! empty( $class ) ? $class : '' ) . ' wpcf7-validates-as-password';

			$atts = array();

			$validation_error = wpcf7_get_validation_error( $tag->name );

			if ( ! empty( $validation_error ) ) {
				$class                   .= ' wpcf7-not-valid';
				$atts['aria-invalid']     = 'true';
				$atts['aria-describedby'] = wpcf7_get_validation_error_reference(
					$tag->name
				);
			} else {
				$atts['aria-invalid'] = 'false';
			}

			$atts['size']      = is_callable( array( $tag, 'get_size_option' ) ) ? $tag->get_size_option( '40' ) : '40';
			$atts['maxlength'] = is_callable( array( $tag, 'get_maxlength_option' ) ) ? $tag->get_maxlength_option() : '';
			$atts['minlength'] = is_callable( array( $tag, 'get_minlength_option' ) ) ? $tag->get_minlength_option() : '';

			if ( $atts['maxlength'] && $atts['minlength'] && intval( $atts['maxlength'] ) < intval( $atts['minlength'] ) ) {
				unset( $atts['maxlength'], $atts['minlength'] );
			}

			$atts['class']    = is_callable( array( $tag, 'get_class_option' ) ) ? $tag->get_class_option( $class ) : '';
			$atts['id']       = is_callable( array( $tag, 'get_id_option' ) ) ? $tag->get_id_option() : '';
			$atts['tabindex'] = is_callable( array( $tag, 'get_option' ) ) ? $tag->get_option( 'tabindex', 'signed_int', true ) : '';

			if ( is_callable( array( $tag, 'is_required' ) ) && $tag->is_required() ) {
				$atts['aria-required'] = 'true';
			}

			$value = ! empty( $tag->values ) ? (string) reset( $tag->values ) : '';

			// Support placeholder. Reference: modules/text.php in the contact form 7 plugin.
			if ( is_callable( array( $tag, 'has_option' ) ) && ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) ) {
				$atts['placeholder'] = $value;
				$value               = '';
			}

			$atts['type'] = 'password';
			$atts['name'] = $tag->name;

			return sprintf(
				'<span class="wpcf7-form-control-wrap" data-name="%1$s"><input %2$s />%3$s</span>',
				sanitize_html_class( $tag->name ),
				wpcf7_format_atts( $atts ),
				! empty( $validation_error ) ? $validation_error : ''
			);
		}

		/**
		 * Runs the Affiliate Registration action after CF7 form submission.
		 *
		 * @param WPCF7_ContactForm $contact_form WPCF7 contact form object.
		 * @param bool              $abort Whether the form submission will be abort or not.
		 * @param WPCF7_Submission  $submission The WPCF7_Submission object.
		 *
		 * @return void.
		 */
		public function before_send_mail( $contact_form = null, &$abort = false, $submission = null ) {

			if ( ! $contact_form instanceof WPCF7_ContactForm || false === $this->is_afwc_form( $contact_form ) ) {
				return;
			}

			// Get submitted form data.
			$data = is_callable( array( $submission, 'get_posted_data' ) ) ? $submission->get_posted_data() : array();

			if ( empty( $data ) ) {
				return;
			}

			if (
				isset( $data['afwc_confirm_password'] )
				&& ! empty( $data['afwc_password'] )
				&& ( empty( $data['afwc_confirm_password'] ) || ( $data['afwc_confirm_password'] !== $data['afwc_password'] ) )
				&& is_callable( array( $submission, 'set_response' ) )
			) {
				$abort = true;
				$submission->set_response( _x( 'Passwords do not match.', 'Password mismatch message', 'affiliate-for-woocommerce' ) );
				return;
			}

			// Get files.
			$uploaded_files = is_callable( array( $submission, 'uploaded_files' ) ) ? $submission->uploaded_files() : array();
			$tags           = is_callable( array( $contact_form, 'scan_form_tags' ) ) ? $contact_form->scan_form_tags() : array();

			$restricted_fields = array();

			// Restricted some specific field types to saving in the database.
			$restricted_types = apply_filters(
				'afwc_registration_cf7_restricted_fields_types',
				array( 'acceptance', 'captchac' ),
				array( 'source' => $this )
			);

			$field_types = array();

			if ( ! empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					if ( $tag instanceof WPCF7_FormTag && ! empty( $tag->basetype ) && ! empty( $tag->name ) ) {

						if ( ! empty( $restricted_types ) && is_array( $restricted_types ) && in_array( $tag->basetype, $restricted_types, true ) ) {
							$restricted_fields[] = $tag->name;
						}

						$field_types[ $tag->name ] = $tag->basetype;
					}
				}
			}

			$fields = array();

			foreach ( $data as $key => $value ) {
				if ( is_array( $restricted_fields ) && in_array( $key, $restricted_fields, true ) ) {
					continue;
				}

				// Submissions ignored fields.
				if ( 'afwc_confirm_password' === $key ) {
					continue;
				}

				if ( 'file' === ( ! empty( $field_types[ $key ] ) ? $field_types[ $key ] : '' ) ) {
					$value = ! empty( $uploaded_files[ $key ] ) ? wpcf7_flat_join( $this->handle_files( $key, $uploaded_files[ $key ] ) ) : '';
				} else {
					$value = is_array( $value ) ? wpcf7_flat_join( $value ) : $value;
				}

				$fields[] = array(
					'key'   => $key,
					'value' => $value,
					'type'  => ! empty( $field_types[ $key ] ) ? $field_types[ $key ] : '',
					'label' => ucwords( wc_clean( str_replace( '-', ' ', $key ) ) ),
				);
			}

			$affiliate_registration = is_callable( array( 'AFWC_Registration_Submissions', 'get_instance' ) ) ? AFWC_Registration_Submissions::get_instance() : null;
			$result                 = is_callable( array( $affiliate_registration, 'register_user' ) ) ? $affiliate_registration->register_user( $fields ) : array();

			if ( ! empty( $result['status'] ) && 'error' === $result['status'] ) {
				$abort = true;
			}

			if ( is_callable( array( $submission, 'set_response' ) ) ) {
				$message = '';

				$data = ! empty( $result['data'] ) ? $result['data'] : array();

				if ( ! empty( $data ) && ! empty( $data['affiliate_status'] ) ) {
					if ( ! empty( $data['submitted_before'] ) && true === $data['submitted_before'] ) {
						if ( 'pending' === $data['affiliate_status'] ) {
							$message = wpcf7_get_message( 'afwc_pending_affiliate' );
						} elseif ( 'no' === $data['affiliate_status'] ) {
							$message = wpcf7_get_message( 'afwc_rejected_affiliate' );
						} elseif ( 'yes' === $data['affiliate_status'] ) {
							$message = wpcf7_get_message( 'afwc_registered_affiliate' );
						}
					} elseif ( 'yes' === $data['affiliate_status'] ) {
							$message = wpcf7_get_message( 'afwc_successfully_registered_with_auto_approved' );
					} elseif ( 'pending' === $data['affiliate_status'] ) {
						$message = wpcf7_get_message( 'afwc_successfully_registered_without_auto_approved' );
					}
				}

				if ( empty( $message ) && ! empty( $result['message'] ) ) {
					$message = $result['message'];
				}

				if ( ! empty( $message ) ) {
					if ( true === $abort ) {
						$submission->set_response( $message );
					} elseif ( is_callable( array( $contact_form, 'get_properties' ) ) && is_callable( array( $contact_form, 'set_properties' ) ) ) {
						$properties                             = $contact_form->get_properties();
						$properties['messages']['mail_sent_ok'] = $message;
						$contact_form->set_properties( $properties );
					}
				}
			}
		}

		/**
		 * Handle files.
		 *
		 * @param string $file_key File key name.
		 * @param array  $uploaded_files The uploaded files.
		 *
		 * @return array Return the array of uploaded files URL.
		 */
		public function handle_files( $file_key = '', $uploaded_files = array() ) {
			if ( empty( $uploaded_files ) ) {
				return array();
			}

			$upload_dir = wp_upload_dir();

			if ( empty( $upload_dir['basedir'] ) || empty( $upload_dir['baseurl'] ) ) {
				return array();
			}

			$registration_upload_path = '/afwc_uploads/registrations/';
			$path                     = $upload_dir['basedir'] . $registration_upload_path;
			$attachments              = array();

			if ( ! file_exists( $path ) ) {
				wp_mkdir_p( $path );
			}

			foreach ( $uploaded_files as $file ) {
				$file_name = wp_unique_filename( $path, $file_key . basename( $file ) );
				copy( $file, $path . $file_name );
				$attachments[] = $upload_dir['baseurl'] . $registration_upload_path . $file_name;
			}

			return $attachments;
		}

		/**
		 * Register the affiliate responses in CF7.
		 *
		 * @param array $messages The array of messages.
		 *
		 * @return array Return the array of response messages.
		 */
		public function registration_response( $messages = array() ) {
			$contact_form = wpcf7_get_current_contact_form();

			if ( ! $contact_form instanceof WPCF7_ContactForm || false === $this->is_afwc_form( $contact_form ) ) {
				return $messages;
			}

			$afwc_submissions = AFWC_Registration_Submissions::get_instance();

			if ( ! is_callable( array( $afwc_submissions, 'get_message' ) ) ) {
				return $messages;
			}

			// Get the admin contact email.
			$afwc_admin_contact_email = get_option( 'afwc_contact_admin_email_address', '' );

			$afwc_messages = array(
				'afwc_successfully_registered_without_auto_approved' => array(
					'description' => _x( 'The user\'s request is successfully submitted and is now awaiting review for approval as an affiliate as Approval method setting is disabled', 'cf7: affiliate successfully registered with out auto approved response description', 'affiliate-for-woocommerce' ),
					'default'     => $afwc_submissions->get_message( 'success_without_auto_approved' ),
				),
				'afwc_successfully_registered_with_auto_approved' => array(
					'description' => _x( 'The user is successfully registered as an affiliate as Approval method setting is enabled', 'cf7: affiliate successfully registered with auto approved response description', 'affiliate-for-woocommerce' ),
					'default'     => _x( 'Congratulations, you are successfully registered as our affiliate. Visit My Account > Affiliate to find more details about the affiliate program.', 'cf7: affiliate successfully registered with auto approved response default message', 'affiliate-for-woocommerce' ),
				),
				'afwc_pending_affiliate'    => array(
					'description' => _x( 'The user have already signed up for affiliate program and their request is pending for review', 'cf7: pending affiliate response description', 'affiliate-for-woocommerce' ),
					'default'     => $afwc_submissions->get_message( 'pending' ),
				),
				'afwc_rejected_affiliate'   => array(
					'description' => _x( 'The user\'s request to signup for affiliate program was rejected previously', 'cf7: rejected affiliate response description', 'affiliate-for-woocommerce' ),
					'default'     => ! empty( $afwc_admin_contact_email )
									? sprintf(
										/* translators: affiliate manager mail */
										_x( 'Your previous request to join our affiliate program has been declined. Please contact %s for more details.', 'cf7: rejected affiliate response default message', 'affiliate-for-woocommerce' ),
										$afwc_admin_contact_email
									)
									: _x( 'Your previous request to join our affiliate program has been declined. Please contact the store admin for more details.', 'cf7: rejected affiliate response default message', 'affiliate-for-woocommerce' ),
				),
				'afwc_registered_affiliate' => array(
					'description' => _x( 'The user is already registered as an affiliate', 'cf7: already registered affiliate response description', 'affiliate-for-woocommerce' ),
					'default'     => $afwc_submissions->get_message( 'already_registered' ),
				),
			);

			// Get the messages from db.
			$message_properties = is_callable( array( $contact_form, 'prop' ) ) ? $contact_form->prop( 'messages' ) : array();

			foreach ( $afwc_messages as $key => $arr ) {
				// Check whether afwc message is available in the db property.
				if ( ! isset( $message_properties[ $key ] ) ) {
					$message_properties[ $key ] = ! empty( $arr['default'] ) ? $arr['default'] : '';
				}
			}

			// Set afwc default values to message property.
			$contact_form->set_properties( array( 'messages' => $message_properties ) );

			return array_merge( $afwc_messages, $messages );
		}

		/**
		 * Check the form is enabled for affiliate registration or not.
		 *
		 * @param WPCF7_ContactForm $contact_form WPCF7 contact form object.
		 *
		 * @return bool Return true if "afwc_registration" is enable or not for the form otherwise false.
		 */
		public function is_afwc_form( $contact_form = null ) {
			// Check whether the 'afwc_registration' is enabled for the current form or not.
			return apply_filters(
				'afwc_is_cf7_form',
				$contact_form instanceof WPCF7_ContactForm && is_callable( array( $contact_form, 'is_true' ) ) && true === $contact_form->is_true( 'afwc_registration' ),
				$contact_form,
				array( 'source' => $this )
			);
		}

		/**
		 * Callback method to render the tag generator context for affiliate fields.
		 *
		 * @param WPCF7_ContactForm $contact_form WPCF7 contact form object.
		 * @param array             $args The arguments.
		 *
		 * @return void.
		 */
		public function tag_generator_context( $contact_form = null, $args = array() ) {
			if ( empty( $args['id'] ) ) {
				return;
			}

			$type = 'text';
			$id   = $args['id'];

			switch ( true ) {
				case 'afwc_password' === $id || 'afwc_confirm_password' === $id:
					$type = 'afwc_password';
					break;
				case 'afwc_website' === $id:
					$type = 'url';
					break;
				case 'afwc_parent_id' === $id:
					$type = 'number';
					break;
				case 'afwc_email' === $id || 'afwc_paypal_email' === $id:
					$type = 'email';
			}

			$content = ! empty( $args['content'] ) ? $args['content'] : '';

			?>
			<div class="control-box">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><?php echo esc_html_x( 'Field type', 'cf7 field type', 'affiliate-for-woocommerce' ); ?></th>
							<td>
								<fieldset>
								<legend class="screen-reader-text"><?php echo esc_html_x( 'Field type', 'cf7 field type', 'affiliate-for-woocommerce' ); ?></legend>
								<label><input type="checkbox" name="required" /> <?php echo esc_html_x( 'Required field', 'cf7 required field', 'affiliate-for-woocommerce' ); ?></label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $content . '-name' ); ?>"><?php echo esc_html_x( 'Name', 'cf7 name attribute label', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $content . '-name' ); ?>" value="<?php echo esc_attr( $args['id'] ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $content . '-values' ); ?>"><?php echo esc_html_x( 'Default value', 'cf7 default value label', 'affiliate-for-woocommerce' ); ?></label></th>
							<td>
								<input type="text" name="values" class="oneline" id="<?php echo esc_attr( $content . '-values' ); ?>" /><br />
								<label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html_x( 'Use this text as the placeholder of the field', 'cf7 default value description', 'affiliate-for-woocommerce' ); ?></label>
							</td>
						</tr>
						<th scope="row"><?php echo esc_html_x( 'Range', 'cf7 number range label', 'affiliate-for-woocommerce' ); ?></th>
						<?php
						if ( 'number' === $type ) :
							// Set minimum value for parent id.
							$min_value = ( 'afwc_parent_id' === $id ) ? '1' : '';
							?>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><?php echo esc_html_x( 'Range', 'cf7 number range label', 'affiliate-for-woocommerce' ); ?></legend>
								<label>
									<?php echo esc_html_x( 'Min', 'cf7 minimum range label', 'affiliate-for-woocommerce' ); ?>
									<input type="number" name="min" class="numeric option" value="<?php echo esc_attr( $min_value ); ?>" />
								</label>
								&ndash;
								<label>
									<?php echo esc_html_x( 'Max', 'cf7 maximum range label', 'affiliate-for-woocommerce' ); ?>
									<input type="number" name="max" class="numeric option" />
								</label>
							</fieldset>
						</td>
						<?php endif; ?>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $content . '-id' ); ?>"><?php echo esc_html_x( 'Id attribute', 'cf7 id attribute label', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $content ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $content . '-class' ); ?>"><?php echo esc_html_x( 'Class attribute', 'cf7 class attribute label', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $content . '-class' ); ?>" /></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			</div>

			<div class="insert-box">
				<input type="text" name="<?php echo esc_attr( $type ); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

				<div class="submitbox">
					<input type="button" class="button button-primary insert-tag" value="<?php echo esc_html_x( 'Insert Tag', 'cf7 inserting tag', 'affiliate-for-woocommerce' ); ?>" />
				</div>

				<br class="clear" />

				<p class="description mail-tag">
					<label for="<?php echo esc_attr( $content . '-mailtag' ); ?>">
						<?php
						/* translators: mail tag */
						printf( esc_html_x( 'To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.', 'Guide for cf7 mail tag installations', 'affiliate-for-woocommerce' ), '<strong><span class="mail-tag"></span></strong>' );
						?>
						<input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $content . '-mailtag' ); ?>" />
					</label>
				</p>
			</div>
			<?php
		}

		/**
		 * Add script for CF7 form.
		 *
		 * @return void.
		 */
		public function enqueue_scripts() {
			if ( ! wpcf7_script_is() ) {
				return;
			}

			$user = wp_get_current_user();

			if ( $user instanceof WP_User && is_callable( array( $user, 'exists' ) ) && $user->exists() ) {
				$affiliate_registration = AFWC_Registration_Submissions::get_instance();

				$afwc_fields = array(
					'hidden'   => ! empty( $affiliate_registration->hide_fields ) ? $affiliate_registration->hide_fields : array(),
					'readonly' => ! empty( $affiliate_registration->readonly_fields ) ? $affiliate_registration->readonly_fields : array(),
				);

				if ( empty( $afwc_fields['hidden'] ) && empty( $afwc_fields['hidden'] ) ) {
					return;
				}

				wp_localize_script( 'contact-form-7', 'afwcFieldDetails', $afwc_fields );
				$email = ( ! empty( $user->user_email ) ) ? $user->user_email : '';
				$js    = <<<JS
				if( 'object' === typeof afwcFieldDetails ) {
					jQuery('form.wpcf7-form').find('input').each(function(){
						let name = jQuery(this).attr('name') || '';
						if( afwcFieldDetails.hidden && jQuery.inArray(name, afwcFieldDetails.hidden ) >= 0 ) {
							jQuery(this).closest('label').remove();
							jQuery(this).remove();
							jQuery('.wpcf7-form-control-wrap[data-name="'+name+'"]').remove()
						}

						if( afwcFieldDetails.readonly && jQuery.inArray(name, afwcFieldDetails.readonly) >= 0 ) {
							jQuery(this).attr('readonly', true);
							jQuery(this).val('$email');
						}
					})
				}
JS;

				wc_enqueue_js( $js );
			}
		}

		/**
		 * Filter the posted data on submissions.
		 * A workaround solution to remove the `password`, `confirm_password` field from the posted data as `is_user_logged_in()` does not give desired result in CF7 ajax call.
		 *
		 * @param array $data The submission posted data.
		 *
		 * @return array The array of filtered posted data.
		 */
		public function posted_data( $data = array() ) {

			// Prevent the execution if the current form is not an affiliate registration form.
			if ( ! $this->is_afwc_form( wpcf7_get_current_contact_form() ) ) {
				return $data;
			}

			if ( empty( $data['afwc_email'] ) || ! email_exists( $data['afwc_email'] ) ) {
				return $data;
			}

			// Unset the password if exists.
			if ( isset( $data['afwc_password'] ) ) {
				unset( $data['afwc_password'] );
			}

			// Unset the confirm password if exists.
			if ( isset( $data['afwc_confirm_password'] ) ) {
				unset( $data['afwc_confirm_password'] );
			}

			return $data;
		}
	}
}

CF7_AFWC_Compatibility::get_instance();
