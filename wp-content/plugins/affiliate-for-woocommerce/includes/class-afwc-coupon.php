<?php
/**
 * Main class for Affiliates Coupon functionality
 *
 * @package     affiliate-for-woocommerce/includes/
 * @version     1.0.3
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
		 * Variable to hold instance of AFWC_Admin_Notifications
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 *  Constructor
		 */
		public function __construct() {

			$use_referral_coupons = get_option( 'afwc_use_referral_coupons', 'no' );
			if ( is_admin() && 'yes' === $use_referral_coupons ) {
				add_action( 'woocommerce_coupon_options', array( $this, 'affiliate_restriction' ), 10, 2 );
			}
			add_action( 'save_post', array( $this, 'handle_affiliate_coupon' ), 10, 2 );

			add_action( 'woocommerce_applied_coupon', array( $this, 'coupon_applied' ) );

			add_action( 'wp_ajax_afwc_json_search_affiliates', array( $this, 'afwc_json_search_affiliates' ), 1, 2 );

		}

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Admin_Affiliate_Users Singleton object of this class
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
		 * @param string $x string.
		 * @param string $attribute string.
		 * @return void
		 */
		public function afwc_json_search_affiliates( $x = '', $attribute = '' ) {

			check_ajax_referer( 'afwc-search-affiliate-users', 'security' );

			$term = ( ! empty( $_GET['term'] ) ) ? (string) urldecode( stripslashes( wp_strip_all_tags( $_GET ['term'] ) ) ) : ''; // phpcs:ignore
			if ( empty( $term ) ) {
				die();
			}

			$is_affiliate         = array();
			$affiliate_role_users = array();

			$args         = array(
				'search'     => $term,
				'meta_key'   => 'afwc_is_affiliate', // phpcs:ignore
				'meta_value' => 'yes', // phpcs:ignore
			);
			$is_affiliate = get_users( $args );

			$affiliate_user_roles = get_option( 'affiliate_users_roles', '' );
			if ( ! empty( $affiliate_user_roles ) ) {
				$args                 = array(
					'search'   => $term,
					'role__in' => $affiliate_user_roles,
				);
				$affiliate_role_users = get_users( $args );
			}

			$affiliate_users = array_merge( $is_affiliate, $affiliate_role_users );

			$user = array();
			if ( $affiliate_users ) {
				foreach ( $affiliate_users as $users ) {
					$user[ $users->data->ID ] = $users->data->display_name . ' (#' . $users->data->ID . ' - ' . $users->data->user_email . ')';
				}
			}

			echo wp_json_encode( $user );
			die();

		}

		/**
		 * Assign a coupon to an affiliate
		 *
		 * @param int    $coupon_id The Coupon ID.
		 * @param object $coupon The Coupon Object.
		 */
		public function affiliate_restriction( $coupon_id = 0, $coupon = null ) {

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_register_script( 'affiliate-user-search', AFWC_PLUGIN_URL . '/assets/js/affiliate-search.js', array(), $plugin_data['Version'], true );
			wp_enqueue_script( 'affiliate-user-search' );

			$affiliate_params = array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'afwc_security' => wp_create_nonce( 'afwc-search-affiliate-users' ),
			);
			wp_localize_script( 'affiliate-user-search', 'affiliate_params', $affiliate_params );

			if ( ! empty( $coupon_id ) ) {
				$user_id = get_post_meta( $coupon_id, 'afwc_referral_coupon_of', true );
				if ( ! empty( $user_id ) ) {
					$user        = get_user_by( 'id', $user_id );
					$user_string = sprintf(
						/* translators: 1: user display name 2: user ID 3: user email */
						esc_html__( '%1$s (#%2$s &ndash; %3$s)', 'affiliate-for-woocommerce' ),
						$user->display_name,
						absint( $user_id ),
						$user->user_email
					);
				} else {
					$user_string = '';
					$user_id     = '';
				}
			}

			?>
			<div class="options_group afwc-field">
				<p class="form-field">
					<label for="afwc_referral_coupon_of"><?php esc_attr_e( 'Assign to affiliate', 'affiliate-for-woocommerce' ); ?></label>
					<select id="afwc_referral_coupon_of" name="afwc_referral_coupon_of" style="width: 50%;" class="wc-afw-customer-search" data-placeholder="<?php esc_attr_e( 'Search by affiliate id or email address', 'affiliate-for-woocommerce' ); ?>" data-allow_clear="true" data-action="afwc_json_search_affiliates">
						<option value="<?php echo esc_attr( $user_id ); ?>" selected="selected"><?php echo esc_html( htmlspecialchars( wp_kses_post( $user_string ) ) ); ?><option>
					</select>
					<?php echo wp_kses_post( wc_help_tip( __( 'Search affiliates by id or email address to assign a coupon to them. Affiliates will see this coupon in their account.', 'affiliate-for-woocommerce' ) ) ); ?>
				</p>
			</div>
			<?php

		}

		/**
		 * Function to handle affiliate coupon changes
		 *
		 * @param int    $post_id The post id.
		 * @param object $post The post object.
		 */
		public function handle_affiliate_coupon( $post_id, $post ) {
			if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( is_int( wp_is_post_revision( $post ) ) ) {
				return;
			}
			if ( is_int( wp_is_post_autosave( $post ) ) ) {
				return;
			}
			if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) { // phpcs:ignore
				return;
			}
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			if ( 'shop_coupon' !== $post->post_type ) {
				return;
			}

			$affiliate_id = ( ! empty( $_POST['afwc_referral_coupon_of'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_referral_coupon_of'] ) ) : ''; // phpcs:ignore
			if ( ! empty( $affiliate_id ) ) {
				update_post_meta( $post_id, 'afwc_referral_coupon_of', $affiliate_id );
			} else {
				delete_post_meta( $post_id, 'afwc_referral_coupon_of' );
			}

		}

		/**
		 * Get referral coupon
		 *
		 * @param  array $args The data.
		 * @return array/string
		 */
		public function get_referral_coupon( $args = array() ) {

			if ( empty( $args ) ) {
				return false;
			}

			if ( ! empty( $args['user_id'] ) ) {
				$params  = array(
							'meta_key'    => 'afwc_referral_coupon_of', // phpcs:ignore
							'meta_value'  => $args['user_id'], // phpcs:ignore
							'post_type'   => 'shop_coupon',
							'post_status' => 'publish',
							'order'       => 'ASC',
				);
				$coupons = get_posts( $params );

				$coupons_list = array();
				if ( ! empty( $coupons ) ) {
					foreach ( $coupons as $coupon ) {
						$coupons_list[ $coupon->ID ] = $coupon->post_title;
					}
				}
				return $coupons_list;
			} elseif ( ! empty( $args['coupon_id'] ) ) {
				$coupon      = new WC_Coupon( $args['coupon_id'] );
				$coupon_code = ( is_object( $coupon ) && is_callable( array( $coupon, 'get_code' ) ) ) ? $coupon->get_code() : '';
				return $coupon_code;
			}

			return false;

		}

		/**
		 * Handle hit if the referral coupon is applied
		 *
		 * @param string $coupon_code The coupon code.
		 */
		public function coupon_applied( $coupon_code = null ) {

			if ( empty( $coupon_code ) ) {
				return;
			}

			$coupon    = new WC_Coupon( $coupon_code );
			$coupon_id = ( is_object( $coupon ) && is_callable( array( $coupon, 'get_id' ) ) ) ? $coupon->get_id() : 0;
			if ( ! empty( $coupon_id ) ) {
				$affiliate_id = get_post_meta( $coupon_id, 'afwc_referral_coupon_of', true );
				$afwc         = Affiliate_For_WooCommerce::get_instance();
				$afwc->handle_hit( $affiliate_id );
			}

		}

		/**
		 * Given a coupon code, return some params to show with the coupon.
		 *
		 * @param string $coupon_code The coupon code.
		 * @return array
		 */
		public function get_coupon_params( $coupon_code ) {
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
