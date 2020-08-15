<script type="text/javascript">
	(function() {
		var turnOff = function() {
			window.setUserSetting( 'om_cybermonday_notice', 'off' );
		};
		if ( jQuery ) {
			jQuery( function( $ ) {
				$( '#om-cybermonday-notice' ).on( 'click', '.notice-dismiss', turnOff );
			});
		} else {
			var el = document.getElementById( 'om-cybermonday-notice' );
			el.addEventListener('click', function( e ) {
				if ( ( ' ' + e.target.className + ' ' ).indexOf( ' notice-dismiss ' ) > -1 ) {
					turnOff();
				}
			});
		}
		setTimeout( turnOff, 4000 );
	})();
</script>
