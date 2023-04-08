<?php
/**
 * Main class for affiliate pending request email.
 *
 * @package   affiliate-for-woocommerce/includes/emails/
 * @since     6.4.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Email_Affiliate_Pending_Request' ) ) {

	/**
	 * Affiliate pending request email.
	 *
	 * @extends \WC_Email
	 */
	class AFWC_Email_Affiliate_Pending_Request extends WC_Email {

		/**
		 * Set email defaults
		 */
		public function __construct() {

			// Set ID, this simply needs to be a unique name.
			$this->id = 'afwc_affiliate_pending_request';

			// This is the title in WooCommerce email settings.
			$this->title = 'Affiliate - Pending Request';

			// This is the description in WooCommerce email settings.
			$this->description = _x( 'This email will be sent to an affiliate after the registration form is submitted and their request to join the affiliate program is in pending status.', 'description for affiliate pending request email', 'affiliate-for-woocommerce' );

			// These are the default heading and subject lines that can be overridden using the settings.
			$this->subject = 'Your affiliate request on {site_title} is under review';
			$this->heading = 'Affiliate request under review';

			// Email template location.
			$this->template_html  = 'afwc-affiliate-pending-request.php';
			$this->template_plain = 'plain/afwc-affiliate-pending-request.php';

			// Use our plugin templates directory as the template base.
			$this->template_base = AFWC_PLUGIN_DIRPATH . '/templates/';

			$this->placeholders = array();

			// Trigger this email to affiliate if their affiliate request is pending.
			add_action( 'afwc_email_affiliate_pending_request', array( $this, 'trigger' ), 10, 1 );

			// Call parent constructor to load any other defaults not explicity defined here.
			parent::__construct();

			// When sending email to customer in this case it is affiliate.
			$this->customer_email = true;

		}

		/**
		 * Determine if the email should actually be sent and setup email merge variables
		 *
		 * @param array $args Email arguments.
		 */
		public function trigger( $args = array() ) {

			if ( empty( $args ) ) {
				return;
			}

			$this->email_args = $args;

			// Set the locale to the store locale for customer emails to make sure emails are in the store language.
			$this->setup_locale();

			$user = ! empty( $this->email_args['user_id'] ) ? get_user_by( 'id', $this->email_args['user_id'] ) : null;
			if ( $user instanceof WP_User && ! empty( $user->user_email ) ) {
				$this->recipient = $user->user_email;
			}
			$this->email_args['user_name']     = ! empty( $user->first_name ) ? $user->first_name : _x( 'there', 'Greeting for affiliate user', 'affiliate-for-woocommerce' );
			$this->email_args['contact_email'] = get_option( 'afwc_contact_admin_email_address', '' );

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
				'email'              => $this,
				'email_heading'      => is_callable( array( $this, 'get_heading' ) ) ? $this->get_heading() : '',
				'user_name'          => $this->email_args['user_name'],
				'contact_email'      => $this->email_args['contact_email'],
				'additional_content' => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
			);
		}

		/**
		 * Initialize Settings Form Fields
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'            => array(
					'title'   => _x( 'Enable/Disable', 'title for enable/disable the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'type'    => 'checkbox',
					'label'   => _x( 'Enable this email notification', 'label for enable/disable the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'default' => 'yes',
				),
				'subject'            => array(
					'title'       => _x( 'Subject', 'title for the subject of the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'type'        => 'text',
					/* translators: %s Email subject. */
					'description' => sprintf( _x( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'description for the subject of the affiliate pending request email', 'affiliate-for-woocommerce' ), $this->subject ),
					'placeholder' => $this->subject,
					'default'     => '',
				),
				'heading'            => array(
					'title'       => _x( 'Email Heading', 'title for the email heading of the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'type'        => 'text',
					/* translators: %s Email heading. */
					'description' => sprintf( _x( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'description for the email heading of the affiliate pending request email', 'affiliate-for-woocommerce' ), $this->heading ),
					'placeholder' => $this->heading,
					'default'     => '',
				),
				'additional_content' => array(
					'title'       => _x( 'Additional content', 'title for the additional content of the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'description' => _x( 'Text to appear below the main email content.', 'description for the additional content of the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'css'         => 'width:400px; height: 75px;',
					'placeholder' => _x( 'N/A', 'placeholder for the additional content of the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'type'        => 'textarea',
					'default'     => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '', // WC 3.7 introduced an additional content field for all emails.
				),
				'email_type'         => array(
					'title'       => _x( 'Email type', 'title for the email type of the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'type'        => 'select',
					'description' => _x( 'Choose which format of email to send.', 'description for the email type of the affiliate pending request email', 'affiliate-for-woocommerce' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
				),
			);
		}

	}

}
