<?php if ( empty( $questions ) ) {
	return;
} ?>
<div class="tqb-question-wrapper tve_no_icons">
	<?php foreach ( $questions as $question ) : ?>
		<div class="tqb-question-container">

			<?php echo $media; ?>

			<div class="tqb-question-text tve_no_icons">
				<?php echo $question['text']; ?>
			</div>

			<?php if ( $question['description'] ) : ?>
				<div class="tqb-question-description tve_no_icons">
					<?php echo $question['description']; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! $media && $question['image'] ) : ?>
				<div class="tqb-question-image-container">
					<img src="<?php echo isset( $question['image']->sizes->large ) ? $question['image']->sizes->large->url : $question['image']->sizes->full->url; ?>" alt="question-image">
				</div>
			<?php endif; ?>

		</div>
		<div class="tqb-answers-container tve_no_icons <?php if ( $question['answers'][0]['image'] ) : ?> tqb-answer-has-image <?php endif; ?>">
			<?php if ( ! empty( $question['answers'] ) && is_array( $question['answers'] ) ) : ?>
				<?php foreach ( $question['answers'] as $answer ) : ?>
					<div class="tqb-answer-inner-wrapper">
						<div class="tqb-answer-action">
							<?php if ( $answer['image'] ) : ?>
								<div class="tqb-answer-image-type">
									<div class="tqb-answer-image-container">
										<img src="<?php echo $answer['image']->sizes->thumbnail->url; ?>" alt="" class="tqb-answer-image">
									</div>
									<div class="tqb-answer-text-container">
										<div class="tqb-answer-text">
											<?php echo $answer['text']; ?>
										</div>
									</div>
								</div>
							<?php elseif ( $answer['text'] ) : ?>
								<div class="tqb-answer-text-type">
									<div class="tqb-answer-text">
										<?php echo $answer['text']; ?>
										<span class="tqb-fancy-icon">
											<?php tqb_get_svg_icon( 'check' ); ?>
											<?php tqb_get_svg_icon( 'times' ); ?>
										</span>
									</div>
								</div>
							<?php else : ?>
								<div class="tqb-answer-oeq-type">
									<div class="tqb-answer-oeq">
										<?php echo __( 'Write your response here', Thrive_Quiz_Builder::T ); ?>
									</div>
								</div>
							<?php endif; ?>

						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<?php break; ?>
	<?php endforeach; ?>
</div>
