<div class="row text-left">
  <div class="small-6 columns">
    <h5 class="cal">
      <?php if (!ks_giveaways_has_started()): ?>
      <?php _e('Giveaway Starts', KS_GIVEAWAYS_TEXT_DOMAIN); ?><br />
      <em><?php echo ks_giveaways_get_date_text(ks_giveaways_get_date_start()) ?></em>
      <?php else: ?>
        <?php if (ks_giveaways_has_ended()): ?>
        <?php _e('Giveaway Ended', KS_GIVEAWAYS_TEXT_DOMAIN); ?><br />
        <?php else: ?>
        <?php _e('Giveaway Ends', KS_GIVEAWAYS_TEXT_DOMAIN); ?><br />
        <?php endif ?>
        <em><?php echo ks_giveaways_get_date_text(ks_giveaways_get_date_end()) ?></em>
      <?php endif ?>
    </h5>
  </div>
  <div class="small-6 columns">
    <h5 class="cal">
      <?php _e('Prizes Awarded', KS_GIVEAWAYS_TEXT_DOMAIN); ?><br />
      <em><?php echo ks_giveaways_get_date_text(ks_giveaways_get_date_awarded()) ?></em>
    </h5>
  </div>
  <div class="small-12 columns">
    <h5 class="rules">
      <?php _e('Enter sweepstakes and receive exclusive offers from', KS_GIVEAWAYS_TEXT_DOMAIN); ?> <?php bloginfo('name') ?>. <?php _e('Unsubscribe anytime', KS_GIVEAWAYS_TEXT_DOMAIN); ?>.
      <?php if (ks_giveaways_get_prize_brand()): ?>
      <?php echo ks_giveaways_get_prize_brand() ?> <?php _e('is not affiliated with the giveaway', KS_GIVEAWAYS_TEXT_DOMAIN); ?>.
      <?php endif ?>
      <a href="javascript:void(0)" data-ng-click="toggleShowRules()" class="ng-cloak">{{showRules ? '<?php _e('Hide official rules', KS_GIVEAWAYS_TEXT_DOMAIN); ?>' : '<?php _e('Read official rules', KS_GIVEAWAYS_TEXT_DOMAIN); ?>'}}.</a>
    </h5>

    <div data-ng-show="showRules" class="ng-cloak">
      <?php echo ks_giveaways_get_rules() ?>
    </div>
  </div>
  <?php if (ks_giveaways_show_badge()): ?>
    <div class="small-12 columns text-center">
      <a href="http://kingsumo.com/apps/giveaways/?ref=<?php echo $_SERVER['HTTP_HOST'] ?>&utm_medium=badge" class="powered-by" target="_blank"><?php _e('Powered by KingSumo Giveaways for WordPress', KS_GIVEAWAYS_TEXT_DOMAIN); ?></a>
    </div>
  <?php endif ?>
</div>
