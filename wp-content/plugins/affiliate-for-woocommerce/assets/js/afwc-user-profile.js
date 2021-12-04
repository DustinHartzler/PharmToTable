/* phpcs:ignoreFile */
jQuery(function(){

	const { __ } = wp.i18n;

	if (jQuery('#afwc_review_pending').length > 0) {
		jQuery('#afwc_review_pending').parent().parent().next().hide();
	}

	let afwcSec = jQuery('.afwc-settings-wrap');
	jQuery(document).on( 'change', 'input[name="afwc_is_affiliate"]', function() {
		if( ! jQuery( this ).is( ':checked' ) ){
			alert( __( 'Are you sure you want to remove this user as an affiliate? Doing this will remove this affiliate and its entire chain from part of the parent-chain relationship of any other affiliate in a multi-tier commission distribution. This change is irreversible.', 'affiliate-for-woocommerce') );
			afwcSec.find('table#afwc tr:not(#afwc_is_affiliate_row)').hide('slow');
			afwcSec.find('.afwc-update-desc').show('slow');
		} else {
			jQuery(this).closest('td').find('p.description').hide( 'slow' );
			afwcSec.find('table#afwc tr:not(#afwc_is_affiliate_row)').show('slow');
		}
	});

	jQuery(document).on( 'click', '.afwc_actions', function( e ) {
		e.preventDefault();
		var action = jQuery(this).data('afwc_action') || '';
		if ( 'approve' == action ) {
			jQuery('input[name="afwc_is_affiliate"]').prop('checked', true);
		} else if ( 'disapprove' == action ) {
			jQuery('input[name="afwc_is_affiliate"]').prop('checked', false);
		}
		jQuery('#afwc_review_pending').val('');
		jQuery('input:submit.button-primary').trigger('click');
	});

	var tagsSelect2Args = {
		allowClear:  jQuery( this ).data( 'allow_clear' ) ? true : false,
		placeholder: jQuery( this ).data( 'placeholder' ),
		minimumInputLength: 3,
		escapeMarkup: function( m ) {
			return m;
		},
		tags:true,
		ajax: {
			url:         ajaxurl,
			dataType:    'json',
			delay:       1000,
			data:        function( params ) {
				return {
					term:     params.term,
					action:   'afwc_json_search_tags',
					security: profile_js_params.afwc_security,
					exclude:  jQuery( this ).data( 'exclude' )
				};
			},
			processResults: function( data ) {
				var terms = [];
				if ( data ) {
					jQuery.each( data, function( id, text ) {
						terms.push({
							id: id,
							text: text
						});
					});
				}
				return {
					results: terms
				};
			},
			cache: true
		}
	};
	jQuery('#afwc_user_tags').selectWoo(tagsSelect2Args);

	var affiliateSelect2Args = {

		placeholder: jQuery( this ).data( 'placeholder' ) || '',
		allowClear:  true,
		escapeMarkup: function( m ) {
			return m;
		},
		
		ajax: {
			url:         ajaxurl,
			dataType:    'json',
			delay:       1000,
			data:        function( params ) {
				return {
					term:     params.term,
					action:   'afwc_json_search_parent_affiliates',
					security: profile_js_params.afwc_security,
					user_id:  jQuery( this ).data( 'user' )
				};
			},
			processResults: function( data ) {
				var terms = [];
				if ( data ) {
					jQuery.each( data, function( id, text ) {
						terms.push({
							id: id,
							text: text
						});
					});
				}
				return {
					results: terms
				};
			},
			cache: true
		}
	};
	jQuery('#afwc_parent_id').selectWoo(affiliateSelect2Args);
});
