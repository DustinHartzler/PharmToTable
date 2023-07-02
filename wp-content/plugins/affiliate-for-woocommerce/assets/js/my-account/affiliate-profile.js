jQuery(function(){
	const { _x } = wp.i18n;
	let homeURL          = afwcProfileParams.homeURL || '';
	let pName            = afwcProfileParams.pName;
	let isPrettyReferral = afwcProfileParams.isPrettyReferralEnabled || 'no';

	jQuery('#afwc_resources_wrapper').on('change, keyup', '#afwc_affiliate_link', function() {
		let path                  = jQuery(this).val() || '';
		// Remove the slash at the end of pageURL.
		let pageURL               = (homeURL + path).replace(/\/$/, "");
		let affiliateIdentifier   = jQuery('#afwc_id_change_wrap code').text();
		let affiliateReferralLink = '';
		if ( -1 === pageURL.indexOf( '?' ) ) {
			affiliateReferralLink = pageURL + ( 'yes' == isPrettyReferral ? ( '/' + pName + '/' + affiliateIdentifier ) : ( '?'+ pName + '=' + affiliateIdentifier ) );
		} else {
			if ( 'yes' == isPrettyReferral ) {
				affiliateReferralLink = ( pageURL.substring( 0, pageURL.indexOf('?') ) ).replace(/\/$/, "") + '/' + pName + '/' + affiliateIdentifier + '/?'+ ( pageURL.substring( pageURL.indexOf('?') + 1 ) );
			} else {
				affiliateReferralLink = pageURL + '&' + pName+'='+affiliateIdentifier;
			}
		}
		jQuery('#afwc_generated_affiliate_link').text( affiliateReferralLink );
	});

	jQuery('#afwc_account_form').on('submit', function(e) {
		e.preventDefault();
		if( ! afwcProfileParams.saveAccountDetailsURL ) {
			return;
		}
		let formData      = jQuery(this).serialize();
		let statusElement = jQuery(this).find('.afwc_save_account_status');
		statusElement.removeClass('afwc_status_yes afwc_status_no').addClass('afwc_status_spinner');
		jQuery.ajax({
			url: afwcProfileParams.saveAccountDetailsURL,
			type: 'post',
			dataType: 'json',
			data: {
				security: afwcProfileParams.saveAccountSecurity || '',
				form_data: decodeURIComponent( formData )
			},
			success: function( response ) {
				if ( response.success ) {
					if ( 'yes' === response.success ) {
						statusElement.removeClass('afwc_status_spinner').addClass('afwc_status_yes');
					} else if ( 'no' === response.success ) {
						statusElement.removeClass('afwc_status_spinner').addClass('afwc_status_no');
						if ( response.message ) {
							alert( response.message );
						}
					}
				}
			}
		});
	});

	jQuery('#afwc_resources_wrapper').on( 'click', '#afwc_change_identifier', function( e ) {
		e.preventDefault();
		jQuery('#afwc_id_change_wrap, #afwc_id_save_wrap').toggle();
	});

	// If affiliate identifier change is canceled.
	jQuery('#afwc_resources_wrapper').on( 'click', '#afwc_cancel_change_identifier', function( e ) {
		e.preventDefault();
		jQuery('#afwc_ref_url_id').val( jQuery('#afwc_id_change_wrap').find('code').text() || ''); // Revert the input value.
		jQuery('#afwc_id_change_wrap').show();
		jQuery('#afwc_id_save_wrap').hide();
		jQuery('#afwc_id_msg').hide();
	});

	jQuery('#afwc_resources_wrapper').on( 'click', '#afwc_save_identifier', function( e ) {
		e.preventDefault();
		jQuery( '#afwc_id_msg' ).hide();
		let savedIdentifier    = afwcProfileParams.savedAffiliateIdentifier;
		let referralIdentifier = jQuery('#afwc_ref_url_id').val() || '';

		if ( afwcProfileParams.saveReferralURLIdentifier ) {
			if ( '' == referralIdentifier ) {
				jQuery( '#afwc_id_msg' ).html( _x( 'Identifier cannot be empty.', 'referral identifier validation message', 'affiliate-for-woocommerce' ) ).addClass( 'afwc_error' ).show();
				return;
			} else {
				if ( savedIdentifier === referralIdentifier ) {
					jQuery( '#afwc_id_msg' ).html( _x( 'You are already using this identifier.', 'referral identifier validation message', 'affiliate-for-woocommerce' ) ).addClass( 'afwc_error' ).show();
					return;
				}

				if ( false === new RegExp(afwcProfileParams.identifierRegexPattern || '', 'g').test(referralIdentifier) ) {
					jQuery( '#afwc_id_msg' )
						.html( _x( 'Invalid identifier. It should be a combination of alphabets and numbers, but the number should not be in the first position.', 'referral identifier validation message', 'affiliate-for-woocommerce' ) )
						.addClass( 'afwc_error' ).show();
					return;
				}

				jQuery('#afwc_save_id_loader').show();
				// Ajax call to save ID.
				jQuery.ajax({
					url: afwcProfileParams.saveReferralURLIdentifier,
					type: 'post',
					dataType: 'json',
					data: {
						security: afwcProfileParams.saveIdentifierSecurity || '',
						ref_url_id: referralIdentifier
					},
					success: function( response ) {
						jQuery('#afwc_save_id_loader').hide();
						if ( response.success ) {
							if ( 'yes' === response.success ) {
								jQuery('#afwc_id_change_wrap, #afwc_id_save_wrap').toggle();
								if( response.message ) {
									jQuery( '#afwc_id_msg' ).html( response.message ).addClass( 'afwc_success' ).removeClass( 'afwc_error' ).show();
								}
								if( jQuery('#afwc_id_change_wrap').length > 0 ) {
									jQuery('#afwc_id_change_wrap').find('code').text(referralIdentifier);
								}
								if( jQuery('.afwc_ref_id_span').length > 0 ) {
									jQuery('.afwc_ref_id_span').text(referralIdentifier);
								}
								if( jQuery('#afwc_affiliate_link_label').length > 0 && homeURL && pName ) {
									jQuery('#afwc_affiliate_link_label').text( ( 'yes' == isPrettyReferral ) ? ( `${homeURL}${pName}/${referralIdentifier}` ) : ( `${homeURL}?${pName}=${referralIdentifier}` ) );
								}
							} else if ( 'no' === response.success && response.message ) {
								jQuery( '#afwc_id_msg' ).html( response.message ).addClass( 'afwc_error' ).removeClass( 'afwc_success' ).show();
							}
						}
						setTimeout( function(){ jQuery( '#afwc_id_msg' ).hide(); }, 10000);
					}
				});
			}
		}
	})
});

function afwc_copy_affiliate_link_coupon( obj ) {
	let element = jQuery("<input>");
	jQuery("body").append(element);
	element.val(jQuery(obj).text()).select();
	if( navigator && navigator.clipboard ) {
		navigator.clipboard.writeText(element.val());
	}
	element.remove();
}
