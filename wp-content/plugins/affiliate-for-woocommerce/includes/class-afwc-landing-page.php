<?php
/**
 * Main class for Affiliates Landing page functionality.
 * It will support on all single posts.
 *
 * @package  affiliate-for-woocommerce/includes/
 * @since    6.24.0
 * @version  1.0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Landing_Page' ) ) {

	/**
	 * Main class for Affiliate Landing page functionality.
	 */
	class AFWC_Landing_Page {

		/**
		 * Variable to hold instance of AFWC_Landing_Page
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Landing_Page Singleton object of this class
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

			if ( self::is_enabled() ) {
				add_action( 'add_meta_boxes', array( $this, 'landing_page_meta_box' ), 10, 2 );
				add_action( 'save_post', array( $this, 'save_landing_page_data' ), 10, 2 );

				// Track the visitors.
				add_action( 'template_redirect', array( $this, 'track_visitor' ) );

				// Hook to format the merge tags for landing page content.
				add_filter( 'the_content', array( $this, 'support_merge_tags' ) );
			}
		}

		/**
		 * Method to check whether the landing page feature is enabled.
		 *
		 * @return bool Return if enabled.
		 */
		public static function is_enabled() {
			return 'yes' === get_option( 'afwc_enable_landing_pages', 'no' );
		}

		/**
		 * Retrieves the affiliate ID by post ID.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return int A positive Affiliate ID if found otherwise 0.
		 */
		public function get_affiliate_id( $post_id = 0 ) {

			// Return a zero value if the post id is not provided.
			if ( empty( $post_id ) ) {
				return 0;
			}

			// Get the affiliate ID from post meta.
			$affiliate_id = get_post_meta( $post_id, 'afwc_landing_page_for', true );

			return ! empty( $affiliate_id ) ? absint( $affiliate_id ) : 0;
		}

		/**
		 * Track visitor for the landing page.
		 *
		 * @return void
		 */
		public function track_visitor() {
			// Get the affiliate id by the current single post page.
			$affiliate_id = $this->get_affiliate_id_by_current_singular_post();

			// Return if the affiliate is not assigned to the current post.
			if ( empty( $affiliate_id ) ) {
				return;
			}

			global $affiliate_for_woocommerce;

			if ( is_callable( array( $affiliate_for_woocommerce, 'handle_hit' ) ) ) {
				$affiliate_for_woocommerce->handle_hit( $affiliate_id ); // Handle the hit for current post page.
			}
		}

		/**
		 * Method to check whether the current page supported for affiliate landing page.
		 *
		 * @return bool Return true it supports otherwise false.
		 */
		public function is_singular_affiliate_landing_page() {
			$supported_post_types = $this->get_supported_post_types();

			return (bool) apply_filters(
				'afwc_is_singular_affiliate_landing_page',
				! empty( $supported_post_types ) && is_singular( $supported_post_types )
			);
		}

		/**
		 * Register the meta box.
		 *
		 * @param string  $post_type The post type.
		 * @param WP_Post $post      The Post object.
		 *
		 * @return void.
		 */
		public function landing_page_meta_box( $post_type = '', $post = null ) {

			if ( empty( $post_type ) || empty( $post ) || empty( $post->ID ) ) {
				return;
			}

			$post_types = $this->get_supported_post_types();
			// Return if the post type is not supported.
			if ( empty( $post_types ) || ! in_array( $post_type, $post_types, true ) ) {
				return;
			}

			$excluded_post_ids = $this->get_excluded_post_ids();

			// Return if post ID is excluded from landing page.
			if ( ! empty( $excluded_post_ids ) && in_array( intval( $post->ID ), $excluded_post_ids, true ) ) {
				return;
			}

			add_meta_box(
				'afwc_landing_page', // Meta box ID.
				_x( 'Affiliate Landing Page', 'Title for landing page meta box', 'affiliate-for-woocommerce' ),
				array( $this, 'render_landing_page_meta_box' ),
				$post_types,
				'side'
			);
		}

		/**
		 * Render the meta box.
		 *
		 * @param Object $post The post object.
		 *
		 * @return void.
		 */
		public function render_landing_page_meta_box( $post = null ) {
			if ( empty( $post ) || ! is_object( $post ) || empty( $post->ID ) ) {
				return;
			}

			global $affiliate_for_woocommerce;

			$affiliate_id = get_post_meta( $post->ID, 'afwc_landing_page_for', true );

			// Add an nonce field for our field.
			wp_nonce_field( 'afwc_lp_nonce', 'afwc_lp_nonce' );

			?>
			<div class="options_group afwc-field">
				<label for="afwc_landing_page_for"><?php echo esc_html_x( 'Assign to affiliate', 'Label for the landing page assignment field', 'affiliate-for-woocommerce' ); ?></label>
				<p class="form-field">
					<?php is_callable( array( $affiliate_for_woocommerce, 'render_affiliate_search' ) ) ? $affiliate_for_woocommerce->render_affiliate_search( 'afwc_landing_page_for', array( 'affiliate_id' => $affiliate_id ) ) : ''; ?>
				</p>
				<p><?php echo esc_html_x( 'This will be set as a landing page for the selected affiliate.', 'Description for assign to affiliate - landing page meta box field', 'affiliate-for-woocommerce' ); ?></p>
				<p> 
				<?php
					/* translators: 1: Merge tag for affiliate Id 2: Merge tag for affiliate name 3: Merge tag for affiliate link  */
					echo wp_kses_post( sprintf( _x( 'Available merge tags: <code>%1$s</code> <code>%2$s</code> <code>%3$s</code>', 'Show the available merge tags', 'affiliate-for-woocommerce' ), '{afwc_affiliate_id}', '{afwc_affiliate_name}', '{afwc_affiliate_link}' ) );
				?>
				</p>
			</div>

			<?php
		}

		/**
		 * Save post meta when the save_post action is called
		 *
		 * @param int    $post_id The post ID.
		 * @param Object $post    The post Object.
		 *
		 * @return void
		 */
		public function save_landing_page_data( $post_id = 0, $post = null ) {
			// Return if the nonce is not validated or post ID is not assigned.
			if ( empty( $_POST['afwc_lp_nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['afwc_lp_nonce'] ) ), 'afwc_lp_nonce' ) || empty( $post_id ) ) { // phpcs:ignore
				return;
			}

			// If this is an autosave, our form has not been submitted.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Check the user's permissions.
			if ( ! empty( $post->post_type ) && 'page' === $post->post_type ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
			}

			$assigned_affiliate = ! empty( $_POST['afwc_landing_page_for'] ) ? absint( wc_clean( $_POST['afwc_landing_page_for'] ) ) : 0; // phpcs:ignore

			if ( ! empty( $assigned_affiliate ) ) {
				// Update post meta.
				update_post_meta( $post_id, 'afwc_landing_page_for', $assigned_affiliate );
			} else {
				// Delete post meta.
				delete_post_meta( $post_id, 'afwc_landing_page_for' );
			}
		}

		/**
		 * Method to get supported post types.
		 *
		 * @return array
		 */
		public function get_supported_post_types() {
			return array( 'post', 'page', 'product' );
		}

		/**
		 * Retrieves the assigned landing page IDs by affiliate ID.
		 *
		 * @param int $affiliate_id The affiliate ID.
		 *
		 * @return array Return the array of post IDs.
		 * @throws Exception When unable to fetch the landing page IDs.
		 */
		public function get_pages_by_affiliate_id( $affiliate_id = 0 ) {
			if ( empty( $affiliate_id ) ) {
				return array();
			}

			global $wpdb;

			try {
				$affiliate_id         = intval( $affiliate_id );
				$supported_post_types = $this->get_supported_post_types();
				$post_type_list       = ! empty( $supported_post_types ) ? array_map( 'esc_sql', $supported_post_types ) : array();

				if ( empty( $post_type_list ) ) {
					return array();
				}

				$results = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT p.ID
						FROM {$wpdb->posts} p
						INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
						WHERE p.post_type IN (" . implode( ',', array_fill( 0, count( $post_type_list ), '%s' ) ) . ')
						AND pm.meta_key = %s
						AND pm.meta_value = %d',
						array_merge( $post_type_list, array( esc_sql( 'afwc_landing_page_for' ), $affiliate_id ) )
					),
					'ARRAY_A'
				);

				if ( null === $results ) {
					/* translators: Affiliate ID */
					throw new Exception( sprintf( _x( 'Database query failed while fetching the landing page for %d', 'Exception message for landing page fetching query failed', 'affiliate-for-woocommerce' ), $affiliate_id ) );
				}

				$post_ids = ! empty( $results ) ? wp_list_pluck( $results, 'ID' ) : array();
				return ! empty( $post_ids ) ? $this->filter_post_ids( $post_ids ) : array();
			} catch ( Exception $e ) {

				if ( is_callable( array( $e, 'getMessage' ) ) ) {
					Affiliate_For_WooCommerce::get_instance()->log( 'error', $e->getMessage() );
				}

				return array();
			}
		}

		/**
		 * Retrieves the assigned affiliate Id to the current single post page.
		 *
		 * @return int Return the affiliate ID.
		 */
		public function get_affiliate_id_by_current_singular_post() {
			// Return if the current singular page is not a affiliate landing page.
			if ( ! $this->is_singular_affiliate_landing_page() ) {
				return 0;
			}

			$current_post_id = $this->filter_post_ids( get_the_ID() );

			// Return if the current post ID is not found.
			if ( empty( $current_post_id ) ) {
				return 0;
			}

			$affiliate_id = $this->get_affiliate_id( $current_post_id );

			if ( empty( $affiliate_id ) ) {
				return 0;
			}

			$afwc_affiliate = new AFWC_Affiliate( $affiliate_id );

			return ! empty( $afwc_affiliate->affiliate_id ) ? intval( $afwc_affiliate->affiliate_id ) : 0;
		}

		/**
		 * Method to support the merge tags.
		 *
		 * @param string $content The post content.
		 *
		 * @return string Return the formatted content.
		 */
		public function support_merge_tags( $content = '' ) {
			if ( empty( $content ) ) {
				return $content;
			}

			// Get the affiliate id by the current single post page.
			$affiliate_id = $this->get_affiliate_id_by_current_singular_post();

			$afwc_merge_tags = AFWC_Merge_Tags::get_instance();
			return is_callable( array( $afwc_merge_tags, 'parse_content' ) ) ? $afwc_merge_tags->parse_content( $content, array( 'affiliate' => intval( $affiliate_id ) ) ) : $content;
		}

		/**
		 * Method to retrieve excluded post ids.
		 *
		 * @return array Return post ids.
		 */
		public function get_excluded_post_ids() {
			// Get the excluded products from Affiliate global setting.
			$product_ids = get_option( 'afwc_storewide_excluded_products', array() );

			$excluded_post_ids = apply_filters( 'afwc_get_excluded_landing_page_ids', $product_ids, array( 'source' => $this ) );

			return ! empty( $excluded_post_ids ) && is_array( $excluded_post_ids ) ? array_map( 'intval', $excluded_post_ids ) : array();
		}

		/**
		 * Method to filter the landing page post ID(s).
		 *
		 * @param int|array $post_ids The post Ids.
		 *
		 * @return array Return filtered post ids.
		 */
		public function filter_post_ids( $post_ids = array() ) {
			if ( empty( $post_ids ) ) {
				return $post_ids;
			}

			$excluded_post_ids = $this->get_excluded_post_ids();

			// Return the original post ids if no posts for excluding.
			if ( empty( $excluded_post_ids ) || ! is_array( $excluded_post_ids ) ) {
				return $post_ids;
			}

			if ( is_array( $post_ids ) ) {
				// Remove the of post Ids with excluded post ids.
				return array_diff( $post_ids, $excluded_post_ids );
			} elseif ( is_scalar( $post_ids ) ) {
				// Check the provided single $post id with excluded post ids.
				return in_array( intval( $post_ids ), $excluded_post_ids, true ) ? 0 : $post_ids;
			}

			return $post_ids;
		}
	}

}

return AFWC_Landing_Page::get_instance();
