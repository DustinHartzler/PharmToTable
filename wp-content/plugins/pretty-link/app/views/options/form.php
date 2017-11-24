<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
  <?php echo PrliAppHelper::page_title(__('Options', 'pretty-link')); ?>
  <a href="https://prettylinks.com/user-manual-2" class="page-title-action"><?php _e('User Manual', 'pretty-link'); ?></a>
  <hr class="wp-header-end">

  <?php
    $permalink_structure = get_option('permalink_structure');
    if(!$permalink_structure or empty($permalink_structure)) {
      global $prli_siteurl;
      ?>
        <div class="error"><p><strong><?php _e('WordPress Must be Configured:', 'pretty-link'); ?></strong> <?php _e("Pretty Link won't work until you select a Permalink Structure other than 'Default'", 'pretty-link'); ?> ... <a href="<?php echo $prli_siteurl; ?>/wp-admin/options-permalink.php"><?php _e('Permalink Settings', 'pretty-link'); ?></a></p></div>
      <?php
    }

    do_action('prli-options-message');
  ?>

  <?php if($update_message): ?>
    <div class="updated notice notice-success is-dismissible"><p><strong><?php echo esc_html($update_message); ?></strong></p></div>
  <?php endif; ?>

  <form name="form1" id="prli-options" method="post" action="<?php echo admin_url('/admin.php?page=pretty-link-options'); ?>">
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
    <?php wp_nonce_field('update-options'); ?>

    <table class="prli-settings-table">
      <tr class="prli-mobile-nav">
        <td colspan="2">
          <a href="" class="prli-toggle-nav"><i class="pl-icon-menu"> </i></a>
        </td>
      </tr>
      <tr>
        <td class="prli-settings-table-nav">
          <ul class="prli-sidebar-nav">
            <?php if($plp_update->is_installed()): ?>
              <li><a data-id="general"><?php _e('General', 'pretty-link'); ?></a></li>
            <?php endif; ?>
            <li><a data-id="links"><?php _e('Links', 'pretty-link'); ?></a></li>
            <li><a data-id="reporting"><?php _e('Reporting', 'pretty-link'); ?></a></li>
            <?php do_action('prli_admin_options_nav'); ?>
          </ul>
        </td>
        <td class="prli-settings-table-pages">
          <?php if($plp_update->is_installed()): ?>
            <div class="prli-page" id="general">
              <div class="prli-page-title"><?php _e('General Options', 'pretty-link'); ?></div>
              <?php do_action('prli_admin_general_options'); ?>
            </div>
          <?php endif; ?>

          <div class="prli-page" id="links">
            <div class="prli-page-title"><?php _e('Default Link Options', 'pretty-link'); ?></div>
            <table class="form-table">
              <tbody>
                <tr valign="top">
                  <th scope="row">
                    <label for="<?php echo $link_redirect_type; ?>"><?php _e('Redirection Type', 'pretty-link') ?></label>
                    <?php PrliAppHelper::info_tooltip('prli-options-default-link-redirection',
                                                      __('Redirection Type', 'pretty-link'),
                                                      __('Select the type of redirection you want your newly created links to have.', 'pretty-link'));
                    ?>
                  </th>
                  <td>
                    <?php PrliLinksHelper::redirect_type_dropdown($link_redirect_type, $prli_options->link_redirect_type); ?>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <label for="<?php echo $link_track_me; ?>"><?php _e('Enable Tracking', 'pretty-link'); ?></label>
                    <?php PrliAppHelper::info_tooltip('prli-options-track-link',
                                                      __('Enable Tracking', 'pretty-link'),
                                                      __('Default all new links to be tracked.', 'pretty-link'));
                    ?>
                  </th>
                  <td>
                    <input type="checkbox" name="<?php echo $link_track_me; ?>" <?php checked($prli_options->link_track_me != 0); ?>/>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <label for="<?php echo $link_nofollow; ?>"><?php _e('Enable No Follow', 'pretty-link'); ?></label>
                    <?php PrliAppHelper::info_tooltip('prli-options-add-nofollow',
                                                      __('Add No Follow', 'pretty-link'),
                                                      __('Add the \'nofollow\' attribute by default to new links.', 'pretty-link'));
                    ?>
                  </th>
                  <td>
                    <input type="checkbox" name="<?php echo $link_nofollow; ?>" <?php checked($prli_options->link_nofollow != 0); ?>/>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <label for="<?php echo $link_prefix; ?>"><?php _e('Enable Permalink Fix', 'pretty-link'); ?></label>
                    <?php PrliAppHelper::info_tooltip('prli-options-use-prefix-permalinks',
                                                      __('Use fix for index.php Permalink Structure', 'pretty-link'),
                                                      __("This option should ONLY be checked if you have elements in your permalink structure that must be present in any link on your site. For example, some WordPress installs don't have the benefit of full rewrite capabilities and in this case you'd need an index.php included in each link (http://example.com/index.php/mycoolslug instead of http://example.com/mycoolslug). If this is the case for you then check this option but the vast majority of users will want to keep this unchecked.", 'pretty-link'));
                    ?>
                  </th>
                  <td>
                    <input type="checkbox" name="<?php echo $link_prefix; ?>" <?php checked($prli_options->link_prefix != 0); ?>/>
                  </td>
                </tr>
                <?php do_action('prli_custom_link_options'); ?>
              </tbody>
            </table>
          </div>

          <div class="prli-page" id="reporting">
            <div class="prli-page-title"><?php _e('Reporting Options', 'pretty-link'); ?></div>
            <table class="form-table">
              <tbody>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Tracking Style', 'pretty-link'); ?>
                    <?php PrliAppHelper::info_tooltip('prli-options-tracking-style',
                                                      __('Tracking Style', 'pretty-link'),
                                                      __("Changing your tracking style can affect the accuracy of your existing statistics. Extended mode must be used for Conversion reporting.", 'pretty-link'));
                    ?>
                  </th>
                  <td>
                    <input type="radio" name="<?php echo $extended_tracking; ?>" value="normal" <?php checked($prli_options->extended_tracking,'normal'); ?>/><span class="prli-radio-text"><?php _e('Normal Tracking', 'pretty-link'); ?></span><br/><br/>
                    <input type="radio" name="<?php echo $extended_tracking; ?>" value="extended"<?php checked($prli_options->extended_tracking,'extended'); ?>/><span class="prli-radio-text"><?php _e('Extended Tracking (more stats / slower performance)', 'pretty-link'); ?></span><br/><br/>
                    <input type="radio" name="<?php echo $extended_tracking; ?>" value="count"<?php checked($prli_options->extended_tracking,'count'); ?>/><span class="prli-radio-text"><?php _e('Simple Click Count Tracking (less stats / faster performance)', 'pretty-link'); ?></span><br/>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <label for="<?php echo $prli_exclude_ips; ?>">
                      <?php _e('Excluded IP Addresses:', 'pretty-link'); ?>
                      <?php PrliAppHelper::info_tooltip('prli-options-excluded-ips',
                                                        __('Excluded IP Addresses', 'pretty-link'),
                                                        sprintf(__("Enter IP Addresses or IP Ranges you want to exclude from your Click data and Stats. Each IP Address should be separated by commas. Example: 192.168.0.1, 192.168.2.1, 192.168.3.4 or 192.168.*.*<br/><br/><strong>FYI, your current IP address is %s.", 'pretty-link'), $prli_utils->get_current_client_ip()));
                      ?>
                    </label>
                  </th>
                  <td>
                    <input type="text" name="<?php echo $prli_exclude_ips; ?>" class="regular-text" value="<?php echo $prli_options->prli_exclude_ips; ?>">
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Auto-Trim Clicks', 'pretty-link'); ?>
                    <?php PrliAppHelper::info_tooltip('prli-options-auto-trim-clicks',
                                                      __('Automatically Trim Clicks', 'pretty-link'),
                                                      __("Will automatically delete all hits older than 90 days. We strongly recommend doing this to keep your database performance up. This will permanently delete this click data, and is not undo-able. ", 'pretty-link'));
                    ?>
                  </th>
                  <td>
                    <input type="checkbox" name="<?php echo $auto_trim_clicks; ?>" <?php checked($prli_options->auto_trim_clicks != 0); ?> />
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Filter Robots', 'pretty-link'); ?>
                    <?php PrliAppHelper::info_tooltip('prli-options-filter-robots',
                                                      __('Filter Robots', 'pretty-link'),
                                                      __("Filter known Robots and unidentifiable browser clients from your click data, stats and reports. Works best if Tracking Style above is set to 'Extended Tracking'.", 'pretty-link'));
                    ?>
                  </th>
                  <td>
                    <input type="checkbox" class="prli-toggle-checkbox" data-box="prli-whitelist-ips" name="<?php echo $filter_robots; ?>" <?php checked($prli_options->filter_robots != 0); ?> />
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="prli-sub-box prli-whitelist-ips">
              <div class="prli-arrow prli-gray prli-up prli-sub-box-arrow"> </div>
              <table class="form-table">
                <tbody>
                  <tr valign="top">
                    <th scope="row">
                      <label for="<?php echo $whitelist_ips; ?>">
                        <?php _e('Whitelist IP Addresses', 'pretty-link'); ?>
                        <?php PrliAppHelper::info_tooltip('prli-options-whitelist-ips',
                                                          __('Whiltelist IP Addresses', 'pretty-link'),
                                                          __("Enter IP Addresses or IP Ranges you want to always include in your Click data and Stats even if they are flagged as robots. Each IP Address should be separated by commas. Example: 192.168.0.1, 192.168.2.1, 192.168.3.4 or 192.168.*.*", 'pretty-link'));
                        ?>
                      </label>
                    </th>
                    <td><input type="text" name="<?php echo $whitelist_ips; ?>" class="regular-text" value="<?php echo $prli_options->whitelist_ips; ?>"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <?php do_action('prli_admin_options_pages'); ?>
        </td>
      </tr>
    </table>

    <p class="submit">
      <input type="submit" name="submit" class="button button-primary" value="<?php _e('Update', 'pretty-link') ?>" />
    </p>

  </form>
</div>
