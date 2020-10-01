<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
} ?>

<div id="tve-link-component" class="tve-component" data-view="Link">
	<div class="text-options action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php echo __( 'Main Options', 'thrive-cb' ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-control" data-view="ToggleColor" data-css_class=""></div>
			<div class="color-target">
				<div></div>
				<div class="tve-control pb-10" data-view="FontColor"></div>
			</div>
			<div class="tve-control" data-view="BgColor"></div>
			<div class="tve-control" data-view="ToggleFont" data-css_class=""></div>
			<div class="font-target">
				<div class="tcb-font-specific tcb-link-font"></div>
				<div class="tve-control tcb-link-font tcb-font-set pb-10" data-view="FontFace"></div>
			</div>
			<div class="tve-control" data-view="ToggleSize" data-css_class=""></div>
			<div class="size-target">
				<div class="tcb-size-specific tcb-text-font-size"></div>
				<div class="tve-control tcb-size-set tcb-text-font-size pb-10" data-view="FontSize"></div>
			</div>
			<div class="tve-control" data-view="TextStyle"></div>
			<hr>
			<div class="tve-control hide-states" data-view="Effect"></div>
			<div class="tve-control scrolled" data-key="EffectPicker" data-initializer="effectPicker"></div>
			<div class="eff-settings">
				<div class="tve-control hide-states eff-setting eff-color" data-view="EffectColor"></div>
				<div class="tve-control hide-states pb-10 eff-setting eff-speed" data-view="EffectSpeed"></div>
			</div>
			<hr class="hide-states">
			<div class="tcb-text-center hide-states">
				<span class="click tcb-text-uppercase clear-format custom-icon" data-fn="clearFormatting">
					<?php echo __( 'Clear all formatting', 'thrive-cb' ); ?>
				</span>
			</div>
		</div>
	</div>
</div>
