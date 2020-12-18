<?php
/*
 * settings drawer (right small sidebar)
 */
$has_zip_archive = class_exists( 'ZipArchive', false );
?>
<div id="settings" class="tcb-relative">
	<div class="state-default state">
		<span class="label tcb-hide"><?php echo __( 'Settings', 'thrive-cb' ); ?></span>
		<div class="list">
			<?php if ( tcb_editor()->is_landing_page() ) : ?>
				<a href="#" class="nav s-setting" data-nav="global"><span class="s-name"><?php echo __( 'Global', 'thrive-cb' ); ?></span><?php tcb_icon( 'long-arrow-right-light' ); ?></a>
				<a href="#" class="click s-setting"
				   data-fn="lp_settings"><span class="s-name"><?php echo __( 'Landing Page Settings', 'thrive-cb' ); ?></span><?php tcb_icon( 'long-arrow-right-light' ); ?></a>
			<?php elseif ( tcb_editor()->is_lightbox() ) : ?>
				<a href="#" class="click s-setting"
				   data-fn="select_element"
				   data-el=".tve_p_lb_content"><span class="s-name"><?php echo __( 'Thrive Lightbox Settings', 'thrive-cb' ); ?></span><?php tcb_icon( 'cog-light' ); ?></a>
			<?php endif ?>
			<a href="#" class="nav s-setting" data-nav="advanced"><span class="s-name"><?php echo __( 'Advanced Settings', 'thrive-cb' ); ?></span><?php tcb_icon( 'long-arrow-right-light' ); ?></a>
			<span class="sep"></span>
			<?php if ( tcb_editor()->is_landing_page() ) : ?>
				<a href="#" class="click s-setting" data-fn="save_template_lp"><span class="s-name"><?php echo __( 'Save Landing Page', 'thrive-cb' ); ?></span></a>
				<a href="#" class="<?php echo $has_zip_archive ? 'click' : 'disabled-children'; ?> s-setting" data-fn="export_lp" data-position="top" data-tooltip="<?php esc_attr_e( $has_zip_archive ? '' : __( 'The PHP ZipArchive extension must be enabled in order to use this functionality. Please contact your hosting provider.', 'thrive-cb' ) ); ?>"><span class="s-name"><?php echo __( 'Export Landing Page', 'thrive-cb' ); ?></span></a>
				<span class="sep"></span>
			<?php endif; ?>
			<?php if ( tcb_editor()->can_use_landing_pages() ) : ?>
				<a href="#" class="<?php echo $has_zip_archive ? 'click' : 'disabled-children'; ?> s-setting" data-fn="import_lp" data-position="top" data-tooltip="<?php esc_attr_e( $has_zip_archive ? '' : __( 'The PHP ZipArchive extension must be enabled in order to use this functionality. Please contact your hosting provider.', 'thrive-cb' ) ); ?>"><span class="s-name"><?php echo __( 'Import Landing Page', 'thrive-cb' ); ?></span></a>
			<?php endif; ?>
			<?php if ( tcb_editor()->has_save_template_button() ) : ?>
				<a href="#" class="click s-setting" data-fn="save_template"><span class="s-name"><?php echo __( 'Save as Template', 'thrive-cb' ); ?></span></a>
			<?php endif; ?>
			<?php
			/**
			 * Action hook. Allows injecting custom menu options under the "Templates Setup" tab
			 */
			do_action( 'tcb_settings_links' );
			?>
		</div>
	</div>

	<div class="state-advanced state">
		<span class="label tcb-hide"><?php echo __( 'Advanced Settings', 'thrive-cb' ); ?></span>
		<div class="list">
			<a href="#" class="click s-setting" data-fn="html"><span class="s-name"><?php echo __( 'View Page Source (HTML)', 'thrive-cb' ); ?></span><?php tcb_icon( 'code-regular' ); ?></a>
			<a href="#" class="click s-setting" data-fn="css"><span class="s-name"><?php echo __( 'Custom CSS', 'thrive-cb' ); ?></span><?php tcb_icon( 'css3-brands' ); ?></a>
			<?php if ( tcb_editor()->can_use_page_events() ) : ?>
				<a href="#" class="click s-setting" data-fn="page_events"><span class="s-name"><?php echo __( 'Page Events', 'thrive-cb' ); ?></span></a>
			<?php endif ?>
			<?php if ( tcb_editor()->is_landing_page() ) : ?>
				<a href="#" data-nav="custom-scripts" class="s-setting"><span class="s-name"><?php echo __( 'Custom Scripts', 'thrive-cb' ); ?></span><?php tcb_icon( 'long-arrow-right-light' ); ?></a>
				<a href="#" data-nav="head-css" class="s-setting"><span class="s-name"><?php echo esc_html__( 'CSS in the <head> section', 'thrive-cb' ); ?></span><?php tcb_icon( 'long-arrow-right-light' ); ?></a>
			<?php endif ?>
			<a href="#" class="click s-setting" data-fn="reminders">
				<?php $label = (int) get_option( 'tve_display_save_notification', 1 ) ? __( 'Turn Off Save Reminders', 'thrive-cb' ) : __( 'Turn On Save Reminders', 'thrive-cb' ); ?>
				<span class="s-name"><?php echo $label; ?></span><?php tcb_icon( 'bell-slash-light' ); ?>
			</a>
		</div>
	</div>

	<?php if ( tcb_editor()->is_landing_page() ) : ?>

		<?php tcb_template( 'custom-scripts' ); ?>

		<div class="state-lp-settings state">
			<span class="label tcb-hide"><?php echo __( 'Landing Page Settings', 'thrive-cb' ); ?></span>
			<div class="list">
				<a href="#" class="click s-setting" data-fn="toggle_theme_css" data-do="disable">
					<span class="s-name"><?php echo __( 'Disable Theme CSS', 'thrive-cb' ); ?></span>
					<?php echo tcb_icon( 'toggle-off-regular' ); ?>
				</a>
				<a href="#" class="click s-setting" data-fn="toggle_theme_css" data-do="enable">
					<span class="s-name"><?php echo __( 'Enable Theme CSS', 'thrive-cb' ); ?></span>
					<?php echo tcb_icon( 'toggle-on-regular' ); ?>
				</a>
				<a href="#" class="click s-setting" data-fn="lp_revert">
					<span class="s-name"><?php echo __( 'Revert to Theme', 'thrive-cb' ); ?></span><?php tcb_icon( 'undo-regular' ); ?>
				</a>
			</div>
		</div>

		<div class="state-head-css state">
			<span class="label tcb-hide"><?php echo esc_html__( 'CSS in <head>', 'thrive-cb' ); ?></span>
			<section>
				<div class="field-section checkbox"></div>
				<div class="field-section">
					Thrive Architect will strip out any Custom CSS from the
					&lt;head&gt; section from all Landing Pages built with it.
					Usually, this is extra CSS that is not needed throughout the Lading Page.
					By ticking the checkbox above, you will disable this functionality, and all Custom CSS will be included.
					Please keep in mind that including this Custom CSS might prevent some of the Landing Page settings to function
					properly, such as: background color, background image etc.
				</div>
			</section>
		</div>

		<div class="state-global state">
			<span class="label tcb-hide"><?php echo __( 'Global settings', 'thrive-cb' ); ?></span>
			<section>
				<div class="field-section s-setting" id="p-texts">
					<label class="s-name"><?php echo __( 'Fonts', 'thrive-cb' ); ?></label>
					<a href="javascript:void(0)" class="style-input dots click" data-fn="landing_page_fonts">
						<span class="value tcb-truncate"
							  data-default="<?php echo __( '[inherit]', 'thrive-cb' ); ?>"><?php echo __( '[inherit]', 'thrive-cb' ); ?></span>
						<?php tcb_icon( 'pen-regular' ); ?>
					</a>
				</div>
				<div class="field-section s-setting" id="p-header">
					<label class="s-name"><?php echo __( 'Header', 'thrive-cb' ); ?></label>
					<a href="javascript:void(0)" class="style-input dots click" data-fn="add_section" data-type="header">
						<span class="value tcb-truncate"
							  data-default="<?php echo __( '[no header added]', 'thrive-cb' ); ?>"><?php echo __( '[no header added]', 'thrive-cb' ); ?></span>
						<?php tcb_icon( 'pen-regular' ); ?>
					</a>
				</div>
				<div class="field-section s-setting" id="p-footer">
					<label class="s-name"><?php echo __( 'Footer', 'thrive-cb' ); ?></label>
					<a href="javascript:void(0)" class="style-input dots click" data-fn="add_section" data-type="footer">
						<span class="value tcb-truncate"
							  data-default="<?php echo __( '[no footer added]', 'thrive-cb' ); ?>"><?php echo __( '[no footer added]', 'thrive-cb' ); ?></span>
						<?php tcb_icon( 'pen-regular' ); ?>
					</a>
				</div>
			</section>
		</div>
	<?php endif; ?>
</div>
