window.omapiPointer = window.omapiPointer || {};
( function( window, document, $, app, undefined ) {
	'use strict';

	app.close = () => $(app.target).pointer('close');

	app.open = () => {
		const options = $.extend(app.options, {
			close: () => {
				$.post( ajaxurl, {
					pointer: app.id,
					action: 'dismiss-wp-pointer'
				});
			}
		});

		$(app.target).pointer( options ).pointer('open');
	};

	app.init = () => {
		// Trigger a pointer close when clicking on the link in the pointer
		$('#omPointerButton, .om-pointer-close-link').click(app.close);

		app.open();
	};

	$( app.init );

} )( window, document, jQuery, window.omapiPointer );
