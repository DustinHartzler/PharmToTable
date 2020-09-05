<?php
/**
 * Template Name:       Responsive 3
 */
?>
<!doctype html>
<html class="no-js" lang="<?php echo esc_attr(substr(get_locale(), 0, 2)); ?>" prefix="og: http://ogp.me/ns#">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php the_title() ?></title>
    <?php if (function_exists('wp_site_icon')): ?>
      <?php wp_site_icon(); ?>
    <?php endif ?>
    <link rel="canonical" href="<?php echo esc_attr(get_permalink()) ?>" />

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr(get_the_title()) ?>">
    <meta property="og:description" content="<?php echo esc_attr(strip_tags(ks_giveaways_get_description())) ?>">
    <meta property="og:image" content="<?php echo esc_attr(ks_giveaways_get_prize_image_url()) ?>">
    <meta property="og:url" content="<?php echo esc_attr(get_permalink()) ?>">

    <meta name="twitter:card" content="summary">
    <?php if (get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)): ?>
      <meta name="twitter:site" content="@<?php echo esc_attr(get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)) ?>">
    <?php endif ?>
    <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()) ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(strip_tags(ks_giveaways_get_description())) ?>">
    <meta name="twitter:image" content="<?php echo esc_attr(ks_giveaways_get_prize_image_url()) ?>">

    <link rel="stylesheet" href="<?php echo ks_giveaways_assets_url('build/public.css') ?>" />

<?php if (function_exists('wpcss_handle_actions')):  //CHECK IF CSS Hero is ACTIVE ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('url');?>/?wpcss_action=show_css<?php if (current_user_can('edit_theme_options')) echo '&amp;rnd='.rand(0,1024); ?>" data-apply-prefixfree />
<?php endif ?>

<?php if (array_key_exists('embed_post', $_GET)): ?>
  <style type="text/css">
    body {padding:5px;}
    .contest {margin-left:auto;margin-right:auto;float:none;}
    .contest-images {display:none;}
    .back {display:none;}
  </style>
<?php endif; ?>
  <?php //wp_head(); ?>
    <script type="text/javascript">
      var ks_giveaways_globals = {};
      ks_giveaways_globals.ajax_url       = <?php echo json_encode(admin_url('admin-ajax.php')); ?>;
      ks_giveaways_globals.nonce          = <?php echo json_encode(wp_create_nonce('ks_giveaways_form')); ?>;
      ks_giveaways_globals.enableQuestion = <?php echo json_encode(ks_giveaways_question_enabled()) ?>;
      ks_giveaways_globals.askName        = <?php echo json_encode(get_option(KS_GIVEAWAYS_OPTION_GIVEAWAYS_ASK_NAME)); ?>;
      ks_giveaways_globals.lucky          = <?php echo isset($_REQUEST['lucky']) ? json_encode($_REQUEST['lucky']) : 'null'; ?>;
      ks_giveaways_globals.contestant_id  = <?php echo json_encode(ks_giveaways_get_contestant_id()); ?>;
      ks_giveaways_globals.giveaway_id    = <?php echo json_encode(ks_giveaways_get_giveaway_id()); ?>;
      ks_giveaways_globals.entryActions   = <?php echo json_encode(get_post_meta($post->ID, '_entry_actions', true)) ?>;
      ks_giveaways_globals.contestantActionEntries = <?php echo json_encode(ks_giveaways_get_all_action_entries_count()) ?>;
      ks_giveaways_globals.totalEntries   = <?php echo json_encode(ks_giveaways_get_my_entries()); ?>
    </script>
  </head>
  <body data-ng-app="KingSumoGiveawayApp" data-ng-controller="main">
    <div class="row">
      <div class="medium-5 small-12 left columns contest-images logo">
        <?php if (ks_giveaways_get_logo_image_url()): ?>
          <a href="<?php echo esc_url(home_url('/')) ?>"><img src="<?php echo ks_giveaways_get_logo_image_url() ?>" alt="" /></a>
        <?php endif ?>
      </div>
      <?php if(array_key_exists('embed_post', $_GET)): ?>
        <div class="small-12 contest columns">
      <?php else: ?>
        <div class="medium-7 small-12 right columns contest">
      <?php endif; ?>
        <div class="row">
          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>

          <!-- Contest -->
          <div class="small-12 medium-10 columns text-center">

            <?php if (ks_giveaways_has_error_message()): ?>
            <div class="row">
              <div class="small-12 columns">
                <div class="error-message"><?php echo ks_giveaways_error_message() ?></div>
              </div>
            </div>
            <?php endif ?>
            <div class="row ng-cloak" data-ng-if="error.message">
              <div class="small-12 columns">
                <div class="error-message">{{error.message}}</div>
              </div>
            </div>

            <div class="row">
              <div class="small-12 columns">
                <h1 class="text-center"><?php the_title() ?></h1>
              </div>
            </div>
            <?php if (!ks_giveaways_has_contestant()): ?>
              <div class="row text-center">
                <div class="small-12 medium-6 columns">
                  <h4 class="value"><?php printf(__("%s Value", KS_GIVEAWAYS_TEXT_DOMAIN), ks_giveaways_get_prize_value()) ?></h4>
                </div>
                <div class="small-12 medium-6 columns">
                  <h4 class="winners"><?php printf(__("%s Winner%s", KS_GIVEAWAYS_TEXT_DOMAIN), ks_giveaways_get_winner_count(), (ks_giveaways_get_winner_count() == 1 ? '' : 's')) ?></h4>
                </div>
              </div>
            <?php endif ?>

            <?php if (!ks_giveaways_has_started()): ?>
              <?php include 'not-started.php' ?>
            <?php elseif (ks_giveaways_has_ended()): ?>
              <?php include 'ended.php' ?>
            <?php elseif (ks_giveaways_has_started() && !ks_giveaways_has_ended()): ?>
              <?php include 'running.php' ?>
            <?php endif ?>

          </div>
          <!-- End Contest -->

          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>
        </div>
        <div class="row footer">
          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>

          <!-- Rules -->
          <div class="small-12 medium-10 columns text-center">
            <?php include 'rules.php' ?>
          </div>
          <!-- End Rules -->
          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>
        </div>
      </div>
      <div class="medium-5 small-12 left columns contest-images products">
        <?php if (ks_giveaways_has_image1()): ?>
            <?php if (ks_giveaways_has_image1_link()): ?>
                <a href="<?php echo ks_giveaways_get_image1_link() ?>">
            <?php endif ?>
            <img src="<?php echo ks_giveaways_get_image1_url() ?>" alt="" />
            <?php if (ks_giveaways_has_image1_link()): ?>
                </a>
            <?php endif ?>
        <?php endif ?>

          <?php if (ks_giveaways_has_image2()): ?>
              <?php if (ks_giveaways_has_image2_link()): ?>
                  <a href="<?php echo ks_giveaways_get_image2_link() ?>">
              <?php endif ?>
              <img src="<?php echo ks_giveaways_get_image2_url() ?>" alt="" />
              <?php if (ks_giveaways_has_image2_link()): ?>
                  </a>
              <?php endif ?>
          <?php endif ?>

          <?php if (ks_giveaways_has_image3()): ?>
              <?php if (ks_giveaways_has_image3_link()): ?>
                  <a href="<?php echo ks_giveaways_get_image3_link() ?>">
              <?php endif ?>
              <img src="<?php echo ks_giveaways_get_image3_url() ?>" alt="" />
              <?php if (ks_giveaways_has_image3_link()): ?>
                  </a>
              <?php endif ?>
          <?php endif ?>
      </div>
    </div>
    <div class="back">
      <div class="fullscreen background" style="background-image: url(<?php echo ks_giveaways_get_background_image_url() ?>)"></div>
    </div>

    <?php if(!ks_giveaways_has_contestant()): ?>
      <?php /* Not needed since angular directive includes this for us */ ?>
      <?php /* <script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script> */ ?>
    <?php endif; ?>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script type="text/javascript" src="<?php echo ks_giveaways_public_assets_url('build/public.js') ?>"></script>
    <?php ks_giveaways_include_countdown_translation(); ?>

    <?php /*
    <script>window.twttr = (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
      if (d.getElementById(id)) return t;
      js = d.createElement(s);
      js.id = id;
      js.src = "https://platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js, fjs);

      t._e = [];
      t.ready = function(f) {
        t._e.push(f);
      };

      return t;
    }(document, "script", "twitter-wjs"));</script>               

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=859406404089021&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    */ ?>

    <?php echo ks_giveaways_extra_footer() ?>

    <?php if(ks_giveaways_get_conversion_transient()): ?>
      <?php echo ks_giveaways_extra_contestant_footer() ?>
    <?php endif; ?>

    <?php if (function_exists('wpcss_handle_actions')): //CHECK IF CSS Hero is ACTIVE ?>
    <?php csshero_add_footer_trigger() ?>
    <?php endif ?>
     <?php //wp_footer(); ?>
  </body>
</html>