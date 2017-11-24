<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<div class="wrap">
  <p style="text-align: left;">
    <a href="https://prettylinks.com/pl/widget/buy"><img style="border: 0px;" src="<?php echo PRLI_IMAGES_URL . '/pl-logo-horiz-RGB.svg'; ?>" width="75%" /></a>
  </p>

  <form name="form1" method="post" action="<?php echo admin_url("admin.php?page=pretty-link"); ?>">
  <input type="hidden" name="action" value="quick-create">
  <?php wp_nonce_field('update-options'); ?>

  <table class="form-table">
    <tr class="form-field">
      <td valign="top"><?php _e("Target URL", 'pretty-link'); ?></td>
      <td><input type="text" name="url" value="" size="75">
    </tr>
    <tr>
      <td valign="top"><?php _e("Pretty Link", 'pretty-link'); ?></td>
      <td><strong><?php echo esc_html($prli_blogurl); ?></strong>/<input type="text" name="slug" value="<?php echo $prli_link->generateValidSlug(); ?>">
    </tr>
  </table>

  <p class="submit">
    <input type="submit" name="Submit" value="Create" class="button button-primary" />
  </p>
  </form>
</div>
