<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$fb_app_ID                      = 'https://developers.facebook.com/apps/';
$fb_app_ID_help                 = 'https://developers.facebook.com/docs/apps/';
$fb_debugger                    = 'https://developers.facebook.com/tools/debug/';
$twitter_card                   = 'https://cards-dev.twitter.com/validator/';
$iframely                       = 'http://debug.iframely.com/';
$og_check                       = 'https://opengraphcheck.com/';
$reop_url                       = 'https://wordpress.org/support/plugin/open-graphite';
$pro_support_url                = 'https://rocketapps.com.au/log-ticket/';   
$buy_url                        = 'https://rocketapps.com.au/buy/?product=open-graphite-pro'; 
$yoast_search_appearance_url    = admin_url() . 'admin.php?page=wpseo_titles';
$yoast_facebook_url             = admin_url() . 'admin.php?page=wpseo_social#top#facebook';
$yoast_twitter_url              = admin_url() . 'admin.php?page=wpseo_social#top#twitterbox';
$yoast_search_appearance_url    = admin_url() . 'admin.php?page=wpseo_titles';
$image_path                     = plugins_url('../images/help', __FILE__ );
?>

<div class="wrap">
<h2><?php _e( 'Open Graphite Help', 'open-graphite' ); ?></h2>

<div class="og-help-topics">
    
    <p class="topic"><?php _e( 'What does this plug-in do?', 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php _e( 'This plug-in will place the appropriate Open Graph meta tags and attributes into the head of your web pages, so that when someone links to one of your pages (or homepage) on social media the correct image and other information will show.', 'open-graphite' ); ?></p>
    </div>

    <p class="topic"><?php _e( 'Can I customise how each page or post is previewed when shared on social media?', 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php _e( 'Yes. You can either set the option to automatically use the existing title, text (or excerpt) and image, or specify them manually on each page or post in the Open Graphite metabox.', 'open-graphite' ); ?></p>
        <img src="<?php echo $image_path; ?>/metabox.png" />
    </div>

    <p class="topic"><?php _e( 'There appears to be duplicate sets of open graph tags.', 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php _e( 'Any other plug-in that inserts the open graph properties into the code of your website (Yoast is one that comes to mind) may cause Open Graphite to not work properly.', 'open-graphite' ); ?></p>
        <p><?php _e( "To test if you have a conflict, simply view the source code of your home page in your browser and search for any instances of <code>og:</code> within. Typically a plug-in will output the meta tags into it's own group. For this plug-in, they will be directly below the <code>&lt;!--/ Open Graphite /--&gt;</code> comment but other plug-ins will output something else (if at all). The only solution to resolve a conflict is to disable one of the plug-ins.", 'open-graphite' ); ?></p>
    </div>

    <?php if(in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins')))) { ?>
    <p class="topic" id="yoast"><?php _e( "How do I prevent Jetpack from conflicting with Open Graphite?", 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php printf(__( 'To avoid a situation where you have sets of open graph tags generated by both Yoast and Open Graphite, you will need to <a href="%1$s" target="_blank">disable the Yoast Facebook setting</a> and then <a href="%2$s" target="_blank">disable the Yoast Twitter setting</a>. Finally, you will need to make sure the organisation name and logo are empty <a href="%3$s" target="_blank">here</a>.', 'open-graphite' ), $yoast_facebook_url, $yoast_twitter_url, $yoast_search_appearance_url); ?></p>
    </div>
    <?php } ?>

    <?php if ( class_exists( 'Jetpack' )) { ?>
    <p class="topic" id="yoast"><?php _e( "How do I prevent Jetpack from conflicting with Open Graphite?", 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php printf(__( 'To avoid a situation where you have multiple sets of open graph tags generated by both Jetpack and Open Graphite, you will need to disable Jetpack open graph meta tags by going to <code>Open Graphite</code> -> <code>Troubleshooting</code> and enabling the <code>Avoid Jetpack open graph tag conflict</code> checkbox.', 'open-graphite' ), $yoast_facebook_url, $yoast_twitter_url, $yoast_search_appearance_url); ?></p>
    </div>
    <?php } ?>

    <p class="topic"><?php _e( "Facebook isn't showing the correct information when I share my page or post.", 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php _e( "Facebook (and possibly other social networks) will cache the open graph properties of a post that has previously been shared for up to 24 hours (maybe longer). During this time, any changes you make to your open graph properties will not be picked up by Facebook immediately. But there is an easy fix.", 'open-graphite' ); ?></p>
        <p><?php printf(__( 'Using the official <a href="%1$s" target="_blank" rel="noopener">Facebook Debugger</a>, paste in the URL of your page or post and hit the <strong>Debug</strong> button. When it has finished, hit the <strong>Scrape Again</strong> button. This will force Facebook to fetch the latest open graph data from your post. Twitter has a similar tool called the <a href="%2$s" target="_blank" rel="noopener">Card Validator</a> for this purpose as well.', 'open-graphite' ), $fb_debugger, $twitter_card); ?></p>
        <img src="<?php echo $image_path; ?>/facebook-debugger.png" />
        <p><?php _e( 'Facebook might complain about the lack of an App ID when you pass your URL through their debugger, but this will not prevent anything from working correctly.', 'open-graphite' ); ?></p>
    </div>

    <p class="topic"><?php _e( 'Do I need a Facebook App ID?', 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php printf(__(  'You probably do not need a Facebook App ID. That said, there may be instances where having one is helpful. <a href="%1$s" target="_blank" rel="noopener">Decide for yourself</a>.', 'open-graphite' ), $fb_app_ID_help); ?></p>
    </div>

    <p class="topic"><?php _e( 'Where can I find my Facebook App ID?', 'open-graphite' ); ?></p>
    <div class="help-answer">
        <p><?php printf(__( 'If you have a Facebook App, you can get the App ID from <a href="%1$s" target="_blank">your developer dashboard.</a>', 'open-graphite' ), $fb_app_ID); ?></p>
    </div>

    <p class="topic"><?php _e( 'Will my current settings be inherited if I upgrade to pro?', 'open-graphite' ); ?></p>
    <div class="help-answer">
    <p><?php printf(__(  'Yes. If you <a href="%1$s" target="_blank" rel="noopener">upgrade to Pro</a> all your current configurations will remain.', 'open-graphite' ), $buy_url); ?></p>
    </div>

    <p class="topic"><?php _e( 'Where can I get support for this plugin?', 'open-graphite' ); ?></p>
    <div class="help-answer">
    <p><?php printf(__( 'Pro users can get priority support at <a href="%1$s" target="_blank">Rocket Apps</a>. If you are using the free version of this plugin, leave a support message at the <a href="%2$s" target="_blank">Wordpress plug-in repo</a>.', 'open-graphite' ), $pro_support_url, $reop_url); ?></p>
    </div>
    
</div>

<?php if(isset($_GET['topic'])) { $topic = $_GET['topic']; } ?>
<script>
    jQuery('.topic').click(function(){
        jQuery(this).closest('p').next('.help-answer').fadeToggle(0);
        jQuery(this).toggleClass('active');
    });
    <?php if(isset($_GET['topic'])) { ?>
    jQuery( document ).ready(function() {
        jQuery('#<?php echo $topic; ?>').addClass('active');
        jQuery('#<?php echo $topic; ?>').closest('p').next('.help-answer').fadeIn(0);
    });
    <?php } ?>
</script>
