<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<span class="tcb-modal-title mt-0 ml-0"><?php echo esc_html__( 'Confirmation', Thrive_Quiz_Builder::T ); ?></span>
<div class="margin-top-20">
	<?php echo esc_html__( 'Are you sure you want to equalize all intervals?', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tcb-modal-footer flex-end pr-0">
	<button type="button" class="tcb-right tve-button medium white-text red click" data-fn="equalize_intervals">
		<?php echo esc_html__( 'Equalize Sizes', Thrive_Quiz_Builder::T ) ?>
	</button>
</div>
