<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package TCB2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

?>
<div id="tve-icon-component" class="tve-component" data-view="Icon">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="hide-states pb-10">
			<div class="tve-control tve-choose-icon gl-st-icon-toggle-2" data-view="IconPicker"></div>
			<div class="tve-control" data-view="StylePicker" data-initializer="style"></div>
		</div>
		<div class="tve-control no-space gl-st-icon-toggle-1" data-view="ColorPicker"></div>
		<div class="hide-states pt-10">
			<div class="tve-control gl-st-icon-toggle-1" data-view="Slider"></div>
			<div class="tve-control" data-key="ToggleURL" data-extends="Switch" data-label="<?php echo __( 'Add link to icon', 'thrive-cb' ); ?>"></div>
			<div class="tve-control link-control" data-key="link" data-initializer="elementLink"></div>
		</div>
	</div>
</div>
