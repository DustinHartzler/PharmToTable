<?php
/**
 * Main class for Affiliates Coupon functionality
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       1.7.0
 * @version     1.1.5
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Coupon' ) ) {

	/**
	 * Main class for Affiliate Coupon functionality
	 */
	class AFWC_Coupon {

		/**
		 * Variable to hold instance of AFWC_Coupon
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 *  Constructor
		 */
		public function __construct() {

			$use_referral_coupons = get_option( 'afwc_use_referral_coupons', 'yes' );
			if ( is_admin() && 'yes' === $use_referral_coupons ) {
				add_action( 'woocommerce_coupon_options', array( $this, 'affiliate_restriction' ), 10, 2 );
			}
			add_action( 'woocommerce_coupon_options_save', array( $this, 'save_affiliate_coupon_fields' ), 10, 2 );

			add_action( 'woocommerce_applied_coupon', array( $this, 'coupon_applied' ) );

			add_action( 'wp_ajax_afwc_json_search_affiliates', array( $this, 'afwc_json_search_affiliates' ) );
		}

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Coupon Singleton object of this class
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Search for attribute values and return json
		 *
		 * @return void
		 */
		public function afwc_json_search_affiliates() {

			check_ajax_referer( 'afwc-search-affiliate-users', 'security' );

			$term = ( ! empty( $_GET['term'] ) ) ? (string) urldecode( stripslashes( wp_strip_all_tags( $_GET ['term'] ) ) ) : ''; // phpcs:ignore
			if ( empty( $term ) ) {
				wp_die();
			}

			$afwc  = Affiliate_For_WooCommerce::get_instance();
			$users = $afwc->get_affiliates(
				array(
					'search' => '*' . $term . '*',
				)
			);
			$users = ! empty( $users ) ? $users : array();

			echo wp_json_encode( $users );
			wp_die();
		}

		/**
		 * Assign a coupon to an affiliate
		 *
		 * @param int    $coupon_id The Coupon ID.
		 * @param object $coupon The Coupon Object.
		 */
		public function affiliate_restriction( $coupon_id = 0, $coupon = null ) {

			if ( empty( $coupon_id ) ) {
				return;
			}

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_register_script( 'affiliate-user-search', AFWC_PLUGIN_URL . '/assets/js/affiliate-search.js', array( 'jquery', 'wp-i18n', 'select2' ), $plugin_data['Version'], true );
			wp_enqueue_script( 'affiliate-user-search' );

			wp_localize_script(
				'affiliate-user-search',
				'affiliateParams',
				array(
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'afwc-search-affiliate-users' ),
				)
			);

			$user_string = '';

			if ( ! empty( $coupon_id ) && ( empty( $coupon ) || ! is_object( $coupon ) || ! $coupon instanceof WC_Coupon ) ) {
				$coupon = new WC_Coupon( $coupon_id );
			}

			$user_id = $coupon->get_meta( 'afwc_referral_coupon_of', true );

			if ( ! empty( $user_id ) ) {
				$user = get_user_by( 'id', $user_id );
				if ( is_object( $user ) && $user instanceof WP_User ) {
					$user_string = sprintf(
						/* translators: 1: user display name 2: user ID 3: user email */
						esc_html__( '%1$s (#%2$s &ndash; %3$s)', 'affiliate-for-woocommerce' ),
						$user->display_name,
						absint( $user_id ),
						$user->user_email
					);
				}
			}

			?>
			<div class="options_group afwc-field">
				<p class="form-field">
					<label for="afwc_referral_coupon_of"><?php esc_attr_e( 'Assign to affiliate', 'affiliate-for-woocommerce' ); ?></label>
					<select id="afwc_referral_coupon_of" name="afwc_referral_coupon_of" style="width: 50%;" class="afwc-affiliate-search" data-placeholder="<?php echo esc_attr_x( 'Search by email, username or name', 'affiliate search placeholder', 'affiliate-for-woocommerce' ); ?>" data-allow-clear="true" data-action="afwc_json_search_affiliates">
						<?php
						if ( ! empty( $user_id ) ) {
							?>
							<option value="<?php echo esc_attr( $user_id ); ?>" selected="selected"><?php echo esc_html( wp_kses_post( $user_string ) ); ?><option>
							<?php
						}
						?>
					</select>
					<?php echo wp_kses_post( wc_help_tip( _x( 'Search affiliate by email, username, name or user id to assign this coupon to them. Affiliates will see this coupon in their My account > Affiliates > Profile.', 'help tip for search and assign affiliate', 'affiliate-for-woocommerce' ) ) ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Saves our custom coupon fields.
		 *
		 * @param int    $coupon_id The coupon's ID.
		 * @param object $coupon    The coupon's object.
		 */
		public function save_affiliate_coupon_fields( $coupon_id = 0, $coupon = null ) {
			// Verify the nonce.
			if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) { // phpcs:ignore
				return;
			}

			if ( empty( $coupon_id ) ) {
				return;
			}

			// Always refresh object to get updated meta values.
			$coupon = new WC_Coupon( intval( $coupon_id ) );
			if ( ! $coupon instanceof WC_Coupon ) {
				return;
			}

			$affiliate_id = ( ! empty( $_POST['afwc_referral_coupon_of'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_referral_coupon_of'] ) ) : 0; // phpcs:ignore
			if ( ! empty( $affiliate_id ) ) {
				$coupon->update_meta_data( 'afwc_referral_coupon_of', $affiliate_id );
			} else {
				$coupon->delete_meta_data( 'afwc_referral_coupon_of' );
			}
			$coupon->save();
		}

		/**
		 * Get referral coupon.
		 *
		 * @param  array $args The data.
		 * @return array|string Return the array of referral coupons if user_id provided or return the coupon code if coupon id is provided.
		 */
		public function get_referral_coupon( $args = array() ) {

			if ( empty( $args ) ) {
				return array();
			}

			if ( ! empty( $args['user_id'] ) ) {
				$coupons = get_posts(
					array(
						'meta_key'    => 'afwc_referral_coupon_of', // phpcs:ignore
						'meta_value'  => $args['user_id'], // phpcs:ignore
						'post_type'   => 'shop_coupon',
						'post_status' => 'publish',
						'order'       => 'ASC',
						'numberposts' => -1,
					)
				);

				if ( empty( $coupons ) ) {
					return array();
				}

				$coupons_list = array();

				foreach ( $coupons as $coupon ) {
					if ( ! empty( $coupon->ID ) && ! empty( $coupon->post_title ) ) {
						$coupons_list[ $coupon->ID ] = $coupon->post_title;
					}
				}

				return $coupons_list;
			} elseif ( ! empty( $args['coupon_id'] ) ) {
				$coupon = new WC_Coupon( $args['coupon_id'] );
				return ( is_object( $coupon ) && is_callable( array( $coupon, 'get_code' ) ) ) ? $coupon->get_code() : '';
			}

			return array();
		}

		/**
		 * Handle hit if the referral coupon is applied.
		 *
		 * @param string $coupon_code The coupon code.
		 */
		public function coupon_applied( $coupon_code = null ) {

			if ( empty( $coupon_code ) ) {
				return;
			}

			$coupon = new WC_Coupon( $coupon_code );
			if ( ! $coupon instanceof WC_Coupon ) {
				return;
			}

			$affiliate_id = $coupon->get_meta( 'afwc_referral_coupon_of', true );
			if ( empty( $affiliate_id ) ) {
				return;
			}

			$afwc = Affiliate_For_WooCommerce::get_instance();
			$afwc->handle_hit( $affiliate_id );
		}

		/**
		 * Given a coupon code, return some params to show with the coupon.
		 *
		 * @param string $coupon_code The coupon code.
		 * @return array
		 */
		public function get_coupon_params( $coupon_code = '' ) {
			if ( empty( $coupon_code ) ) {
				return;
			}

			$coupon_params = array();

			$coupon                 = new WC_Coupon( $coupon_code );
			$coupon_discount_type   = ( is_object( $coupon ) && is_callable( array( $coupon, 'get_discount_type' ) ) ) ? $coupon->get_discount_type() : '';
			$coupon_discount_amount = ( is_object( $coupon ) && is_callable( array( $coupon, 'get_amount' ) ) ) ? $coupon->get_amount() : '';

			$coupon_params = array(
				'discount_type'   => $coupon_discount_type,
				'discount_amount' => $coupon_discount_amount,
			);

			return $coupon_params;
		}
	}

}

return new AFWC_Coupon();
