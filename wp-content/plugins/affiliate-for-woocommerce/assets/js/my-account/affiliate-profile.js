/* phpcs:ignoreFile */
jQuery(function(){
	const { _x } = wp.i18n;
	let homeURL          = afwcProfileParams.homeURL || '';
	let pName            = afwcProfileParams.pName;
	let isPrettyReferral = afwcProfileParams.isPrettyReferralEnabled || 'no';

	jQuery('#afwc_resources_wrapper').on('change, keyup', '#afwc_affiliate_link', afwcGenerateLink);

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
					jQuery('#afwc_id_msg').html(afwcProfileParams.identifierPatternValidationErrorMessage || '').addClass('afwc_error').show();
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
								let affiliateLinkElement = jQuery('#afwc_affiliate_link_label');
								if( affiliateLinkElement.length > 0 && homeURL ) {
									let refURL = afwcGetAffiliateURL(affiliateLinkElement.attr('data-redirect') || homeURL);
									affiliateLinkElement.text(refURL).attr('data-ctp', refURL);
								}
								afwcGenerateLink();
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

	function afwcGetAffiliateURL(targetURL = ''){
		if(!targetURL){
			return '';
		}
		targetURL = targetURL.replace(/\/$/, "");
		let affiliateIdentifier   = jQuery('#afwc_id_change_wrap code').text();
		let generatedLink = '';
		
		if ( -1 === targetURL.indexOf( '?' ) ) {
			generatedLink = targetURL + ( 'yes' == isPrettyReferral ? ( '/' + pName + '/' + affiliateIdentifier + '/' ) : ( '/?'+ pName + '=' + affiliateIdentifier ) );
		} else {
			if ( 'yes' == isPrettyReferral ) {
				generatedLink = ( targetURL.substring( 0, targetURL.indexOf('?') ) ).replace(/\/$/, "") + '/' + pName + '/' + affiliateIdentifier + '/?'+ ( targetURL.substring( targetURL.indexOf('?') + 1 ) );
			} else {
				generatedLink = targetURL + '&' + pName+'='+affiliateIdentifier;
			}
		}

		return generatedLink;
	}

	function afwcGenerateLink(){
		let path                  = jQuery('#afwc_affiliate_link').val() || '';
		affiliateReferralLink = homeURL ? afwcGetAffiliateURL( homeURL + path ) : '';
		jQuery('#afwc_generated_affiliate_link').text(affiliateReferralLink).attr('data-ctp', affiliateReferralLink);
	}
});
