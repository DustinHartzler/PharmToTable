<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

?>

<div id="tve-tvd-login-form-wrapper-component" class="tve-component" data-view="FormWrapper">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Form Wrapper', TVE_DASH_TRANSLATE_DOMAIN ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control mt-5" data-view="HorizontalPosition"></div>
		<div class="tve-control mt-5" data-view="VerticalPosition"></div>
		<div class="tve-control mt-5" data-view="FullHeight"></div>
	</div>
</div>
