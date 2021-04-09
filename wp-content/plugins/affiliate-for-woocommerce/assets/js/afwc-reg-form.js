/* phpcs:ignoreFile */
jQuery(function(){
	// form validation
	// validate password and confirm password
	var password = jQuery("#afwc_reg_password")
	  , confirm_password = jQuery("#afwc_reg_confirm_password");

	function validatePassword(){
		if (password.val() != confirm_password.val()) {
			confirm_password[0].setCustomValidity( afwc_reg_pre_data.password_error );
		} else {
			confirm_password[0].setCustomValidity('');
		}
	}
	jQuery(password).on('change', function(){
		validatePassword();
	});
	jQuery(confirm_password).on('keyup', function(){
		validatePassword();
	});

	// URL validation
	function validWebsiteUrl(){
		var urlfield = jQuery("#afwc_reg_website");
		var url = urlfield.val();
		if ( url != '' ) {
			var regx = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/g;
			if ( !regx.test(url) ) {
				urlfield[0].setCustomValidity( afwc_reg_pre_data.invalid_url );
			} else {
				urlfield[0].setCustomValidity("");
			}
		}
	}
	jQuery("#afwc_reg_website").on('keyup', function(){
		validWebsiteUrl();
	});

	// Form submission
	jQuery(document).on('submit', '#afwc_registration_form', function (e) {
		e.preventDefault();
		var form = jQuery(this);
		jQuery(form).find('input[type="submit"]').attr('disabled', true);
		var formData = {};
		jQuery.each(form.serializeArray(), function() {
			formData[this.name] = this.value;
		});
		if ( formData['afwc_hp_email'] !== '' ) {
			jQuery(form).find('.afwc_reg_message').addClass('success').html( afwc_reg_pre_data.hp_success_msg ).show();
			jQuery(form)[0].reset();
			return;
		}
		formData['action'] = 'afwc_register_user';
		formData['security'] = jQuery('#afwc_registration').val();
		jQuery(form).find('.afwc_reg_loader').show().css('display', 'inline-block');
		var actionUrl = afwc_reg_pre_data.ajaxurl;
		jQuery.ajax({
			type: 'POST',
			url: actionUrl,
			data: formData,
			dataType: 'json',
			success: function (response) {
				jQuery(form).find('.afwc_reg_loader').hide();
				if ( response.status && 'success' == response.status ) {
					jQuery(form).find('.afwc_reg_message').addClass('success').html( response.message ).show();
				} else {
					jQuery(form).find('.afwc_reg_message').addClass('error').html( response.message ).show();
				}
				jQuery(form)[0].reset();
				jQuery(form).find('input[type="submit"]').attr('disabled', false);
			},
			error: function (err) {
				console.log(err, 'error');
				jQuery(form).find('input[type="submit"]').attr('disabled', false);
			},
		});
	});
});
