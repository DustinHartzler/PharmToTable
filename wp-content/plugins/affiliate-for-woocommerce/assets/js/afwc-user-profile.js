/* phpcs:ignoreFile */
jQuery(function(){

	if (jQuery('#afwc_review_pending').length > 0) {
		jQuery('#afwc_review_pending').parent().parent().next().hide();
	}

	jQuery(document).on( 'click', '.afwc_actions', function(e){
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

	var select2_args = {
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
	jQuery('#afwc_user_tags').selectWoo(select2_args);

});
