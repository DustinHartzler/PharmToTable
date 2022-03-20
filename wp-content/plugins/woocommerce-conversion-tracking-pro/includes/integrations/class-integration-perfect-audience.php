<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WCCT_Integration_Perfect_Audience class
 */
class WCCT_Integration_Perfect_Audience extends WCCT_Integration {

	/**
	 * Constructor for WCCT_Integration_Perfect_Audience
	 */
	public function __construct() {
		$this->id       = 'perfect_audience';
		$this->name     = __( 'Perfect Audience', 'wc-conversion-tracking-pro' );
		$this->enabled  = true;
		$this->add_new  = false;
		$this->supports = array(
			'add_to_cart',
			'checkout',
			'initiate_checkout',
			'product_view',
			'registration',
		);
	}

	/**
	 * Get settings for perfect audience
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = array(
			array(
				'type'  => 'text',
				'name'  => 'advertiser_id',
				'label' => __( 'Advertiser ID', 'wc-conversion-tracking-pro' ),
				'value' => '',
				/* translators: %s: advertiser link */
				'help'      => sprintf( __( 'Find the Advertiser ID <a href="%s" target="_blank">here</a>.', 'woocommerce-conversion-tracking' ), 'https://app.perfectaudience.com/sites' ),
			),
			array(
				'type'    => 'multicheck',
				'name'    => 'events',
				'label'   => __( 'Events', 'wc-conversion-tracking-pro' ),
				'value'   => '',
				/* translators: %s: audience event link */
				'help'      => sprintf( __( 'Set up Perfect Audience event <a href="%s" target="_blank">learn more</a>.', 'woocommerce-conversion-tracking' ), 'https://wedevs.com/docs/woocommerce-conversion-tracking/perfect-audience/events-of-perfect-audience/?utm_source=wp-admin&utm_medium=inline-help&utm_campaign=wcct_docs&utm_content=pa_learn_more' ),
				'options' => array(
					'ViewContent'      => __( 'View Product <em>(Event: <code>ViewContent</code>)</em>', 'wc-conversion-tracking-pro' ),
					'AddToCart'        => __( 'Add to Cart <em>(Event: <code>AddToCart</code>)</em>', 'wc-conversion-tracking-pro' ),
					'InitiateCheckout' => __( 'Initiate Checkout <em>(Event: <code>InitiateCheckout</code>)</em>', 'wc-conversion-tracking-pro' ),
					'Purchase'         => __( 'Purchase <em>(Event: <code>Purchase</code>)</em>', 'wc-conversion-tracking-pro' ),
					'Registration'     => __( 'Complete Registration <em>(Event: <code>Registration</code>)</em>', 'wc-conversion-tracking-pro' ),
				),
			),
		);

		return $settings;
	}

	/**
	 * Build the event object
	 *
	 * @param  string  $event
	 * @param  array   $params
	 *
	 * @return void
	 */
	public function build_event( $event, $params = array(), $method = 'track' ) {
		return sprintf( "_pq.push(['%s','%s', %s]);", $method, $event, json_encode( $params ) );
	}

	/**
	 * Enqueue script
	 *
	 * @return void
	 */
	public function enqueue_script() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		$integration_settins    = $this->get_integration_settings();
		$advertiser_id          = ! empty( $integration_settins[0]['advertiser_id'] ) ? $integration_settins[0]['advertiser_id'] : '';

		$html  = '(function() {';
		$html .= 'window._pq = window._pq || [];';
		$html .= "var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;";
		$html .= "pa.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + \"//tag.marinsm.com/serve/{$advertiser_id}.js\";";
		$html .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);";
		$html .= '})();';

		wc_enqueue_js( $html );

		$this->add_to_cart_ajax();
	}

	/**
	 * Enqueue add to cart event
	 *
	 * @return  void
	 */
	public function add_to_cart() {
		if ( ! $this->event_enabled( 'AddToCart' ) ) {
			return;
		}

		$code = $this->build_event( 'AddToCart' );

		wc_enqueue_js( $code );
	}

	/**
	 * Added to cart
	 *
	 * @return void
	 */
	public function add_to_cart_ajax() {
		if ( ! $this->event_enabled( 'AddToCart' ) ) {
			return;
		}
		?>
		<script type="text/javascript">
			jQuery(function($) {
				$(document).on('added_to_cart', function() {
					_pq.push(['track', 'AddToCart']);
				});
			});
		</script>
		<?php
	}

	/**
	 * Fire the product view event
	 *
	 * @return void
	 */
	public function product_view() {
		global $product;
		if ( ! $this->event_enabled( 'ViewContent' ) ) {
			return;
		}

		$code = $this->build_event( $product->get_id(), array(), 'trackProduct' );

		wc_enqueue_js( $code );
	}

	/**
	 * Fire initiate checkout events
	 *
	 * @return void
	 */
	public function initiate_checkout() {
		if ( ! $this->event_enabled( 'InitiateCheckout' ) ) {
			return;
		}

		$product_ids = $this->get_content_ids_from_cart( WC()->cart->get_cart() );

		$code = $this->build_event( 'InitiateCheckout' );

		wc_enqueue_js( $code );
	}

	/**
	 * Perfect audience checkout
	 *
	 * @return void
	 */
	public function checkout( $order_id ) {
		if ( ! $this->event_enabled( 'Purchase' ) ) {
			return;
		}

		$order = new WC_Order( $order_id );

		$code = $this->build_event(
			'Purchase', array(
				'orderId' => $order->get_id(),
				'revenue' => $order->get_total(),
			)
		);

		wc_enqueue_js( $code );
	}

	/**
	 * Registration script
	 *
	 * @return void
	 */
	public function registration() {
		if ( ! $this->event_enabled( 'Registration' ) ) {
			return;
		}

		$code = $this->build_event( 'Registration' );
		wc_enqueue_js( $code );
	}

}

return new WCCT_Integration_Perfect_Audience();
