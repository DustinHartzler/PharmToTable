<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
$admin_email = get_option( 'admin_email' );
?>
<h2 class="tvd-card-title"><?php echo esc_html( $this->get_title() ); ?></h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->get_key() ); ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-pm-api-email" type="text" name="connection[email]"
				   value="<?php echo esc_attr( $this->param( 'email', $admin_email ) ); ?>">
			<label for="tvd-pm-api-email">
				<?php echo esc_html__( "Postmark-approved email address", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-pm-api-key" type="text" name="connection[key]"
				   value="<?php echo esc_attr( $this->param( 'key' ) ); ?>">
			<label for="tvd-pm-api-key"><?php echo esc_html__( "API key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<?php $this->display_video_link(); ?>
	</form>
</div>
<div class="tvd-row">
	<div class="tvd-col tvd-s12">
		<p class="tvd-form-description tvd-note-text">
			<?php echo esc_html__( 'Note: sending from Postmark only works if the email you enter has been verified in Postmark.', TVE_DASH_TRANSLATE_DOMAIN ) ?>
			<a href="http://developer.postmarkapp.com/" target="_blank"><?php echo esc_html__( 'Learn more', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>.
		</p>
	</div>
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
