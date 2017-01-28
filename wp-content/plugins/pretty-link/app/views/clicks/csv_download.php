<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<div class="wrap">
  <?php echo PrliAppHelper::page_title(__('Download Clicks', 'pretty-link')); ?>
  <span style="font-size: 14px; font-weight: bold;">For <?php echo stripslashes($link_name); ?>: </span>

  <h3><?php _e('Click Reports:', 'pretty-link'); ?></h3>
  <span class="description"><?php printf( __('All clicks on %s', 'pretty-link'), stripslashes($link_name) ); ?></span>
  <br/>
  <ul>
    <?php
    for($i=$hit_page_count; $i>0; $i--) {
      $hit_min = 0;

      if($i) { $hit_min = ($i - 1) * $max_rows_per_file; }

      if($i==$hit_page_count) {
        $hit_max = $hit_record_count;
      }
      else {
        $hit_max = ($i * $max_rows_per_file) - 1;
      }

      $hit_count = $hit_max - $hit_min + 1;
      $report_label = sprintf(__('Clicks %d-%d (%d Records)', 'pretty-link'), $hit_min, $hit_max, $hit_count);
      $hit_param_delim = (preg_match('#\?#',$hit_report_url)?'&':'?');

      ?>
      <li><a href="<?php echo $hit_report_url . $hit_param_delim; ?>prli_page=<?php echo $i; ?>"><?php echo $report_label; ?></a></li>
      <?php
    }
    ?>
  </ul>
</div>

