<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

$variation       = tqb_get_variation( $_REQUEST[ Thrive_Quiz_Builder::VARIATION_QUERY_KEY_NAME ] );
$absolute_limits = tqb_compute_quiz_absolute_max_min_values( $variation['quiz_id'], true );
?>

<?php if ( false === $absolute_limits['min'] && false === $absolute_limits['max'] ) : // No questions defined ?>
	<?php $quiz_post = get_post( $variation['quiz_id'] ); ?>
	<h2 class="tcb-modal-title"><?php echo __( 'You have no questions defined', Thrive_Quiz_Builder::T ); ?></h2>
	<hr class="margin-top-0">
	<div class="margin-top-0">
		<?php echo __( 'There are no questions defined for this quiz so the dynamic content cannot be added!', Thrive_Quiz_Builder::T ); ?>
	</div>
	<div class="row margin-top-20">
		<div class="col-xs-12 tcb-text-center">
			<button type="button" class="green medium tve-button click" data-fn="redirect" data-href="<?php echo tge()->editor_url( $quiz_post ); ?>"><?php echo __( 'Add Questions', Thrive_Quiz_Builder::T ) ?></button>
		</div>
	</div>
<?php elseif ( is_numeric( $absolute_limits['min'] ) && $absolute_limits['min'] == $absolute_limits['max'] ) : // question min = question max; no branches in question editor. ?>
	<?php $quiz_post = get_post( $variation['quiz_id'] ); ?>
	<h2 class="tcb-modal-title"><?php echo __( 'There seems to be a problem with your quiz', Thrive_Quiz_Builder::T ); ?></h2>
	<hr class="margin-top-0">
	<div class="margin-top-0">
		<?php echo __( 'The minimum and maximum result cannot be the same! First you need to add points to the quiz answers and then add the dynamic content in your page.', Thrive_Quiz_Builder::T ); ?>
	</div>
	<div class="row margin-top-20">
		<div class="col-xs-12 tcb-text-center">
			<button type="button" class="green medium tve-button click" data-fn="redirect" data-href="<?php echo tge()->editor_url( $quiz_post ); ?>"><?php echo __( 'Edit Questions', Thrive_Quiz_Builder::T ) ?></button>
		</div>
	</div>
<?php else : ?>
	<?php
	$max = Thrive_Quiz_Builder::STATES_MAXIMUM_NUMBER_OF_INTERVALS;
	$aux = $absolute_limits['max'] - $absolute_limits['min'];
	if ( $aux < $max ) {
		$max = $aux + 1;
	}
	?>
	<h2 class="tcb-modal-title"><?php echo __( 'Dynamic content intervals', Thrive_Quiz_Builder::T ); ?></h2>
	<hr class="margin-top-0">
	<div class="margin-top-0">
		<?php echo sprintf( __( 'Before adding your Dynamic Content, please choose how to split your results into intervals.<br> You can create a maximum of %s intervals', Thrive_Quiz_Builder::T ), $max ); ?>
	</div>
	<div class="row margin-top-20">
		<div class="col-xs-6">
			<span class="tqb-import-content-description tqb-action-required-text"><?php echo __( 'Split available results range into: ', Thrive_Quiz_Builder::T ); ?></span>
		</div>
		<div class="col-xs-2">
			<input id="tqb_result_intervals" maxlength="2" class="tve-input change input" type="number" min="1" max="<?php echo $max; ?>" step="1" data-fn-change="preview_states" data-fn-input="preview_states">
		</div>
		<div class="col-xs-4">
			<span class="tqb-import-content-description tqb-action-required-text"><?php echo __( 'equal intervals', Thrive_Quiz_Builder::T ); ?></span>
		</div>
	</div>
	<div id="tqb-intervals-preview" class="row margin-top-20"></div>
	<div class="row margin-top-20">
		<div class="col-xs-12 tcb-text-center">
			<button type="button" class="green medium tve-button click" data-fn="save_states_number"><?php echo __( 'Create new dynamic content intervals', Thrive_Quiz_Builder::T ) ?></button>
		</div>
	</div>
	<?php if ( tqb_has_similar_dynamic_content( $variation ) ) : ?>
		<?php

		switch ( $variation['post_type'] ) {
			case Thrive_Quiz_Builder::QUIZ_STRUCTURE_ITEM_RESULTS:
				$searched_post_type = Thrive_Quiz_Builder::QUIZ_STRUCTURE_ITEM_OPTIN;
				break;
			case Thrive_Quiz_Builder::QUIZ_STRUCTURE_ITEM_OPTIN:
				$searched_post_type = Thrive_Quiz_Builder::QUIZ_STRUCTURE_ITEM_RESULTS;
				break;
			default:
				$searched_post_type = '';
				break;
		}
		$page_to_import_from = tqb()->get_style_page_name( $searched_post_type );

		?>
		<div class="row tqb-line-with-or"></div>
		<div class="row">
			<div class="col-xs-12 tcb-text-center">
				<?php echo sprintf( __( 'You can choose to import intervals from the %s.', Thrive_Quiz_Builder::T ), $page_to_import_from ); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 tcb-text-center">
				<?php echo __( 'If you choose this, only the intervals ranges will be imported.', Thrive_Quiz_Builder::T ); ?>
			</div>
		</div>
		<div class="row margin-top-20 tcb-text-center">
			<div class="col-xs-12 tcb-text-center">
				<button type="button" class="blue medium tve-button click" data-fn="copy_states_from_prev_page"><?php tcb_icon( 'transfer' ); ?>&nbsp;<?php echo sprintf( __( 'Import intervals from %s', Thrive_Quiz_Builder::T ), $page_to_import_from ); ?></button>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
