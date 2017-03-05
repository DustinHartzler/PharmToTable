<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<div id="prli-upgrade-popup" class="mfp-hide prli-popup prli-auto-open">
  <p><img src="<?php echo PRLI_IMAGES_URL . '/pl-logo-horiz-RGB.svg'; ?>" width="200" height="32" /></p>
  <div>&nbsp;</div>
  <h2><?php _e('Upgrade Pretty Link PRO', 'pretty-link'); ?></h2>
  <p><?php _e('Upgrading will enable you to:', 'pretty-link'); ?><br/>
    <ul>
      <li>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;<?php _e('Cloak links', 'pretty-link'); ?></li>
      <li>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;<?php _e('Redirect based on location', 'pretty-link'); ?></li>
      <li>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;<?php _e('Auto-prettylink your Pages &amp; Posts', 'pretty-link'); ?></li>
      <li>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;<?php _e('Auto-replace links throughout your site with Pretty Links', 'pretty-link'); ?></li>
      <li>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;<?php _e('And much, much more!', 'pretty-link'); ?></li>
    </ul>
  </p>
  <p><?php _e('Plus, upgrading is fast, easy and won\'t disrupt any of your existing links or data. And there\'s even a 14 day money back guarantee.', 'pretty-link'); ?></p>
  <p><?php _e('We think you\'ll love it!', 'pretty-link'); ?></p>
  <div>&nbsp;</div>
  <div>&nbsp;</div>

  <center>
    <button data-popup="upgrade" class="prli-delay-popup button"><?php _e('Remind Me Later', 'pretty-link'); ?></button>
    <button data-popup="upgrade" data-href="https://prettylinkpro.com/pricing/plans/" class="prli-delay-popup button-primary"><?php _e('Upgrade to Pretty Link Pro', 'pretty-link'); ?></button>
    <div>&nbsp;</div>
    <a href="" data-popup="upgrade" class="prli-stop-popup"><?php _e('Never Remind Me Again', 'pretty-link'); ?></a>
  </center>

</div>

