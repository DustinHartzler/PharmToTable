<h2 class="tvd-card-title"><?php echo esc_html( $this->getTitle() ); ?></h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<div class="tvd-input-field">
			<input id="tvd-op-api-id" type="text" name="connection[app_id]"
				   value="<?php echo esc_attr( $this->param( 'app_id' ) ); ?>">
			<label for="tvd-op-api-id"><?php echo esc_html__( "Application ID", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-op-api-key" type="text" name="connection[key]"
				   value="<?php echo esc_attr( $this->param( 'key' ) ); ?>">
			<label for="tvd-op-api-key"><?php echo esc_html__( "API key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->getKey() ); ?>"/>
		<?php $this->display_video_link(); ?>
	</form>
</div>
<div class="tvd-card-action">
	<div class="tvd-row tvd-no-margin">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo esc_html__( "Cancel", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-connect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo esc_html__( "Connect", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
	</div>
</div>
