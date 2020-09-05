<div class="wrap">
  <h2><?php the_title() ?> &mdash; Contestants <a href="<?php echo admin_url('edit.php?post_type='.KS_GIVEAWAYS_POST_TYPE) ?>" class="add-new-h2">Back</a></h2>

  <?php settings_errors("ks_admin_contestant_page_errors"); ?>

  <form action="" method="post">
  	<?php echo $list_table->search_box(__('Search', KS_GIVEAWAYS_TEXT_DOMAIN), 'ks-giveaways-contestant-search'); ?>
   	<p>
    	<a href="<?php echo admin_url('admin.php?page=ks-giveaways&action=contestants&id=' . $list_table->contest_id); ?>&noheader=true&downloadcsv=true" class="wp-core-ui button-primary action">
        <?php _e('Download CSV', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
    	</a>
    	
    	<input type="submit" name="bulkresend" class="button-secondary button action" onclick="return confirm('Resend confirmation email to all unconfirmed contestants?');" value="<?php esc_attr(_e('Resend Confirmations', KS_GIVEAWAYS_TEXT_DOMAIN)); ?>"> 
    	<input type="submit" name="bulkremove" class="button-secondary button action" onclick="return confirm('Remove all unconfirmed contestants?');" value="<?php esc_attr(_e('Remove Unconfirmed', KS_GIVEAWAYS_TEXT_DOMAIN)); ?>">
	</p>                
  	<?php echo $list_table->display() ?>
  </form>
</div>
