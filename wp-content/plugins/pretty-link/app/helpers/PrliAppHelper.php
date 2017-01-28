<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); }

class PrliAppHelper {
  public static function page_title($page_title) {
    require(PRLI_VIEWS_PATH . '/shared/title_text.php');
  }

  public static function info_tooltip($id, $title, $info) {
    ?>
    <span id="prli-tooltip-<?php echo $id; ?>" class="prli-tooltip">
      <span><i class="pl-icon pl-icon-info-circled pl-16"></i></span>
      <span class="prli-data-title prli-hidden"><?php echo $title; ?></span>
      <span class="prli-data-info prli-hidden"><?php echo $info; ?></span>
    </span>
    <?php
  }

  public static function groups_dropdown( $field_name, $value='', $include_blank=true ) {
    ?>
    <select name="<?php echo $field_name; ?>">

    <?php if( $include_blank ): ?>
      <option value=""><?php _e('None', 'pretty-link'); ?></option>
    <?php endif; ?>

    <?php
      $groups = prli_get_all_groups();
      if(is_array($groups)) {
        foreach($groups as $group) {
          ?>
          <option value="<?php echo $group['id']; ?>" <?php selected($value, $group['id']); ?>><?php echo $group['name']; ?></option>
          <?php
        }
      }
    ?>

    </select>
    <?php
  }
}

