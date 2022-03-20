<?php
/*
Plugin Name: URL Params
Plugin URI: http://asandia.com/wordpress-plugins/urlparams/
Description: Short Code to grab any URL Parameter
Version: 2.3
Author: Jeremy B. Shapiro
Author URI: http://www.asandia.com/
*/

/*
URL Params (Wordpress Plugin)
Copyright (C) 2011-2022 Jeremy Shapiro

*/

// error_reporting(E_ALL); // Helpful for checking for warnings that are TYPICALLY hidden but may be present on some installs

// Tell WordPress to register the shortcodes
add_shortcode("urlparam", "urlparam");
add_shortcode("ifurlparam", "ifurlparam");

function urlparam($attributes, $content) {
    $defaults = array(
        'param'          => '',
        'default'        => '',
        'dateformat'	 => '',
        'attr'           => '',
        'htmltag'        => false,
    );

    // We used to use shortcode_atts(), but that would nuke an extra attributes that we don't know about but want. array_merge() keeps them all.
    $attributes = array_merge($defaults, $attributes);

    $params = preg_split('/,\s*/',$attributes['param']);

    $return = false;

    foreach($params as $param)
    {
        if(!$return
            && array_key_exists($param, $_REQUEST)
            && ($rawText = $_REQUEST[$param])
        ) {
            if(($attributes['dateformat'] != '')
                && strtotime($rawText)
            ) {
                $return = date($attributes['dateformat'], strtotime($rawText));
            } else {
                $return = esc_html($rawText);
            }
        }
    }

    if(!$return) {
        $return = $attributes['default'];
    }

    if($attributes['attr']) {
        $return = ' ' . $attributes['attr'] . '="' . $return . '" ';

        if($attributes['htmltag']) {
            $tagName = $attributes['htmltag'];

            foreach(array_keys($defaults) as $key) {
                unset($attributes[$key]);
            }

            $otherAttributes = "";
            foreach($attributes as $key => $val) {
                $otherAttributes .= " $key=\"$val\"";
            }

            $return = "<$tagName $otherAttributes $return".($content ? ">$content</$tagName>" : "/>");
        }
    }

    return $return;
}

/*
 * If 'param' is found and 'is' is set, compare the two and display the contact if they match
 * If 'param' is found and 'is' isn't set, display the content between the tags
 * If 'param' is not found and 'empty' is set, display the content between the tags
 *
 */
function ifurlparam($attributes, $content) {
    $attributes = shortcode_atts(array(
        'param'           => '',
        'empty'          => false,
        'is'            => false,
    ), $attributes);

    $params = preg_split('/,\s*/',$attributes['param']);

    foreach($params as $param)
    {
        if($_REQUEST[$param])
        {
            if($attributes['empty'])
            {
                return '';
            } elseif(!$attributes['is']
                || ($_REQUEST[$param] == $attributes['is'])
            ) {
                return do_shortcode($content);
            }
        }
    }

    if ($attributes['empty'])
    {
        return do_shortcode($content);
    }

    return '';
}

?>
