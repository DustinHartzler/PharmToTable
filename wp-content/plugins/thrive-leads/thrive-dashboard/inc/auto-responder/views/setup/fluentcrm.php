<h2 class="tvd-card-title"><?php echo esc_html( $this->getTitle() ); ?></h2>
<?php
/** @var $this Thrive_Dash_List_Connection_MailPoet */
?>
<?php $installed = $this->pluginInstalled(); ?>
<?php if ( ! empty( $installed ) ) : ?>
	<div class="tvd-row">
		<p><?php echo esc_html__( 'Click the button below to enable FluentCRM integration.', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
		<?php $this->display_video_link(); ?>
	</div>
	<form>
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->getKey() ); ?>">
		<input type="hidden" name="connection[connected]" value="1">
	</form>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo esc_html__( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn tvd-api-connect"
				   href="javascript:void(0)"><?php echo esc_html__( 'Connect', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
		</div>
	</div>
<?php else : ?>
	<p><?php echo esc_html__( 'You currently do not have FluentCRM plugin installed or activated.', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	<br>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo esc_html__( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
		</div>
	</div>
<?php endif ?>
