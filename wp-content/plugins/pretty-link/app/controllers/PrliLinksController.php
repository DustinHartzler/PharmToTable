<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); }

class PrliLinksController extends PrliBaseController {
  public function load_hooks() {
    // nothing yet
    add_filter( 'cron_schedules', array($this,'intervals') );
    add_action( 'prli_cleanup_visitor_locks_worker', array($this,'cleanup_visitor_locks') );
    add_action( 'admin_init', array($this,'maybe_cleanup_visitor_locks') );

    if(!($snapshot_timestamp = wp_next_scheduled('prli_cleanup_visitor_locks_worker'))) {
      wp_schedule_event( time(), 'prli_cleanup_visitor_locks_interval', 'prli_cleanup_visitor_locks_worker' );
    }
  }

  public static function route() {
    $action = (isset($_REQUEST['action'])?$_REQUEST['action']:null);
    $params = self::get_params_array();

    // "new()" has its own submenu so we don't need a route for it here
    switch($action) {
      case 'list-form':
        return self::list_form($params);
      case 'quick-create':
        return self::quick_create_link($params);
      case 'create':
        return self::create_link($params);
      case 'edit':
        return self::edit_link($params);
      case 'bulk-update':
        return self::bulk_update_links($params);
      case 'update':
        return self::update_link($params);
      case 'reset':
        return self::reset_link($params);
      case 'destroy':
        return self::destroy_link($params);
      case 'bulk-destroy':
        return self::bulk_destroy_links($params);
      default:
        return self::list_links($params);
    }
  }

  public static function list_links($params) {
    global $wpdb, $prli_group;

    if(!empty($params['message']))
      $prli_message = $params['message'];
    else if(empty($params['group']))
      $prli_message = PrliUtils::get_main_message();
    else
      $prli_message = __("Links in Group: ", 'pretty-link') . $wpdb->get_var("SELECT name FROM " . $prli_group->table_name . " WHERE id=".$params['group']);

    self::display_links_list($params, $prli_message);
  }

  public function list_form($params) {
    if(apply_filters('prli-link-list-process-form', true))
      self::display_links_list($params, PrliUtils::get_main_message());
    }

  public static function new_link($params) {
    global $prli_group;
    $groups = $prli_group->getAll('',' ORDER BY name');
    $values = self::setup_new_vars($groups);

    require_once PRLI_VIEWS_PATH . '/links/new.php';
  }

  public static function quick_create_link($params) {
    global $prli_link, $prli_group, $prli_options;

    $params = self::get_params_array();
    $errors = $prli_link->validate($_POST);

    if( count($errors) > 0 )
    {
      $groups = $prli_group->getAll('',' ORDER BY name');
      $values = self::setup_new_vars($groups);
      require_once PRLI_VIEWS_PATH . '/links/new.php';
    }
    else
    {
      unset($_POST['param_forwarding']);

      $_POST['param_struct'] = '';
      $_POST['name'] = '';
      $_POST['description'] = '';
      if( $prli_options->link_track_me )
        $_POST['track_me'] = 'on';
      if( $prli_options->link_nofollow )
        $_POST['nofollow'] = 'on';

      $_POST['redirect_type'] = $prli_options->link_redirect_type;

      $record = $prli_link->create( $_POST );

      $prli_message = __("Your Pretty Link was Successfully Created", 'pretty-link');
      self::display_links_list($params, $prli_message, '', 1);
    }
  }

  public static function create_link($params) {
    global $prli_link, $prli_group;
    $errors = $prli_link->validate($_POST);

    $errors = apply_filters( "prli_validate_link", $errors );

    if(count($errors) > 0) {
      $groups = $prli_group->getAll('',' ORDER BY name');
      $values = self::setup_new_vars($groups);
      require_once PRLI_VIEWS_PATH . '/links/new.php';
    }
    else {
      $record = $prli_link->create( $_POST );

      do_action('prli_update_link', $record);

      $prli_message = __("Your Pretty Link was Successfully Created", 'pretty-link');
      self::display_links_list($params, $prli_message, '', 1);
    }
  }

  public static function edit_link($params) {
    global $prli_group, $prli_link;
    $groups = $prli_group->getAll('',' ORDER BY name');

    $record = $prli_link->getOne( $params['id'] );
    $values = self::setup_edit_vars($groups,$record);
    $id = $params['id'];
    require_once(PRLI_VIEWS_PATH . '/links/edit.php');
  }

  public static function update_link($params) {
    global $prli_link, $prli_group;
    $errors = $prli_link->validate($_POST);
    $id = $_POST['id'];

    $errors = apply_filters( "prli_validate_link", $errors );

    if( count($errors) > 0 ) {
      $groups = $prli_group->getAll('',' ORDER BY name');
      $record = $prli_link->getOne( $params['id'] );
      $values = self::setup_edit_vars($groups,$record);
      require_once(PRLI_VIEWS_PATH . '/links/edit.php');
    }
    else {
      $record = $prli_link->update( $_POST['id'], $_POST );

      do_action('prli_update_link', $id);

      $prli_message = __('Your Pretty Link was Successfully Updated', 'pretty-link');
      self::display_links_list($params, $prli_message, '', 1);
    }
  }

  public static function bulk_update_links() {
    global $prli_link;
    if(wp_verify_nonce($_REQUEST['_wpnonce'],'prli_bulk_update') and isset($_REQUEST['ids'])) {

      $ids = $_REQUEST['ids'];
      $params = $_REQUEST['bu'];

      $prli_link->bulk_update( $ids, $params );
      do_action('prli-bulk-action-update',$ids,$params);

      $message = __('Your links were updated successfully', 'pretty-link');

      //self::display_links_list(self::get_params_array(),$message);

      // We're going to redirect here to avoid having a big nasty url that
      // can cause problems when doing several activities in a row.

      // Scrub message, action, _wpnonce, ids & bu vars from the arguments and redirect
      $request_uri = preg_replace( '#\&(message|action|_wpnonce|ids|bu\[[^\]]*?\])=[^\&]*#', '', $_SERVER['REQUEST_URI'] );

      // we assume here that some arguments are set ... if not this value is meaningless anyway
      $request_uri .= '&message=' . urlencode($message);
      $redirect_url = 'http' . (empty($_SERVER['HTTPS'])?'':'s') . '://' . $_SERVER['HTTP_HOST'] . $request_uri;

      require PRLI_VIEWS_PATH . '/shared/jsredirect.php';
    }
    else {
      wp_die(__('You are unauthorized to view this page.', 'pretty-link'));
    }
  }

  public static function reset_link($params) {
    global $prli_link;
    $prli_link->reset( $params['id'] );
    $prli_message = __("Your Pretty Link was Successfully Reset", 'pretty-link');
    self::display_links_list($params, $prli_message, '', 1);
  }

  public static function destroy_link($params) {
    global $prli_link;
    $prli_link->destroy( $params['id'] );
    $prli_message = __("Your Pretty Link was Successfully Destroyed", 'pretty-link');
    self::display_links_list($params, $prli_message, '', 1);
  }

  public static function bulk_destroy_links($params) {
    global $prli_link;
    if(wp_verify_nonce($_REQUEST['_wpnonce'],'prli_bulk_update') and isset($_REQUEST['ids'])) {
      $ids = explode(',', $_REQUEST['ids']);

      foreach($ids as $id) {
        $prli_link->destroy( $id );
      }

      $message = __('Your links were deleted successfully', 'pretty-link');

      //self::display_links_list($params,$message);
      // Scrub message, action, _wpnonce, ids & bu vars from the arguments and redirect
      $request_uri = preg_replace( '#\&(message|action|_wpnonce|ids|bu\[[^\]]*?\])=[^\&]*#', '', $_SERVER['REQUEST_URI'] );

      // we assume here that some arguments are set ... if not this value is meaningless anyway
      $request_uri .= '&message=' . urlencode($message);
      $redirect_url = 'http' . (empty($_SERVER['HTTPS'])?'':'s') . '://' . $_SERVER['HTTP_HOST'] . $request_uri;

      require PRLI_VIEWS_PATH . '/shared/jsredirect.php';
    }
    else {
      wp_die(__('You are unauthorized to view this page.', 'pretty-link'));
    }
  }

  public static function display_links_list($params, $prli_message, $page_params_ov = false, $current_page_ov = false) {
    global $wpdb, $prli_utils, $prli_click, $prli_group, $prli_link, $page_size, $prli_options;

    $controller_file = basename(__FILE__);

    $where_clause = '';
    $page_params  = '';
    $group_param = '';

    $page_size = (isset($_REQUEST['size']) && is_numeric($_REQUEST['size']) && !empty($_REQUEST['size']))?$_REQUEST['size']:10;

    if(!empty($params['group'])) {
      $where_clause = " group_id=" . $params['group'];
      $group_param = "&group={$params['group']}";
      $page_params = "&group=" . $params['group'];
    }

    $link_vars = self::get_link_sort_vars($params, $where_clause);

    if($current_page_ov)
      $current_page = $current_page_ov;
    else
      $current_page = $params['paged'];

    if($page_params_ov)
      $page_params .= $page_params_ov;
    else
      $page_params .= $link_vars['page_params'];

    $sort_str = $link_vars['sort_str'];
    $sdir_str = $link_vars['sdir_str'];
    $search_str = $link_vars['search_str'];

    $record_count = $prli_link->getRecordCount($link_vars['where_clause']);
    $page_count = $prli_link->getPageCount($page_size,$link_vars['where_clause']);
    $links = $prli_link->getPage($current_page,$page_size,$link_vars['where_clause'],$link_vars['order_by']);
    $page_last_record = $prli_utils->getLastRecordNum($record_count,$current_page,$page_size);
    $page_first_record = $prli_utils->getFirstRecordNum($record_count,$current_page,$page_size);

    require_once(PRLI_VIEWS_PATH . '/links/list.php');
  }

  public static function get_link_sort_vars($params,$where_clause = '')
  {
    $order_by = '';
    $page_params = '';

    // These will have to work with both get and post
    $sort_str = $params['sort'];
    $sdir_str = $params['sdir'];
    $search_str = $params['search'];

    // Insert search string
    if(!empty($search_str))
    {
      $search_params = explode(" ", $search_str);

      foreach($search_params as $search_param)
      {
        if(!empty($where_clause))
          $where_clause .= " AND";

        $where_clause .= " (li.name like '%$search_param%' OR li.slug like '%$search_param%' OR li.url like '%$search_param%' OR li.created_at like '%$search_param%')";
      }

      $page_params .="&search=$search_str";
    }

    // make sure page params stay correct
    if(!empty($sort_str))
      $page_params .="&sort=$sort_str";

    if(!empty($sdir_str))
      $page_params .= "&sdir=$sdir_str";

    // Add order by clause
    switch($sort_str)
    {
      case "name":
      case "clicks":
      case "group_name":
      case "slug":
        $order_by .= " ORDER BY $sort_str";
        break;
      default:
        $order_by .= " ORDER BY created_at";
    }

    // Toggle ascending / descending
    if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'desc')
    {
      $order_by .= ' DESC';
      $sdir_str = 'desc';
    }
    else
      $sdir_str = 'asc';

    return array('order_by' => $order_by,
                 'sort_str' => $sort_str,
                 'sdir_str' => $sdir_str,
                 'search_str' => $search_str,
                 'where_clause' => $where_clause,
                 'page_params' => $page_params);
  }

  // Set defaults and grab get or post of each possible param
  public static function get_params_array() {
    return array(
       'action'     => (isset($_REQUEST['action'])?$_REQUEST['action']:'list'),
       'regenerate' => (isset($_REQUEST['regenerate'])?$_REQUEST['regenerate']:'false'),
       'id'         => (isset($_REQUEST['id'])?$_REQUEST['id']:''),
       'group_name' => (isset($_REQUEST['group_name'])?$_REQUEST['group_name']:''),
       'paged'      => (isset($_REQUEST['paged'])?$_REQUEST['paged']:1),
       'group'      => (isset($_REQUEST['group'])?(int)$_REQUEST['group']:''),
       'search'     => (isset($_REQUEST['search'])?$_REQUEST['search']:''),
       'sort'       => (isset($_REQUEST['sort'])?$_REQUEST['sort']:''),
       'sdir'       => (isset($_REQUEST['sdir'])?$_REQUEST['sdir']:''),
       'message'    => (isset($_REQUEST['message'])?sanitize_text_field($_REQUEST['message']):'')
    );
  }

  public static function setup_new_vars($groups) {
    global $prli_link, $prli_options;

    $values = array();
    $values['url'] =  (isset($_REQUEST['url'])?$_REQUEST['url']:'');
    $values['slug'] = (isset($_REQUEST['slug'])?$_REQUEST['slug']:$prli_link->generateValidSlug());
    $values['name'] = htmlspecialchars((isset($_REQUEST['name'])?stripslashes($_REQUEST['name']):''));
    $values['description'] = htmlspecialchars((isset($_REQUEST['description'])?stripslashes($_REQUEST['description']):''));

    $values['track_me'] = (((isset($_REQUEST['track_me']) and $_REQUEST['track_me'] == 'on') or (!isset($_REQUEST['track_me']) and $prli_options->link_track_me == '1'))?'checked="true"':'');
    $values['nofollow'] = (((isset($_REQUEST['nofollow']) and $_REQUEST['nofollow'] == 'on') or (!isset($_REQUEST['nofollow']) and $prli_options->link_nofollow == '1'))?'checked="true"':'');

    $values['redirect_type'] = array();
    $values['redirect_type']['307'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == '307') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == '307'))?'selected="selected"':'');
    $values['redirect_type']['302'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == '302') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == '302'))?'selected="selected"':'');
    $values['redirect_type']['301'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == '301') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == '301'))?'selected="selected"':'');
    $values['redirect_type']['prettybar'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'prettybar') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == 'prettybar'))?'selected="selected"':'');
    $values['redirect_type']['cloak'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'cloak') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == 'cloak'))?'selected="selected"':'');
    $values['redirect_type']['pixel'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'pixel') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == 'pixel'))?'selected="selected"':'');
    $values['redirect_type']['metarefresh'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'metarefresh') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == 'metarefresh'))?'selected="selected"':'');
    $values['redirect_type']['javascript'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'javascript') or (!isset($_REQUEST['redirect_type']) and $prli_options->link_redirect_type == 'javascript'))?'selected="selected"':'');

    $values['groups'] = array();

    if(is_array($groups)) {
      foreach($groups as $group) {
        $values['groups'][] = array(
          'id' => $group->id,
          'value' => ((isset($_REQUEST['group_id']) && $_REQUEST['group_id'] == $group->id)?' selected="true"':''),
          'name' => $group->name
        );
      }
    }

    $values['param_forwarding'] = isset($_REQUEST['param_forwarding']);
    $values['delay'] = (isset($_REQUEST['delay']) ? $_REQUEST['delay'] : 0);

    if(isset($_REQUEST['google_tracking'])) {
      $values['google_tracking'] = ' checked=checked';
    }
    else {
      global $plp_update;
      if( $plp_update->is_installed() ) {
        global $plp_options;
        $values['google_tracking'] = $plp_options->google_tracking?' checked=checked':'';
      }
      else {
        $values['google_tracking'] = '';
      }
    }

    return $values;
  }

  public static function setup_edit_vars($groups,$record) {
    global $prli_link, $prli_link_meta;

    $values = array();
    $values['url'] =  ((isset($_REQUEST['url']) and $record == null)?$_REQUEST['url']:$record->url);
    $values['slug'] = ((isset($_REQUEST['slug']) and $record == null)?$_REQUEST['slug']:$record->slug);
    $values['name'] = htmlspecialchars(stripslashes(((isset($_REQUEST['name']) and $record == null)?$_REQUEST['name']:$record->name)));
    $values['description'] = htmlspecialchars(stripslashes(((isset($_REQUEST['description']) and $record == null)?$_REQUEST['description']:$record->description)));
    $values['track_me'] = (((isset($_REQUEST['track_me']) or $record->track_me) and ((isset($_REQUEST['track_me']) and $_REQUEST['track_me'] == 'on') or $record->track_me == 1))?'checked="true"':'');
    $values['nofollow'] = (((isset($_REQUEST['nofollow']) and $_REQUEST['nofollow'] == 'on') or (isset($record->nofollow) && $record->nofollow == 1))?'checked="true"':'');

    $values['groups'] = array();
    foreach($groups as $group) {
      $values['groups'][] = array( 'id' => $group->id,
                                   'value' => (((isset($_REQUEST['group_id']) and ($_REQUEST['group_id'] == $group->id)) or ($record->group_id == $group->id))?' selected="true"':''),
                                   'name' => $group->name );
    }

    $values['param_forwarding'] = (isset($_REQUEST['param_forwarding']) || !(empty($record->param_forwarding) || $record->param_forwarding=='off'));

    $values['redirect_type'] = array();
    $values['redirect_type']['307'] = ((!isset($_REQUEST['redirect_type']) or (isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == '307') or (isset($record->redirect_type) and $record->redirect_type == '307'))?' selected="selected"':'');
    $values['redirect_type']['302'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == '302') or (isset($record->redirect_type) and $record->redirect_type == '302'))?' selected="selected"':'');
    $values['redirect_type']['301'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == '301') or (isset($record->redirect_type) and $record->redirect_type == '301'))?' selected="selected"':'');
    $values['redirect_type']['prettybar'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'prettybar') or (isset($record->redirect_type) and $record->redirect_type == 'prettybar'))?' selected="selected"':'');
    $values['redirect_type']['cloak'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'cloak') or (isset($record->redirect_type) and $record->redirect_type == 'cloak'))?' selected="selected"':'');
    $values['redirect_type']['pixel'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'pixel') or (isset($record->redirect_type) and $record->redirect_type == 'pixel'))?' selected="selected"':'');
    $values['redirect_type']['metarefresh'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'metarefresh') or (isset($record->redirect_type) and $record->redirect_type == 'metarefresh'))?' selected="selected"':'');
    $values['redirect_type']['javascript'] = (((isset($_REQUEST['redirect_type']) and $_REQUEST['redirect_type'] == 'javascript') or (isset($record->redirect_type) and $record->redirect_type == 'javascript'))?' selected="selected"':'');

    if(isset($_REQUEST['delay'])) {
      $values['delay'] = $_REQUEST['delay'];
    }
    else {
      $values['delay'] = $prli_link_meta->get_link_meta($record->id, 'delay', true);
    }

    if(isset($_REQUEST['google_tracking'])) {
      $values['google_tracking'] = ' checked=checked';
    }
    else {
      $values['google_tracking'] = (($prli_link_meta->get_link_meta($record->id, 'google_tracking', true) == 1)?' checked=checked':'');
    }

    return $values;
  }

  public function maybe_cleanup_visitor_locks() {
    $cleanup = get_transient('prli_cleanup_visitor_locks');

    if(empty($cleanup)) {
      set_transient('prli_cleanup_visitor_locks', 1, DAY_IN_SECONDS);
      $this->cleanup_visitor_locks();
    }
  }

  /** Delete visitor locks so we don't explode the size of peoples' databases */
  public function cleanup_visitor_locks() {
    global $wpdb;

    //|   1127004 | _transient_timeout_prli_visitor_58b12712690d5           | 1488004892    | no       |
    //|   1127005 | _transient_prli_visitor_58b12712690d5                   | 58b12712690d5 | no       |

    $q = $wpdb->prepare("
        SELECT option_name
          FROM {$wpdb->options}
         WHERE option_name LIKE %s
           AND option_value < %s
         ORDER BY option_value
      ",
      '_transient_timeout_prli_visitor_%',
      time()
    );

    $timeouts = $wpdb->get_col($q);

    foreach($timeouts as $i => $timeout_key) {
      // figure out the transient_key from the timeout_key
      $transient_key = preg_replace(
        '/^_transient_timeout_prli_visitor_/',
        '_transient_prli_visitor_',
        $timeout_key
      );

      $tq = $wpdb->prepare("
          DELETE FROM {$wpdb->options}
           WHERE option_name IN (%s,%s)
        ",
        $timeout_key,
        $transient_key
      );

      $res = $wpdb->query($tq);
    }
  }

  public function intervals( $schedules ) {
    $schedules['prli_cleanup_visitor_locks_interval'] = array(
      'interval' => HOUR_IN_SECONDS,
      'display' => __('Pretty Link Cleanup Visitor Locks', 'pretty-link'),
    );

    return $schedules;
  }
}

