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

<div id="tve-login-component" class="tve-component" data-view="Login">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="no-api tcb-text-center login-elem-text mb-10 mr-5 ml-5">
			<button class="tve-button orange click" data-fn="editFormElements">
				<?php echo __( 'Edit Form Elements', 'thrive-cb' ); ?>
			</button>
		</div>

		<div class="skip-api no-service mb-5 click">
			<span><?php echo __( 'After Successful Login', 'thrive-cb' ); ?></span>

			<div class="tve-login-options-wrapper mt-10 click" data-fn="setAfterLoginAction">
				<div class="input">
					<a href="javascript:void(0)" class="click style-input flex-start dots">
						<span class="preview"></span>
						<span class="submit-value tcb-truncate t-80"></span>
						<span class="mr-5">
							<?php tcb_icon( 'pen-regular' ); ?>
						</span>
					</a>
				</div>
			</div>
			<div class="tve-login-submit-option-control"></div>
		</div>

		<div class="tve-control tcb-icon-side-wrapper mt-10 tcb-login-align" data-key="Align" data-view="ButtonGroup"></div>
		<div class="tve-control tcb-icon-side-wrapper mt-10" data-view="FormWidth"></div>
		<hr>
		<div class="tve-control" data-view="AddRemoveLabels"></div>
		<div class="tve-control" data-view="RememberMe"></div>
		<div class="tve-control" data-view="PassResetUrl"></div>
	</div>
</div>
