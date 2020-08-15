<script src="//use.typekit.net/pef1xgi.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
<div class="omapi-static-banner">
	<div class="inner-container">
		<div class="logo-wrapper">
			<?php require dirname( __FILE__ ) . '/logo.svg' ?>
			<span class="omapi-logo-version">
				<?php printf( esc_html__( 'v%s', 'optin-monster-api' ), $this->version );  ?>
				<?php if ( $this->beta_version() ) : ?>
					&mdash; <strong>Beta Version: <?php echo $this->beta_version(); ?></strong>
				<?php endif; ?>
			</span>
		</div>
		<div class="static-menu">
			<ul>
				<li>
					<a target="_blank" rel="noopener" href="https://optinmonster.com/docs/"><?php esc_html_e( 'Need Help?', 'optin-monster-api' ); ?> </a>
				</li>
				<li>
					<a href="https://optinmonster.com/contact-us/" target="_blank" rel="noopener"><?php esc_html_e( 'Send Us Feedback', 'optin-monster-api' ); ?></a>
				</li>
				<?php if ( $this->get_api_credentials() ) : ?>
					<li class="omapi-menu-button">
						<a id="omapi-create-new-optin-button" href="<?php echo esc_url( OPTINMONSTER_APP_URL . '/campaigns/new/' ); ?>" class="button button-secondary omapi-new-optin" title="<?php echo esc_attr_e( 'Create New Campaign', 'optin-monster-api' ); ?>" rel="noopener"><?php esc_html_e(  'Create New Campaign', 'optin-monster-api' ); ?></a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
