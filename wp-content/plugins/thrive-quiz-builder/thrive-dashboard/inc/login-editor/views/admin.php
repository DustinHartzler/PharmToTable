<?php include TVE_DASH_PATH . '/templates/header.phtml'; ?>

<div class="tvd-breadcrumb login-nav">
	<a href="<?php echo admin_url( 'admin.php?page=tve_dash_section' ); ?>" class="tvd-breadcrumb-back">
		<?php echo __( 'Thrive Dashboard', TVE_DASH_TRANSLATE_DOMAIN ); ?>
	</a>
	<span>
		<?php echo __( 'WordPress Login Screen Branding', TVE_DASH_TRANSLATE_DOMAIN ); ?>
	</span>
</div>

<div class="tvd-wrap">
	<div class="tvd-login-preview">
		<iframe scrolling="no" src="<?php echo $design_enabled ? $preview_url : $default_url; ?>" data-preview-url="<?php echo $preview_url ?>" data-default-url="<?php echo $default_url; ?>"></iframe>
	</div>
	<div class="tvd-actions">
		<h2>
			<?php echo __( 'Add your own brand to the default WordPress login screen', TVE_DASH_TRANSLATE_DOMAIN ); ?>
		</h2>
		<p>
			<?php echo __( 'Visually design the WordPress login screen.  This screen is used when users wish to login to the site, set a new password and similar flows.', TVE_DASH_TRANSLATE_DOMAIN ); ?>
		</p>
		<p>
			<?php echo __( 'Activating this feature will mean taking over the default WordPress design.', TVE_DASH_TRANSLATE_DOMAIN ); ?>
		</p>
		<div class="tvd-wrap space-between">
			<div class="tvd-switch">
				<label>
					<?php echo __( 'ACTIVE', TVE_DASH_TRANSLATE_DOMAIN ); ?>
					<input type="checkbox" class="tvd-toggle-input" <?php checked( true, $design_enabled ); ?>>
					<span class="tvd-lever"></span>
				</label>
			</div>
			<div class="tvd-wrap tvd-template-links" style="visibility: <?php echo $design_enabled ? 'visible' : 'hidden'; ?>;">
				<a target="_blank" href="<?php echo $preview_url; ?>">
					<span class="tvd-icon-eye"></span>
					<?php echo __( 'View', TVE_DASH_TRANSLATE_DOMAIN ); ?>
				</a>
				<span class="tvd-sep"></span>
				<a target="_blank" href="<?php echo $edit_url; ?>">
					<span class="tvd-icon-architect"></span>
					<?php echo __( 'Edit with Thrive Architect', TVE_DASH_TRANSLATE_DOMAIN ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
<div id="tvd-back-td" class="tvd-col tvd-m6">
	<a href="<?php echo admin_url( 'admin.php?page=tve_dash_section' ); ?>" class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-gray">
		<?php echo __( 'Back To Dashboard', TVE_DASH_TRANSLATE_DOMAIN ); ?>
	</a>
</div>

<script>
	( $ => {
		const $iframe = $( '.tvd-login-preview iframe' ),
			$links = $( '.tvd-template-links' );

		$( '.tvd-toggle-input' ).on( 'click', ( event ) => {
			const enabled = event.currentTarget.checked ? 1 : 0

			$links.css( 'visibility', enabled ? 'visible' : 'hidden' );

			$.ajax( {
				url: ajaxurl,
				dataType: 'json',
				type: 'POST',
				data: {
					_wpnonce: TVE_Dash_Const.nonce,
					action: TVE_Dash_Const.actions.backend_ajax,
					route: TVE_Dash_Const.routes.settings,
					field: 'tvd_enable_login_design',
					value: enabled
				}
			} ).always( response => {
				TVE_Dash.success( 'Setting saved successfully!' );

				$iframe.attr( 'src', $iframe.attr( 'data-' + ( enabled ? 'preview' : 'default' ) + '-url' ) );
			} );
		} );
	} )( jQuery )
</script>
