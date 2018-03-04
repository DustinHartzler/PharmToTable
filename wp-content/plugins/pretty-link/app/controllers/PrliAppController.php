<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); }

class PrliAppController extends PrliBaseController {
  public function load_hooks() {
    global $prli_options;

    add_action('init', array($this, 'parse_standalone_request'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    add_action('admin_menu', array($this, 'menu'), 3); //Hooking in earlier - there's a plugin out there somewhere breaking this action for later plugins

    //Where the magic happens when not in wp-admin nor !GET request
    if($_SERVER["REQUEST_METHOD"] == 'GET' && !is_admin()) {
      add_action('plugins_loaded', array($this, 'redirect'), 1); // Redirect
    }

    // Hook into the 'wp_dashboard_setup' action to register our other functions
    add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets'));

    add_action('after_plugin_row', array($this, 'pro_action_needed'));
    add_action('admin_notices', array($this, 'pro_get_started_headline'));

    // DB upgrades/installs will happen here, as a non-blocking process hopefully
    add_action('init', array($this, 'install'));

    add_filter( 'plugin_action_links_' . PRLI_PLUGIN_SLUG, array($this,'add_plugin_action_links') );
  }

  public function menu() {
    global $prli_options, $plp_options, $plp_update;

    $role = 'manage_options';
    if(isset($plp_options->min_role))
      $role = $plp_options->min_role;

    $prli_menu_hook = add_menu_page(
      __('Pretty Links | Manage Pretty Links', 'pretty-link'),
      __('Pretty Links', 'pretty-link'),
      $role, 'pretty-link',
      'PrliLinksController::route',
      PRLI_IMAGES_URL.'/pretty-link-small.png'
    );

    $prli_menu_hook = add_submenu_page(
      'pretty-link',
      __('Pretty Links | Manage Pretty Links', 'pretty-link'),
      __('Pretty Links', 'pretty-link'),
      $role, 'pretty-link',
      'PrliLinksController::route'
    );

    $prli_add_links_menu_hook = add_submenu_page(
      'pretty-link',
      __('Pretty Links | Add New Link', 'pretty-link'),
      __('Add New Link', 'pretty-link'),
      $role, 'add-new-pretty-link',
      'PrliLinksController::new_link'
    );

    $groups_ctrl = new PrliGroupsController();
    add_submenu_page(
      'pretty-link',
      __('Pretty Links | Groups', 'pretty-link'),
      __('Groups', 'pretty-link'),
      $role, 'pretty-link-groups',
      array( $groups_ctrl, 'route' )
    );

    if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking != 'count' ) {
      $clicks_ctrl = new PrliClicksController();
      add_submenu_page(
        'pretty-link',
        __('Pretty Links | Clicks', 'pretty-link'),
        __('Clicks', 'pretty-link'),
        $role, 'pretty-link-clicks',
        array( $clicks_ctrl, 'route' )
      );
    }

    $routes_ctrl = new PrliToolsController();
    add_submenu_page(
      'pretty-link',
      __('Pretty Links | Tools', 'pretty-link'),
      __('Tools', 'pretty-link'),
      $role, 'pretty-link-tools',
      array($routes_ctrl,'route')
    );

    $options_ctrl = new PrliOptionsController();
    add_submenu_page(
      'pretty-link',
      __('Pretty Links | Options', 'pretty-link'),
      __('Options', 'pretty-link'),
      $role, 'pretty-link-options',
      array( $options_ctrl, 'route' )
    );

    if(!defined('PRETTYLINK_LICENSE_KEY') && class_exists('PrliUpdateController')) {
      if($plp_update->is_installed_and_activated()) {
        add_submenu_page( 'pretty-link', __('Activate Pro', 'pretty-link'), __('Activate Pro', 'pretty-link'), $role, 'pretty-link-updates', array($plp_update, 'route'));
      }
      else if($plp_update->is_installed()) {
        add_submenu_page( 'pretty-link', __('Activate Pro', 'pretty-link'), '<span class="prli-menu-red"><b>'.__('Activate Pro', 'pretty-link').'</b></span>', $role, 'pretty-link-updates', array($plp_update, 'route'));
      }
      else {
        add_submenu_page( 'pretty-link', __('Upgrade to Pro', 'pretty-link'), '<span class="prli-menu-red"><b>'.__('Upgrade to Pro', 'pretty-link').'</b></span>', $role, 'pretty-link-updates', array($plp_update, 'route'));
      }
    }
  }

  public function add_plugin_action_links($links) {
    global $plp_update;

    $pllinks = array();

    if($plp_update->is_installed_and_activated()) {
      $pllinks[] = '<a href="https://prettylinks.com/pl/plugin-actions/activated/docs" target="_blank">'.__('Docs', 'pretty-link').'</a>';
      $pllinks[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=pretty-link-updates') ) .'">'.__('Activate', 'pretty-link').'</a>';
    }
    else if($plp_update->is_installed()) {
      $pllinks[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=pretty-link-updates') ) .'" class="prli-menu-green">'.__('Activate Pro License', 'pretty-link').'</a>';
      $pllinks[] = '<a href="https://prettylinks.com/pl/plugin-actions/installed/buy" target="_blank" class="prli-menu-red">'.__('Buy', 'pretty-link').'</a>';
      $pllinks[] = '<a href="https://prettylinks.com/pl/plugin-actions/installed/docs" target="_blank">'.__('Docs', 'pretty-link').'</a>';
    }
    else {
      $pllinks[] = '<a href="https://prettylinks.com/pl/plugin-actions/lite/upgrade" class="prli-menu-red" target="_blank">'.__('Upgrade to Pro', 'pretty-link').'</a>';
      $pllinks[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=pretty-link-updates') ) .'" class="prli-menu-green">'.__('Activate Pro License', 'pretty-link').'</a>';
      $pllinks[] = '<a href="https://prettylinks.com/pl/plugin-actions/lite/docs" target="_blank">'.__('Docs', 'pretty-link').'</a>';
    }

    return array_merge($pllinks, $links);
  }

  public function enqueue_admin_scripts($hook) {
    global $wp_version;

    wp_enqueue_style( 'prli-fontello-animation',
                      PRLI_VENDOR_LIB_URL.'/fontello/css/animation.css',
                      array(), PRLI_VERSION );
    wp_enqueue_style( 'prli-fontello-pretty-link',
                      PRLI_VENDOR_LIB_URL.'/fontello/css/pretty-link.css',
                      array('prli-fontello-animation'), PRLI_VERSION );

    // If we're in 3.8 now then use a font for the admin image
    if( version_compare( $wp_version, '3.8', '>=' ) ) {
      wp_enqueue_style( 'prli-menu-styles', PRLI_CSS_URL.'/menu-styles.css',
                        array('prli-fontello-pretty-link'), PRLI_VERSION );
    }

    $wp_scripts = new WP_Scripts();
    $ui = $wp_scripts->query('jquery-ui-core');
    $url = "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.css";

    if(strstr($hook, 'pretty-link') !== false) {
      wp_register_style('pl-ui-smoothness', $url);
      wp_register_style('prli-simplegrid', PRLI_CSS_URL.'/simplegrid.css', array(), PRLI_VERSION);
      wp_register_style('prli-social', PRLI_CSS_URL.'/social_buttons.css', array(), PRLI_VERSION);
      wp_enqueue_style(
        'prli-admin-shared',
        PRLI_CSS_URL.'/admin_shared.css',
        array(
          'wp-pointer',
          'prli-simplegrid',
          'prli-social',
        ),
        PRLI_VERSION
      );


      wp_register_script(
        'prli-tooltip',
        PRLI_JS_URL.'/tooltip.js',
        array('jquery', 'wp-pointer'),
        PRLI_VERSION
      );
      wp_localize_script(
        'prli-tooltip',
        'PrliTooltip',
        array(
          'show_about_notice' => $this->show_about_notice(),
          'about_notice' => $this->about_notice()
        )
      );
      wp_enqueue_script(
        'prli-admin-shared',
        PRLI_JS_URL.'/admin_shared.js',
        array(
          'jquery',
          'jquery-ui-datepicker',
          'jquery-ui-sortable',
          'prli-tooltip'
        ),
        PRLI_VERSION
      );
    }

    if( in_array( $hook, array( 'toplevel_page_pretty-link', 'pretty-links_page_add-new-pretty-link' ) ) ) {
      wp_enqueue_style( 'prli-admin-links', PRLI_CSS_URL . '/prli-admin-links.css', array(), PRLI_VERSION );
      wp_enqueue_script( 'jquery-clippy', PRLI_JS_URL . '/jquery.clippy.js', array('jquery'), PRLI_VERSION );

      wp_enqueue_script( 'prli-admin-links', PRLI_JS_URL . '/prli-admin-links.js', array('jquery','jquery-clippy'), PRLI_VERSION );

      wp_enqueue_script( 'prli-admin-link-list', PRLI_JS_URL . '/admin_link_list.js', array('jquery','jquery-clippy'), PRLI_VERSION );
      wp_localize_script( 'prli-admin-link-list', 'PrliLink', array('clippy_url' => PRLI_JS_URL.'/clippy.swf') );
    }

    if( $hook === 'pretty-links_page_pretty-link-groups' ) {
      wp_enqueue_style('pl-groups', PRLI_CSS_URL.'/admin_groups.css', null, PRLI_VERSION);
      wp_enqueue_script('pl-groups', PRLI_JS_URL.'/admin_groups.js', array('jquery'), PRLI_VERSION);
    }

    if( $hook === 'pretty-links_page_pretty-link-options' ) {
      wp_enqueue_style('pl-options', PRLI_CSS_URL.'/admin_options.css', null, PRLI_VERSION);
      wp_enqueue_script('pl-options', PRLI_JS_URL.'/admin_options.js', array('jquery'), PRLI_VERSION);
    }

    if(in_array($hook, array(
        'toplevel_page_pretty-link',
        'pretty-links_page_add-new-pretty-link',
        'pretty-links_page_pretty-link-tools',
        'pretty-links_page_pretty-link-options'
       ))) {
      wp_enqueue_style('pl-settings-table', PRLI_CSS_URL.'/settings_table.css', null, PRLI_VERSION);
      wp_enqueue_script('pl-settings-table', PRLI_JS_URL.'/settings_table.js', array('jquery'), PRLI_VERSION);
    }

    if( $hook === 'pretty-links_page_pretty-link-clicks' ) {
      wp_enqueue_script('google-visualization-api', 'https://www.google.com/jsapi', null, PRLI_VERSION);
      wp_enqueue_style('pl-reports', PRLI_CSS_URL.'/admin_reports.css', null, PRLI_VERSION);
      wp_enqueue_script('pl-reports', PRLI_JS_URL.'/admin_reports.js', array('jquery','google-visualization-api'), PRLI_VERSION);
      wp_localize_script('pl-reports', 'PrliReport', PrliReportsController::chart_data());
    }

    do_action('prli_load_admin_scripts', $hook);
  }

  public function parse_standalone_request() {
    if( !empty($_REQUEST['plugin']) and $_REQUEST['plugin'] == 'pretty-link' and
        !empty($_REQUEST['controller']) and !empty($_REQUEST['action']) ) {
      $this->standalone_route($_REQUEST['controller'], $_REQUEST['action']);
      do_action('prli-standalone-route');
      exit;
    }
    else if( !empty($_GET['action']) and $_GET['action']=='prli_bookmarklet' ) {
      PrliToolsController::standalone_route();
      exit;
    }
  }

  public function standalone_route($controller, $action) {
    return; // Nothing here now that we've moved DB upgrade out of here
  }

  public static function install() {
    global $plp_update, $prli_utils;
    $prli_db = new PrliDb();

    if($prli_db->should_install()) {
      @ignore_user_abort(true);
      @set_time_limit(0);
      $prli_db->prli_install();
    }

    // Install Pro DB maybe
    if($plp_update->is_installed() && $prli_utils->should_install_pro_db()) {
      @ignore_user_abort(true);
      @set_time_limit(0);
      $prli_utils->install_pro_db();
    }
  }

  public function pro_settings_submenu() {
    global $wpdb, $prli_utils, $plp_update, $prli_db_version;

    if(isset($_GET['action']) && $_GET['action'] == 'force-pro-reinstall') {
      // Queue the update and auto upgrade
      $plp_update->manually_queue_update();
      $reinstall_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=pretty-link/pretty-link.php', 'upgrade-plugin_pretty-link/pretty-link.php');
      ?>

      <div class="updated notice notice-success"><p><strong><?php printf(__('You\'re almost done!<br/>%1$sFinish your Re-Install of Pretty Links Pro%2$s', 'pretty-link'), '<a href="'.$reinstall_url.'">', '</a>'); ?></strong></p></div>
      <?php
    }

    if(isset($_GET['action']) and $_GET['action'] == 'pro-uninstall') {
      $prli_utils->uninstall_pro();
      ?>
      <div class="updated notice notice-success is-dismissible"><p><strong><?php _e('Pretty Links Pro Successfully Uninstalled.' , 'pretty-link'); ?></strong></p></div>
      <?php
    }

    require_once(PRLI_VIEWS_PATH.'/options/pro-settings.php');
  }

  /********* ADD REDIRECTS FOR STANDARD MODE ***********/
  public function redirect() {
    global $prli_link;

    // Remove the trailing slash if there is one
    $request_uri = preg_replace('#/(\?.*)?$#', '$1', urldecode($_SERVER['REQUEST_URI']));

    if($link_info = $prli_link->is_pretty_link($request_uri,false)) {
      $params = (isset($link_info['pretty_link_params'])?$link_info['pretty_link_params']:'');
      $this->link_redirect_from_slug( $link_info['pretty_link_found']->slug, $params );
    }
  }

  // For use with the redirect function
  public function link_redirect_from_slug($slug,$param_str) {
    global $prli_link, $prli_utils;

    $link = $prli_link->getOneFromSlug(urldecode($slug));

    if(isset($link->slug) and !empty($link->slug)) {
      $custom_get = $_GET;

      /* Don't do any custom param forwarding now
        if(isset($link->param_forwarding) and $link->param_forwarding == 'custom')
          $custom_get = $prli_utils->decode_custom_param_str($link->param_struct, $param_str);
      */

      $success = $prli_utils->track_link($link->slug, $custom_get);

      if($success) { exit; }
    }
  }

  /********* DASHBOARD WIDGET ***********/
  public function dashboard_widget_function() {
    global $prli_group,$prli_link,$prli_blogurl;

    $groups = $prli_group->getAll('',' ORDER BY name');
    $values = PrliLinksController::setup_new_vars($groups);

    require_once(PRLI_VIEWS_PATH . '/widgets/widget.php');
  }

  // Create the function use in the action hook
  public function add_dashboard_widgets() {
    global $plp_options;
    $current_user = PrliUtils::get_currentuserinfo();

    $role = 'administrator';
    if(isset($plp_options->min_role)) {
      $role = $plp_options->min_role;
    }

    if(current_user_can($role)) {
      wp_add_dashboard_widget('prli_dashboard_widget', __('Pretty Link Quick Add', 'pretty-link'), array($this,'dashboard_widget_function'));

      // Globalize the metaboxes array, this holds all the widgets for wp-admin
      global $wp_meta_boxes;

      // Get the regular dashboard widgets array
      $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

      // Backup and delete our new dashbaord widget from the end of the array
      $prli_widget_backup = array('prli_dashboard_widget' => $normal_dashboard['prli_dashboard_widget']);
      unset($normal_dashboard['prli_dashboard_widget']);

      // Merge the two arrays together so our widget is at the beginning
      $i = 0;
      foreach($normal_dashboard as $key => $value) {
        if($i == 1 or (count($normal_dashboard) <= 1 and $i == count($normal_dashboard) - 1)) {
          $sorted_dashboard['prli_dashboard_widget'] = $prli_widget_backup['prli_dashboard_widget'];
        }

        $sorted_dashboard[$key] = $normal_dashboard[$key];
        $i++;
      }

      // Save the sorted array back into the original metaboxes
      $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }
  }

  public function pro_action_needed( $plugin ) {
    global $plp_update;

    if( $plugin == 'pretty-link/pretty-link.php' && $plp_update->is_activated() && !$plp_update->is_installed() ) {
      $plp_update->manually_queue_update();
      $inst_install_url = $plp_update->update_plugin_url();

      ?>
        <tr class="plugin-update-tr active" id="pretty-link-upgrade" data-slug="pretty-link" data-plugin="pretty-link/pretty-link.php">
          <td colspan="3" class="plugin-update colspanchange">
            <div class="update-message notice inline notice-error notice-alt">
              <p><?php printf(__('Your Pretty Links Pro installation isn\'t quite complete yet. %1$sAutomatically Upgrade to Enable Pretty Links Pro%2$s', 'pretty-link'), '<a href="'.$inst_install_url.'">', '</a>'); ?></p>
            </div>
          </td>
        </tr>
      <?php
    }
  }

  public function pro_get_started_headline() {
    global $plp_update;

    // Don't display this error as we're upgrading the thing... cmon
    if(isset($_GET['action']) && $_GET['action'] == 'upgrade-plugin') {
      return;
    }

    if( $plp_update->is_activated() && !$plp_update->is_installed()) {
      $plp_update->manually_queue_update();
      $inst_install_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . PRLI_PLUGIN_SLUG, 'upgrade-plugin_' . PRLI_PLUGIN_SLUG);

      ?>
        <div class="error" style="padding-top: 5px; padding-bottom: 5px;"><?php printf(__('Your Pretty Links Pro installation isn\'t quite complete yet.<br/>%1$sAutomatically Upgrade to Enable Pretty Links Pro%2$s', 'pretty-link'), '<a href="'.$inst_install_url.'">','</a>'); ?></div>
      <?php
    }
  }

  public function show_about_notice() {
    $last_shown_notice = get_option('prli_about_notice_version');
    $version_str = preg_replace('/\./','-',PRLI_VERSION);
    return ( $last_shown_notice != PRLI_VERSION and
             file_exists( PRLI_VIEWS_PATH . "/about/{$version_str}.php" ) );
  }

  public function about_notice() {
    $version_str  = preg_replace('/\./','-',PRLI_VERSION);
    $version_file = PRLI_VIEWS_PATH . "/about/{$version_str}.php";

    if( file_exists( $version_file ) ) {
      ob_start();
      require_once($version_file);
      return ob_get_clean();
    }

    return '';
  }

  public static function close_about_notice() {
    update_option('prli_about_notice_version',PRLI_VERSION);
  }
}

