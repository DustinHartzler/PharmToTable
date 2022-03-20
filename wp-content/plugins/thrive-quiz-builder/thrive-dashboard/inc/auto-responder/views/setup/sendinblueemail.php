<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
/** var $this Thrive_Dash_List_Connection_SendinblueEmail */
?>
<h2 class="tvd-card-title"><?php echo esc_html( $this->getTitle() ); ?></h2>
<div class="tvd-row">
	<div class="tvd-col tvd-s12">
		<p class="tve-form-description tvd-note-text">
			<?php echo esc_html__( 'Note: Sending email through Sendinblue only works if the SMTP feature is activated for your account.', TVE_DASH_TRANSLATE_DOMAIN ) ?>
			<a href="https://resources.sendinblue.com/en/francais-mon-compte-sendinblue-smtp-nest-pas-active-comment-faire/"
			   target="_blank"><?php echo esc_html__( 'Learn more', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>.
		</p>
		<p>
			<strong><?php echo esc_html__( 'Note:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong><?php echo esc_html__( 'Connecting to Sendinblue Email Service will also connect to Sendinblue autoresponders.', TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</p>
	</div>
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->getKey() ); ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-aw-api-email" type="text" name="connection[key]"
			       value="<?php echo esc_attr( $this->param( 'key' ) ); ?>">
			<label for="tvd-aw-api-email"><?php echo esc_html__( "API key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
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
