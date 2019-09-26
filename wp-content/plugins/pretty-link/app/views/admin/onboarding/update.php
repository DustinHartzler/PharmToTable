<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); } ?>

<div class="wrap prli-update">
  <h1><?php esc_html_e('Get Some Quick Wins with Pretty Links 3.0!', 'pretty-link'); ?></h1>

  <p>
    <img src="<?php echo PRLI_IMAGES_URL . '/update-3-0.png'; ?>" alt="<?php esc_attr_e('Get Some Quick Wins with Pretty Links 3.0!', 'pretty-link'); ?>">
  </p>

  <p>
    <?php
      printf(
        // translators: %1$s: open strong tag, %2$s: close strong tag
        esc_html__('We hope you know that here at Pretty Links, we work every day to deliver amazing tools for business owners to help them %1$swin more%2$s. We want our customers to make more money, easier through superior affiliate link strategy and management.', 'pretty-link'),
        '<strong><em>',
        '</em></strong>'
      );
    ?>
  </p>

  <p>
    <?php
      printf(
        // translators: %1$s: open strong tag, %2$s: close strong tag
        esc_html__('That\'s why today, we\'re excited to announce that we\'re rolling out a major, new release. This release won\'t deliver any huge, new features but rather a set of incremental features & fixes that will %1$sinstantly give you some well deserved quick wins%2$s in your business.', 'pretty-link'),
        '<strong><em>',
        '</em></strong>'
      );
    ?>
  </p>

  <p>
    <?php
      printf(
        // translators: %1$s: open strong tag, %2$s: close strong tag
        esc_html__('That\'s why we\'re calling Pretty Links 3.0 - %1$sQuick Wins!%2$s', 'pretty-link'),
        '<strong><em>',
        '</em></strong>'
      );
    ?>
  </p>

  <h2><?php esc_html_e('What Changes Can You Expect, Exactly?', 'pretty-link'); ?></h2>

  <p><?php esc_html_e('Check out these solid improvements that will make your experience with Pretty Links better in almost every way:', 'pretty-link'); ?></p>

  <ul class="prli-bullet-list">
    <li>
      <p>
        <?php
          printf(
            // translators: %1$s: open strong tag, %2$s: close strong tag
            esc_html__('%1$sAn all-new Link Editor%2$s - We\'ve completely overhauled our link editing experience. Our new, power-packed link editor will make creating and managing your affiliate links easier than ever before!', 'pretty-link'),
            '<strong>',
            '</strong>'
          );
        ?>
      </p>
      <div class="prli-row prli-2-columns">
        <div class="prli-column">
          <a href="<?php echo PRLI_IMAGES_URL . '/update-new-link-editor-1.png'; ?>" class="prli-image-popup" title="<?php esc_attr_e('An all-new Link Editor', 'pretty-link'); ?>">
            <img src="<?php echo PRLI_IMAGES_URL . '/update-new-link-editor-1.png'; ?>" alt="<?php esc_attr_e('An all-new Link Editor', 'pretty-link'); ?>">
          </a>
        </div>
        <div class="prli-column">
          <a href="<?php echo PRLI_IMAGES_URL . '/update-new-link-editor-2.png'; ?>" class="prli-image-popup" title="<?php esc_attr_e('An all-new Link Editor', 'pretty-link'); ?>">
            <img src="<?php echo PRLI_IMAGES_URL . '/update-new-link-editor-2.png'; ?>" alt="<?php esc_attr_e('An all-new Link Editor', 'pretty-link'); ?>">
          </a>
        </div>
      </div>
    </li>
    <li>
      <p>
        <?php
          printf(
            // translators: %1$s: open strong tag, %2$s: close strong tag
            esc_html__('%1$sAll new Link Listing%2$s - Gone is the clumsy, old Link Listing feature! You’ll now be able to send links to the "Trash," customize what columns you see in your listing with "Screen Options" and change the number of rows displayed.', 'pretty-link'),
            '<strong>',
            '</strong>'
          );
        ?>
      </p>
      <div class="prli-row">
        <div class="prli-column">
          <a href="<?php echo PRLI_IMAGES_URL . '/update-all-new-link-listing.png'; ?>" class="prli-image-popup" title="<?php esc_attr_e('All new Link Listing', 'pretty-link'); ?>">
            <img src="<?php echo PRLI_IMAGES_URL . '/update-all-new-link-listing.png'; ?>" alt="<?php esc_attr_e('All new Link Listing', 'pretty-link'); ?>">
          </a>
        </div>
      </div>
    </li>
    <li>
      <p>
        <?php
          printf(
          // translators: %1$s: open strong tag, %2$s: close strong tag
            esc_html__('%1$sCustomizable Links Tags / Categories (Pro Only)%2$s - Once you upgrade to Quick Wins, you\'ll be able to categorize and tag your pretty links! This is a long-requested feature that will help you organize your links and group them any way that you\'d like to for ultimate productivity and monetization!', 'pretty-link'),
            '<strong>',
            '</strong>'
          );
        ?>
      </p>
      <div class="prli-row">
        <div class="prli-column">
          <a href="<?php echo PRLI_IMAGES_URL . '/update-categories-tags.png'; ?>" class="prli-image-popup" title="<?php esc_attr_e('Customizable Links Tags / Categories (Pro Only)', 'pretty-link'); ?>">
            <img src="<?php echo PRLI_IMAGES_URL . '/update-categories-tags.png'; ?>" alt="<?php esc_attr_e('Customizable Links Tags / Categories (Pro Only)', 'pretty-link'); ?>">
          </a>
        </div>
      </div>
    </li>
    <li>
      <p>
        <?php
          printf(
          // translators: %1$s: open strong tag, %2$s: close strong tag
            esc_html__('%1$sGutenberg Block Link Integration%2$s - You can now use your Pretty Links directly inside the Gutenberg Paragraph Block. All you have to do is select the "Pretty Link" button from your Paragraph Block\'s formatting bar then you can search and insert Pretty Links instantly!', 'pretty-link'),
            '<strong>',
            '</strong>'
          );
        ?>
      </p>
      <div class="prli-row">
        <div class="prli-column">
          <a href="<?php echo PRLI_IMAGES_URL . '/update-gutenberg-editor.png'; ?>" class="prli-image-popup" title="<?php esc_attr_e('Gutenberg Block Link Integration', 'pretty-link'); ?>">
            <img src="<?php echo PRLI_IMAGES_URL . '/update-gutenberg-editor.png'; ?>" alt="<?php esc_attr_e('Gutenberg Block Link Integration', 'pretty-link'); ?>">
          </a>
        </div>
      </div>
    </li>
  </ul>

  <h2><?php esc_html_e('Tons of Fixes and Enhancements', 'pretty-link'); ?></h2>

  <p><?php esc_html_e('In addition to these streamlined, new features we’ve made hundreds of enhancements and numerous small fixes. Pretty Links Quick Wins is going to make your link experience easier, faster, more secure and more capable than ever before!', 'pretty-link'); ?></p>

  <p>
    <?php
      printf(
        // translators: %1$s: open strong tag, %2$s: close strong tag
        esc_html__('We\'re excited for you to start winning more today with %1$sPretty Link Quick Wins!%2$s', 'pretty-link'),
        '<strong><em>',
        '</em></strong>'
      );
    ?>
  </p>

</div>
