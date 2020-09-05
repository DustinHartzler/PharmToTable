<div class="row text-center ng-cloak">
  <div class="small-12 columns">
    <h4 class="timer">
      <div id="countdown" data-until="<?php echo ks_giveaways_get_date_end() ?>"></div>
    </h4>
  </div>
</div>

<?php if (ks_giveaways_has_contestant()): ?>
<div class="row text-center">
  <div class="small-12 columns">
    <br>
    <h4><?php _e('You have', KS_GIVEAWAYS_TEXT_DOMAIN); ?> 
      <strong class="ng-cloak">
        {{KSGlobals.totalEntries}} 
        <?php if (ks_giveaways_is_confirmed_contestant()): ?><?php _e('confirmed', KS_GIVEAWAYS_TEXT_DOMAIN); ?> <?php endif ?>
        <data-ng-pluralize count="KSGlobals.totalEntries" when="{'0' : '<?php echo _e('entries', KS_GIVEAWAYS_TEXT_DOMAIN); ?>', '1' : '<?php _e('entry', KS_GIVEAWAYS_TEXT_DOMAIN); ?>', 'other' : '<?php echo _e('entries', KS_GIVEAWAYS_TEXT_DOMAIN); ?>'}"></data-ng-pluralize>
      </strong>
    </h4>
    <?php if (ks_giveaways_contestant_needs_confirmation()): ?>
      <p>
        <?php _e('Your number of contest entries will display once you have confirmed your email address. Please check your inbox to confirm now.', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
      </p>
    <?php endif ?>
  </div>
</div>

<div class="row text-center">
  <div class="small-12 columns">
    <h5>
      <?php if(ks_giveaways_get_entries_per_friend() > 1): ?>
        <?php printf(__('Get <strong>%d more entries</strong> for every friend you refer', KS_GIVEAWAYS_TEXT_DOMAIN), ks_giveaways_get_entries_per_friend()); ?>
      <?php else: ?>
        <?php printf(__('Get <strong>%d more entry</strong> for every friend you refer', KS_GIVEAWAYS_TEXT_DOMAIN), ks_giveaways_get_entries_per_friend()); ?>
      <?php endif; ?>
    </h5>
  </div>
</div>

<div class="row text-left sharing">
  <?php if (ks_giveaways_sharing_platform_enabled('facebook')): ?>
    <div class="small-6 columns float-left">
      <span class="step"><?php echo ks_giveaways_sharing_platform_counter(); ?></span> 
      <a class="resp-sharing-button__link" href="javascript:void(0)" target="_blank" aria-label="Facebook" title="<?php _e('Share it on Facebook', KS_GIVEAWAYS_TEXT_DOMAIN); ?>" onclick="ks_giveaways_fb('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>')">
        <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div><?php _e('Share', KS_GIVEAWAYS_TEXT_DOMAIN); ?></div>
      </a>
    </div>
  <?php endif ?>
  <?php if (ks_giveaways_sharing_platform_enabled('twitter')): ?>
    <div class="small-6 columns float-left">
      <span class="step"><?php echo ks_giveaways_sharing_platform_counter(); ?></span> 
        <a class="resp-sharing-button__link" href="javascript:void(0)" target="_blank" aria-label="Twitter" title="<?php _e('Tweet it on Twitter', KS_GIVEAWAYS_TEXT_DOMAIN); ?>" onclick="ks_giveaways_tw('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>', '<?php echo esc_js(get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)) ?>')">
          <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div><?php _e('Tweet', KS_GIVEAWAYS_TEXT_DOMAIN); ?></div>
        </a>
    </div>
  <?php endif ?>
  <?php if (ks_giveaways_sharing_platform_enabled('linkedin')): ?>
    <div class="small-6 columns float-left">
      <span class="step"><?php echo ks_giveaways_sharing_platform_counter(); ?></span>

      <a class="resp-sharing-button__link" href="javascript:void(0)" target="_blank" aria-label="LinkedIn" title="<?php _e('Share it on LinkedIn', KS_GIVEAWAYS_TEXT_DOMAIN); ?>" onclick="ks_giveaways_li('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>')">
        <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg></div><?php _e('Share', KS_GIVEAWAYS_TEXT_DOMAIN); ?></div>
      </a>
    </div>
  <?php endif ?>
  <?php if (ks_giveaways_sharing_platform_enabled('pinterest')): ?>
    <div class="small-6 columns float-left">
      <span class="step"><?php echo ks_giveaways_sharing_platform_counter(); ?></span> 
        <a class="resp-sharing-button__link" href="javascript:void(0)" target="_blank" aria-label="Pinterest" title="<?php _e('Share it on Pinterest', KS_GIVEAWAYS_TEXT_DOMAIN); ?>" onclick="ks_giveaways_pi('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>', '<?php echo esc_js(ks_giveaways_get_pinterest_image_url()) ?>')">
        <div class="resp-sharing-button resp-sharing-button--pinterest resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.14.5C5.86.5 2.7 5 2.7 8.75c0 2.27.86 4.3 2.7 5.05.3.12.57 0 .66-.33l.27-1.06c.1-.32.06-.44-.2-.73-.52-.62-.86-1.44-.86-2.6 0-3.33 2.5-6.32 6.5-6.32 3.55 0 5.5 2.17 5.5 5.07 0 3.8-1.7 7.02-4.2 7.02-1.37 0-2.4-1.14-2.07-2.54.4-1.68 1.16-3.48 1.16-4.7 0-1.07-.58-1.98-1.78-1.98-1.4 0-2.55 1.47-2.55 3.42 0 1.25.43 2.1.43 2.1l-1.7 7.2c-.5 2.13-.08 4.75-.04 5 .02.17.22.2.3.1.14-.18 1.82-2.26 2.4-4.33.16-.58.93-3.63.93-3.63.45.88 1.8 1.65 3.22 1.65 4.25 0 7.13-3.87 7.13-9.05C20.5 4.15 17.18.5 12.14.5z"/></svg></div><?php _e('Pin it', KS_GIVEAWAYS_TEXT_DOMAIN); ?></div>
      </a>
    </div>
  <?php endif ?>
</div>
<div class="row text-left sharing">
  <div class="small-6 columns">
    <span class="step"><?php echo ks_giveaways_sharing_platform_counter(); ?></span> <?php _e('Share Lucky URL', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
  </div>
  <div class="small-6 columns">
    <input type="text" value="<?php echo esc_attr(ks_giveaways_get_lucky_url()) ?>" onclick="this.select();" />
  </div>
</div>
<hr />
<div class="row text-left sharing">
  <?php include 'entry-actions.php'; ?>
</div>

<?php else: ?>
  <?php include 'entry-form.php'; ?>
<?php endif ?>
