jQuery( document ).ready( function() {
	jQuery( '.storefront-recent-products ul.products, .storefront-featured-products ul.products, .storefront-popular-products ul.products, .storefront-on-sale-products ul.products, .storefront-best-selling-products ul.products' ).slick({
		infinite: false,
		arrows: true,
		dots: false,
		slidesToShow: 3,
		slidesToScroll: 1,
		cssEase: 'cubic-bezier(0.795, -0.035, 0.000, 1.000)',
		adaptiveHeight: true,
		speed: 500,
		responsive: [
			{
				breakpoint: 768,
				settings: {
					arrows: false,
					dots: true,
					centerMode: true,
					slidesToShow: 1,
			}
			}
			]
	});
});