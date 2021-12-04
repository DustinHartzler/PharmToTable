<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-leads
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>
<a href="javascript:void(0)" class="setting-item click s-setting" data-fn="tl_template_save">
	<span class="s-name"><?php echo __( 'Save Template', 'thrive-cb' ); ?></span>
</a>

<a href="javascript:void(0)" class="setting-item click s-setting" data-fn="tl_template_reset">
	<span class="s-name"><?php echo __( 'Reset to default content', 'thrive-leads' ); ?></span>
</a>

<a href="javascript:void(0)" class="setting-item click s-setting" data-fn="select_element" data-el=".tve_editor_main_content,.tve_p_lb_content" style="order: -1">
	<span class="s-name"><?php echo __( 'Form Settings', 'thrive-leads' ); ?></span><?php tcb_icon( 'cog-light'); ?>
</a>

