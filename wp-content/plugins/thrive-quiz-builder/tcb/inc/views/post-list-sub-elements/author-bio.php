<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

global $post;

$author             = empty( $post ) ? null : $post->post_author;
$author_description = get_the_author_meta( 'description', $author );

if ( empty( $author_description ) ) {
	echo TCB_Editor()->is_inner_frame() || TCB_Utils::is_rest() ? __( 'No Author Description', 'thrive-cb' ) : '';
} else {
	echo $author_description;
}
