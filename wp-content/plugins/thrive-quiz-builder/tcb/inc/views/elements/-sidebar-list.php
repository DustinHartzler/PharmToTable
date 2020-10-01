<?php foreach ( tcb_editor()->elements->get_for_front() as $category => $elements ) : ?>
	<div class="tve-category expanded"<?php echo empty( $elements ) ? ' style="display: none;"' : ''; ?>
	     data-category="<?php echo $category; ?>">
		<a class="c-header" href="javascript:void(0)" onclick="this.parentNode.classList.toggle('expanded')">
			<?php tcb_icon( 'caret-right-solid' ) ?>
			<?php echo $category; ?>
		</a>
		<div class="c-items">
			<?php foreach ( $elements as $element ) : ?>
				<div class="tve-element" data-elem="<?php echo $element->tag() ?>" data-alternate="<?php echo $element->alternate() ?>"
				     draggable="true">
					<button class="tve-element-pin<?php echo $element->pinned ? ' pinned' : ''; ?>"
					        data-cat="<?php echo $element->category() ?>"></button>
					<div class="item">
						<span class="tve-e-icon">
							<?php tcb_icon( $element->icon() ); ?>
						</span>
						<span class="tve-e-name">
							<?php echo $element->name(); ?>
						</span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endforeach; ?>
<?php do_action( 'tcb_sidebar_elements_notice' ); ?>
