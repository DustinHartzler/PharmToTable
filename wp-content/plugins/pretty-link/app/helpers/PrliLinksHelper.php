<?php

class PrliLinksHelper {

  public static function groups_dropdown($fieldname, $value='', $extra_options=array(), $classes='') {
    global $prli_group;
    $groups = $prli_group->getAll();

    $idname = preg_match('#^.*\[(.*?)\]$#',$fieldname,$matches)?$matches[1]:$fieldname;

    ?>
    <select id="<?php echo esc_html($idname); ?>" name="<?php echo esc_html($fieldname); ?>" class="<?php echo $classes; ?>">
      <?php if( empty($extra_options) ): ?>
        <option><?php _e('None', 'pretty-link'); ?>&nbsp;</option>
      <?php else:
              foreach($extra_options as $exoptkey => $exoptval): ?>
                <option value="<?php echo $exoptval; ?>"><?php echo $exoptkey; ?>&nbsp;</option>
      <?php   endforeach;
            endif; ?>
      <?php foreach($groups as $group): ?>
        <?php $selected = ($value==$group->id)?' selected="selected"':''; ?>
        <option value="<?php echo $group->id; ?>"<?php echo $selected; ?>><?php echo $group->name; ?>&nbsp;</option>
      <?php endforeach; ?>
    </select>
    <?php
  }

  public static function redirect_type_dropdown($fieldname, $value='', $extra_options=array(), $classes='') {
    $idname = preg_match('#^.*\[(.*?)\]$#',$fieldname,$matches)?$matches[1]:$fieldname;
    ?>
    <select id="<?php echo $idname; ?>" name="<?php echo $fieldname; ?>" class="<?php echo $classes; ?>">
      <?php if( !empty($extra_options) ): ?>
        <?php foreach( $extra_options as $exoptkey => $exoptval ): ?>
          <option value="<?php echo $exoptval; ?>"><?php echo $exoptkey; ?>&nbsp;</option>
        <?php endforeach; ?>
      <?php endif; ?>
      <option value="307" <?php selected((int)$value,(int)307); ?>><?php _e('307 (Temporary)', 'pretty-link') ?>&nbsp;</option>
      <option value="302" <?php selected((int)$value,(int)302); ?>><?php _e('302 (Temporary)', 'pretty-link') ?>&nbsp;</option>
      <option value="301" <?php selected((int)$value,(int)301); ?>><?php _e('301 (Permanent)', 'pretty-link') ?>&nbsp;</option>
      <?php do_action('prli_redirection_types', array(), $value); ?>
    </select>
    <?php
  }

  public static function bulk_action_dropdown() {
    ?>
    <div class="prli_bulk_action_dropdown">
      <select class="prli_bulk_action">
        <option value="-1"><?php _e('Bulk Actions', 'pretty-link'); ?>&nbsp;</option>
        <option value="edit"><?php _e('Edit', 'pretty-link'); ?>&nbsp;</option>
        <option value="delete"><?php _e('Delete', 'pretty-link'); ?>&nbsp;</option>
      </select>
      <a href="javascript:" class="prli_bulk_action_apply button button-primary" data-confmsg="<?php _e('Are you sure you want to delete the selected links?', 'pretty-link'); ?>" data-url="<?php echo admin_url('admin.php'); ?>" data-wpnonce="<?php echo wp_create_nonce('prli_bulk_update'); ?>" style="display:inline-block;margin:0;"><?php _e('Apply', 'pretty-link'); ?></a>
    </div>
    <?php
  }

  public static function bulk_action_checkbox_dropdown($input_name, $input_title, $classes='') {
    $idname = preg_match('#^.*\[(.*?)\]$#',$input_name,$matches)?$matches[1]:$input_name;
    ?>
      <div class="bacheck-title"><?php echo $input_title; ?></div>
      <select name="<?php echo $input_name; ?>" class="<?php echo $classes; ?>" id="<?php echo $idname; ?>">
        <option value="##nochange##"><?php _e('- No Change -', 'pretty-link'); ?>&nbsp;</option>
        <option value="off"><?php _e('Off', 'pretty-link'); ?>&nbsp;</option>
        <option value="on"><?php _e('On', 'pretty-link'); ?>&nbsp;</option>
      </select>
    <?php
  }

  public static function link_list_icons($link) {
    do_action('prli_list_icon',$link->id);

    switch( $link->redirect_type ):
      case 'prettybar': ?>
        <i title="<?php _e('PrettyBar Redirection', 'pretty-link'); ?>" class="pl-icon-star pl-list-icon"></i><?php
        break;
      case 'cloak': ?>
        <i title="<?php _e('Cloaked Redirection', 'pretty-link'); ?>" class="pl-icon-cloak pl-list-icon"></i><?php
        break;
      case 'pixel': ?>
        <i title="<?php _e('Pixel Tracking Redirection', 'pretty-link'); ?>" class="pl-icon-eye-off pl-list-icon"></i><?php
        break;
      case 'metarefresh': ?>
        <i title="<?php _e('Meta Refresh Redirection', 'pretty-link'); ?>" class="pl-icon-cw pl-list-icon"></i><?php
        break;
      case 'javascript': ?>
        <i title="<?php _e('Javascript Redirection', 'pretty-link'); ?>" class="pl-icon-code pl-list-icon"></i><?php
        break;
      case '307': ?>
        <i title="<?php _e('Temporary (307) Redirection', 'pretty-link'); ?>" class="pl-icon-307 pl-list-icon"></i><?php
        break;
      case '302': /* Using 307 Icon for now */ ?>
        <i title="<?php _e('Temporary (302) Redirection', 'pretty-link'); ?>" class="pl-icon-307 pl-list-icon"></i><?php
        break;
      case '301': ?>
        <i title="<?php _e('Permanent (301) Redirection', 'pretty-link'); ?>" class="pl-icon-301 pl-list-icon"></i><?php
    endswitch;

    if( $link->nofollow ): ?>
      <i title="<?php _e('Nofollow Enabled', 'pretty-link'); ?>" class="pl-icon-cancel-circled pl-list-icon"></i><?php
    endif;

    if(!empty($link->param_forwarding) && $link->param_forwarding != 'off'): ?>
      <i title="<?php _e('Parameter Forwarding Enabled', 'pretty-link'); ?>" class="pl-icon-forward pl-list-icon"></i><?php
    endif;

    do_action('prli_list_end_icon',$link);
  }

  public static function link_list_actions($link, $pretty_link_url) {
    global $prli_options;

    ?>
    <a href="<?php echo admin_url('admin.php?page=pretty-link&action=edit&id=' . $link->id); ?>" title="<?php printf( __('Edit %s', 'pretty-link'), $link->slug ); ?>"><i class="pl-list-icon pl-icon-edit"></i></a>
    <a href="<?php echo admin_url('admin.php?page=pretty-link&action=destroy&id=' . $link->id); ?>" onclick="return confirm('<?php printf( __('Are you sure you want to delete your %s Pretty Link? This will delete the Pretty Link and all of the statistical data about it in your database.', 'pretty-link'), $link->name ); ?>');" title="<?php printf( __('Delete %s', 'pretty-link'), $link->slug ); ?>"><i class="pl-list-icon pl-icon-cancel"></i></a>
    <a href="<?php echo admin_url('admin.php?page=pretty-link&action=reset&id=' . $link->id); ?>" onclick="return confirm('<?php printf( __('Are you sure you want to reset your %s Pretty Link? This will delete all of the statistical data about this Pretty Link in your database.', 'pretty-link'), $link->name ); ?>');" title="<?php printf( __('Reset %s', 'pretty-link'), $link->name ); ?>"><i class="pl-list-icon pl-icon-reply"></i></a>
    <?php
      if( $link->track_me and $prli_options->extended_tracking!='count' ):
        ?><a href="<?php echo admin_url("admin.php?page=pretty-link-clicks&l={$link->id}"); ?>" title="<?php printf( __('View clicks for %s', 'pretty-link'), $link->slug ); ?>"><i class="pl-list-icon pl-icon-chart-line"></i></a><?php
        do_action('prli-link-action',$link->id);
      endif;

      if( $link->redirect_type != 'pixel' ):
        ?><a href="http://twitter.com/home?status=<?php echo $pretty_link_url; ?>" target="_blank" title="<?php printf( __('Post %s to Twitter', 'pretty-link'), $pretty_link_url ); ?>"><i class="pl-list-icon pl-icon-twitter"></i></a>
        <a href="mailto:?subject=Pretty Link&body=<?php echo $pretty_link_url; ?>" target="_blank" title="<?php printf( __('Send %s in an Email', 'pretty-link'), $pretty_link_url ); ?>"><i class="pl-list-icon pl-icon-mail"></i></a><?php
      endif;
    ?>

    <?php if( $link->redirect_type != 'pixel' ): ?>
      <a href="<?php echo $link->url; ?>" target="_blank" title="<?php printf( 'Visit Target URL: %s in a New Window', $link->url ); ?>"><i class="pl-icon-link-ext pl-list-icon"></i></a>
      <a href="<?php echo $pretty_link_url; ?>" target="_blank" title="<?php printf( 'Visit Short URL: %s in a New Window', $pretty_link_url ); ?>"><i class="pl-icon-link-ext pl-list-icon"></i></a><?php
    endif;

    do_action('prli-special-link-action',$link->id);
  }
}

