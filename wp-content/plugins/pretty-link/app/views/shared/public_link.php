<?php if(!defined('ABSPATH')) die('You are not allowed to call this page directly.');

// Escape all variables used on this page
$pretty_link_id   = esc_html( $pretty_link_id );
$target_url_raw   = esc_url_raw( $target_url, array('http','https') );
$target_url       = esc_url( $target_url, array('http','https') );
$pretty_link_raw  = esc_url_raw( $pretty_link, array('http','https') );
$pretty_link      = esc_url( $pretty_link, array('http','https') );
$prli_blogurl_raw = esc_url_raw( $prli_blogurl, array('http','https') );
$prli_blogurl     = esc_url( $prli_blogurl, array('http','https') );
$target_url_title = esc_html( $target_url_title );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php _e('Here is your Pretty Link', 'pretty-link'); ?></title>
    <script type='text/javascript' src='<?php echo site_url('/wp-includes/js/jquery/jquery.js'); ?>'></script>
    <script type='text/javascript' src='<?php echo PRLI_JS_URL . '/jquery.clippy.js'; ?>'></script>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        /* Set up the clippies! */
        $('.clippy').clippy({clippy_path: '<?php echo PRLI_JS_URL; ?>/clippy.swf', width: '100px'});
      });
    </script>
    <link rel="stylesheet" href="<?php echo PRLI_VENDOR_LIB_URL.'/fontello/css/animation.css'; ?>" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo PRLI_VENDOR_LIB_URL.'/fontello/css/pretty-link.css'; ?>" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo PRLI_CSS_URL . '/social_buttons.css'; ?>" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo PRLI_CSS_URL . '/public_link.css'; ?>" type="text/css" media="all" />
  </head>
  <body>
    <p><img src="<?php echo PRLI_IMAGES_URL; ?>/pl-logo-horiz-RGB.svg" width="400px" height="64px" /></p>
    <h4><em><?php _e('Here\'s your pretty link for:', 'pretty-link'); ?></em><br/><?php echo $target_url_title; ?><br/>(<span title="<?php echo $target_url; ?>"><?php echo substr($target_url,0,50) . ((strlen($target_url)>50)?"...":''); ?></span>)</h4>
    <h2><a href="<?php echo $pretty_link_raw; ?>"><?php echo $pretty_link; ?></a><br/><span class="clippy"><?php echo $pretty_link_raw; ?></span></h2>
    <?php global $plp_update; ?>
    <?php if( $plp_update->is_installed() ): ?>
      <p><?php _e('send this link to:', 'pretty-link'); ?><br/>
      <?php echo PlpSocialButtonsHelper::get_social_buttons_bar($pretty_link_id); ?>
    <?php endif; ?>
    <p><a href="<?php echo $target_url_raw; ?>">&laquo; <?php _e('back', 'pretty-link'); ?></a></p>
  </body>
</html>


