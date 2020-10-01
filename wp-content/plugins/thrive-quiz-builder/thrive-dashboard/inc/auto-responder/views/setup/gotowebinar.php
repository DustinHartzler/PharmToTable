<?php $version = (int) $this->get_version(); ?>
<h2 class="tvd-card-title"><?php echo 2 === (int) $version ? $this->getTitle() . ' ' . $version . '.0' : $this->getTitle(); ?></h2>
<div class="tvd-row">
	<?php
	/** @var $this Thrive_Dash_List_Connection_GoToWebinar */
	?>
	<?php if ( $this->isConnected() && $this->expiresIn() > 30 && 2 !== $version ) : ?>
		<p class="tvd-card-spacer tvd-card-margin"><?php echo __( 'GoToWebinar is connected. The access token expires on:', TVE_DASH_TRANSLATE_DOMAIN ); ?>
			<strong><?php echo $this->getExpiryDate(); ?></strong></p>
	<?php elseif ( $this->isExpired() && 2 !== $version ) : ?>
		<p class="tvd-card-spacer  tvd-card-margin">
			<?php echo __( 'The GoToWebinar access token has expired on:', TVE_DASH_TRANSLATE_DOMAIN ); ?>
			<strong><?php echo $this->getExpiryDate(); ?></strong>. <?php echo __( 'You need to renew the token by providing your GoToWebinar credentials below', TVE_DASH_TRANSLATE_DOMAIN ); ?>
		</p>
	<?php elseif ( $this->isConnected() && $this->expiresIn() <= 30 && 2 !== $version ) : ?>
		<p class="tvd-card-spacer tvd-card-margin"><?php echo sprintf( __( 'The GoToWebinar access token will expire in <strong>%s days</strong>. Renew the token by providing your GoToWebinar credentials below', TVE_DASH_TRANSLATE_DOMAIN ), $this->expiresIn() ); ?></p>
	<?php else : ?>
		<p class="tvd-card-spacer tvd-card-margin"><?php echo __( 'Fill in your GoToWebinar username (email) and password below to connect', TVE_DASH_TRANSLATE_DOMAIN ); ?></p>
	<?php endif ?>
	<form class="tvd-col tvd-s12" autocomplete="false">
		<input type="hidden" name="api" value="<?php echo $this->getKey(); ?>"/>
		<div class="tvd-input-field tvd-margin-top">
			<input id="tvd-gtw-api-email" type="text" class="text" autocomplete="new-email" name="gtw_email" value="<?php echo $this->getUsername(); ?>"/>
			<label for="tvd-gtw-api-email"><?php echo __( 'Email', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-gtw-api-password" type="password" autocomplete="new-password" class="text" name="gtw_password" value="<?php echo $this->getPassword(); ?>"/>
			<label for="tvd-gtw-api-password"><?php echo __( 'Password', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6 tvd-no-padding">
			<p>
				<input class="tvd-version-1 tvd-api-hide-extra-options" name="connection[version]" type="radio" value="1" disabled id="tvd-version-1" <?php checked( $version, 1, true ); ?> />
				<label for="tvd-version-1"><?php echo __( 'Version 1', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
			</p>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6 tvd-no-padding">
			<p>
				<input class="tvd-version-2 tvd-api-show-extra-options" name="connection[version]" type="radio" value="2" id="tvd-version-2" <?php echo ! $this->isConnected() || 2 === $version ? 'checked="checked"' : ''; ?> />
				<label for="tvd-version-2"><?php echo __( 'Version 2', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
				<input type="hidden" name="connection[versioning]" value="1">
			</p>
		</div>
		<?php $this->display_video_link(); ?>
	</form>
</div>
<div class="tvd-card-action">
	<div class="tvd-row tvd-no-margin">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ); ?></a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-connect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo __( 'Connect', TVE_DASH_TRANSLATE_DOMAIN ); ?></a>
		</div>
	</div>
</div>
