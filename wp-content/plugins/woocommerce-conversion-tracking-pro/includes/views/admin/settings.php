<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2><?php esc_html_e( 'Settings', 'woocommerce-conversion-tracking-pro' ); ?></h2>

<div class="postbox permission-form">
	<h2 class="hndle"><?php esc_html_e( 'Integration Access', 'woocommerce-conversion-tracking-pro' ); ?></h2>

	<div class="inside">
		<p class="description">
			<?php esc_html_e( 'Allow your team to access the Conversion Tracking integration settings by role.', 'woocommerce-conversion-tracking-pro' ); ?>
		</p>

		<form action="" method="post" id="wcct-save-permission">
			<?php
			foreach ( $roles as $key => $user_role ) {
				$permission = isset( $get_permission[ $key ] ) ? $get_permission[ $key ] : '';
				?>
				<fieldset>
					<label for="<?php echo esc_attr( $key ); ?>">
						<input
							type="checkbox"
							name="<?php echo esc_attr( $key ); ?>"
							id="<?php echo esc_attr( $key ); ?>"
							value="1"
							<?php checked( 1, $permission ); ?> >
						<?php echo esc_attr( $user_role ); ?>
					</label>
				</fieldset>
				<?php
			}
			?>
			<p class="submit">
				<?php wp_nonce_field( 'wcct-settings' ); ?>
				<input type="hidden" name="action" value="wcct_save_permissions">
				<button class="button button-primary" type="submit"><?php esc_html_e( 'Save Changes', 'woocommerce-conversion-tracking-pro' ); ?></button>
			</p>
		</form>
	</div>
</div>
