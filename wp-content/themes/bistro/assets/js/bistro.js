/* bistro.js */

(function() {
	jQuery( '.site-search label' ).click( function() {
		jQuery( '.site-search' ).toggleClass( 'active' );
	});

	jQuery( '.site-header-cart' ).click( function() {
		jQuery( '.site-header-cart' ).toggleClass( 'active' );
	});

	jQuery( '.page-template-template-homepage-php .type-page img.wp-post-image' ).addClass( 'loaded' );

	if ( jQuery( '.secondary-navigation').length > 0 ) {
		jQuery( 'body' ).addClass( 'has-secondary-navigation' );
	} else {
		jQuery( 'body' ).addClass( 'no-secondary-navigation' );
	}
}).call( this );