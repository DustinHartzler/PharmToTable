<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

$dynamic_links = array(
	'login'       => array( 'label' => 'Log In' ),
	'logout'      => array( 'label' => 'Logout' ),
	'bk_to_login' => array( 'label' => 'Back to Login' ),
	'pass_reset'  => array( 'label' => 'Password Reset' ),
);

foreach ( $dynamic_links as $key => $dynamic_link ) {
	$_link = tcb_get_dynamic_link( $dynamic_link['label'], 'Login Form' );

	if ( $_link ) {
		$dynamic_link['id']  = isset( $_link['id'] ) ? $_link['id'] : '';
		$dynamic_link['url'] = isset( $_link['url'] ) ? $_link['url'] : '#';

		$dynamic_links[ $key ] = $dynamic_link;
	}
}
?>

<div class="thrv_wrapper thrv-login-element" data-ct="login" data-ct-name="Default">
	<div class="tcb-login-form-wrapper tve_empty_dropzone tve_no_drag tve-form-state tve-active-state" data-state="login">
		<div class="thrv_wrapper tcb-login-form tcb-no-clone tcb-no-delete tcb-no-save tve_no_drag">
			<form action="" method="post" novalidate class="tve-login-form">
				<div class="tve-form-drop-zone">
					<div class="tve-login-item-wrapper">
						<div class="tve-login-form-item tcb-no-clone tcb-no-delete tcb-no-save">
							<div class="thrv-form-input-wrapper tcb-no-clone tcb-no-delete tcb-no-save" data-type="email">
								<div class="thrv_wrapper tcb-label tcb-removable-label thrv_text_element tcb-no-delete tcb-no-save tcb-no-clone tve_no_drag">
									<p>
										<?php echo __( 'Username or Email Address', 'thrive-cb' ); ?>
									</p>
								</div>

								<div class="tve-login-form-input tcb-no-clone tcb-no-delete tcb-no-save">
									<input type="text" name="username">
								</div>
							</div>
						</div>
						<div class="tve-login-form-item tcb-no-clone tcb-no-delete tcb-no-save">
							<div class="thrv-form-input-wrapper tcb-no-clone tcb-no-delete tcb-no-save" data-type="password">
								<div class="thrv_wrapper tcb-label tcb-removable-label thrv_text_element tcb-no-delete tcb-no-save tcb-no-clone tve_no_drag">
									<p>
										<?php echo __( 'Password', 'thrive-cb' ); ?>
									</p>
								</div>
								<div class="tve-login-form-input tcb-no-clone tcb-no-delete tcb-no-save">
									<input type="password" name="password">
								</div>
							</div>
						</div>
						<div class="tve-login-form-item tcb-remember-me-item tcb-no-delete tcb-no-clone tcb-no-save">
							<div class="thrv_wrapper tcb-label thrv_text_element tcb-no-delete tcb-no-save tcb-no-clone tve_no_drag">
								<p>
									<input type="checkbox" name="remember_me">
									<?php echo __( 'Remember me', 'thrive-cb' ); ?>
								</p>
							</div>
						</div>
					</div>

					<div class="thrv_wrapper thrv-button tar-login-submit tar-login-elem-button tcb-no-delete tcb-no-save tcb-no-scroll tcb-no-clone">
						<a href="javascript:void(0);" class="tcb-button-link tve-dynamic-link tcb-no-delete" data-dynamic-link="thrive_login_form_shortcode" data-shortcode-id="<?php echo $dynamic_links['login']['id']; ?>" data-editable="false">
						<span class="tcb-button-texts tcb-no-clone tve_no_drag tcb-no-save tcb-no-delete">
							<span class="tcb-button-text thrv-inline-text tcb-no-clone tve_no_drag tcb-no-save tcb-no-delete">
								<?php esc_attr_e( 'Log In', 'thrive-cb' ); ?>
							</span>
						</span>
						</a>
					</div>
					<div class="thrv_wrapper thrv_text_element tcb-lost-password-link tar-login-elem-link tcb-no-delete tcb-no-title tcb-no-save tcb-no-clone">
						<p class="tcb-switch-state" data-switch_state="forgot_password" data-shortcode-id="1">
							<a href="javascript:void(0)" class="tve-dynamic-link" data-dynamic-link="thrive_login_form_shortcode" data-shortcode-id="<?php echo $dynamic_links['pass_reset']['id']; ?>" data-editable="false">
								<?php esc_attr_e( 'I have forgotten my password', 'thrive-cb' ); ?>
							</a>
						</p>
					</div>

					<!--Needed for the loader-->
					<button type="submit" style="display: none"></button>
				</div>
			</form>
		</div>
	</div>

	<div class="tcb-login-form-wrapper tve-form-state tve_empty_dropzone tcb-permanently-hidden tve_no_drag" data-state="forgot_password">
		<div class="thrv_wrapper tcb-login-form tcb-no-clone tcb-no-delete tcb-no-save tve_no_drag">
			<form action="" method="post" class="tve-login-form" novalidate>

				<div class="tve-form-drop-zone">
					<div class="thrv_wrapper thrv_contentbox_shortcode thrv-content-box tve-elem-default-pad tcb-no-delete tcb-no-save tcb-no-clone">
						<div class="tve-content-box-background"></div>
						<div class="tve-cb">
							<div class="thrv_wrapper thrv_text_element thrv-form-title" data-tag="h2">
								<h2><?php echo __( 'Password Reset', 'thrive-cb' ); ?></h2>
							</div>
							<div class="thrv_wrapper thrv_text_element thrv-form-info">
								<p><?php echo __( 'Please enter your email address. You will receive a link to create a new password via email', 'thrive-cb' ); ?></p>
							</div>
							<div class="tve-cf-item-wrapper">
								<div class="tve-login-form-item tcb-no-clone tcb-no-delete tcb-no-save">
									<div class="thrv-form-input-wrapper" data-type="text">
										<div class="thrv_wrapper tcb-label tcb-removable-label thrv_text_element tcb-no-delete tcb-no-save tcb-no-clone tve_no_drag">
											<p>
												<?php echo __( 'Username or Email Address', 'thrive-cb' ); ?>
											</p>
										</div>
										<div class="tve-login-form-input tcb-no-clone tcb-no-delete tve_no_drag tcb-no-save">
											<input type="text" name="login">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="thrv_wrapper thrv-button tar-login-submit tar-login-elem-button tcb-no-delete tcb-no-save tcb-no-clone">
						<a href="javascript:void(0);" class="tcb-button-link" data-dynamic-link="thrive_login_form_shortcode" data-shortcode-id="<?php echo $dynamic_links['pass_reset']['id']; ?>" data-editable="false">
						<span class="tcb-button-texts">
							<span class="tcb-button-text thrv-inline-text">
								<?php esc_attr_e( 'Get New Password', 'thrive-cb' ); ?>
							</span>
						</span>
						</a>
					</div>

					<div class="thrv_wrapper thrv_text_element tar-login-elem-link tcb-no-title tcb-no-save tcb-no-clone">
						<p class="tcb-switch-state" data-switch_state="login" data-shortcode-id="0">
							<a href="javascript:void(0)" class="tve-dynamic-link" data-dynamic-link="thrive_login_form_shortcode" data-shortcode-id="<?php echo $dynamic_links['bk_to_login']['id']; ?>" data-editable="false">
								<?php esc_attr_e( 'Back to login', 'thrive-cb' ); ?>
							</a>
						</p>
					</div>

					<!--Needed for the loader-->
					<button type="submit" style="display: none"></button>
				</div>

			</form>
		</div>
	</div>

	<div class="tcb-login-form-wrapper tve-form-state tve_empty_dropzone tcb-permanently-hidden tve_no_drag" data-state="reset_confirmation">

		<div class="tve-form-drop-zone">

			<div class="thrv_wrapper thrv_contentbox_shortcode thrv-content-box tve-elem-default-pad">
				<div class="tve-content-box-background"></div>
				<div class="tve-cb">
					<div class="thrv_wrapper thrv_text_element thrv-form-title" data-tag="h2">
						<h2><?php echo __( 'Password Reset', 'thrive-cb' ); ?></h2>
					</div>
					<div class="thrv_wrapper thrv_text_element thrv-form-info">
						<p><?php echo __( 'The instructions to reset your password are sent to the email address you provided. If you did not receive the email, please check your spam folder as well', 'thrive-cb' ); ?></p>
					</div>
				</div>
			</div>

			<div class="thrv_wrapper thrv_text_element tar-login-elem-link tcb-no-clone tcb-no-title tcb-no-save tcb-no-clone">
				<p class="tcb-switch-state" data-switch_state="login" data-shortcode-id="0">
					<a href="javascript:void(0)" class="tve-dynamic-link" data-dynamic-link="thrive_login_form_shortcode" data-shortcode-id="<?php echo $dynamic_links['bk_to_login']['id']; ?>" data-editable="false">
						<?php esc_attr_e( 'Back to login', 'thrive-cb' ); ?>
					</a>
				</p>
			</div>

		</div>

	</div>

	<div class="tcb-login-form-wrapper tve-form-state tve_empty_dropzone tcb-permanently-hidden tve_no_dra" data-state="logged_in">

		<div class="tve-form-drop-zone">
			<div class="thrv_wrapper thrv_contentbox_shortcode thrv-content-box tve-elem-default-pad ">
				<div class="tve-content-box-background"></div>
				<div class="tve-cb">
					<div class="thrv_wrapper thrv_text_element thrv-form-title tar-login-elem-h2" data-tag="h2">
						<h2><?php echo __( 'You are already logged in', 'thrive-cb' ); ?></h2>
					</div>
				</div>
			</div>

			<div class="thrv_wrapper thrv_text_element tar-login-elem-link tcb-no-clone tcb-no-title tcb-no-save tcb-no-clone">
				<p class="tcb-switch-state" data-switch_state="login" data-shortcode-id="0">
					<a href="<?php echo $dynamic_links['logout']['url']; ?>" class="tve-dynamic-link" data-dynamic-link="thrive_login_form_shortcode" data-shortcode-id="<?php echo $dynamic_links['logout']['id']; ?>" data-editable="false">
						<?php esc_attr_e( 'Log Out', 'thrive-cb' ); ?>
					</a>
				</p>
			</div>
		</div>

	</div>
	<input type="hidden" name="config" value="YTozOntzOjEzOiJzdWJtaXRfYWN0aW9uIjtzOjExOiJzaG93TWVzc2FnZSI7czoxMjoicmVkaXJlY3RfdXJsIjtzOjA6IiI7czoxNToic3VjY2Vzc19tZXNzYWdlIjtzOjc6IlN1Y2Nlc3MiO30=">
</div>
