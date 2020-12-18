<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
} ?>
<div id="tve-social-component" class="tve-component" data-view="Social">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tcb-text-center mb-10 mr-5 ml-5">
			<button class="tve-button orange click" data-fn="editElement">
				<?php echo __( 'Edit design', 'thrive-cb' ); ?>
			</button>
		</div>
		<div class="tve-control" data-view="CustomBranding"></div>
		<div class="tve-control gl-st-button-toggle-1 hide-states tcb-hidden" data-view="SocialSharePalettes"></div>
		<div class="tve-control gl-st-button-toggle-1 hide-states" data-view="CssVarChanger"></div>
		<div class="tcb-css-var-changer"></div>
		<div class="tve-control" data-key="type" data-view="ButtonGroup"></div>
		<div class="tve-control" data-key="style" data-initializer="style_control"></div>
		<div class="tve-control" data-key="orientation" data-view="ButtonGroup"></div>
		<hr>
		<div class="tve-control" data-key="size" data-view="Slider"></div>
		<div class="tve-control pt-5 gl-st-button-toggle-2" data-key="Align" data-view="ButtonGroup"></div>
		<div class="tve-control" data-view="CommonButtonWidth"></div>
		<hr>
		<div class="control-grid">
			<span class="input-label"><?php echo __( 'Social Networks', 'thrive-cb' ) ?></span>
		</div>
		<div class="tve-control" data-key="selector" data-initializer="selector_control"></div>
		<div class="tve-control" data-key="preview" data-initializer="previewListInitializer"></div>
		<hr>
		<div class="tve-control no-space" data-key="has_custom_url" data-view="Switch"></div>
		<div class="tve-control no-space pt-5 pb-5 full-width" data-key="custom_url" data-view="LabelInput"></div>
		<div class="tve-control no-space" data-key="total_share" data-view="Switch"></div>
		<div class="tve-control no-space pt-5 input-small tve-counts-control" data-key="counts" data-view="LabelInput" data-label="<?php echo __( 'Display when greater than', 'thrive-cb' ); ?>"></div>
		<div class="control-grid tve-display-location mt-5">
			<div class="label">
				<?php echo __( 'Display location', 'thrive-cb' ); ?>
			</div>
			<div class="input">
				<select class="change tve-lg-field-type-select" data-fn="changeDisplayLocation">
					<option value="left"><?php echo __( 'Left', 'thrive-cb' ); ?></option>
					<option value="right"><?php echo __( 'Right', 'thrive-cb' ); ?></option>
					<option value="top"><?php echo __( 'Top', 'thrive-cb' ); ?></option>
					<option value="bottom"><?php echo __( 'Bottom', 'thrive-cb' ); ?></option>
				</select>
			</div>
		</div>
	</div>
</div>
