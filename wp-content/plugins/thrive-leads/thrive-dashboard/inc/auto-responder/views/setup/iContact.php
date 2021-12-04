<h2 class="tvd-card-title"><?php echo esc_html( $this->getTitle() ); ?></h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->getKey() ); ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-ic-api-username" type="text" name="connection[apiUsername]"
					value="<?php echo esc_attr( $this->param( 'apiUsername' ) ); ?>">
			<label for="tvd-ic-api-username"><?php echo esc_html__( "iContact username", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-ic-api-appid" type="text" name="connection[appId]"
					value="<?php echo esc_attr( $this->param( 'appId' ) ); ?>">
			<label for="tvd-ic-api-appid"><?php echo esc_html__( "Application ID", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-ic-api-password" type="text" name="connection[apiPassword]"
					value="<?php echo esc_attr( $this->param( 'apiPassword' ) ); ?>">
			<label for="tvd-ic-api-password"><?php echo esc_html__( "Application password", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
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
