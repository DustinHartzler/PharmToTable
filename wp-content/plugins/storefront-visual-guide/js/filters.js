jQuery( function ( $ ) {

	var $vaciado = new Array();

	$(document).ready(function() {
		$( 'span#svg-show-filters').on( "click", __displayFilters );
	});

	function __displayFilters() {
		var $this = $( this );
		$this.find('span').toggleClass( 'open' );
		$this.next().slideToggle();
		$this.closest( '#visual-hook-box' ).toggleClass( 'active' );
	}

});
