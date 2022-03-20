<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Facebook Pro Integration Class
 */

class WCCT_PRO_Integration_Facebook extends WCCT_Integration_Facebook {

	/**
	 * Constructor fot the WCCT_PRO_Integration_Facebook Class
	 */
	public function __construct() {
		parent::__construct();
		$this->multiple = true;
		array_push(
			$this->supports,
			'product_view',
			'category_view',
			'search',
			'add_to_wishlist'
		);

		add_filter( 'wcct_settings_fb', array( $this, 'field_settings' ), 10, 1 );
		add_action( 'wcct_integration_facebook', array( $this, 'add_multiple_pixel_installation' ), 10, 1 );
		add_action( 'wcct_nav_content_product-catalog', array( $this, 'fb_product_catalog_page' ) );
	}

	/**
	 * Add field for Integration
	 *
	 * @param  array $settings
	 *
	 * @return array
	 */
	public function field_settings( $settings ) {
		$settings['events']['options']['ViewContent']   = __( 'View Product', 'woocommerce-conversion-tracking-pro' );
		$settings['events']['options']['ViewCategory']  = __( 'View Product Category', 'woocommerce-conversion-tracking-pro' );
		$settings['events']['options']['Search']        = __( 'Search Product', 'woocommerce-conversion-tracking-pro' );
		$settings['events']['options']['AddToWishlist'] = __( 'Add To Wishlist', 'woocommerce-conversion-tracking-pro' );

		return $settings;
	}

	/**
	 * Create multiple pixel event
	 *
	 * @param  string $event
	 * @param  array  $params
	 * @return void
	 */
	public function create_multiple_pixel_event( $event_name, $params = array() ) {
		$settings   = $this->get_fb_settings();
		$script = '';
		foreach ( $settings as $setting ) {
			if ( ! empty( $setting['events'] ) && array_key_exists( $event_name, $setting['events'] ) ) {
				$script .= sprintf( "wcfbq('%s', '%s', %s);", $setting['pixel_id'], $event_name, json_encode( $params, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT ) );
			}
		}

		return $script;
	}
	/**
	 * Init multiple pixel id
	 *
	 * @param  array  $params
	 * @return void
	 */
	public function multiple_pixel_id_init( $params = array() ) {
		$integration_settins = $this->get_fb_settings();
		foreach ( $integration_settins as $setting ) {
			echo wp_kses_post( $this->build_event( $setting['pixel_id'], $params, 'init' ) );
		}
	}
	/**
	 * Enqueue script
	 *
	 * @return void
	 */
	public function enqueue_script() {
		?>
		<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
				n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
				document,'script','https://connect.facebook.net/en_US/fbevents.js');
			<?php
			if ( is_order_received_page() && ! is_user_logged_in() ) {
				$order_id   = get_query_var( 'order-received' );
				$order      = new WC_Order( $order_id );
				$first_name = $order->get_billing_first_name();
				$last_name  = $order->get_billing_last_name();
				$phone      = $order->get_billing_phone();
				$email      = $order->get_billing_email();
				$zip        = $order->get_billing_postcode();
				$city       = $order->get_billing_city();
				$state      = $order->get_billing_state();

				$this->multiple_pixel_id_init(
					array(
						'em' => $email,
						'ph' => $phone,
						'fn' => $first_name,
						'ln' => $last_name,
						'zp' => $zip,
						'ct' => $city,
						'st' => $state,
					)
				);
			} elseif ( is_user_logged_in() ) {
				$user = wp_get_current_user();

				if ( in_array( 'customer', (array) $user->roles ) ) {
					$customer = new WC_Customer( $user->ID );

					$first_name = $customer->get_billing_first_name();
					$last_name  = $customer->get_billing_last_name();
					$phone      = $customer->get_billing_phone();
					$email      = $customer->get_billing_email();
					$zip        = $customer->get_billing_postcode();
					$city       = $customer->get_billing_city();
					$state      = $customer->get_billing_state();

					$this->multiple_pixel_id_init(
						array(
							'em' => $email,
							'ph' => $phone,
							'fn' => $first_name,
							'ln' => $last_name,
							'zp' => $zip,
							'ct' => $city,
							'st' => $state,
						)
					);
				} else {
					$ID         = get_current_user_id();
					$user       = get_userdata( $ID );
					$user_email = wp_get_current_user()->user_email;
					$first_name = $user->first_name;
					$last_name  = $user->last_name;
					$this->multiple_pixel_id_init(
						array(
							'em' => $user_email,
							'fn' => $first_name,
							'ln' => $last_name,
						)
					);
				}
			} else {
				$this->multiple_pixel_id_init( array() );
			}

			echo wp_kses_post( $this->build_event( 'PageView', array() ) );
			?>
		</script>

		<!--  <noscript><img height="1" width="1" style="display:none"
		 src="https://www.facebook.com/tr?id=1336191666485656&ev=PageView&noscript=1"
		 /></noscript> -->
		<?php
		$this->multiple_pixel_event_script();
		$this->add_to_cart_ajax();
		$this->add_to_wishlist_ajax();
	}

	/**
	 * Enqueue add to cart event
	 *
	 * @return void
	 */
	public function add_to_cart() {
		$product_ids = $this->get_content_ids_from_cart( WC()->cart->get_cart() );

		$code = $this->create_multiple_pixel_event(
			'AddToCart', array(
				'content_ids'  => json_encode( $product_ids ),
				'content_type' => 'product',
				'value'        => WC()->cart->total,
				'currency'     => get_woocommerce_currency(),
			)
		);

		if ( $code ) {
			wc_enqueue_js( $code );
		}
	}

	/**
	 * Added to cart
	 *
	 * @return void
	 */
	public function add_to_cart_ajax() {
		$settings = json_encode( $this->get_fb_settings() );
		$currency = get_woocommerce_currency();
		?>
		<script type="text/javascript">
			jQuery( function( $ ) {
				$(document).on('added_to_cart', function ( event, fragments, hash, button ) {
					var currencySymbol = $($(button.get()[0]).closest('.product')
						.find('.woocommerce-Price-currencySymbol').get()[0]).text();

					var price = $(button.get()[0]).closest('.product').find('.amount').text();
					var originalPrice = price.split(currencySymbol).slice(-1).pop();

					var settings = <?php echo wp_kses_post( $settings ); ?>;
					$.each( settings, function( index, setting ) {
						if ( 'AddToCart' in setting.events ) {
							wcfbq(setting.pixel_id, 'AddToCart', {
								content_ids: [ $(button).data('product_id') ],
								content_type: 'product',
								value: originalPrice,
								currency: '<?php echo wp_kses_post( $currency ); ?>',
							});
						}

					} );
				});
			});
		</script>
		<?php
	}

	/**
	 * Fire initiate checkout events
	 *
	 * @return void
	 */
	public function initiate_checkout() {
		$product_ids = $this->get_content_ids_from_cart( WC()->cart->get_cart() );

		$code = $this->create_multiple_pixel_event(
			'InitiateCheckout', array(
				'num_items'    => WC()->cart->get_cart_contents_count(),
				'content_ids'  => json_encode( $product_ids ),
				'content_type' => 'product',
				'value'        => WC()->cart->total,
				'currency'     => get_woocommerce_currency(),
			)
		);

		if ( $code ) {
			wc_enqueue_js( $code );
		}
	}

	/**
	 * Check Out
	 *
	 * @param  integer $order_id
	 *
	 * @return void
	 */
	public function checkout( $order_id ) {
		$order        = new WC_Order( $order_id );
		$content_type = 'product';
		$product_ids  = array();

		foreach ( $order->get_items() as $item ) {
			$product = wc_get_product( $item['product_id'] );

			$product_ids[] = $product->get_id();

			if ( $product->get_type() === 'variable' ) {
				$content_type = 'product_group';
			}
		}

		$code = $this->create_multiple_pixel_event(
			'Purchase', array(
				'content_ids'  => json_encode( $product_ids ),
				'content_type' => $content_type,
				'value'        => $order->get_total(),
				'currency'     => get_woocommerce_currency(),
			)
		);

		if ( $code ) {
			wc_enqueue_js( $code );
		}
	}

	/**
	 * Registration script
	 *
	 * @return void
	 */
	public function registration() {
		$code = $this->create_multiple_pixel_event( 'CompleteRegistration' );

		if ( $code ) {
			wc_enqueue_js( $code );
		}
	}

	/**
	 * Fire the product view event
	 *
	 * @return void
	 */
	public function product_view() {
		$product      = wc_get_product( get_the_ID() );
		$content_type = 'product';

		if ( ! $product ) {
			return;
		}

		if ( $product->get_type() === 'variable' ) {
			$content_type = 'product_group';
		}
		$price = $product->get_price();
		$code = $this->create_multiple_pixel_event(
			'ViewContent', array(
				'content_name'  => $product->get_title(),
				'content_ids'   => json_encode( array( $product->get_id() ) ),
				'content_type'  => $content_type,
				'value'         => $price ? $price : 0.00,
				'currency'      => get_woocommerce_currency(),
			)
		);

		if ( $code ) {
			wc_enqueue_js( $code );
		}
	}

	/**
	 * View category event
	 *
	 * @return void
	 */
	public function category_view() {
		global $wp_query;

		$products = array_values(
			array_map(
				function( $item ) {
					return wc_get_product( $item->ID );
				}, $wp_query->get_posts()
			)
		);

		// if any product is a variant, fire the pixel with
		// content_type: product_group
		$content_type = 'product';
		$product_ids  = array();

		foreach ( $products as $product ) {
			if ( ! $product ) {
				continue;
			}

			$product_ids[] = $product->get_id();

			if ( $product->get_type() === 'variable' ) {
				$content_type = 'product_group';
			}
		}

		$categories = $this->get_product_categories( get_the_ID() );

		$code = $this->create_multiple_pixel_event(
			'ViewCategory', array(
				'content_name'      => $categories['name'],
				'content_category'  => $categories['categories'],
				'content_ids'       => json_encode( array_slice( $product_ids, 0, 10 ) ),
				'content_type'      => $content_type,
			)
		);

		if ( $code ) {
			wc_enqueue_js( $code );
		}
	}

	/**
	 * Search event
	 *
	 * @return void
	 */
	public function search() {
		if ( ! is_admin() && is_search() && get_search_query() != '' && get_query_var( 'post_type' ) == 'product' ) {
			if ( class_exists( 'WooCommerce' ) ) {
				$this->inject_search_event();
			} else {
				add_action( 'wp_head', array( $this, 'inject_search_event' ) );
			}
		}
	}

	/**
	 * Search inject
	 *
	 * @return void
	 */
	public function inject_search_event() {
		$code = $this->create_multiple_pixel_event(
			'Search', array(
				'search_string' => get_search_query(),
			)
		);

		if ( $code ) {
			wc_enqueue_js( $code );
		}
	}

	/**
	 * Add to wishlist event
	 *
	 * @return  void
	 */
	public function add_to_wishlist() {
		if ( ! $this->event_enabled( 'AddToWishlist' ) ) {
			return;
		}

		if ( ! defined( 'YITH_WCWL' ) && ! class_exists( 'WC_Wishlists_Plugin' ) ) {
			return;
		}

		global $product;

		$code = $this->build_event(
			'AddToWishlist',
			array(
				'content_ids'  => $product->get_id(),
				'content_type' => 'product',
			)
		);
	}

	/**
	 * Add to wish list ajax
	 *
	 * @return void
	 */
	public function add_to_wishlist_ajax() {
		if ( ! defined( 'YITH_WCWL' ) && ! class_exists( 'WC_Wishlists_Plugin' ) ) {
			return;
		}

		$settings = json_encode( $this->get_fb_settings() );
		?>
		<script type="text/javascript">
			jQuery(function($) {
				$(document).on('added_to_wishlist', function ( el, button ) {
					var settings = <?php echo wp_kses_post( $settings ); ?>;
					$.each( settings, function( index, setting ) {
						if ( 'AddToWishlist' in setting.events ) {
							wcfbq(setting.pixel_id, 'AddToWishlist', {
								content_ids: [ $( el ).data( 'data-product-id' ) ],
								content_type: 'product',
							});
						}
					} );
				});

				$(document).on('click', '.wl-add-to', function ( el ) {
					var settings = <?php echo wp_kses_post( $settings ); ?>;
					$.each( settings, function( index, setting ) {
						if ( 'AddToWishlist' in setting.events ) {
							wcfbq(setting.pixel_id, 'AddToWishlist', {
								content_ids: [ $( el ).data( 'data-product-id' ) ],
								content_type: 'product',
							});
						}
					} );
				});
			});
		</script>
		<?php
	}

	/**
	 * Get facebook settings
	 *
	 * @return array
	 */
	public function get_fb_settings() {
		$settings = $this->get_integration_settings();
		unset( $settings['enabled'] );

		return $settings;
	}
	/**
	 * Multiple facebook pixel installation script
	 *
	 * @return void
	 */
	public function multiple_pixel_event_script() {
		?>
		<script>
			(function (window, document) {
				if (window.wcfbq) return;
				window.wcfbq = (function () {
					if (arguments.length > 0) {
						var pixelId, trackType, contentObj;

						if (typeof arguments[0] == 'string') pixelId = arguments[0];
						if (typeof arguments[1] == 'string') trackType = arguments[1];
						if (typeof arguments[2] == 'object') contentObj = arguments[2];

						var params = [];
						if (typeof pixelId === 'string' && pixelId.replace(/\s+/gi, '') != '' &&
							typeof trackType === 'string' && trackType.replace(/\s+/gi, '')) {
							params.push('id=' + encodeURIComponent(pixelId));
							switch (trackType) {
								case 'PageView':
								case 'ViewContent':
								case 'Search':
								case 'AddToCart':
								case 'InitiateCheckout':
								case 'AddPaymentInfo':
								case 'Lead':
								case 'CompleteRegistration':
								case 'Purchase':
								case 'AddToWishlist':
									params.push('ev=' + encodeURIComponent(trackType));
									break;
								default:
									return;
							}

							params.push('dl=' + encodeURIComponent(document.location.href));
							if (document.referrer) params.push('rl=' + encodeURIComponent(document.referrer));
							params.push('if=false');
							params.push('ts=' + new Date().getTime());

							if (typeof contentObj == 'object') {
								for (var u in contentObj) {
									if (typeof contentObj[u] == 'object' && contentObj[u] instanceof Array) {
										if (contentObj[u].length > 0) {
											for (var y = 0; y < contentObj[u].length; y++) { contentObj[u][y] = (contentObj[u][y] + '').replace(/^\s+|\s+$/gi, '').replace(/\s+/gi, ' ').replace(/,/gi, '§'); }
											params.push('cd[' + u + ']=' + encodeURIComponent(contentObj[u].join(',').replace(/^/gi, '[\'').replace(/$/gi, '\']').replace(/,/gi, '\',\'').replace(/§/gi, '\,')));
										}
									}
									else if (typeof contentObj[u] == 'string')
										params.push('cd[' + u + ']=' + encodeURIComponent(contentObj[u]));
								}
							}

							params.push('v=' + encodeURIComponent('2.7.19'));

							var imgId = new Date().getTime();
							var img = document.createElement('img');
							img.id = 'fb_' + imgId, img.src = 'https://www.facebook.com/tr/?' + params.join('&'), img.width = 1, img.height = 1, img.style = 'display:none;';
							document.body.appendChild(img);
							window.setTimeout(function () { var t = document.getElementById('fb_' + imgId); t.parentElement.removeChild(t); }, 1000);
						}
					}
				});
			})(window, document);
		</script>
		<?php
	}

	/**
	 * Add new pixel
	 *
	 * @param void
	 */
	public function add_multiple_pixel_installation( $object ) {
		$setting_field      = $object->get_settings();
		$id                 = $object->get_id();
		$settings           = $object->get_integration_settings();
		$border             = ( isset( $object->multiple ) ) ? 'wcct-border' : '';
		unset( $settings[0] );
		unset( $settings['enabled'] );

		$settings = is_array( $settings ) ? $settings : array();

		include WCCT_PRO_INCLUDES . '/views/facebook/multiple-pixel.php';
	}

	/**
	 * Add facebook product catalog page
	 *
	 * @return void
	 */
	public function fb_product_catalog_page() {
		$product_type       = wc_get_product_types();
		$product_categories = get_terms( 'product_cat' );

		include WCCT_PRO_INCLUDES . '/views/facebook/product-catalog.php';
	}
}

return new WCCT_PRO_Integration_Facebook();
