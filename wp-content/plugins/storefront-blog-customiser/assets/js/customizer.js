/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Background color
	if ( jQuery(window).width() > 768 ) {

		wp.customize( 'sbc_fixed_width', function( value ) {
			value.bind( function() {
				$( 'body' ).toggleClass( 'sbc-fixed-width' );
			} );
		} );

		wp.customize( 'sbc_max_width', function( value ) {
			value.bind( function() {
				$( 'body' ).toggleClass( 'sbc-max-width' );
			} );
		} );

		wp.customize( 'sbc_scale', function( value ) {
			value.bind( function( to ) {
				$( 'body' ).removeClass( 'sbc-scale-smaller' ).removeClass( 'sbc-scale-larger' );
				$( 'body' ).addClass( 'sbc-scale-' + to );
			} );
		} );

		wp.customize( 'sbc_button_flat', function( value ) {
			value.bind( function( to ) {
				if ( to == true ) {
					$( 'body' ).addClass( 'sbc-buttons-flat' );
				} else {
					$( 'body' ).removeClass( 'sbc-buttons-flat' );
				}
			} );
		} );

	}
} )( jQuery );