<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
} ?>

<div id="tve-megamenu_image-component" class="tve-component" data-view="MegamenuImage">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="hide-tablet hide-mobile">
			<div class="tve-control" data-view="ImagePicker"></div>
			<hr>
		</div>
		<div class="tve-control" data-view="Height"></div>
	</div>
</div>
