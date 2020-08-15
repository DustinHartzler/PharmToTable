<div class="omapi-welcome-content">
	<div class="inner-container">
		<h1><?php esc_html_e( 'Welcome to OptinMonster', 'optin-monster-api' ); ?></h1>

		<div class="omapi-well welcome-connect">
			<p><?php esc_html_e( 'Please connect to or create an OptinMonster account to start using OptinMonster. This will enable you to start turning website visitors into subscribers & customers.', 'optin-monster-api' ); ?></p>
			<div class="actions">
				<a class="button button-omapi-green button-hero" href="<?php echo $data['button_link']; ?>" target="_blank"><?php echo $data['button_text']; ?></a>
				<span class="or">or</span>
				<a class="button button-omapi-gray button-hero omapi-authorize-button" href="<?php echo $data['api_link']; ?>"><?php esc_html_e( 'Connect Your Account', 'optin-monster-api' ) ?></a>
			</div>
		</div>
		<div id="js__omapi-video-well" class="omapi-well welcome-data-vid">
			<h2><?php esc_html_e( 'Get More Email Subscribers, FAST!', 'optin-monster-api' ); ?></h2>
			<p><?php esc_html_e( 'OptinMonster helps you convert abandoning website visitors into email subscribers with smart web forms and behavior personalization.', 'optin-monster-api' ); ?></p>
			<div class="actions">
				<a id="js_omapi-welcome-video-link" class="omapi-video-link" href="https://www.youtube.com/embed/jbP9C9bQtv4?rel=0&amp;controls=0&amp;showinfo=0&amp;autoplay=1">
					<img width="188" src="<?php echo plugins_url( '/assets/css/images/video-cta-button.png', OMAPI_FILE ) ?>">
				</a>
			</div>
			<div class="omapi-welcome-video-holder">
				<iframe id="js__omapi-welcome-video-frame" width="640" height="360" src="" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>

		<div class="omapi-sub-title">
			<h2><?php esc_html_e( 'Top 4 Reasons Why People Love OptinMonster', 'optin-monster-api' ); ?></h2>
			<p><?php esc_html_e( 'Here\'s why smart business owners love OptinMonster, and you will too!', 'optin-monster-api' ); ?></p>
		</div>
		<div class="divider"></div>

		<div class="omapi-feature-box omapi-clear">
			<div class="omapi-feature-image"><img src="<?php echo plugins_url( '/assets/css/images/features-builder.png', OMAPI_FILE ); ?>" alt="<?php esc_attr_e( 'OptinMonster Form Builder', 'optin-monster-api' ); ?>" /></div>
			<div class="omapi-feature-text">
				<h3><?php esc_html_e( 'Build high converting forms in minutes, not hours', 'optin-monster-api' ); ?></h3>
				<p><?php esc_html_e( 'Create visually stunning optin forms that are optimized for the highest conversion rates.', 'optin-monster-api' ); ?></p>
				<p><?php esc_html_e( 'You can create various types of optin forms such as lightbox popups, floating bars, slide-ins, and more.', 'optin-monster-api' ); ?></p>
			</div>
		</div>

		<div class="omapi-feature-box omapi-clear">
			<div class="omapi-feature-text">
				<h3><?php esc_html_e( 'Convert abandoning visitors into subscribers & customers', 'optin-monster-api' ); ?></h3>
				<p><?php esc_html_e( 'Did you know that over 70% of visitors who abandon your website will never return?', 'optin-monster-api' ); ?></p>
				<p><?php esc_html_e( 'Our exit-intent&reg; technology detects user behavior and prompts them with a targeted campaign at the precise moment they are about to leave.', 'optin-monster-api' ); ?></p>
			</div>
			<div class="omapi-feature-image"><img src="<?php echo plugins_url( '/assets/css/images/features-exit-animated.gif', OMAPI_FILE ); ?>" alt="<?php esc_attr_e( 'OptinMonster Exit Intent Technology', 'optin-monster-api' ); ?>" /></div>
		</div>

		<div class="omapi-feature-box omapi-clear">
			<div class="omapi-feature-image"><img src="<?php echo plugins_url( '/assets/css/images/features-ab-testing.png', OMAPI_FILE ); ?>" alt="<?php esc_attr_e( 'OptinMonster uses smart A/B testing', 'optin-monster-api' ); ?>" /></div>
			<div class="omapi-feature-text">
				<h3><?php esc_html_e( 'Easily A/B test your ideas and increase conversions', 'optin-monster-api' ); ?></h3>
				<p><?php esc_html_e( 'A/B testing helps you eliminate the guess work and make data-driven decisions on what works best.', 'optin-monster-api' ); ?></p>
				<p><?php esc_html_e( 'Try different content, headlines, layouts, and styles to see what converts best with our smart and easy to use A/B testing tool.', 'optin-monster-api' ); ?></p>
			</div>
		</div>

		<div class="omapi-feature-box omapi-clear">
			<div class="omapi-feature-text">
				<h3><?php esc_html_e( 'Measuring your results has never been easier', 'optin-monster-api' ); ?></h3>
				<p><?php esc_html_e( 'Get the stats that matter and take action to imrpove your lead-generation strategy.', 'optin-monster-api' ); ?></p>
				<p><?php esc_html_e( 'Our built-in analytics help you analyze clicks, views, and overall conversion rates for each page and optin form.', 'optin-monster-api' ); ?></p>
			</div>
			<div class="omapi-feature-image"><img src="<?php echo plugins_url( '/assets/css/images/features-analytics.png', OMAPI_FILE ); ?>" alt="<?php esc_attr_e( 'OptinMonster Segmenting with Page Level Targeting', 'optin-monster-api' ); ?>" /></div>
		</div>

		<div class="omapi-single-cta">
			<a class="button button-omapi-green button-hero" href="<?php echo $data['button_link']; ?>" target="_blank"><?php echo $data['button_text']; ?></a>
		</div>
		<div class="omapi-well welcome-featuredin">
			<h2><?php esc_html_e( 'OptinMonster has been featured in:', 'optin-monster-api' ); ?></h2>
			<img src="<?php echo plugins_url( '/assets/css/images/featured-logos.png', OMAPI_FILE ); ?>" alt="<?php esc_attr_e( 'OptinMonster has been featured in Inc., Forbes, VB, Yahoo, Entrepreneur, Huff Post, and more', 'optin-monster-api' ); ?>" />
		</div>

		<div class="omapi-reviews">
			<div class="omapi-well omapi-mini-well">
				<div class="omapi-talking-head">
					<img src="<?php echo plugins_url( '/assets/css/images/michaelstelzner.png', OMAPI_FILE ); ?>">
				</div>
				<p class="ompai-review">
					<?php _e( '<strong>We added more than 95,000 names to our email list</strong> using OptinMonster\'s Exit Intent&reg; technology. We strongly recommend it!', 'optin-monster-api' ); ?>
					<span class="reviewer-name"><?php esc_html_e( 'Michael Stelzner', 'optin-monster-api' ); ?></span>
					<span class="reviewer-title"><?php esc_html_e( 'Founder Social Media Examiner', 'optin-monster-api' ); ?></span>
				</p>
			</div>
			<div class="omapi-well omapi-mini-well">
				<div class="omapi-talking-head">
					<img src="<?php echo plugins_url( '/assets/css/images/neilpatel.png', OMAPI_FILE ); ?>">
				</div>
				<p class="ompai-review">
					<?php _e( 'Exit Intent&reg; popups have doubled my email opt-in rate. <strong>When done right, you can see an instant 10% lift on driving sales.</strong> I highly recommend that you use OptinMonster for growing your email list and sales.', 'optin-monster-api' ); ?>
					<span class="reviewer-name"><?php esc_html_e( 'Neil Patel', 'optin-monster-api' ); ?></span>
					<span class="reviewer-title"><?php esc_html_e( 'Founder QuickSprout', 'optin-monster-api' ); ?></span>
				</p>
			</div>
			<div class="omapi-well omapi-mini-well">
				<div class="omapi-talking-head">
					<img src="<?php echo plugins_url( '/assets/css/images/matthewwoodward.png', OMAPI_FILE ); ?>">
				</div>
				<p class="ompai-review">
					<?php _e( 'OptinMonster played a critical role in increasing my email optin conversion rate by 469%. In real numbers, <strong>that is the difference between $7,765 and $47,748 per month.</strong>', 'optin-monster-api' ); ?>
					<span class="reviewer-name"><?php esc_html_e( 'Matthew Woodward', 'optin-monster-api' ); ?></span>
					<span class="reviewer-title"><?php esc_html_e( 'SEO Expert', 'optin-monster-api' ); ?></span>
				</p>
			</div>
		</div>

		<div class="omapi-well welcome-connect">
			<p><?php esc_html_e( 'Join the thousands of users who use OptinMonster to convert abandoning website visitors into subscribers and customers.', 'optin-monster-api' ); ?></p>
			<div class="actions">
				<a class="button button-omapi-green button-hero" href="<?php echo $data['button_link']; ?>" target="_blank"><?php echo $data['button_text']; ?></a>
				<span class="or">or</span>
				<a class="button button-omapi-gray button-hero omapi-authorize-button" href="<?php echo $data['api_link']; ?>"><?php esc_html_e( 'Connect Your Account', 'optin-monster-api' ) ?></a>
			</div>
		</div>

	</div>

	<form style="display:none" method="post" action="<?php echo esc_url( add_query_arg( 'optin_monster_api_view', 'api', $data['api_link'] ) ); ?>">
		<?php wp_nonce_field( 'omapi_nonce_api', 'omapi_nonce_api' ); ?>
		<input type="hidden" name="omapi_panel" value="api" />
		<input type="hidden" name="omapi_save" value="true" />
		<input type="password" id="omapi-field-apikey" name="omapi[api][apikey]" />
	</form>
</div>
