<h2 class="tvd-card-title"><?php echo esc_html( $this->get_title() ); ?></h2>
<div class="tvd-row">
	<div class="tvd-col tvd-s12">
		<p>
			<strong><?php echo esc_html__( 'Notification:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong><?php echo esc_html__( 'Mail Relay limits the number of emails sent per minute to 6. All emails sent after this limit will be discarded', TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</p>
		<p>
			<strong><?php echo esc_html__( 'Note:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong><?php echo esc_html__( 'Connecting to Mail Relay Email Service will also connect to Mail Relay autoresponders.', TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</p>
	</div>
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->get_key() ); ?>"/>

		<div class="tvd-input-field">
			<input id="tvd-mm-api-key" type="text" name="connection[domain]"
			       value="<?php echo esc_attr( $this->param( 'domain' ) ); ?>">
			<label for="tvd-mm-api-key"><?php echo esc_html__( 'API URL', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-mm-api-key" type="text" name="connection[key]"
			       value="<?php echo esc_attr( $this->param( 'key' ) ); ?>">
			<label for="tvd-mm-api-key"><?php echo esc_html__( 'API key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
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

