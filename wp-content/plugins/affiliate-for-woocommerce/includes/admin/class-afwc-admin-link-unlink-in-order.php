<?php
/**
 * Class for Affiliates Order linking and Unlinking
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       2.1.1
 * @version     1.3.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

if ( ! class_exists( 'AFWC_Admin_Link_Unlink_In_Order' ) ) {

	/**
	 * Main class for Affiliate Order linking and Unlinking functionality
	 */
	class AFWC_Admin_Link_Unlink_In_Order {

		/**
		 * Variable to hold instance of AFWC_Admin_Link_Unlink_In_Order
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Admin_Link_Unlink_In_Order Singleton object of this class
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_afwc_custom_box' ) );
			add_action( 'woocommerce_process_shop_order_meta', array( $this, 'link_unlink_affiliate_in_order' ), 10, 2 );
		}

		/**
		 * Function to add custom meta box in order add/edit screen.
		 */
		public function add_afwc_custom_box() {

			$screen = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
					? wc_get_page_screen_id( 'shop-order' )
					: 'shop_order';

			if ( ! in_array( $screen, array( 'woocommerce_page_wc-orders', 'shop_order' ), true ) ) {
				return;
			}

			add_meta_box( 'afwc_order', _x( 'Affiliate details', 'Affiliate\'s order meta box title', 'affiliate-for-woocommerce' ), array( $this, 'affiliate_in_order' ), $screen, 'side', 'low' );

		}

		/**
		 * Function to add/remove affiliate from an order.
		 *
		 * @param objec $post_or_order_object The Post/Order object.
		 */
		public function affiliate_in_order( $post_or_order_object ) {
			$order = ( $post_or_order_object instanceof WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
			// $post_or_order_object should not be used directly below this point.

			if ( ! $order instanceof WC_Order ) {
				return;
			}

			$order_id = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : 0;
			if ( empty( $order_id ) ) {
				return;
			}

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_register_script( 'affiliate-user-search', AFWC_PLUGIN_URL . '/assets/js/affiliate-search.js', array( 'jquery', 'wp-i18n' ), $plugin_data['Version'], true );
			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'affiliate-user-search', 'affiliate-for-woocommerce' );
			}
			wp_enqueue_script( 'affiliate-user-search' );

			wp_localize_script(
				'affiliate-user-search',
				'affiliateParams',
				array(
					'ajaxurl'        => admin_url( 'admin-ajax.php' ),
					'security'       => wp_create_nonce( 'afwc-search-affiliate-users' ),
					'allowSelfRefer' => afwc_allow_self_refer(),
				)
			);

			$is_commission_recorded = $order->get_meta( 'is_commission_recorded', true );

			$afwc_api       = new AFWC_API();
			$affiliate_data = is_callable( array( $afwc_api, 'get_affiliate_by_order' ) ) ? $afwc_api->get_affiliate_by_order( $order_id ) : array();

			$user_string = '';
			if ( 'yes' === $is_commission_recorded && ! empty( $affiliate_data ) ) {
				$user_id = afwc_get_user_id_based_on_affiliate_id( $affiliate_data['affiliate_id'] );
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
			}

			$allow_clear = ( isset( $affiliate_data['status'] ) && 'paid' === $affiliate_data['status'] ) ? 'false' : 'true';
			$disabled    = ( isset( $affiliate_data['status'] ) && 'paid' === $affiliate_data['status'] ) ? 'disabled' : '';

			?>
			<div class="options_group afwc-field">
				<p class="form-field">
					<label for="afwc_referral_order_of"><?php esc_attr_e( 'Assigned to affiliate', 'affiliate-for-woocommerce' ); ?></label>
					<?php echo wp_kses_post( wc_help_tip( _x( 'Search affiliate by email, username, name or user id to assign this order to them. Affiliates will see this order in their My account > Affiliates > Reports.', 'help tip for search and assign affiliate', 'affiliate-for-woocommerce' ) ) ); ?>
					<br><br>
					<select id="afwc_referral_order_of" name="afwc_referral_order_of" style="width: 100%;" class="wc-afw-customer-search" data-placeholder="<?php echo esc_attr_x( 'Search by email, username or name', 'affiliate search placeholder', 'affiliate-for-woocommerce' ); ?>" data-allow-clear="<?php echo esc_attr( $allow_clear ); ?>" data-action="afwc_json_search_affiliates" <?php echo esc_attr( $disabled ); ?>>
						<?php
						if ( ! empty( $user_id ) ) {
							?>
							<option value="<?php echo esc_attr( $user_id ); ?>" selected="selected"><?php echo esc_html( htmlspecialchars( wp_kses_post( $user_string ) ) ); ?><option>
							<?php
						}
						?>
					</select>
				</p>
			</div>
			<?php
		}


		/**
		 * Function to do database updates when linking/unlinking affiliate from the order.
		 *
		 * @param int    $order_id The Order ID.
		 * @param object $order    The Order Object.
		 */
		public function link_unlink_affiliate_in_order( $order_id = 0, $order = null ) {

			if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) { // phpcs:ignore
				return;
			}

			if ( empty( $order_id ) ) {
				return;
			}

			global $wpdb;

			$affiliate_id = isset( $_POST['afwc_referral_order_of'] ) ? wc_clean( wp_unslash( $_POST['afwc_referral_order_of'] ) ) : ''; // phpcs:ignore

			if ( ! empty( $affiliate_id ) ) {
				$old_affiliate_id = $wpdb->get_var( // phpcs:ignore
					$wpdb->prepare(
						"SELECT IFNULL( (CASE WHEN status = 'paid' THEN -1 ELSE affiliate_id END), 0 ) as affiliate_id
							FROM {$wpdb->prefix}afwc_referrals
							WHERE post_id = %d AND reference = ''",
						$order_id
					)
				);

				if ( ! empty( $old_affiliate_id ) ) {
					// Return if the commission status is paid.
					if ( -1 === $old_affiliate_id ) {
						return;
					}

					// Check if old affiliate id and new affiliate is different.
					if ( $old_affiliate_id !== $affiliate_id ) {

						// Unlink the old affiliate and link to the new affiliate on order.
						if ( $this->unlink_affiliate_from_order( $order_id ) ) {
							$this->link_affiliate_on_order( $order_id, $affiliate_id );
						}
					}
				} else {
					// Directly assign affiliate to order if there is no assigned affiliate.
					$this->link_affiliate_on_order( $order_id, $affiliate_id );
				}
			} else {
				// Unlinking and deleting.
				$this->unlink_affiliate_from_order( $order_id );
			}
		}

		/**
		 * Function to unlink the affiliate by order id.
		 *
		 * @param int $order_id The Order ID.
		 * @return bool.
		 */
		public function unlink_affiliate_from_order( $order_id = 0 ) {
			if ( empty( $order_id ) ) {
				return false;
			}

			global $wpdb;

			// Delete referral data of the order.
			$delete_referral = boolval(
				$wpdb->query( // phpcs:ignore
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}afwc_referrals WHERE post_id = %d AND status != %s",
						$order_id,
						esc_sql( 'paid' )
					)
				)
			);

			if ( true === $delete_referral ) {
				$order = wc_get_order( $order_id );
				if ( ! $order instanceof WC_Order ) {
					return false;
				}

				// Delete the affiliate meta data related to order id.
				$order->delete_meta_data( 'is_commission_recorded' );
				$order->delete_meta_data( 'afwc_order_valid_plans' );
				$order->delete_meta_data( 'afwc_set_commission' );
				$order->delete_meta_data( 'afwc_parent_commissions' );
				$updated_order_id = $order->save();

				// Delete the affiliate meta data related to order id in postmeta table.
				// Additionally firing due to delay in deleting via delete_meta_data causing issues of re-meta insertion from orders screen.
				if ( 'yes' === get_option( 'woocommerce_custom_orders_table_data_sync_enabled', 'no' ) ) {
					$result = boolval(
						$wpdb->query( // phpcs:ignore
							$wpdb->prepare(
								"DELETE FROM {$wpdb->prefix}postmeta
									WHERE post_id = %d
									AND meta_key IN ('is_commission_recorded','afwc_order_valid_plans','afwc_set_commission','afwc_parent_commissions')",
								$order_id
							)
						)
					);
				}

				/**
				 * Here we don't know if meta is actually deleted since WooCommerce does not send any confirmation.
				 * So we are doing additional sanity checks.
				 */
				if ( empty( $updated_order_id ) || $updated_order_id !== $order_id || is_wp_error( $updated_order_id ) ) {
					return false;
				}

				return true;
			}

			return false;
		}

		/**
		 * Function to assign the affiliate to an order.
		 *
		 * @param int $order_id     The Order ID.
		 * @param int $affiliate_id The Affiliate ID.
		 * @return void.
		 */
		public function link_affiliate_on_order( $order_id = 0, $affiliate_id = 0 ) {

			if ( empty( $order_id ) || empty( $affiliate_id ) ) {
				return;
			}

			$affiliate_api = AFWC_API::get_instance();
			$affiliate_api->track_conversion( $order_id, $affiliate_id, '', array( 'is_affiliate_eligible' => true ) );
			$order      = wc_get_order( $order_id );
			$new_status = is_object( $order ) && is_callable( array( $order, 'get_status' ) ) ? $order->get_status() : '';
			$affiliate_api->update_referral_status( $order_id, '', $new_status );

		}

	}

}

return new AFWC_Admin_Link_Unlink_In_Order();
