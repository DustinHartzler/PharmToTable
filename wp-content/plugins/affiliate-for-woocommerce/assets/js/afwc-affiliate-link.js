/* phpcs:ignoreFile */
jQuery(function(){
	"use strict";
	const { _x } = wp.i18n;

	const productAffiliateLink = {
		singleProductAffiliateLink: '',
		init() {
			this.singleProductAffiliateLink = jQuery('.single-product-affiliate-link');
			jQuery('form.variations_form').on('change.wc-variation-form', this.refreshAffiliateLink.bind(this));
			jQuery('.single-product-affiliate-link.afwc-click-to-copy').on('copied', this.afterReferralLinkCopy);
		},
		refreshAffiliateLink(e) {
			e.preventDefault();
			this.singleProductAffiliateLink.hide();
			let variationForm = jQuery('div.woocommerce-variation-add-to-cart');
			jQuery.ajax({
				url: afwcAffiliateLinkParams.product.ajaxURL || '',
				type: 'POST',
				dataType: 'json',
				data: {
					product_id: parseInt( variationForm.find('input.variation_id[name="variation_id"]').val() || variationForm.find('input[name="product_id"]').val() ),
					security: afwcAffiliateLinkParams.product.security || '',
				},
				success: res => {
					if (res && res.success) {
						this.singleProductAffiliateLink.attr({
							'href': res.data.url || '',
							'data-ctp': res.data.url || ''
						});
						(res.data && res.data.url) ? this.singleProductAffiliateLink.show() : this.singleProductAffiliateLink.hide();
					}
				},
				error: err => {
					console.log('Cannot get the product\'s affiliate link: ', err);
				}
			})
		},
		afterReferralLinkCopy(e){
			const originalText = jQuery(e.target).text();
			jQuery(e.target).text(_x('Copied', 'Text after successfully copied', 'affiliate-for-woocommerce'));
			setTimeout(() => {
				jQuery(e.target).text(originalText);
			}, 1000);
		}
	};

	const clickToCopy = {
		selector: '.afwc-click-to-copy',
		init() {
			jQuery(this.selector).on('click', this.copy.bind(this));
		},
		async copy(e){
			e.preventDefault();
			let target = e.target;
			let text = jQuery(target).attr('data-ctp');
			if(!text){
				return;
			}
			let element = jQuery("<input>");
			jQuery("body").append(element);
			element.val(text).select();
			try {
				if (navigator && navigator.clipboard) {
					await this.copyToClipboard(element.val());
					jQuery(target).trigger('copied');
				}
				element.remove();
			} catch (err) {
				console.error('Failed to copy: ', err);
			}
		},
		copyToClipboard: (text) => {
			return new Promise((resolve, reject) => {
				navigator.clipboard.writeText(text).then(() => {
					resolve();
				}).catch(err => {
					reject(err);
				});
			});
		}
	};

	// Initialization
	productAffiliateLink.init();
	clickToCopy.init();
});
