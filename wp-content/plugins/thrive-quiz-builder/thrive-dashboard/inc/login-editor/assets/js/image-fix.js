window.addEventListener( 'load', () => {
	var imageElement = document.querySelector( '#login > h1 > a' );
	if ( imageElement ) {
		var style = getComputedStyle( imageElement );
		if ( style && style.backgroundImage ) {
			var image = new Image();
			image.onload = function () {
				imageElement.style.setProperty( '--logo-ratio', this.width / this.height );
			}
			image.src = style.backgroundImage.replace( /.*\("/, '' ).replace( '")', '' ).trim();
		}
	}
} );
