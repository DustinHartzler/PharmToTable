<?php
/**
 * Plugin Name: Gleam
 * Plugin URI: https://gleam.io/docs/faq#wordpress
 * Description: Gleam competition plugin. Requires PHP 5.3
 * Version: 1.0
 * Author: Crowd9
 * Author URI: https://github.com/Crowd9/Gleam-Wordpress
 */

add_shortcode('gleam', function($atts, $content = null) {
    $args = shortcode_atts(array(
        'url' => null
    ), $atts);
    
    if (empty($args['url'])) {
        return;
    }
    return sprintf('<a class="e-gleam" href="%s" rel="nofollow">%s</a><script src="//js.gleam.io/e.js" async="true"></script>',
            htmlspecialchars($args['url']), do_shortcode($content));
});
