<div class="option_tabs left">
	<div class="thrive-admin-submenu">
		<a id="thrive-link-general-options" rel="general-options"><?php _e( "Display Options", 'thrive' ); ?></a>
		<a id="thrive-link-focus-areas" rel="focus-areas"><?php _e( "Focus Areas", 'thrive' ); ?></a>
		<a id="thrive-link-custom-code" rel="custom-code"><?php _e( "Custom Code", 'thrive' ); ?></a>
		<a id="thrive-link-social-media" rel="social-media"><?php _e( "Social Media", 'thrive' ); ?></a>
		<div class="clear"></div>
	</div>
</div>
<div class="option_window left">
	<div class="options-container">
		<div id="thrive-admin-container">
			<div class="thrive-admin-subcontainer" id="thrive-admin-subcontainer-general-options">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Page Title", 'thrive' ) ?></label>
						</th>
						<td>
							<input type="radio" value="1" name="thrive_meta_show_post_title"
							       <?php if ( $value_show_post_title == 1 ): ?>checked<?php endif ?> /> Show
							<input type="radio" value="0" name="thrive_meta_show_post_title"
							       <?php if ( $value_show_post_title == 0 ): ?>checked<?php endif ?> /> Hide
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Breadcrumbs", 'thrive' ) ?></label>
						</th>
						<td>
							<input type="radio" value="on" name="thrive_meta_post_breadcrumbs"
							       <?php if ( $value_post_bradcrumbs == "on" ): ?>checked<?php endif ?> /> On
							<input type="radio" value="default" name="thrive_meta_post_breadcrumbs"
							       <?php if ( $value_post_bradcrumbs == "default" ): ?>checked<?php endif ?> /> Default
							<input type="radio" value="off" name="thrive_meta_post_breadcrumbs"
							       <?php if ( $value_post_bradcrumbs == "off" ): ?>checked<?php endif ?> /> Off
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Featured Image", 'thrive' ) ?></label>
						</th>
						<td>
							<input type="radio" value="default" class="thrive_meta_post_featured_image"
							       name="thrive_meta_post_featured_image"
							       <?php if ( $value_post_featured_image == "default" ): ?>checked<?php endif ?> />
							Default
							<input type="radio" value="thumbnail" class="thrive_meta_post_featured_image"
							       name="thrive_meta_post_featured_image"
							       <?php if ( $value_post_featured_image == "thumbnail" ): ?>checked<?php endif ?> />
							Thumbnail
							<input type="radio" value="wide" class="thrive_meta_post_featured_image"
							       name="thrive_meta_post_featured_image"
							       <?php if ( $value_post_featured_image == "wide" ): ?>checked<?php endif ?> /> Wide
							<input type="radio" value="off" class="thrive_meta_post_featured_image"
							       name="thrive_meta_post_featured_image"
							       <?php if ( $value_post_featured_image == "off" ): ?>checked<?php endif ?> /> No
							featured image
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label><?php _e( "Title Background type", 'thrive' ); ?></label>
						</th>
						<td>

							<input type="radio" name="thrive_meta_post_title_bg_type"
							       id="thrive_input_meta_post_title_bg_type_default" class="thrive_shortcode_bg_type"
							       value="default"
							       <?php if ( $value_post_title_bg_type != "image" && $value_post_title_bg_type != "solid" ): ?>checked<?php endif; ?>/>
							<?php _e( "Default", 'thrive' ); ?>
							<input type="radio" name="thrive_meta_post_title_bg_type"
							       id="thrive_input_meta_post_title_bg_type_color" class="thrive_shortcode_bg_type"
							       value="solid"
							       <?php if ( $value_post_title_bg_type == "solid" ): ?>checked<?php endif; ?>/>
							<?php _e( "Solid", 'thrive' ); ?>
							<input type="radio" name="thrive_meta_post_title_bg_type"
							       id="thrive_input_meta_post_title_bg_type_image" class="thrive_shortcode_bg_type"
							       value="image"
							       <?php if ( $value_post_title_bg_type == "image" ): ?>checked<?php endif; ?>/>
							<?php _e( "Image", 'thrive' ); ?>
						</td>
					</tr>
					<tr id="thrive_shortcode_container_bg_solid"
					    <?php if ( $value_post_title_bg_type == "image" ): ?>style="display:none;"<?php endif; ?>>
						<th scope="row">
							<label><?php _e( "Color", 'thrive' ); ?></label>
						</th>
						<td>
							<input type="text" value="<?php echo $value_post_title_bg_solid_color; ?>"
							       class="thrive-color-field"
							       data-default-color="<?php echo $scheme_options['thrivetheme_link_color']; ?>"
							       id="thrive_meta_post_title_color" name="thrive_meta_post_title_bg_solid_color"/>
							<input class='thrive_options pure-button clear-field remove' type="button"
							       value="<?php _e( "Clear", 'thrive' ); ?>" id="thrive_meta_post_title_color_reset"/>
						</td>
					</tr>

					<tr id="thrive_shortcode_container_bg_image"
					    <?php if ( $value_post_title_bg_type != "image" ): ?>style="display:none;"<?php endif; ?>>
						<th scope="row">
							<label><?php _e( "Image", 'thrive' ); ?></label>
						</th>
						<td>
							<span class="color_overlay_label"><?php _e( "Color Overlay", 'thrive' ); ?></span>

							<input type='hidden' name='thrive_meta_post_title_bg_img_trans'
							       value='<?php echo $value_post_title_bg_img_trans; ?>'
							       id='thrive_hidden_featured_title_bg_img_trans'/>
							<input type='hidden' id='thrive_hidden_theme_color'
							       value='<?php echo $scheme_options['thrivetheme_link_color']; ?>'/>
							<select id='thrive_select_featured_title_bg_img_trans'>
								<option value='none'><?php _e( "None", 'thrive' ); ?></option>
								<option value='theme'><?php _e( "Theme color", 'thrive' ); ?></option>
								<option value='custom'><?php _e( "Custom color", 'thrive' ); ?></option>
							</select>
							<div style='display:none;' id='thrive_custom_featured_title_bg_img_trans_container'>
								<input type='text' id='thrive_custom_featured_title_bg_img_trans'
								       data-default-color="<?php echo $value_post_title_bg_img_trans; ?>"
								       value='<?php echo $value_post_title_bg_img_trans; ?>'/></div>

							<br/><br/>
							<div class="clear"></div>
							<input type="radio" class="radio_img_static" value="default"
							       name="thrive_meta_post_title_bg_img_static"
							       <?php if ( $value_post_title_bg_img_static != "static" ): ?>checked<?php endif; ?> /> <?php _e( "Default", 'thrive' ); ?>
							<input type="radio" class="radio_img_static" value="static"
							       name="thrive_meta_post_title_bg_img_static"
							       <?php if ( $value_post_title_bg_img_static == "static" ): ?>checked<?php endif; ?> /> <?php _e( "Static image", 'thrive' ); ?>
							<br/><br/>
							<?php _e( "Show full image height", 'thrive' ); ?>
							<input type="radio" class="radio_img_fullheight" value="off"
							       name="thrive_meta_post_title_bg_img_full_height"
							       <?php if ( $value_post_title_bg_img_full_height != "on" ): ?>checked<?php endif; ?> /> <?php _e( "Off", 'thrive' ); ?>
							<input type="radio" class="radio_img_fullheight" value="on"
							       name="thrive_meta_post_title_bg_img_full_height"
							       <?php if ( $value_post_title_bg_img_full_height == "on" ): ?>checked<?php endif; ?>/> <?php _e( "On", 'thrive' ); ?>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for=""><?php _e( "Display Share Buttons", 'thrive' ) ?></label>
						</th>
						<td>
							<input type="radio" value="default"
							       name="thrive_meta_post_share_buttons"
							       <?php if ( $value_post_share_buttons != "off" ): ?>checked<?php endif ?> /> <?php _e( "Default", 'thrive' ); ?>
							<input type="radio" value="off"
							       name="thrive_meta_post_share_buttons"
							       <?php if ( $value_post_share_buttons == "off" ): ?>checked<?php endif ?> /> <?php _e( "Off", 'thrive' ); ?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Show Floating Icons by Default", 'thrive' ) ?></label>
						</th>
						<td>
							<input type="radio" value="default" name="thrive_meta_post_floating_icons"
							       <?php if ( $value_post_floating_icons == "default" ): ?>checked<?php endif ?> /> <?php _e( "Default", 'thrive' ); ?>
							<input type="radio" value="on" name="thrive_meta_post_floating_icons"
							       <?php if ( $value_post_floating_icons == "on" ): ?>checked<?php endif ?> /> <?php _e( "On", 'thrive' ); ?>
							<input type="radio" value="off" name="thrive_meta_post_floating_icons"
							       <?php if ( $value_post_floating_icons == "off" ): ?>checked<?php endif ?> /><?php _e( "Off", 'thrive' ); ?>
						</td>
					</tr>

				</table>
			</div>

			<div class="thrive-admin-subcontainer" id="thrive-admin-subcontainer-focus-areas">
				<table class="form-table">
					<tr>
						<th scope="row">
							<?php _e( "Top Focus Area", 'thrive' ) ?>
						</th>
						<td>
							<input type='radio' value='default' name='thrive_meta_post_focus_area_top'
							       <?php if ( $value_post_focus_area_top == "default" || $value_post_focus_area_top == "" ): ?>checked<?php endif; ?> />
							Default
							<input type='radio' value='hide' name='thrive_meta_post_focus_area_top'
							       <?php if ( $value_post_focus_area_top == "hide" ): ?>checked<?php endif; ?> /> Hide
							<input type='radio' value='custom' name='thrive_meta_post_focus_area_top'
							       <?php if ( $value_post_focus_area_top != "default" && $value_post_focus_area_top != "hide" && $value_post_focus_area_top != "" ): ?>checked<?php endif; ?> />
							Custom

							<select name='thrive_meta_post_focus_area_top_select'>
								<?php foreach ( $queryFocusAreas->get_posts() as $p ): ?>
									<option value='<?php echo $p->ID ?>'
									        <?php if ( $value_post_focus_area_top == $p->ID ): ?>selected<?php endif; ?>><?php echo $p->post_title; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e( "Bottom Focus Area", 'thrive' ) ?>
						</th>
						<td>
							<input type='radio' value='default' name='thrive_meta_post_focus_area_bottom'
							       <?php if ( $value_post_focus_area_bottom == "default" || $value_post_focus_area_bottom == "" ): ?>checked<?php endif; ?> />
							Default
							<input type='radio' value='hide' name='thrive_meta_post_focus_area_bottom'
							       <?php if ( $value_post_focus_area_bottom == "hide" ): ?>checked<?php endif; ?> />
							Hide
							<input type='radio' value='custom' name='thrive_meta_post_focus_area_bottom'
							       <?php if ( $value_post_focus_area_bottom != "default" && $value_post_focus_area_bottom != "hide" && $value_post_focus_area_bottom != "" ): ?>checked<?php endif; ?> />
							Custom

							<select name='thrive_meta_post_focus_area_bottom_select'>
								<?php foreach ( $queryFocusAreas->get_posts() as $p ): ?>
									<option value='<?php echo $p->ID ?>'
									        <?php if ( $value_post_focus_area_bottom == $p->ID ): ?>selected<?php endif; ?>><?php echo $p->post_title; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<div class="thrive-admin-subcontainer" id="thrive-admin-subcontainer-custom-code">
				<table class="form-table">
					<tr>
						<th sope="row">
							<label for=""><?php _e( "Header Scripts", 'thrive' ) ?></label>
						</th>
						<td>
							<textarea
								name="thrive_meta_post_header_scripts"><?php echo $value_post_header_scripts; ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Opening Body Scripts", 'thrive' ) ?></label><br/>
							<span class="tooltips"
							      title="<?php echo __( 'Enter the scripts you want to load right after the opening body tag on each page. Typically used for Google Tag Manager..  Example: ' . htmlentities( '&lt;script src=\'/path/to/file/script.js\'>&lt;/script>' ) . '. <a href=\'http://thrivethemes.com/tkb_item/load-scriptscustom-css-individual-postspages-thrive-themes/\'> Read more about custom scripts here</a>.' ); ?>"></span>
						</th>
						<td>
							<textarea
								name="thrive_meta_post_body_scripts_top"><?php echo $value_post_body_scripts_top; ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Body Scripts", 'thrive' ) ?></label>
						</th>
						<td>
							<textarea
								name="thrive_meta_post_body_scripts"><?php echo $value_post_body_scripts; ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Custom CSS", 'thrive' ) ?></label>
						</th>
						<td>
							<textarea
								name="thrive_meta_post_custom_css"><?php echo $value_post_custom_css; ?></textarea>
						</td>
					</tr>
				</table>
			</div>

			<div class="thrive-admin-subcontainer" id="thrive-admin-subcontainer-social-media">
				<table class="form-table">
					<tr>
						<th scope="row" colspan="2">
							<?php if ( thrive_get_theme_options( "social_site_meta_enable" ) != 1 ): ?>
								<b><?php _e( "The social media meta option is disabled. If you want to use it please enable it from Thrive Options -> Social Media -> Social Sharing Data", 'thrive' ); ?></b>
								<br/><br/>
							<?php endif; ?>
							<?php echo _e( 'Social media meta data allows you to control the title, image and description of what is shared on the various social media networks for a higher click through rate. When creating this meta data, think about what will elicit the highest click through rate for your content in order to maximise your social media results. <br><br>You must be sure to fill in all fields marked with a * for the meta data to display.', 'thrive' ); ?>
							<?php if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ): ?>
								<?php echo '<br><br>' . __( 'We see you have WP SEO enabled in your account.  By adding social media markup in this section here, your WP SEO social media markup settings will be overridden.', 'thrive' ); ?>
							<?php endif; ?>
						</th>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Title *", 'thrive' ) ?></label>
						</th>
						<td>
							<input value="<?php echo $thrive_meta_social_data_title; ?>" type="text"
							       class="thrive_post_input_large" name="thrive_meta_social_data_title"/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Description *", 'thrive' ) ?></label><br/>
						</th>
						<td>
							<textarea
								name="thrive_meta_social_data_description"><?php echo $thrive_meta_social_data_description; ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Image", 'thrive' ) ?></label>
						</th>
						<td>
							<input value="<?php echo $thrive_meta_social_image; ?>" type="text"
							       class="thrive_post_input_large" name="thrive_meta_social_image"
							       id="thrive_meta_social_image"/>
							<input type="button" class="thrive_options pure-button upload"
							       id="thrive_meta_social_button_upload" value="Upload"/>
							<input type="button" class="thrive_options pure-button clear-field remove"
							       id="thrive_meta_social_button_remove" value="Remove"/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for=""><?php _e( "Twitter Author Username", 'thrive' ) ?></label>
						</th>
						<td>
							<input value="<?php echo $thrive_meta_social_twitter_username; ?>" type="text"
							       class="thrive_post_input_large" name="thrive_meta_social_twitter_username"/>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>


<script type="text/javascript">

	jQuery( document ).ready( function () {

		jQuery( "#thrive_meta_post_title_color" ).wpColorPicker();
		jQuery( "#thrive_meta_post_title_bg_trans_color" ).wpColorPicker();

		jQuery( "#thrive_meta_post_title_color_reset" ).click( function () {
			jQuery( "#thrive_meta_post_title_color" ).wpColorPicker( 'color', "#ffffff" );
		} );
		jQuery( "#thrive_meta_post_title_bg_trans_color_reset" ).click( function () {
			jQuery( "#thrive_meta_post_title_bg_trans_color" ).wpColorPicker( 'color', "#ffffff" );
		} );

		jQuery( ".sc_select_page_section_tpl" ).click( function () {
			var tpl_name = jQuery( this ).attr( 'rel' );
			if ( tpl_name === "custom" ) {
				jQuery( "#thrive_shortcode_options_container" ).show();
			} else {
				jQuery( "#thrive_shortcode_options_container" ).hide();
			}
			jQuery( "#thrive_shortcode_hidden_template_option" ).val( tpl_name );
			jQuery( this ).addClass( 'selPattern' );
			jQuery( this ).siblings( '.sc_select_page_section_tpl' ).removeClass( 'selPattern' );
		} );

		jQuery( ".thrive_shortcode_bg_type" ).click( ThrivePostOptions.display_featured_image_title_bg_type );

		var firstPattern = jQuery( '.patternList' ).find( 'li a' ).first().css( 'background-image' );
		jQuery( '.defaultPattern span' ).css( 'background-image', firstPattern );
		jQuery( '.patternList li a' ).each( function () {
			jQuery( this ).click( function () {
				var imageSource = jQuery( this ).css( 'background-image' );
				jQuery( '.defaultPattern span' ).css( 'background-image', imageSource );
				jQuery( '.patternList' ).hide();
				var temp_pic_url = ThriveThemeUrl + "/images/patterns/" + jQuery( this ).attr( 'id' ) + ".png";
				jQuery( '#thrive_shortcode_option_pattern' ).val( temp_pic_url );
				return false;
			} );
		} );
		jQuery( '#showPattern' ).click( function () {
			jQuery( '.patternList' ).toggle();
			return false;
		} );

		jQuery( "#thrive_shortcode_option_remove_pattern_btn" ).click( function () {
			jQuery( "#thrive_shortcode_option_pattern" ).val( "" );
		} );
		jQuery( "#thrive_shortcode_option_remove_image_btn" ).click( function () {
			jQuery( "#thrive_shortcode_option_image" ).val( "" );
		} );

	} );
</script>