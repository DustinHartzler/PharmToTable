var {addFilter} = wp.hooks,
	{_x} = wp.i18n;

const AFWCOrderReport = {
	addAffiliateDetailsToOrderReport(reportTableData) {
		const { endpoint, items } = reportTableData;
		if ('orders' !== endpoint) {
			return reportTableData;
		}
		reportTableData.headers = [
			...reportTableData.headers,
			{
				label: _x('Affiliate', 'Header for affiliate name in WooCommerce orders report', 'affiliate-for-woocommerce'),
				key: 'affiliate',
			},
		];
		reportTableData.rows = reportTableData.rows.map((row, index) => {
			const item = items.data[index] || {};
			return  [
				...row,
				{
					display: item.affiliate,
					value: item.affiliate || '',
				},
			];
		});
		return reportTableData;
	},
	init(){
		addFilter('woocommerce_admin_report_table', 'affiliate-for-woocommerce', AFWCOrderReport.addAffiliateDetailsToOrderReport);
	},
};

AFWCOrderReport.init();
