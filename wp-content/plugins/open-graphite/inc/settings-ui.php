<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$the_ogvar 			= open_graphite_vars();
$ogoptions 			= get_option( 'openg_settings' ); 
$buy_link 			= 'https://rocketapps.com.au/';
$login_link 		= 'https://rocketapps.com.au/?login=yes';
$utp_notice 		= '<a href="https://rocketapps.com.au/product/open-graphite-pro/?origin=open-graphite" target="_blank" rel="noopener" class="og-pro">' . _('Pro') . '</a>';
$disable_jetpack_og	= isset($ogoptions['disable_jetpack_og']) ? $ogoptions['disable_jetpack_og'] : '';
?>

<!--/ Start Wrap /-->
<div class="wrap">

	<h2><?php _e( 'Open Graphite Settings', 'open-graphite' ); ?></h2>
	<?php // show saved options message
	if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') { ?>
		<div id="message" class="updated notice notice-success is-dismissible">
			<p><strong><?php _e( 'Options saved', 'open-graphite' ); ?></strong></p>
			
		</div>
	<?php } ?>

	<p><?php _e( 'Customise the default open graph settings for the homepage, posts and pages.', 'open-graphite' ); ?></p>


	<?php if(function_exists('ftfixer_menu')) { ?>
		<div class="warning">
			<p><span class="dashicons dashicons-warning"></span><?php _e( 'You appear to have the <em>Thumbnail Fixer for Facebook</em> plugin activated which will cause conflicts with this plugin. Please deactivate it now.', 'open-graphite' ); ?></p>
		</div>
	<?php } ?>

	<?php if(in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins')))) { 
		$yoast_help = admin_url() . 'admin.php?page=open-graphite-help#yoast';
		$yoast_fb_status = WPSEO_Options::get( 'opengraph', '' );
		$yoast_tw_status = WPSEO_Options::get( 'twitter', '' );

		if($yoast_fb_status =='1' || $yoast_tw_status =='1') {
	?>
		<div class="warning">
			<p><span class="dashicons dashicons-warning"></span><?php printf(__( 'You currently have Yoast activated, which already takes care of some social sharing duties. If you prefer to keep using Open Graphite, you will need to disable a couple of Yoast settings. <a href="%1$s">Learn how</a>.', 'open-graphite' ), $yoast_help); ?></p>
		</div>
	<?php } 
	} ?>

	<?php if ( class_exists( 'Jetpack' ) && !$disable_jetpack_og) { 
		$jetpack_link = '#troubleshooting'; ?>
		<div class="warning">
			<p><span class="dashicons dashicons-warning"></span><?php printf(__( 'You currently have Jetpack activated, which already takes care of some social sharing duties. If you prefer to keep using Open Graphite, you will need to go to the <a href="%1$s">troubleshooting</a> section and enable the <code>Avoid Jetpack open graph meta tag conflict</code> checkbox.', 'open-graphite' ), $jetpack_link); ?></p>
		</div>
	<?php
	} ?>

	<form method="post" action="options.php">
		<?php 
			settings_fields( 'openg_settings' );
			$ogoptions 		= get_option( 'openg_settings' ); 
			$openg_objects 	= isset( $ogoptions['objects'] ) ? $ogoptions['objects'] : array();
		?>

		<div class="og-nav">
			<ul>
				<li>
					<a href="#homepage"><?php _e( 'Homepage', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/home.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#facebook"><?php _e( 'Facebook', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/facebook.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#twitter"><?php _e( 'Twitter', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/twitter.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#pinterest"><?php _e( 'Pinterest', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/pinterest.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#slack"><?php _e( 'Slack', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/slack.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#post-types"><?php _e( 'Enabled Post Types', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/layers.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#defaults"><?php _e( 'Defaults', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/settings-2.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#permissions"><?php _e( 'Permissions', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/fingerprint.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#other"><?php _e( 'Other settings', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/sliders.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#previews"><?php _e( 'Previews', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/view.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#debugging"><?php _e( 'Debugging', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/bug.svg', __FILE__ );?>" />
				</li>
				<li>
					<a href="#troubleshooting"><?php _e( 'Troubleshooting', 'open-graphite' ); ?></a>
					<img src="<?php echo plugins_url('../images/settings/circle-slash.svg', __FILE__ );?>" />
				</li>
			</ul>
			<input name="submit" class="button button-primary" value="Save Settings" type="submit" />
			<?php wp_nonce_field( 'save-og-settings','og-stuff' ) ?>
		</div>
		
		<div class="og-wrap">

		<div class="og-settings" id="homepage">
				<?php /* Image */
				$default_image 		= $ogoptions['default_home_image'] ?? '';
				$default_image_id	= attachment_url_to_postid($default_image) ?? ''; 
				$image_attributes   = wp_get_attachment_image_src( $default_image_id, 'full' ) ?? '';
				
				if($default_image) {
					wp_get_attachment_image_src($default_image_id);
					$width = $image_attributes[1];
					$height = $image_attributes[2];
				} ?>
				
				<p class="title">
					<strong><?php _e( 'Homepage', 'open-graphite' ); ?></strong>
					<span><?php _e( 'The image and content that will be shown when your homepage is shared on social media.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/home.svg', __FILE__ );?>" />
				</p>

				<h3 class="no-top-margin"><?php _e( 'Image', 'open-graphite' ); ?><sup>*</sup></h3>
				<p><?php printf( esc_html__( 'It is recommended the image be %1$s or %2$s at very least.', 'open-graphite' ), $the_ogvar['full_dimensions'] , $the_ogvar['min_dimensions']); ?> <a href="https://developers.facebook.com/docs/sharing/webmasters/images/" target="_blank" rel="noopener"><?php _e( 'Learn more', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" /></p>
				
				<?php if($default_image) { ?>
					
					<?php if($width >= $the_ogvar['min_image_width'] && $height >= $the_ogvar['min_image_height'] ) { ?>
					<!--/ All OK /-->
					<?php } else { ?>
						<div class="image-error image-info">
							<span class="dashicons dashicons-warning"></span> 
							<?php printf( esc_html__( 'The image dimensions are too small at %1$s x %2$s. It is recommended the image be %3$s, or %4$s at very least.', 'open-graphite' ), $width, $height, $the_ogvar['min_dimensions'], $the_ogvar['full_dimensions'] ); ?>
						</div>
					<?php } ?>
					
					<div class="og-image-container">
						<a href="<?php echo admin_url() . 'post.php?post=' . $default_image_id . '&action=edit&image-editor'; ?>" class="dashicons dashicons-admin-customizer" target="_blank" title="<?php _e( 'Edit image', 'open-graphite' ); ?>"></a>	
						<a href="<?php echo $default_image; ?>" class="thickbox image-preview">
							<?php echo wp_get_attachment_image($default_image_id, $size = 'medium'); ?>
						</a>
					</div>

					<p class="og-dashicons-yes">
						<?php if($width >= $the_ogvar['min_image_width'] && $height >= $the_ogvar['min_image_height'] ) { ?>
							<img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" />
						<?php } ?>
						<?php printf(__( 'The dimensions of the selected image are %1$s x %2$s.', 'open-graphite' ), $width, $height, $the_ogvar['full_dimensions'], $the_ogvar['min_dimensions']); ?>
					</p>

				<?php } ?>
				
				<div class="browse-for-image">
					<input type="text" name="openg_settings[default_home_image]" id="image_url" class="half" value="<?php if($default_image) { echo $default_image; } ?>" required />
					<input type="button" name="upload-btn" id="upload-btn" class="media-button button button-secondary" value="<?php _e( 'Browse', 'open-graphite' ); ?>">
					
					<script>
					jQuery(function($) {
					$('#upload-btn').click(function(e) {
							e.preventDefault();
							var image = wp.media({ 
								title: 'Browse',
								multiple: false
							}).open()
							.on('select', function(e){
								var uploaded_image = image.state().get('selection').first();
								console.log(uploaded_image);
								var image_url = uploaded_image.toJSON().url;
								var image_id = uploaded_image.toJSON().id;
								$('#image_url').val(image_url);
								$('.og-image-container img').attr('srcset',image_url);
								$('.dashicons-admin-customizer').attr('href','<?php echo admin_url();?>post.php?post=' + image_id + '&action=edit&image-editor');
							});
						});
					});
					</script>
				</div>
			
				<?php 
				
					$default_title			= isset($ogoptions['open_graphite_default_title']) ? $ogoptions['open_graphite_default_title'] : '';
					$title_char_limit		= isset($ogoptions['open_graphite_title_char_limit']) ? $ogoptions['open_graphite_title_char_limit'] : '';
					$description_char_limit	= isset($ogoptions['open_graphite_description_char_limit']) ? $ogoptions['open_graphite_description_char_limit'] : '';
					$fb_access_token		= isset($ogoptions['open_graphite_home_access_token']) ? $ogoptions['open_graphite_home_access_token'] : '';
					$fb_app_ID 				= isset($ogoptions['open_graphite_home_fb_app_id']) ? $ogoptions['open_graphite_home_fb_app_id'] : '';
					$default_description	= isset($ogoptions['open_graphite_default_description']) ? $ogoptions['open_graphite_default_description'] : '';
					$description_char_limit	= isset($ogoptions['open_graphite_description_char_limit']) ? $ogoptions['open_graphite_description_char_limit'] : '';
					$twitter_card_type 		= isset($ogoptions['open_graphite_home_twitter_card_type']) ? $ogoptions['open_graphite_home_twitter_card_type'] : '';
					$twitter_username 		= isset($ogoptions['open_graphite_twitter_username']) ? $ogoptions['open_graphite_twitter_username'] : '';
					$og_roles 				= isset($ogoptions['og_roles']) ? (array) $ogoptions['og_roles'] : array();

					if($description_char_limit) {
						$description_char_limit = $description_char_limit;
					} else {
						$description_char_limit = $the_ogvar['default_desc_chars'];
					}

					if($title_char_limit) {
						$title_char_limit = $title_char_limit;
					} else {
						$title_char_limit = $the_ogvar['default_title_chars'];
					}

					$tagline = get_bloginfo('description');
				?>


				<h3><?php _e( 'Title', 'open-graphite' ); ?><sup>*</sup></h3>
				<p><?php printf( esc_html__( 'The website title that will be shown when your homepage is shared on social media. %1$s characters max.', 'open-graphite' ), $title_char_limit ); ?></p>
		
				
				<input type="text" name="openg_settings[open_graphite_default_title]" id="openg_settings[open_graphite_default_title]" value="<?php if($default_title) { echo esc_html($default_title); } ?>" class="default-title half" required />
				<br />
				<a class="copy-from copy-from-site-title"><?php _e( 'Use the website title', 'open-graphite' ); ?></a>
				<input type="hidden" id="site-title" value="<?php echo esc_html(get_bloginfo('name')); ?>" />
				
				<script>
				jQuery(function($) {
					$('.copy-from-site-title').click(function() {
						$('.default-title').val($('#site-title').val());
					});
				});
				</script>
	
			
				<h3><?php _e( 'Description', 'open-graphite' ); ?><sup>*</sup></h3>
				<p><?php printf( esc_html__( 'A description of your website that will be shown when your homepage is shared on social media. %1$s characters max.', 'open-graphite' ), $description_char_limit ); ?></p>
				

				<input type="text" name="openg_settings[open_graphite_default_description]" id="openg_settings[open_graphite_default_description]" value="<?php if($default_description) { echo esc_html($default_description); } ?>" class="tagline half" required />
				<?php if($tagline) { ?>
				<br />
				<a class="copy-from copy-from-tagline"><?php _e( 'Use the website tagline', 'open-graphite' ); ?></a>
				
				<input type="hidden" id="tagline" value="<?php echo esc_html($tagline); ?>" />
				
				<script>
				jQuery(function($) {
					$('.copy-from-tagline').click(function() {
						$('.tagline').val($('#tagline').val());
					});
				});
				</script>
				<?php } ?>
		
				
				<h3><?php _e( 'Object Type', 'open-graphite' ); ?><sup>*</sup></h3>
				<p><?php _e( 'The object type that best suits your homepage.', 'open-graphite' ); ?></p>
			

				<select id="openg_settings[open_graphite_open_type_homepage_default]" name="openg_settings[open_graphite_open_type_homepage_default]" class="quarter" required>
					<?php $ot = $ogoptions['open_graphite_open_type_homepage_default'] ?? ''; ?>
					<option></option>
					
					<optgroup label="<?php _e( 'Global', 'open-graphite' ); ?>">
						<option value="article"<?php if($ot == "article") { echo " selected"; } ?>><?php _e( 'article', 'open-graphite' ); ?></option>
						<option value="book"<?php if($ot == "book") { echo " selected"; } ?>><?php _e( 'book', 'open-graphite' ); ?></option>
						<option value="books.author"<?php if($ot == "books.author") { echo " selected"; } ?>><?php _e( 'books.author', 'open-graphite' ); ?></option>
						<option value="books.book"<?php if($ot == "books.book") { echo " selected"; } ?>><?php _e( 'books.book', 'open-graphite' ); ?></option>
						<option value="books.genre"<?php if($ot == "books.genre") { echo " selected"; } ?>><?php _e( 'books.genre', 'open-graphite' ); ?></option>
						<option value="profile"<?php if($ot == "profile") { echo " selected"; } ?>><?php _e( 'profile', 'open-graphite' ); ?></option>
						<option value="website"<?php if($ot == "website") { echo " selected"; } ?>><?php _e( 'website', 'open-graphite' ); ?></option>
					</optgroup>

					<optgroup label="<?php _e( 'Music', 'open-graphite' ); ?>">
						<option value="music.album"<?php if($ot == "music.album") { echo " selected"; } ?>><?php _e( 'music.album', 'open-graphite' ); ?></option>
						<option value="music.radio_station"<?php if($ot == "music.radio_station") { echo " selected"; } ?>><?php _e( 'music.radio_station', 'open-graphite' ); ?></option>
						<option value="music.song"<?php if($ot == "music.song") { echo " selected"; } ?>><?php _e( 'music.song', 'open-graphite' ); ?></option>
					</optgroup>

					<optgroup label="<?php _e( 'Video', 'open-graphite' ); ?>">
						<option value="video.episode"<?php if($ot == "video.episode") { echo " selected"; } ?>><?php _e( 'video.episode', 'open-graphite' ); ?></option>
						<option value="video.movie"<?php if($ot == "video.movie") { echo " selected"; } ?>><?php _e( 'video.movie', 'open-graphite' ); ?></option>
						<option value="video.other"<?php if($ot == "video.other") { echo " selected"; } ?>><?php _e( 'video.other', 'open-graphite' ); ?></option>
						<option value="video.tv_show"<?php if($ot == "video.tv_show") { echo " selected"; } ?>><?php _e( 'video.tv_show', 'open-graphite' ); ?></option>
					</optgroup>

					<optgroup label="<?php _e( 'Other', 'open-graphite' ); ?>">
						<option value="business.business"<?php if($ot == "business.business") { echo " selected"; } ?>><?php _e( 'business.business', 'open-graphite' ); ?></option>
						<option value="object"<?php if($ot == "object") { echo " selected"; } ?>><?php _e( 'object', 'open-graphite' ); ?></option>
						<option value="place"<?php if($ot == "place") { echo " selected"; } ?>><?php _e( 'place', 'open-graphite' ); ?></option>
						<option value="product"<?php if($ot == "product") { echo " selected"; } ?>><?php _e( 'product', 'open-graphite' ); ?></option>
						<option value="product.group"<?php if($ot == "product.group") { echo " selected"; } ?>><?php _e( 'product.group', 'open-graphite' ); ?></option>
						<option value="restaurant.menu"<?php if($ot == "restaurant.menu") { echo " selected"; } ?>><?php _e( 'restaurant.menu', 'open-graphite' ); ?></option>
						<option value="restaurant.menu_item"<?php if($ot == "restaurant.menu_item") { echo " selected"; } ?>><?php _e( 'restaurant.menu_item', 'open-graphite' ); ?></option>
					</optgroup><br />
					</select><br />
					
				<?php _e( 'In most cases this is usually set to <strong>website</strong>.', 'open-graphite' ); ?> <a href="https://ogp.me/#types" target="_blank" rel="noopener"><?php _e( 'Learn about object types', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" />
			</div>
				
			<div class="og-settings" id="facebook">

				<p class="title">
					<strong><?php _e( 'Facebook', 'open-graphite' ); ?></strong>
					<span><?php _e( 'Specify an App ID or an Access Token (recommended).', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/facebook.svg', __FILE__ );?>" />
				</p>

				<h3 class="first"><?php _e( 'Facebook App ID', 'open-graphite' ); ?></h3>
				<p><?php _e( 'If you do not know what this is, you probably do not need it.', 'open-graphite' ); ?></p>
				<input type="text" name="openg_settings[open_graphite_home_fb_app_id]" id="openg_settings[open_graphite_home_fb_app_id]" value="<?php if($fb_app_ID) { echo esc_html($fb_app_ID); } ?>" class="app-id-field quarter" /><br />
				<?php if (!$fb_app_ID) { ?>
					<a href="https://developers.facebook.com/apps/" target="_blank" rel="noopener"><?php _e( 'Find your Facebook App ID', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" />
				<?php } else { ?>
					<a href="https://developers.facebook.com/apps/<?php echo $fb_app_ID; ?>/dashboard/" target="_blank" rel="noopener"><?php _e( 'Facebook App Dashboard', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" />
				<?php } ?>

				<div class="access-token-container">
					<h3><?php _e( 'Facebook Access Token', 'open-graphite' ); ?> <span class="beta"><?php _e( 'beta', 'open-graphite' ); ?></span></h3>
					<p><?php _e( 'Automatically push the open graph data of your post to Facebook as soon as it is created or when it is updated.', 'open-graphite' ); ?> <a href="https://rocketapps.com.au/product/open-graphite-pro/#what-does-the-facebook-access-token-do" target="_blank" rel="noopener"><?php _e( 'Learn more', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" /></p>
					<input type="text" name="openg_settings[open_graphite_home_access_token]" id="openg_settings[open_graphite_home_access_token]" value="<?php if($fb_access_token) { echo esc_html($fb_access_token); } ?>" class="half" /><br />
					<?php if (!$fb_access_token) { ?>
						<a href="https://developers.facebook.com/tools/explorer/" target="_blank" rel="noopener"><?php _e( 'Get your Facebook Access Token', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" />
					<?php } else { ?>
						<a href="https://developers.facebook.com/tools/explorer/" target="_blank" rel="noopener"><?php _e( 'Graph API Explorer', 'open-graphite' ); ?></a> <img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" />
					<?php } ?>
				</div>

			</div>

			<div class="og-settings" id="twitter">

				<p class="title">
					<strong><?php _e( 'Twitter', 'open-graphite' ); ?></strong>
					<span><?php _e( 'Your Twitter username and the default card type.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/twitter.svg', __FILE__ );?>" />
				</p>

				<h3 class="first"><?php _e( 'Username', 'open-graphite' ); ?></h3>
				<p><?php _e( 'If you have a Twitter account for this website, enter the @username here.', 'open-graphite' ); ?></p>
				<input type="text" name="openg_settings[open_graphite_twitter_username]" id="openg_settings[open_graphite_twitter_username]" value="<?php if($twitter_username) { echo esc_html($twitter_username); } ?>" class="quarter" placeholder="@username" />

				<h3><?php _e( 'Card type', 'open-graphite' ); ?><sup>*</sup></h3>
				<ul>
					<li><input type="radio" name="openg_settings[open_graphite_home_twitter_card_type]" value="summary" <?php checked( 'summary' == $twitter_card_type); ?> required /><?php _e( 'Summary', 'open-graphite' ); ?></li>
					<li><input type="radio" name="openg_settings[open_graphite_home_twitter_card_type]" value="summary_large_image" <?php checked( 'summary_large_image' == $twitter_card_type); ?> /><?php _e( 'Summary with large image (recommended)', 'open-graphite' ); ?></li>
				</ul>
			</div>

			<div class="og-settings" id="pinterest">
	
				<p class="title">
					<strong><?php _e( 'Pinterest', 'open-graphite' ); ?> <?php echo $utp_notice; ?></strong>
					<span><?php _e( 'Decide how your content behaves with Pinterest.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/pinterest.svg', __FILE__ );?>" />
				</p>
				<?php $pinterest = isset($ogoptions['open_graphite_pinterest']) ? $ogoptions['open_graphite_pinterest'] : ''; ?>
				<ul>
					<li>
						<input type="radio" disabled /> <?php _e( 'Allow Pinterest (allow content to be saved to Pinterest)', 'open-graphite' ); ?> 
					</li>
					<li>
						<input type="radio" disabled /> <?php _e( 'Prevent content from showing as rich pins (all content can still be saved to Pinterest, but it will not show as a rich pin)', 'open-graphite' ); ?> 
					</li>
					<li>
						<input type="radio" disabled /> <?php _e( 'Deny Pinterest (do not allow content to be saved to Pinterest)', 'open-graphite' ); ?>
					</li>
				</ul>
			</div>

			<div class="og-settings" id="slack">
			
				<p class="title">
					<strong><?php _e( 'Slack', 'open-graphite' ); ?> <?php echo $utp_notice; ?></strong>
					<span><?php _e( 'Enhanced Slack sharing.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/slack.svg', __FILE__ );?>" />
				</p>

				<ul>
                    <li>
                        <input type="checkbox" disabled /> <?php _e( 'Enable author name and reading time', 'open-graphite' ); ?>
                    </li>
                </ul>

			</div>
			
	
			<div class="og-settings" id="post-types">

				<p class="title">
					<strong><?php _e( 'Enabled Post Types', 'open-graphite' ); ?></strong>
					<span><?php _e( 'The post types to show the Open Graphite metabox.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/layers.svg', __FILE__ );?>" />
				</p>

				<div class="post-type-list">
				<ul>
				<?php
					$post_types = get_post_types( array (
							'show_ui' => true,
							'show_in_menu' => true,
						), 
							'objects'
						);
						foreach ( $post_types as $post_type ) {
							$post_type_name = $post_type->name;
							$post_types = isset($ogoptions['post_types']) ? $ogoptions['post_types'] : '';
							if($post_type_name == 'post' || $post_type_name == 'page' && $post_type_name !== 'attachment') { 
						?>
						<li>
							<input type="checkbox" name="openg_settings[post_types][]" value="<?php echo $post_type->name; ?>" <?php if ($post_types && in_array($post_type->name, $ogoptions['post_types'])) echo 'checked';?> /> <?php echo $post_type->label; ?>
						</li>
						<?php
						} 
					}
				?>
				<li><input type="checkbox" disabled /> <?php _e( 'Custom Post Types', 'open-graphite' ); ?> <?php echo $utp_notice; ?></li>
				</ul>
				</div>
			</div>
	
			<div class="og-settings" id="defaults">
				<?php 
					$open_graphite_post_default = isset($ogoptions['open_graphite_post_default']) ? $ogoptions['open_graphite_post_default'] : '';
				?>

				<p class="title">
					<strong><?php _e( 'Defaults', 'open-graphite' ); ?> <?php echo $utp_notice; ?></strong>
					<span><?php _e( 'Force these defaults on all post types and pages.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/settings-2.svg', __FILE__ );?>" />
				</p>
				
				<ul>
					<li>
						<input type="checkbox" disabled /> <?php _e( 'Always use the title', 'open-graphite' ); ?> 
					</li>
					
					<li>
						<input type="checkbox" disabled /> <?php _e( 'Always use the excerpt for the description (posts only)', 'open-graphite' ); ?> 
					</li>
					
					<li>
						<input type="checkbox" disabled /> <?php _e( 'Always use the featured image', 'open-graphite' ); ?> 
					</li>
				</ul>
				

				<table class="form-table">
					<tbody>
					
						<tr>
							<th scope="row"><?php _e( 'Always use this object type:', 'open-graphite' ); ?></th>
							<td>
							<select disabled>
								<option></option>
								<option><?php _e( 'Upgrade to pro', 'open-graphite' ); ?></option>
							</select>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e( 'Title character limit:', 'open-graphite' ); ?></th>
							<td>
								<input type="number" min="1" max="300" disabled />
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e( 'Description character limit:', 'open-graphite' ); ?></th>
							<td>
								<input type="number" min="1" max="300" disabled />
							</td>
						</tr>


					</tbody>
				</table>

			</div>

			<div class="og-settings" id="permissions">
				<p class="title">
					<strong><?php _e( 'Permissions', 'open-graphite' ); ?></strong>
					<span><?php _e( 'Roles permitted to access the Open Graphite interface when creating or editing.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/fingerprint.svg', __FILE__ );?>" />
				</p>
				<p><strong><?php _e( 'Allowed roles', 'open-graphite' ); ?></strong></p>
				<ul>
					<li><input type="checkbox" checked /> <?php _e( 'Administrator', 'open-graphite' ); ?></li>
					<?php $roles = wp_roles()->roles;
					// Output checkboxes for each role
					foreach ($roles as $role => $role_details) { ?>
						<?php if($role != 'subscriber' && $role != 'administrator') { ?>
							<li><input type="checkbox" name="openg_settings[og_roles][]" value="<?php echo esc_attr($role); ?>" <?php if ($roles && in_array($role, $og_roles)) echo 'checked';?> /> <?php echo  esc_html($role_details['name']); ?></li>
						<?php } ?>
					<?php }
					?>
				</ul>
				

			</div>

			<div class="og-settings" id="other">
				<p class="title">
					<strong><?php _e( 'Other Settings', 'open-graphite' ); ?> <?php echo $utp_notice; ?></strong>
					<span><?php _e( 'Additional open graph options.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/sliders.svg', __FILE__ );?>" />
				</p>
		
				<strong><?php _e( 'Common options', 'open-graphite' ); ?></strong>
				<ul>
					<li>
						<input type="checkbox" disabled /> <?php _e( 'Force required', 'open-graphite' ); ?>
					</li>
					<li>
						<input type="checkbox" disabled /> <?php _e( 'Enable a description of the open graph image (derived from the ALT attribute)', 'open-graphite' ); ?>
					</li>
					<li>
						<input type="checkbox" disabled /> <?php _e( 'Enable open graph updated time property', 'open-graphite' ); ?>
					</li>
					<li>
						<input type="checkbox" disabled /> <?php _e( 'Disable the quality indicators admin column', 'open-graphite' ); ?>
					</li>
				</ul>
				

			</div>

			<div class="og-settings" id="previews">
				<p class="title">
					<strong><?php _e( 'Previews', 'open-graphite' ); ?> <?php echo $utp_notice; ?></strong>
					<span><?php _e( 'See how these social networks view your homepage.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/view.svg', __FILE__ );?>" />
				</p>
				<?php require_once('preview-links.php');?>
			</div>
			
			<div class="og-settings" id="debugging">
				<p class="title">
					<strong><?php _e( 'Debugging', 'open-graphite' ); ?></strong>
					<span><?php _e( 'Test your page using official and 3rd party online tools.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/bug.svg', __FILE__ );?>" />
				</p>

				<p>
					<a href="https://developers.facebook.com/tools/debug/sharing/?q=<?php echo home_url(); ?>" target="_blank" rel="noopener" class="og-button"><?php _e( 'Facebook', 'open-graphite' ); ?><img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" /></a> 
					<a href="https://cards-dev.twitter.com/validator" target="_blank" rel="noopener" class="og-button"><?php _e( 'Twitter', 'open-graphite' ); ?><img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" /></a>
					<a href="https://www.linkedin.com/post-inspector/inspect/<?php echo home_url(); ?>" target="_blank" rel="noopener" class="og-button"><?php _e( 'Linkedin Post Inspector', 'open-graphite' ); ?><img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" /></a>
					<a href="http://debug.iframely.com/?uri=<?php echo home_url(); ?>" target="_blank" rel="noopener" class="og-button"><?php _e( 'iFramely', 'open-graphite' ); ?><img src="<?php echo plugins_url('../images/external.svg', __FILE__ );?>" class="og-external" /></a>
				</p>
			</div>

			<div class="og-settings" id="troubleshooting">
				<?php $output_priority 	= isset($ogoptions['output_priority']) ? $ogoptions['output_priority'] : ''; ?>
				<p class="title">
					<strong><?php _e( 'Troubleshooting', 'open-graphite' ); ?></strong>
					<span><?php _e( 'May solve compatibility issues with certain themes and plugins.', 'open-graphite' ); ?></span>
					<img src="<?php echo plugins_url('../images/settings/circle-slash.svg', __FILE__ );?>" />
				</p>

				<strong><?php _e( 'Theme output priority', 'open-graphite' ); ?></strong>
				<ul>
					<li><input type="radio" name="openg_settings[output_priority]" value="low_priority" <?php if($output_priority == 'low_priority') { echo 'checked'; } ?> /><?php _e( 'Low', 'open-graphite' ); ?></li>
					<li><input type="radio" name="openg_settings[output_priority]" value="high_priority" <?php if($output_priority == 'high_priority') { echo 'checked'; } ?> /><?php _e( 'High', 'open-graphite' ); ?></li>
					<li><input type="radio" name="openg_settings[output_priority]" value="auto_priority" <?php if($output_priority == 'auto_priority' || !$output_priority) { echo 'checked'; } ?> /><?php _e( 'Auto (default)', 'open-graphite' ); ?></li>
				</ul>

				<?php if ( class_exists( 'Jetpack' )) { ?>
				<strong><?php _e( 'Jetpack', 'open-graphite' ); ?></strong>
				<ul>
					<li><input type="checkbox" name="openg_settings[disable_jetpack_og]" id="openg_settings[disable_jetpack_og]" value="disable_jetpack_og" <?php if ( ! empty( $ogoptions['disable_jetpack_og'] ) ) { ?>checked<?php } ?> /> <?php _e( 'Avoid Jetpack open graph meta tag conflict.', 'open-graphite' ); ?></li>
				</ul>
				<?php } ?>
			</div>
			
		</div>
	</form>

	<script>
	/* Admin UI */
	if(jQuery(window).width() > 960) {

		jQuery(window).scroll(function(){
			if(jQuery(window).scrollTop() > 80) {
				jQuery('.og-nav').addClass('mover');
				jQuery('.rocket-apps-newsletter').addClass('mover');
			}
		});
		jQuery(window).scroll(function(){
			if(jQuery(window).scrollTop() < 80) {
				jQuery('.og-nav').removeClass('mover');
				jQuery('.rocket-apps-newsletter').removeClass('mover');
			}
		});
	}

	jQuery('.app-id-field').keyup(function() {
		if(jQuery(this).val() == '') {
			jQuery('.access-token-container input').attr('disabled','disabled');
		} else {
			jQuery('.access-token-container input').removeAttr('disabled');
		}
	});
	
	/* Smooth scroll */
	jQuery(document).on('click', 'a[href^="#"]', function(e) {
		var id = jQuery(this).attr('href');
		var jQueryid = jQuery(id);
		if (jQueryid.length === 0) {
			return;
		}
		e.preventDefault();
		var pos = jQueryid.offset().top - 50;
		jQuery('body, html').animate({scrollTop: pos});
	});
	</script>

</div>
<!--/ End Wrap /-->

<!--/ Start Promo /-->
<div class="og-promo">

	<div class="testimonial">
		<img src="<?php echo plugins_url('../images/star.svg', __FILE__ );?>" class="star" />
		<img src="<?php echo plugins_url('../images/star.svg', __FILE__ );?>" class="star" />
		<img src="<?php echo plugins_url('../images/star.svg', __FILE__ );?>" class="star" />
		<img src="<?php echo plugins_url('../images/star.svg', __FILE__ );?>" class="star" />
		<img src="<?php echo plugins_url('../images/star.svg', __FILE__ );?>" class="star" />
		<p><strong><?php _e( '"The best plugin of its kind"', 'open-graphite' ); ?></strong></p>

		<img src="<?php echo plugins_url('../images/matt.jpg', __FILE__ );?>" class="matt" />

		<p class="person">
			<strong><?php _e( 'Matthew Woods', 'open-graphite' ); ?></strong>
			<span><?php _e( 'Tour Administrator & Web Developer', 'open-graphite' ); ?><br /><a href="https://www.perthobservatory.com.au/" target="_blank" rel="noopener"><?php _e( 'Perth Observatory', 'open-graphite' ); ?></a></span>
		</p>
	</div>

	<div class="upgrade">
		<p><strong>Upgrade to Pro</strong></p>
		<ul>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Use on all post types', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Specify automatic defaults', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Mobile & desktop previews', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'WooCommerce product support', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Pinterest specific options', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Enhanced Slack sharing', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Open Graph content indicators', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Apply character limits', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Open graph check tool', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Additional open graph options', 'open-graphite' ); ?></li>
			<li><img src="<?php echo plugins_url('../images/settings/check-circle-2.svg', __FILE__ );?>" /><?php _e( 'Prioritised support response', 'open-graphite' ); ?></li>
		</ul>

		<p>
			<a href="https://rocketapps.com.au/product/open-graphite-pro/?origin=open-graphite" target="_blank" rel="noopener" class="button upgrade-cta"><?php _e( 'Upgrade to Pro Now', 'open-graphite' ); ?></a> 
		</p>
	</div>

</div>
<?php if(!wp_is_mobile()) { ?>
<script>
	jQuery(window).scroll(function(){
        if(jQuery(window).scrollTop() > 52){
            jQuery('.promo').addClass('mover');
        }
        if(jQuery(window).scrollTop() < 52){
            jQuery('.promo').removeClass('mover')
        }
        
    });
</script>
<?php } ?>
<!--/ Start Promo /-->