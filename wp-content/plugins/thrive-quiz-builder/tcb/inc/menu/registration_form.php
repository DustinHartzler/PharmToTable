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
<div id="tve-registration_form-component" class="tve-component" data-view="RegistrationForm">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control" data-key="showLabels" data-view="Switch" data-label="<?php esc_attr_e( 'Show labels', 'thrive-cb' ); ?>"></div>
		<hr>
		<div class="tve-control pt-5" data-key="connectionType" style="display: none" data-view="ButtonGroup"></div>
		<div class="connection-controls" data-connection="api">
			<div class="tve-lg-connection">
				<span><?php echo __( 'Send leads to', 'thrive-cb' ); ?></span>
				<button class="tcb-right tve-button blue click tve-add-lg-connection" data-fn="addConnection">
					<?php echo __( 'Add Connection', 'thrive-cb' ); ?>
				</button>
			</div>
			<div class="no-api tve-control api-connections-list" data-view="ApiConnections"></div>
			<hr class="mt-10">
			<div class="control-grid">
				<div class="label"><?php echo __( 'Form fields', 'thrive-cb' ); ?></div>
				<div class="full">
					<a class="tcb-right click tve-lg-add-field" data-fn="addLGField">
						<i class="mr-5">
							<?php tcb_icon( 'plus-regular' ); ?>
						</i>
						<?php echo __( 'Add new', 'thrive-cb' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="tve-control" data-key="FieldsControl" data-initializer="getFieldsControl"></div>

		<div class="tve-lg-hidden-fields">
			<div class="control-grid">
				<div class="label"><?php echo __( 'Hidden fields', 'thrive-cb' ); ?></div>
				<div class="full" data-tooltip="<?php echo __( 'Hidden', 'thrive-cb' ); ?>" data-side="left">
					<?php tcb_icon( 'eye-light-slash' ); ?>
				</div>
			</div>
			<div class="tve-control" data-key="HiddenFieldsControl"></div>
		</div>

		<hr>

		<div id="api-controls" class="mt-10">

			<div class="no-api tve-advanced-controls extend-grey">
				<div class="dropdown-header" data-prop="advanced">
					<span>
						<?php echo __( 'Advanced', 'thrive-cb' ); ?>
					</span>
				</div>
				<div class="dropdown-content pt-0">
					<?php do_action( 'tcb_lead_generation_menu' ); ?>
					<div class="tve-lg-prevention">
						<p class="strong"><?php echo __( 'Spam prevention', 'thrive-cb' ); ?></p>
						<div class="tve-control" data-view="Captcha"></div>
						<a class="tcb-hidden info-link toggle-control mb-5" target="_blank" href="<?php echo admin_url( 'admin.php?page=tve_dash_api_connect' ); ?>">
							<span class="info-text"><?php echo __( 'Requires integration with Google ReCaptcha', 'thrive-cb' ); ?></span>
						</a>
					</div>
					<div class="no-api tcb-text-center mt-5">
						<button class="tve-button blue long click" data-fn="manage_error_messages">
							<?php echo __( 'Edit error messages', 'thrive-cb' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
