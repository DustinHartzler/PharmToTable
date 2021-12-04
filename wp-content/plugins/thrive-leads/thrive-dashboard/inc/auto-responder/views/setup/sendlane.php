<h2 class="tvd-card-title"><?php echo esc_html( $this->getTitle() ); ?></h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->getKey() ); ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-sl-api-url" type="text" name="connection[api_url]"
				   value="<?php echo esc_attr( $this->param( 'api_url' ) ); ?>">
			<label for="tvd-sl-api-url"><?php echo esc_html__( 'API URL', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-sl-api-key" type="text" name="connection[api_key]"
				   value="<?php echo esc_attr( $this->param( 'api_key' ) ) ?>">
			<label for="tvd-sl-api-key"><?php echo esc_html__( 'API key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-sl-hash-key" type="text" name="connection[hash_key]"
				   value="<?php echo esc_attr( $this->param( 'api_key' ) ) ?>">
			<label for="tvd-sl-hash-key"><?php echo esc_html__( 'HASH key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
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
			<a class="tvd-api-connect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo esc_html__( 'Connect', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
	</div>
</div>
