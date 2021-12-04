<?php
/**
 * Main class for Affiliate conversion received Email
 *
 * @package     affiliate-for-woocommerce/includes/emails/
 * @since       2.3.0
 * @version     1.0.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_New_Conversion_Email' ) ) {

	/**
	 * The Affiliate New Conversion Email class
	 *
	 * @extends \WC_Email
	 */
	class AFWC_New_Conversion_Email extends WC_Email {

		/**
		 * Set email defaults
		 */
		public function __construct() {

			// Set ID, this simply needs to be a unique name.
			$this->id = 'afwc_new_conversion';

			// This is the title in WooCommerce Email settings.
			$this->title = 'Affiliate - New Conversion Received';

			// This is the description in WooCommerce email settings.
			$this->description = __( 'This email will be sent to an affiliate when an order is placed using their referral link/coupon i.e. on a new conversion.', 'affiliate-for-woocommerce' );

			// These are the default heading and subject lines that can be overridden using the settings.
			$this->subject = '{site_title} - new order from your referral 👍';
			$this->heading = 'You helped {site_title} make a sale!';

			// Email template location.
			$this->template_html  = 'afwc-new-conversion.php';
			$this->template_plain = 'plain/afwc-new-conversion.php';
			// Use our plugin templates directory as the template base.
			$this->template_base = AFWC_PLUGIN_DIRPATH . '/templates/';

			$this->placeholders = array();

			// Trigger on new conversion.
			add_action( 'afwc_new_conversion_received_email', array( $this, 'trigger' ), 10, 1 );

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

			$user_info = get_userdata( $affiliate_id );
			// TODO-MS: write a fallback logic if first name not found.
			$this->email_args['user_name'] = $user_info->first_name;

			$order_id                                     = isset( $this->email_args['order_id'] ) ? $this->email_args['order_id'] : 0;
			$order                                        = wc_get_order( $order_id );
			$this->email_args['order_total']              = $order->get_total();
			$this->email_args['order_customer_full_name'] = $order->get_formatted_billing_full_name();

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

			$email_heading           = $this->get_heading();
			$order_commission_amount = isset( $this->email_args['order_commission_amount'] ) ? wc_format_decimal( $this->email_args['order_commission_amount'], wc_get_price_decimals() ) : 0.00;
			$order_currency_symbol   = isset( $this->email_args['currency_id'] ) ? get_woocommerce_currency_symbol( $this->email_args['currency_id'] ) : get_woocommerce_currency_symbol();
			$affiliate_name          = isset( $this->email_args['user_name'] ) ? $this->email_args['user_name'] : __( 'there', 'affiliate-for-woocommerce' );

			$order_total = isset( $this->email_args['order_total'] ) ? $this->email_args['order_total'] : 0;

			$order_customer_full_name = ( $this->email_args['order_customer_full_name'] ) ? $this->email_args['order_customer_full_name'] : __( 'Guest', 'affiliate-for-woocommerce' );

			$endpoint            = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
			$my_account_afwc_url = wc_get_endpoint_url( $endpoint, '', wc_get_page_permalink( 'myaccount' ) );

			$order_id = isset( $this->email_args['order_id'] ) ? $this->email_args['order_id'] : 0;

			ob_start();

			wc_get_template(
				$this->template_html,
				array(
					'email'                    => $this,
					'email_heading'            => $email_heading,
					'order_commission_amount'  => $order_commission_amount,
					'order_currency_symbol'    => $order_currency_symbol,
					'affiliate_name'           => $affiliate_name,
					'order_total'              => $order_total,
					'order_id'                 => $order_id,
					'my_account_afwc_url'      => $my_account_afwc_url,
					'order_customer_full_name' => $order_customer_full_name,
					'additional_content'       => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
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

			$email_heading           = $this->get_heading();
			$order_commission_amount = isset( $this->email_args['order_commission_amount'] ) ? wc_format_decimal( $this->email_args['order_commission_amount'], wc_get_price_decimals() ) : 0.00;
			$order_currency_symbol   = isset( $this->email_args['currency_id'] ) ? get_woocommerce_currency_symbol( $this->email_args['currency_id'] ) : get_woocommerce_currency_symbol();
			$affiliate_name          = isset( $this->email_args['user_name'] ) ? $this->email_args['user_name'] : __( 'there', 'affiliate-for-woocommerce' );

			$order_total = isset( $this->email_args['order_total'] ) ? $this->email_args['order_total'] : 0;

			$order_customer_full_name = ( $this->email_args['order_customer_full_name'] ) ? $this->email_args['order_customer_full_name'] : __( 'Guest', 'affiliate-for-woocommerce' );

			$endpoint            = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
			$my_account_afwc_url = wc_get_endpoint_url( $endpoint, '', wc_get_page_permalink( 'myaccount' ) );
			$order_id            = isset( $this->email_args['order_id'] ) ? $this->email_args['order_id'] : 0;

			ob_start();

			wc_get_template(
				$this->template_plain,
				array(
					'email'                    => $this,
					'email_heading'            => $email_heading,
					'order_commission_amount'  => $order_commission_amount,
					'order_currency_symbol'    => $order_currency_symbol,
					'affiliate_name'           => $affiliate_name,
					'order_total'              => $order_total,
					'my_account_afwc_url'      => $my_account_afwc_url,
					'order_customer_full_name' => $order_customer_full_name,
					'order_id'                 => $order_id,
					'additional_content'       => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
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
