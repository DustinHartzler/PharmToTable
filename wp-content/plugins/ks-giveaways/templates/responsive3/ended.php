<div class="row text-center">
  <div class="small-12 columns">
    <h3 style="color:#aaa;">
      <?php _e('Giveaway Ended', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
    </h3>
  </div>
</div>

<div class="row contest-winners">
  <?php foreach (ks_giveaways_get_winners() as $winner): ?>
  <div class="medium-6 columns">
    <h5>
      <?php if (in_array($winner['status'], array('confirmed','notified')) && $winner['winner_avatar']): ?>
        <img src="<?php echo $winner['winner_avatar'] ?>" alt="" />
      <?php else: ?>
        <i class="icon-ei-user" style="font-size:6em;color:#aaa;vertical-align:middle;"></i>
      <?php endif ?>

      <?php if (in_array($winner['status'], array('confirmed','notified'))): ?>
        <?php echo $winner['winner_name'] ? $winner['winner_name'] : __('Anonymous', KS_GIVEAWAYS_TEXT_DOMAIN) ?>
      <?php elseif ($winner['status'] == 'unconfirmed'): ?>
        <?php _e('Verifying Winner', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
      <?php elseif ($winner['status'] == 'pending'): ?>
        <?php _e('Pending', KS_GIVEAWAYS_TEXT_DOMAIN); ?>
      <?php endif ?>
    </h5>
  </div>
  <?php endforeach ?>
</div>
