<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); }

class PrliOnboardingController extends PrliBaseController {
  public function load_hooks() {
    // must be before PrliAppController::install() to detect a fresh install
    add_action('init', array($this, 'maybe_onboard'), 5);

    if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
      add_action('wp_loaded', array($this, 'onboarding_intercept'));
    }
  }

  public function maybe_onboard() {
    if (get_option('prli_version') == PRLI_VERSION) {
      return;
    }

    if (get_option('prli_db_version')) {
      // existing install
      if ($this->is_major_update()) {
        update_option('prli_onboard', 'update');
        wp_cache_delete('alloptions', 'options');
      }
    } else {
      // fresh install
      update_option('prli_onboard', 'welcome');
      wp_cache_delete('alloptions', 'options');
    }

    update_option('prli_version', PRLI_VERSION);
    wp_cache_delete('alloptions', 'options');
  }

  protected function is_major_update() {
    $oldVersion = get_option('prli_version');

    if (empty($oldVersion)) {
      return true;
    }

    list($oldMajor, $oldMinor) = explode('.', $oldVersion);
    list($newMajor, $newMinor) = explode('.', PRLI_VERSION);

    return $newMajor > $oldMajor || ($newMajor == $oldMajor && $newMinor > $oldMinor);
  }

  public function onboarding_intercept() {
    $onboard = get_option('prli_onboard');

    if ($onboard == 'welcome' || $onboard == 'update') {
      if (delete_option('prli_onboard')) {
        wp_cache_delete('alloptions', 'options');
        nocache_headers();
        wp_redirect(admin_url("admin.php?page=pretty-link-{$onboard}"), 307);
        exit;
      }
    }
  }

  public function welcome_route() {
    require_once PRLI_VIEWS_PATH . '/admin/onboarding/welcome.php';
  }

  public function update_route() {
    require_once PRLI_VIEWS_PATH . '/admin/onboarding/update.php';
  }
}
