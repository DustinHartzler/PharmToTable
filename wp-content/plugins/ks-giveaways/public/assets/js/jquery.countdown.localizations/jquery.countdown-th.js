/* http://keith-wood.name/countdown.html
   Thai initialisation for the jQuery countdown extension
   Written by Pornchai Sakulsrimontri (li_sin_th@yahoo.com). */
(function($) {
	'use strict';
	$.countdown.regionalOptions.th = {
		labels: ['à¸›à¸µ','à¹€à¸”à¸·à¸­à¸™','à¸ªà¸±à¸›à¸”à¸²à¸«à¹Œ','à¸§à¸±à¸™','à¸Šà¸±à¹ˆà¸§à¹‚à¸¡à¸‡','à¸™à¸²à¸—à¸µ','à¸§à¸´à¸™à¸²à¸—à¸µ'],
		labels1: ['à¸›à¸µ','à¹€à¸”à¸·à¸­à¸™','à¸ªà¸±à¸›à¸”à¸²à¸«à¹Œ','à¸§à¸±à¸™','à¸Šà¸±à¹ˆà¸§à¹‚à¸¡à¸‡','à¸™à¸²à¸—à¸µ','à¸§à¸´à¸™à¸²à¸—à¸µ'],
		compactLabels: ['à¸›à¸µ','à¹€à¸”à¸·à¸­à¸™','à¸ªà¸±à¸›à¸”à¸²à¸«à¹Œ','à¸§à¸±à¸™'],
		whichLabels: null,
		digits: ['0','1','2','3','4','5','6','7','8','9'],
		timeSeparator: ':',
		isRTL: false
	};
	$.countdown.setDefaults($.countdown.regionalOptions.th);
})(jQuery);