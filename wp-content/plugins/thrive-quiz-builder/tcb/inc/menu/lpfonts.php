<div id="tve-lpfonts-component" class="tve-component default-visible">
	<div class="action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php echo esc_html__( 'Typography', 'thrive-cb' ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">
			<?php if ( tcb_editor()->is_landing_page() ) : ?>
				<div class="field-section p-texts center-text">
					<button class="style-input lp-typography-button tve-button click" data-fn="f:main.sidebar_extra.settings.landingPageFonts">
						<?php echo esc_html__( 'Edit landing page typography', 'thrive-cb' ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
