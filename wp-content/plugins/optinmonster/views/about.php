<h2></h2>
<div class="omapi-box omapi-box--flex">
	<div class="omapi-box__half">
		<p><strong><?php esc_html_e( 'Over the years, we found that many businesses struggle to collect emails simply because the tools aren’t easy to use and are far too expensive. So we started with a simple goal: build powerful enterprise-level technology to help businesses grow their customer base and revenue.', 'optin-monster-api' ); ?></strong></p>
		<p><?php esc_html_e( 'Since our launch in 2013, we have been improving conversions for small independent businesses to Fortune 500 companies. Over a billion people see a website with OptinMonster on it every month. Our customers are seeing huge increases in their subscriber growth and overall sales.', 'optin-monster-api' ); ?></p>
		<p><?php esc_html_e( 'We are humbly considered thought leaders by many in our space because of our rapid growth and innovations. Whatever the market conditions or current trends, you will always find OptinMonster leading the way to help our customers gain competitive business advantage and stay ahead of the curve.', 'optin-monster-api' ); ?></p>
	</div>
	<div class="omapi-box__half">
		<div class="omapi-box__image">
			<img src="<?php echo esc_url( $this->url . 'assets/images/omteam.jpg' ); ?>" alt="OptinMonster Team">
			<div class="omapi-box__image-subtitle">
				<?php printf( esc_html__( 'The %1$s Team: %2$s', 'optin-monster-api' ), 'OptinMonster', 'Syed, Christina, Muneeb, Keri, Pranaya, Anna, Tommy, Thomas, Justin, Rahul, Erica, Ben, Devin, Calista, Brandon, Jonathan, Briana' ); ?>
			</div>
		</div>
	</div>
</div>
<div class="notice notice-error" style="display:none;" id="om-plugin-alerts"></div>
<div class="omapi-plugin-recommendations">
	<?php foreach ( $data['plugins'] as $plugin_id => $plugin ) : ?>
	<div class="omapi-plugin-recommendation omapi-plugin-recommendation--<?php echo $plugin['class']; ?>">
		<div class="omapi-plugin-recommendation__details">
			<div class="omapi-plugin-recommendation__image-wrapper">
				<img src="<?php echo esc_url( $plugin['icon'] ); ?>" alt="<?php echo esc_html( $plugin['name'] ); ?>">
			</div>
			<div class="omapi-plugin-recommendation__text-wrapper">
				<h5 class="omapi-plugin-recommendation__name">
					<?php echo esc_html( $plugin['name'] ); ?>
				</h5>
				<p class="omapi-plugin-recommendation__description">
					<?php echo esc_html( $plugin['desc'] ); ?>
				</p>
			</div>
		</div>
		<div class="omapi-plugin-recommendation__actions">
			<div class="omapi-plugin-recommendation__status">
				<strong><?php esc_html_e( 'Status:', 'optin-monster-api' ); ?></strong> <?php echo $plugin['active'] ? '<span class="omapi-green">'. $plugin['status'] .'</span>' : $plugin['status']; ?>
			</div>
			<div class="omapi-plugin-recommendation__action-button">
				<form class="install-plugin-form">
					<input type="hidden" name="plugin" value="<?php echo esc_attr( $plugin_id ); ?>">
					<input type="hidden" name="url" value="<?php echo esc_attr( $plugin['url'] ); ?>">
					<?php if ( empty( $plugin['installed'] ) ) : ?>
						<?php wp_nonce_field( 'install_plugin', 'nonce' ); ?>
						<input type="hidden" name="action" value="install">
						<button type="submit" class="button button-omapi-blue button-install">
							<?php esc_html_e( 'Install Plugin', 'optin-monster-api' ); ?>
						</button>
					<?php elseif ( empty( $plugin['active'] ) ): ?>
						<input type="hidden" name="action" value="activate">
						<?php wp_nonce_field( 'activate_plugin', 'nonce' ); ?>
						<button type="submit" class="button button-omapi-blue button-activate">
							<?php esc_html_e( 'Activate Plugin', 'optin-monster-api' ); ?>
						</button>
					<?php else: ?>
						<button disabled class="button button-omapi-outline disabled">
							<?php esc_html_e( 'Already Active', 'optin-monster-api' ); ?>
						</button>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
