<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php _e('Insert Pretty Link', 'pretty-link'); ?></title>
    <style type="text/css">
      .ui-autocomplete-loading {
        background: white url("<?php echo admin_url('images/wpspin_light.gif'); ?>") right center no-repeat;
      }
      .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        width: 510px !important;
      }
    </style>
    <link rel="stylesheet" href="<?php echo PRLI_CSS_URL . '/tinymce_form_popup.css'; ?>" type="text/css" media="all" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" type="text/css" media="all" />
    <script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/jquery/jquery.js'); ?>"></script>
    <script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/jquery/ui/core.min.js'); ?>"></script>
    <script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/jquery/ui/widget.min.js'); ?>"></script>
    <script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/jquery/ui/position.min.js'); ?>"></script>
    <script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/jquery/ui/menu.min.js'); ?>"></script>
    <script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/jquery/ui/autocomplete.min.js'); ?>"></script>
    <script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/jquery/ui/accordion.min.js'); ?>"></script>
    <script type="text/javascript">
      //Setting up some JS variables for the tinymce_form_popup.js file
      //Doing this here becuase I have access to PHP
      var prli_selected_text  = ''; //Updated on PrliPopUpHandler.init
      var home_url            = '<?php echo $home_url; ?>';
      var default_redirect    = '<?php echo $default_redirect; ?>';
      var default_nofollow    = '<?php echo $default_nofollow; ?>';
      var default_tracking    = '<?php echo $default_tracking; ?>';
      var ajaxurl             = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <script language="javascript" type="text/javascript" src="<?php echo PRLI_JS_URL . '/tinymce_form_popup.js'; ?>"></script>
  </head>
  <body>
    <div id="errors"></div>
    <div id="prli_accordion">
      <h3><?php _e('Create New Pretty Link', 'pretty-link'); ?></h3>
      <div class="prlitinymce-options">
        <div class="prlitinymce-options-row">
          <label><?php _e('Target URL', 'pretty-link'); ?>:</label>
          <input type="text" name="prli_insert_link_target" id="prli_insert_link_target" value="" />
        </div>
        <div class="prlitinymce-options-row">
          <label><?php _e('Slug', 'pretty-link'); ?>:</label>
          <input type="text" name="prli_insert_link_slug" id="prli_insert_link_slug" value="<?php echo $random_slug; ?>" />
          <span id="prlitinymce-thinking" class="prlitinymce-hidden"><img src="<?php echo admin_url('images/wpspin_light.gif'); ?>" /></span>
          <span id="prlitinymce-good-slug" class="prlitinymce-hidden"><small><?php _e('valid', 'pretty-link'); ?></small></span>
          <span id="prlitinymce-bad-slug" class="prlitinymce-hidden"><small><?php _e('invalid', 'pretty-link'); ?></small></span>
          <input type="hidden" name="prli_is_valid_slug" id="prli_is_valid_slug" value="good" />
        </div>
        <div class="prlitinymce-options-row">
          <label><?php _e('Link Text', 'pretty-link'); ?>:</label>
          <input type="text" name="prli_insert_link_link_text" id="prli_insert_link_link_text" value="" />
        </div>
        <div class="prlitinymce-options-row">
          <label><?php _e('Redirect Type', 'pretty-link'); ?>:</label>
          <select name="prli_insert_link_redirect" id="prli_insert_link_redirect">
            <option value="default"><?php _e('Default', 'pretty-link'); ?></option>
            <option value="307"><?php _e('307 (Temporary)', 'pretty-link'); ?></option>
            <option value="302"><?php _e('302 (Temporary)', 'pretty-link'); ?></option>
            <option value="301"><?php _e('301 (Permanent)', 'pretty-link'); ?></option>
            <?php global $plp_update; ?>
            <?php if($plp_update->is_installed()): ?>
              <option value="prettybar"><?php _e('Pretty Bar', 'pretty-link'); ?></option>
              <option value="cloak"><?php _e('Cloaked', 'pretty-link'); ?></option>
              <option value="pixel"><?php _e('Pixel', 'pretty-link'); ?></option>
              <option value="metarefresh"><?php _e('Meta Refresh', 'pretty-link'); ?></option>
              <option value="javascript"><?php _e('Javascript', 'pretty-link'); ?></option>
            <?php endif; ?>
          </select>
        </div>
        <div class="prlitinymce-options-row">
          <label><?php _e('Nofollow', 'pretty-link'); ?>:</label>
          <select name="prli_insert_link_nofollow" id="prli_insert_link_nofollow">
            <option value="default"><?php _e('Default', 'pretty-link'); ?></option>
            <option value="enabled"><?php _e('Enabled', 'pretty-link'); ?></option>
            <option value="disabled"><?php _e('Disabled', 'pretty-link'); ?></option>
          </select>
        </div>
        <div class="prlitinymce-options-row">
          <label><?php _e('Tracking', 'pretty-link'); ?>:</label>
          <select name="prli_insert_link_tracking" id="prli_insert_link_tracking">
            <option value="default"><?php _e('Default', 'pretty-link'); ?></option>
            <option value="enabled"><?php _e('Enabled', 'pretty-link'); ?></option>
            <option value="disabled"><?php _e('Disabled', 'pretty-link'); ?></option>
          </select>
        </div>
        <div class="prlitinymce-options-row">
          <label>&nbsp;</label>
          <input type="checkbox" name="prli_insert_link_new_tab" id="prli_insert_link_new_tab" /> <?php _e('Open this Pretty Link in a new window/tab', 'pretty-link'); ?>
        </div>
        <div class="prlitinymce-options-row" id="prlitinymce-insert">
          <a href="javascript:PrliPopUpHandler.insert_new()" class="prli_button"><?php _e('Insert New Pretty Link', 'pretty-link'); ?></a>
          <span id="insert_loading" class="prlitinymce-hidden"><img src="<?php echo includes_url('/js/thickbox/loadingAnimation.gif'); ?>" width="150" /></span>
        </div>
      </div>
      <h3><?php _e("Use Existing Pretty Link", 'pretty-link'); ?></h3>
      <div id="prlitinymce-search-area" class="prlitinymce-options">
        <input type="text" name="prli_search_box" id="prli_search_box" value="" placeholder="<?php _e('Search by Slug, Title, or Target URL...', 'pretty-link'); ?>" />
        <div class="prlitinymce-options-row">
          <label class="lefty"><?php _e('Target URL', 'pretty-link'); ?>:</label>
          <small id="existing_link_target" class="righty"><?php _e('None', 'pretty-link'); ?></small>
        </div>
        <div class="prlitinymce-options-row">
          <label class="lefty"><?php _e('Pretty Link', 'pretty-link'); ?>:</label>
          <small id="existing_link_slug" class="righty"><?php _e('None', 'pretty-link'); ?></small>
        </div>
        <div class="prlitinymce-options-row">
          <label><?php _e('Link Text', 'pretty-link'); ?>:</label>
          <input type="text" name="existing_link_link_text" id="existing_link_link_text" value="" />
        </div>
        <div class="prlitinymce-options-row">
          <label>&nbsp;</label>
          <input type="checkbox" name="existing_link_new_tab" id="existing_link_new_tab" /> <?php _e('Open this Pretty Link in a new window/tab', 'pretty-link'); ?>
        </div>
        <div class="prlitinymce-options-row" id="existing_link_insert">
          <input type="hidden" name="existing_link_nofollow" id="existing_link_nofollow" value="0" />
          <a href="javascript:PrliPopUpHandler.insert_existing()" class="prli_button"><?php _e('Insert Existing Pretty Link', 'pretty-link'); ?></a>
        </div>
      </div>
    </div>
  </body>
</html>
