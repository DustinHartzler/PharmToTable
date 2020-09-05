/* http://keith-wood.name/countdown.html
   Danish initialisation for the jQuery countdown extension
   Written by Buch (admin@buch90.dk). */
(function($) {
	'use strict';
	$.countdown.regionalOptions.da = {
		labels: ['Ã…r','MÃ¥neder','Uger','Dage','Timer','Minutter','Sekunder'],
		labels1: ['Ã…r','MÃ¥ned','Uge','Dag','Time','Minut','Sekund'],
		compactLabels: ['Ã…','M','U','D'],
		whichLabels: null,
		digits: ['0','1','2','3','4','5','6','7','8','9'],
		timeSeparator: ':',
		isRTL: false
	};
	$.countdown.setDefaults($.countdown.regionalOptions.da);
})(jQuery);