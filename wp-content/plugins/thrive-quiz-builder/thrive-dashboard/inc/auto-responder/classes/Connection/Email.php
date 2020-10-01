<?php
/**
 * Thrive Themes - https://thrivethemes.com
 */

/**
 * Handle Email connection for Lead Generation.
 * This connection will always be available but won't be displayed in TD, because no action is needed for it
 *
 * Class Thrive_Dash_List_Connection_Email
 */
class Thrive_Dash_List_Connection_Email extends Thrive_Dash_List_Connection_Abstract {

	/**
	 * @return string the API connection title
	 */
	public function getTitle() {
		return 'Email';
	}

	/**
	 * Remove the api from dashboard
	 *
	 * @return bool
	 */
	public function isRelated() {
		return true;
	}

	/**
	 * Email connection will always be available
	 *
	 * @return bool
	 */
	public function isConnected() {
		return true;
	}

	/**
	 * Noting to do here
	 */
	public function outputSetupForm() {
	}

	/**
	 * @return true
	 */
	public function readCredentials() {

		$this->setCredentials( array( 'connected' => true ) );
		$this->save();

		return true;
	}

	/**
	 * @return bool
	 */
	public function testConnection() {
		return true;
	}

	/**
	 * Send the emails on lg submit, the name may be a bit inappropriate, but we have to stay with the general implementation
	 *
	 * @param array $list_identifier
	 * @param array $arguments
	 *
	 * @return array|string
	 */
	public function addSubscriber( $list_identifier, $arguments ) {

		if ( ! is_array( $list_identifier ) ) {
			return __( 'Failed to send email', TVE_DASH_TRANSLATE_DOMAIN );
		}

		$response = array();

		foreach ( $list_identifier as $connection ) {

			$_list = $connection['list'];

			if ( 'own_site' === $_list ) {
				$_list = 'email';
			}

			$_instance = Thrive_Dash_List_Manager::connectionInstance( $_list );

			if ( ! method_exists( $_instance, 'sendMultipleEmails' ) ) {
				continue;
			}

			$connection = array_merge( $connection, $arguments );

			$response[ $_instance->getKey() ] = $_instance->sendMultipleEmails( $this->prepare_data_for_email_service( $connection ) );
		}

		return $response;
	}

	protected function _apiInstance() {
	}

	protected function _getLists() {
		return $this->get_connected_email_providers();
	}

	/**
	 * Get connected email providers
	 *
	 * @return array
	 */
	public function get_connected_email_providers() {

		$providers = array(
			array(
				'id'   => 'own_site',
				'name' => 'Send emails from this site',
			),
		);

		foreach ( Thrive_Dash_List_Manager::getAvailableAPIsByType( true, array( 'email' ) ) as $email_provider ) {

			/**
			 * @var Thrive_Dash_List_Connection_Abstract $email_provider
			 */
			$providers[] = array(
				'id'   => $email_provider->getKey(),
				'name' => $email_provider->getTitle(),
			);
		}

		return $providers;
	}

	/**
	 * @param array $arguments
	 *
	 * @return bool
	 */
	public function sendMultipleEmails( $arguments ) {
		$headers = 'Content-Type: text/html; charset=UTF-8 ' . "\r\n"
		           . 'From: ' . $arguments['from_name'] . ' <' . $arguments['from_email'] . ' > ' . "\r\n"
		           . 'Reply-To: ' . $arguments['reply_to'] . "\r\n"
		           . 'CC: ' . implode( ', ', $arguments['cc'] ) . "\r\n"
		           . 'BCC: ' . implode( ', ', $arguments['bcc'] ) . "\r\n";

		$email_sent = wp_mail(
			$arguments['emails'],
			$arguments['subject'],
			$arguments['html_content'],
			$headers
		);
		/* Send confirmation email */
		if ( $email_sent && $arguments['send_confirmation'] ) {

			$headers = 'Content-Type: text/html; charset=UTF-8 ' . "\r\n"
			           . 'From: ' . $arguments['from_name'] . ' <' . $arguments['from_email'] . ' > ' . "\r\n"
			           . 'Reply-To: ' . $arguments['reply_to'] . "\r\n";

			$email_sent = wp_mail(
				$arguments['sender_email'],
				$arguments['confirmation_subject'],
				$arguments['confirmation_html'],
				$headers
			);
		}

		return $email_sent;
	}

	/**
	 * Get any extra settings needed by the api
	 *
	 * @param array $arguments
	 *
	 * @return array
	 */
	public function get_extra_settings( $arguments = array() ) {

		$response = array();

		foreach ( Thrive_Dash_List_Manager::getAvailableAPIsByType( true, array( 'email' ) ) as $email_provider ) {

			/**
			 * @var Thrive_Dash_List_Connection_Abstract $email_provider
			 */
			$response[ $email_provider->getKey() ] = array(
				'from_email' => $email_provider->get_email_param(),
			);
		}

		return $response;
	}

	/**
	 * Prepare data for email service
	 *
	 * @param array $arguments
	 *
	 * @return array
	 */
	public function prepare_data_for_email_service( $arguments ) {
		$emails = array_map(
			function ( $item ) {
				return sanitize_email( trim( $item ) );
			},
			explode( ',', $arguments['to'] )
		);

		$cc  = array();
		$bcc = array();

		if ( ! empty( $arguments['cc'] ) ) {
			$cc = array_map(
				function ( $item ) {
					return sanitize_email( trim( $item ) );
				},
				explode( ',', $arguments['cc'] )
			);
		}

		if ( ! empty( $arguments['bcc'] ) ) {
			$bcc = array_map(
				function ( $item ) {
					return sanitize_email( trim( $item ) );
				},
				explode( ',', $arguments['bcc'] )
			);
		}


		$confirmation_html = '';
		$send_confirmation = false;
		if ( ! empty( $arguments['send_confirmation_email'] ) && $arguments['send_confirmation_email'] ) {
			$confirmation_html = $this->replace_shortcodes( $arguments['email_confirmation_message'], $arguments );
			$send_confirmation = true;
		}

		$data = array(
			'emails'               => $emails,
			'subject'              => sanitize_text_field( $arguments['email_subject'] ),
			'from_name'            => sanitize_text_field( $arguments['from_name'] ),
			'from_email'           => sanitize_email( $arguments['from_email'] ),
			'html_content'         => $this->replace_shortcodes( $arguments['email_message'], $arguments ),
			'reply_to'             => sanitize_email( $arguments['email'] ),
			'bcc'                  => $bcc,
			'cc'                   => $cc,
			'send_confirmation'    => $send_confirmation,
			'confirmation_html'    => $confirmation_html,
			'confirmation_subject' => sanitize_text_field( $arguments['email_confirmation_subject'] ),
			'sender_email'         => sanitize_email( trim( $arguments['email'] ) ),
		);

		/**
		 * Allow filter email output
		 *
		 * @param array $data
		 * @param array $arguments
		 */
		return apply_filters( 'tve_dash_email_data', $data, $arguments );
	}

	/**
	 * Get form fields of the form
	 *
	 * @param $message
	 * @param $args
	 * @param $time
	 *
	 * @return string
	 */
	public function get_email_fields( $message, $args, $time ) {
		$has_shortcode = strpos( $args['email_message'], '[ form_fields ]' );
		if ( strpos( $message, '[all_form_fields]' ) !== false ) {
			$has_shortcode = true;
		}

		ob_start();

		include dirname( dirname( dirname( __FILE__ ) ) ) . '/views/includes/email.php';

		$html = ob_get_clean();

		$html = $html . $this->generate_custom_fields_html( $args );
		$html = preg_replace( "/[\r\n]+/", "", $html );

		return $html;
	}

	/**
	 * Get all custom fields from request args
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function _get_custom_fields( $args ) {
		$mapping         = unserialize( base64_decode( $args['tve_mapping'] ) );
		$apis            = Thrive_Dash_List_Manager::getAvailableAPIsByType( true, array( 'email', 'other' ) );
		$custom_fields   = array();
		$excluded_fields = array( 'name', 'email', 'phone' );

		foreach ( $apis as $api ) {
			/** @var Thrive_Dash_List_Connection_Abstract $api */

			$cf = $api->get_custom_fields();
			$cf = wp_list_pluck( $cf, 'id' );

			$custom_fields = array_merge( $custom_fields, $cf );
		}

		$custom_fields = array_unique( $custom_fields );
		$custom_fields = array_filter(
			$custom_fields,
			function ( $field ) use ( $excluded_fields, $args ) {
				if ( ! in_array( $field, $excluded_fields ) && array_key_exists( $field, $args ) ) {
					return $field;
				}
			}
		);
		$custom_fields = array_merge( $custom_fields, array_keys( $mapping ) );

		return $custom_fields;
	}

	/**
	 * Generate the html for custom fields added in lg
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function generate_custom_fields_html( $args ) {

		$html   = '';
		$labels = ! empty( $args['tve_labels'] ) ? unserialize( base64_decode( $args['tve_labels'] ) ) : array();

		foreach ( $this->_get_custom_fields( $args ) as $field ) {
			$label = ! empty( $labels[ $field ] ) ? sanitize_text_field( $labels[ $field ] ) : __( 'Extra Data', TVE_DASH_TRANSLATE_DOMAIN );

			if ( strpos( $field, 'textarea' ) !== false ) { /* preserve textarea formatting */
				$value = ! empty( $args[ $field ] ) ? sanitize_textarea_field( $args[ $field ] ) : '';
				$value = str_replace( ' ', '&nbsp;', $value );
			} else {
				$field = str_replace( '[]', '', $field );
				if ( ! empty( $args[ $field ] ) ) {
					$args[ $field ] = $this->processField( $args[ $field ] );
				}
				$value = ! empty( $args[ $field ] ) ? sanitize_text_field( $args[ $field ] ) : '';
			}

			$value = stripslashes( nl2br( $value ) );

			/**
			 * Filters a field value sent in the email message.
			 *
			 * @param string $value value to be sent in the email message
			 * @param string $field Field name that's being processed
			 * @param array  $args  form submission data
			 *
			 * @return string
			 */
			$value = apply_filters( 'thrive_email_message_field', $value, $field, $args );
			if ( 'password' === $field || 'confirm_password' === $field ) {
				$value = '******';
			}

			$_html = '<b>' . $label . ':</b> <span>' . $value . '</span><br>';

			$html .= $_html;
		}

		return $html;
	}

	public function replace_shortcodes( $message, $args ) {
		$timezone    = get_option( 'gmt_offset' );
		$time        = date( 'H:i', time() + 3600 * ( $timezone + date( 'I' ) ) );
		$first_name  = empty( $args['name'] ) ? '' : $this->_getNameParts( $args['name'] )[0];
		$fields_html = $this->get_email_fields( $message, $args, $time );

		$to_replace = array(
			'[all_form_fields]',
			'[ form_fields ]',
			'[wp_site_title]',
			'[form_url_slug]',
			'[first_name]',
			'[user_email]',
			'[phone]',
			'[date]',
			'[time]',
			'[page_url]',
			'[ip_address]',
			'[device_settings]',
		);
		$values     = array(
			$fields_html,
			$fields_html,
			wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
			trim( parse_url( $args['url'], PHP_URL_PATH ), '/' ),
			$first_name,
			$args['email'],
			$args['phone'],
			date_i18n( 'jS F, Y' ),
			$time,
			$args['url'],
			tve_dash_get_ip(),
			htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] ),
		);

		/**
		 * Add custom fields to the shortcodes to be replaced in messages
		 */
		foreach ( $this->_get_custom_fields( $args ) as $field ) {

			$to_replace[] = '[' . $field . ']';
			$field        = str_replace( '[]', '', $field );
			if ( ! empty( $args[ $field ] ) ) {
				$args[ $field ] = $this->processField( $args[ $field ] );
			}
			$value = ! empty( $args[ $field ] ) ? sanitize_textarea_field( $args[ $field ] ) : '';
			$value = stripslashes( nl2br( str_replace( ' ', '&nbsp;', $value ) ) );

			$values[] = $value;
		}

		$message = str_replace( $to_replace, $values, $message );

		/**
		 * Filter the email message being sent.
		 *
		 * @param string $message
		 * @param array  $args submitted post data
		 *
		 * @return string
		 */
		$message = apply_filters( 'thrive_api_email_message', $message, $args );

		return nl2br( $message );
	}
}
