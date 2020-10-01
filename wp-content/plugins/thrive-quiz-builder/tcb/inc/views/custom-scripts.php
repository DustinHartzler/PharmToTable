<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>
<div class="state-custom-scripts state">
	<span class="label tcb-hide"><?php echo __( 'Custom Scripts', 'thrive-cb' ); ?></span>
	<section>
		<div class="field-section s-setting">
			<label class="s-name">Header scripts (Before the <b>&lt;/head&gt;</b> end tag)</label>
			<textarea rows="5" title="<?php echo __( 'Header Scripts', 'thrive-cb' ); ?>" data-location="head"></textarea>
		</div>
		<div class="field-section no-border s-setting">
			<label class="s-name">Body (header) scripts (Immediately after the <b>&lt;body&gt;</b> tag)</label>
			<textarea rows="5" title="<?php echo __( 'Body Scripts', 'thrive-cb' ); ?>" data-location="body"></textarea>
		</div>
		<div class="field-section no-border s-setting">
			<label class="s-name">Body (footer) scripts (Before the <b>&lt;/body&gt;</b> end tag)</label>
			<textarea rows="5" title="<?php echo __( 'Footer Scripts', 'thrive-cb' ); ?>" data-location="footer"></textarea>
		</div>
	</section>
</div>
