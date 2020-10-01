<?php
/**
 * FileName  default.php.
 *
 * @project  : thrive-visual-editor
 * @developer: Dragos Petcu
 * @company  : BitStone
 */
?>
<?php $symbol_id = get_the_ID(); ?>
<?php $symbol_title = get_the_title(); ?>
<?php $content = TCB_Symbol_Template::render_content( array(), true ); ?>
<?php $symbol_vars = TCB_Symbol_Template::get_edit_symbol_vars(); ?>
<?php $type = ucfirst( $symbol_vars['type'] ); ?>
<?php $data_attr = TCB_Symbol_Template::data_attr( $symbol_id ); ?>
<?php
$shortcode_class = '';
if ( $symbol_vars['css_class'] === 'thrv_header' ) {
	$symbol_vars['css_class'] .= ' tve-default-state';
	$shortcode_class          = 'tve-default-state';
}
?>

<?php TCB_Symbol_Template::body_open(); ?>
<div class="tve-leads-conversion-object">
	<div id="tve-leads-editor-replace">
		<div class="tve-symbol-container">
			<div class="tve_flt" id="tve_flt">
				<?php if ( isset( $_GET['tve'] ) ) { ?>
					<div class="symbol-extra-info">
						<p class="sym-l"><?php echo __( "Currently Editing {$type} \"{$symbol_title}\"" ); ?></p>
						<p class="sym-r"><?php echo __( "Note that this {$symbol_vars['type']} doesn't have any width settings. <br />It will expand to the full width of the content area of your theme." ); ?></p>
					</div>
				<?php } ?>
				<div id="tve_editor">
					<div class="tve_editable thrv_symbol <?php echo $symbol_vars['css_class'] ?> thrv_symbol_<?php echo $symbol_id ?>" data-id="<?php echo $symbol_id ?>">
						<div class="thrive-symbol-shortcode <?php echo $shortcode_class ?>"<?php echo $data_attr; ?>>
							<?php if ( empty( $content ) ) { ?>
								<div class="symbol-section-out"></div>
								<div class="symbol-section-in"></div>
							<?php } else { ?>
								<?php echo $content; ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php TCB_Symbol_Template::body_close(); ?>
