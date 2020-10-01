<div class="tve-post-option-confirm tve-post-option-confirm-private tcb-hide">
	<div class="clearfix pb-10 tcb-post-options-modal-content">
		<?php if ( get_post_type() === 'post' ) : ?>
			<h2><?php echo __( 'Are you sure you want to privately publish this post?', 'thrive-cb' ) ?></h2>
		<?php else : ?>
			<h2><?php echo __( 'Are you sure you want to privately publish this page?', 'thrive-cb' ) ?></h2>
		<?php endif; ?>
	</div>

	<div class="tcb-modal-footer pt-20 control-grid">
		<button type="button" class="tcb-left tve-button text-only tve-cancel-change tcb-modal-cancel click" data-fn="cancelChanges">
			<?php echo __( 'Cancel', 'thrive-cb' ) ?>
		</button>
		<button type="button" class="tcb-right tve-button medium tcb-modal-save click" data-post-status="private" data-fn="setPostStatus">
			<?php echo __( 'Yes, privately publish', 'thrive-cb' ) ?>
		</button>
	</div>
</div>

<div class="tve-post-option-confirm tve-post-option-confirm-unpublish tcb-hide">
	<div class="clearfix pb-10 tcb-post-options-modal-content">
		<?php if ( get_post_type() === 'post' ) : ?>
			<h2><?php echo __( 'Are you sure you want to unpublish this post?', 'thrive-cb' ) ?></h2>
		<?php else : ?>
			<h2><?php echo __( 'Are you sure you want to unpublish this page?', 'thrive-cb' ) ?></h2>
		<?php endif; ?>
	</div>

	<div class="tcb-modal-footer pt-20 control-grid">
		<button type="button" class="tcb-left tve-button text-only tve-cancel-change tcb-modal-cancel click" data-fn="cancelChanges">
			<?php echo __( 'Cancel', 'thrive-cb' ) ?>
		</button>
		<button type="button" class="tcb-right tve-button medium tcb-modal-save click" data-post-status="draft" data-fn="setPostStatus">
			<?php echo __( 'Yes, unpublish', 'thrive-cb' ) ?>
		</button>
	</div>
</div>

<div class="tve-post-option-confirm tve-post-option-confirm-publish tcb-hide">
	<div class="clearfix pb-10 tcb-post-options-modal-content">
		<?php if ( get_post_type() === 'post' ) : ?>
			<h2><?php echo __( 'Are you sure you want to publish this post?', 'thrive-cb' ) ?></h2>
		<?php else : ?>
			<h2><?php echo __( 'Are you sure you want to publish this page?', 'thrive-cb' ) ?></h2>
		<?php endif; ?>
	</div>

	<div class="tcb-modal-footer pt-20 control-grid">
		<button type="button" class="tcb-left tve-button text-only tve-cancel-change tcb-modal-cancel click" data-fn="cancelChanges">
			<?php echo __( 'Cancel', 'thrive-cb' ) ?>
		</button>
		<button type="button" class="tcb-right tve-button medium tcb-modal-save click" data-post-status="publish" data-fn="setPostStatus">
			<?php echo __( 'Yes, publish', 'thrive-cb' ) ?>
		</button>
	</div>
</div>