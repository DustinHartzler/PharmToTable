<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<h2 class="tcb-modal-title"><?php echo __( 'Save Content as Template', 'thrive-cb' ) ?></h2>
<div class="mt-20">
	<?php echo __( 'You can save your work as a template for use on another post/page on your site.', 'thrive-cb' ) ?>
</div>

<div class="tvd-input-field mb-5 mt-25">
	<input type="text" id="tve-template-name" required>
	<label for="tve-template-name"><?php echo __( 'Template Name', 'thrive-cb' ); ?></label>
</div>

<div class="tcb-modal-footer clearfix mt-20 control-grid flex-end">
	<button type="button" class="tcb-right tve-button medium green tcb-modal-save">
		<?php echo __( 'Save Template', 'thrive-cb' ) ?>
	</button>
</div>
