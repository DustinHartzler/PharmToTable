<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<h2><?php echo esc_html__( 'Reset Template', TVE_DASH_TRANSLATE_DOMAIN ); ?></h2>
<br>
<p>
	<?php echo esc_html__( 'Are you sure you want to reset the design ?', TVE_DASH_TRANSLATE_DOMAIN ); ?>
	<?php echo esc_html__( 'You will lose any customizations made to it.', TVE_DASH_TRANSLATE_DOMAIN ); ?>
</p>
<br><br>

<div class="ttd-modal-footer">
	<button class="tcb-left tve-button medium grey click" data-fn="close"><?php echo esc_html__( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ); ?></button>
	<button class="tcb-right tve-button medium red click" data-fn="reset"><?php echo esc_html__( 'Reset Design', TVE_DASH_TRANSLATE_DOMAIN ); ?></button>
</div>
