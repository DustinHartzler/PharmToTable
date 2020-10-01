<?php /* small 32px sidebar */ ?>
<div id="sidebar-right">
	<div class="links">
		<a class="sidebar-item click" data-fn="blur" href="javascript:void(0)">
			<img alt="Thrive Architect" width="18" src="<?php echo apply_filters( 'architect.branding', tve_editor_css( 'images/admin-bar-logo.png' ), 'logo_src' ); ?>"/>
		</a>
		<?php if ( tcb_editor()->can_add_elements() ) : ?>
			<a href="javascript:void(0)" class="sidebar-item green add-element" data-position="left" data-tooltip="<?php echo __( 'Add Element', 'thrive-cb' ); ?>"
			   data-toggle="elements">
				<?php tcb_icon( 'plus-square-light' ); ?>
				<?php tcb_icon( 'plus-square-regular', false, 'sidebar', 'active' ); ?>
			</a>
		<?php endif; ?>
		<?php
		/* this is not connected yet
		<a href="javascript:void(0)" class="sidebar-item">
			<?php tcb_icon( 'book-heart-light' ); ?>
			<?php tcb_icon( 'book-heart-regular', false, 'sidebar', 'active' ); ?>
		</a> */
		?>
		<?php if ( tcb_editor()->has_central_style_panel() ) : ?>
			<a href="javascript:void(0)" data-position="left" class="sidebar-item" data-toggle="central_style_panel"
			   data-tooltip="<?php echo __( 'Central Style Panel', 'thrive-cb' ); ?>">
				<?php tcb_icon( 'central-style-panel' ); ?>
				<?php tcb_icon( 'central-style-panel', false, 'sidebar', 'active' ); ?>
			</a>
		<?php endif; ?>
		<?php if ( tcb_editor()->has_templates_tab() ) : ?>
			<a href="javascript:void(0)" data-position="left" class="sidebar-item click"
			   data-fn="open_templates_picker" data-tooltip="<?php echo tcb_editor()->get_templates_tab_title(); ?>">
				<?php tcb_icon( 'cloud-download-light' ); ?>
			</a>
		<?php endif; ?>
		<?php if ( tcb_editor()->has_settings_tab() ) : ?>
			<a href="javascript:void(0)" class="sidebar-item" data-toggle="settings" data-position="left" data-tooltip="<?php echo __( 'Settings', 'thrive-cb' ); ?>">
				<?php tcb_icon( 'cog-light' ); ?>
				<?php tcb_icon( 'cog-regular', false, 'sidebar', 'active' ); ?>
			</a>
		<?php endif; ?>
		<?php do_action( 'tcb_sidebar_extra_links' ); ?>
	</div>

	<div class="drawer" data-drawer="elements">
		<div class="header fill" id="el-search">
			<span class="text s-normal"><?php echo __( 'Add Element', 'thrive-cb' ); ?></span>
			<div class="s-links s-normal">
				<a href="javascript:void(0)" class="s-icon click search" data-fn="state" data-state="search"><?php tcb_icon( 'search-regular' ); ?></a>
				<a href="javascript:void(0)" class="s-icon click close" data-fn="hide_drawers"><?php tcb_icon( 'times-regular' ); ?></a>
			</div>
			<input autocomplete="off" type="text" name="s" placeholder="<?php echo __( 'Search elements...', 'thrive-cb' ); ?>" class="s-search q">
			<a href="javascript:void(0)" class="x-icon click search s-search" data-fn="state" data-state="normal">
				<?php tcb_icon( 'times-light' ); ?>
			</a>
		</div>
		<div id="tve-promoted-elements"><?php tcb_template( 'elements/-list-promoted' ); ?></div>
		<div id="tve-elements" class="scrollbar"><?php tcb_template( 'elements/-sidebar-list' ); ?></div>
	</div>
	<div class="drawer central_style_panel" data-drawer="central_style_panel">
		<div class="header fill" id="el-search">
			<span class="text s-normal"><?php echo __( 'Style Editor', 'thrive-cb' ); ?></span>
			<div class="s-links s-normal">
				<a href="javascript:void(0)" class="s-icon click close" data-fn="hide_drawers"><?php tcb_icon( 'times-regular' ); ?></a>
			</div>
		</div>
		<?php tcb_template( 'central-style-panel', tcb_editor()->get_template_styles_data() ); ?>
	</div>
	<div class="drawer hide-scroll settings" data-drawer="settings">
		<div class="header" id="settings-search">
			<a href="javascript:void(0)" class="s-normal back-link">
				<?php tcb_icon( 'arrow-left-solid', false, 'sidebar', 's-normal b-icon' ); ?>
				<span class="text s-normal"><?php echo __( 'Settings', 'thrive-cb' ); ?></span>
			</a>
			<div class="s-links s-normal">
				<a href="javascript:void(0)" class="s-icon click search" data-fn="state" data-state="search"><?php tcb_icon( 'search-regular' ); ?></a>
				<a href="javascript:void(0)" class="s-icon click close" data-fn="hide_drawers"><?php tcb_icon( 'times-regular' ); ?></a>
			</div>
			<input autocomplete="off" type="text" name="s" placeholder="<?php echo __( 'Search setting...', 'thrive-cb' ); ?>" class="s-search q">
			<a href="javascript:void(0)" class="x-icon click search s-search" data-fn="state" data-state="normal">
				<?php tcb_icon( 'times-light' ); ?>
			</a>
		</div>
		<div class="scroll-content"><?php tcb_template( 'settings' ); ?></div>
	</div>

	<div class="tve-custom-code-wrapper full-width" style="display: none">
		<textarea id="tve-custom-css-code"></textarea>
		<div class="tve-css-buttons-wrapper">
			<div class="code-apply"><?php tcb_icon( 'check' ); ?></div>
			<div class="code-close"><?php tcb_icon( 'close2' ); ?></div>
		</div>
	</div>
	<div class="tve-editor-html-wrapper full-width" style="display: none">
		<textarea id="tve-custom-html-code"></textarea>
		<div class="tve-code-buttons-wrapper">
			<div class="code-button-check"><?php tcb_icon( 'check' ); ?></div>
			<div class="code-button-close"><?php tcb_icon( 'close2' ); ?></div>
		</div>
	</div>

</div>
