jQuery( function ( $ ) {

	var $vaciado = new Array();

	$(document).ready(function() {
		$( 'span#svg-show-hooked-functions').on( "click", __displayFunctions );
		$( 'span#svg-toggle-remove-functions').on( "click", __removeActionToggle );
	});

	function __displayFunctions() {
		var $this = $( this );
		$this.find('span').toggleClass( 'open' );
		$this.next().slideToggle();
		$this.closest( '#visual-hook-box' ).toggleClass( 'active' );
	}

	function __removeActionToggle() {
		var $this        = $( this ),
			$wrapper     = $this.closest('.svg-hooked-functions'),
			$funciones   = $wrapper.find('.svg-hooked-function'),
			$hook        = $this.attr('data-svg-action-hook'),
			$prepend     = "<span class=\"svg-append\" title=\"Action Hook\">remove_action( '" + $hook + "', </span>",
			$append      = "<span class=\"svg-append\">);</span>";

		if ( $this.hasClass( 'active' ) ){
			$funciones.each(function() {
				$( this ).find('span.svg-append').remove();
				if ( $( this ).text().substr(0, 6) == 'array(' ) {
					$newString = $( this ).html().replace( "array( '", "'Class-&gt;");
					$newString = $newString.replace( "', '", "::" );
					$newString = $newString.replace( "' )", "()'" );
					$( this ).html( $newString );
				}
			});
		} else {
			$funciones.each(function() {
				if ( $( this ).text().substr(1, 6) == 'Class-' ) {
					$newString = $( this ).html().replace( "'Class-&gt;", "array( '");
					$newString = $newString.replace("::", "', '");
					$newString = $newString.replace("()'", "' )");
					$( this ).html( $newString );
				}
				$( this ).prepend( $prepend );
				$( this ).append( $append );
			});
		}

		$this.toggleClass( 'active' );
		$wrapper.toggleClass( 'active' );
	}

});
