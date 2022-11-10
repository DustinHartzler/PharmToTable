<h2 class="tvd-card-title">
	<?php echo esc_html( $this->get_title() ); ?>
</h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->get_key() ); ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-rc-api-app-id" type="text" name="connection[app_id]"
				   value="<?php echo esc_attr( $this->param( 'app_id' ) ); ?>">
			<label for="tvd-rc-api-app-id"><?php echo esc_html__( 'App ID', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-ac-api-app-secret" type="text" name="connection[app_secret]"
				   value="<?php echo esc_attr( $this->param( 'app_secret' ) ); ?>">
			<label for="tvd-ac-api-app-secret"><?php echo esc_html__( 'App Secret', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<?php $this->display_video_link(); ?>
	</form>
</div>
<div class="tvd-card-action">
	<div class="tvd-row tvd-no-margin">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo esc_html__( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-redirect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo esc_html__( 'Connect', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
	</div>
</div>
