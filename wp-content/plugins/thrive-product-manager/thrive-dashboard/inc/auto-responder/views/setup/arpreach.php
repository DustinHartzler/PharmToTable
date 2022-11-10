<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
/** @var $this Thrive_Dash_List_Connection_ArpReach */
?>
<h2 class="tvd-card-title"><?php echo esc_html( $this->get_title() ) ?></h2>
<div class="tvd-row">
	<form class="">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->get_key() ); ?>"/>

		<div class="tvd-col tvd-s10">
			<div class="tvd-input-field">
				<input id="tvd-arp-api-url" type="text" name="connection[url]"
					   value="<?php echo esc_attr( $this->param( 'url' ) ) ?>">
				<label for="tvd-arp-api-url"><?php echo esc_html__( "Installation URL", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
			</div>
		</div>
		<div class="tvd-col tvd-s10">
			<div class="tvd-input-field">
				<input id="tvd-arp-api-key" type="text" name="connection[api_key]"
					   value="<?php echo esc_attr( $this->param( 'api_key' ) ) ?>">
				<label for="tvd-arp-api-key"><?php echo esc_html__( "API key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
			</div>
		</div>
		<div class="tvd-col tvd-s10">
			<div class="tvd-input-field">
				<input class="tvd-api-add-chip" id="tvd-arp-api-lists" type="text" data-name="connection[lists][]"/>
				<label for="tvd-arp-api-lists"><?php echo esc_html__( "List ID", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
			</div>
		</div>
		<div class="tvd-col tvd-s2">
			<i class="tvd-icon-question-circle tvd-tooltipped" data-position="top"
			   data-tooltip="<?php echo esc_html__( "Write the list ID and press the enter key", TVE_DASH_TRANSLATE_DOMAIN ) ?>"></i>
		</div>
		<div class="tvd-api-chip-wrapper tvd-col tvd-s12">
			<?php $lists = $this->param( 'lists' ); ?>
			<?php if ( ! empty( $lists ) ) : ?>
				<?php foreach ( $this->param( 'lists' ) as $key => $value ) : ?>
					<div class="tvd-chip"><?php echo esc_html($value) ?><i class="tvd-icon-close2"></i></div><input
						type="hidden" name="connection[lists][]"
						value="<?php echo esc_attr($value) ?>"/>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</form>
	<?php $this->display_video_link(); ?>
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
