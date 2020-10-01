<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
} ?>

<div id="tve-progressbar-component" class="tve-component" data-view="ProgressBar">
	<div class="action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php echo __( 'Main Options', 'thrive-cb' ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-control" data-view="FillColor"></div>
			<div class="tve-control mt-10" data-view="BackgroundColor"></div>
			<div class="tve-control" data-view="ExternalFields"></div>
			<div class="tve-control custom-fields-state" data-state="static" data-view="FillPercent"></div>
			<div class="tve-control" data-view="InnerLabel"></div>
			<div class="tve-control" data-view="LabelColor"></div>
		</div>
	</div>
</div>

