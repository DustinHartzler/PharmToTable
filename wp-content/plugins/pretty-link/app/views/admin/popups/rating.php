<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<div id="prli-rating-popup" class="mfp-hide prli-popup prli-auto-open">
  <p><img src="<?php echo PRLI_IMAGES_URL . '/pl-logo-horiz-RGB.svg'; ?>" width="200" height="32" /></p>
  <div>&nbsp;</div>
  <h2><?php _e('Rate Pretty Link', 'pretty-link'); ?></h2>
  <p><?php _e('If you enjoy using Pretty Link would you mind taking a moment to rate it on WordPress.org? It won\'t take more than a minute.', 'pretty-link'); ?></p>
  <p><?php _e('Thanks for your support!', 'pretty-link'); ?></p>
  <div>&nbsp;</div>
  <div>&nbsp;</div>

  <center>
    <button data-popup="rating" class="prli-delay-popup button"><?php _e('Remind Me Later', 'pretty-link'); ?></button>
    <button data-popup="rating" data-href="https://wordpress.org/plugins/pretty-link/" class="prli-delay-popup button-primary"><?php _e('Review Pretty Link', 'pretty-link'); ?></button>
    <div>&nbsp;</div>
    <a href="" data-popup="rating" class="prli-stop-popup"><?php _e('Never Remind Me Again', 'pretty-link'); ?></a>
  </center>

</div>

