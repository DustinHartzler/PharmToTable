<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<span class="tcb-modal-title"><?php echo esc_html__( 'Confirmation', Thrive_Quiz_Builder::T ); ?></span>
<div class="margin-top-20">
	<?php echo esc_html__( 'Are you sure you want to delete this state?', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tcb-modal-footer flex-end">
	<button type="button" class="tcb-right tve-button medium red click" data-fn="remove_interval">
		<?php echo esc_html__( 'Remove', Thrive_Quiz_Builder::T ) ?>
	</button>
</div>
