<?php
/**
 * Main class for Admin dashboard widget.
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       6.36.0
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Dashboard_Widget' ) ) {

	/**
	 * Admin dashboard widget class.
	 */
	class AFWC_Admin_Dashboard_Widget {

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Admin_Dashboard_Widget Singleton object of this class
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			add_action( 'admin_init', array( $this, 'initialize' ) );
		}

		/**
		 * Initialize widget.
		 *
		 * @return void
		 */
		public function initialize() {
			add_action( 'admin_enqueue_scripts', array( $this, 'widget_scripts' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'register_widget' ) );
		}

		/**
		 * Load widget-specific scripts.
		 * Load them only on the admin dashboard page.
		 *
		 * @return void
		 */
		public function widget_scripts() {

			$screen = get_current_screen();

			if ( ! $screen instanceof WP_Screen || empty( $screen->id ) || 'dashboard' !== $screen->id ) {
				return;
			}

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();

			wp_enqueue_style(
				'afwc-dashboard-widget',
				AFWC_PLUGIN_URL . '/assets/css/admin-dashboard-widget.css',
				array(),
				$plugin_data['Version']
			);
		}

		/**
		 * Register the widget
		 *
		 * @return void
		 */
		public function register_widget() {
			wp_add_dashboard_widget(
				'afwc_summary',
				esc_html_x( 'Affiliates summary - this month', 'Summary dashboard widget title', 'affiliate-for-woocommerce' ),
				array( $this, 'summary_widget_content' )
			);
		}

		/**
		 * Render summary widget.
		 *
		 * @return void
		 */
		public function summary_widget_content() {

			$data = $this->get_summary_widget_data();
			?>
			<div id="afwc-widget-container">
				<div class="afwc-widget-section">
					<h3><?php echo esc_html_x( 'Affiliates (All time)', 'Title for affiliate count section in dashboard widget', 'affiliate-for-woocommerce' ); ?></h3>
					<div class="afwc-stats-container">
						<div class="afwc-single-stats active-affiliates">
							<span class="afwc-value"><?php echo ! empty( $data['affiliates']['active_affiliate_count'] ) ? esc_html( $data['affiliates']['active_affiliate_count'] ) : 0; ?></span>
							<span class="afwc-label"><?php echo esc_html_x( 'Active', 'Stat label for active affiliate count in dashboard widget', 'affiliate-for-woocommerce' ); ?></span>
						</div>
						<div class="afwc-single-stats pending-affiliates">
							<span class="afwc-value"><?php echo ! empty( $data['affiliates']['pending_affiliate_count'] ) ? esc_html( $data['affiliates']['pending_affiliate_count'] ) : 0; ?></span>
							<span class="afwc-label"><?php echo esc_html_x( 'Pending', 'Stat label for pending affiliate count in dashboard widget', 'affiliate-for-woocommerce' ); ?></span>
						</div>
						<div class="afwc-single-stats rejected-affiliates">
							<span class="afwc-value"><?php echo ! empty( $data['affiliates']['rejected_affiliate_count'] ) ? esc_html( $data['affiliates']['rejected_affiliate_count'] ) : 0; ?></span>
							<span class="afwc-label"><?php echo esc_html_x( 'Rejected', 'Stat label for rejected affiliate count in dashboard widget', 'affiliate-for-woocommerce' ); ?></span>
						</div>
					</div>
				</div>
				<div class="affiliates-revenue afwc-widget-section">
					<h3> 
					<?php
					echo sprintf(
						/* translators: The affiliate total sales */
						wp_kses_post( _x( 'Affiliates Revenue: %s', 'Title for affiliate revenue section in dashboard widget', 'affiliate-for-woocommerce' ) ),
						'<span class="afwc-value">' . esc_html( ! empty( $data['affiliate_total_sales'] ) ? $data['affiliate_total_sales'] : 0 ) . '</span>'
					);
					?>
					</h3>
					<h3 class="afwc-percentage-sales"> 
					<?php
					echo sprintf(
						/* translators: The total percentage revenue made by affiliates */
						wp_kses_post( _x( '%s of total revenue', 'Text for total revenue stats in dashboard widget', 'affiliate-for-woocommerce' ) ),
						'<span class="afwc-value">' . esc_html( $data['percent_of_total_sales'] ) . '%</span>'
					);
					?>
					</h3>
				</div>
				<div class="affiliates-referrals afwc-widget-section">
					<h3>
					<?php
					echo sprintf(
						/* translators: The total referral orders */
						wp_kses_post( _x( 'Referrals: %s', 'Title for affiliate revenue section in dashboard widget', 'affiliate-for-woocommerce' ) ),
						'<span class="afwc-value">' . esc_html( $data['referrals']['referral_count'] ) . '</span>'
					);
					?>
					</h3>
					<div class="afwc-stats-container">
						<div class="afwc-single-stats">
							<span class="afwc-value"><?php echo ! empty( $data['referrals']['visitor_count'] ) ? esc_html( $data['referrals']['visitor_count'] ) : 0; ?></span>
							<span class="afwc-label"><?php echo esc_html_x( 'Visitors', 'Label for visitor count in dashboard  widget', 'affiliate-for-woocommerce' ); ?></span>
						</div>
						<div class="afwc-single-stats">
							<span class="afwc-value"><?php echo ! empty( $data['referrals']['customer_count'] ) ? esc_html( $data['referrals']['customer_count'] ) : 0; ?></span>
							<span class="afwc-label"><?php echo esc_html_x( 'Customers', 'Label for referral count in dashboard  widget', 'affiliate-for-woocommerce' ); ?></span>
						</div>
						<div class="afwc-single-stats">
							<span class="afwc-value"><?php echo ( ! empty( $data['referrals']['conversion_rate'] ) ? esc_html( $data['referrals']['conversion_rate'] ) : 0 ) . '%'; ?></span>
							<span class="afwc-label"><?php echo esc_html_x( 'Conversion Rate', 'Label for conversion rate in dashboard  widget', 'affiliate-for-woocommerce' ); ?></span>
						</div>
					</div>
				</div>
				<div class="afwc-dashboard-link-section">
					<p class="afwc-dashboard-links">
						<a href="<?php echo ! empty( $data['dashboard_url'] ) ? esc_url( $data['dashboard_url'] ) : ''; ?>" target="_blank"><?php echo esc_html_x( 'Dashboard', 'Affiliates dashboard link in dashboard widget', 'affiliate-for-woocommerce' ); ?><span aria-hidden="true" class="dashicons dashicons-external"></span></a>
						<a href="<?php echo ! empty( $data['setting_url'] ) ? esc_url( $data['setting_url'] ) : ''; ?>" target="_blank"><?php echo esc_html_x( 'Settings', 'Setting link in dashboard widget', 'affiliate-for-woocommerce' ); ?><span aria-hidden="true" class="dashicons dashicons-external"></span></a>
					</p>
				</div>
			</div>
			<?php
		}

		/**
		 * Get the data for summary widget.
		 *
		 * @return array The array of data.
		 */
		public function get_summary_widget_data() {

			$date_range = $this->get_date_range_for_summary();

			// Initialize admin affiliate.
			$admin_affiliates = new AFWC_Admin_Affiliates(
				array(),
				! empty( $date_range['from'] ) ? get_gmt_from_date( $date_range['from'] ) : '',
				! empty( $date_range['to'] ) ? get_gmt_from_date( $date_range['to'] ) : ''
			);

			$price_decimal = afwc_get_price_decimals();

			// Get the data.
			$affiliates_count       = is_callable( array( $admin_affiliates, 'get_all_affiliates_count' ) ) ? $admin_affiliates->get_all_affiliates_count() : array();
			$visitors_count         = is_callable( array( $admin_affiliates, 'get_visitors_count' ) ) ? absint( $admin_affiliates->get_visitors_count() ) : 0;
			$customers_count        = is_callable( array( $admin_affiliates, 'get_customers_count' ) ) ? absint( $admin_affiliates->get_customers_count() ) : 0;
			$total_store_wide_sales = is_callable( array( $admin_affiliates, 'get_storewide_sales' ) ) ? floatval( $admin_affiliates->get_storewide_sales() ) : 0;

			// Set the affiliate sales, orders and refund to calculate affiliate net sales.
			$admin_affiliates->affiliates_orders = is_callable( array( $admin_affiliates, 'get_affiliates_orders' ) ) ? $admin_affiliates->get_affiliates_orders() : array();
			$admin_affiliates->affiliates_refund = is_callable( array( $admin_affiliates, 'get_affiliates_refund' ) ) ? floatval( $admin_affiliates->get_affiliates_refund() ) : 0;
			$admin_affiliates->affiliates_sales  = is_callable( array( $admin_affiliates, 'get_affiliates_sales' ) ) ? floatval( $admin_affiliates->get_affiliates_sales() ) : 0;

			$affiliate_net_sales = is_callable( array( $admin_affiliates, 'get_net_affiliates_sales' ) ) ? floatval( $admin_affiliates->get_net_affiliates_sales() ) : 0;

			// Format the data.
			return array(
				'affiliates'             => array(
					'active_affiliate_count'   => ! empty( $affiliates_count['active_affiliates_count'] ) ? absint( $affiliates_count['active_affiliates_count'] ) : 0,
					'pending_affiliate_count'  => ! empty( $affiliates_count['pending_affiliates_count'] ) ? absint( $affiliates_count['pending_affiliates_count'] ) : 0,
					'rejected_affiliate_count' => ! empty( $affiliates_count['rejected_affiliates_count'] ) ? absint( $affiliates_count['rejected_affiliates_count'] ) : 0,
				),
				'referrals'              => array(
					'customer_count'  => $customers_count,
					'visitor_count'   => $visitors_count,
					'conversion_rate' => number_format( ( $visitors_count > 0 ? ( $customers_count * 100 / $visitors_count ) : 0 ), $price_decimal ),  // Fixed number with defined decimal number.
					'referral_count'  => is_callable( array( $admin_affiliates, 'get_referrals_count' ) ) ? absint( $admin_affiliates->get_referrals_count() ) : 0,
				),
				'affiliate_total_sales'  => AFWC_CURRENCY . ( ! empty( $affiliate_net_sales ) ? afwc_format_price( $affiliate_net_sales ) : 0 ),
				'percent_of_total_sales' => number_format( ( $total_store_wide_sales > 0 ? ( $affiliate_net_sales * 100 / $total_store_wide_sales ) : 0 ), $price_decimal ), // Fixed number with defined decimal number.
				'dashboard_url'          => admin_url( 'admin.php?page=affiliate-for-woocommerce' ),
				'setting_url'            => add_query_arg(
					array(
						'page' => 'wc-settings',
						'tab'  => 'affiliate-for-woocommerce-settings',
					),
					admin_url( 'admin.php' )
				),
			);
		}

		/**
		 * Get the data range for summary.
		 * Currently it returns the date range of this month.
		 *
		 * @return array Return the array of date range(From date and To date)
		 */
		public function get_date_range_for_summary() {

			$offset_timestamp = Affiliate_For_WooCommerce::get_offset_timestamp();
			$format           = 'd-m-Y';

			return array(
				'from' => gmdate( $format, mktime( 0, 0, 0, gmdate( 'n', $offset_timestamp ), 1, gmdate( 'Y', $offset_timestamp ) ) ),   // From date: the start date of the month.
				'to'   => gmdate( $format, $offset_timestamp ) . '23:59:59', // To date: date as of today.
			);
		}
	}
}

return AFWC_Admin_Dashboard_Widget::get_instance();
