<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<?php global $plp_update; ?>

<div class="prli-page-title"><?php _e('Pretty Links Pro License', 'pretty-link'); ?></div>

<?php if( !isset($li) or empty($li) ): ?>
  <p class="description"><?php printf(__('You must have a License Key to enable automatic updates for Pretty Links Pro. If you don\'t have a License please go to %1$s to get one. If you do have a license you can login at %2$s to manage your licenses and site activations.', 'pretty-link'), '<a href="https://prettylinks.com/pl/license-alert/buy">prettylinks.com</a>', '<a href="https://prettylinks.com/pl/license-alert/login">prettylinks.com/login</a>'); ?></p>
  <form name="activation_form" method="post" action="">
    <?php wp_nonce_field('activation_form'); ?>

    <table class="form-table">
      <tr class="form-field">
        <td valign="top" width="225px"><?php _e('Enter Your Pretty Links Pro License Key:', 'pretty-link'); ?></td>
        <td>
          <input type="text" name="<?php echo $plp_update->mothership_license_str; ?>" value="<?php echo (isset($_POST[$plp_update->mothership_license_str])?$_POST[$plp_update->mothership_license_str]:$plp_update->mothership_license); ?>"/>
        </td>
      </tr>
    </table>
    <p class="submit">
      <input type="submit" name="Submit" class="button button-primary" value="<?php printf(__('Activate License Key on %s', 'pretty-link'), PrliUtils::site_domain()); ?>" />
    </p>
  </form>

  <?php if(!$plp_update->is_installed()): ?>
    <div>&nbsp;</div>

    <div class="prli-page-title"><?php _e('Upgrade to Pro', 'pretty-link'); ?></div>

    <div>
      <?php printf(__('It looks like you haven\'t %1$supgraded to Pretty Links Pro%2$s yet. Here are just a few things you could be doing with pro:', 'pretty-link'),'<a href="https://prettylinks.com/pl/license-alert/upgrade" target="_blank">','</a>') ?>
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
    <div><a href="https://prettylinks.com/pl/license-alert/upgrade-1" class="button button-primary"><?php _e('Upgrade to Pro today!', 'pretty-link'); ?></a></div>
  <?php endif; ?>
<?php else: ?>
  <div class="prli-license-active">
    <div><h4><?php _e('Active License Key Information:', 'pretty-link'); ?></h4></div>
    <table>
      <tr>
        <td><?php _e('License Key:', 'pretty-link'); ?></td>
        <td>********-****-****-****-<?php echo substr($li['license_key']['license'], -12); ?></td>
      </tr>
      <tr>
        <td><?php _e('Status:', 'pretty-link'); ?></td>
        <td><?php printf(__('<b>Active on %s</b>', 'pretty-link'), PrliUtils::site_domain()); ?></td>
      </tr>
      <tr>
        <td><?php _e('Product:', 'pretty-link'); ?></td>
        <td><?php echo $li['product_name']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Activations:', 'pretty-link'); ?></td>
        <td><?php printf('<b>%1$d of %2$s</b> sites have been activated with this license key', $li['activation_count'], ucwords($li['max_activations'])); ?></td>
      </tr>
    </table>
    <div class="prli-deactivate-button"><a href="<?php echo admin_url('admin.php?page=pretty-link-updates&action=deactivate&_wpnonce='.wp_create_nonce('pretty-link_deactivate')); ?>" class="button button-primary" onclick="return confirm('<?php printf(__("Are you sure? Pretty Links Pro will not be functional on %s if this License Key is deactivated.", 'pretty-link'), PrliUtils::site_domain()); ?>');"><?php printf(__('Deactivate License Key on %s', 'pretty-link'), PrliUtils::site_domain()); ?></a></div>
  </div>
  <?php if(!$this->is_installed()): ?>
    <div><a href="<?php echo $this->update_plugin_url(); ?>" class="button button-primary"><?php _e('Upgrade plugin to Pro', 'pretty-link'); ?></a></div>
    <div>&nbsp;</div>
  <?php endif; ?>
  <?php require(PRLI_VIEWS_PATH.'/admin/update/edge_updates.php'); ?>
  <br/>
  <div id="prli-version-string"><?php printf(__("You're currently running version %s of Pretty Links Pro", 'pretty-link'), '<b>'.PRLI_VERSION.'</b>'); ?></div>
<?php endif; ?>

