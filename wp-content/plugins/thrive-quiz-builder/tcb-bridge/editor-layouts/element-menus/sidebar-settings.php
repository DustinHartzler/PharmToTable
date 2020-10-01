<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

global $variation;
if ( empty( $variation ) ) {
	$variation = tqb_get_variation( $_REQUEST[ Thrive_Quiz_Builder::VARIATION_QUERY_KEY_NAME ] );
}
$page_type_name = tqb()->get_style_page_name( $variation['post_type'] );
?>
<a href="javascript:void(0)" class="click s-setting" data-fn="tqb_save_template">
	<span class="s-name"><?php echo sprintf( __( 'Save %s Template', Thrive_Quiz_Builder::T ), $page_type_name ); ?></span>
</a>

<a href="javascript:void(0)" class="click s-setting" data-fn="tqb_reset_template">
	<span class="s-name"><?php echo sprintf( __( 'Reset %s Template', Thrive_Quiz_Builder::T ), $page_type_name ); ?></span>
</a>

<a href="javascript:void(0)" class="click s-setting" data-fn="select_element" data-el="#tve_editor > .thrv_wrapper" style="order: -1">
	<span class="s-name"><?php echo sprintf( __( '%s Settings', Thrive_Quiz_Builder::T ), $page_type_name ); ?></span>
</a>
