/* phpcs:ignoreFile */
jQuery(function(){
	const { _x } = wp.i18n;
	// form validation
	// validate password and confirm password
	let password = jQuery("#afwc_reg_password"),
	    confirmPassword = jQuery("#afwc_reg_confirm_password");

	function validatePassword(){
		if (password.val() != confirmPassword.val()) {
			confirmPassword[0].setCustomValidity( _x( 'Passwords do not match.', 'Registration form error message for password mismatch', 'affiliate-for-woocommerce' ) );
		} else {
			confirmPassword[0].setCustomValidity('');
		}
	}
	password.on('change', function(){
		validatePassword();
	});
	confirmPassword.on('keyup', function(){
		validatePassword();
	});

	// Form submission
	jQuery(document).on('submit', '#afwc_registration_form', async function (e) {
		e.preventDefault();
		let form = jQuery(this);
		form.find('input[type="submit"]').attr('disabled', true);
		var formData = {};
		jQuery.each(form.serializeArray(), function() {
			formData[this.name] = this.value;
		});
		if ('' !== formData['afwc_hp_email']) {
			form.find('.afwc_reg_message').addClass('success').removeClass('error').html( _x( 'User registered successfully.', 'Registration form success message for user register', 'affiliate-for-woocommerce' ) ).show();
			form[0].reset();
			return;
		}
		formData['action'] = 'afwc_register_user';
		formData['security'] = jQuery('#afwc_registration').val();
		form.find('.afwc_reg_loader').css('display', 'inline-block');
		await jQuery.ajax({
			type: 'POST',
			url: afwcRegistrationFormParams.ajaxurl || '',
			data: formData,
			dataType: 'json',
			success: function (response) {
				if ( response.status && 'success' == response.status ) {
					form.find('.afwc_reg_message').addClass('success').removeClass('error');
				} else {
					form.find('.afwc_reg_message').addClass('error').removeClass('success');
				}
				if( ! response.invalidFieldId ) {
					form[0].reset();
				}
				if(response.message){
					let msgPrefix = response.invalidFieldId ? _x( 'Error: ', 'Prefix for affiliate registration error message', 'affiliate-for-woocommerce' ) : '';
					form.find('.afwc_reg_message').html( msgPrefix + response.message ).show();
				}
			},
			error: function (err) {
				console.log('[ERROR]', err);
			},
		});
		form.find('.afwc_reg_loader').hide();
		form.find('input[type="submit"]').attr('disabled', false);
	});
});
