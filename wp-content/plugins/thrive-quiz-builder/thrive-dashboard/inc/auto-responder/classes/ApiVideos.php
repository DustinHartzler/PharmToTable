<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class ApiVideos {

	/**
	 * @var string
	 */
	private $_ttw_api_url_transient = 'ttw_api_urls';

	/**
	 * @var string
	 */
	private $_ttw_url;

	/**
	 * Transient lifetime [1 day in seconds]
	 *
	 * @var int
	 */
	protected $_cache_life_time = 86400;

	/**
	 * Used for obfuscation
	 *
	 * @var array
	 */
	private $_randomize
		= array(
			'a'  => 'F',
			'b'  => 'g',
			'c'  => 'R',
			'd'  => '6j',
			'e'  => 'k9t',
			'f'  => '#U',
			'g'  => 'x',
			'h'  => 'E',
			'i'  => '_',
			'j'  => '6',
			'k'  => '^',
			'l'  => 'Y',
			'm'  => '7hI',
			'n'  => 'm',
			'o'  => 'pI',
			'u'  => '5',
			'1'  => '2W7',
			'2'  => 'g',
			'9'  => 'T',
			'5'  => '3',
			':'  => 'p',
			'/'  => 'u',
			't'  => 'I',
			'p'  => 'o',
			'x'  => 'a',
			'y'  => 'e',
			'z'  => 'i',
			'w'  => 'u',
			'6'  => 'h',
			'U'  => 't',
			'89' => '/',
		);

	/**
	 * Used in case of TTW API call failed [/api_videos endpoint]
	 *
	 * @var array
	 */
	private $_fallback_urls
		= array(
			'mailchimp'        => '//fast.wistia.net/embed/iframe//e9ct1imq5x?popover=true',
			'aweber'           => '//fast.wistia.net/embed/iframe/cc3ycaycud?popover=true',
			'getresponse'      => '//fast.wistia.net/embed/iframe/t4ilbqeaw6?popover=true',
			'mailpoet'         => '//fast.wistia.net/embed/iframe/53gudkrboc?popover=true',
			'wordpressaccount' => '//fast.wistia.net/embed/iframe/91e1nd0o4b?popover=true',
			'ontraport'        => '//fast.wistia.net/embed/iframe/kuen8af235?popover=true',
			'icontact'         => '//fast.wistia.net/embed/iframe/xp77k6q3oe?popover=true',
			'convertkit'       => '//fast.wistia.net/embed/iframe/s049f4xon1?popover=true',
			'activecampaign'   => '//fast.wistia.net/embed/iframe/g0h6g65o3l?popover=true',
			'sendreach'        => '//fast.wistia.net/embed/iframe/6lv0bfvyk3?popover=true',
			'klicktipp'        => '//fast.wistia.net/embed/iframe/913ckkr5p8?popover=true',
			'sendy'            => '//fast.wistia.net/embed/iframe/r8tuoik2p8?popover=true',
			'arpreach'         => '//fast.wistia.net/embed/iframe/14lpsht7zc?popover=true',
			'drip'             => '//fast.wistia.net/embed/iframe/bkowp50zzc?popover=true',
			'constantcontact'  => '//fast.wistia.net/embed/iframe/dk1pthoaw0?popover=true',
			'madmimi'          => '//fast.wistia.net/embed/iframe/sc3okkry3u?popover=true',
			'webinarjamstudio' => '//fast.wistia.net/embed/iframe/ln7sk2etzv?popover=true',
			'gotowebinar'      => '//fast.wistia.net/embed/iframe/gapqjdylzm?popover=true',
			'hubspot'          => '//fast.wistia.net/embed/iframe/eg3vm8m5rk?popover=true',
			'sendinblue'       => '//fast.wistia.net/embed/iframe/ar3wj9h5b7?popover=true',
			'mandrill'         => '//fast.wistia.net/embed/iframe/s4s27t2vgf?popover=true',
			'postmark'         => '//fast.wistia.net/embed/iframe/r9qavwnfpk?popover=true',
			'infusionfost'     => '//fast.wistia.net/embed/iframe/0ycr56huu9?popover=true',
			'recaptcha'        => '//fast.wistia.net/embed/iframe/1slctvv3jx?popover=true',
			'sparkpost'        => '//fast.wistia.net/embed/iframe/b2de3cck54?popover=true',
			'mailgun'          => '//fast.wistia.net/embed/iframe/5rg7vysd8p?popover=true',
			'amazonses'        => '//fast.wistia.net/embed/iframe/rhmub3bqbd?popover=true',
			'mailerlite'       => '//fast.wistia.net/embed/iframe/s5ly5fx8jh?popover=true/',
			'mautic'           => '//fast.wistia.net/embed/iframe/3gxaruhmpx?popover=true/',
			'campaignmonitor'  => '//fast.wistia.net/embed/iframe/8eevtq5ww9?popover=true',
			'facebook'         => '//fast.wistia.net/embed/iframe/7azaizklnb?popover=true',
			'google'           => '//fast.wistia.net/embed/iframe/mf6yx17kj5?popover=true',
			'twitter'          => '//fast.wistia.net/embed/iframe/5vukjsb2eh?popover=true',
			'mailrelay'        => '//fast.wistia.net/embed/iframe/1xcg9wzafa?popover=true',
			'sendgrid'         => '//fast.wistia.net/embed/iframe/fw18w215rg?popover=true',
			'sgautorepondeur'  => '//fast.wistia.net/embed/iframe/jvup3g7em4?popover=true',
			'zoom'             => '//fast.wistia.net/embed/iframe/zqmpobvt39?popover=true',
			'sendlane'         => '//fast.wistia.net/embed/iframe/fj7e28vbmf?popover=true',
			'google_drive'     => '//fast.wistia.net/embed/iframe/q2u7970z2q?popover=true',
			'dropbox'          => '//fast.wistia.net/embed/iframe/wzepho8uws?popover=true',
		);

	/**
	 * ApiVideos constructor.
	 */
	public function __construct() {

		// URLs based on env
		$this->_set_urls();

		// Check and set api videos URLs transient and call TTW API for them
		$this->_check_videos_transient();
	}

	/**
	 * URLs setter
	 */
	private function _set_urls() {

		$this->_ttw_url = esc_url( defined( 'THRV_ENV' ) && is_string( THRV_ENV ) ? THRV_ENV : 'https://thrivethemes.com' );
	}

	/**
	 * Obfuscation
	 *
	 * @param      $string
	 * @param bool $flip
	 *
	 * @return string
	 */
	protected function _obfuscate( $string, $flip = false ) {

		if ( $flip ) {
			$this->_randomize = array_flip( $this->_randomize );
		}

		return (string) str_replace( array_keys( $this->_randomize ), $this->_randomize, $string );
	}

	/**
	 * @return bool
	 */
	protected function _build_videos_transient() {

		$headers = array(
			'Content-Type' => 'application/json',
			'website'      => get_site_url(),
			'tpm'          => 'no',
		);

		$tpm_data = get_option( 'tpm_connection', array() );

		// Build auth header for users with TPM [token received from TTW API]
		if ( ! empty( $tpm_data ) && ! empty( $tpm_data['ttw_salt'] ) ) {

			$headers['Authorization'] = $tpm_data['ttw_salt'];
			$headers['userid']        = ! empty( $tpm_data['ttw_id'] ) ? $tpm_data['ttw_id'] : '';
			$headers['tpm']           = 'yes';
		}

		// Build auth header for users without TPM
		if ( empty( $headers['Authorization'] ) ) {

			$headers['Authorization'] = $this->_obfuscate( get_site_url() );
			$headers['userid']        = $this->_obfuscate( get_site_url() . md5( date( 'Y-m-d' ) ), true );
		}

		$args = array(
			'headers'   => $headers,
			'sslverify' => false,
			'timeout'   => 20,
		);

		$request = wp_remote_get( $this->_ttw_url . '/api/v1/public/api_videos', $args );
		$body    = json_decode( wp_remote_retrieve_body( $request ) );

		if ( $body && ! empty( $body->urls ) ) {

			return set_transient( $this->_ttw_api_url_transient, (array) $body->urls, $this->_cache_life_time );
		}

		return set_transient( $this->_ttw_api_url_transient, (array) $this->_fallback_urls, $this->_cache_life_time );
	}

	/**
	 * Verify is transient is set or set it
	 */
	protected function _check_videos_transient() {

		$video_urls_transient = get_transient( $this->_ttw_api_url_transient );

		if ( ! $video_urls_transient ) {
			$this->_build_videos_transient();
		}
	}
}
