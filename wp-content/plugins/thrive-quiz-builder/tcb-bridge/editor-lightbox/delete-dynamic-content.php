<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<h2 class="tcb-modal-title"><?php echo __( 'Confirmation', Thrive_Quiz_Builder::T ); ?></h2>
<div class="margin-top-20">
	<?php echo __( 'By deleting the Dynamic Content Element from the page you\'ll loose all the settings you\'ve made to the intervals.', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tcb-modal-footer clearfix padding-top-20 row end-xs">
	<div class="col col-xs-12">
		<button type="button" class="tcb-right tve-button medium red click" data-fn="delete_all_dynamic_content">
			<?php echo __( 'Delete Dynamic Content', Thrive_Quiz_Builder::T ) ?>
		</button>
	</div>
</div>
