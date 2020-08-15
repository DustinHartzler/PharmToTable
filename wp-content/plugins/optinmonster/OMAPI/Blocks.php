<?php
/**
 * Gutenberg Blocks registration class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gutenberg Blocks registration class.
 *
 * @since 1.9.10
 */
class OMAPI_Blocks {

	/**
	 * Holds the class object.
	 *
	 * @since 1.9.10
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.9.10
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.9.10
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.9.10
	 */
	public function __construct() {

		if ( function_exists( 'register_block_type' ) ) {

			// Set our object.
			$this->set();
			$this->register_blocks();

			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		}

	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.9.10
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Register OptinMonster Gutenberg blocks on the backend.
	 *
	 * @since 1.9.10
	 */
	public function register_blocks() {
		register_block_type(
			'optinmonster/campaign-selector',
			array(
				'attributes'      => array(
					'slug'        => array(
						'type' => 'string',
					),
					'followrules' => array(
						'type' => 'boolean',
					),
				),
				// 'editor_style'    => $this->base->plugin_slug . '-gutenberg-campaign-selector',
				'render_callback' => array( $this, 'get_output' ),
			)
		);
	}

	/**
	 * Load OptinMonster Gutenberg block scripts.
	 *
	 * @since 1.9.10
	 */
	public function enqueue_block_editor_assets() {
		$version   = $this->base->asset_version();
		$script_id = $this->base->plugin_slug . '-gutenberg-campaign-selector';

		wp_enqueue_style( $this->base->plugin_slug . '-blocks-admin', $this->base->url . 'assets/dist/css/blocks-admin.min.css', array(), $version );

		wp_enqueue_script(
			$script_id,
			$this->base->url . 'assets/dist/js/campaign-selector.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			$version,
			true
		);

		// For translation of strings.
		$i18n                = array(
			'title'             => esc_html__( 'OptinMonster Campaign', 'optin-monster-api' ),
			'description'       => esc_html__( 'Select &amp; display one of your OptinMonster inline Campaigns.', 'optin-monster-api' ),
			'campaign_select'   => esc_html__( 'Select a Campaign', 'optin-monster-api' ),
			'campaign_settings' => esc_html__( 'OptinMonster Settings', 'optin-monster-api' ),
			'campaign_selected' => esc_html__( 'Campaign', 'optin-monster-api' ),
			'followrules_label' => esc_html__( 'Use Output Settings' ),
			'followrules_help'  => esc_html__( 'Ensure this campaign follows any conditions you\'ve selected in its %s', 'optin-monster-api' ),
			'output_settings'   => esc_html__( 'Output Settings', 'optin-monster-api' ),
			'no_campaigns'      => esc_html__( 'No inline campaigns available!', 'optin-monster-api' ),
			'no_campaigns_help' => esc_html__( 'Create an inline campaign in order to display them in your WordPress posts/pages. Alternatively, use a Monster Link to trigger another campaign type.', 'optin-monster-api' ),
			'found_error'       => esc_html__( 'An error was encountered', 'optin-monster-api' ),
		);
		$i18n['description'] = html_entity_decode( $i18n['description'] );

		$campaigns = $this->base->get_optins();
		wp_localize_script(
			$script_id,
			'OMAPI',
			array(
				'logoUrl'   => $this->base->url . 'assets/css/images/logo-om.png',
				'i18n'      => $i18n,
				'campaigns' => ! empty( $campaigns ) ? $campaigns : array(),
				'post'      => get_post(),
				'omEnv'     => defined( 'OPTINMONSTER_ENV' ) ? OPTINMONSTER_ENV : '',
				'apiUrl'    => esc_url_raw( $this->base->get_api_url() ),
				'omUserId'  => $this->base->get_option( 'userId' ),
				'editUrl'   => $this->base->menu->get_settings_link(
					'optins',
					array(
						'optin_monster_api_action' => 'edit',
						'optin_monster_api_id'     => '%d',
					)
				),
			)
		);
	}

	/**
	 * Get form HTML to display in a OptinMonster Gutenberg block.
	 *
	 * @param array $atts Attributes passed by OptinMonster Gutenberg block.
	 *
	 * @since 1.9.10
	 *
	 * @return string
	 */
	public function get_output( $atts ) {
		$is_rest  = defined( 'REST_REQUEST' ) && REST_REQUEST;
		$context  = ! empty( $_REQUEST['context'] ) ? sanitize_text_field( $_REQUEST['context'] ) : '';
		$is_gutes = $is_rest && 'edit' === $context;

		// Our Guten-block handles the embed output manually.
		if ( $is_gutes ) {
			return;
		}

		$output = $this->base->shortcode->shortcode( $atts );

		if (
			! empty( $output )
			&& ! wp_script_is( $this->base->plugin_slug . '-api-script', 'enqueued' )
		) {

			// Need to enqueue the base api script.
			$this->base->output->api_script();
		}

		// Just return the shortcode output to the frontend.
		return $output;
	}

}
