/* phpcs:ignoreFile */
jQuery(function( $ ){
	const { _x } = wp.i18n;

	let affiliateUserSearch = {
		init() {
			let self = affiliateUserSearch;

			$( ':input.wc-afw-customer-search' ).filter( ':not(.enhanced)' ).each( function() {
				let select2Args = self.getSelect2Args( this );
				select2Args = $.extend( select2Args, self.getEnhancedSelectFormatString() );

				$( this )
				.selectWoo( select2Args )
				.addClass( 'enhanced' )
				.on( 'select2:selecting', function (e) {
					self.affiliateConfirmationAlert( e, self.getCustomerId(), e.params.args.data.id || 0 );
				});

				if ( $( this ).data( 'sortable' ) ) {
					let $select = $( this );
					let $list   = $select.next( '.select2-container' ).find( 'ul.select2-selection__rendered' );

					$list.sortable({
						placeholder : 'ui-state-highlight select2-selection__choice',
						forcePlaceholderSize: true,
						items       : 'li:not(.select2-search__field)',
						tolerance   : 'pointer',
						stop: function() {
							$( $list.find( '.select2-selection__choice' ).get().reverse() ).each( function() {
								let id     = $select.data( 'data' ).id;
								$select.prepend( $select.find( 'option[value="' + id + '"]' )[0] || '' );
							} );
						}
					});
				}
			});

			if( $( '.woocommerce-order-data' ).length > 0 ) {
				$( '#customer_user' ).on( 'select2:selecting', function(e) {
					self.affiliateConfirmationAlert( e, e.params.args.data.id || 0, self.getAffiliate() );
				});
			}
		},
		getSelect2Args( elem = null ) {
			if( ! elem ) {
				return {};
			}
			return {
				placeholder: $( elem ).data( 'placeholder' ),
				minimumInputLength: $( elem ).data( 'minimum_input_length' ) || 3,
				escapeMarkup: function( m ) {
					return m;
				},
				ajax: {
					url:         affiliate_params.ajaxurl,
					dataType:    'json',
					delay:       1000,
					data:        function( params ) {
						return {
							term:     params.term || '',
							action:   'afwc_json_search_affiliates',
							security: affiliate_params.afwcSecurity || '',
							exclude:  $( elem ).data( 'exclude' ) || []
						};
					},
					processResults: function( data ) {
						let terms = [];
						if ( data ) {
							$.each( data, function( id, text ) {
								terms.push({ id, text });
							});
						}
						return {
							results: terms
						};
					},
					cache: true
				}
			};
		},
		getAffiliate(){
			return $( '#afwc_referral_order_of' ).length > 0 ? $('#afwc_referral_order_of').val() : 0;
		},
		getCustomerId(){
			return $( '#customer_user' ).length > 0 ? $( '#customer_user' ).val() : 0;
		},
		affiliateConfirmationAlert( e, customerId = 0, affiliateId = 0  ) {
			if( ! customerId || ! affiliateId || ( true === Boolean( affiliate_params.allowSelfRefer) ) ) {
				return;
			}
			if( ( parseInt( customerId ) === parseInt( affiliateId ) ) && ( false === confirm( _x( 'Are you sure you want to set the affiliate same as the customer? This overrides the setting Affiliate self-refer.', 'self refer alert', 'affiliate-for-woocommerce' ) ) ) ) {
					e.preventDefault();
			}
		},
		getEnhancedSelectFormatString() {
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
	};

	affiliateUserSearch.init();
});
