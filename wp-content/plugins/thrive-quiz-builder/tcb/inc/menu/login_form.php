<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>
<div id="tve-login_form-component" class="tve-component" data-view="LoginForm">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Login Form Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control" data-key="FieldsControl" data-initializer="getFieldsControl"></div>
	</div>
</div>
