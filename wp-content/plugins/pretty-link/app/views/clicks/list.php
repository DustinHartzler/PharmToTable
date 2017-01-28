<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<div class="wrap">
  <?php echo PrliAppHelper::page_title(__('Clicks', 'pretty-link')); ?>
  <br/>
  <span style="font-size: 14px;"><?php echo __('For', 'pretty-link').' '.stripslashes($link_name); ?>: </span>
  <?php
  // Don't show this sheesh if we're displaying the vuid or ip grouping
  if(empty($params['ip']) and empty($params['vuid'])) {
    ?>
    <a href="#" class="filter_toggle button button-primary" style="vertical-align:middle;"><?php _e('Customize Report', 'pretty-link'); ?></a>
    <?php
  }
  ?>

<?php
  if(!empty($params['l']) && $params['l'] != 'all') {
    echo '<br/><a href="'.admin_url("admin.php?page=pretty-link-clicks").'">&laquo '.__("Back to Links", 'pretty-link').'</a>';
  }
  else if(!empty($params['ip']) || !empty($params['vuid'])) {
    echo '<br/><a href="'.admin_url('admin.php?page=pretty-link-clicks').'">&laquo '.__("Back to Clicks", 'pretty-link').'</a>';
  }

  if(empty($params['ip']) && empty($params['vuid'])) {
?>

<div class="filter_pane" style="margin-top:15px;">
  <form class="form-fields" name="form2" method="post" action="">
    <?php wp_nonce_field('prli-reports'); ?>
    <span><?php _e('Type:', 'pretty-link'); ?></span>&nbsp;
    <select id="type" name="type" style="display: inline;">
      <option value="all"<?php selected(empty($params['type']) || $params['type'] == 'all'); ?>><?php _e('All Clicks', 'pretty-link'); ?>&nbsp;</option>
      <option value="unique"<?php selected($params['type'] == 'unique'); ?>><?php _e('Unique Clicks', 'pretty-link'); ?>&nbsp;</option>
    </select>
    <br/>
    <br/>
    <span><?php _e('Date Range:', 'pretty-link'); ?></span>
    <div id="dateselectors" style="display: inline;">
      <input type="text" name="sdate" id="sdate" value="<?php echo $params['sdate']; ?>" style="display:inline;"/>&nbsp;<?php _e('to', 'pretty-link'); ?>&nbsp;<input type="text" name="edate" id="edate" value="<?php echo $params['edate']; ?>" style="display:inline;"/>
    </div>
    <br/>
    <br/>
    <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Customize" class="button button-primary" /> &nbsp; <a href="#" class="filter_toggle button"><?php _e('Cancel', 'pretty-link'); ?></a></div>
  </form>
</div>

<div id="my_chart" style="margin-top:15px;"></div>

<?php
  }
  $navstyle = "float: right;";
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

  <div id="search_pane" style="margin-top:25px;margin-bottom:15px;">
    <form class="form-fields" name="click_form" method="post" action="">
      <?php wp_nonce_field('prli-clicks'); ?>

      <input type="hidden" name="sort" id="sort" value="<?php echo $sort_str; ?>" />
      <input type="hidden" name="sdir" id="sort" value="<?php echo $sdir_str; ?>" />
      <input type="text" name="search" id="search" value="<?php echo esc_attr($search_str); ?>" style="display:inline;"/>
      <div class="submit" style="display: inline;"><input class="button button-primary" type="submit" name="Submit" value="Search Clicks"/>
      <?php if(!empty($search_str)): ?>
        &nbsp; <a href="<?php echo admin_url('admin.php?page=pretty-link-clicks'.(!empty($params['l'])?'&l='.$params['l']:'')); ?>" class="button"><?php _e('Reset', 'pretty-link'); ?></a>
      <?php endif; ?>
      </div>
    </form>
  </div>
<table class="widefat post fixed" cellspacing="0">
    <thead>
    <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="5%"><a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=btype".(($sort_str == 'btype' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('Browser', 'pretty-link'); echo (($sort_str == 'btype')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
      <th class="manage-column" width="5%"><a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=os".(($sort_str == 'os' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('OS', 'pretty-link'); echo (($sort_str == 'btype')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="12%">
        <a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=ip".(($sort_str == 'ip' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('IP', 'pretty-link'); echo (($sort_str == 'ip')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="12%">
        <a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=vuid".(($sort_str == 'vuid' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('Visitor', 'pretty-link'); echo (($sort_str == 'vuid')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="13%">
        <a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=created_at".(($sort_str == 'created_at' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('Timestamp', 'pretty-link'); echo ((empty($sort_str) or $sort_str == 'created_at')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.((empty($sort_str) or $sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="16%">
        <a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=host".(($sort_str == 'host' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('Host', 'pretty-link'); echo (($sort_str == 'host')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="16%">
        <a href="?page=<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=uri".(($sort_str == 'uri' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('URI', 'pretty-link'); echo (($sort_str == 'uri')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
      <th class="manage-column" width="16%">
        <a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=referer".(($sort_str == 'referer' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('Referrer', 'pretty-link'); echo (($sort_str == 'referer')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
      <th class="manage-column" width="13%">
        <a href="<?php echo admin_url("admin.php?page=pretty-link-clicks{$sort_params}&sort=link".(($sort_str == 'link' and $sdir_str == 'asc')?'&sdir=desc':'')); ?>"><?php _e('Link', 'pretty-link'); echo (($sort_str == 'link')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    </tr>
    </thead>
  <?php

  if(count($clicks) <= 0)
  {
      ?>
    <tr>
      <td colspan="8"><?php _e('No Clicks have been recorded yet', 'pretty-link'); ?></td>
    </tr>
    <?php
  }
  else
  {
    $row_index=0;
    foreach($clicks as $click) {
      $alternate = ( $row_index++ % 2 ? '' : 'alternate' );

      ?>
      <tr id="record_<?php echo $click->id; ?>" class="<?php echo $alternate; ?>">

      <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ): ?>
        <td>
          <?php
            $browser_image = PrliUtils::browser_image($click->btype);
            $os_image = PrliUtils::os_image($click->os);
          ?>

          <?php if(false===$browser_image): ?>
            <span style="font-size: 16px; cursor: pointer;" title="<?php echo $click->btype; ?>"><strong>?</strong></span>
          <?php else: ?>
            <img src="<?php echo esc_html(PRLI_BROWSER_URL); ?>/<?php echo $browser_image; ?>" alt="<?php echo $click->btype . " v" . $click->bversion; ?>" title="<?php echo $click->btype . " v" . $click->bversion; ?>" width="16px" height="16px" style="width: 16px; height: 16px;" />
          <?php endif; ?>
        </td>

        <td>
          <?php if(false===$os_image): ?>
            <span style="font-size: 16px; cursor: pointer;" title="<?php echo $click->os; ?>"><strong>?</strong></span>
          <?php else: ?>
            <img src="<?php echo esc_html(PRLI_OS_URL); ?>/<?php echo $os_image; ?>" alt="<?php echo $click->os; ?>" title="<?php echo $click->os; ?>" width="16px" height="16px" style="width: 16px; height: 16px;" /></td>
          <?php endif; ?>
        </td>
      <?php endif; ?>

        <td><a href="<?php echo admin_url("admin.php?page=pretty-link-clicks&ip={$click->ip}"); ?>" title="<?php printf(__('View All Activity for IP Address: %s', 'pretty-link'), $click->ip); ?>"><?php echo $click->ip; ?> (<?php echo $click->ip_count; ?>)</a></td>

      <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ): ?>
        <td><a href="<?php echo admin_url("admin.php?page=pretty-link-clicks&vuid={$click->vuid}") ?>" title="<?php printf(__('View All Activity for Visitor: %s', 'pretty-link'), $click->vuid); ?>"><?php echo $click->vuid; ?><?php echo (($click->vuid != null)?" ($click->vuid_count)":''); ?></a></td>
      <?php endif; ?>

        <td><?php echo $click->created_at; ?></td>

      <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ): ?>
        <td><?php echo $click->host; ?></td>
      <?php endif; ?>

        <td><?php echo $click->uri; ?></td>
        <td><a href="<?php echo $click->referer; ?>"><?php echo $click->referer; ?></a></td>
        <td><a href="<?php echo admin_url("admin.php?page=pretty-link-clicks&l={$click->link_id}"); ?>" title="<?php printf(__('View clicks for %s', 'pretty-link'), stripslashes($click->link_name)); ?>"><?php echo stripslashes($click->link_name); ?></a></td>
      </tr>
      <?php

    }
  }
  ?>
    <tfoot>
    <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column"><?php _e('Browser', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('OS', 'pretty-link'); ?></th>
    <?php } ?>
      <th class="manage-column"><?php _e('IP', 'pretty-link'); ?></th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column"><?php _e('Visitor', 'pretty-link'); ?></th>
    <?php } ?>
      <th class="manage-column"><?php _e('Timestamp', 'pretty-link'); ?></th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column"><?php _e('Host', 'pretty-link'); ?></th>
    <?php } ?>
      <th class="manage-column"><?php _e('URI', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Referrer', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Link', 'pretty-link'); ?></th>
    </tr>
    </tfoot>
</table>

<br/>
<a href="<?php echo admin_url("admin.php?page=pretty-link-clicks&action=csv{$page_params}"); ?>" class="button button-primary"><?php _e('Download CSV', 'pretty-link'); ?> (<?php echo stripslashes($link_name); ?>)</a>

<?php
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

</div>
