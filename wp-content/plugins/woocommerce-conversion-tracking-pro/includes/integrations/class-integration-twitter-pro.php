<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WCCT_RPRO_Integration_Twitter Class
 */
class WCCT_PRO_Integration_Twitter extends WCCT_Integration_Twitter {

	/**
	 * Constructor for the WCCT_PRO_Integration_Twitter Class
	 */
	public function __construct() {
		parent::__construct();
		array_push(
			$this->supports,
			'add_to_cart',
			'registration'
		);

		add_filter( 'wcct_settings_twitter', array( $this, 'field_settings' ), 10, 1 );
	}

	/**
	 * Get twitter pro field
	 *
	 * @return array
	 */
	public function field_settings( $settings ) {
		$settings['events']['options']['AddToCart'] = array(
			'event_label_box'   => true,
			'label'             => __( 'Add To Cart', 'woocommerce-conversion-tracking' ),
			'label_name'        => 'AddtoCart-label',
			'placeholder'       => 'Website Tag Id',
		);

		$settings['events']['options']['Registration'] = array(
			'event_label_box'  => true,
			'label'            => __( 'Registration', 'woocommerce-conversion-tracking' ),
			'label_name'       => 'registration-label',
			'placeholder'      => 'Website Tag Id',
		);

		return $settings;
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

		$integration_settins = $this->get_integration_settings();
		$twitter_pixel_id    = ! empty( $integration_settins[0]['events']['universal_tag_id'] ) ? $integration_settins[0]['events']['universal_tag_id'] : '';
		?>
		<script>
			!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');

			<?php echo wp_kses_post( $this->build_event( $twitter_pixel_id, array(), 'init' ) ); ?>
			<?php echo wp_kses_post( $this->build_event( 'PageView' ) ); ?>
		</script>
		<?php
		wp_enqueue_script( 'twitter-platform', '//platform.twitter.com/oct.js', [], WCCT_PRO_VERSION );
		$this->add_to_cart_ajax();
	}

	/**
	 * Enqueue add to cart event
	 *
	 * @return void
	 */
	public function add_to_cart() {
		if ( ! $this->event_enabled( 'AddToCart' ) ) {
			return;
		}

		$product_ids = $this->get_content_ids_from_cart( WC()->cart->get_cart() );

		$code = $this->build_custom_event( 'AddtoCart-label' );

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

		$integration_settins = $this->get_integration_settings();
		$label               = isset( $integration_settins[0]['events']['AddtoCart-label'] ) ? $integration_settins[0]['events']['AddtoCart-label'] : '';

		?>
		<script type="text/javascript">
			jQuery(function($) {
				$(document).on('added_to_cart', function (event, fragments, hash, button) {
					twttr.conversion.trackPid('<?php echo esc_html( $label ); ?>', { tw_sale_amount: 0, tw_order_quantity: 0 });
				});
			});
		</script>
		<?php
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

		$code = $this->build_custom_event( 'registration-label' );

		wc_enqueue_js( $code );
	}

	/**
	 * Get twitter event label
	 *
	 * @param  string $event_label
	 *
	 * @return void
	 */
	public function build_custom_event( $event_label ) {
		$integration_settins = $this->get_integration_settings();
		$label               = isset( $integration_settins[0]['events'][ $event_label ] ) ? $integration_settins[0]['events'][ $event_label ] : '';

		if ( empty( $label ) ) {
			return;
		}

		$html = 'twttr.conversion.trackPid("' . wp_kses_post( $label ) . '", { tw_sale_amount: 0, tw_order_quantity: 0 });';

		return wp_kses_post( $html );
	}
}

return new WCCT_PRO_Integration_Twitter();
