<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>

<h2 class="tcb-modal-title"><?php echo __( 'Links missing', Thrive_Quiz_Builder::T ) ?></h2>
<div class="margin-top-20">
	<?php echo __( 'Your page has neither a link to the next step in the quiz nor a form connected to the email service, so your visitors will be blocked on this step.', Thrive_Quiz_Builder::T ) ?>
	<a class="tqb-open-external-link" href="<?php echo Thrive_Quiz_Builder::KB_NEXT_STEP_ARTICLE?>" target="_blank"><?php echo __( 'Learn how to add links to the next step in the quiz.', Thrive_Quiz_Builder::T ) ?></a>
</div>
<div class="tcb-modal-footer clearfix padding-top-20 row end-xs">
	<div class="col col-xs-12">
		<button type="button" class="tcb-right tve-button medium red click" data-fn="close">
			<?php echo __( 'Continue', Thrive_Quiz_Builder::T ) ?>
		</button>
	</div>
</div>
