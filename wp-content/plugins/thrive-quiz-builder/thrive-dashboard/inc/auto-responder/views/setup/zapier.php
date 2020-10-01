<?php
/** var $this Thrive_Dash_List_Connection_Zapier */

$using_https = ( strpos( get_site_url(), 'https://' ) !== false ) && is_ssl() ? true : false;
?>
<h2 class="tvd-card-title"><?php echo $this->getTitle(); ?></h2>

<?php if ( ! $using_https ) : ?>
	<div class="tvd-row tvd-text-red">
		<p><?php echo __( 'In order to be able to create a connection between your website and Zapier, your website must be secure.', TVE_DASH_TRANSLATE_DOMAIN ); ?></p>
		<p>&nbsp;</p>
		<p><?php echo __( 'Thus, for security reasons, please make sure that your website has a valid SSL certificate and it works properly on the "https" protocol.', TVE_DASH_TRANSLATE_DOMAIN ); ?></p>
		<p>&nbsp;</p>
		<p><?php echo __( 'If you need more information on this matter, please read <a href="https://thrivethemes.com/tkb_item/how-to-make-sure-your-website-is-secure-with-ssl-and-https/" target="_blank">this article</a>.', TVE_DASH_TRANSLATE_DOMAIN ); ?></p>
	</div>
<?php else : ?>
	<div class="tdz-mb20">
		<p><?php echo __( 'Copy the API Key and the Blog URL from the fields below to connect to Zapier.', TVE_DASH_TRANSLATE_DOMAIN ); ?></p>
	</div>
	<div class="tvd-row">
		<form class="tvd-col tvd-s12 tvd-zapier-rel">
			<input type="hidden" name="api" value="<?php echo $this->getKey(); ?>"/>
			<div class="tvd-input-field">
				<input id="tvd-zapier-api-key" readonly type="text" name="connection[api_key]" value="<?php echo $this->get_api_key(); ?>">
				<label for="tvd-zapier-api-key"><?php echo __( 'API key', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
			</div>

			<div class="tvd-row tvd-collapse tvd-copy-row">
				<div class="tvd-no-margin-left">
					<div class="tvd-input-field">
						<input type="hidden" id="tvd-zapier-token-hidden" class="tvd-zapier-token-hidden tvd-copy" disabled="disabled"
						       name="tvd-zapier-token-hidden" value="<?php echo $this->get_api_key(); ?>"/>
						<span class="">
							<a class="tvd-zapier-copy-key tvd-right"
							   href="javascript:void(0)">
								<span class="">&nbsp;</span>
							</a>
						</span>
					</div>
				</div>
			</div>

			<div class="tvd-input-field">
				<input id="tvd-zapier-api-blog_url" readonly type="text" name="connection[blog_url]" value="<?php echo $this->get_blog_url(); ?>">
				<label for="tvd-zapier-api-blog_url"><?php echo __( 'Blog URL', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
			</div>

			<div class="tvd-row tvd-collapse tvd-copy-row">
				<div class="tvd-no-margin-left">
					<div class="tvd-input-field">
						<input type="hidden" id="tvd-zapier-url-hidden" class="tvd-zapier-url-hidden tvd-copy" disabled="disabled"
						       name="tvd-zapier-url-hidden"
						       value="<?php echo $this->get_blog_url(); ?>"/>
						<span class="">
								<a class="tvd-zapier-copy-url tvd-right"
								   href="javascript:void(0)">
									<span class="">&nbsp;</span>
								</a>
							</span>
					</div>
				</div>
			</div>

			<?php $this->display_video_link(); ?>
		</form>
	</div>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ); ?></a>
			</div>
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-api-connect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo __( 'Connect', TVE_DASH_TRANSLATE_DOMAIN ); ?></a>
			</div>
		</div>
	</div>
<?php endif; ?>

