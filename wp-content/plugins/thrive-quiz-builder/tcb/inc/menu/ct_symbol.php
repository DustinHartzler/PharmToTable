<?php
/**
 * FileName  symbol.php.
 * @project: thrive-visual-editor
 * @developer: Dragos Petcu
 * @company: BitStone
 */
?>
<div id="tve-ct-symbol-component" class="tve-component" data-view="CtSymbol">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="pb-10 tcb-text-center grey-text">
			<?php echo __('This is a symbol. You can edit it as a global element( it updates simultaneously all over the places you used it) or unlink it and you edit it as a regular element', 'thrive-tcb'); ?>
		</div>
		<hr>
		<div class="row pb-10">
			<div class="col-xs-6">
				<button class="tve-button grey long click" data-fn="edit_symbol">
					<?php echo __( 'Edit as Symbol', 'thrive-cb' ) ?>
				</button>
			</div>

			<div class="col-xs-6">
				<button class="tve-button blue long click" data-fn="unlink_symbol">
					<?php echo __( 'Unlink', 'thrive-cb' ) ?>
				</button>
			</div>
		</div>
	</div>
</div>