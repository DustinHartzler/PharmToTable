<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
/** @var $this Thrive_Dash_List_Connection_ConstantContact */
?>
<h2 class="tvd-card-title"><?php echo esc_html( $this->get_title() ); ?></h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->get_key() ); ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-cc-api-key" type="text" name="connection[api_key]"
				   value="<?php echo esc_attr( $this->param( 'api_key' ) ); ?>">
			<label for="tvd-cc-api-key"><?php echo esc_html__( "API key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
			<p><?php echo esc_html__( "To get an API Key you have to follow these steps:", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
			<ol>
				<li>
					<?php echo sprintf( __( "Register a new account %s", TVE_DASH_TRANSLATE_DOMAIN ), '<a target="_blank" href="https://constantcontact.mashery.com/member/register">' . esc_html__( "here", TVE_DASH_TRANSLATE_DOMAIN ) . '</a>' ) ?></li> <?php //phpcs:ignore ?>
				<li>
					<?php echo esc_html__( "Log in and create a new Application for which the API key will be automatically be generated.", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
				<li>
					<?php echo esc_html__( "Copy+Paste the API Key into the field", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
			</ol>
		</div>
		<div class="tvd-row">
			<div class="tvd-col tvd-s12">
				<a id="btn-get-token" class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn" href="<?php echo esc_url( $this->getTokenUrl() ); ?>"><?php echo esc_html__( "Get Token", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
		</div>
		<div class="tvd-row">
			<div class="tvd-col tvd-s12">
				<div class="tvd-input-field">
					<input id="tvd-cc-api-token" type="text" name="connection[api_token]"
						   value="<?php echo esc_attr( $this->param( 'api_token' ) ); ?>">
					<label for="tvd-cc-api-token"><?php echo esc_html__( "API token", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
					<p><?php echo esc_html__( "To get an API Token you have to follow these steps:", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
					<ol>
						<li><?php echo esc_html__( "After you have completed the steps for getting an API Key you have to click the Get Token Button", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
						<li><?php echo esc_html__( "Follow the steps until you receive the token string", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
						<li><?php echo esc_html__( "Copy+Paste the token string into the field and click the Connect to Constant Contact button", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
					</ol>
				</div>
			</div>
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


<script type="text/javascript">
	(
		function ( $ ) {
			var _token_url = $( "#btn-get-token" ).attr( 'href' );

			$( "#btn-get-token" ).click( function () {
				var api_key = $( "#tvd-cc-api-key" ).val(),
					$this = $( this );
				if ( ! api_key ) {
					alert( '<?php echo __( "Please enter the API Key in order to get the token !", TVE_DASH_TRANSLATE_DOMAIN ) ?>' );
					return false;
				}
				$this.attr( 'href', _token_url + api_key );
			} );
		}
	)( jQuery );
</script>
