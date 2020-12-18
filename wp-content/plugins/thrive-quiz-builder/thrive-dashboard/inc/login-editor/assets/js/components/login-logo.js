module.exports = TVE.Views.Base.component.extend( {

	after_init() {
		this.$resetButton = this.$( '.reset-logo' );
	},

	before_update: function () {
		this.controls.Size.originalConfig.max = TVE.inner_$( '#login' ).innerWidth();

		const logoBackground = TVE.inner_$( '#login h1 a' ).head_css( 'background-image', false, '', true );

		this.$resetButton.toggle( ! ( logoBackground.includes( 'images/wordpress-logo.svg' ) || logoBackground.includes( 'default=1' ) ) );
	},

	/**
	 * Initialize controls
	 * @param controls
	 */
	controls_init: function ( controls ) {

		controls[ 'ImagePicker' ].change_image = ( thumb ) => {
			TVE.ActiveElement.head_css( {
				'--logo-ratio': thumb.width / thumb.height,
				'background-image': `url(${thumb.url})`
			} );

			controls.ImagePicker.update( TVE.ActiveElement );

			this.$resetButton.show();
		};

		controls[ 'ImagePicker' ].update = function ( $element ) {
			this.$( '.preview' ).addClass( 'img' ).css( 'background-image', $element.head_css( 'background-image', false, '', true ) );
		};

		controls[ 'Size' ].input = function ( $element, input ) {
			this.applyElementCss( {
				'--logo-size': `${input.value}${this.getUM()}`
			}, $element );
		}

		controls[ 'Size' ].update = function ( $element ) {
			let value = $element.head_css( '--logo-size' );

			if ( ! value ) {
				/* this is the default value */
				value = $element.css( 'background-size' );
			}

			this.setValue( value );
		}
	},

	resetLogo() {
		TVE.ActiveElement.head_css( {
			'--logo-ratio': 1,
			'background-image': ''
		} );

		TVE.ActiveElement[ 0 ].style.removeProperty( '--logo-ratio' );

		/* We need set timeout  in order for the update to work correctly when the logo reverts to the theme logo */
		setTimeout( () => this.controls.ImagePicker.update( TVE.ActiveElement ), 100 );

		this.$resetButton.hide();
	},

	/**
	 * Make sure it's the right ratio in case the url has changed
	 * @param {String} logoUrl
	 */
	fixLogoRatio( logoUrl ) {
		const $image = TVE.inner_$( '#login > h1 > a' ),
			image = new Image();

		if ( typeof logoUrl === 'undefined' ) {
			logoUrl = $image.css( 'background-image' ).replace( /.*\("/, '' ).replace( '")', '' ).trim();
		}

		image.onload = function () {
			$image.head_css( {'--logo-ratio': this.width / this.height} );
			$image[ 0 ].style.removeProperty( '--logo-ratio' );
		}

		image.src = logoUrl;

		return this;
	},

	/**
	 * Center vertical align the show/hide password button
	 */
	fixHidePasswordButton() {
		const $passInput = TVE.inner_$( '#user_pass' ),
			$hideButton = TVE.inner_$( '#login .wp-pwd .wp-hide-pw' );

		$hideButton.head_css( {
			top: ( ( $passInput.outerHeight() - $hideButton.outerHeight() ) / 2 + parseInt( $passInput.css( 'margin-top' ) ) ) + 'px'
		} );

		return this;
	}
} );
