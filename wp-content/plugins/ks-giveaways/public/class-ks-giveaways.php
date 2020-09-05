<?php
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-contestant-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-entry-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-winner-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-aweber.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-mailchimp.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-getresponse.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-campaignmonitor.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-convertkit.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-activecampaign.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-sendfox.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-zapier.php';

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-widget.php';

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-http.php';

/**
 * @package     KS_Giveaways
 */
class KS_Giveaways
{
    /**
     * Version used for stylesheet and Javascript assets.
     */
    const VERSION = KS_GIVEAWAYS_EDD_VERSION;

    protected $plugin_slug = 'ks-giveaways';

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    public static $default_template = '%wp_content_dir%/plugins/ks-giveaways/templates/responsive3/index.php';

    private function __construct()
    {
        add_action('init', array($this, 'init'), 1);

        add_action('template_redirect', array($this, 'template_redirect'));
        add_action('ks_giveaways_add_contestant', array($this, 'new_contestant_added'));
        add_action('ks_giveaways_confirm_contestant', array($this, 'contestant_confirmed'));
        add_filter('wpseo_whitelist_permalink_vars', array($this, 'wpseo_whitelist_permalink_vars'));
        add_shortcode('giveaway', array($this, 'post_shortcode'));
        add_action('widgets_init', array($this, 'widget'));

        add_action('wp_ajax_ks_giveaways_api_endpoint', array($this, 'ks_giveaways_api_endpoint'));
        add_action('wp_ajax_nopriv_ks_giveaways_api_endpoint', array($this, 'ks_giveaways_api_endpoint'));
        //add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function wpseo_whitelist_permalink_vars($whitelisted_extravars)
    {
        $whitelisted_extravars = array_merge($whitelisted_extravars, array('lucky','confirm','key'));

        return $whitelisted_extravars;
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

    public static function _activate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite() && $network_wide) {
          self::_network_call(array(__CLASS__, 'activate'), $network_wide);
        } else {
          self::activate();
        }
    }

    public static function _deactivate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite() && $network_wide) {
          self::_network_call(array(__CLASS__, 'deactivate'), $network_wide);
        } else {
          self::deactivate();
        }
    }

    protected static function _network_call($func)
    {
        $args = func_get_args();
        $func = array_shift($args);

        global $wpdb;

        $old_blog = $wpdb->blogid;

        $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
        foreach ($blogids as $blog_id) {
            switch_to_blog($blog_id);
            call_user_func_array($func, $args);
        }
        switch_to_blog($old_blog);
    }

    public static function activate()
    {
        self::register_post_types();
        flush_rewrite_rules();

        self::check_database_tables();
        self::check_default_options();
    }

    public static function deactivate()
    {
    }

    public function get_plugin_slug()
    {
        return $this->plugin_slug;
    }

    /**
     * Load the plugin text domain for translation.
     */
    public static function load_text_domain()
    {
        $domain = KS_GIVEAWAYS_TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
    }

    /**
     * Register and enqueue the public-facing stylesheet.
     */
    public function enqueue_styles()
    {
        wp_register_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__));
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), self::VERSION);
    }

    /**
     * Register and enqueue public-facing Javascript files.
     */
    public function enqueue_scripts()
    {
        $this->enqueue_styles();
        wp_enqueue_script('jquery-plugin', plugins_url('assets/js/jquery.plugin.min.js', __FILE__), array('jquery'), self::VERSION);
        wp_enqueue_script('jquery-countdown', plugins_url('assets/js/jquery.countdown.min.js', __FILE__), array('jquery', 'jquery-plugin'), self::VERSION);
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('assets/js/public.js', __FILE__), array('jquery', 'jquery-countdown'), self::VERSION);
    }

    public function init()
    {
        self::check_database_tables();
        self::register_post_types();
    }

    public static function check_default_options()
    {
        // add default options to DB
        add_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS, sprintf('%s <%s>', get_bloginfo('name'), get_bloginfo('admin_email')));
        add_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT, 'Confirm your entry for "[name]"');
        add_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY, <<<EOF
<p>Thanks for entering "[name]".</p>
<p>Visit <a href="[confirm_url]">[confirm_url]</a> to confirm your entry.</p>
<p>Don't forget to share your lucky URL as much as possible to increase your chances of winning.  You will receive [entries_per_friend] extra entries for every person who enters via your lucky URL.</p>
<p>Your Lucky URL: <a href="[lucky_url]">[lucky_url]</a></p>
<p>
Regards,<br>
[site_name]</p>
EOF
        );

        add_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT, 'Congratulations! You won the "[name]" giveaway');
        add_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY, <<<EOF
<p>Thanks for entering "[name]".</p>
<p>We are just letting you know that you have won, how awesome is that?!</p>
<p>Stay tuned and we will contact you soon about collecting your [prize_name].</p>
<p>
Regards,<br>
[site_name]</p>
EOF
        );
    }

    public static function check_database_tables()
    {
        KS_Contestant_DB::check_database_table(self::VERSION);
        KS_Entry_DB::check_database_table(self::VERSION);
        KS_Winner_DB::check_database_table(self::VERSION);
    }

    public static function register_post_types()
    {
        $labels = array(
            'name' => 'Giveaways',
            'singular_name' => 'Giveaway',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Giveaway',
            'edit_item' => 'Edit Giveaway',
            'new_item' => 'New Giveaway',
            'view_item' => 'View Giveaway',
            'search_items' => 'Search Giveaways',
            'not_found' => 'No giveaways found',
            'not_found_in_trash' => 'No giveaways found in trash',
            'menu_name' => 'KingSumo Giveaways',
            'all_items' => 'All Giveaways'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'public_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'giveaways', 'with_front' => true),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => array('title'),
            'menu_icon' => plugin_dir_url(__FILE__) . '../admin/assets/images/ks-giveaways-icon-wp-solid-detailed.png'
        );

        register_post_type(
            KS_GIVEAWAYS_POST_TYPE,
            $args
        );
    }

    public function verify_captcha($response)
    {
        $args = array(
            'secret' => get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY),
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $qs = http_build_query($args);
        $ret = KS_Http::get('https://www.google.com/recaptcha/api/siteverify?' . $qs);
        if ($ret) {
          $answers = json_decode($ret, true);
          if (is_array($answers) && trim($answers['success'])) {
              return true;
          }
        }

        return false;
    }

    public function template_redirect()
    {
        if (!is_single()) {
            return;
        }

        $post = get_post();
        if (get_post_type($post->ID) != KS_GIVEAWAYS_POST_TYPE) {
            return;
        }

        // Tell CloudFlare not to cache
        header('Cache-Control: private, no-cache, no-store, max-age=0, must-revalidate, proxy-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.

        define('DONOTCACHEPAGE', true);
        define('DONOTCACHEDB', true);
        define('DONOTCACHCEOBJECT', true);
        define('DONOTMINIFY', true);

        if (!is_preview() && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // POSTing to giveaway
            if (KS_Helper::is_running($post)) {

                $first_name = isset($_POST['giveaways_first_name']) ? KS_Helper::sanitise_first_name($_POST['giveaways_first_name']) : null;
                $email = isset($_POST['giveaways_email']) ? sanitize_email($_POST['giveaways_email']) : null;
                $answer = isset($_POST['giveaways_answer']) ? $_POST['giveaways_answer'] : null;
                $passed_sig = isset($_POST['giveaways_sig']) ? $_POST['giveaways_sig'] : null;
                $first_name_field_active = isset($_POST['first_name_field_active']) ? $_POST['first_name_field_active'] : null;
                $from_widget = isset($_POST['widget']) ? true : false;

                $right_answer = htmlspecialchars_decode(KS_Helper::get_right_answer($post));

                if(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME)) {
                    $calculated_sig = md5($right_answer . '|' . $email . '|' . str_replace('\\', '', $first_name));
                } else {
                    $calculated_sig = md5($right_answer . '|' . $email);
                }

                $referral_id = isset($_REQUEST['lucky']) ? (int) $_REQUEST['lucky'] : null;
                if (!$referral_id) $referral_id = null;

                if (empty($_POST['giveaways_nonce']) || !wp_verify_nonce($_POST['giveaways_nonce'], 'ks_giveaways_form')) {
                  $GLOBALS['ks_giveaways_error_message'] = 'CSRF form token verification failed.';

                } else if (!$from_widget && get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY) && !$this->verify_captcha($_POST['g-recaptcha-response'])) {
                  $GLOBALS['ks_giveaways_error_message'] = 'Google reCaptcha verification failed.';

                } else if (!$from_widget && $calculated_sig != $passed_sig) {
                  $GLOBALS['ks_giveaways_error_message'] = 'Incorrect answer signature specified.';

                } else if (!$email) {
                  $GLOBALS['ks_giveaways_error_message'] = 'No email address specified.';

                } else if (!$from_widget && get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME) && !$first_name) {
                    $GLOBALS['ks_giveaways_error_message'] = 'No first name specified.';

                } else {
                    $pi = parse_url(get_permalink());
                    $base = dirname(rtrim($pi['path'], '/')) . '/';
                    setcookie(KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS, $email, time() + YEAR_IN_SECONDS, $base);
                    setcookie(KS_GIVEAWAYS_COOKIE_FIRST_NAME, $first_name, time() + YEAR_IN_SECONDS, $base);

                    $contestant = $this->add_contestant_entry($post->ID, $email, $referral_id, $first_name);
                    if ($contestant) {
                        $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID, $contestant->ID);
                        $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT_HASH.$post->ID, sha1($contestant->email_address));

                        // Set transient so we can distinguish this redirect to the lucky url as the unique entry.
                        set_transient(KS_GIVEAWAYS_TRANSIENT_CONVERSION . $contestant->ID, 'true', MINUTE_IN_SECONDS);

                        // redirect to lucky URL
                        $url = KS_Helper::get_lucky_url($post, $contestant);
                        wp_redirect($url);
                        exit;
                    } else {
                      $GLOBALS['ks_giveaways_error_message'] = 'Unspecified error saving entry.';
                    }
                }
            } else {
              $GLOBALS['ks_giveaways_error_message'] = 'The giveaway is not currently running.';
            }
        } else if (!is_preview() && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['confirm']) && isset($_GET['key'])) {
            // confirming email address
            $id = $_GET['confirm'];
            $key = $_GET['key'];

            $contestant = KS_Contestant_DB::get($id);
            if ($contestant) {
                if ($contestant->confirm_key == $key) {
                    KS_Contestant_DB::update_status($contestant->ID, 'confirmed');

                    if ($contestant->status !== 'confirmed') {
                        do_action('ks_giveaways_confirm_contestant', $contestant);
                    }

                    $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID, $contestant->ID);
                    $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT_HASH.$post->ID, sha1($contestant->email_address));

                    // redirect to lucky URL
                    $url = KS_Helper::get_lucky_url($post, $contestant);
                    wp_redirect($url);
                    exit;
                } else {
                  $GLOBALS['ks_giveaways_error_message'] = 'The specified confirmation key is incorrect.';
                }
            } else {
              $GLOBALS['ks_giveaways_error_message'] = 'The specified contestant does not exist.';
            }
        } else if (!is_preview() && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_COOKIE[KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID])) {
            // returning after a cookie was set
            $id = $_COOKIE[KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID];
            $hash = isset($_COOKIE[KS_GIVEAWAYS_COOKIE_CONTESTANT_HASH.$post->ID]) ? $_COOKIE[KS_GIVEAWAYS_COOKIE_CONTESTANT_HASH.$post->ID] : null;

            $contestant = KS_Contestant_DB::get($id);

            if ($contestant) {
                $GLOBALS['ks_giveaways_contestant'] = $contestant;

                if (KS_Helper::is_running($post)) {
                    // handle referral - credit logged in user with referral if needed
                    $referral_id = isset($_REQUEST['lucky']) ? (int) $_REQUEST['lucky'] : null;
                    if (!$referral_id) $referral_id = null;
                    $referral = $referral_id ? KS_Contestant_DB::get($referral_id, $post->ID) : null;
                    if ($hash && $referral && $referral->ID != $contestant->ID && sha1($contestant->email_address) === $hash) {
                        $this->add_referral_entry($post->ID, $contestant, $referral);
                    }
                }

                // redirect to lucky URL if not on it
                if (!isset($_GET['lucky']) || $_GET['lucky'] != $contestant->ID) {
                    $url = KS_Helper::get_lucky_url($post, $contestant);
                    // re-set cookie, try tell varnish not to cache
                    $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID, $contestant->ID);
                    wp_redirect($url);
                    exit;
                }
            }
        }

        $template = $this->get_template(null);
        include $template;
        exit;
    }

    /**
     * @param string $email_address
     * @param bool $global If set, override per-post list_id with the global setting. No post-related functions will be called.
     * @return bool|NULL true on success, false on failure, NULL if no services are defined.
     */
    public function sync_provider_email_address($email_address, $first_name, $global = false)
    {
        /*
         * Maybe the test should quit as soon as any exception has occurred?
         * But for now perform queries to all services to keep integrity.
         */

        $status = array();

        if (!$global) {
            $post_id = get_post()->ID;
        }

        // aweber
        $list_id = (isset($post_id)) ? get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, true) : '__global';

        switch ($list_id) {
            case '__disable':
                $list_id = false;
                break;

            case '':
            case '__global':
                $list_id = get_option(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID);
                if ($list_id === "__disable") {
                    $list_id = false;
                }
                break;

            default:
        }

        if (KS_Giveaways_Aweber::is_valid()) {
            if ($list_id) {
                $cls = KS_Giveaways_Aweber::get_instance();
                $status['Aweber'] = $cls->add_subscriber($list_id, $email_address, $first_name);
            } else {
                $status['Aweber'] = 3;
            }
        }

        // mailchimp
        $list_id = (isset($post_id)) ? get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, true) : '__global';

        switch ($list_id) {
            case '__disable':
                $list_id = false;
                break;

            case '':
            case '__global':
                $list_id = get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID);
                if ($list_id === "__disable") {
                    $list_id = false;
                }
                break;

            default:
        }

        if (KS_Giveaways_Mailchimp::is_valid()) {
            if ($list_id) {
                $cls = KS_Giveaways_Mailchimp::get_instance();
                $status['MailChimp'] = $cls->add_subscriber($list_id, $email_address, $first_name);
            } else {
                $status['MailChimp'] = 3;
            }
        }

        // GetResponse
        $campaign_id = (isset($post_id)) ? get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, true) : '__global';

        switch ($campaign_id) {
            case '__disable':
                $campaign_id = false;
                break;

            case '':
            case '__global':
                $campaign_id = get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID);
                if ($list_id === "__disable") {
                    $list_id = false;
                }
                break;

            default:
        }

        if (KS_Giveaways_GetResponse::is_valid()) {
            if ($campaign_id) {
                $cls = KS_Giveaways_GetResponse::get_instance();
                $status['GetResponse'] = $cls->add_subscriber($campaign_id, $email_address, $first_name);
            } else {
                $status['GetResponse'] = 3;
            }
        }

        // Campaign Monitor
        $list_id = (isset($post_id)) ? get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID, true) : '__global';

        switch ($list_id) {
            case '__disable':
                $list_id = false;
                break;

            case '':
            case '__global':
                $list_id = get_option(KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID);
                if ($list_id === "__disable") {
                    $list_id = false;
                }
                break;

            default:
        }

        if (KS_Giveaways_CampaignMonitor::is_valid()) {
            if ($list_id) {
                $cls = KS_Giveaways_CampaignMonitor::get_instance();
                $status['Campaign Monitor'] = $cls->add_subscriber($list_id, $email_address, $first_name);
            } else {
                $status['CampaignMonitor'] = 3;
            }
        }

        // ConvertKit
        $form_id = (isset($post_id)) ? get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID, true) : '__global';

        switch ($form_id) {
            case '__disable':
                $form_id = false;
                break;

            case '':
            case '__global':
                $form_id = get_option(KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID);
                if ($form_id === "__disable") {
                    $form_id = false;
                }
                break;

            default:
        }

        if (KS_Giveaways_ConvertKit::is_valid()) {
            if ($form_id) {
                $cls = KS_Giveaways_ConvertKit::get_instance();
                $status['ConvertKit'] = $cls->add_subscriber($form_id, $email_address, $first_name);
            } else {
                $status['ConvertKit'] = 3;
            }
        }

        // ActiveCampaign
        $list_id = (isset($post_id)) ? get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID, true) : '__global';

        switch ($list_id) {
            case '__disable':
                $list_id = false;
                break;

            case '':
            case '__global':
                $list_id = get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID);
                if ($list_id === "__disable") {
                    $list_id = false;
                }
                break;

            default:
        }

        if (KS_Giveaways_ActiveCampaign::is_valid()) {
            if ($list_id) {
                $cls = KS_Giveaways_ActiveCampaign::get_instance();
                $status['ActiveCampaign'] = $cls->add_subscriber($list_id, $email_address, $first_name);
            } else {
                $status['ActiveCampaign'] = 3;
            }
        }

        // SendFox
        $list_id = (isset($post_id)) ? get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID, true) : '__global';

        switch ($list_id) {
            case '__disable':
                $list_id = false;
                break;

            case '':
            case '__global':
                $list_id = get_option(KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID);
                if ($list_id === "__disable") {
                    $list_id = false;
                }
                break;

            default:
        }

        if (KS_Giveaways_SendFox::is_valid()) {
            if ($list_id) {
                $cls = KS_Giveaways_SendFox::get_instance();
                $status['SendFox'] = $cls->add_subscriber($list_id, $email_address, $first_name);
            } else {
                $status['SendFox'] = 3;
            }
        }

        // Zapier
        $zapierUrl = '';
        if (isset($post_id)) {
            $zapierUrl = get_post_meta($post_id, '_'.KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL, true);
        }

        if (empty($zapierUrl)) {
            $zapierUrl = get_option(KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL);
        }

        if (!empty($zapierUrl)) {
            $zapier = new KS_Giveaways_Zapier($zapierUrl);
            $status['Zapier'] = $zapier->send_entry($email_address, $first_name);
        }

        return $status;
    }

    public function contestant_confirmed($contestant)
    {
        $value = get_option(KS_GIVEAWAYS_OPTION_SYNC_WHEN, 'entry');
        if ($value !== 'confirm') return;

        $this->sync_provider_email_address($contestant->email_address, $contestant->first_name);
    }

    public function new_contestant_added($contestant)
    {
        $value = get_option(KS_GIVEAWAYS_OPTION_SYNC_WHEN, 'entry');
        if ($value !== 'entry') return;

        $this->sync_provider_email_address($contestant->email_address, $contestant->first_name);
    }

    public static function get_available_templates()
    {
        $file_headers = array(
            'TemplateName' => 'Template Name',
            'TemplateAuthor' => 'Template Author'
        );
        $path = KS_GIVEAWAYS_PLUGIN_TEMPLATES_DIR;
        $files = glob(trailingslashit($path) . '**' . DIRECTORY_SEPARATOR . '*.php');

        // normal files to correct symlinks
        foreach ($files as &$file) {
            if (strpos($file, WP_CONTENT_DIR) !== 0) {
                $file = trailingslashit(WP_PLUGIN_DIR) . plugin_basename($file);

                if (!file_exists($file) && defined('WPMU_PLUGIN_DIR')) {
                    $file = trailingslashit(WPMU_PLUGIN_DIR) . plugin_basename($file);
                }
            }
        }

        $path = trailingslashit(trailingslashit(WP_CONTENT_DIR) . 'ks-giveaways-themes');
        $custom_files = glob($path . '**' . DIRECTORY_SEPARATOR . '*.php');
        if ($custom_files) {
            $files = array_merge($files, $custom_files);
        }
        $search = array(
            untrailingslashit(WP_CONTENT_DIR)
        );
        $replace = array(
            '%wp_content_dir%'
        );

        $templateNames = array();
        $templates = array();
        foreach ($files as $file) {
            $headers = get_file_data($file, $file_headers);
            if ($headers && is_array($headers) && isset($headers['TemplateName']) && !empty($headers['TemplateName'])) {
                $file = str_replace($search, $replace, $file);

                // override previous theme with same name
                $templateName = $headers['TemplateName'];
                if (isset($templateNames[$templateName])) {
                    $headers['IsOverride'] = true;
                }

                $templates[$file] = $headers;
                $templateNames[$headers['TemplateName']] = $file;
            }
        }

        return $templates;
    }

    public function add_contestant_entry($contest_id, $email, $referral_id = null, $first_name = null)
    {
        // verify contestant is first-time
        $contestant = KS_Contestant_DB::get_existing($contest_id, $email);
        $referral = $referral_id ? KS_Contestant_DB::get($referral_id, $contest_id) : null;

        $existing_contestant = true;

        global $wpdb;

        // start transaction to avoid 0 entries
        $wpdb->query('START TRANSACTION');

        if (!$contestant) {
            $existing_contestant = false;

            // add new contestant
            $contestant = KS_Contestant_DB::add($contest_id, $email, $first_name);
            if (!$contestant) {
                $wpdb->query('ROLLBACK');

                return false;
            }

            // add contestant entry
            if (!KS_Entry_DB::add($contestant->ID, $referral ? $referral->ID : null)) {
                $wpdb->query('ROLLBACK');

                return false;
            }

            if (!get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS)) {
                KS_Helper::send_confirm_email($contestant);
            }
        }

        // handle referral
        if ($referral && $referral->ID != $contestant->ID) {
            if (!$this->add_referral_entry($contest_id, $contestant, $referral)) {
                $wpdb->query('ROLLBACK');

                return false;
            }
        }

        $wpdb->query('COMMIT');

        $GLOBALS['ks_giveaways_contestant'] = $contestant;

        if (!$existing_contestant) {
            do_action('ks_giveaways_add_contestant', $contestant);
        }

        return $contestant;
    }

    public function add_referral_entry($contest_id, $contestant, $referral)
    {
        $has_referral = KS_Entry_DB::has_referral($referral->ID, $contestant->ID);
        $entries_per_friend = KS_Helper::get_entries_per_friend($contest_id);
        if (!$has_referral) {
            for ($i = 0; $i < $entries_per_friend; $i++) {
                // add 3 more entries to the referer with his referral set as contestant
                if (!KS_Entry_DB::add($referral->ID, $contestant->ID)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function set_post_cookie($name, $value, $time = null)
    {
        if ($time === null) {
            $time = time() + YEAR_IN_SECONDS;
        }

        $pi = parse_url(get_permalink());
        setcookie($name, $value, $time, $pi['path']);

    }

    public function get_template($template)
    {
        if (is_singular(KS_GIVEAWAYS_POST_TYPE)) {
            require_once KS_GIVEAWAYS_PLUGIN_PUBLIC_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'template_functions.php';

            global $post;

            $template_file = get_post_meta($post->ID, '_template_file', true);
            if (!$template_file) {
                $template_file = KS_Giveaways::$default_template;
            }

            $search = array(
                '%wp_content_dir%'
            );
            $replace = array(
                untrailingslashit(WP_CONTENT_DIR)
            );

            $template_file = str_replace($search, $replace, $template_file);

            return $template_file;
        }

        return $template;
    }

    public function post_shortcode($attributes)
    {
        global $post;
        if ( ! is_array($attributes)) {
            return '<p><b>KingSumo giveaway id is required in shortcode.</b></p>';
        }

        if (array_key_exists('id', $attributes)) {
            $giveaway = get_post($attributes['id']);
            if ($giveaway->post_type !== KS_GIVEAWAYS_POST_TYPE) {
                return '<p><i>Post ID#' . $attributes['id'] . ' is not a valid giveaway.</i></p>';
            }

            // Get url for this giveaway
            // Pass lucky param to iframe
            if (array_key_exists('lucky', $_GET)) {
                $contestant = KS_Contestant_DB::get($_GET['lucky']);
                $url = KS_Helper::get_lucky_url($giveaway, $contestant);

            } else {
                $url = get_permalink($giveaway);

                // Add confirm link params if needed
                if (array_key_exists('confirm', $_GET) && array_key_exists('key', $_GET)) {
                    $url = add_query_arg('confirm', $_GET['confirm'], $url);
                    $url = add_query_arg('key', $_GET['key'], $url);
                }
            }

            // external=true option
            // Add ?embed_post param, but don't reference a post ID if this is being embedded on an external site.
            if (array_key_exists('external', $attributes) && $attributes['external'] == 'true') {
                $url = add_query_arg('embed_post', '', $url);

            } else {
                $url = add_query_arg('embed_post', $post->ID, $url);
            }

            $height = array_key_exists('height', $attributes) ? $attributes['height'] : 880;
            return '<iframe class="ks_giveaway_iframe" src="' . $url . '" style="width:100%;height:' . $height . 'px;border:none;"></iframe>';
        }

    }

    public function widget()
    {
        register_widget('ks_giveaways_widget');
    }

    /**
     * Handles awarding of entries when a payout link is clicked.
     * @param $_POST[giveaway_id, contestant_id, link_clicked]
     */
    public function handle_ajax_entry_action()
    {
        global $wpdb, $post;

        if (isset($_POST['giveaway_id']) && isset($_POST['contestant_id']) && isset($_POST['action_id'])) {
            $contestant = KS_Contestant_DB::get($_POST['contestant_id']);

             // First check to make sure contestant hasn't already been awarded these entries
            if (is_numeric($_POST['action_id'])) {
                $entry_actions = get_post_meta($post->ID, '_entry_actions', true);
                $existing_action_entries = KS_Entry_DB::get_action_entry_count($contestant->ID, $_POST['action_id']);

                // Get relevant action info
                foreach ($entry_actions as $action) {
                    if ($action['id'] == $_POST['action_id']) {
                        $current_action = $action;
                        break;
                    }
                }

                // start transaction to avoid 0 entries
                $wpdb->query('START TRANSACTION');

                if (!KS_Entry_DB::add($contestant->ID, NULL, $current_action['entries'] - $existing_action_entries, $_POST['action_id'])) {
                    $wpdb->query('ROLLBACK');
                    return false;
                }

                $wpdb->query('COMMIT');

                $action_entries = KS_Entry_DB::get_all_action_entries_count($contestant->ID);
            }

            $total_entries = KS_Entry_DB::get_total($contestant->ID);

            echo json_encode(array('totalEntries' => $total_entries, 'contestantActionEntries' => $action_entries));
        }
        
        exit;
    }

    public function handle_ajax_entry_form()
    {
        global $post;

        if (KS_Helper::is_running($post)) {

            $first_name = isset($_POST['giveaways_first_name']) ? KS_Helper::sanitise_first_name($_POST['giveaways_first_name']) : null;
            $email = isset($_POST['giveaways_email']) ? sanitize_email($_POST['giveaways_email']) : null;
            $answer = isset($_POST['giveaways_answer']) ? $_POST['giveaways_answer'] : null;
            $passed_sig = isset($_POST['giveaways_sig']) ? $_POST['giveaways_sig'] : null;
            $first_name_field_active = isset($_POST['first_name_field_active']) ? $_POST['first_name_field_active'] : null;
            $from_widget = isset($_POST['widget']) ? true : false;
            $blocked_ips = get_post_meta($post->ID, '_blocked_ips', true);

            $right_answer = htmlspecialchars_decode(KS_Helper::get_right_answer($post));

            if(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME)) {
                $calculated_sig = md5($right_answer . '|' . $email . '|' . str_replace('\\', '', $first_name));
            } else {
                $calculated_sig = md5($right_answer . '|' . $email);
            }

            $referral_id = isset($_REQUEST['lucky']) ? (int) $_REQUEST['lucky'] : null;
            if (!$referral_id) $referral_id = null;

            if (empty($_POST['giveaways_nonce']) || !wp_verify_nonce($_POST['giveaways_nonce'], 'ks_giveaways_form')) {
              $error = __('CSRF form token verification failed.', KS_GIVEAWAYS_TEXT_DOMAIN);

            } else if (!$from_widget && get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY) && !$this->verify_captcha($_POST['g-recaptcha-response'])) {
              $error = __('Google reCaptcha verification failed.', KS_GIVEAWAYS_TEXT_DOMAIN);

            } else if(is_array($blocked_ips) && in_array(KS_Contestant_DB::get_ip(), $blocked_ips)) {
                $error = __("Sorry, there was an error with your entry.", KS_GIVEAWAYS_TEXT_DOMAIN);

            } else if (false /*!$from_widget && $calculated_sig != $passed_sig*/) {
              $error = __('Incorrect answer signature specified.', KS_GIVEAWAYS_TEXT_DOMAIN);

            } else if (!$email) {
              $error = __('No email address specified.', KS_GIVEAWAYS_TEXT_DOMAIN);

            } else if (!$from_widget && get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME) && !$first_name) {
                $error = __('No first name specified.', KS_GIVEAWAYS_TEXT_DOMAIN);

            } else {
                $pi = parse_url(get_permalink());
                $base = dirname(rtrim($pi['path'], '/')) . '/';
                setcookie(KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS, $email, time() + YEAR_IN_SECONDS, $base);
                setcookie(KS_GIVEAWAYS_COOKIE_FIRST_NAME, $first_name, time() + YEAR_IN_SECONDS, $base);

                $contestant = $this->add_contestant_entry($post->ID, $email, $referral_id, $first_name);
                if ($contestant) {
                    $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID, $contestant->ID);
                    $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT_HASH.$post->ID, sha1($contestant->email_address));

                    // Set transient so we can distinguish this redirect to the lucky url as the unique entry.
                    set_transient(KS_GIVEAWAYS_TRANSIENT_CONVERSION . $contestant->ID, 'true', MINUTE_IN_SECONDS);

                    // redirect to lucky URL
                    $url = KS_Helper::get_lucky_url($post, $contestant);
                    echo json_encode(array('success' => true, 'redirect' => $url));
                    exit;

                } else {
                  $error = __('Unspecified error saving entry.', KS_GIVEAWAYS_TEXT_DOMAIN);
                }
            }
        } else {
          $error = __('The giveaway is not currently running.', KS_GIVEAWAYS_TEXT_DOMAIN);
        }

        echo json_encode(array('success' => false, 'error' => $error));
        exit;
    }

    /**
     * Handles awarding of entries when a payout link is clicked.
     * @param $_POST[giveaway_id, contestant_id, link_clicked]
     */
    public function ks_giveaways_api_endpoint()
    {
        global $wpdb;

        $GLOBALS['post'] = get_post($_POST['giveaway_id']);

        header("Content-Type: application/json");

        if (get_post_type($GLOBALS['post']->ID) != KS_GIVEAWAYS_POST_TYPE) {
            echo json_encode("Invalid post type.");
            exit;
        }

        if (isset($_POST['command'])) {
            if ($_POST['command'] === 'entry_form') {
                $this->handle_ajax_entry_form();

            } elseif($_POST['command'] === 'entry_action') {
                $this->handle_ajax_entry_action();
            }
        }
    }
}
