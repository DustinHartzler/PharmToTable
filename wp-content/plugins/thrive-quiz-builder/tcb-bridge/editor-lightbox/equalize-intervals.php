<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<h2 class="tcb-modal-title"><?php echo __( 'Confirmation', Thrive_Quiz_Builder::T ); ?></h2>
<div class="margin-top-20">
	<?php echo __( 'Are you sure you want to equalize all intervals?', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tcb-modal-footer clearfix padding-top-20 row end-xs">
	<div class="col col-xs-12">
		<button type="button" class="tcb-right tve-button medium red click" data-fn="equalize_intervals">
			<?php echo __( 'Equalize Sizes', Thrive_Quiz_Builder::T ) ?>
		</button>
	</div>
</div>
