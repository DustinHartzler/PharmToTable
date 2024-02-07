<?php
/**
 * Main class for New  registration to admin Email
 *
 * @package     affiliate-for-woocommerce/includes/emails/
 * @since       2.4.0
 * @version     1.4.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Email_New_Registration_Received' ) ) {

	/**
	 * The Affiliate New Registration to admin
	 *
	 * @extends \WC_Email
	 */
	class AFWC_Email_New_Registration_Received extends WC_Email {

		/**
		 * Set email defaults
		 */
		public function __construct() {

			// Set ID, this simply needs to be a unique name.
			$this->id = 'afwc_new_registration';

			// This is the title in WooCommerce email settings.
			$this->title = 'Affiliate Manager - New Registration Received';

			// This is the description in WooCommerce email settings.
			$this->description = _x( 'This email will be sent to an affiliate manager when a new affiliate registration request is received or when an affiliate joins automatically.', 'Description for Affiliate Manager - New Registration Received', 'affiliate-for-woocommerce' );

			// These are the default heading and subject lines that can be overridden using the settings.
			$this->subject = '{site_title} - New affiliate user registration';
			$this->heading = 'New affiliate registration';

			// Email template location.
			$this->template_html  = 'afwc-new-registration-received.php';
			$this->template_plain = 'plain/afwc-new-registration-received.php';

			// Use our plugin templates directory as the template base.
			$this->template_base = AFWC_PLUGIN_DIRPATH . '/templates/';

			$this->placeholders = array();

			// Trigger this email when a new affiliate registration is received.
			add_action( 'afwc_email_new_registration_received', array( $this, 'trigger' ), 10, 1 );

			// Call parent constructor to load any other defaults not explicity defined here.
			parent::__construct();

			$this->recipient = get_option( 'afwc_contact_admin_email_address', '' );

			if ( ! $this->recipient ) {
				$this->recipient = get_option( 'admin_email' );
			}
		}

		/**
		 * Determine if the email should actually be sent and setup email merge variables
		 *
		 * @param array $args Email arguements.
		 */
		public function trigger( $args = array() ) {

			if ( empty( $args ) ) {
				return;
			}

			$this->email_args = array();
			$this->email_args = wp_parse_args( $args, $this->email_args );

			$admin = get_user_by( 'email', $this->recipient );

			$this->email_args['admin_name']                   = ! empty( $admin->user_login ) ? $admin->user_login : _x( 'there', 'Greeting for admin', 'affiliate-for-woocommerce' );
			$this->email_args['dashboard_url']                = admin_url( 'admin.php?page=affiliate-for-woocommerce' );
			$this->email_args['manage_url']                   = ! empty( $this->email_args['user_id'] ) ? admin_url( 'user-edit.php?user_id=' . $this->email_args['user_id'] . '#afwc-settings' ) : admin_url();
			$this->email_args['user_name']                    = ! empty( $this->email_args['userdata']['afwc_first_name'] ) ? $this->email_args['userdata']['afwc_first_name'] . ' ' . ( ! empty( $this->email_args['userdata']['afwc_last_name'] ) ? $this->email_args['userdata']['afwc_last_name'] : '' ) : ( ! empty( $this->email_args['userdata']['user_login'] ) ? $this->email_args['userdata']['user_login'] : '' );
			$this->email_args['user_email']                   = ! empty( $this->email_args['userdata']['afwc_email'] ) ? $this->email_args['userdata']['afwc_email'] : '';
			$this->email_args['additional_information']       = ! empty( $this->email_args['userdata']['afwc_additional_fields'] ) ? $this->email_args['userdata']['afwc_additional_fields'] : '';
			$this->email_args['additional_information_label'] = esc_html_x( 'Additional information', 'Label for additional information', 'affiliate-for-woocommerce' );
			$this->email_args['user_url']                     = ! empty( $this->email_args['userdata']['afwc_website'] ) ? $this->email_args['userdata']['afwc_website'] : '';
			$this->email_args['user_website_label']           = esc_html_x( 'Website', 'Label for affiliate user website link', 'affiliate-for-woocommerce' );
			$this->email_args['is_auto_approved']             = ! empty( $this->email_args['is_auto_approved'] ) ? $this->email_args['is_auto_approved'] : get_option( 'afwc_auto_add_affiliate', 'no' );

			// Set the locale to the store locale for customer emails to make sure emails are in the store language.
			$this->setup_locale();

			// For any email placeholders.
			$this->set_placeholders();

			$email_content = $this->get_content();
			// Replace placeholders with values in the email content.
			$email_content = ( is_callable( array( $this, 'format_string' ) ) ) ? $this->format_string( $email_content ) : $email_content;

			// Send email.
			if ( ! empty( $email_content ) && $this->is_enabled() && $this->get_recipient() ) {
				$this->send( $this->get_recipient(), $this->get_subject(), $email_content, $this->get_headers(), $this->get_attachments() );
			}

			$this->restore_locale();
		}

		/**
		 * Function to set placeholder variables used in email.
		 */
		public function set_placeholders() {
			// For any email placeholders.
			$this->placeholders = array(
				'{site_title}' => $this->get_blogname(),
			);
		}

		/**
		 * Function to load email html content
		 *
		 * @return string Email content html
		 */
		public function get_content_html() {
			global $affiliate_for_woocommerce;

			$email_arguments = $this->get_template_args();

			if ( ! empty( $email_arguments ) ) {
				ob_start();

				wc_get_template(
					$this->template_html,
					$email_arguments,
					is_callable( array( $affiliate_for_woocommerce, 'get_template_base_dir' ) ) ? $affiliate_for_woocommerce->get_template_base_dir( $this->template_html ) : '',
					$this->template_base
				);

				return ob_get_clean();
			}

			return '';
		}

		/**
		 * Function to load email plain content
		 *
		 * @return string Email plain content
		 */
		public function get_content_plain() {
			global $affiliate_for_woocommerce;

			$email_arguments = $this->get_template_args();

			if ( ! empty( $email_arguments ) ) {
				ob_start();

				wc_get_template(
					$this->template_plain,
					$email_arguments,
					is_callable( array( $affiliate_for_woocommerce, 'get_template_base_dir' ) ) ? $affiliate_for_woocommerce->get_template_base_dir( $this->template_plain ) : '',
					$this->template_base
				);

				return ob_get_clean();
			}

			return '';
		}

		/**
		 * Function to return the required email arguments for this email template.
		 *
		 * @return array Email arguments.
		 */
		public function get_template_args() {
			return array(
				'email'                        => $this,
				'email_heading'                => is_callable( array( $this, 'get_heading' ) ) ? $this->get_heading() : '',
				'admin_name'                   => $this->email_args['admin_name'],
				'user_email'                   => $this->email_args['user_email'],
				'user_name'                    => $this->email_args['user_name'],
				'dashboard_url'                => esc_url( $this->email_args['dashboard_url'] ),
				'manage_url'                   => esc_url( $this->email_args['manage_url'] ),
				'additional_information'       => $this->email_args['additional_information'],
				'additional_information_label' => $this->email_args['additional_information_label'],
				'user_url'                     => esc_url( $this->email_args['user_url'] ),
				'user_website_label'           => $this->email_args['user_website_label'],
				'is_auto_approved'             => $this->email_args['is_auto_approved'],
				'additional_content'           => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
			);
		}

		/**
		 * Initialize Settings Form Fields
		 */
		public function init_form_fields() {
			$onboarding_email_setup = get_option( 'afwc_onboarding_emails_setup', array() );
			$this->form_fields      = array(
				'enabled'            => array(
					'title'   => __( 'Enable/Disable', 'affiliate-for-woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'affiliate-for-woocommerce' ),
					'default' => ! empty( $onboarding_email_setup ) && is_array( $onboarding_email_setup ) && ! empty( $onboarding_email_setup[ $this->id ] ) ? $onboarding_email_setup[ $this->id ] : 'yes',
				),
				'subject'            => array(
					'title'       => __( 'Subject', 'affiliate-for-woocommerce' ),
					'type'        => 'text',
					'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
					'placeholder' => $this->subject,
					'default'     => '',
				),
				'heading'            => array(
					'title'       => __( 'Email Heading', 'affiliate-for-woocommerce' ),
					'type'        => 'text',
					/* translators: %s Email heading. */
					'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
					'placeholder' => $this->heading,
					'default'     => '',
				),
				'additional_content' => array(
					'title'       => __( 'Additional content', 'affiliate-for-woocommerce' ),
					'description' => __( 'Text to appear below the main email content.', 'affiliate-for-woocommerce' ),
					'css'         => 'width:400px; height: 75px;',
					'placeholder' => __( 'N/A', 'affiliate-for-woocommerce' ),
					'type'        => 'textarea',
					'default'     => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '', // WC 3.7 introduced an additional content field for all emails.
				),
				'email_type'         => array(
					'title'       => __( 'Email type', 'affiliate-for-woocommerce' ),
					'type'        => 'select',
					'description' => __( 'Choose which format of email to send.', 'affiliate-for-woocommerce' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
				),
			);
		}
	}

}
