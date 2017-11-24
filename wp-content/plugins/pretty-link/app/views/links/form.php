<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); } ?>

<table class="form-table">
  <tr>
    <th scope="row">
      <?php _e('Redirection*', 'pretty-link'); ?>
      <?php PrliAppHelper::info_tooltip(
              'prli-link-options-redirection-type',
              __('Redirection Type', 'pretty-link'),
              __('This is the method of redirection for your link.', 'pretty-link')
            ); ?>
    </th>
    <td>
      <select id="redirect_type" name="redirect_type" style="padding: 0px; margin: 0px;">
        <option value="307"<?php echo esc_html($values['redirect_type']['307']); ?>><?php _e("307 (Temporary)", 'pretty-link') ?>&nbsp;</option>
        <option value="302"<?php echo esc_html($values['redirect_type']['302']); ?>><?php _e("302 (Temporary)", 'pretty-link') ?>&nbsp;</option>
        <option value="301"<?php echo esc_html($values['redirect_type']['301']); ?>><?php _e("301 (Permanent)", 'pretty-link') ?>&nbsp;</option>
        <?php do_action('prli_redirection_types', $values, false); ?>
      </select>
      <?php
        global $plp_update;
        if(!$plp_update->is_installed()) {
          ?>
          <p class="description"><?php printf(__('Make your life easier by upgrading to %1$sPretty Links Pro%2$s -- get more redirection types, speed, automations and <b>results from your links</b> by %1$sgoing pro today!%2$s', 'pretty-link'),'<a href="https://prettylinks.com/pl/link-form/upgrade" target="_blank">','</a>') ?></p>
          <?php
        } ?>
    </td>
  </tr>
  <tr id="prli_target_url">
    <th scope="row">
      <?php _e('Target URL*', 'pretty-link'); ?>
      <?php PrliAppHelper::info_tooltip(
              'prli-link-options-target-url',
              __('Target URL', 'pretty-link'),
              __('This is the URL that your Pretty Link will redirect to.', 'pretty-link')
            ); ?>
    </th>
    <td>
      <textarea class="large-text" name="url"><?php echo esc_html($values['url'],ENT_COMPAT,'UTF-8'); ?></textarea>
    </td>
  </tr>
  <tr>
    <th scope="row">
      <?php _e('Pretty Link*', 'pretty-link'); ?>
      <?php PrliAppHelper::info_tooltip(
              'prli-link-options-slug',
              __('Pretty Link', 'pretty-link'),
              __('This is how your pretty link will appear. You can edit the Pretty Link slug here.', 'pretty-link')
            ); ?>
    </th>
    <td>
      <strong><?php global $prli_blogurl; echo esc_html($prli_blogurl); ?></strong>/<input type="text" name="slug" class="regular-text" value="<?php echo esc_attr($values['slug']); ?>" />
    </td>
  </tr>
  <tr>
    <th scope="row">
      <?php _e('Title', 'pretty-link'); ?>
      <?php PrliAppHelper::info_tooltip(
              'prli-link-options-name',
              __('Title', 'pretty-link'),
              __('Leave this blank and Pretty Link will attempt to detect the title from the target url. Alternatively you can enter a custom title here.', 'pretty-link')
            ); ?>
    </th>
    <td>
      <input type="text" name="name" class="large-text" value="<?php echo esc_attr($values['name']); ?>" />
    </td>
  </tr>
  <tr>
    <th scope="row">
      <?php _e('Notes', 'pretty-link'); ?>
      <?php PrliAppHelper::info_tooltip(
              'prli-link-options-notes',
              __('Notes', 'pretty-link'),
              __('This is a field where you can enter notes about a particular link. This notes field is mainly for your own link management needs. It isn\'t currently used anywhere on the front end.', 'pretty-link')
            ); ?>
    </th>
    <td>
      <textarea class="large-text" name="description"><?php echo esc_html($values['description'],ENT_COMPAT,'UTF-8'); ?></textarea>
    </td>
  </tr>
</table>

<div class="prli-sub-box-white">
  <h3 class="prli-page-title"><a href="" class="prli-toggle-link" data-box="prli-link-advanced-options"><?php _e('Advanced Options', 'pretty-link'); ?></a></h3>
  <div class="prli-sub-box prli-link-advanced-options prli-hidden">
    <div class="prli-arrow prli-gray prli-up prli-sub-box-arrow"> </div>
    <table class="form-table" id="prli-link-advanced-options-box">
      <tbody>
        <tr>
          <th scope="row">
            <?php _e('Group', 'pretty-link'); ?>
            <?php PrliAppHelper::info_tooltip(
                    'prli-link-options-group',
                    __('Link Group', 'pretty-link'),
                    __('Select a Group for this Link', 'pretty-link')
                  ); ?>
          </th>
          <td>
            <select name="group_id" id="group_dropdown" style="padding: 0px; margin: 0px;">
              <option><?php _e("None", 'pretty-link') ?></option>
              <?php
                foreach($values['groups'] as $group) {
              ?>
                  <option value="<?php echo esc_attr($group['id']); ?>"<?php echo esc_html($group['value']); ?>><?php echo esc_html($group['name']); ?>&nbsp;</option>
              <?php
                }
              ?>
            </select>
            <input class="defaultText" id="add_group_textbox" title="<?php _e('Add a New Group', 'pretty-link') ?>" type="text" prli_nonce="<?php echo wp_create_nonce('prli-add-new-group'); ?>" style="vertical-align:middle;" /><div id="add_group_message"></div>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <?php _e('No Follow', 'pretty-link'); ?>
            <?php PrliAppHelper::info_tooltip(
                    'prli-link-options-nofollow',
                    __('Nofollow Link', 'pretty-link'),
                    __('Add a nofollow and noindex to this link\'s http redirect header', 'pretty-link')
                  ); ?>
          </th>
          <td>
            <input type="checkbox" name="nofollow" <?php echo esc_html($values['nofollow']); ?>/>
          </td>
        </tr>
        <tr id="prli_time_delay" style="display: none">
          <th scope="row">
            <?php _e('Delay Redirect', 'pretty-link'); ?>
            <?php PrliAppHelper::info_tooltip(
                    'prli-link-delay-redirect',
                    __('Delay Redirect', 'pretty-link'),
                    __('Time in seconds to wait before redirecting', 'pretty-link')
                  ); ?>
          </th>
          <td>
            <input type="number" name="delay" class="small-text" value="<?php echo esc_attr($values['delay']); ?>" />
          </td>
        </tr>
        <tr>
          <th scope="row">
            <?php _e("Parameter Forwarding", 'pretty-link') ?>
            <?php PrliAppHelper::info_tooltip(
                    'prli-link-parameter-forwarding',
                    __('Parameter Forwarding', 'pretty-link'),
                    __('Forward parameters passed to this link onto the Target URL', 'pretty-link')
                  ); ?>
          </th>
          <td>
            <input type="checkbox" name="param_forwarding" id="param_forwarding" <?php echo checked($values['param_forwarding']); ?> />
          </td>
        </tr>
        <tr>
          <th scope="row">
            <?php _e("Tracking", 'pretty-link') ?>
            <?php PrliAppHelper::info_tooltip(
                    'prli-link-tracking-options',
                    __('Tracking', 'pretty-link'),
                    __('Enable Pretty Link\'s built-in hit (click) tracking', 'pretty-link')
                  ); ?>
          </th>
          <td>
            <input type="checkbox" name="track_me" <?php echo esc_html($values['track_me']); ?> />
          </td>
        </tr>
        <tr id="prli_google_analytics" style="display: none">
          <th scope="row">
            <?php _e('Google Analytics', 'pretty-link'); ?>
            <?php PrliAppHelper::info_tooltip(
                    'prli-link-ga',
                    __('Google Analytics Tracking', 'pretty-link'),
                    __('Requires the Google Analyticator, Google Analytics by MonsterInsights (formerly Yoast) or Google Analytics Plugin installed and configured for this to work.', 'pretty-link')
                  ); ?>
          </th>
          <td>
            <?php
            global $plp_update;
            if($plp_update->is_installed()):
              if($ga_info = PlpUtils::ga_installed()):
                ?>
                <input type="checkbox" name="google_tracking" <?php echo esc_html($values['google_tracking']); ?> />
                <p class="description"><?php printf(__('It appears that <strong>%s</strong> is currently installed. Pretty Link will attempt to use its settings to track this link.', 'pretty-link'), $ga_info['name']); ?></p>
                <?php
              else:
                ?>
                  <input type="hidden" name="google_tracking" value="" />
                  <p class="description"><strong><?php _e('No Google Analytics Plugin is currently installed. Pretty Link cannot track links using Google Analytics until one is.', 'pretty-link'); ?></strong></p>
                <?php
              endif;
            endif;
            ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<div>&nbsp;</div>
<div class="prli-sub-box-white">
  <h3><a href="" class="prli-toggle-link" data-box="prli-link-pro-options"><?php _e('Pro Options', 'pretty-link'); ?></a></h3>
  <div class="prli-sub-box prli-link-pro-options prli-hidden">
    <div class="prli-arrow prli-gray prli-up prli-sub-box-arrow"> </div>
    <?php
      global $plp_update;
      if($plp_update->is_installed()) {
        ?>
        <table class="form-table" id="prli-link-pro-options-box">
        <?php

        $id = isset($id)?$id:false;
        // Add stuff to the form here
        do_action('prli_link_fields',$id);

        ?>
        </table>
        <?php
      }
      else {
      ?>
        <h2><?php _e('Oops!', 'pretty-link'); ?></h2>

        <div>
          <?php printf(__('It looks like you haven\'t %1$supgraded to Pretty Links Pro%2$s yet. Here are just a few things you could be doing with pro:', 'pretty-link'),'<a href="https://prettylinks.com/pl/link-form/upgrade-1" target="_blank">','</a>') ?>
        </div>
        <div>&nbsp;</div>
        <ul style="padding-left: 25px;">
          <li>&bullet; <?php _e('Auto-replace keywords throughout your site with Pretty Links', 'pretty-link'); ?></li>
          <li>&bullet; <?php _e('Protect your affiliate links by using Cloaked Redirects', 'pretty-link'); ?></li>
          <li>&bullet; <?php _e('Redirect based on a visitor\'s location', 'pretty-link'); ?></li>
          <li>&bullet; <?php _e('Auto-prettylink your Pages &amp; Posts', 'pretty-link'); ?></li>
          <li>&bullet; <?php _e('Find out what works and what doesn\'t by split testing your links', 'pretty-link'); ?></li>
          <li>&bullet; <?php _e('And much, much more!', 'pretty-link'); ?></li>
        </ul>
        <div>&nbsp;</div>
        <div><?php _e('Plus, upgrading is fast, easy and won\'t disrupt any of your existing links or data. And there\'s even a 14 day money back guarantee.', 'pretty-link'); ?></div>
        <div>&nbsp;</div>
        <div><?php _e('We think you\'ll love it!', 'pretty-link'); ?></div>
        <div>&nbsp;</div>
        <div><a href="https://prettylinks.com/pl/link-form/upgrade-2" class="button button-primary"><?php _e('Upgrade to Pro today!', 'pretty-link'); ?></a></div>
      <?php
      }
    ?>
  </div>
</div>

