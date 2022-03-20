<span class="summary"><?php echo sprintf( esc_html__( 'Select or add an element on the %s canvas in order to activate this sidebar.', 'thrive-cb' ), '<br>' ); ?></span>
<?php if ( tcb_editor()->has_templates_tab() ) : ?>
	<img src="<?php echo esc_url( tve_editor_css( 'images/sidebar-blank-tpl.png' ) ); ?>" width="207" height="328"
		 srcset="<?php echo esc_url( tve_editor_css( 'images/sidebar-blank-tpl@2x.png' ) ); ?> 2x">
<?php else : ?>
	<img src="<?php echo esc_url( tve_editor_css( 'images/sidebar-blank.png' ) ); ?>" width="193" height="326"
		 srcset="<?php echo esc_url( tve_editor_css( 'images/sidebar-blank@2x.png' ) ); ?> 2x">
<?php endif; ?>
