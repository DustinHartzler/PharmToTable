( function ( $ ) {
	var ThriveGutenbergMenu = {
		init: function () {
			var url = window.location.href.replace( 'edit.php', 'post-new.php' ),
				urlHasParams = ( - 1 !== url.indexOf( '?' ) ),
				location = url + ( urlHasParams ? '&' : '?' ) + 'architect',
				tcb = '<a href="#" id="tar_launch" >Thrive Architect</a>';

			$( '#split-page-title-action .dropdown' ).append( tcb );

			$( '#tar_launch' ).click( function ( e ) {
				e.preventDefault();
				var title = prompt( 'Enter ' + typenow + ' title', 'No title' );
				if ( title ) {
					location += '&title=' + encodeURIComponent( title );
					window.open( location );
				}
			} );
		},
	};
	$( function () {
		ThriveGutenbergMenu.init();
	} );

}( jQuery ) );