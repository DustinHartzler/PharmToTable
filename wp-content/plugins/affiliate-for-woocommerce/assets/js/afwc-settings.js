/* phpcs:ignoreFile */
jQuery(
	function(){

		const { _x } = wp.i18n;

		jQuery( 'form' ).on(
			'click',
			'.woocommerce-save-button',
			function(e){
				let newPname = jQuery( '#afwc_pname' ).val() || '';
				let oldPname = afwcSettingParams.oldPname || '';
				// Return if default param and old & new param is same.
				if ( 'ref' === oldPname && oldPname === newPname ) {
					return;
				}
				// Return if new param is empty or old & new param is same.
				if ( '' == newPname || oldPname === newPname ) {
					return;
				}
				if ( jQuery( 'form' ).find( '#afwc_admin_settings_security' ).length > 0 ) {
					return confirm( _x( 'Changing tracking param name will stop affiliate tracking for the existing URL with the current tracking param name. Are you sure you want to continue?', 'alert when changing affiliate tracking param name', 'affiliate-for-woocommerce' ) );
				}
			}
		);
		jQuery( 'form' ).on(
			'change, keyup',
			'#afwc_pname',
			function( event ){
				let newPname = jQuery( this ).val();
				jQuery( '#afwc_pname_span' ).text( newPname );
			}
		);
		jQuery( 'form' ).on(
			'keydown',
			'#afwc_pname',
			function( event ){
				let key = event.which;
				if ( ! ( ( key == 8 ) || ( key == 46 ) || ( key >= 35 && key <= 40 ) || ( key >= 65 && key <= 90 ) ) ) {
					event.preventDefault();
				}
			}
		);
		jQuery('#affiliate_reg_form').css('display', 'none');
		jQuery('#affiliate_tags').css('display', 'none');
		jQuery('#afwc_storewide_commission').css('display', 'none');

		let ltcExcludesSelect2Args = {
			minimumInputLength: 3,
			escapeMarkup: function(m) {
				return m;
			},
			ajax: {
				url:         afwcSettingParams.ajaxURL,
				dataType:    'json',
				delay:       1000,
				data:        function(params = {}) {
					return {
						term:     params.term || '',
						action:   'afwc_search_ltc_excludes_list',
						security: afwcSettingParams.security.searchExcludeLTC || ''
					};
				},
				processResults: function(data = {}) {
					let results = [];
					if ( data ) {
						jQuery.each( data, function(key, {title, children, group}) {
							let groupChildren = []
							jQuery.each(children, function(id, text){
								groupChildren.push({id: group + '-' + parseInt(id), text})
							})
							results.push({text: title, children: groupChildren});
						});
					}
					return {results};
				},
				error: function (jqXHR, status, error) {
					console.log(error + ": " + jqXHR.responseText);
					return { results: [] }; // Return dataset to load after error
				},
				cache: true
			}
		};
		jQuery('.afwc-lifetime-commission-excludes-search').selectWoo(ltcExcludesSelect2Args);

		let afwcFieldVisibility = {
			hiddenFields: [],
			parentSelector: 'table.form-table',
			attrName: 'data-hide',
			init() {
				this.setSelectedFields();
				this.hiddenFields.length > 0 && this.hiddenFields.each( function( _, elem ) {
					afwcFieldVisibility.toggleParent( jQuery( elem ) );
					afwcFieldVisibility.toggleField( jQuery( elem ), false);
				});
			},
			toggleParent( $fieldElem = [] ) {
				let parentField = afwcFieldVisibility.getParentField( $fieldElem );
				parentField.on( 'change', function() {
					afwcFieldVisibility.toggleField( $fieldElem );
				});
			},
			toggleField( $fieldElem = [], isFade = true ) {
				let parentField = afwcFieldVisibility.getParentField($fieldElem);
				parentField.length > 0 && parentField.is( ':checked' )
					? ( isFade ? $fieldElem.fadeIn(500) : $fieldElem.show() )
					: ( isFade ? $fieldElem.fadeOut(500) : $fieldElem.hide() );
			},
			getParentField( $elem = [] ) {
				let parentFieldId = $elem.attr( afwcFieldVisibility.attrName ) || '';
				return parentFieldId ? jQuery( `#${parentFieldId}` ) : [];
			},
			setSelectedFields() {
				this.hiddenFields = jQuery(this.parentSelector).find(`tr[${this.attrName}]`);
			},
		};

		afwcFieldVisibility.init();

	}
);
