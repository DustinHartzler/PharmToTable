<?php
/**
 * FileName  ct_symbol.php.
 * @project: thrive-visual-editor
 * @developer: Dragos Petcu
 * @company: BitStone
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>
<div class="thrv_wrapper thrv_ct_symbol tcb-elem-placeholder">
	<span class="tcb-inline-placeholder-action with-icon">
		<?php tcb_icon( 'add', false, 'editor' ); ?>
		<?php echo __( 'Insert Content Template or Symbol', 'thrive-cb' ); ?>
	</span>
</div>
