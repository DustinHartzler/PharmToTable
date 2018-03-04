<?php
if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); }

// We'll do a full refactor later -- for now we'll just implement the add group ajax method
class PrliGroupsController extends PrliBaseController {
  public function load_hooks() {
    add_action('wp_ajax_add_new_prli_group', array($this,'ajax_new_group'));
  }

  public function route() {
    global $prli_group;

    $params = $prli_group->get_params_array();

    if($params['action'] == 'list') {
      $this->display_list($params);
    }
    else if($params['action'] == 'new') {
      $this->display_new($params);
    }
    else if($params['action'] == 'create') {
      $this->create($params);
    }
    else if($params['action'] == 'edit') {
      $this->display_edit($params);
    }
    else if($params['action'] == 'update') {
      $this->update($params);
    }
    else if($params['action'] == 'destroy') {
      $this->destroy($params);
    }
  }

  public function ajax_new_group() {
    global $prli_group;

    if(!PrliUtils::is_authorized()) {
      return json_encode( array( 'status' => 'failure',
                                 'message' => __('Cannot add group because current user is not authorized.', 'pretty-link') ) );
    }

    // Default response
    $response = json_encode( array( 'status' => 'failure',
                                    'message' => __('An unknown error occurred when creating your group.', 'pretty-link') ) );

    if(isset($_REQUEST['_prli_nonce']) and wp_verify_nonce($_REQUEST['_prli_nonce'], 'prli-add-new-group')) {
      if(isset($_REQUEST['new_group_name'])) {
        $new_group_name = stripslashes($_REQUEST['new_group_name']);
        $group_id = $prli_group->create( array( 'name' => $new_group_name, 'description' => '' ) );

        if( $group_id ) {
          $response = json_encode( array( 'status' => 'success',
                                          'message' => __('Group Created', 'pretty-link'),
                                          'group_id' => $group_id,
                                          'group_option' => "<option value=\"{$group_id}\">{$new_group_name}</option>" ) );
        }
      }
      else {
        $response = json_encode( array( 'status' => 'failure',
                                        'message' => __('A name must be specified for your new group name', 'pretty-link') ) );
      }
    }
    else {
      $response = json_encode( array( 'status' => 'failure',
                                      'message' => __('Cannot add group because security nonce failed', 'pretty-link') ) );
    }

    header( "Content-Type: application/json" );
    echo $response;

    exit;
  }

  public function display_list($params) {
    $this->display_groups_list($params, __('Create a group and use it to organize your Pretty Links.', 'pretty-link'));
  }

  public function display_new($params) {
    global $prli_link;
    $links = $prli_link->getAll('',' ORDER BY li.name');
    require_once(PRLI_VIEWS_PATH.'/groups/new.php');
  }

  public function create($params) {
    global $prli_group, $prli_link;

    $errors = $prli_group->validate($_POST);
    if( count($errors) > 0 ) {
      $links = $prli_link->getAll('',' ORDER BY li.name');
      require_once(PRLI_VIEWS_PATH.'/groups/new.php');
    }
    else {
      $insert_id = $prli_group->create($_POST);

      if(isset($_POST['link'])) {
        $this->update_groups($insert_id, $_POST['link']);
      }

      $this->display_groups_list($params, __("Your Pretty Link Group was Successfully Created", 'pretty-link'), '', 1);
    }
  }

  public function display_edit($params) {
    global $prli_group, $prli_link;

    $record = $prli_group->getOne( $params['id'] );
    $id = $params['id'];
    $links = $prli_link->getAll('',' ORDER BY li.name');

    require_once(PRLI_VIEWS_PATH.'/groups/edit.php');
  }

  public function update($params) {
    global $prli_group, $prli_link;

    $errors = $prli_group->validate($_POST);
    $id = $_POST['id'];
    if( count($errors) > 0 ) {
      $links = $prli_link->getAll('',' ORDER BY li.name');
      require_once(PRLI_VIEWS_PATH.'/groups/edit.php');
    }
    else {
      $record = $prli_group->update( $_POST['id'], $_POST );

      if(isset($_POST['link'])) {
        $this->update_groups($_POST['id'],$_POST['link']);
      }

      $this->display_groups_list($params, __('Your Pretty Link Group was Successfully Updated', 'pretty-link'), '', 1);
    }
  }

  public function destroy($params) {
    global $prli_group;

    $prli_group->destroy( $params['id'] );

    $this->display_groups_list($params, __("Your Pretty Link Group was Successfully Deleted", 'pretty-link'), '', 1);
  }

  public function update_groups($group_id, $values) {
    global $prli_link;

    $links = $prli_link->getAll();

    foreach($links as $link) {
      // Only update a group if the user's pulling it from another group
      if($link->group_id != $group_id and empty($values[$link->id])) {
        continue;
      }

      $prli_link->update_group($link->id, $values[$link->id], $group_id);
    }
  }

  // Helpers
  public function display_groups_list($params, $prli_message, $page_params_ov = false, $current_page_ov = false) {
    global $wpdb, $prli_utils, $prli_group, $prli_click, $prli_link, $page_size;

    $controller_file = basename(__FILE__);
    $group_vars = $this->get_group_sort_vars($params);

    if($current_page_ov) {
      $current_page = $current_page_ov;
    }
    else {
      $current_page = $params['paged'];
    }

    if($page_params_ov) {
      $page_params = $page_params_ov;
    }
    else {
      $page_params = $group_vars['page_params'];
    }

    $sort_str = $group_vars['sort_str'];
    $sdir_str = $group_vars['sdir_str'];
    $search_str = $group_vars['search_str'];

    $record_count = $prli_group->getRecordCount($group_vars['where_clause']);
    $page_count = $prli_group->getPageCount($page_size,$group_vars['where_clause']);
    $groups = $prli_group->getPage($current_page,$page_size,$group_vars['where_clause'],$group_vars['order_by']);
    $page_last_record = $prli_utils->getLastRecordNum($record_count,$current_page,$page_size);
    $page_first_record = $prli_utils->getFirstRecordNum($record_count,$current_page,$page_size);

    require_once(PRLI_VIEWS_PATH.'/groups/list.php');
  }

  public function get_group_sort_vars($params,$where_clause = '') {
    $order_by = '';
    $page_params = '';

    // These will have to work with both get and post
    $sort_str = $params['sort'];
    $sdir_str = $params['sdir'];
    $search_str = $params['search'];

    // Insert search string
    if(!empty($search_str)) {
      $search_params = explode(" ", $search_str);

      foreach($search_params as $search_param) {
        if(!empty($where_clause))
          $where_clause .= " AND";

        $where_clause .= " (name like '%$search_param%' OR description like '%$search_param%' OR created_at like '%$search_param%')";
      }

      $page_params .="&search=$search_str";
    }

    // make sure page params stay correct
    if(!empty($sort_str)) {
      $page_params .="&sort=$sort_str";
    }

    if(!empty($sdir_str)) {
      $page_params .= "&sdir=$sdir_str";
    }

    // Add order by clause
    switch($sort_str) {
      case "name":
      case "link_count":
      case "click_count":
      case "description":
        $order_by .= " ORDER BY $sort_str";
        break;
      default:
        $order_by .= " ORDER BY created_at";
    }

    // Toggle ascending / descending
    if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'desc') {
      $order_by .= ' DESC';
      $sdir_str = 'desc';
    }
    else {
      $sdir_str = 'asc';
    }

    return array(
      'order_by' => $order_by,
      'sort_str' => $sort_str,
      'sdir_str' => $sdir_str,
      'search_str' => $search_str,
      'where_clause' => $where_clause,
      'page_params' => $page_params
    );
  }
}
