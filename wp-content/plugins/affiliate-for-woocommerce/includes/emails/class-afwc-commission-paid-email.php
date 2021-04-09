<?php
/**
 * Main class for Affiliate conversion received Email
 *
 * @package     affiliate-for-woocommerce/includes/emails/
 * @version     1.0.1
 * @since       2.4.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Commission_Paid_Email' ) ) {

	/**
	 * The Affiliate Payout Sent Email class
	 *
	 * @extends \WC_Email
	 */
	class AFWC_Commission_Paid_Email extends WC_Email {

		/**
		 * Set email defaults
		 */
		public function __construct() {

			// Set ID, this simply needs to be a unique name.
			$this->id = 'afwc_commission_paid';

			// This is the title in WooCommerce Email settings.
			$this->title = 'Affiliate - Commission Paid';

			// This is the description in WooCommerce email settings.
			$this->description = __( 'This email will be sent to an affiliate when their commission/payout is processed from affiliate dashboard.', 'affiliate-for-woocommerce' );

			// These are the default heading and subject lines that can be overridden using the settings.
			$this->subject = 'Your affiliate commission from {site_title} is here!';
			$this->heading = 'Your commission is on your way!';

			// Email template location.
			$this->template_html  = 'afwc-commission-paid.php';
			$this->template_plain = 'plain/afwc-commission-paid.php';
			// Use our plugin templates directory as the template base.
			$this->template_base = AFWC_PLUGIN_DIRPATH . '/templates/';

			$this->placeholders = array();

			// Trigger on new conversion.
			add_action( 'afwc_commission_paid_email', array( $this, 'trigger' ), 10, 1 );

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

			// Whom to send email.
			$affiliate_id = $this->email_args['affiliate_id'];
			$user         = get_user_by( 'id', $affiliate_id );
			if ( $user ) {
				$this->recipient = $user->user_email;
			}

			$user_info = get_userdata( $affiliate_id );
			// TODO-MS: write a fallback logic to use nicename if first name not found.
			$this->email_args['affiliate_name']    = isset( $user_info->first_name ) ? $user_info->first_name : __( 'there', 'affiliate-for-woocommerce' );
			$this->email_args['commission_amount'] = isset( $this->email_args['amount'] ) ? wc_format_decimal( $this->email_args['amount'], wc_get_price_decimals() ) : 0.00;
			$this->email_args['currency_symbol']   = isset( $this->email_args['currency_id'] ) ? get_woocommerce_currency_symbol( $this->email_args['currency_id'] ) : get_woocommerce_currency_symbol();

			$this->email_args['start_date']            = isset( $this->email_args['from_date'] ) ? $this->email_args['from_date'] : '';
			$this->email_args['end_date']              = isset( $this->email_args['to_date'] ) ? $this->email_args['to_date'] : '';
			$this->email_args['total_orders']          = isset( $this->email_args['total_orders'] ) ? $this->email_args['total_orders'] : '';
			$this->email_args['payout_notes']          = isset( $this->email_args['payout_notes'] ) ? $this->email_args['payout_notes'] : '';
			$this->email_args['payment_gateway']       = $this->email_args['payment_gateway'];
			$this->email_args['paypal_receiver_email'] = $this->email_args['paypal_receiver_email'];

			$endpoint                                = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
			$this->email_args['my_account_afwc_url'] = wc_get_endpoint_url( $endpoint, '', wc_get_page_permalink( 'myaccount' ) );

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
					'email'                 => $this,
					'email_heading'         => $email_heading,
					'affiliate_name'        => $this->email_args['affiliate_name'],
					'commission_amount'     => $this->email_args['commission_amount'],
					'currency_symbol'       => $this->email_args['currency_symbol'],
					'start_date'            => $this->email_args['start_date'],
					'end_date'              => $this->email_args['end_date'],
					'total_orders'          => $this->email_args['total_orders'],
					'payout_notes'          => $this->email_args['payout_notes'],
					'payment_gateway'       => $this->email_args['payment_gateway'],
					'paypal_receiver_email' => $this->email_args['paypal_receiver_email'],
					'my_account_afwc_url'   => $this->email_args['my_account_afwc_url'],
					'additional_content'    => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
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
					'email'                 => $this,
					'email_heading'         => $email_heading,
					'affiliate_name'        => $this->email_args['affiliate_name'],
					'commission_amount'     => $this->email_args['commission_amount'],
					'currency_symbol'       => $this->email_args['currency_symbol'],
					'start_date'            => $this->email_args['start_date'],
					'end_date'              => $this->email_args['end_date'],
					'total_orders'          => $this->email_args['total_orders'],
					'payout_notes'          => $this->email_args['payout_notes'],
					'payment_gateway'       => $this->email_args['payment_gateway'],
					'paypal_receiver_email' => $this->email_args['paypal_receiver_email'],
					'my_account_afwc_url'   => $this->email_args['my_account_afwc_url'],
					'additional_content'    => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
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
