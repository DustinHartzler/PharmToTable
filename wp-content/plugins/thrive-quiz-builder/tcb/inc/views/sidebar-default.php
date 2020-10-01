<span class="summary"><?php echo __( 'Select or add an element on the<br>canvas in order to activate this sidebar.', 'thrive-cb' ); ?></span>
<?php if ( tcb_editor()->has_templates_tab() ) : ?>
	<img src="<?php echo tve_editor_css( 'images/sidebar-blank-tpl.png' ); ?>" width="207" height="328"
		 srcset="<?php echo tve_editor_css( 'images/sidebar-blank-tpl@2x.png' ); ?> 2x">
<?php else : ?>
	<img src="<?php echo tve_editor_css( 'images/sidebar-blank.png' ); ?>" width="193" height="326"
		 srcset="<?php echo tve_editor_css( 'images/sidebar-blank@2x.png' ); ?> 2x">
<?php endif; ?>
