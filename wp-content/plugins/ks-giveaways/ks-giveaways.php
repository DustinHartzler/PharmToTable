<?php
/**
 * WordPress Plugin Boilerplate.
 *
 * @wordpress-plugin
 * Plugin Name:       KingSumo Giveaways
 * Plugin URI:        http://wordpress.kingsumo.com/apps/giveaways
 * Description:       Viral Giveaways for WordPress.
 * Version:           1.8.10
 * Author:            KingSumo
 * Author URI:        http://wordpress.kingsumo.com
 * Text Domain:       ks-giveaways
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

// ensure it matches EDD backend
define('KS_GIVEAWAYS_EDD_NAME', 'KingSumo Giveaways');
define('KS_GIVEAWAYS_EDD_VERSION', '1.8.10');
define('KS_GIVEAWAYS_EDD_URL', 'http://wordpress.kingsumo.com');
define('KS_GIVEAWAYS_EDD_AUTHOR', 'KingSumo');

define('KS_GIVEAWAYS_POST_TYPE', 'ks_giveaway');
define('KS_GIVEAWAYS_TEXT_DOMAIN', 'ks-giveaways');

define('KS_GIVEAWAYS_PLUGIN_FILE', __FILE__);
define('KS_GIVEAWAYS_PLUGIN_DIR', dirname(__FILE__));
define('KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'includes');

define('KS_GIVEAWAYS_PLUGIN_PUBLIC_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public');
define('KS_GIVEAWAYS_PLUGIN_PUBLIC_INCLUDES_DIR', KS_GIVEAWAYS_PLUGIN_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'includes');
define('KS_GIVEAWAYS_PLUGIN_PUBLIC_VIEWS_DIR', KS_GIVEAWAYS_PLUGIN_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'views');

define('KS_GIVEAWAYS_PLUGIN_TEMPLATES_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates');

define('KS_GIVEAWAYS_PLUGIN_ADMIN_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'admin');
define('KS_GIVEAWAYS_PLUGIN_ADMIN_INCLUDES_DIR', KS_GIVEAWAYS_PLUGIN_ADMIN_DIR . DIRECTORY_SEPARATOR . 'includes');
define('KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR', KS_GIVEAWAYS_PLUGIN_ADMIN_DIR . DIRECTORY_SEPARATOR . 'views');

define('KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY', 'ks_giveaways_captcha_site_key');
define('KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY', 'ks_giveaways_captcha_secret_key');

define('KS_GIVEAWAYS_OPTION_LICENSE_KEY', 'ks_giveaways_license_key');
define('KS_GIVEAWAYS_OPTION_LICENSE_STATUS', 'ks_giveaways_license_status');
define('KS_GIVEAWAYS_OPTION_TWITTER_VIA', 'ks_giveaways_twitter_via');
define('KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE', 'ks_giveaways_facebook_page_id');
define('KS_GIVEAWAYS_OPTION_YOUTUBE_URL', 'ks_giveaways_youtube_url');
define('KS_GIVEAWAYS_OPTION_INSTAGRAM_URL', 'ks_giveaways_instagram_url');
define('KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS', 'ks_giveaways_entry_actions');

define('KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS', 'ks_giveaways_email_from_address');
define('KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS', 'ks_giveaways_email_replyto_address');

define('KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME', 'ks_giveaways_giveaways_ask_name');

define('KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS', 'ks_giveaways_entry_email_suppress');
define('KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT', 'ks_giveaways_entry_email_subject');
define('KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY', 'ks_giveaways_entry_email_body');

define('KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT', 'ks_giveaways_winner_email_subject');
define('KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY', 'ks_giveaways_winner_email_body');

define('KS_GIVEAWAYS_OPTION_ADDRESS_STREET', 'ks_giveaways_address_street');
define('KS_GIVEAWAYS_OPTION_ADDRESS_CITY', 'ks_giveaways_address_city');
define('KS_GIVEAWAYS_OPTION_ADDRESS_STATE', 'ks_giveaways_address_state');
define('KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY', 'ks_giveaways_address_country');
define('KS_GIVEAWAYS_OPTION_ADDRESS_ZIP', 'ks_giveaways_address_zip');

define('KS_GIVEAWAYS_OPTION_EXTRA_FOOTER', 'ks_giveaways_extra_footer');
define('KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER', 'ks_giveaways_extra_contestant_footer');

define('KS_GIVEAWAYS_OPTION_DRAW_MODE', 'all');
define('KS_GIVEAWAYS_OPTION_SYNC_WHEN', 'ks_giveaways_sync_when');

define('KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID', 'ks_giveaways_aweber_list_id');
define('KS_GIVEAWAYS_OPTION_AWEBER_KEY', 'ks_giveaways_aweber_key');
define('KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_KEY', 'ks_giveaways_aweber_consumer_key');
define('KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_SECRET', 'ks_giveaways_aweber_consumer_secret');
define('KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_KEY', 'ks_giveaways_aweber_access_key');
define('KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_SECRET', 'ks_giveaways_aweber_access_secret');

define('KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID', 'ks_giveaways_mailchimp_list_id');
define('KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY', 'ks_giveaways_mailchimp_key');

define('KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID', 'ks_giveaways_getresponse_campaign_id');
define('KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY', 'ks_giveaways_getresponse_key');

define('KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_API_KEY', 'ks_giveaways_campaignmonitor_api_key');
define('KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID', 'ks_giveaways_campaignmonitor_list_id');

define('KS_GIVEAWAYS_OPTION_CONVERTKIT_API_KEY', 'ks_giveaways_convertkit_api_key');
define('KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID', 'ks_giveaways_convertkit_form_id');

define('KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY', 'ks_giveaways_activecampaign_api_key');
define('KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL', 'ks_giveaways_activecampaign_api_url');
define('KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID', 'ks_giveaways_activecampaign_list_id');

define('KS_GIVEAWAYS_OPTION_SENDFOX_TOKEN', 'ks_giveaways_sendfox_token');
define('KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID', 'ks_giveaways_sendfox_tag_id');

define('KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL', 'ks_giveaways_zapier_trigger_url');

define('KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE', 'ks_giveaways_show_ks_badge');

define('KS_GIVEAWAYS_COOKIE_CONTESTANT', 'ks_giveaways_contestant');
define('KS_GIVEAWAYS_COOKIE_CONTESTANT_HASH', 'ks_giveaways_contestant_hash');
define('KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS', 'ks_giveaways_email');
define('KS_GIVEAWAYS_COOKIE_FIRST_NAME', 'ks_giveaways_first_name');

define('KS_GIVEAWAYS_TRANSIENT_CONVERSION', 'ks_giveaways_transient_conversion');

require_once(plugin_dir_path(__FILE__) . 'public/class-ks-giveaways.php');

register_activation_hook(__FILE__, array('KS_Giveaways', '_activate'));
register_deactivation_hook(__FILE__, array('KS_Giveaways', '_deactivate'));

add_action('plugins_loaded', array('KS_Giveaways', 'get_instance'));
add_action('plugins_loaded', array('KS_Giveaways', 'load_text_domain'));

// ensure we activate on multisite WP
add_action('wpmu_new_blog', 'ks_giveaways_new_blog', 10, 6);
function ks_giveaways_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta)
{
    global $wpdb;

    $plugin = plugin_basename(KS_GIVEAWAYS_PLUGIN_FILE);
    if (is_plugin_active_for_network($plugin)) {
        $old_blog = $wpdb->blogid;
        switch_to_blog($blog_id);
        KS_Giveaways::activate();
        switch_to_blog($old_blog);
    }
}

if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . 'admin/class-ks-giveaways-admin.php');
    add_action('plugins_loaded', array('KS_Giveaways_Admin', 'get_instance'));
}

