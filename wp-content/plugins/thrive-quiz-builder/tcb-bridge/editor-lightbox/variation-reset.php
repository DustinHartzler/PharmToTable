<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<h2 class="tcb-modal-title"><?php echo __( 'Reset to default content', Thrive_Quiz_Builder::T ) ?></h2>
<div class="margin-top-20">
	<?php echo __( 'Are you sure you want to reset this variation to the default template? This action cannot be undone.', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tcb-modal-footer clearfix padding-top-20 row end-xs">
	<div class="col col-xs-12">
		<button type="button" class="tcb-right tve-button medium red click" data-fn="reset">
			<?php echo __( 'Reset to default content', Thrive_Quiz_Builder::T ) ?>
		</button>
	</div>
</div>
