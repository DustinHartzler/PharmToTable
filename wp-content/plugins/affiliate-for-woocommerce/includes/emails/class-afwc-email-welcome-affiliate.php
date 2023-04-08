<?php
/**
 * Main class for Welcome affiliate email
 *
 * @package     affiliate-for-woocommerce/includes/emails/
 * @since       2.4.0
 * @version     1.4.5
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Email_Welcome_Affiliate' ) ) {

	/**
	 * Welcome email for affiliate
	 *
	 * @extends \WC_Email
	 */
	class AFWC_Email_Welcome_Affiliate extends WC_Email {

		/**
		 * Set email defaults
		 */
		public function __construct() {

			// Set ID, this simply needs to be a unique name.
			$this->id = 'afwc_welcome';

			// This is the title in WooCommerce email settings.
			$this->title = 'Affiliate - Welcome Email';

			// This is the description in WooCommerce email settings.
			$this->description = __( 'This email will be sent to an affiliate after their request to join is approved OR if they automatically become an affiliate.', 'affiliate-for-woocommerce' );

			// These are the default heading and subject lines that can be overridden using the settings.
			$this->subject = 'Welcome to {site_title}';
			$this->heading = 'Welcome to our affiliate program!';

			// Email template location.
			$this->template_html  = 'afwc-welcome-affiliate-email.php';
			$this->template_plain = 'plain/afwc-welcome-affiliate-email.php';

			// Use our plugin templates directory as the template base.
			$this->template_base = AFWC_PLUGIN_DIRPATH . '/templates/';

			$this->placeholders = array();

			// Trigger this email as a welcome email to an affiliate.
			add_action( 'afwc_email_welcome_affiliate', array( $this, 'trigger' ), 10, 1 );

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

			$affiliate_id = ! empty( $this->email_args['affiliate_id'] ) ? intval( $this->email_args['affiliate_id'] ) : 0;
			$user         = ! empty( $affiliate_id ) ? get_user_by( 'id', $affiliate_id ) : null;
			if ( $user instanceof WP_User && ! empty( $user->user_email ) ) {
				$this->recipient = $user->user_email;
			}

			$user_info                     = get_userdata( $affiliate_id );
			$this->email_args['user_name'] = ! empty( $user_info->first_name ) ? $user_info->first_name : __( 'there', 'affiliate-for-woocommerce' );

			$pname                                   = afwc_get_pname();
			$afwc_ref_url_id                         = get_user_meta( $affiliate_id, 'afwc_ref_url_id', true );
			$affiliate_id                            = afwc_get_affiliate_id_based_on_user_id( $affiliate_id );
			$affiliate_identifier                    = ( ! empty( $afwc_ref_url_id ) ) ? $afwc_ref_url_id : $affiliate_id;
			$endpoint                                = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );
			$my_account_page_id                      = wc_get_page_id( 'myaccount' );
			$my_account_page                         = ( ! empty( $my_account_page_id ) && ( intval( $my_account_page_id ) > 0 ) ) ? wc_get_page_permalink( 'myaccount' ) : '';
			$this->email_args['my_account_afwc_url'] = apply_filters( 'afwc_myaccount_dashboard_url', ( ! empty( $my_account_page ) ? wc_get_endpoint_url( $endpoint, '', $my_account_page ) : get_home_url() ), array( 'source' => $this ) );
			$this->email_args['affiliate_link']      = afwc_get_affiliate_url( trailingslashit( home_url() ), $pname, $affiliate_identifier );
			$shop_page_id                            = wc_get_page_id( 'shop' );
			$shop_page                               = ( ! empty( $shop_page_id ) && ( intval( $shop_page_id ) > 0 ) ) ? wc_get_page_permalink( 'shop' ) : '';
			$this->email_args['shop_page']           = apply_filters( 'afwc_shop_url', $shop_page, array( 'source' => $this ) );
			$this->email_args['affiliate_id']        = $affiliate_identifier;
			$this->email_args['is_auto_approved']    = ! empty( $this->email_args['is_auto_approved'] ) ? $this->email_args['is_auto_approved'] : get_option( 'afwc_auto_add_affiliate', 'no' );

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
				'email'               => $this,
				'email_heading'       => is_callable( array( $this, 'get_heading' ) ) ? $this->get_heading() : '',
				'user_name'           => $this->email_args['user_name'],
				'affiliate_link'      => esc_url( $this->email_args['affiliate_link'] ),
				'affiliate_id'        => $this->email_args['affiliate_id'],
				'shop_page'           => $this->email_args['shop_page'],
				'my_account_afwc_url' => esc_url( $this->email_args['my_account_afwc_url'] ),
				'is_auto_approved'    => $this->email_args['is_auto_approved'],
				'additional_content'  => is_callable( array( $this, 'get_additional_content' ) ) ? $this->get_additional_content() : '',
			);
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
