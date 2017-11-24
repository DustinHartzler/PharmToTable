<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<?php global $plp_update; ?>
<div id="<?php echo $plp_update->edge_updates_str; ?>-wrap">
  <input type="checkbox" id="<?php echo $plp_update->edge_updates_str; ?>" data-nonce="<?php echo wp_create_nonce('wp-edge-updates'); ?>" <?php checked($plp_update->edge_updates); ?>/>&nbsp;<?php _e('Include Pretty Links Pro edge (development) releases in automatic updates (not recommended for production websites)', 'pretty-link'); ?> <img src="<?php echo PRLI_IMAGES_URL . '/square-loader.gif'; ?>" alt="<?php _e('Loading...', 'pretty-link'); ?>" class="prli_loader" />
</div>

