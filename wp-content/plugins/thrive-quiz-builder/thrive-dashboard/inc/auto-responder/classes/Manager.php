<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class Thrive_Dash_List_Manager {
	public static $ADMIN_HAS_ERROR = false;

	public static $API_TYPES
		= array(
			'autoresponder' => 'Email Marketing',
			'webinar'       => 'Webinars',
			'other'         => 'Other',
			'recaptcha'     => 'Recaptcha',
			'social'        => 'Social',
			'sellings'      => 'Sales',
			'integrations'  => 'Integration Services',
			'email'         => 'Email Delivery',
			'storage'       => 'File Storage',
		);

	public static $AVAILABLE
		= array(
			'email'                => 'Thrive_Dash_List_Connection_Email',
			'activecampaign'       => 'Thrive_Dash_List_Connection_ActiveCampaign',
			'arpreach'             => 'Thrive_Dash_List_Connection_ArpReach',
			'aweber'               => 'Thrive_Dash_List_Connection_AWeber',
			'campaignmonitor'      => 'Thrive_Dash_List_Connection_CampaignMonitor',
			'constantcontact'      => 'Thrive_Dash_List_Connection_ConstantContact',
			'convertkit'           => 'Thrive_Dash_List_Connection_ConvertKit',
			'drip'                 => 'Thrive_Dash_List_Connection_Drip',
			'facebook'             => 'Thrive_Dash_List_Connection_Facebook',
			'get-response'         => 'Thrive_Dash_List_Connection_GetResponse',
			'google'               => 'Thrive_Dash_List_Connection_Google',
			'gotowebinar'          => 'Thrive_Dash_List_Connection_GoToWebinar',
			'hubspot'              => 'Thrive_Dash_List_Connection_HubSpot',
			'icontact'             => 'Thrive_Dash_List_Connection_iContact',
			'infusionsoft'         => 'Thrive_Dash_List_Connection_Infusionsoft',
			'klicktipp'            => 'Thrive_Dash_List_Connection_KlickTipp',
			'madmimi'              => 'Thrive_Dash_List_Connection_MadMimi',
			'mailchimp'            => 'Thrive_Dash_List_Connection_Mailchimp',
			'mailerlite'           => 'Thrive_Dash_List_Connection_MailerLite',
			'mailpoet'             => 'Thrive_Dash_List_Connection_MailPoet',
			'mailrelay'            => 'Thrive_Dash_List_Connection_MailRelay',
			'mautic'               => 'Thrive_Dash_List_Connection_Mautic',
			'ontraport'            => 'Thrive_Dash_List_Connection_Ontraport',
			'recaptcha'            => 'Thrive_Dash_List_Connection_ReCaptcha',
			'sendreach'            => 'Thrive_Dash_List_Connection_Sendreach',
			'sendgrid'             => 'Thrive_Dash_List_Connection_SendGrid',
			'sendinblue'           => 'Thrive_Dash_List_Connection_Sendinblue',
			'sendy'                => 'Thrive_Dash_List_Connection_Sendy',
			'sg-autorepondeur'     => 'Thrive_Dash_List_Connection_SGAutorepondeur',
			'twitter'              => 'Thrive_Dash_List_Connection_Twitter',
			'webinarjamstudio'     => 'Thrive_Dash_List_Connection_WebinarJamStudio',
			'wordpress'            => 'Thrive_Dash_List_Connection_Wordpress',
			'mailster'             => 'Thrive_Dash_List_Connection_Mailster',
			'sendfox'              => 'Thrive_Dash_List_Connection_Sendfox',
			'zoho'                 => 'Thrive_Dash_List_Connection_Zoho',

			/* notification manger - these are now included in the dashboard - services for email notifications */
			'awsses'               => 'Thrive_Dash_List_Connection_Awsses',
			'campaignmonitoremail' => 'Thrive_Dash_List_Connection_CampaignMonitorEmail',
			'mailgun'              => 'Thrive_Dash_List_Connection_Mailgun',
			'mandrill'             => 'Thrive_Dash_List_Connection_Mandrill',
			'mailrelayemail'       => 'Thrive_Dash_List_Connection_MailRelayEmail',
			'postmark'             => 'Thrive_Dash_List_Connection_Postmark',
			'sendgridemail'        => 'Thrive_Dash_List_Connection_SendGridEmail',
			'sendinblueemail'      => 'Thrive_Dash_List_Connection_SendinblueEmail',
			'sparkpost'            => 'Thrive_Dash_List_Connection_SparkPost',
			'sendowl'              => 'Thrive_Dash_List_Connection_SendOwl',
			'sendlane'             => 'Thrive_Dash_List_Connection_Sendlane',
			'zoom'                 => 'Thrive_Dash_List_Connection_Zoom',
			'everwebinar'          => 'Thrive_Dash_List_Connection_EverWebinar',

			/* integrations services */
			'zapier'               => 'Thrive_Dash_List_Connection_Zapier',

			/* File Storage */
			'google_drive'         => 'Thrive_Dash_List_Connection_FileUpload_GoogleDrive',
			'dropbox'              => 'Thrive_Dash_List_Connection_FileUpload_Dropbox',
		);

	private static $_available = array();

	/**
	 * get a list of all available APIs
	 *
	 * @param bool  $onlyConnected
	 * @param array $exclude_types
	 * @param bool  $onlyNames
	 *
	 * @return array
	 */
	public static function getAvailableAPIs( $onlyConnected = false, $exclude_types = array(), $onlyNames = false ) {
		$lists = array();

		$credentials = self::credentials();

		foreach ( self::available() as $key => $api ) {
			/** @var Thrive_Dash_List_Connection_Abstract $instance */
			if ( ! class_exists( $api ) ) {//for cases when Mandril is not updated in TL
				continue;
			}

			$instance = self::connectionInstance( $key, isset( $credentials[ $key ] ) ? $credentials[ $key ] : array() );
			if ( ! $instance ) {
				continue;
			}
			if ( ( $onlyConnected && empty( $credentials[ $key ] ) ) || in_array( $instance::getType(), $exclude_types, true ) ) {
				continue;
			}
			/**
			 * Allow custom isConnected implementations
			 */
			if ( $onlyConnected && ! $instance->isConnected() ) {
				continue;
			}
			if ( $onlyNames ) {
				$lists[ $key ] = $instance->getTitle();
			} else {
				$lists[ $key ] = $instance;
			}
		}

		return $lists;
	}

	/**
	 * Build custom fields for all available connections
	 */
	public static function getAvailableCustomFields() {

		$custom_fields = array();
		$apis          = self::getAvailableAPIs(
			true,
			array(
				'email',
				'social',
			)
		);

		/**
		 * @var string                               $api_name
		 * @var Thrive_Dash_List_Connection_Abstract $api
		 */
		foreach ( $apis as $api_name => $api ) {
			$custom_fields[ $api_name ] = $api->get_api_custom_fields( array(), false, true );
		}

		return $custom_fields;
	}

	/**
	 * Get the harcoded mapper from the first connected API
	 *
	 * @return array
	 */
	public static function getCustomFieldsMapper() {

		$mapper = array();
		$apis   = self::getAvailableAPIs(
			true,
			array(
				'email',
				'social',
			)
		);

		/**
		 * @var Thrive_Dash_List_Connection_Abstract $api
		 */
		foreach ( $apis as $api ) {

			if ( ! empty( $mapper ) ) {
				break;
			}

			$mapper = $api->getMappedCustomFields();
		}

		return $mapper;
	}

	/**
	 * get a list of all available APIs by type
	 *
	 * @param bool         $onlyConnected if true, it will return only APIs that are already connected
	 * @param string|array $include_types exclude connection by their type
	 *
	 * @return array Thrive_Dash_List_Connection_Abstract[]
	 */
	public static function getAvailableAPIsByType( $onlyConnected = false, $include_types = array() ) {

		if ( ! is_array( $include_types ) ) {
			$include_types = array( $include_types );
		}
		$lists = array();

		$credentials = self::credentials();

		foreach ( self::available() as $key => $api ) {
			/** @var Thrive_Dash_List_Connection_Abstract $instance */
			$instance = self::connectionInstance( $key, isset( $credentials[ $key ] ) ? $credentials[ $key ] : array() );
			if ( ( $onlyConnected && empty( $credentials[ $key ] ) ) || ! in_array( $instance::getType(), $include_types, true ) ) {
				continue;
			}

			if ( $onlyConnected && ! $instance->isConnected() ) {
				continue;
			}

			$lists[ $key ] = $instance;
		}

		return $lists;
	}

	/**
	 * fetch the connection credentials for a specific connection (or for all at once)
	 *
	 * @param string $key if empty, all will be returned
	 *
	 * @return array
	 */
	public static function credentials( $key = '' ) {
		$details = get_option( 'thrive_mail_list_api', array() );

		/**
		 * Make sure that email connection is always available
		 */
		if ( ! array_key_exists( 'email', $details ) ) {
			$details['email'] = array( 'connected' => true );
		}

		if ( empty( $key ) ) {
			return $details;
		}

		if ( ! isset( $details[ $key ] ) ) {
			return array();
		}

		return $details[ $key ];
	}

	/**
	 * save the credentials for an instance
	 *
	 * @param Thrive_Dash_List_Connection_Abstract $instance
	 */
	public static function save( $instance ) {
		$existing                        = self::credentials();
		$existing[ $instance->getKey() ] = $instance->getCredentials();

		/**
		 * Invalidate cache for Email Connection
		 * - so that Email connection get to know the new email provider
		 */
		if ( $instance::getType() === 'email' ) {
			delete_transient( 'tve_api_data_email' );
		}

		update_option( 'thrive_mail_list_api', $existing );
	}

	/**
	 *
	 * factory method for a connection instance
	 *
	 * @param string     $key
	 * @param bool|array $savedCredentials
	 *
	 * @return Thrive_Dash_List_Connection_Abstract
	 */
	public static function connectionInstance( $key, $savedCredentials = false ) {
		$available = self::available();

		if ( ! isset( $available[ $key ] ) ) {
			return null;
		}

		/** @var Thrive_Dash_List_Connection_Abstract $instance */
		$instance = new $available[ $key ]( $key );

		if ( false !== $savedCredentials ) {
			$instance->setCredentials( $savedCredentials );
		} else {
			$instance->setCredentials( self::credentials( $key ) );
		}

		return $instance;
	}

	/**
	 * saves a message to be displayed on the next request
	 *
	 * @param string $type
	 * @param string $message
	 */
	public static function message( $type, $message ) {
		if ( $type == 'error' ) {
			self::$ADMIN_HAS_ERROR = true;
		}
		$messages          = get_option( 'tve_api_admin_notices', array() );
		$messages[ $type ] = $message;

		update_option( 'tve_api_admin_notices', $messages );
	}

	/**
	 * reads out all messages (success / error from the options table)
	 */
	public static function flashMessages() {
		$GLOBALS['thrive_list_api_message'] = get_option( 'tve_api_admin_notices', array() );

		delete_option( 'tve_api_admin_notices' );
	}

	/**
	 * transform the array of connections into one input string
	 * this is a really simple encrypt system, is there any need for a more complicated one ?
	 *
	 * @param array $connections a list of key => value pairs (api => list)
	 *
	 * @return string
	 */
	public static function encodeConnectionString( $connections = array() ) {
		return base64_encode( serialize( $connections ) );
	}

	/**
	 * transform the $string into an array of connections
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public static function decodeConnectionString( $string ) {
		if ( empty( $string ) ) {
			return array();
		}
		$string = @base64_decode( $string );
		if ( empty( $string ) ) {
			return array();
		}
		$data = @unserialize( $string );

		return empty( $data ) ? array() : tve_sanitize_data_recursive( $data, 'sanitize_textarea_field' );
	}

	public static function toJSON( $APIs = array() ) {
		if ( ! is_array( $APIs ) || empty( $APIs ) ) {
			return json_encode( array() );
		}

		$list = array();

		foreach ( $APIs as $key => $instance ) {

			/** @var $instance Thrive_Dash_List_Connection_Abstract */
			if ( ! is_subclass_of( $instance, 'Thrive_Dash_List_Connection_Abstract' ) ) {
				continue;
			}

			$list[] = $instance->prepareJSON();
		}

		return json_encode( $list );
	}

	public static function available() {
		if ( ! self::$_available ) {
			self::$_available = apply_filters( "tve_filter_available_connection", self::$AVAILABLE );
		}

		return self::$_available;
	}
}
