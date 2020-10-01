<h2 class="tvd-card-title"><?php echo $this->getTitle() ?></h2>
<?php
/** @var $this Thrive_Dash_List_Connection_Mailster */
?>
<?php if ( ! empty( $this->pluginInstalled() ) ) : ?>
	<div class="tvd-row">
		<p><?php echo __( 'Click the button below to enable Mailster integration.', 'thrive-dash' ); ?></p>
	</div>
	<form>
		<input type="hidden" name="api" value="<?php echo $this->getKey(); ?>">
		<?php $this->display_video_link(); ?>
	</form>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect">
					<?php echo __( 'Cancel', 'thrive-dash' ); ?>
				</a>
			</div>
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn tvd-api-connect"
				   href="javascript:void(0)"><?php echo __( 'Connect', 'thrive-dash' ); ?></a>
			</div>
		</div>
	</div>
<?php else : ?>
	<p>
		<?php echo __( 'You currently do not have Mailster plugin installed or activated.', 'thrive-dash' ); ?>
	</p>
	<br>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect">
					<?php echo __( 'Cancel', 'thrive-dash' ); ?>
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>
