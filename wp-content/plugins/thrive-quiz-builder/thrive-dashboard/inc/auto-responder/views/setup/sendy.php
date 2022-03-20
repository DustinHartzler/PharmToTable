<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
 $version = $this->param( 'version' ); ?>

<h2 class="tvd-card-title"><?php echo esc_html( $this->getTitle() ); ?></h2>
<div class="tvd-row">
	<form class="">
		<input type="hidden" name="api" value="<?php echo esc_attr( $this->getKey() ); ?>"/>
		<input type="hidden" name="connection[versioning]" value="1"/>
		<div class="tvd-col tvd-s10 tvd-extra-options <?php echo empty( $version ) || $version === '1' ? 'tvd-hide"' : ''; ?>">
			<div class="tvd-input-field">
				<input id="tvd-s-api-url" type="text" name="connection[api_key]"
					   value="<?php echo esc_attr( $this->param( 'api_key' ) ) ?>">
				<label for="tvd-s-api-url"><?php echo esc_html__( "Api Key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
			</div>
		</div>
		<div class="tvd-col tvd-s10">
			<div class="tvd-input-field">
				<input id="tvd-s-api-url" type="text" name="connection[url]"
					   value="<?php echo esc_attr( $this->param( 'url' ) ); ?>">
				<label for="tvd-s-api-url"><?php echo esc_html__( "Installation URL", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
			</div>
		</div>
		<div class="tvd-col tvd-s10">
			<div class="tvd-input-field">
				<input class="tvd-api-add-chip" id="tvd-s-api-lists" type="text" data-name="connection[lists][]" name="connection[lists][]"/>
				<label for="tvd-s-api-lists"><?php echo esc_html__( "List ID", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
			</div>
		</div>
		<div class="tvd-col tvd-s2">
			<i class="tvd-icon-question-circle tvd-tooltipped" data-position="top"
			   data-tooltip="<?php echo esc_html__( "Write the list ID and press the enter key", TVE_DASH_TRANSLATE_DOMAIN ) ?>"></i>
		</div>
		<div class="tvd-api-chip-wrapper tvd-col tvd-s12">
			<?php /** @var $this Thrive_Dash_List_Connection_Sendy */ ?>
			<?php $lists = $this->param( 'lists' ); ?>
			<?php if ( ! empty( $lists ) ) : ?>
				<?php foreach ( $lists as $key => $value ) : ?>
					<div class="tvd-chip"><?php echo esc_html( $value ); ?><i class="tvd-icon-close2"></i></div><input
						type="hidden" name="connection[lists][]"
						value="<?php echo esc_attr( $value ); ?>"/>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="tvd-col tvd-s12 tvd-m6 tvd-no-padding">
			<p>
				<input class="tvd-version-2 tvd-api-hide-extra-options" name="connection[version]" type="radio" value="1"
					   id="tvd-version-1" <?php echo empty( $version ) || $version === '1' ? 'checked="checked"' : ''; ?> />
				<label for="tvd-version-1"><?php echo esc_html__( 'Below 4.0.3.3', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
			</p>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6 tvd-no-padding">
			<p>
				<input class="tvd-version-3 tvd-api-show-extra-options" name="connection[version]" type="radio" value="2"
					   id="tvd-version-2" <?php echo ! empty( $version ) && $version === '2' ? 'checked="checked"' : ''; ?> />
				<label for="tvd-version-2"><?php echo esc_html__( 'Above 4.0.3.3', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
			</p>
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
