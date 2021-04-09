<?php
/**
 * Main class for Welcome affiliate email
 *
 * @package     affiliate-for-woocommerce/includes/emails/
 * @version     1.0.1
 * @since       2.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Afwc_Welcome_Affiliate_Email' ) ) {

	/**
	 * Welcome email for affiliate
	 *
	 * @extends \WC_Email
	 */
	class Afwc_Welcome_Affiliate_Email extends WC_Email {

		/**
		 * Set email defaults
		 */
		public function __construct() {

			// Set ID, this simply needs to be a unique name.
			$this->id = 'afwc_new_registration';

			// This is the title in WooCommerce Email settings.
			$this->title = 'Affiliate - Welcome email';

			// This is the description in WooCommerce email settings.
			$this->description = __( 'This email will be sent to an affiliate after their request to join is approved.', 'affiliate-for-woocommerce' );

			// These are the default heading and subject lines that can be overridden using the settings.
			$this->subject = 'Welcome to {site_title}';
			$this->heading = 'Welcome to our affiliate program!';

			// Email template location.
			$this->template_html  = 'afwc-welcome-affiliate-email.php';
			$this->template_plain = 'plain/afwc-welcome-affiliate-email.php';

			// Use our plugin templates directory as the template base.
			$this->template_base = AFWC_PLUGIN_DIRPATH . '/templates/';

			$this->placeholders = array();

			// Trigger on new conversion.
			add_action( 'afwc_welcome_affiliate_email', array( $this, 'trigger' ), 10, 1 );

			// Call parent constructor to load any other defaults not explicity defined here.
			parent::__construct();

			// When sending email to customer in this case it is affiliate.
			$this->customer_email = true;

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

			$this->email_args = '';
			$this->email_args = wp_parse_args( $args, $this->email_args );

			// Set the locale to the store locale for customer emails to make sure emails are in the store language.
			$this->setup_locale();

			$affiliate_id = $this->email_args['affiliate_id'];
			$user         = get_user_by( 'id', $affiliate_id );
			if ( $user ) {
				$this->recipient = $user->user_email;
			}

			$user_info                     = get_userdata( $affiliate_id );
			$this->email_args['user_name'] = ! empty( $user_info->first_name ) ? $user_info->first_name : __( 'there', 'affiliate-for-woocommerce' );

			$pname                                   = get_option( 'afwc_pname', 'ref' );
			$pname                                   = ( ! empty( $pname ) ) ? $pname : 'ref';
			$afwc_ref_url_id                         = get_user_meta( $affiliate_id, 'afwc_ref_url_id', true );
			$affiliate_id                            = get_affiliate_id_based_on_user_id( $affiliate_id );
			$affiliate_identifier                    = ( ! empty( $afwc_ref_url_id ) ) ? $afwc_ref_url_id : $affiliate_id;
			$endpoint                                = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
			$my_account_afwc_url                     = wc_get_endpoint_url( $endpoint, '', wc_get_page_permalink( 'myaccount' ) );
			$this->email_args['affiliate_link']      = add_query_arg( $pname, $affiliate_identifier, trailingslashit( home_url() ) );
			$this->email_args['shop_page']           = get_permalink( wc_get_page_id( 'shop' ) );
			$this->email_args['my_account_afwc_url'] = $my_account_afwc_url;
			$this->email_args['affiliate_id']        = $affiliate_identifier;
			// For any email placeholders.
			$this->set_placeholders();

			$email_content = $this->get_content();
			// Replace placeholders with values in the email content.
			$email_content = ( is_callable( array( $this, 'format_string' ) ) ) ? $this->format_string( $email_content ) : $email_content;

			// Send email.
			if ( $this->is_enabled() && $this->get_recipient() ) {
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
			$default_path  = $this->template_base;
			$template_path = AFWC_Emails::get_instance()->get_template_base_dir( $this->template_html );

			$email_heading = $this->get_heading();

			ob_start();

			wc_get_template(
				$this->template_html,
				array(
					'email'               => $this,
					'email_heading'       => $email_heading,
					'user_name'           => $this->email_args['user_name'],
					'affiliate_link'      => $this->email_args['affiliate_link'],
					'affiliate_id'        => $this->email_args['affiliate_id'],
					'shop_page'           => $this->email_args['shop_page'],
					'my_account_afwc_url' => $this->email_args['my_account_afwc_url'],
					'additional_content'  => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
				),
				$template_path,
				$default_path
			);

			return ob_get_clean();
		}

		/**
		 * Function to load email plain content
		 *
		 * @return string Email plain content
		 */
		public function get_content_plain() {
			$default_path  = $this->template_base;
			$template_path = AFWC_Emails::get_instance()->get_template_base_dir( $this->template_plain );

			$email_heading = $this->get_heading();

			ob_start();

			wc_get_template(
				$this->template_plain,
				array(
					'email'               => $this,
					'email_heading'       => $email_heading,
					'user_name'           => $this->email_args['user_name'],
					'affiliate_link'      => $this->email_args['affiliate_link'],
					'affiliate_id'        => $this->email_args['affiliate_id'],
					'shop_page'           => $this->email_args['shop_page'],
					'my_account_afwc_url' => $this->email_args['my_account_afwc_url'],
					'additional_content'  => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
				),
				$template_path,
				$default_path
			);

			return ob_get_clean();
		}

		/**
		 * Initialize Settings Form Fields
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'            => array(
					'title'   => __( 'Enable/Disable', 'affiliate-for-woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'affiliate-for-woocommerce' ),
					'default' => 'yes',
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
