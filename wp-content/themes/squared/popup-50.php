<!-- popup -->
<div class="popup-container reveal-modal" id="myModal">
	<div class="popup" id="register_popup">
		<!-- book image-->
		<img class="book-pop" src="<?php bloginfo('template_url'); ?>/img/404/book.png" id="book-popup"/>
		<div class="pop-right">
			<!-- pop header -->
			<div id="pop-head">
				<p class="head">The 50 Most Useful<br />WordPress Plugins</p>
				<p class="sub-headline">(Including Ones I Personally Use)</p>
			</div>
			<div id="ck_success_msg" style="display:none;">
			  <p>Success! Now check your email to confirm your subscription.</p>
			</div>
			<!--  Form starts here  -->
			<form id="ck_subscribe_form" class="ck_subscribe_form" action="https://app.convertkit.com/landing_pages/20589/subscribe" data-remote="true">
			  <input type="hidden" value="{&quot;form_style&quot;:&quot;full&quot;,&quot;embed_style&quot;:&quot;modal&quot;,&quot;embed_trigger&quot;:&quot;scroll_percentage&quot;,&quot;scroll_percentage&quot;:&quot;70&quot;,&quot;delay_seconds&quot;:&quot;10&quot;,&quot;display_position&quot;:&quot;br&quot;,&quot;display_devices&quot;:&quot;all&quot;,&quot;days_no_show&quot;:&quot;15&quot;,&quot;converted_behavior&quot;:&quot;custom&quot;}" id="ck_form_options">
			  <input type="hidden" name="id" value="20589" id="landing_page_id">
			  <div class="ck_errorArea">
				<div id="ck_error_msg" style="display:none">
				  <p>There was an error submitting your subscription. Please try again.</p>
				</div>
			  </div>
			  <div class="ck_control_group ck_first_name_field_group">
				<input type="text" name="first_name" class="ck_first_name" id="ck_firstNameField" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your first name here'"  placeholder="Enter your first name here" />
			  </div>
			  <div class="ck_control_group ck_email_field_group">
				<input type="text" name="email" class="ck_email_address" id="ck_emailField"  onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your best email here'" placeholder="Enter your best email here" required/>
			  </div>
			  <div class="ck_control_group ck_captcha2_h_field_group ck-captcha2-h" style="position: absolute !important;left: -999em !important;">
				<label class="ck_label" for="ck_captcha2_h">We use this field to detect spam bots. If you fill this in, you will be marked as a spammer.</label>
				<input type="text" name="captcha2_h" class="ck-captcha2-h" id="ck_captcha2_h">
			  </div>

				<label class="ck_checkbox" style="display:none;">
				  <input class="optIn ck_course_opted" name="course_opted" type="checkbox" id="optIn" checked />
				  <span class="ck_opt_in_prompt">I'd like to receive the free email course.</span>
				</label>

			  <button class="subscribe_button ck_subscribe_button button fields" id="ck_subscribe_button">
				Send My eBook Now!
			  </button>
			  <span class="ck_guarantee">

				  <a class="ck_powered_by" href="https://yourwebsiteengineer.com/convertkit">Powered by ConvertKit</a>
			  </span>
			</form>
		</div>
		<a class="close-reveal-modal"><img src="<?php bloginfo('template_url'); ?>/img/404/ico-close.png" alt=""/></a>
		<div class="cl"></div>
	</div>
</div>
<!-- /end popup -->
