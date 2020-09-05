<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-winner-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-entry-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-aweber.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-mailchimp.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-getresponse.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-campaignmonitor.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-convertkit.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-activecampaign.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-sendfox.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-debug.php';

if (!class_exists('EDD_SL_Plugin_Updater')) {
	require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'EDD_SL_Plugin_Updater.php';
}

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-http.php';

/**
 * @package     KS_Giveaways_Admin
 */
class KS_Giveaways_Admin
{
	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	private function __construct()
	{
		$plugin = KS_Giveaways::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
		add_action('plugin_action_links_ks-giveaways/ks-giveaways.php', array($this, 'add_plugin_links'));

		add_action('admin_notices', array($this, 'admin_notices'));
		add_action('current_screen', array($this, 'current_screen'));

		add_filter('manage_'.KS_GIVEAWAYS_POST_TYPE.'_posts_columns', array($this, 'set_contest_columns'), 99999);
		add_filter('manage_'.KS_GIVEAWAYS_POST_TYPE.'_posts_custom_column', array($this, 'display_contest_column'), 10, 2);
		add_filter('get_pages', array($this, 'add_giveaways_to_dropdown'), 10, 2);

		add_filter('bulk_post_updated_messages', array($this, 'bulk_post_updated_messages'));
		add_filter('post_updated_messages', array($this, 'post_updated_messages'));
		add_filter('redirect_post_location', array($this, 'redirect_post_location'), 10, 2);
		add_action('dbx_post_advanced', array($this, 'dbx_post_advanced'));
		add_filter('post_row_actions', array($this, 'set_page_row_actions'), 10, 2);
		add_filter('views_edit-'.KS_GIVEAWAYS_POST_TYPE, array($this, 'set_page_views'));
		add_filter('default_hidden_meta_boxes', array($this, 'default_hidden_meta_boxes'), 10, 2);
		add_action('admin_action_duplicate_giveaway', array($this, 'duplicate_giveaway'));
	}

	/**
	 * Returns an instance of this class.
	 *
	 * @return  object    A single instance of this class.
	 */
	public static function get_instance()
	{
		if (null == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function add_giveaways_to_dropdown($pages, $r)
	{
		if (isset($r['name']) && $r['name'] == 'page_on_front') {
			$args = array('post_type' => KS_GIVEAWAYS_POST_TYPE);
			$items = get_posts($args);
			if ($items) {
				$pages = array_merge($pages, $items);
			}
		}

		return $pages;
	}

	public function add_plugin_links($links)
	{
		$settings_link = sprintf('<a href="%s">Settings</a>', admin_url('options-general.php?page=ks-giveaways-options'));
		$promo_link = '<a href="https://wordpress.kingsumo.com/" target="_blank" class="more-ks-plugins">More Plugins</a>';

		$links[] = $settings_link;
		$links[] = $promo_link;

		return $links;
	}

	/**
	 * Register and enqueue the admin stylesheet.
	 */
	public function enqueue_admin_styles()
	{
		$screen = get_current_screen();
		$screen = $screen ? $screen->id : null;

		// Only load CSS on KS Giveaways Admin screens
		if (strstr($screen, 'ks-giveaway') || strstr($screen, 'ks_giveaway')) {
			wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__), array(), KS_Giveaways::VERSION);
			wp_enqueue_style($this->plugin_slug . '-picker', plugins_url('assets/css/picker/default.css', __FILE__), array(), KS_Giveaways::VERSION);
			wp_enqueue_style($this->plugin_slug . '-picker-date', plugins_url('assets/css/picker/default.date.css', __FILE__), array(), KS_Giveaways::VERSION);
			wp_enqueue_style($this->plugin_slug . '-picker-time', plugins_url('assets/css/picker/default.time.css', __FILE__), array(), KS_Giveaways::VERSION);
			wp_enqueue_style('thickbox');
			wp_enqueue_style('media');
		}
	}

	/**
	 * Register and enqueue admin Javascript files.
	 */
	public function enqueue_admin_scripts()
	{
		// edit-ks_giveaway: Giveaways list
		// admin_page_ks-giveaways: Manage giveaway (action=view)
		// admin_page_ks-giveaways: View contestants (action=contestants)
		// ks_giveaway: Edit giveaway
		
		$screen = get_current_screen();
		$screen = $screen ? $screen->id : null;
		$action = isset($_GET['action']) ? $_GET['action'] : null;

		if ($screen == 'ks_giveaway') { // KS Giveaways Post New/Edit Screen
			if (! is_plugin_active('ultimate-member/index.php')) {
				// Ultimate member also includes this file on every admin page which causes Uncaught RangeError
				// Is this even needed anymore?
				wp_enqueue_script($this->plugin_slug . '-legacy', plugins_url('assets/js/legacy.js', __FILE__), array('jquery'), KS_Giveaways::VERSION);
			}

			wp_enqueue_script($this->plugin_slug . '-picker', plugins_url('assets/js/picker.js', __FILE__), array('jquery',$this->plugin_slug.'-legacy'), KS_Giveaways::VERSION);
			wp_enqueue_script($this->plugin_slug . '-picker-date', plugins_url('assets/js/picker.date.js', __FILE__), array('jquery',$this->plugin_slug.'-legacy'), KS_Giveaways::VERSION);
			wp_enqueue_script($this->plugin_slug . '-picker-time', plugins_url('assets/js/picker.time.js', __FILE__), array('jquery',$this->plugin_slug.'-legacy'), KS_Giveaways::VERSION);
			wp_enqueue_script($this->plugin_slug . '-angular', plugins_url('assets/js/angular.min.js', __FILE__), array(), KS_Giveaways::VERSION);
			wp_enqueue_script($this->plugin_slug . '-admin', plugins_url('assets/js/admin.js', __FILE__), array(), KS_Giveaways::VERSION);
			wp_enqueue_script('thickbox');
			wp_enqueue_media();
		}

		if ($screen == 'admin_page_ks-giveaways' and $action == 'view') { // Manage Giveaway View Only
			wp_enqueue_script($this->plugin_slug . '-editable', plugins_url('assets/js/jquery.jeditable.min.js', __FILE__), array('jquery'), KS_Giveaways::VERSION);
			wp_enqueue_script('thickbox');
			wp_enqueue_media();
		}

	}

	public function admin_init()
	{
		// upgrade checks
		KS_Giveaways::check_default_options();

		add_action('wp_ajax_ks_activate_giveaways_license', array($this, 'ajax_activate_license'));
		add_action('wp_ajax_ks_deactivate_giveaways_license', array($this, 'ajax_deactivate_license'));
		add_action('wp_ajax_ks_save_giveaways_winner_name', array($this, 'save_winner_name'));
		add_action('wp_ajax_ks_save_giveaways_winner_avatar', array($this, 'save_winner_avatar'));
		add_action('wp_ajax_ks_giveaways_test_services_subscription', array($this, 'ajax_test_services_subscription'));

		$this->updater = new EDD_SL_Plugin_Updater(
		  KS_GIVEAWAYS_EDD_URL,
		  KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'ks-giveaways.php',
		  array(
			  'version' => KS_GIVEAWAYS_EDD_VERSION,
			  'item_name' => KS_GIVEAWAYS_EDD_NAME,
			  'author' => KS_GIVEAWAYS_EDD_AUTHOR,
			  'license' => get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY)
		  )
		);

		$tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'general';

		add_action('add_meta_boxes_'.KS_GIVEAWAYS_POST_TYPE, array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_contest'));

		if ($tab == 'general') {
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_DRAW_MODE, array($this, 'sanitize_draw_mode'));
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_LICENSE_KEY, array($this, 'sanitize_license'));
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE, array($this, 'sanitize_url'));
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_TWITTER_VIA, array($this, 'sanitize_twitter'));
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_YOUTUBE_URL, 'esc_url');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_INSTAGRAM_URL, 'esc_url');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY, 'sanitize_text_field');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY, 'sanitize_text_field');

			add_settings_section('ks_giveaways_options', 'General Options', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_DRAW_MODE, 'Eligibility', array($this, 'input_draw_mode'), 'ks-giveaways-options', 'ks_giveaways_options');

			add_settings_section('ks_giveaways_license', 'License', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_LICENSE_KEY, 'License Key', array($this, 'input_license_key'), 'ks-giveaways-options', 'ks_giveaways_license');

			add_settings_section('ks_giveaways_captcha', 'Google reCAPTCHA', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY, 'Site Key', array($this, 'input_captcha_site_key'), 'ks-giveaways-options', 'ks_giveaways_captcha');
			add_settings_field(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY, 'Secret Key', array($this, 'input_captcha_secret_key'), 'ks-giveaways-options', 'ks_giveaways_captcha');

			add_settings_section('ks_giveaways_social', 'Social', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE, 'Facebook Page', array($this, 'input_facebook_page'), 'ks-giveaways-options', 'ks_giveaways_social');
			add_settings_field(KS_GIVEAWAYS_OPTION_TWITTER_VIA, 'Twitter Handle', array($this, 'input_twitter_via'), 'ks-giveaways-options', 'ks_giveaways_social');
			//add_settings_field(KS_GIVEAWAYS_OPTION_YOUTUBE_URL, 'YouTube Channel URL', array($this, 'input_youtube_url'), 'ks-giveaways-options', 'ks_giveaways_social');
			//add_settings_field(KS_GIVEAWAYS_OPTION_INSTAGRAM_URL, 'Instagram URL', array($this, 'input_instagram_url'), 'ks-giveaways-options', 'ks_giveaways_social');
		}

		if ($tab == 'email') {
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT, 'sanitize_text_field');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY, 'trim');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT, 'sanitize_text_field');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY, 'trim');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS, 'trim');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS, 'trim');

			add_settings_section('ks_giveaways_email_address', 'Email Addresses', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS, 'From Address', array($this, 'input_email_from_address'), 'ks-giveaways-options', 'ks_giveaways_email_address');
			add_settings_field(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS, 'Reply-To Address', array($this, 'input_email_replyto_address'), 'ks-giveaways-options', 'ks_giveaways_email_address');

			add_settings_section('ks_giveaways_entry_email', 'Entry Email Template', null, 'ks-giveaways-options');

			$mode = get_option(KS_GIVEAWAYS_OPTION_DRAW_MODE, 'all');
			if ($mode !== 'confirmed') {
				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS, array($this, 'sanitize_boolean'));
				add_settings_field(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS, 'Suppress Entry Email', array($this, 'input_entry_email_suppress'), 'ks-giveaways-options', 'ks_giveaways_entry_email');
			}

			add_settings_field(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT, 'Subject', array($this, 'input_entry_email_subject'), 'ks-giveaways-options', 'ks_giveaways_entry_email');
			add_settings_field(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY, 'Body', array($this, 'input_entry_email_body'), 'ks-giveaways-options', 'ks_giveaways_entry_email');

			add_settings_section('ks_giveaways_winner_email', 'Winner Email Template', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT, 'Subject', array($this, 'input_winner_email_subject'), 'ks-giveaways-options', 'ks_giveaways_winner_email');
			add_settings_field(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY, 'Body', array($this, 'input_winner_email_body'), 'ks-giveaways-options', 'ks_giveaways_winner_email');
		}

		if ($tab == 'settings') {
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME, array($this, 'sanitize_boolean'));

			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_STREET, 'sanitize_text_field');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_CITY, 'sanitize_text_field');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_STATE, 'sanitize_text_field');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY, 'sanitize_text_field');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_ZIP, 'sanitize_text_field');

			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE, array($this, 'sanitize_boolean'));

			add_settings_section('ks_giveaways_giveaways', 'Giveaways Options', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME, 'Ask for name', array($this, 'input_giveaways_ask_name'), 'ks-giveaways-options', 'ks_giveaways_giveaways');

			add_settings_section('ks_giveaways_address', 'Location', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_STREET, 'Street', array($this, 'input_address_street'), 'ks-giveaways-options', 'ks_giveaways_address');
			add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_CITY, 'City', array($this, 'input_address_city'), 'ks-giveaways-options', 'ks_giveaways_address');
			add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_STATE, 'State', array($this, 'input_address_state'), 'ks-giveaways-options', 'ks_giveaways_address');
			add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY, 'Country', array($this, 'input_address_country'), 'ks-giveaways-options', 'ks_giveaways_address');
			add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_ZIP, 'Postal Code', array($this, 'input_address_zip'), 'ks-giveaways-options', 'ks_giveaways_address');

			//add_settings_section('ks_giveaways_badge', 'Support KingSumo', null, 'ks-giveaways-options');
			//add_settings_field(KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE, 'Display KingSumo Badge', array($this, 'input_show_ks_badge'), 'ks-giveaways-options', 'ks_giveaways_badge');
		}

		if ($tab == 'advanced') {
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EXTRA_FOOTER, 'trim');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER, 'trim');

			add_settings_section('ks_giveaways_template', 'Template', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER, 'Extra Footer', array($this, 'input_extra_footer'), 'ks-giveaways-options', 'ks_giveaways_template');
			add_settings_field(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER, 'Extra Contestant Footer', array($this, 'input_extra_contestant_footer'), 'ks-giveaways-options', 'ks_giveaways_template');
		}

		if ($tab == 'services') {
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_AWEBER_KEY, array($this, 'sanitize_aweber_key'));

			add_settings_section('ks_giveaways_services', 'Sync Options', null, 'ks-giveaways-options');
			add_settings_field(KS_GIVEAWAYS_OPTION_SYNC_WHEN, 'Push Mode', array($this, 'input_sync_contestants'), 'ks-giveaways-options', 'ks_giveaways_services');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_SYNC_WHEN, array($this, 'sanitize_sync_mode'));

			add_settings_section('ks_giveaways_sendfox', 'SendFox', null, 'ks-giveaways-options');
			if (KS_Giveaways_SendFox::is_valid()) {
				add_settings_field(KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN, 'Disconnect from API', array($this, 'disconnect_sendfox_button'), 'ks-giveaways-options', 'ks_giveaways_sendfox');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID, 'trim');
				add_settings_field(KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID, 'Tag', array($this, 'input_sendfox_tag_id'), 'ks-giveaways-options', 'ks_giveaways_sendfox');
			} else {
				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN, array($this, 'sanitize_sendfox_token'));
				add_settings_field(KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN, 'Connect to API', array($this, 'input_sendfox_token'), 'ks-giveaways-options', 'ks_giveaways_sendfox');
			}

			add_settings_section('ks_giveaways_aweber', 'Aweber', null, 'ks-giveaways-options');
			if (KS_Giveaways_Aweber::is_valid()) {
				add_settings_field(KS_GIVEAWAYS_OPTION_AWEBER_KEY, 'Disconnect from API', array($this, 'disconnect_aweber_button'), 'ks-giveaways-options', 'ks_giveaways_aweber');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, 'trim');
				add_settings_field(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, 'List', array($this, 'input_aweber_list_id'), 'ks-giveaways-options', 'ks_giveaways_aweber');
			} else {
				add_settings_field(KS_GIVEAWAYS_OPTION_AWEBER_KEY, 'Connect to API', array($this, 'input_aweber_key'), 'ks-giveaways-options', 'ks_giveaways_aweber');
			}

			add_settings_section('ks_giveaways_mailchimp', 'MailChimp', null, 'ks-giveaways-options');
			if (KS_Giveaways_Mailchimp::is_valid()) {
				add_settings_field(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, 'Disconnect from API', array($this, 'disconnect_mailchimp_button'), 'ks-giveaways-options', 'ks_giveaways_mailchimp');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, 'trim');
				add_settings_field(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, 'List', array($this, 'input_mailchimp_list_id'), 'ks-giveaways-options', 'ks_giveaways_mailchimp');
			} else {
				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, array($this, 'sanitize_mailchimp_key'));
				add_settings_field(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, 'Connect to API', array($this, 'input_mailchimp_key'), 'ks-giveaways-options', 'ks_giveaways_mailchimp');
			}

			// GetResponse API
			add_settings_section('ks_giveaways_getresponse', 'GetResponse', null, 'ks-giveaways-options');
			if (KS_Giveaways_GetResponse::is_valid()) {
				add_settings_field(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, 'Disconnect from API', array($this, 'disconnect_getresponse_button'), 'ks-giveaways-options', 'ks_giveaways_getresponse');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, 'trim');
				add_settings_field(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, 'List', array($this, 'input_getresponse_campaign_id'), 'ks-giveaways-options', 'ks_giveaways_getresponse');
			} else {
				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, array($this, 'sanitize_getresponse_key'));
				add_settings_field(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, 'Connect to API', array($this, 'input_getresponse_key'), 'ks-giveaways-options', 'ks_giveaways_getresponse');
			}

			// CampaignMonitor API
			add_settings_section('ks_giveaways_campaignmonitor', 'CampaignMonitor', null, 'ks-giveaways-options');
			if (KS_Giveaways_CampaignMonitor::is_valid()) {
				add_settings_field(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY, 'Disconnect from API', array($this, 'disconnect_campaignmonitor_button'), 'ks-giveaways-options', 'ks_giveaways_campaignmonitor');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID, 'trim');
				add_settings_field(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID, 'List', array($this, 'input_campaignmonitor_list_id'), 'ks-giveaways-options', 'ks_giveaways_campaignmonitor');
			} else {
				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY, array($this, 'sanitize_campaignmonitor_api_key'));
				add_settings_field(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY, 'API Key', array($this, 'input_campaignmonitor_api_key'), 'ks-giveaways-options', 'ks_giveaways_campaignmonitor');
			}

			// ConvertKit API
			add_settings_section('ks_giveaways_convertkit', 'ConvertKit', null, 'ks-giveaways-options');
			if (KS_Giveaways_ConvertKit::is_valid()) {
				add_settings_field(KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY, 'Disconnect from API', array($this, 'disconnect_convertkit_button'), 'ks-giveaways-options', 'ks_giveaways_convertkit');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID, 'trim');
				add_settings_field(KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID, 'Form', array($this, 'input_convertkit_form_id'), 'ks-giveaways-options', 'ks_giveaways_convertkit');
			} else {
				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY, array($this, 'sanitize_convertkit_api_key'));
				add_settings_field(KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY, 'API Key', array($this, 'input_convertkit_api_key'), 'ks-giveaways-options', 'ks_giveaways_convertkit');

			}

			// ActiveCampaign API
			add_settings_section('ks_giveaways_activecampaign', 'ActiveCampaign', null, 'ks-giveaways-options');
			if (KS_Giveaways_ActiveCampaign::is_valid()) {
				add_settings_field(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY, 'Disconnect from API', array($this, 'disconnect_activecampaign_button'), 'ks-giveaways-options', 'ks_giveaways_activecampaign');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID, 'trim');
				add_settings_field(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID, 'List', array($this, 'input_activecampaign_list_id'), 'ks-giveaways-options', 'ks_giveaways_activecampaign');
			} else {
				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL, array($this, 'sanitize_activecampaign_api_url'));
				add_settings_field(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL, 'API URL', array($this, 'input_activecampaign_api_url'), 'ks-giveaways-options', 'ks_giveaways_activecampaign');

				register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY, array($this, 'sanitize_activecampaign_api_key'));
				add_settings_field(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY, 'API Key', array($this, 'input_activecampaign_api_key'), 'ks-giveaways-options', 'ks_giveaways_activecampaign');
			}

			// Zapier
			add_settings_section('ks_giveaways_zapier', 'Zapier', null, 'ks-giveaways-options');
			register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL, array($this, 'sanitize_activecampaign_api_url'));
			add_settings_field(KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL, 'Trigger URL', array($this, 'input_zapier_trigger_url'), 'ks-giveaways-options', 'ks_giveaways_zapier');


			add_settings_section('ks_giveaways_test', 'Test Integration Configuration', null, 'ks-giveaways-options');
			add_settings_field('ks_giveaways_test_services_subscription', 'Test Services', array($this, 'input_test_services_subscription'), 'ks-giveaways-options', 'ks_giveaways_test');
		}
	}

	public function disconnect_sendfox_button()
	{
		echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-sendfox&noheader=true').'" class="button">Disconnect SendFox API</a>';
	}

	public function disconnect_aweber_button()
	{
		echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-aweber&noheader=true').'" class="button">Disconnect Aweber API</a>';
	}

	public function disconnect_mailchimp_button()
	{
		echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-mailchimp&noheader=true').'" class="button">Disconnect MailChimp API</a>';
	}

	public function disconnect_getresponse_button()
	{
		echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-getresponse&noheader=true').'" class="button">Disconnect GetResponse API</a>';
	}

	public function disconnect_campaignmonitor_button()
	{
		echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-campaignmonitor&noheader=true').'" class="button">Disconnect CampaignMonitor API</a>';
	}

	public function disconnect_convertkit_button()
	{
		echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-convertkit&noheader=true').'" class="button">Disconnect ConvertKit API</a>';
	}

	public function disconnect_activecampaign_button()
	{
		echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-activecampaign&noheader=true').'" class="button">Disconnect ActiveCampaign API</a>';
	}

	public function input_draw_mode()
	{
		$value = get_option(KS_GIVEAWAYS_OPTION_DRAW_MODE, 'all');

		echo '<fieldset>';
		echo sprintf('<label><input type="radio" name="%s" value="all"%s /> Any contestant is eligible to win my giveaways</label><br />', KS_GIVEAWAYS_OPTION_DRAW_MODE, $value === 'all' ? ' checked' : '');
		echo sprintf('<label><input type="radio" name="%s" value="confirmed"%s /> Only confirmed entries may win my giveaways</label><br />', KS_GIVEAWAYS_OPTION_DRAW_MODE, $value === 'confirmed' ? ' checked' : '');
		echo '</fieldset>';
	}

	public function input_sync_contestants()
	{
		$value = get_option(KS_GIVEAWAYS_OPTION_SYNC_WHEN, 'entry');

		echo '<fieldset>';
		echo sprintf('<label><input type="radio" name="%s" value="entry"%s /> Push contestants immediately when they enter my giveaway</label><br />', KS_GIVEAWAYS_OPTION_SYNC_WHEN, $value === 'entry' ? ' checked' : '');
		echo sprintf('<label><input type="radio" name="%s" value="confirm"%s /> Push contestants after they confirm their giveaway entry</label><br />', KS_GIVEAWAYS_OPTION_SYNC_WHEN, $value === 'confirm' ? ' checked' : '');
		echo '</fieldset>';
	}

	public function sanitize_boolean($on)
	{
		$on = intval($on);

		return $on;
	}

	public function sanitize_aweber_key($key)
	{
		if ($key) {
			try {
				$auth = KS_Giveaways_Aweber::auth_from_key($key);

				update_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_KEY, $auth ? $auth[0] : '');
				update_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_SECRET, $auth ? $auth[1] : '');
				update_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_KEY, $auth ? $auth[2] : '');
				update_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_SECRET, $auth ? $auth[3] : '');

				delete_transient('ks_giveaways_aweber_lists');
			}
			catch(Exception $e)
			{
			}

			delete_option(KS_GIVEAWAYS_OPTION_AWEBER_KEY);
		}

		return '';
	}

	public function save_winner_avatar()
	{
		$url = isset($_POST['value']) ? $_POST['value'] : null;
		$id = isset($_POST['id']) ? (int) str_replace('winner_avatar_', '', $_POST['id']) : null;

		if ($id) {
			if ($url !== null) {
				KS_Winner_DB::update_avatar($id, $url);
			}

			$winner = KS_Winner_DB::get($id);
			if ($winner) {
				$url = $winner->winner_avatar;
				if (!$url) {
					$url = plugins_url('assets/images/user-avatar.jpg', KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR);
				}
				echo $url;
			}
		}
		exit;
	}

	public function save_winner_name()
	{
		$name = isset($_POST['value']) ? $_POST['value'] : null;
		$id = isset($_POST['id']) ? (int) str_replace('winner_name_', '', $_POST['id']) : null;

		if ($id) {
			if ($name !== null) {
				KS_Winner_DB::update_name($id, $name);
			}

			$winner = KS_Winner_DB::get($id);
			if ($winner) {
				echo $winner->winner_name;
			}
		}
		exit;
	}

	public function sanitize_sendfox_token($token)
	{
		$token = trim($token);

		if ($token) {
			try {
				delete_transient('ks_giveaways_sendfox_tags');
			}
			catch(Exception $e)
			{
			}
		}

		return $token;
	}

	public function sanitize_mailchimp_key($key)
	{
		$key = trim($key);

		if ($key) {
			try {
				delete_transient('ks_giveaways_mailchimp_lists');
			}
			catch(Exception $e)
			{
			}
		}

		return $key;
	}

	public function sanitize_getresponse_key($key)
	{
		$key = trim($key);

		if ($key) {
			try {
				delete_transient('ks_giveaways_getresponse_campaigns');
			}
			catch(Exception $e)
			{
			}
		}

		return $key;
	}

	public function sanitize_campaignmonitor_api_key($key)
	{
		$key = trim($key);

		if ($key) {
			try {
				delete_transient('ks_giveaways_campaignmonitor_lists');
			}
			catch(Exception $e)
			{
			}
		}

		return $key;
	}

	public function sanitize_convertkit_api_key($key)
	{
		$key = trim($key);

		if ($key) {
			try {
				delete_transient('ks_giveaways_campaignmonitor_lists');
			}
			catch(Exception $e)
			{
			}
		}

		return $key;
	}

	public function sanitize_activecampaign_api_key($key)
	{
		$key = trim($key);

		if ($key) {
			try {
				delete_transient('ks_giveaways_campaignmonitor_lists');
			}
			catch(Exception $e)
			{
			}
		}

		return $key;
	}

	public function sanitize_activecampaign_api_url($url)
	{
		return trim($url);
	}

	public function sanitize_draw_mode($draw_mode)
	{
		$draw_mode = strtolower(trim($draw_mode));

		if (!in_array($draw_mode, array('confirmed', 'all'))) {
			$draw_mode = 'all';
		}

		return $draw_mode;
	}

	public function sanitize_sync_mode($sync_mode)
	{
		$sync_mode = strtolower(trim($sync_mode));

		if (!in_array($sync_mode, array('entry', 'confirm'))) {
			$sync_mode = 'entry';
		}

		return $sync_mode;
	}

	public function sanitize_url($url)
	{
		$url = sanitize_text_field($url);
		if ($url) {
			$parts = parse_url($url);
			if (!is_array($parts) || !isset($parts['scheme'])) {
				$url = 'http://' . $url;
				$parts = parse_url($url);
				if (!is_array($parts) || !isset($parts['scheme']) || !filter_var($url, FILTER_VALIDATE_URL)) {
					$url = '';
				}
			}
		}

		return $url;
	}

	public function sanitize_twitter($handle)
	{
		$handle = ltrim(sanitize_text_field($handle), '@');

		return $handle;
	}

	public function admin_menu()
	{
		add_options_page('KingSumo Giveaways', 'KingSumo Giveaways', 'manage_options', 'ks-giveaways-options', array($this, 'settings_page'));
		add_submenu_page(null, 'KingSumo Giveaways', null, 'manage_options', 'ks-giveaways', array($this, 'giveaways_page'));

		// Kind of hacky way to get the options page linked in the KingSumo Giveaways menu
		global $submenu;
		$ks_edit_path = 'edit.php?post_type=' . KS_GIVEAWAYS_POST_TYPE;
		add_submenu_page($ks_edit_path, 'KingSumo Giveaways', 'Options', 'manage_options', 'ks-giveaways-options', array($this, 'settings_page'));

		if (array_key_exists($ks_edit_path, $submenu) && isset($submenu[$ks_edit_path][11])) {
			$submenu[$ks_edit_path][11][2] = 'options-general.php?page=ks-giveaways-options';
		}
	}

	public function settings_page()
	{
		$active_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'general';

		require_once KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'settings.php';
	}

	private function display_input_box($name, $value, $description = null, $type = 'text', $cls = 'regular-text')
	{
		echo sprintf('<input type="%s" class="%s" name="%s" value="%s" />'."\r\n", $type, $cls, $name, esc_attr($value));
		if ($description) {
			echo '<p class="description">' . $description . '</p>'."\r\n";
		}
	}

	private function display_textarea_box($name, $value, $description = null, $rows = 5, $cls = 'regular-text', $placeholder = '')
	{
		echo sprintf('<textarea rows="%s" class="%s" name="%s" style="width:85%%;" placeholder="%s">%s</textarea>'."\r\n", $rows, $cls, $name, $placeholder, esc_textarea($value));
		if ($description) {
			echo '<p class="description">' . $description . '</p>'."\r\n";
		}
	}

	private function display_select($name, $value, $options = array(), $description = null)
	{
		$opts = array();
		if ($options) {
			foreach ($options as $key => $val) {
				$opts[] = sprintf('<option value="%s"%s>%s</option>', $key, $key == $value ? ' selected' : '', $val);
			}
		}
		echo sprintf('<select name="%s">%s</select>', $name, implode("\n", $opts));
		if ($description) {
			echo '<p class="description">' . $description . '</p>'."\r\n";
		}
	}

	/**
	 * Create a multidimensional dropdown with select and optgroups
	 *
	 * $optgroups = [
	 *      "Optgroup Label" => [
	 *          "Option Value" => "Option Label",
	 *          "Option Value" => "Option Label"
	 *      ],
	 *      "Optgroup Label" => [
	 *          "Option Value" => "Option Label",
	 *          "Option Value" => "Option Label"
	 *      ]
	 * ]
	 *
	 * @param string $input_name The input name
	 * @param string $current_option_value The current "value" of the selected option
	 * @param array $optgroups A 2 dimensional array where the key is the optgroup label
	 * @param string $description A description to display underneath
	 */
	private function display_select_multidimensional($input_name, $current_option_value, $optgroups, $description = "")
	{
		echo "<select name=\"{$input_name}\">";

		foreach($optgroups as $optgroup_label => $optgroup)
		{
			if(is_array($optgroup))
			{
				echo "<optgroup label=\"{$optgroup_label}\">";

				foreach($optgroup as $option_value => $option_label)
				{
					if($option_value == $current_option_value)
					{
						echo "<option value=\"{$option_value}\" selected>{$option_label}</option>";
					}
					else
					{
						echo "<option value=\"{$option_value}\">{$option_label}</option>";
					}
				}

				echo "</optgroup>";
			}
			else
			{
				$value = $optgroup_label;
				$label = $optgroup;

				if($value == $current_option_value)
				{
					echo "<option value=\"{$value}\" selected>{$label}</option>";
				}
				else
				{
					echo "<option value=\"{$value}\">{$label}</option>";
				}
			}
		}

		echo "<p class=\"description\">{$description}/p>";
	}

	public function input_extra_footer()
	{
		$this->display_textarea_box(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER, get_option(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER), 'Extra code appended to the footer of every giveaway.  This can be used to place Google Analytics or any other tracking code.');
	}

	public function input_extra_contestant_footer()
	{
		$this->display_textarea_box(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER, get_option(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER), 'Additional extra code appended to the footer for contestants who have entered.  This can be used to track conversions.');
	}

	public function input_test_services_subscription()
	{
		echo '<input type="text" class="regular-text" id="ks-giveaways-test-services-subscription-input">';
		echo '<button class="button" type="button" id="ks-giveaways-test-services-subscription-button">Add Subscriber</button>';
		echo '<span id="ks-giveaways-test-services-subscription-container">';
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'test_services_subscription.php';
		echo '</span>';
	}

	public function ajax_test_services_subscription()
	{
		$errors = array();
		$status = true;

		$email = isset($_POST['ks-giveaways-test-services-subscription-email-address']) ? $_POST['ks-giveaways-test-services-subscription-email-address'] : null;

		if ($email === null || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$status = false;
			$errors[] = "Please enter a valid email address.";
			include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'test_services_subscription.php';
			exit;
		}

		/** @var KS_Giveaways $ks_giveaways */
		$ks_giveaways = KS_Giveaways::get_instance();

		/** @noinspection PhpUnusedLocalVariableInspection Used in the template to be included. */
		$r = $ks_giveaways->sync_provider_email_address($email, null, true);

		/*
		 * $status
		 *
		 * [
		 *      'aweber' => true for success, false for failure, unset for not set up and NULL for __disabled.
		 *      'mailchimp',
		 *      'getresponse',
		 *      'campaignmonitor'
		 * ]
		 */

		if(empty($r))
		{
			/** @noinspection PhpUnusedLocalVariableInspection */
			$status = 3;
		}
		else
		{
			foreach($r as $key => $s)
			{
				if($s === 3)
				{
					$errors[] = "The {$key} service has no list configured.";
				}
				else if($s !== true)
				{
					/** @noinspection PhpUnusedLocalVariableInspection */
					$status = false;
					$errors[] = "The {$key} service ran into an error: {$s}";
				} else {
					$errors[] = "{$key} was pushed successfully.  Please login to {$key} and confirm.";
				}
			}
		}

		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'test_services_subscription.php';
		exit;
	}

	public function input_sendfox_tag_id($meta_box = false, $current = false)
	{
		$options = array();

		//Make sure that the global setting shows up before the other message.
		if($meta_box)
		{
			$options['__global'] = "-- Use global SendFox setting --";
		}

		$options ['__disable'] = "-- Don't automatically subscribe contestants to SendFox --";

		$lists = get_transient('ks_giveaways_sendfox_tags');
		if ($lists === false) {
			$cls = KS_Giveaways_SendFox::get_instance();
			$lists = $cls->get_tags();
			if (is_array($lists)) {
				set_transient('ks_giveaways_sendfox_tags', $lists, 1 * MINUTE_IN_SECONDS);
			}
		}

		if ($lists && is_array($lists)) {
			$options = $options + $lists;
		}

		if($current === false)
		{
			$current = get_option(KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID);
		}
		elseif ($current === "")
		{
			$current = "__global";
		}

		$this->display_select(KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID, $current, $options, 'The SendFox tag that contestant email addresses will be automatically added to.');
	}

	public function input_aweber_list_id($meta_box = false, $current = false)
	{
		$options = array();

		//Make sure that the global setting shows up before the other message.
		if($meta_box)
		{
			$options['__global'] = "-- Use global Aweber setting --";
		}

		$options ['__disable'] = "-- Don't automatically subscribe contestants to Aweber --";

		$lists = get_transient('ks_giveaways_aweber_lists');
		if ($lists === false) {
			$cls = KS_Giveaways_Aweber::get_instance();
			$lists = $cls->get_lists();
			if (is_array($lists)) {
				set_transient('ks_giveaways_aweber_lists', $lists, 1 * MINUTE_IN_SECONDS);
			}
		}

		if ($lists && is_array($lists)) {
			$options = array_merge($options, $lists);
		}

		if($current === false)
		{
			$current = get_option(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID);
		}
		elseif ($current === "")
		{
			$current = "__global";
		}

		$this->display_select(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, $current, $options, 'The Aweber subscriber list contestant email addresses will be automatically added to.');
	}

	public function input_mailchimp_list_id($meta_box = false, $current = false)
	{
		$options = array();

		//Make sure that the global setting shows up before the other message.
		if($meta_box)
		{
			$options['__global'] = "-- Use global MailChimp setting --";
		}

		$options ['__disable'] = "-- Don't automatically subscribe contestants to MailChimp --";

		$lists = get_transient('ks_giveaways_mailchimp_lists');
		if ($lists === false) {
			$cls = KS_Giveaways_Mailchimp::get_instance();
			$lists = $cls->get_lists();
			if (is_array($lists)) {
				set_transient('ks_giveaways_mailchimp_lists', $lists, 1 * MINUTE_IN_SECONDS);
			}
		}

		if ($lists && is_array($lists)) {
			$options = array_merge($options, $lists);
		}

		if($current === false)
		{
			$current = get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID);
		}
		elseif ($current === "")
		{
			$current = "__global";
		}

		$this->display_select(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, $current, $options, 'The Mailchimp subscriber list contestant email addresses will be automatically added to.');
	}

	public function input_getresponse_campaign_id($meta_box = false, $current = false)
	{
		$options = array();

		//Make sure that the global setting shows up before the other message.
		if($meta_box)
		{
			$options['__global'] = "-- Use global GetResponse setting --";
		}

		$options ['__disable'] = "-- Don't automatically subscribe contestants to GetResponse --";

		$campaigns = get_transient('ks_giveaways_getresponse_campaigns');
		if ($campaigns === false) {
			/** @var KS_Giveaways_GetResponse $cls */
			$cls = KS_Giveaways_GetResponse::get_instance();
			$campaigns = $cls->get_campaigns();
			if (is_array($campaigns)) {
				set_transient('ks_giveaways_getresponse_campaigns', $campaigns, 1 * MINUTE_IN_SECONDS);
			}
		}

		if ($campaigns && is_array($campaigns)) {
			$options = array_merge($options, $campaigns);
		}

		if($current === false)
		{
			$current = get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID);
		}
		elseif ($current === "")
		{
			$current = "__global";
		}

		$this->display_select(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, $current, $options, 'The GetResponse subscriber campaign contestant email addresses will be automatically added to.');
	}

	public function input_campaignmonitor_list_id($meta_box = false, $current = false)
	{
		$options = array();

		//Make sure that the global setting shows up before the other message.
		if($meta_box)
		{
			$options['__global'] = "-- Use global CampaignMonitor setting --";
		}

		$options ['__disable'] = "-- Don't automatically subscribe contestants to CampaignMonitor --";

		$lists = get_transient('ks_giveaways_campaignmonitor_lists');
		if ($lists === false) {
			/** @var KS_Giveaways_CampaignMonitor $cls */
			$cls = KS_Giveaways_CampaignMonitor::get_instance();
			$lists = $cls->get_subscriber_lists();
			if (is_array($lists)) {
				set_transient('ks_giveaways_campaignmonitor_lists', $lists, 1 * MINUTE_IN_SECONDS);
			}
		}

		if ($lists && is_array($lists)) {
			$options = array_merge($options, $lists);
		}

		if($current === false)
		{
			$current = get_option(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID);
		}
		elseif ($current === "")
		{
			$current = "__global";
		}

		$this->display_select_multidimensional(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID, $current, $options, 'The CampaignMonitor subscriber list contestant email addresses will be automatically added to.');
	}

	public function input_convertkit_form_id($meta_box = false, $current = false)
	{
		$options = array();

		//Make sure that the global setting shows up before the other message.
		if($meta_box)
		{
			$options['__global'] = "-- Use global ConvertKit setting --";
		}

		$options ['__disable'] = "-- Don't automatically subscribe contestants to ConvertKit --";

		$forms = get_transient('ks_giveaways_convertkit_forms');
		if ($forms === false) {
			/** @var KS_Giveaways_ConverKit $cls */
			$cls = KS_Giveaways_ConvertKit::get_instance();
			$forms = $cls->get_forms();
			if (is_array($forms)) {
				set_transient('ks_giveaways_convertkit_forms', $forms, 1 * MINUTE_IN_SECONDS);
			}
		}

		if ($forms && is_array($forms)) {
			$options = $options + $forms;
		}

		if($current === false)
		{
			$current = get_option(KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID);
		}
		elseif ($current === "")
		{
			$current = "__global";
		}

		$this->display_select_multidimensional(KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID, $current, $options, 'The ConvertKit form contestant email addresses will be automatically added to.');
	}

	public function input_sendfox_token()
	{
		$this->display_textarea_box(KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN, get_option(KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN), 'SendFox is a better and more affordable email tool - from the team behind KingSumo.<br />If you\'re already a member you can <a href="https://sendfox.com/account/oauth" target="_blank">create your personal access token here.</a><br />If you want to join the waiting list, <a href="https://sendfox.com" target="_blank">click here</a>.', 5, 'regular-text', 'Personal access token...');
	}

	public function input_aweber_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_AWEBER_KEY, '', 'To authenticate with Aweber <a href="https://auth.aweber.com/1.0/oauth/authorize_app/984c4ccd" id="ks_giveaways_aweber_auth" target="_blank">click here to get your Aweber code</a>, paste it in to the box above and click Save Changes.');
	}

	public function input_mailchimp_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY), 'To authenticate with MailChimp <a href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key" target="_blank">click here to learn how to generate an API key</a>, paste it in to the box above and click Save Changes.');
	}

	public function input_captcha_site_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY, get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY), '<a href="https://www.google.com/recaptcha/admin" target="_blank">Sign up for a Google reCAPTCHA site key</a> to use captcha verification on giveaways.');
	}

	public function input_captcha_secret_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY, get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY));
	}

	public function input_getresponse_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY), 'To authenticate with GetResponse <a href="http://support.getresponse.com/faq/where-i-find-api-key" target="_blank">click here to learn how to generate an API key</a>, paste it in to the box above and click Save Changes.');
	}

	public function input_campaignmonitor_api_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY, get_option(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY), 'To authenticate with CampaignMonitor <a href="http://help.campaignmonitor.com/topic.aspx?t=206" target="_blank">click here to learn how to generate an API key</a>, paste it in to the box above and click Save Changes.');
	}

	public function input_convertkit_api_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY, get_option(KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY), 'To authenticate with ConvertKit grab your API key at <a href="https://app.convertkit.com/account/edit" target="_blank">https://app.convertkit.com/account/edit</a>, paste it in to the box above and click Save Changes.');
	}

	public function input_activecampaign_api_key()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY, get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY), 'To authenticate with ActiveCampaign grab your API key and API URL from your ActiveCampaign account and paste them into the boxes above and click Save Changes.');
	}

	public function input_activecampaign_api_url()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL, get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL));
	}

	public function input_activecampaign_list_id($meta_box = false, $current = false)
	{
		$options = array();

		//Make sure that the global setting shows up before the other message.
		if ($meta_box) {
			$options['__global'] = "-- Use global ActiveCampaign setting --";
		}

		$options ['__disable'] = "-- Don't automatically subscribe contestants to ActiveCampaign --";

		$lists = get_transient('ks_giveaways_activecampaign_lists');
		if ($lists === false) {
			$cls = KS_Giveaways_ActiveCampaign::get_instance();
			$lists = $cls->get_lists();
			if (is_array($lists)) {
				set_transient('ks_giveaways_activecampaign_lists', $lists, 1 * MINUTE_IN_SECONDS);
			}
		}

		if ($lists && is_array($lists)) {
			$options = $options + $lists;
		}

		if ($current === false) {
			$current = get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID);

		} elseif ($current === "") {
			$current = "__global";
		}

		$this->display_select_multidimensional(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID, $current, $options, 'The ActiveCampaign list contestant email addresses will be automatically added to.');
	}

	public function input_zapier_trigger_url($meta_box = false, $current = null)
	{
		if ($meta_box && ! is_null($current)) {
			$value = $current;

		} else {
			$value = get_option(KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL);
		}

		$description = 'Custom webhook URL provided by Zapier when setting up a new "Webhooks" Zap.  Sends [email, first_name, giveaway_name] for each entry.';

		if ($meta_box) {
			$description .= '<br /><strong>Overrides global trigger URL if set on KingSumo options page.</strong>';
		}

		$this->display_input_box(KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL, $value, $description);
	}

	public function input_address_street()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_STREET, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_STREET), 'Street address for the [address_street] short code.');
	}

	public function input_address_city()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_CITY, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_CITY), 'City used for the [address_city] short code.');
	}

	public function input_address_state()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_STATE, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_STATE), 'State used for the [address_state] short code.');
	}

	public function input_address_country()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY), 'Country used for the [address_country] short code.');
	}

	public function input_address_zip()
	{
		$this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_ZIP, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_ZIP), 'Postal code used for the [address_zip] short code.');
	}

	public function input_show_ks_badge()
	{
		$option = get_option(KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE);

		echo sprintf('<input type="hidden" id="%s" name="%s" value="%s"/>',
			KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE,
			KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE,
			$option === false || $option ? '1' : '0'
		);
	}

	public function input_email_from_address()
	{
		echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_email_from_address" value="%s" placeholder="'. get_bloginfo('name') . ' &lt;'.get_bloginfo('admin_email').'&gt;" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS)));
		echo '<p class="description">Email address to send emails from.<br /><small><a target="_blank" href="https://wordpress.kingsumo.com/ufaqs/why-isnt-entry-email-arriving-users-enter-giveaway/">Problems sending emails?</a></small></p>';
	}

	public function input_email_replyto_address()
	{
		echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_email_replyto_address" value="%s" placeholder="" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS)));
		echo '<p class="description">Reply-to address for emails.  Defaults to From address if blank.</p>';
	}

	public function input_entry_email_suppress()
	{
		echo sprintf('<input type="checkbox" id="ks_giveaways_entry_email_suppress" name="ks_giveaways_entry_email_suppress" value="1"%s />', get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS) ? ' checked' : '');
		echo '<label for="ks_giveaways_entry_email_suppress">Don\'t send confirmation emails to contestants</label>';
		echo '<p class="description">Warning: By suppressing the entry email from being sent to contestants confirmation will not occur.</p>';
	}

	public function input_giveaways_ask_name()
	{
		$disallow_due_to_shortcode_in_use = false;

		if((strpos(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT), '[first_name]') !== false
			or strpos(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY), '[first_name]') !== false
			or strpos(get_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT), '[first_name]') !== false
			or strpos(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY), '[first_name]') !== false
			)
			and get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME) // Disallow change if option currently enabled and shortcode in use
		)
		{
			$disallow_due_to_shortcode_in_use = true;
		}

		echo sprintf('<input type="checkbox" id="ks_giveaways_giveaways_ask_name" name="ks_giveaways_giveaways_ask_name" value="1"%s%s />',
			get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME) ? ' checked' : '',
			$disallow_due_to_shortcode_in_use ? ' disabled' : ''
		);

		echo '<label for="ks_giveaways_giveaways_ask_name">Ask contestants for their first name</label>';

		$x = '<p class="description">This information will also be available with shortcodes and will be included in the csv export.';

		if($disallow_due_to_shortcode_in_use) {
			$x .= '<br><span style="color: red;">Warning: The [first_name] shortcode is currently in use in one of the email templates! You may not disable this option while it is in use.</span>';
		}

		echo $x.'</p>';

		$this->input_show_ks_badge();
	}

	public function input_entry_email_subject()
	{
		echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_entry_email_subject" value="%s" placeholder="" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT)));
		echo '<p class="description">Email subject notifying contestant of their successful entry to the giveaway.</p>';
	}

	public function input_entry_email_body()
	{
		wp_editor(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY), 'ks_giveaways_entry_email_body', array(
			'wpautop' => true,
			'textarea_rows' => 10
		));
		echo '<p class="description">';
		echo $this->get_shortcodes();
		echo '</p>';
	}

	public function input_winner_email_subject()
	{
		echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_winner_email_subject" value="%s" placeholder="" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT)));
		echo '<p class="description">Email subject notifying contestant they have won the giveaway.</p>';
	}

	public function input_winner_email_body()
	{
		wp_editor(get_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY), 'ks_giveaways_winner_email_body', array(
			'wpautop' => true,
			'textarea_rows' => 10
		));
		echo '<p class="description">';
		echo $this->get_shortcodes();
		echo '</p>';
	}

	private function get_shortcodes()
	{
		$settings_url = admin_url('options-general.php?page=ks-giveaways-options&tab=settings');

		if(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME))
		{
			$x = "<strong>[first_name]</strong> First name of the current contestant - Toggle feature in <a href=\"{$settings_url}\">giveaway settings</a><br />";
			$y = "";
		}
		else
		{
			$x = "";
			$y = "<span style=\"color: red;\">NOT AVAILABLE:</span>
				  <br>
				  <strong>[first_name]</strong> First name of the current contestant - Enable feature from <a href=\"{$settings_url}\">giveaway settings</a><br />";
		}

		return <<<EOF
		  <a href="javascript:void(0)" onclick="jQuery(this).next().toggle();">Toggle available shortcodes</a>
		  <span style="display:none">
			<br />
			{$x}
			<strong>[name]</strong> Name of the giveaway<br />
			<strong>[site_name]</strong> Name of the WordPress website<br />
			<strong>[prize_name]</strong> Name of the prize<br />
			<strong>[prize_brand]</strong> Brand of the prize<br />
			<strong>[prize_value]</strong> Value of the prize<br />
			<strong>[date_ended]</strong> Date the giveaway ends<br />
			<strong>[date_awarded]</strong> Date the prize is awarded<br />
			<strong>[contact_email]</strong> Contact email address from the WordPress website<br />
			<strong>[site_url]</strong> URL of the WordPress website<br />
			<strong>[lucky_url]</strong> Lucky URL of the current contestant<br />
			<strong>[confirm_url]</strong> Confirm email URL for the current contestant<br />
			<strong>[entries_per_friend]</strong> Number of entries per referral<br />
			<strong>[address_street]</strong> Street address from <a href="{$settings_url}">giveaway settings</a><br />
			<strong>[address_city]</strong> City from <a href="{$settings_url}">giveaway settings</a><br />
			<strong>[address_state]</strong> State from <a href="{$settings_url}">giveaway settings</a><br />
			<strong>[address_country]</strong> Country from <a href="{$settings_url}">giveaway settings</a><br />
			<strong>[address_zip]</strong> Postal code from <a href="{$settings_url}">giveaway settings</a><br />
			{$y}
		  </span>
EOF;
	}

	public function input_twitter_via()
	{
		echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_twitter_via" value="%s" placeholder="KingSumo" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)));
		echo '<p class="description">Your Twitter @(Handle) will be used for "via" on share messages and for the Twitter follow button.</p>';
	}

	public function input_facebook_page()
	{
		echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_facebook_page_id" value="%s" placeholder="http://www.facebook.com/mygreatbusiness" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE)));
		echo '<p class="description">Enter a website or Facebook URL for the Facebook like button on the giveaway page.</p>';
	}

	public function input_youtube_url()
	{
		echo sprintf('<input type="text" class="regular-text" name="%s" value="%s" placeholder="https://www.youtube.com/channel/UCF2v8v8te3_u4xhIQ8tGy1g" />', KS_GIVEAWAYS_OPTION_YOUTUBE_URL, esc_attr(get_option(KS_GIVEAWAYS_OPTION_YOUTUBE_URL)));
		echo '<p class="description">Enter a YouTube channel URL.  You can award extra entries per giveaway when a user clicks this link.</p>';
	}

	public function input_instagram_url()
	{
		echo sprintf('<input type="text" class="regular-text" name="%s" value="%s" placeholder="https://www.instagram.com/appsumo/" />', KS_GIVEAWAYS_OPTION_INSTAGRAM_URL, esc_attr(get_option(KS_GIVEAWAYS_OPTION_INSTAGRAM_URL)));
		echo '<p class="description">Enter an Instagram account URL.  You can award extra entries per giveaway when a user clicks this link.</p>';
	}

	public function input_license_key()
	{
		$license_valid = get_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS) === 'valid';

		echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_license_key" value="%s" %s/>', get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY), ($license_valid ? 'readonly' : ''));
		echo '<span id="ks-license-container">';
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'license_status.php';
		echo '</span>';
	}

	public function ajax_activate_license()
	{
		$errors = array();

		// Update option first so user doesn't first have to click 'Save Changes'
		update_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY, sanitize_text_field($_POST['license']));
		$this->activate_license(get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY), 'activate_license', $errors);

		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'license_status.php';
		exit;
	}

	public function ajax_deactivate_license()
	{
		$errors = array();
		$this->activate_license(get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY), 'deactivate_license');

		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'license_status.php';
		exit;
	}

	public function activate_license($license, $action = 'activate_license', &$errors = null)
	{
		if ($license) {
			$api_params = array(
				'edd_action'=> $action,
				'license'     => $license,
				'item_name' => urlencode(KS_GIVEAWAYS_EDD_NAME)
			);

			$response = KS_Http::get(add_query_arg($api_params, KS_GIVEAWAYS_EDD_URL), $errors);

			if ($response === false) {
				return false;
			}

			$license_data = json_decode($response);
			if ($license_data === null) {
				if ($errors !== null) {
					$errors[] = sprintf('Unable to JSON decode response: %s...', esc_html(substr($response, 0, 50)));
				}
				return false;
			}

			if (isset($license_data->license)) {
				$errors = null;
				update_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS, $license_data->license);
			}
		}
	}

	public function sanitize_license($new)
	{
		$old = get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY);
		if ($old != $new) {
			delete_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS);

			if (trim($new)) {
				$this->activate_license($new);
			}
		}

		return $new;
	}

	public function save_contest($post_id)
	{
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!isset($_POST['post_type']) || $_POST['post_type'] != KS_GIVEAWAYS_POST_TYPE || !$this->has_valid_license()) {
			return;
		}

		$offset = get_option('gmt_offset')*3600;
		foreach (array('start', 'end', 'awarded') as $key) {
			if (isset($_POST['date_' . $key])) {
				$time_start = isset($_POST['time_' . $key]) ? $_POST['time_' . $key] : '00:00';
				$parse = sprintf('%s %s:00+00:00', $_POST['date_' . $key], $time_start);
				$date_start = strtotime($parse) - $offset;
				update_post_meta($post_id, '_date_' . $key, $date_start);
			}
		}

		$contest_rules = $_POST['contest_rules'];
		update_post_meta($post_id, '_contest_rules', wpautop(wptexturize($contest_rules)));

		$contest_description = $_POST['contest_description'];
		update_post_meta($post_id, '_contest_description', wpautop(wptexturize($contest_description)));

		$prize_name = stripslashes_deep(sanitize_text_field($_POST['prize_name']));
		$prize_brand = stripslashes_deep(sanitize_text_field($_POST['prize_brand']));
		$prize_image = stripslashes_deep(sanitize_text_field($_POST['prize_image']));
		$prize_value = stripslashes_deep(sanitize_text_field($_POST['prize_value']));
		$winner_count = (int) stripslashes_deep(sanitize_text_field($_POST['winner_count']));
		if (!$winner_count) {
			$winner_count = 1;
		}

		$embed_post_id = stripslashes_deep(sanitize_text_field($_POST['embed_post_id']));

		$entries_per_friend = (int) stripslashes_deep(sanitize_text_field($_POST['entries_per_friend']));
		if (!$entries_per_friend) {
			$entries_per_friend = 3;
		}

		if (array_key_exists(KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS, $_POST)) {
			update_post_meta($post_id, '_entry_actions', $_POST[KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS]);

		} else {
			delete_post_meta($post_id, '_entry_actions');
		}

		update_post_meta($post_id, '_embed_post_id', $embed_post_id);
		update_post_meta($post_id, '_prize_name', $prize_name);
		update_post_meta($post_id, '_prize_brand', $prize_brand);
		update_post_meta($post_id, '_prize_value', $prize_value);
		update_post_meta($post_id, '_prize_image', $prize_image);
		update_post_meta($post_id, '_winner_count', $winner_count);
		update_post_meta($post_id, '_entries_per_friend', $entries_per_friend);

		$templates = KS_Giveaways::get_available_templates();
		$template_file = $_POST['template_file'];
		if (!in_array($template_file, array_keys($templates))) {
			$template_file = KS_Giveaways::$default_template;
		}

		$logo_image = stripslashes_deep(sanitize_text_field($_POST['logo_image']));
		$background_image = stripslashes_deep(sanitize_text_field($_POST['background_image']));
		$image1 = stripslashes_deep(sanitize_text_field($_POST['image1']));
		$image1_link = stripslashes_deep(sanitize_text_field($_POST['image1_link']));
		$image2 = stripslashes_deep(sanitize_text_field($_POST['image2']));
		$image2_link = stripslashes_deep(sanitize_text_field($_POST['image2_link']));
		$image3 = stripslashes_deep(sanitize_text_field($_POST['image3']));
		$image3_link = stripslashes_deep(sanitize_text_field($_POST['image3_link']));

		update_post_meta($post_id, '_template_file', $template_file);
		update_post_meta($post_id, '_logo_image', $logo_image);
		update_post_meta($post_id, '_background_image', $background_image);
		update_post_meta($post_id, '_image_1', $image1);
		update_post_meta($post_id, '_image_1_link', $image1_link);
		update_post_meta($post_id, '_image_2', $image2);
		update_post_meta($post_id, '_image_2_link', $image2_link);
		update_post_meta($post_id, '_image_3', $image3);
		update_post_meta($post_id, '_image_3_link', $image3_link);

		$question = stripslashes_deep(sanitize_text_field($_POST['question']));
		$wrong_answer1 = stripslashes_deep(sanitize_text_field($_POST['wrong_answer1']));
		$wrong_answer2 = stripslashes_deep(sanitize_text_field($_POST['wrong_answer2']));
		$right_answer = stripslashes_deep(sanitize_text_field($_POST['right_answer']));

		// Social sharing options
		foreach (array('facebook', 'twitter', 'linkedin', 'pinterest') as $platform) {
			if (isset($_POST['enable_' . $platform])) {
				$value = stripslashes_deep(sanitize_text_field($_POST['enable_' . $platform]));
			} else {
				$value = 0;
			}

			update_post_meta($post_id, '_enable_' . $platform, $value);
		}

		if (isset($_POST['enable_question'])) {
			$enable_question = stripslashes_deep(sanitize_text_field($_POST['enable_question']));
		} else {
			$enable_question = 0;
		}

		update_post_meta($post_id, '_enable_question', $enable_question);
		update_post_meta($post_id, '_question', $question);
		update_post_meta($post_id, '_wrong_answer1', $wrong_answer1);
		update_post_meta($post_id, '_wrong_answer2', $wrong_answer2);
		update_post_meta($post_id, '_right_answer', $right_answer);

		$keys = array(
			KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID,
			KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID,
			KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID,
			KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID,
			KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID,
			KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID,
			KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID,
			KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL,
		);

		foreach($keys as $query_name)
		{
			if(isset($_POST[$query_name]))
			{
				update_post_meta($post_id, "_{$query_name}", stripslashes_deep(sanitize_text_field($_POST[$query_name])));
			}
			else
			{
				delete_post_meta($post_id, "_{$query_name}");
			}
		}
	}

	public function bulk_post_updated_messages($bulk_messages)
	{
		global $bulk_counts;

		$bulk_messages[KS_GIVEAWAYS_POST_TYPE] = array(
		  'updated'   => _n( '%s giveaway updated.', '%s giveaways updated.', $bulk_counts['updated'] ),
		  'locked'    => _n( '%s giveaway not updated, somebody is editing it.', '%s giveaways not updated, somebody is editing them.', $bulk_counts['locked'] ),
		  'deleted'   => _n( '%s giveaway permanently deleted.', '%s giveaways permanently deleted.', $bulk_counts['deleted'] ),
		  'trashed'   => _n( '%s giveaway moved to the Trash.', '%s giveaways moved to the Trash.', $bulk_counts['trashed'] ),
		  'untrashed' => _n( '%s giveaway restored from the Trash.', '%s giveaways restored from the Trash.', $bulk_counts['untrashed'] ),
		);

		return $bulk_messages;
	}

	public function post_updated_messages($messages)
	{
		global $post_ID, $post;

		$messages[KS_GIVEAWAYS_POST_TYPE] = array(
		   0 => '', // Unused. Messages start at index 1.
		   1 => sprintf( __('Giveaway updated. <a href="%s">View giveaway</a>'), esc_url( get_permalink($post_ID) ) ),
		   2 => __('Custom field updated.'),
		   3 => __('Custom field deleted.'),
		   4 => __('Giveaway updated.'),
		  /* translators: %s: date and time of the revision */
		   5 => isset($_GET['revision']) ? sprintf( __('Giveaway restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		   6 => sprintf( __('Giveaway published. <a href="%s">View giveaway</a>'), esc_url( get_permalink($post_ID) ) ),
		   7 => __('Giveaway saved.'),
		   8 => sprintf( __('Giveaway submitted. <a target="_blank" href="%s">Preview giveaway</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		   9 => sprintf( __('Giveaway scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview giveaway</a>'),
				  /* translators: Publish box date format, see http://php.net/date */
				  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		  10 => sprintf( __('Giveaway draft updated. <a target="_blank" href="%s">Preview giveaway</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		return $messages;
	}

	public function redirect_post_location($location, $post_id)
	{
		if ((isset($_POST['save']) || isset($_POST['publish'])) && $_POST['post_type'] == KS_GIVEAWAYS_POST_TYPE) {
			$status = get_post_status($post_id);

			// if user is attempting to publish giveaway we validate all fields are filled in
			if ($status == 'publish' && !KS_Helper::validate_giveaway($post_id)) {
				remove_action('save_post', array($this, 'save_contest'));
				wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
				add_action('save_post', array($this, 'save_contest'));

				// remove "Post published" message
				if (strpos($location, 'message=6') !== false || strpos($location, 'message=1') !== false) {
					$location = remove_query_arg('message', $location);
				}

				// add error message to query string
				return add_query_arg('error_message', 1, $location);
			}

			// If user is attempting to publish giveaway, we validate that provided links are correct, if they're given
			if ($status == 'publish' && !KS_Helper::validate_giveaway_imagelinks($post_id)) {
				remove_action('save_post', array($this, 'save_contest'));
				wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
				add_action('save_post', array($this, 'save_contest'));

				// remove "Post published" message
				if (strpos($location, 'message=6') !== false || strpos($location, 'message=1') !== false) {
					$location = remove_query_arg('message', $location);
				}

				// add error message to query string
				return add_query_arg('error_message', 2, $location);
			}
		}

		return $location;
	}

	public function dbx_post_advanced()
	{
		if (get_post_type() != KS_GIVEAWAYS_POST_TYPE) {
			return;
		}

		global $notice;

		$error_messages = array(
			1 => __('Cannot publish giveaway until all fields are completed.'),
			2 => __('Cannot publish giveaway because provided image link URLs are not valid.')
		);

		if (isset($_GET['error_message']) && isset($error_messages[$_GET['error_message']])) {
			$notice = $error_messages[$_GET['error_message']];
		}
	}

	public function giveaways_page()
	{
		if (!$this->has_valid_license()) {
			return;
		}

		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

		switch($action) {
			case 'view': return $this->view_giveaway_page();
			case 'contestants': return $this->contestants_page();
			case 'flush':
				flush_rewrite_rules();
				$referer = wp_get_referer();
				wp_redirect($referer ? $referer : admin_url('edit.php?post_type=' . KS_GIVEAWAYS_POST_TYPE));
				exit;
			case 'database':
				delete_option('ks_contestant_db');
				delete_option('ks_entry_db');
				delete_option('ks_winner_db');
				KS_Giveaways::check_database_tables();
				$referer = wp_get_referer();
				wp_redirect($referer ? $referer : admin_url('edit.php?post_type=' . KS_GIVEAWAYS_POST_TYPE));
				exit;
			case 'disconnect-aweber':
				delete_transient('ks_giveaways_aweber_lists');
				KS_Giveaways_Aweber::disconnect();
				wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
				exit;
			case 'disconnect-mailchimp':
				delete_transient('ks_giveaways_mailchimp_lists');
				KS_Giveaways_Mailchimp::disconnect();
				wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
				exit;
			case 'disconnect-getresponse':
				delete_transient('ks_giveaways_getresponse_campaigns');
				KS_Giveaways_GetResponse::disconnect();
				wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
				exit;
			case 'disconnect-campaignmonitor':
				delete_transient('ks_giveaways_campaignmonitor_lists');
				KS_Giveaways_CampaignMonitor::disconnect();
				wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
				exit;
			case 'disconnect-convertkit':
				delete_transient('ks_giveaways_convertkit_forms');
				KS_Giveaways_ConvertKit::disconnect();
				wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
				exit;
			case 'disconnect-activecampaign':
				delete_transient('ks_giveaways_activecampaign_lists');
				KS_Giveaways_ActiveCampaign::disconnect();
				wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
				exit;
			case 'disconnect-sendfox':
				delete_transient('ks_giveaways_sendfox_tags');
				KS_Giveaways_SendFox::disconnect();
				wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
				exit;
		}
	}

	public function view_giveaway_page()
	{
		$id = $_REQUEST['id'];

		$GLOBALS['post'] = (int) $id;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$post_action = $_POST['post_action'];

			switch($post_action) {
				case 'confirm':
					$winner_id = $_POST['winner_id'];
					KS_Winner_DB::update_status($winner_id);
					break;

				case 'redraw':
					$winner_id = $_POST['winner_id'];
					KS_Entry_DB::draw($id, $winner_id);
					break;

				case 'remove':
					$winner_id = $_POST['winner_id'];
					KS_Winner_DB::remove($winner_id);
					break;

				case 'draw':
					KS_Entry_DB::draw($id);
					break;

				case 'notify':
					$winner_id = $_POST['winner_id'];
					KS_Winner_DB::notify($winner_id);
					break;
			}
		}

		require_once KS_GIVEAWAYS_PLUGIN_ADMIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-ks-winners-list-table.php';

		$list_table = new KS_Winners_List_Table(array(
			'contest_id' => $id
		));

		switch($list_table->current_action()) {
			case 'downloadcsv':
				KS_Winner_DB::output_csv($id);
				break;
		}

		$list_table->prepare_items();

		require_once KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'view.php';
	}

	public function contestants_page()
	{
		$id = $_REQUEST['id'];

		$GLOBALS['post'] = (int) $id;

		require_once KS_GIVEAWAYS_PLUGIN_ADMIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-ks-contestants-list-table.php';

		$list_table = new KS_Contestants_List_Table(array(
			'contest_id' => $id,
		));

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-' . $list_table->_args['plural'])) {
			if (isset($_POST['bulk-action-top']) && isset($_POST['bulk-action-bottom']) && ($_POST['bulk-action-top'] > -1 || $_POST['bulk-action-bottom'] > -1)) {
				if ($_POST['bulk-action-top'] > -1) {
					$bulk_action = $_POST['bulk-action-top'];

				} else {
					$bulk_action = $_POST['bulk-action-bottom'];
				}

				if (isset($_POST['selected_contestants'])) {
					foreach ($_POST['selected_contestants'] as $contestant_id) {
						switch ($bulk_action) {
							case 'remove':
								KS_Contestant_DB::remove((int) $contestant_id);
								break;

							case 'resend':
								$contestant = KS_Contestant_DB::get((int) $contestant_id);
								if ($contestant) {
									KS_Helper::send_confirm_email($contestant);
								}
								break;
						}
					}

					switch ($bulk_action) {
						case 'remove':
							add_settings_error(
								'ks_admin_contestant_page_errors',
								esc_attr('ks-contestant-bulk-updated'),
								"The selected contestants have been removed.",
								'updated'
							);
							break;
						
						case 'resend':
							add_settings_error(
								'ks_admin_contestant_page_errors',
								esc_attr('ks-contestant-bulk-updated'),
								"Confirmation emails have been resent to the selected contestants.",
								'updated'
							);
							break;
						}

				} else {
					add_settings_error(
						'ks_admin_contestant_page_errors',
						esc_attr('ks-contestant-bulk-error'),
						"Please select contestants to apply bulk action to.",
						'error'
					);
				}			
			}
		}

		switch($list_table->current_action()) {
			case 'downloadcsv':
				KS_Contestant_DB::output_csv($id);
				break;
			case 'bulkremove':
				$contestants = KS_Contestant_DB::get_all($id);

				foreach($contestants as $contestant)
				{
					if ($contestant and strtolower($contestant->status) === "unconfirmed") {
						KS_Contestant_DB::remove($contestant->ID);
					}
				}

				add_settings_error(
					'ks_admin_contestant_page_errors',
					esc_attr('ks-contestant-bulkremove-updated'),
					"Removed all unconfirmed contestants.",
					'updated'
				);

				break;
			case 'bulkresend':
				$contestants = KS_Contestant_DB::get_all($id);

				foreach($contestants as $contestant)
				{
					if ($contestant and strtolower($contestant->status) === "unconfirmed") {
						KS_Helper::send_confirm_email($contestant);
					}
				}

				add_settings_error(
					'ks_admin_contestant_page_errors',
					esc_attr('ks-contestant-bulkresend-updated'),
					"Resent confirmation emails to all unconfirmed contestants.",
					'updated'
				);

				break;
			case 'blockip':
				if (isset($_GET['blockip']) && wp_verify_nonce($_GET['_wpnonce'], 'blockip')) {
					if ($post = get_post($id)) {
						if ($post->post_type === KS_GIVEAWAYS_POST_TYPE) {
							// Get existing list of blocked IPs
							$ip = $_GET['blockip'];
							$blocked_ips = get_post_meta($post->ID, '_blocked_ips', true);

							if (!is_array($blocked_ips)) {
								$blocked_ips = array();
							}

							if (!in_array($ip, $blocked_ips)) {
								$blocked_ips[] = $ip;
								update_post_meta($post->ID, '_blocked_ips', $blocked_ips);
							}

							add_settings_error(
								'ks_admin_contestant_page_errors',
								esc_attr('ks-contestant-bulkremove-updated'),
								sprintf("Blocked %s from creating future entries for this giveaway.", $ip),
								'updated'
							);
						}
					}
				}
				
				break;
			case 'unblockip':
				if (isset($_GET['unblockip']) && wp_verify_nonce($_GET['_wpnonce'], 'unblockip')) {
					if ($post = get_post($id)) {
						if ($post->post_type === KS_GIVEAWAYS_POST_TYPE) {
							// Get existing list of blocked IPs
							$ip = $_GET['unblockip'];
							$blocked_ips = get_post_meta($post->ID, '_blocked_ips', true);

							if (is_array($blocked_ips)) {
								$blocked_ips = array_diff($blocked_ips, array($ip));
								update_post_meta($post->ID, '_blocked_ips', $blocked_ips);
							}

							add_settings_error(
								'ks_admin_contestant_page_errors',
								esc_attr('ks-contestant-bulkremove-updated'),
								sprintf("Unblocked %s from creating future entries for this giveaway.", $ip),
								'updated'
							);
						}
					}
				}
				
				break;
		}

		$list_table->prepare_items();

		require_once KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'contestants.php';
	}

	public function add_meta_boxes($post_type)
	{
		global $wp_meta_boxes, $post;

		// remove all unwanted meta boxes
		foreach (array_keys($wp_meta_boxes) as $screen) {
			foreach (array_keys($wp_meta_boxes[$screen]) as $context) {
				foreach (array_keys($wp_meta_boxes[$screen][$context]) as $priority) {
					foreach (array_keys($wp_meta_boxes[$screen][$context][$priority]) as $id) {
						if (in_array($id, array('submitdiv', 'slugdiv'))) {
							continue;
						}
						remove_meta_box($id, $screen, $context);
					}
				}
			}
		}

		add_meta_box('ks_contest_info', __('Step 1 &mdash; Giveaway Information'), array($this, 'info_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'high');
		add_meta_box('ks_contest_share_info', __('Step 2 &mdash; Extra Entry Actions'), array($this, 'entry_actions_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'high');
		add_meta_box('ks_contest_prize', __('Step 3 &mdash; Prize Information'), array($this, 'prize_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default');
		add_meta_box('ks_contest_question', __('Step 4 &mdash; Qualifying Question'), array($this, 'question_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default');
		add_meta_box('ks_contest_images', __('Step 5 &mdash; Design'), array($this, 'design_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default');
		add_meta_box('ks_contest_share_options', __('Step 7 &mdash; Sharing Options'), array($this, 'share_options_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default');

		if ($post->ID) {
			add_meta_box('ks_contest_shortcode', __('Embed Code'), array($this, 'shortcode_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'side');
		}

		// Avoid unnecessary display of services meta box if no services have been defined
		// Also, define variables here so the long API calls don't have to be made again.
		$ks_giveaways_aweber_valid = KS_Giveaways_Aweber::is_valid();
		$ks_giveaways_mailchimp_valid = KS_Giveaways_Mailchimp::is_valid();
		$ks_giveaways_getresponse_valid = KS_Giveaways_GetResponse::is_valid();
		$ks_giveaways_campaignmonitor_valid = KS_Giveaways_CampaignMonitor::is_valid();
		$ks_giveaways_convertkit_valid = KS_Giveaways_ConvertKit::is_valid();
		$ks_giveaways_activecampaign_valid = KS_Giveaways_ActiveCampaign::is_valid();
		$ks_giveaways_sendfox_valid = KS_Giveaways_SendFox::is_valid();

		if(
			true || // Always show services meta box now since Zapier can always be an option.
			$ks_giveaways_aweber_valid
			or $ks_giveaways_mailchimp_valid
			or $ks_giveaways_getresponse_valid
			or $ks_giveaways_campaignmonitor_valid
			or $ks_giveaways_convertkit_valid
			or $ks_giveaways_activecampaign_valid
			or $ks_giveaways_sendfox_valid
		)
		{
			add_meta_box('ks_contest_services', __('Step 6 &mdash; Services'), array($this, 'services_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default', array(
				'ks_giveaways_aweber_valid' => (isset($ks_giveaways_aweber_valid)) ? $ks_giveaways_aweber_valid : NULL,
				'ks_giveaways_mailchimp_valid' => (isset($ks_giveaways_mailchimp_valid)) ? $ks_giveaways_mailchimp_valid : NULL,
				'ks_giveaways_getresponse_valid' => (isset($ks_giveaways_getresponse_valid)) ? $ks_giveaways_getresponse_valid : NULL,
				'ks_giveaways_campaignmonitor_valid' => (isset($ks_giveaways_campaignmonitor_valid)) ? $ks_giveaways_campaignmonitor_valid : NULL,
				'ks_giveaways_convertkit_valid' => (isset($ks_giveaways_convertkit_valid)) ? $ks_giveaways_convertkit_valid : NULL,
				'ks_giveaways_activecampaign_valid' => (isset($ks_giveaways_activecampaign_valid)) ? $ks_giveaways_activecampaign_valid : NULL,
				'ks_giveaways_sendfox_valid' => (isset($ks_giveaways_sendfox_valid)) ? $ks_giveaways_sendfox_valid : NULL,
			));
		}
	}

	public function info_meta_box($post)
	{
		$shortcodes = $this->get_shortcodes();
		$default_rules = file_get_contents(KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'default_rules.php');
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_info.php';
	}

	public function entry_actions_meta_box($post)
	{
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_entry_actions.php';
	}

	public function share_options_meta_box($post)
	{
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_share_options.php';
	}

	public function question_meta_box($post)
	{
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_question.php';
	}

	public function prize_meta_box($post)
	{
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_prize.php';
	}

	public function design_meta_box($post)
	{
		$templates = KS_Giveaways::get_available_templates();

		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_design.php';
	}

	public function services_meta_box($post, $args)
	{
		/* Used in template */
		/** @noinspection PhpUnusedLocalVariableInspection */
		$valid_services = $args['args'];

		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_services.php';
	}

	public function shortcode_meta_box($post)
	{
		$templates = KS_Giveaways::get_available_templates();

		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_shortcode.php';
	}

	public function append_submit_metabox()
	{
		include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_stats.php';
	}

	public function set_contest_columns($columns)
	{
		$columns = array();

		$columns['cb'] = '<input type="checkbox" />';
		$columns['title'] = 'Title';
		$columns['shortcode'] = 'Shortcode';
		$columns['num_contestants'] = 'Contestants';
		$columns['num_entries'] = 'Entries';
		$columns['status'] = 'Status';
		$columns['time_remaining'] = 'Remaining';

		return $columns;
	}

	public function display_contest_column($column, $post_id)
	{
		switch($column)
		{
			case 'shortcode':
				echo '[giveaway id=' . $post_id . ']';
				break;

			case 'num_contestants':
				echo sprintf('<a href="%s">%d</a>', admin_url('admin.php?page=ks-giveaways&action=contestants&id=' . $post_id), KS_Contestant_DB::get_total($post_id));
				break;

			case 'num_entries':
				echo KS_Entry_DB::get_contest_total($post_id);
				break;

			case 'status':
				$started = KS_Helper::has_started($post_id);
				$ended = KS_Helper::has_ended($post_id);

				if (!$started) {
					echo 'Pending';
				} else if ($started && !$ended) {
					echo 'Running';
				} else if ($ended) {
					echo 'Ended';
				}
				break;

			case 'time_remaining':
				$started = KS_Helper::has_started($post_id);
				$ended = KS_Helper::has_ended($post_id);

				if (!$started) {
					$start = KS_Helper::get_date_start($post_id);
					if ($start) {
						echo KS_Helper::time_between(time(), $start);
					} else {
						echo 'N/A';
					}
				} else if ($ended) {
					echo 'N/A';
				} else if ($started && !$ended) {
					$end = KS_Helper::get_date_end($post_id);
					if ($end) {
						echo KS_Helper::time_between(time(), $end);
					} else {
						echo 'N/A';
					}
				}
				break;
		}
	}

	public function set_page_row_actions($actions, $post)
	{
		if ($post->post_type != KS_GIVEAWAYS_POST_TYPE) {
			return $actions;
		}

		unset($actions['inline hide-if-no-js']);
		$actions['view'] = sprintf('<a href="%s">' . __('Manage Giveaway', KS_GIVEAWAYS_TEXT_DOMAIN) . '</a>', admin_url('admin.php?page=ks-giveaways&action=view&id=' . $post->ID));
		$actions['contestants'] = sprintf('<a href="%s">' . __('View Contestants', KS_GIVEAWAYS_TEXT_DOMAIN) . '</a>', admin_url('admin.php?page=ks-giveaways&action=contestants&id=' . $post->ID));
		$actions['duplicate'] = sprintf('<a href="%s">' . __('Duplicate', KS_GIVEAWAYS_TEXT_DOMAIN) . '</a>', wp_nonce_url(admin_url('admin.php?action=duplicate_giveaway&id=' . $post->ID), 'duplicate_nonce'));

		return $actions;
	}

	public function set_page_views($views)
	{
		unset($views['publish']);

		return $views;
	}

	private function has_valid_license()
	{
		return (get_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS) == 'valid');
	}

	public function current_screen($screen)
	{
		if ($screen && isset($screen->id) && isset($screen->post_type) && !$this->has_valid_license() && in_array($screen->id, array('edit-'.KS_GIVEAWAYS_POST_TYPE, KS_GIVEAWAYS_POST_TYPE)) && $screen->post_type == KS_GIVEAWAYS_POST_TYPE) {
			$url = $url = add_query_arg(['page' => 'ks-giveaways-options'], admin_url('options-general.php'));
			wp_redirect($url);
			exit;
		}
	}

	public function default_hidden_meta_boxes($hidden, $screen)
	{
		if (isset($screen->id) && $screen->id == KS_GIVEAWAYS_POST_TYPE) {
			$hidden = array_merge($hidden, array('slugdiv'));
		}

		return $hidden;
	}

	public function check_plugin_health()
	{
		$errors = array();

		$tables = array(
			'ks_giveaways_contestant',
			'ks_giveaways_entry',
			'ks_giveaways_winner'
		);

		foreach ($tables as $table) {
			try {
				KS_Debug::check_table_exists($table);
			}
			catch (Exception $e) {
				$errors[] = sprintf('<strong>Fatal:</strong> %s.  Please <a href="%s">click here</a> to try forcing table creation.', $e->getMessage(), admin_url('admin.php?page=ks-giveaways&action=database&noheader=true'));
			}
		}

		if (!KS_Debug::php_version('5.3')) {
			$errors[] = sprintf('<strong>Warning:</strong> PHP version 5.3 or higher is recommended.  Your version is: %s.', PHP_VERSION);
		}

		if (KS_Debug::is_wp_engine()) {
			$errors[] = sprintf('<strong>Warning:</strong> To avoid caching issues on WP Engine please see <a href="%s" target="_blank">this FAQ entry</a>.', 'https://wordpress.kingsumo.com/ufaqs/site-hosted-wp-engine-will-giveaways-still-work/');
		}

		$wpseo = get_option('wpseo_permalinks');
		if (is_array($wpseo) && isset($wpseo['cleanpermalinks']) && $wpseo['cleanpermalinks'] == true) {
			$errors[] = sprintf('<strong>Fatal:</strong> WordPress SEO redirect ugly URL\'s setting is enabled.  Please <a href="%s">click here</a> and disable the setting to avoid redirect loops.', admin_url('admin.php?page=wpseo_permalinks'));
		}

		$update_plugins = get_site_transient('update_plugins');
		if (is_object($update_plugins) && is_array($update_plugins->response)) {
			$plugins = $update_plugins->response;
			$data = get_plugin_data(KS_GIVEAWAYS_PLUGIN_FILE);
			if (is_array($data)) {
				$current_version = $data['Version'];
				$plugin_file = plugin_basename(KS_GIVEAWAYS_PLUGIN_FILE);
				if (isset($plugins[$plugin_file])) {
					$new_version = $plugins[$plugin_file]->new_version;
					if (version_compare($current_version, $new_version, '<')) {
						$errors[] = sprintf('<strong>Notice:</strong> A new version of KingSumo Giveaways is available.  <a href="%s">Apply update now</a>.', admin_url('plugins.php?plugin_status=upgrade'));
					}
				}
			}
		}

		if (!function_exists('curl_version')) {
			$errors[] = '<strong>Warning:</strong> cURL PHP extension not found.  Please contact your host to have them install the PHP cURL extension on your web server.';
		}

		global $wp_rewrite;

		if ($wp_rewrite->permalink_structure) {
			$flush = true;
			$rules = $wp_rewrite->wp_rewrite_rules();
			foreach ($rules as $key => $path) {
				if (strpos($path, 'index.php?' . KS_GIVEAWAYS_POST_TYPE) !== false) {
					$flush = false;
					break;
				}
			}

			if ($flush) {
				$url = admin_url('admin.php?page=ks-giveaways&action=flush&noheader=true');

				$errors[] = sprintf('<strong>Fatal:</strong> Unable to locate giveaway rewrite rules.  Please <a href="%s">click here</a> to flush the WordPress rewrite cache.', $url);
			}
		}

		if (count($errors)) {
			$errors = implode("<br />", $errors);
			echo <<<EOF
<div class="error notice is-dismissible">
  <h3>KingSumo Giveaways found the following issues:</h3>
  <p>{$errors}</p>
</div>
EOF;
		}
	}

	public function admin_notices()
	{
		$url = menu_page_url('ks-giveaways-options', false);

		$screen = get_current_screen();
		if ($screen && isset($screen->id) && isset($screen->post_type) && in_array($screen->id, array('edit-'.KS_GIVEAWAYS_POST_TYPE, KS_GIVEAWAYS_POST_TYPE)) && $screen->post_type === KS_GIVEAWAYS_POST_TYPE) {
			$this->check_plugin_health();
		}

		if (!trim(get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY))) {
			echo <<<EOF
<div class="error ks-license-error"><p>
<strong>KingSumo Giveaways</strong> does not have a license key yet.
&nbsp;
<a href="{$url}">Click here</a> to enter your license key (Settings &#8594; KingSumo Giveaways)
</p></div>
EOF;
		} else if (get_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS) != 'valid') {
		  echo <<<EOF
<div class="error ks-license-error"><p>
<strong>KingSumo Giveaways</strong> has not been activated yet.
&nbsp;
<a href="{$url}">Click here</a> to activate your license (Settings &#8594; KingSumo Giveaways)
</p></div>
EOF;
		}
	}


	public function duplicate_giveaway()
	{
		// Make sure we have a giveaway ID to work with.
		if (! array_key_exists('id', $_GET) || !isset($_GET['id'])) {
			wp_die('No giveaway to duplicate has been supplied!');
		}

		// Nonce verification
		if (! isset($_GET['_wpnonce'] ) || ! wp_verify_nonce($_GET['_wpnonce'], 'duplicate_nonce')) {
			wp_die('Invalid nonce.');
			return;
		}

		if ($post = get_post($_GET['id'])) {
			if ($post->post_type !== KS_GIVEAWAYS_POST_TYPE) {
				wp_die('Post is not a giveaway.');
			}

			$current_user = wp_get_current_user();
			$new_post_author = $current_user->ID;

			// New post data array
			$args = array(
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_title'     => $post->post_title,
				'post_excerpt'   => $post->post_excerpt,
				'post_status'    => 'draft',
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_password'  => $post->post_password,
				'post_name'      => $post->post_name,
				'to_ping'        => $post->to_ping,
				'post_parent'    => $post->post_parent,
				'menu_order'     => $post->menu_order,
				'post_type'      => $post->post_type,
			);

			//Create post
			$new_post_id = wp_insert_post($args);

			// Duplicate post meta
			foreach (get_post_meta($post->ID) as $key => $value) {
				if ($key !== '_wp_old_slug') {
					if (is_array($value) && count($value) === 1) {
						update_post_meta($new_post_id, $key, $value[0]);

					} else {
						update_post_meta($new_post_id, $key, $value);
					}
				}
			}

			// Send to post editor
			wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
			exit;
		}
	}

}
