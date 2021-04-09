/* phpcs:ignoreFile */
jQuery(function( $ ){
	function getEnhancedSelectFormatString() {
		return {
			'language': {
				errorLoading: function() {
					// Workaround for https://github.com/select2/select2/issues/4355 instead of i18n_ajax_error.
					return wc_enhanced_select_params.i18n_searching;
				},
				inputTooLong: function( args ) {
					var overChars = args.input.length - args.maximum;

					if ( 1 === overChars ) {
						return wc_enhanced_select_params.i18n_input_too_long_1;
					}

					return wc_enhanced_select_params.i18n_input_too_long_n.replace( '%qty%', overChars );
				},
				inputTooShort: function( args ) {
					var remainingChars = args.minimum - args.input.length;

					if ( 1 === remainingChars ) {
						return wc_enhanced_select_params.i18n_input_too_short_1;
					}

					return wc_enhanced_select_params.i18n_input_too_short_n.replace( '%qty%', remainingChars );
				},
				loadingMore: function() {
					return wc_enhanced_select_params.i18n_load_more;
				},
				maximumSelected: function( args ) {
					if ( args.maximum === 1 ) {
						return wc_enhanced_select_params.i18n_selection_too_long_1;
					}

					return wc_enhanced_select_params.i18n_selection_too_long_n.replace( '%qty%', args.maximum );
				},
				noResults: function() {
					return wc_enhanced_select_params.i18n_no_matches;
				},
				searching: function() {
					return wc_enhanced_select_params.i18n_searching;
				}
			}
		};
	}

	var bindAffiliateUsersSelect2 = function() {
		// Ajax customer search boxes
		$( ':input.wc-afw-customer-search' ).filter( ':not(.enhanced)' ).each( function() {
			var select2_args = {
				allowClear:  $( this ).data( 'allow_clear' ) ? true : false,
				placeholder: $( this ).data( 'placeholder' ),
				minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '1',
				escapeMarkup: function( m ) {
					return m;
				},
				ajax: {
					url:         affiliate_params.ajax_url,
					dataType:    'json',
					delay:       1000,
					data:        function( params ) {
						return {
							term:     params.term,
							action:   'afwc_json_search_affiliates',
							security: affiliate_params.afwc_security,
							exclude:  $( this ).data( 'exclude' )
						};
					},
					processResults: function( data ) {
						var terms = [];
						if ( data ) {
							$.each( data, function( id, text ) {
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

			select2_args = $.extend( select2_args, getEnhancedSelectFormatString() );

			$( this ).selectWoo( select2_args ).addClass( 'enhanced' );

			if ( $( this ).data( 'sortable' ) ) {
				var $select = $(this);
				var $list   = $( this ).next( '.select2-container' ).find( 'ul.select2-selection__rendered' );

				$list.sortable({
					placeholder : 'ui-state-highlight select2-selection__choice',
					forcePlaceholderSize: true,
					items       : 'li:not(.select2-search__field)',
					tolerance   : 'pointer',
					stop: function() {
						$( $list.find( '.select2-selection__choice' ).get().reverse() ).each( function() {
							var id     = $( this ).data( 'data' ).id;
							var option = $select.find( 'option[value="' + id + '"]' )[0];
							$select.prepend( option );
						} );
					}
				});
			}
		});
	}

	bindAffiliateUsersSelect2();
});
