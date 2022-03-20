<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<span class="tcb-modal-title"><?php echo esc_html__( 'Save Page Template', Thrive_Quiz_Builder::T ); ?></span>
<div class="margin-top-20">
	<?php echo esc_html__( 'You can save the current page as a template for use on another post / page on your site.', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tve-templates-wrapper">
	<div class="tvd-input-field margin-bottom-5 margin-top-25">
		<input type="text" id="tve-template-name" required>
		<label for="tve-template-name"><?php echo esc_html__( 'Template Name', Thrive_Quiz_Builder::T ); ?></label>
	</div>
</div>
<div class="tcb-modal-footer flex-end">
	<button type="button" class="tcb-right tve-button medium green click" data-fn="save">
		<?php echo esc_html__( 'Save Template', Thrive_Quiz_Builder::T ) ?>
	</button>
</div>
