<?php
if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); }

$group      = (isset($_REQUEST['group']))?(int)$_REQUEST['group']:'';
$size       = (isset($_REQUEST['size']))?(int)$_REQUEST['size']:'10';
$search     = (isset($_REQUEST['search']))?sanitize_text_field($_REQUEST['search']):'';

$url_params = "&group={$group}&size={$size}&search={$search}"
?>

<div class="wrap">
  <?php echo PrliAppHelper::page_title(__('Pretty Links', 'pretty-link')); ?>
  <a href="<?php echo admin_url('admin.php?page=add-new-pretty-link'); ?>" class="page-title-action"><?php _e('Add Pretty Link', 'pretty-link'); ?></a>
  <hr class="wp-header-end">

  <?php if(empty($params['group'])): ?>
    <?php $permalink_structure = get_option('permalink_structure'); ?>
    <?php if(!$permalink_structure or empty($permalink_structure)): ?>
      <div class="error"><p><strong><?php _e("WordPress Must be Configured:</strong> Pretty Link won't work until you select a Permalink Structure other than 'Default'", 'pretty-link'); ?> ... <a href="<?php echo admin_url('options-permalink.php'); ?>"><?php _e('Permalink Settings', 'pretty-link'); ?></a></p></div>
    <?php endif; //end $permalink_structure ?>
    <?php if($record_count <= 0): ?>
      <div class="updated notice notice-success is-dismissible"><p><?php echo $prli_message; ?></p></div>
    <?php endif; //end $record_count message ?>
    <?php do_action('prli-link-message'); ?>
    <div id="search_pane" style="float: right;">
      <form class="form-fields" name="link_form" method="post" action="">
        <?php wp_nonce_field('prli-links'); ?>
        <input type="hidden" name="sort" id="sort" value="<?php echo $sort_str; ?>" />
        <input type="hidden" name="sdir" id="sort" value="<?php echo $sdir_str; ?>" />
        <input type="text" name="search" id="search" value="<?php echo esc_attr($search_str); ?>" style="display:inline;"/>
        <div class="submit" style="display: inline;"><input class="button button-primary" type="submit" name="Submit" value="Search"/>
        <?php
        if(!empty($search_str)) {
          ?>
           &nbsp; <a href="<?php echo admin_url('admin.php?page=pretty-link&action=reset'); ?>" class="button"><?php _e('Reset', 'pretty-link'); ?></a>
          <?php
        }
        ?>
        </div>
      </form>
    </div>
  <?php else: //else if Groups ?>
    <h3><?php echo $prli_message; ?></h3>
    <a href="<?php echo admin_url('admin.php?page=pretty-link-groups'); ?>">&laquo <?php _e('Back to Groups', 'pretty-link'); ?></a>
    <br/><br/>
  <?php endif; //end if Groups ?>
  <?php $footer = false; require(PRLI_VIEWS_PATH.'/shared/link-table-nav.php'); ?>
  <table class="prli-edit-table widefat post fixed" cellspacing="0">
    <thead>
    <tr>
      <th class="manage-column" width="30%"><input type="checkbox" name="check-all" class="select-all-link-action-checkboxes" style="margin-left: 1px;"/>&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php?page=pretty-link&sort=name' . (($sort_str == 'name' and $sdir_str == 'asc')?'&sdir=desc':'') . $url_params); ?>"><?php _e('Name', 'pretty-link'); echo (($sort_str == 'name')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL . '/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <?php do_action('prli_link_column_header'); ?>
      <th class="manage-column" width="10%"><a href="<?php echo admin_url('admin.php?page=pretty-link&sort=clicks' . (($sort_str == 'clicks' and $sdir_str == 'asc')?'&sdir=desc':'') . $url_params); ?>"><?php _e('Clicks / Uniq', 'pretty-link'); echo (($sort_str == 'clicks')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL . '/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="5%"><a href="<?php echo admin_url('admin.php?page=pretty-link&sort=group_name' . (($sort_str == 'group_name' and $sdir_str == 'asc')?'&sdir=desc':'') . $url_params) ?>"><?php _e('Group', 'pretty-link'); echo (($sort_str == 'group_name')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL . '/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="12%"><a href="<?php echo admin_url('admin.php?page=pretty-link&sort=created_at' . (($sort_str == 'created_at' and $sdir_str == 'asc')?'&sdir=desc':'') . $url_params); ?>"><?php _e('Created', 'pretty-link'); echo ((empty($sort_str) or $sort_str == 'created_at')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL . '/'.((empty($sort_str) or $sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="20%"><a href="<?php echo admin_url('admin.php?page=pretty-link&sort=slug' . (($sort_str == 'slug' and $sdir_str == 'asc')?'&sdir=desc':'') . $url_params); ?>"><?php _e('Links', 'pretty-link'); echo (($sort_str == 'slug')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL . '/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
    </tr>
    </thead>
    <tr id="bulk-edit" class="inline-edit-row inline-edit-row-post inline-edit-post bulk-edit-row bulk-edit-row-post bulk-edit-post" style="display: none;">
      <td class="colspanchange">
        <form id="prli-bulk-action-form" action="<?php echo admin_url('admin.php'); ?>" method="post">
          <input type="hidden" name="page" value="pretty-link" />
          <input type="hidden" name="action" value="bulk-edit" />
          <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('prli-bulk-edit'); ?>" />
          <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
              <h4><?php _e('Bulk Edit', 'pretty-link'); ?></h4>
              <div id="bulk-title-div">
                <div id="bulk-titles"></div>
              </div>
            </div>
          </fieldset>
          <fieldset class="inline-edit-col-center">
            <h4><?php _e('Basic Link Options', 'pretty-link'); ?></h4>
            <div class="bacheck-title"><?php _e('Redirect Type', 'pretty-link'); ?></div>
            <?php PrliLinksHelper::redirect_type_dropdown( 'bu[redirect_type]', '', array(__('- No Change -', 'pretty-link') => '##nochange##'), 'bulk-edit-select' ) ?>
            <br/>
            <div class="bacheck-title"><?php _e('Group', 'pretty-link'); ?></div>
            <?php PrliLinksHelper::groups_dropdown('bu[group_id]', '', array(__('- No Change -', 'pretty-link') => '##nochange##'), 'bulk-edit-select'); ?>
            <br/>
            <?php PrliLinksHelper::bulk_action_checkbox_dropdown('bu[track_me]', __('Track', 'pretty-link'), 'bulk-edit-select'); ?>
            <br/>
            <?php PrliLinksHelper::bulk_action_checkbox_dropdown('bu[nofollow]', __('Nofollow', 'pretty-link'), 'bulk-edit-select'); ?>
            <br/>
            <?php PrliLinksHelper::bulk_action_checkbox_dropdown('bu[param_forwarding]', __('Forward Params', 'pretty-link'), 'bulk-edit-select'); ?>
            <br/>
          </fieldset>
          <fieldset class="inline-edit-col-right">
            <?php do_action('prli_bulk_action_right_col'); ?>
          </fieldset>
          <p class="submit inline-edit-save">
            <a href="javascript:" title="<?php _e('Cancel', 'pretty-link'); ?>" class="button-secondary bulk-edit-cancel alignleft"><?php _e('Cancel', 'pretty-link'); ?></a>
            <a href="javascript:" title="<?php _e('Update', 'pretty-link'); ?>" class="button-primary bulk-edit-update alignright"><?php _e('Bulk Update', 'pretty-link'); ?></a><br class="clear">
          </p>
        </form>
      </td>
    </tr>
  <?php

  if($record_count <= 0) {
    ?>
    <tr>
      <td colspan="5"><?php printf(__('No Pretty Links were found, %sCreate One%s', 'pretty-link'), '<a href="' . admin_url('admin.php?page=add-new-pretty-link') . '">', '</a>'); ?></td>
    </tr>
    <?php
  }
  else {
    global $prli_blogurl;
    $row_index=0;
    foreach($links as $link) {
      $alternate = ( $row_index++ % 2 ? '' : ' alternate' );
      $struct = PrliUtils::get_permalink_pre_slug_uri();
      $pretty_link_url = "{$prli_blogurl}{$struct}{$link->slug}";
      $plnotes = empty($link->description) ? $link->name : $link->description;
      ?>

      <tr id="record_<?php echo $link->id; ?>" class="link_row<?php echo $alternate; ?>">
        <td class="edit_link">

          <input type="checkbox" name="link-action[<?php echo $link->id; ?>]" class="link-action-checkbox" data-id="<?php echo $link->id; ?>" data-title="<?php echo esc_attr(stripslashes($link->name)); ?>" />&nbsp;&nbsp;<?php PrliLinksHelper::link_list_icons($link); ?>
          <a class="slug_name" href="<?php echo admin_url('admin.php?page=pretty-link&action=edit&id='.$link->id); ?>" title="<?php echo esc_attr(stripslashes($plnotes)); ?>"><?php echo stripslashes($link->name); ?></a>
          <div class="link_actions">
            <br/>
            <?php echo PrliLinksHelper::link_list_actions($link, $pretty_link_url); ?>
          </div>
        </td>
        <?php do_action('prli_link_column_row',$link->id); ?>
        <td>
          <?php if($prli_options->extended_tracking!='count')
                  echo (($link->track_me)?"<a href=\"". admin_url( "admin.php?page=pretty-link-clicks&l={$link->id}" ) . "\" title=\"View clicks for $link->slug\">" . (empty($link->clicks)?0:$link->clicks) . "/" . (empty($link->uniques)?0:$link->uniques) . "</a>":"<img src=\"".PRLI_IMAGES_URL."/not_tracking.png\" title=\"This link isn't being tracked\"/>");
                else
                  echo (($link->track_me)?(empty($link->clicks)?0:$link->clicks) . "/" . (empty($link->uniques)?0:$link->uniques):"<img src=\"".PRLI_IMAGES_URL."/not_tracking.png\" title=\"This link isn't being tracked\"/>");
          ?>
        </td>
        <td><a href="<?php echo admin_url( "admin.php?page=pretty-link&group={$link->group_id}"); ?>"><?php echo $link->group_name; ?></a></td>
        <td><?php echo $link->created_at; ?></td>
        </td>
        <td>
        <input type='text' style="font-size: 10px; width: 100%;" readonly="true" onclick='this.select();' onfocus='this.select();' value='<?php echo $pretty_link_url; ?>' />
        <span class="list-clippy prli-clipboard"><?php echo $pretty_link_url; ?></span>
        <?php if( $link->redirect_type != 'pixel' )
        {
        ?>
        <span style="font-size: 8px;" title="<?php echo $link->url; ?>"><strong><?php _e('Target URL:', 'pretty-link'); ?></strong> <?php echo htmlentities((substr($link->url,0,47) . ((strlen($link->url) >= 47)?'...':'')),ENT_COMPAT,'UTF-8'); ?></span></td>
        <?php
        }
        ?>
      </tr>
      <?php
    }
  }
  ?>
    <tfoot>
    <tr>
      <th class="manage-column"><?php do_action('prli-list-header-icon'); ?><?php _e('Name', 'pretty-link'); ?></th>
      <?php do_action('prli_link_column_footer'); ?>
      <th class="manage-column"><?php _e('Clicks / Uniq', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Group', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Created', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Links', 'pretty-link'); ?></th>
    </tr>
    </tfoot>
  </table>
<?php $footer = true; require(PRLI_VIEWS_PATH.'/shared/link-table-nav.php'); ?>
</div> <!-- end wrap -->
