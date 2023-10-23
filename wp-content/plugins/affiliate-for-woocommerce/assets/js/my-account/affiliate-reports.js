jQuery(function(){
	const { _x } = wp.i18n;

	const afwcDashboard = {
		init: function () {
			this.dashboardWrapper = jQuery('#afwc_dashboard_wrapper');
			this.affiliateId = afwcDashboardParams.affiliateId || 0;
			window.innerWidth && jQuery('.afwc_products, .afwc_referrals, .afwc_payout_history').toggleClass( 'woocommerce-table shop_table shop_table_responsive my_account_orders', window.innerWidth < 760);
			jQuery('body').on('click', '#afwc_load_more_products', this.loadMore.bind(this, 'products', { url: afwcDashboardParams.products.ajaxURL || '', nonce: afwcDashboardParams.products.nonce }));
			jQuery('body').on('click', '#afwc_load_more_referrals', this.loadMore.bind(this, 'referrals', { url: afwcDashboardParams.referrals.ajaxURL || '', nonce: afwcDashboardParams.referrals.nonce }));
			jQuery('body').on('click', '#afwc_load_more_payouts', this.loadMore.bind(this, 'payouts', { url: afwcDashboardParams.payouts.ajaxURL || '', nonce: afwcDashboardParams.payouts.nonce }));
			jQuery('body').on('focus', '#afwc_from, #afwc_to', (e) => this.loadDatepicker( jQuery( e.target )));
			jQuery('body').on('change', '#afwc_from, #afwc_to, #afwc_search', (e) => this.handleDateSearchChange( jQuery(  e.target )));
		},
		loadMore: function (section = '', ajaxArgs = {}, e = {}) {
			e.preventDefault();
			let theTable = jQuery(`.afwc-${section}-section`).find('table');
			let dateRange = this.getFormattedDateRange();
			theTable.addClass('afwc-loading');
			this.ajaxCall({
				url: ajaxArgs.url || '',
				type: 'post',
				dataType: 'html',
				data: {
					security: ajaxArgs.nonce || '',
					from: dateRange.from || '',
					to: dateRange.to || '',
					search: this.dashboardWrapper.find('#afwc_search').val() || '',
					offset: theTable.find('tbody tr').length || 0,
					affiliate: this.affiliateId
				},
				success: function (response) {
					if (response) {
						theTable.find('tbody').append(response);
						let maxRecord = parseInt( jQuery(`#afwc_load_more_${section}`).data('max_record') ) || 0;
						if (theTable.find('tbody tr').length >= maxRecord) {
							jQuery(`#afwc_load_more_${section}`).addClass('disabled').text(_x('No more data to load', 'Text for no data to load', 'affiliate-for-woocommerce'));
						}
						theTable.removeClass('afwc-loading');
					}
				}
			});
		},
		loadDatepicker: function (element) {
			if (!element.hasClass('hasDatepicker')) {
				element.datepicker({
					dateFormat: 'dd-M-yy',
					beforeShowDay: this.dateRange,
					onSelect: this.onSelectDate.bind(this)
				});
			}
			element.datepicker('show');
		},
		getDateTime: function (dateStr = '', args = {}) {
			let { dayStart = false, dayEnd = false } = args;
			let hr = dayStart ? '00' : (dayEnd ? '23' : new Date().getHours());
			let min = dayStart ? '00' : (dayEnd ? '59' : new Date().getMinutes());
			let sec = dayStart ? '00' : (dayEnd ? '59' : new Date().getSeconds());
			return dateStr + ' ' + hr + ':' + min + ':' + sec;
		},
		dateRange: function (date = {}) {
			let from = jQuery.datepicker.parseDate('dd-M-yy', jQuery('#afwc_from').val());
			let to = jQuery.datepicker.parseDate('dd-M-yy', jQuery('#afwc_to').val());
			let isHighlight = (from && ((date.getTime() == from.getTime()) || (to && date >= from && date <= to)));
			return [true, isHighlight ? 'dp-highlight' : ''];
		},
		onSelectDate: function (dateText = '', inst = {}) {
			let from = jQuery.datepicker.parseDate('dd-M-yy', jQuery('#afwc_from').val());
			let to = jQuery.datepicker.parseDate('dd-M-yy', jQuery('#afwc_to').val());
			if (!from && !to) {
				jQuery('#afwc_from').val('');
				jQuery('#afwc_to').val('');
				setTimeout(function () {
					this.loadDatepicker(jQuery('#afwc_from'));
				}.bind(this), 1);
			} else if (!from && to) {
				jQuery('#afwc_from').val(dateText);
				jQuery('#afwc_to').val('');
				setTimeout(function () {
					this.loadDatepicker(jQuery('#afwc_to'));
				}.bind(this), 1);
			} else if (from && !to) {
				jQuery('#afwc_to').val('');
				setTimeout(function () {
				this.loadDatepicker(jQuery('#afwc_to'));
				}.bind(this), 1);
			} else if (from && to) {
				if ('afwc_to' !== inst.id || from >= to) {
					jQuery('#afwc_from').val(dateText);
					jQuery('#afwc_to').val('');
					setTimeout(function () {
						this.loadDatepicker(jQuery('#afwc_to'));
					}.bind(this), 1);
				} else {
					jQuery('#afwc_to').trigger('change');
				}
			}
		},
		getDatesFromDateRange: function () {
			return {
				from: this.dashboardWrapper.find('#afwc_from').val() || '',
				to: this.dashboardWrapper.find('#afwc_to').val() || ''
			}
		},
		getFormattedDateRange: function () {
			let dateRange = this.getDatesFromDateRange();
			return {
				from: dateRange.from ? this.getDateTime(jQuery.datepicker.formatDate('yy-mm-dd', new Date(jQuery.datepicker.parseDate('dd-M-yy', dateRange.from))), { dayStart: true }) : '',
				to: dateRange.to ? this.getDateTime(jQuery.datepicker.formatDate('yy-mm-dd', new Date(jQuery.datepicker.parseDate('dd-M-yy', dateRange.to))), { dayEnd: true }) : ''
			}
		},
		handleDateSearchChange: function () {
			let dateRange = this.getFormattedDateRange();
			let search = this.dashboardWrapper.find('#afwc_search').val() || '';
			this.dashboardWrapper.css('opacity', 0.5);
			if ((dateRange.from && dateRange.to) || search) {
				this.ajaxCall({
					url: afwcDashboardParams.loadAllData.ajaxURL || '',
					type: 'post',
					dataType: 'html',
					data: {
						security: afwcDashboardParams.loadAllData.nonce || '',
						afwc_from: this.getDatesFromDateRange().from || '',
						afwc_to: this.getDatesFromDateRange().to || '',
						afwc_format_from: dateRange.from || '',
						afwc_format_to: dateRange.to || '',
						afwc_search: search || '',
						user_id: this.affiliateId
					},
					success: function (response = '') {
						if (response) {
							this.dashboardWrapper.replaceWith(response);
							this.dashboardWrapper = jQuery('#afwc_dashboard_wrapper');
							this.dashboardWrapper.css('opacity', 1);
						}
					}.bind(this)
				});
			}
		},
		ajaxCall: function (options = {}) {
			jQuery.ajax(options);
		}
	};

	// Initialize the dashboard object
	afwcDashboard.init();      
});
