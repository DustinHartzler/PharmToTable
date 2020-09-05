<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';

function ks_giveaways_get_winner_count()
{
  return KS_Helper::get_winner_count();
}

function ks_giveaways_get_winners()
{
  return KS_Helper::get_winners();
}

function ks_giveaways_get_entries_per_friend()
{
  return KS_Helper::get_entries_per_friend();
}

function ks_giveaways_get_youtube_channel_url()
{
  return KS_Helper::get_youtube_channel_url();
}

function ks_giveaways_get_link_entry_count($url)
{
  return KS_Helper::get_link_entry_count($url);
}

function ks_giveaways_get_all_action_entries_count()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return array();
  }

  return KS_Entry_DB::get_all_action_entries_count($GLOBALS['ks_giveaways_contestant']->ID);
}

/**
 * Gets image URL for pinterest, converts relative URL to absolute if needed.
 */
function ks_giveaways_get_pinterest_image_url()
{
  $url = ks_giveaways_get_prize_image_url();

  /* return if already absolute URL */
  if (parse_url($url, PHP_URL_SCHEME) != '') {
    return $url;
  }

  return get_site_url(null, $url);
}

function ks_giveaways_get_lucky_url($share = true)
{
  return KS_Helper::get_lucky_url(null, null, $share);
}

function ks_giveaways_get_my_entries()
{
  return KS_Helper::get_my_entries();
}

function ks_giveaways_get_contestant_entries()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return array();
  }
  return KS_Helper::get_contestant_entries($GLOBALS['ks_giveaways_contestant']->ID);
}

function ks_giveaways_get_total_entries()
{
  return KS_Helper::get_total_entries();
}

function ks_giveaways_get_total_winners()
{
  return KS_Helper::get_total_winners();
}

function ks_giveaways_get_description()
{
  return KS_Helper::get_description();
}

function ks_giveaways_get_rules()
{
  $rules = KS_Helper::replace_shortcodes(KS_Helper::get_rules());

  return $rules;
}

function ks_giveaways_get_timezone_city($offset = null)
{
  return KS_Helper::get_timezone_city($offset);
}

function ks_giveaways_get_timezone_abbr($offset = null)
{
  return KS_Helper::get_timezone_abbr($offset);
}

function ks_giveaways_has_started()
{
  return KS_Helper::has_started(get_post());
}

function ks_giveaways_has_ended()
{
  return KS_Helper::has_ended(get_post());
}

function ks_giveaways_get_date_start()
{
  return KS_Helper::get_date_start(get_post());
}

function ks_giveaways_get_date_text($epoch)
{
  $offset = get_option('gmt_offset');

  return sprintf('%s %s', date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $epoch+($offset*3600)), KS_Helper::get_timezone_abbr($offset));
}

function ks_giveaways_get_date_end()
{
  return KS_Helper::get_date_end(get_post());
}

function ks_giveaways_get_date_awarded()
{
  return KS_Helper::get_date_awarded(get_post());
}

function ks_giveaways_get_image1_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_image_1', true);
}

function ks_giveaways_has_image1()
{
  $post = get_post();

  return (get_post_meta($post->ID, '_image_1', true) != '');
}

function ks_giveaways_get_image1_link()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_1_link', true);
}

function ks_giveaways_has_image1_link()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_1_link', true) != '');
}

function ks_giveaways_get_image2_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_image_2', true);
}

function ks_giveaways_has_image2()
{
  $post = get_post();

  return (get_post_meta($post->ID, '_image_2', true) != '');
}

function ks_giveaways_get_image2_link()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_2_link', true);
}

function ks_giveaways_has_image2_link()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_2_link', true) != '');
}

function ks_giveaways_get_image3_url()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_3', true);
}

function ks_giveaways_has_image3()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_3', true) != '');
}

function ks_giveaways_get_image3_link()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_3_link', true);
}

function ks_giveaways_has_image3_link()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_3_link', true) != '');
}

function ks_giveaways_get_background_image_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_background_image', true);
}

function ks_giveaways_get_prize_image_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_prize_image', true);
}

function ks_giveaways_get_prize_value()
{
  return KS_Helper::get_prize_value();
}

function ks_giveaways_get_prize_name()
{
  return KS_Helper::get_prize_name();
}

function ks_giveaways_get_prize_brand()
{
  return KS_Helper::get_prize_brand();
}

function ks_giveaways_get_share_message()
{
  return get_the_title();
}

function ks_giveaways_get_logo_image_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_logo_image', true);
}

function ks_giveaways_cookie_first_name()
{
    return isset($_COOKIE[KS_GIVEAWAYS_COOKIE_FIRST_NAME]) ? $_COOKIE[KS_GIVEAWAYS_COOKIE_FIRST_NAME] : '';
}

function ks_giveaways_cookie_email()
{
  return isset($_COOKIE[KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS]) ? $_COOKIE[KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS] : '';
}

function ks_giveaways_has_background_image()
{
  $post = get_post();

  return (get_post_meta($post->ID, '_background_image', true) != '');
}

// Question enable/disable option added in 1.8.3.  If option doesn't exist default to true.
function ks_giveaways_question_enabled()
{
  $post = get_post();
  if (metadata_exists('post', $post->ID, '_enable_question')) {
    $enabled = get_post_meta($post->ID, '_enable_question', true);
    if ($enabled) {
      return true;
    }

    return false;
  }

  return true;
}

function ks_giveaways_question()
{
  $question = KS_Helper::get_question();
  $wrong_answer1 = KS_Helper::get_wrong_answer1();
  $wrong_answer2 = KS_Helper::get_wrong_answer2();
  $right_answer = KS_Helper::get_right_answer();

  $answers = array();
  $answers[] = array('value' => 'wrong', 'text' => $wrong_answer1);
  $answers[] = array('value' => 'wrong', 'text' => $wrong_answer2);
  $answers[] = array('value' => 'right', 'text' => $right_answer);

  $ret = sprintf('<label for="giveaways_answer">%s</label>', $question);
  $ret .= '<select id="giveaways_answer" data-ng-model="entryForm.qualifyAnswer" data-ng-class="{\'is-invalid-input\':entryForm.qualifyAnswer === \'wrong\'}">';
  $ret .= '<option>-- ' . __('Select your answer.', KS_GIVEAWAYS_TEXT_DOMAIN) . ' --</option>';
  foreach ($answers as $answer) {
    $ret .= sprintf('<option value="%s">%s</option>', $answer['value'], $answer['text']);
  }
  $ret .= '</select>';

  return $ret;
}

function ks_giveaways_has_contestant()
{
  if (!empty($GLOBALS['ks_giveaways_contestant'])) {
    return true;
  }

  return false;
}

function ks_giveaways_contestant_needs_confirmation()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return false;
  }

  if (get_option(KS_GIVEAWAYS_OPTION_DRAW_MODE) === 'confirmed') {
    return ($GLOBALS['ks_giveaways_contestant']->status != 'confirmed');
  }
}

function ks_giveaways_is_confirmed_contestant()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return false;
  }

  return ($GLOBALS['ks_giveaways_contestant']->status == 'confirmed');
}

function ks_giveaways_assets_url($asset = NULL)
{
  $content_dir = trailingslashit(WP_CONTENT_DIR);

  $template_file = KS_Helper::get_template_file();

  // content directory theme
  $dir = str_replace($content_dir, '', $template_file);
  $dir = dirname($dir);
  $url = trailingslashit(content_url($dir));

  if ($asset) {
    $asset .= '?' . KS_GIVEAWAYS_EDD_VERSION;
  }

  return trailingslashit($url . 'assets') . $asset;
}

function ks_giveaways_public_assets_url($asset = NULL)
{
  $file = realpath(dirname(__FILE__));
  $url = trailingslashit(trailingslashit(plugin_dir_url($file)) . 'assets');

  if ($asset) {
    $asset .= '?' . KS_GIVEAWAYS_EDD_VERSION;
  }

  return $url . $asset;
}

function ks_giveaways_extra_footer()
{
  return get_option(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER);
}

function ks_giveaways_extra_contestant_footer()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return '';
  }

  return get_option(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER);
}

function ks_giveaways_has_error_message()
{
  return !empty($GLOBALS['ks_giveaways_error_message']);
}

function ks_giveaways_error_message()
{
  if (empty($GLOBALS['ks_giveaways_error_message'])) {
    return '';
  }

  return $GLOBALS['ks_giveaways_error_message'];
}

/**
 * Reads conversion transient if available, then deletes it.
 */
function ks_giveaways_get_conversion_transient()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return false;
  }

  $transient_name = KS_GIVEAWAYS_TRANSIENT_CONVERSION.$GLOBALS['ks_giveaways_contestant']->ID;
  $transient_value = get_transient($transient_name);
  delete_transient($transient_name);
  return $transient_value;
}

/**
 * Include jquery-countdown translation if one is found matching locale
 * file name is: public/assets/jquery.countdown-de.js
 */
function ks_giveaways_include_countdown_translation()
{
  $locale = substr(get_locale(), 0, 2);

  $translation_files = scandir(KS_GIVEAWAYS_PLUGIN_DIR . '/public/assets/js/jquery.countdown.localizations');

  if ($translation_files) {
    foreach ($translation_files as $file) {
      if (substr($file, -5, 2) === $locale) {
        printf('<script type="text/javascript" src="%sjs/jquery.countdown.localizations/%s"></script>', ks_giveaways_public_assets_url(), $file);
        break;
      }
    }
  }

  return NULL;
}

function ks_giveaways_get_giveaway_id()
{
  $post = get_post();
  return $post->ID;
}

function ks_giveaways_get_contestant_id()
{
  if (!empty($GLOBALS['ks_giveaways_contestant'])) {
    return $GLOBALS['ks_giveaways_contestant']->ID;
  }

  return NULL;
}

function ks_giveaways_show_badge()
{
  $option = get_option(KS_GIVEAWAYS_OPTION_SHOW_KS_BADGE);

  return $option === false || (int) $option === 1;
}

/**
 * Gets sharing platform enabled option.  Defaults to true;
 * @param String $platform - [facebook, twitter, linkedin, pinterest]
 * @return Bool
 */
function ks_giveaways_sharing_platform_enabled($platform)
{
  $post = get_post();
  if (metadata_exists('post', $post->ID, '_enable_' . $platform)) {
    $enabled = get_post_meta($post->ID, '_enable_' . $platform, true);
    if ($enabled) {
      return true;
    }

    return false;
  }

  return true;
}

/**
 * Increments counter to use as step number next to enabled social platforms.
 * @return Number
 */
function ks_giveaways_sharing_platform_counter()
{
  static $counter = 0;
  return ++$counter;
}
