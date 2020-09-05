jQuery(function() {
	jQuery('.ks-countdown-widget').each(function(index) {
		var until = jQuery(this).data('until');
		if (until) {
			until = new Date(until * 1000);
			jQuery(this).countdown({until: until, compact: false, format: 'dHMS', alwaysExpire: true});
		}
	});
});