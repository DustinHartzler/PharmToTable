<?php
/**
 * Main class for Affiliates My Account
 *
 * @package     affiliate-for-woocommerce/includes/frontend/
 * @version     1.3.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_My_Account' ) ) {

	/**
	 * Main class for Affiliates My Account
	 */
	class AFWC_My_Account {

		/**
		 * Variable to hold instance of AFWC_My_Account
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Endpoint
		 *
		 * @var $endpoint
		 */
		public $endpoint;

		/**
		 * Constructor
		 */
		private function __construct() {

			$this->endpoint = get_option( 'woocommerce_myaccount_afwc_dashboard_endpoint', 'afwc-dashboard' );

			add_action( 'init', array( $this, 'endpoint' ) );

			add_action( 'wp_loaded', array( $this, 'afw_myaccount' ) );

			add_action( 'wc_ajax_afwc_reload_dashboard', array( $this, 'ajax_reload_dashboard' ) );
			add_action( 'wc_ajax_afwc_load_more_products', array( $this, 'ajax_load_more_products' ) );
			add_action( 'wc_ajax_afwc_load_more_referrals', array( $this, 'ajax_load_more_referrals' ) );
			add_action( 'wc_ajax_afwc_load_more_payouts', array( $this, 'ajax_load_more_payouts' ) );
			add_action( 'wc_ajax_afwc_save_account_details', array( $this, 'afwc_save_account_details' ) );
			add_action( 'wc_ajax_afwc_save_ref_url_identifier', array( $this, 'afwc_save_ref_url_identifier' ) );

			// To provide admin setting different endpoint for affiliate.
			add_action( 'init', array( $this, 'endpoint_hooks' ) );
		}

		/**
		 * Get single instance of AFWC_My_Account
		 *
		 * @return AFWC_My_Account Singleton object of AFWC_My_Account
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Function to add affiliates endpoint to My Account.
		 */
		public function endpoint() {
			add_rewrite_endpoint( $this->endpoint, EP_PAGES );
			$this->check_if_flushed_rules();
		}

		/**
		 * Function to flush rewrite rules if haven't already.
		 */
		public function check_if_flushed_rules() {
			$check_flushed_rules = get_option( 'afwc_flushed_rules', 'notfound' );
			if ( 'notfound' === $check_flushed_rules ) {
				flush_rewrite_rules();
				update_option( 'afwc_flushed_rules', 'found', 'no' );
			}
		}

		/**
		 * Function to add endpoint in My Account if user is an affiliate
		 */
		public function afw_myaccount() {

			if ( ! is_user_logged_in() ) {
				return;
			}
			$user = wp_get_current_user();
			if ( ! is_object( $user ) || empty( $user->ID ) ) {
				return;
			}

			$is_affiliate = afwc_is_user_affiliate( $user );
			if ( 'yes' === $is_affiliate ) {
				add_filter( 'query_vars', array( $this, 'afw_add_query_vars' ), 0 );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
				add_action( 'wp_footer', array( $this, 'footer_styles_scripts' ) );
			}
			if ( 'yes' === $is_affiliate || 'not_registered' === $is_affiliate ) {
				add_filter( 'woocommerce_account_menu_items', array( $this, 'menu_item' ) );
				add_action( 'woocommerce_account_' . $this->endpoint . '_endpoint', array( $this, 'endpoint_content' ) );
				// Change the My Account page title.
				add_filter( 'the_title', array( $this, 'afw_endpoint_title' ) );
			}

		}

		/**
		 * Add new query var.
		 *
		 * @param array $vars The query vars.
		 * @return array
		 */
		public function afw_add_query_vars( $vars ) {
			$vars[] = $this->endpoint;
			return $vars;
		}

		/**
		 * Set endpoint title.
		 *
		 * @param string $title The title of coupon page.
		 * @return string
		 */
		public function afw_endpoint_title( $title ) {
			global $wp_query;

			$is_endpoint = isset( $wp_query->query_vars[ $this->endpoint ] );

			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				if ( 'resources' === $wp_query->query_vars[ $this->endpoint ] ) {
					$title = __( 'Affiliate Resources', 'affiliate-for-woocommerce' );
				} elseif ( 'campaigns' === $wp_query->query_vars[ $this->endpoint ] ) {
					$title = __( 'Affiliate Campaigns', 'affiliate-for-woocommerce' );
				} else {
					$user = wp_get_current_user();
					if ( is_object( $user ) && ! empty( $user->ID ) ) {
						$is_affiliate = afwc_is_user_affiliate( $user );
					}
					$title = ( 'not_registered' === $is_affiliate ) ? __( 'Register as an Affiliate', 'affiliate-for-woocommerce' ) : __( 'Affiliate Dashboard', 'affiliate-for-woocommerce' );
				}
				remove_filter( 'the_title', array( $this, 'afw_endpoint_title' ) );
			}

			return $title;
		}

		/**
		 * Function to add menu items in My Account.
		 *
		 * @param array $menu_items menu items.
		 * @return array $menu_items menu items.
		 */
		public function menu_item( $menu_items = array() ) {
			$user = wp_get_current_user();
			if ( is_object( $user ) && $user instanceof WP_User && ! empty( $user->ID ) ) {
				$is_affiliate              = afwc_is_user_affiliate( $user );
				$insert_at_index           = array_search( 'edit-account', array_keys( $menu_items ), true );
				$afwc_is_registration_open = apply_filters( 'afwc_is_registration_open', 'yes', array( 'source' => $this ) );
				if ( 'yes' === $is_affiliate ) {
					$menu_item = array( $this->endpoint => __( 'Affiliate', 'affiliate-for-woocommerce' ) );
				}
				if ( 'not_registered' === $is_affiliate && 'yes' === $afwc_is_registration_open ) {
					$menu_item = array( $this->endpoint => __( 'Register as an affiliate', 'affiliate-for-woocommerce' ) );
				}
				if ( ! empty( $menu_item ) ) {
					$new_menu_items = array_merge(
						array_slice( $menu_items, 0, $insert_at_index ),
						$menu_item,
						array_slice( $menu_items, $insert_at_index, null )
					);
					return $new_menu_items;
				}
			}
			return $menu_items;
		}

		/**
		 * Function to check if current page has affiliates' endpoint.
		 */
		public function is_afwc_endpoint() {
			global $wp;

			if ( ! empty( $wp->query_vars ) && array_key_exists( $this->endpoint, $wp->query_vars ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Function to add styles.
		 */
		public function enqueue_styles_scripts() {
			if ( $this->is_afwc_endpoint() ) {
				$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
				$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				if ( ! wp_script_is( 'jquery-ui-datepicker' ) ) {
					wp_enqueue_script( 'jquery-ui-datepicker' );
				}
				if ( ! wp_style_is( 'afwc-admin-dashboard-font', 'registered' ) ) {
					wp_register_style( 'afwc-admin-dashboard-font', AFWC_PLUGIN_URL . '/assets/fontawesome/css/all' . $suffix . '.css', array(), $plugin_data['Version'] );
				}
				wp_enqueue_style( 'afwc-admin-dashboard-font' );
				wp_enqueue_style( 'afwc-my-account', AFWC_PLUGIN_URL . '/assets/css/afwc-my-account.css', array(), $plugin_data['Version'] );
				if ( ! wp_style_is( 'jquery-ui-style', 'registered' ) ) {
					wp_register_style( 'jquery-ui-style', WC()->plugin_url() . '/assets/css/jquery-ui/jquery-ui' . $suffix . '.css', array(), WC()->version );
				}
				wp_enqueue_style( 'jquery-ui-style' );
			}
		}

		/**
		 * Function to add scripts in footer.
		 */
		public function footer_styles_scripts() {
			global $wp;
			if ( $this->is_afwc_endpoint() ) {
				if ( ! wp_script_is( 'jquery' ) ) {
					wp_enqueue_script( 'jquery' );
				}
				if ( ! class_exists( 'WC_AJAX' ) ) {
					include_once WP_PLUGIN_DIR . '/woocommerce/includes/class-wc-ajax.php';
				}
				$user = wp_get_current_user();
				if ( ! is_object( $user ) || empty( $user->ID ) ) {
					return;
				}
				$affiliate_id = get_affiliate_id_based_on_user_id( $user->ID );
				if ( 'campaigns' === $wp->query_vars[ $this->endpoint ] ) {
					$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
					// Dashboard scripts.
					wp_register_script( 'mithril', AFWC_PLUGIN_URL . '/assets/js/mithril/mithril.min.js', array(), $plugin_data['Version'], true );
					wp_register_script( 'afwc-campaigns-styles', AFWC_PLUGIN_URL . '/assets/js/styles.js', array( 'mithril' ), $plugin_data['Version'], true );
					wp_register_script( 'afwc-campaigns-dashboard', AFWC_PLUGIN_URL . '/assets/js/frontend.js', array( 'afwc-campaigns-styles' ), $plugin_data['Version'], true );
					if ( ! wp_script_is( 'afwc-campaigns-dashboard' ) ) {
						wp_enqueue_script( 'afwc-campaigns-dashboard' );
					}
					$pname           = get_option( 'afwc_pname', 'ref' );
					$pname           = ( ! empty( $pname ) ) ? $pname : 'ref';
					$affiliate_id    = get_affiliate_id_based_on_user_id( $user->ID );
					$afwc_ref_url_id = get_user_meta( $user->ID, 'afwc_ref_url_id', true );
					$affiliate_id    = ( ! empty( $afwc_ref_url_id ) ) ? $afwc_ref_url_id : $affiliate_id;

					wp_localize_script(
						'afwc-campaigns-dashboard',
						'afwcDashboardParams',
						array(
							'security'           => wp_create_nonce( AFWC_AJAX_SECURITY ),
							'currencySymbol'     => AFWC_CURRENCY,
							'pname'              => $pname,
							'afwc_ref_url_id'    => $afwc_ref_url_id,
							'affiliate_id'       => $affiliate_id,
							'ajaxurl'            => admin_url( 'admin-ajax.php' ),
							'campaign_status'    => 'Active',
							'no_campaign_string' => __( 'No Campaign yet', 'affiliate-for-woocommerce' ),
						)
					);
					$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

					wp_register_style( 'afwc_frontend', AFWC_PLUGIN_URL . '/assets/css/frontend.css', array(), $plugin_data['Version'] );
					if ( ! wp_style_is( 'afwc_frontend' ) ) {
						wp_enqueue_style( 'afwc_frontend' );
					}
				}

				?>
				<!-- Affiliate For WooCommerce JavaScript Start -->
				<script type="text/javascript">
					jQuery(function(){
						if( window.innerWidth < 760 ) {
							jQuery('.afwc_products, .afwc_referrals, .afwc_payout_history').addClass('woocommerce-table shop_table shop_table_responsive order_details');
						} else {
							jQuery('.afwc_products, .afwc_referrals, .afwc_payout_history').removeClass('woocommerce-table shop_table shop_table_responsive order_details');
						}
						jQuery('body').on('click', '#afwc_load_more_products', function(e){
							e.preventDefault();
							let the_table = jQuery('table.afwc_products');
							let the_afwc = jQuery('#afwc_dashboard_wrapper');
							the_table.addClass( 'afwc-loading' );
							jQuery.ajax({
								url: '<?php echo esc_url( WC_AJAX::get_endpoint( 'afwc_load_more_products' ) ); ?>',
								type: 'post',
								dataType: 'html',
								data: {
									security: '<?php echo esc_js( wp_create_nonce( 'afwc-load-more-products' ) ); ?>',
									from: the_afwc.find('#afwc_from').val(),
									to: the_afwc.find('#afwc_to').val(),
									search: the_afwc.find('#afwc_search').val(),
									offset: the_table.find('tbody tr').length,
									affiliate: '<?php echo esc_attr( $affiliate_id ); ?>'
								},
								success: function( response ) {
									if ( response ) {
										the_table.find('tbody').append( response );
										let max_record = jQuery('#afwc_load_more_products').data('max_record');
										if ( the_table.find('tbody tr').length >= max_record ) {
											jQuery('#afwc_load_more_products').addClass('disabled').text('<?php echo esc_html__( 'No more data to load', 'affiliate-for-woocommerce' ); ?>');
										}
										the_table.removeClass( 'afwc-loading' );
									}
								}
							});
						});
						jQuery('body').on('click', '#afwc_load_more_referrals', function(){
							let the_table = jQuery('table.afwc_referrals');
							let the_afwc = jQuery('#afwc_dashboard_wrapper');
							the_table.addClass( 'afwc-loading' );
							jQuery.ajax({
								url: '<?php echo esc_url( WC_AJAX::get_endpoint( 'afwc_load_more_referrals' ) ); ?>',
								type: 'post',
								dataType: 'html',
								data: {
									security: '<?php echo esc_js( wp_create_nonce( 'afwc-load-more-referrals' ) ); ?>',
									from: the_afwc.find('#afwc_from').val(),
									to: the_afwc.find('#afwc_to').val(),
									search: the_afwc.find('#afwc_search').val(),
									offset: the_table.find('tbody tr').length,
									affiliate: '<?php echo esc_attr( $affiliate_id ); ?>'
								},
								success: function( response ) {
									if ( response ) {
										the_table.find('tbody').append( response );
										let max_record = jQuery('#afwc_load_more_referrals').data('max_record');
										if ( the_table.find('tbody tr').length >= max_record ) {
											jQuery('#afwc_load_more_referrals').addClass('disabled').text('<?php echo esc_html__( 'No more data to load', 'affiliate-for-woocommerce' ); ?>');
										}
										the_table.removeClass( 'afwc-loading' );
									}
								}
							});
						});

						jQuery('body').on('click', '#afwc_load_more_payouts', function(){
							let the_table = jQuery('table.afwc_payout_history');
							let the_afwc = jQuery('#afwc_dashboard_wrapper');
							the_table.addClass( 'afwc-loading' );
							jQuery.ajax({
								url: '<?php echo esc_url( WC_AJAX::get_endpoint( 'afwc_load_more_payouts' ) ); ?>',
								type: 'post',
								dataType: 'html',
								data: {
									security: '<?php echo esc_js( wp_create_nonce( 'afwc-load-more-payouts' ) ); ?>',
									from: the_afwc.find('#afwc_from').val(),
									to: the_afwc.find('#afwc_to').val(),
									search: the_afwc.find('#afwc_search').val(),
									offset: the_table.find('tbody tr').length,
									affiliate: '<?php echo esc_attr( $affiliate_id ); ?>'
								},
								success: function( response ) {
									if ( response ) {
										the_table.find('tbody').append( response );
										let max_record = jQuery('#afwc_load_more_payouts').data('max_record');
										if ( the_table.find('tbody tr').length >= max_record ) {
											jQuery('#afwc_load_more_payouts').addClass('disabled').text('<?php echo esc_html__( 'No more data to load', 'affiliate-for-woocommerce' ); ?>');
										}
										the_table.removeClass( 'afwc-loading' );
									}
								}
							});
						});
						jQuery('body').on('focus', '#afwc_from, #afwc_to', function(){
							load_datepicker( jQuery(this) );
						});
						function load_datepicker( element ) {
							if ( ! element.hasClass('hasDatepicker') ) {
								element.datepicker({
									dateFormat: "dd-M-yy",
									maxDate: 0,
									beforeShowDay: date_range,
									onSelect: dr_on_select
								});
							}
							element.datepicker( 'show' );
						}
						function date_range(date){
							let from        = jQuery.datepicker.parseDate("dd-M-yy", jQuery("#afwc_from").val());
							let to          = jQuery.datepicker.parseDate("dd-M-yy", jQuery("#afwc_to").val());
							let is_highlight = ( from && ( ( date.getTime() == from.getTime() ) || ( to && date >= from && date <= to ) ) );
							return [true, is_highlight ? "dp-highlight" : ""];
						}
						function dr_on_select(date_text, inst) {
							let from = jQuery.datepicker.parseDate("dd-M-yy", jQuery("#afwc_from").val());
							let to   = jQuery.datepicker.parseDate("dd-M-yy", jQuery("#afwc_to").val());
							if ( ! from && ! to ) {
								jQuery("#afwc_from").val("");
								jQuery("#afwc_to").val("");
								setTimeout(function(){
									load_datepicker( jQuery('#afwc_from') );
								}, 1);
							} else if ( ! from && to ) {
								jQuery("#afwc_from").val(date_text);
								jQuery("#afwc_to").val("");
								setTimeout(function(){
									load_datepicker( jQuery('#afwc_to') );
								}, 1);
							} else if ( from && ! to ) {
								jQuery("#afwc_to").val("");
								setTimeout(function(){
									load_datepicker( jQuery('#afwc_to') );
								}, 1);
							} else if ( from && to ) {
								if ( 'afwc_to' !== inst.id || from >= to ) {
									jQuery("#afwc_from").val(date_text);
									jQuery("#afwc_to").val("");
									setTimeout(function(){
										load_datepicker( jQuery('#afwc_to') );
									}, 1);
								} else {
									jQuery('#afwc_to').trigger('change');
								}
							}
						}
						jQuery('body').on('change', '#afwc_from, #afwc_to, #afwc_search', function(){
							let the_afwc  = jQuery('#afwc_dashboard_wrapper');
							let from      = the_afwc.find('#afwc_from').val();
							let to        = the_afwc.find('#afwc_to').val();
							var tzoffset = (new Date()).getTimezoneOffset() * 60000;
							let format_from = new Date(jQuery.datepicker.parseDate("dd-M-yy", from).getTime() - tzoffset);
							let format_to = new Date(jQuery.datepicker.parseDate("dd-M-yy", to).getTime() - tzoffset);
							let search = the_afwc.find('#afwc_search').val();
							the_afwc.css( 'opacity', 0.5 );
							if ( ( from && to ) || search ) {
								jQuery.ajax({
									url: '<?php echo esc_url( WC_AJAX::get_endpoint( 'afwc_reload_dashboard' ) ); ?>',
									type: 'post',
									dataType: 'html',
									data: {
										security: '<?php echo esc_js( wp_create_nonce( 'afwc-reload-dashboard' ) ); ?>',
										afwc_from: from,
										afwc_to: to,
										afwc_format_from: format_from.toISOString().slice(0,10),
										afwc_format_to: format_to.toISOString().slice(0,10),
										afwc_search: search,
										user_id: '<?php echo esc_attr( $user->ID ); ?>'
									},
									success: function( response ) {
										if ( response ) {
											the_afwc.replaceWith( response );
											the_afwc.css( 'opacity', 1 );
										}
									}
								});
							}
						});
					});
				</script>
				<!-- Affiliate For WooCommerce JavaScript End -->
				<?php
			}
		}

		/**
		 * Function to retrieve more products.
		 */
		public function ajax_reload_dashboard() {
			check_ajax_referer( 'afwc-reload-dashboard', 'security' );

			$user_id = ( ! empty( $_POST['user_id'] ) ) ? absint( $_POST['user_id'] ) : 0;

			$user = get_user_by( 'id', $user_id );

			$this->dashboard_content( $user );

			die();
		}

		/**
		 * Function to retrieve more products.
		 */
		public function ajax_load_more_products() {
			check_ajax_referer( 'afwc-load-more-products', 'security' );

			$args = apply_filters(
				'afwc_ajax_load_more_products',
				array(
					'from'         => ( ! empty( $_POST['from'] ) ) ? wc_clean( wp_unslash( $_POST['from'] ) ) : '', // phpcs:ignore
					'to'           => ( ! empty( $_POST['to'] ) ) ? wc_clean( wp_unslash( $_POST['to'] ) ) : '', // phpcs:ignore
					'search'       => ( ! empty( $_POST['search'] ) ) ? wc_clean( wp_unslash( $_POST['search'] ) ) : '', // phpcs:ignore
					'offset'       => ( ! empty( $_POST['offset'] ) ) ? wc_clean( wp_unslash( $_POST['offset'] ) ) : 0, // phpcs:ignore
					'affiliate_id' => ( ! empty( $_POST['affiliate'] ) ) ? wc_clean( wp_unslash( $_POST['affiliate'] ) ) : 0, // phpcs:ignore
				)
			);

			$products = Affiliate_For_WooCommerce::get_products_data( $args );

			if ( ! empty( $products['rows'] ) ) {
				do_action( 'afwc_before_ajax_load_more_products', $products, $args, $this );
				foreach ( $products['rows'] as $product ) {

					$product_name = ( strlen( $product['product'] ) > 20 ) ? substr( $product['product'], 0, 19 ) . '...' : $product['product'];

					?>
						<tr>
							<td data-title="<?php echo esc_html__( 'Product', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $product['product'] ); ?>"><?php echo esc_html( $product_name ); ?></td>
							<td data-title="<?php echo esc_html__( 'Sales', 'affiliate-for-woocommerce' ); ?>"><?php echo wp_kses_post( wc_price( $product['sales'] ) ); // phpcs:ignore ?></td>
							<td data-title="<?php echo esc_html__( 'Qty', 'affiliate-for-woocommerce' ); ?>"><?php echo esc_html( $product['qty'] ); ?></td>
						</tr>
					<?php
				}
				do_action( 'afwc_after_ajax_load_more_products', $products, $args, $this );
			}
			die();
		}

		/**
		 * Function to retrieve more referrals.
		 */
		public function ajax_load_more_referrals() {
			check_ajax_referer( 'afwc-load-more-referrals', 'security' );

			$date_format = get_option( 'date_format' );

			$args = apply_filters(
				'afwc_ajax_load_more_referrals',
				array(
					'from'         => ( ! empty( $_POST['from'] ) ) ? wc_clean( wp_unslash( $_POST['from'] ) ) : '', // phpcs:ignore
					'to'           => ( ! empty( $_POST['to'] ) ) ? wc_clean( wp_unslash( $_POST['to'] ) ) : '', // phpcs:ignore
					'search'       => ( ! empty( $_POST['search'] ) ) ? wc_clean( wp_unslash( $_POST['search'] ) ) : '', // phpcs:ignore
					'offset'       => ( ! empty( $_POST['offset'] ) ) ? wc_clean( wp_unslash( $_POST['offset'] ) ) : 0, // phpcs:ignore
					'affiliate_id' => ( ! empty( $_POST['affiliate'] ) ) ? wc_clean( wp_unslash( $_POST['affiliate'] ) ) : 0, // phpcs:ignore
				)
			);

			$referrals = $this->get_referrals_data( $args );

			if ( ! empty( $referrals['rows'] ) ) {
				do_action( 'afwc_before_ajax_load_more_referrals', $referrals, $args, $this );
				foreach ( $referrals['rows'] as $referral ) {

					// $referral_status is passed like this so it is available in the translation.
					if ( 'paid' === $referral['status'] ) {
						$referral_status = __( 'Paid', 'affiliate-for-woocommerce' );
						$status_color    = 'green';
					} elseif ( 'unpaid' === $referral['status'] ) {
						$referral_status = __( 'Unpaid', 'affiliate-for-woocommerce' );
						$status_color    = 'orange';
					} elseif ( 'rejected' === $referral['status'] ) {
						$referral_status = __( 'Rejected', 'affiliate-for-woocommerce' );
						$status_color    = 'red';
					}

					$customer_name = ( strlen( $referral['display_name'] ) > 20 ) ? substr( $referral['display_name'], 0, 19 ) . '...' : $referral['display_name'];
					$commission    = ( html_entity_decode( get_woocommerce_currency_symbol( $referral['currency_id'] ) ) ) . $referral['amount'];

					$is_show_customer_column = apply_filters( 'afwc_account_show_customer_column', true, array( 'source' => $this ) );
					?>
						<tr>
							<td data-title="<?php echo esc_html__( 'Date', 'affiliate-for-woocommerce' ); ?>"> <?php echo esc_html( gmdate( $date_format, Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $referral['datetime'] ) ) ) ); ?></td>
							<?php if ( true === $is_show_customer_column ) { ?>
								<td data-title="<?php echo esc_html__( 'Customer', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $referral['display_name'] ); ?>"><?php echo esc_html( $customer_name ); ?></td>
							<?php } ?>
							<td data-title="<?php echo esc_html__( 'Commission', 'affiliate-for-woocommerce' ); ?>"><?php echo wp_kses_post( $commission ); // phpcs:ignore ?></td>
							<td data-title="<?php echo esc_html__( 'Payout status', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $referral_status ); ?>"><div class="circle <?php echo esc_attr( 'fill_' . $status_color ); ?>"></div></td>
						</tr>
					<?php
				}
				do_action( 'afwc_after_ajax_load_more_referrals', $referrals, $args, $this );
			}
			die();
		}


		/**
		 * Function to retrieve more payouts.
		 */
		public function ajax_load_more_payouts() {
			check_ajax_referer( 'afwc-load-more-payouts', 'security' );

			$date_format = get_option( 'date_format' );

			$args = apply_filters(
				'afwc_ajax_load_more_payouts',
				array(
					'from'         => ( ! empty( $_POST['from'] ) ) ? wc_clean( wp_unslash( $_POST['from'] ) ) : '', // phpcs:ignore
					'to'           => ( ! empty( $_POST['to'] ) ) ? wc_clean( wp_unslash( $_POST['to'] ) ) : '', // phpcs:ignore
					'search'       => ( ! empty( $_POST['search'] ) ) ? wc_clean( wp_unslash( $_POST['search'] ) ) : '', // phpcs:ignore
					'start_limit'  => ( ! empty( $_POST['offset'] ) ) ? wc_clean( wp_unslash( $_POST['offset'] ) ) : 0, // phpcs:ignore
					'affiliate_id' => ( ! empty( $_POST['affiliate'] ) ) ? wc_clean( wp_unslash( $_POST['affiliate'] ) ) : 0, // phpcs:ignore
				)
			);

			$payout_history = Affiliate_For_WooCommerce::get_affiliates_payout_history( $args );
			if ( ! empty( $payout_history['payouts'] ) ) {
				do_action( 'afwc_before_ajax_load_more_payouts', $payout_history, $args, $this );

				foreach ( $payout_history['payouts'] as $payout ) {
					?>
					<tr>
						<td data-title="<?php echo esc_html__( 'Date', 'affiliate-for-woocommerce' ); ?>" ><?php echo esc_html( gmdate( $date_format, Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $payout['datetime'] ) ) ) ); ?></td>
						<td data-title="<?php echo esc_html__( 'Amount', 'affiliate-for-woocommerce' ); ?>" ><?php echo wp_kses_post( wc_price( $payout['amount'] ) ); // phpcs:ignore ?></td>
						<td data-title="<?php echo esc_html__( 'Method', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $payout['method'] ); ?>"><?php echo esc_html( ucfirst( str_replace( '-', ' ', $payout['method'] ) ) ); ?></td>
						<td data-title="<?php echo esc_html__( 'Notes', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $payout['payout_notes'] ); ?>"><?php echo esc_html( $payout['payout_notes'] ); ?></td>
					</tr>
					<?php
				}
				do_action( 'afwc_after_ajax_load_more_payouts', $payout_history, $args, $this );
			}
			die();
		}

		/**
		 * Function to display endpoint content
		 */
		public function endpoint_content() {
			if ( ! is_user_logged_in() ) {
				return;
			}
			if ( ! $this->is_afwc_endpoint() ) {
				return;
			}
			$user = wp_get_current_user();
			if ( ! is_object( $user ) || empty( $user->ID ) ) {
				return;
			}

			$is_affiliate = afwc_is_user_affiliate( $user );
			if ( 'yes' === $is_affiliate ) {
				$this->tabs( $user );
				$this->tab_content( $user );
			}
			if ( 'not_registered' === $is_affiliate ) {
				echo do_shortcode( '[afwc_registration_form]' );
			}

		}

		/**
		 * Function to display tabs headers
		 *
		 * @param  WP_User $user The user object.
		 */
		public function tabs( $user = null ) {
			if ( ! is_object( $user ) || empty( $user->ID ) ) {
				return;
			}

			global $wp;
			?>
			<nav class="nav-tab-wrapper">
				<a href="<?php echo esc_url( wc_get_endpoint_url( $this->endpoint ) ); ?>" class="nav-tab <?php echo ( isset( $wp->query_vars[ $this->endpoint ] ) && empty( $wp->query_vars[ $this->endpoint ] ) ) ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php echo esc_html__( 'Reports', 'affiliate-for-woocommerce' ); ?></a>
				<a href="<?php echo esc_url( wc_get_endpoint_url( $this->endpoint, 'resources' ) ); ?>" class="nav-tab <?php echo ( ! empty( $wp->query_vars[ $this->endpoint ] ) && 'resources' === $wp->query_vars[ $this->endpoint ] ) ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php echo esc_html__( 'Profile', 'affiliate-for-woocommerce' ); ?></a>
				<a href="<?php echo esc_url( wc_get_endpoint_url( $this->endpoint, 'campaigns' ) ); ?>" class="nav-tab <?php echo ( ! empty( $wp->query_vars[ $this->endpoint ] ) && 'campaigns' === $wp->query_vars[ $this->endpoint ] ) ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php echo esc_html__( 'Campaigns', 'affiliate-for-woocommerce' ); ?></a>
			</nav>
			<?php
		}

		/**
		 * Function to display tabs content on my account.
		 *
		 * @param  WP_User $user The user object.
		 */
		public function tab_content( $user = null ) {
			if ( ! is_object( $user ) || empty( $user->ID ) ) {
				return;
			}
			global $wp;

			if ( isset( $wp->query_vars[ $this->endpoint ] ) && empty( $wp->query_vars[ $this->endpoint ] ) ) {
				$this->dashboard_content( $user );
			} elseif ( ! empty( $wp->query_vars[ $this->endpoint ] ) && 'resources' === $wp->query_vars[ $this->endpoint ] ) {
				$this->resources_content( $user );
			} elseif ( ! empty( $wp->query_vars[ $this->endpoint ] ) && 'campaigns' === $wp->query_vars[ $this->endpoint ] ) {
				$this->campaigns_content( $user );
			}

		}

		/**
		 * Function to display dashboard content on my account.
		 *
		 * @param  WP_User $user The user object.
		 */
		public function dashboard_content( $user = null ) {
			global $wpdb;

			if ( ! is_object( $user ) || empty( $user->ID ) ) {
				return;
			}

			if ( defined( 'WC_DOING_AJAX' ) && true === WC_DOING_AJAX ) {
				check_ajax_referer( 'afwc-reload-dashboard', 'security' );
			}

			$date_format = get_option( 'date_format' );

			$affiliate_id = get_affiliate_id_based_on_user_id( $user->ID );

			$from         = ( ! empty( $_POST['afwc_from'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_from'] ) ) : ''; // phpcs:ignore
			$format_from  = ( ! empty( $_POST['afwc_format_from'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_format_from'] ) ) : ''; // phpcs:ignore
			$to           = ( ! empty( $_POST['afwc_to'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_to'] ) ) : ''; // phpcs:ignore
			$format_to           = ( ! empty( $_POST['afwc_format_to'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_format_to'] ) ) : ''; // phpcs:ignore
			$search       = ( ! empty( $_POST['afwc_search'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_search'] ) ) : ''; // phpcs:ignore

			$args = array(
				'affiliate_id' => $affiliate_id,
				'from'         => ( ! empty( $from ) ) ? gmdate( 'Y-m-d', Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $format_from . ' 00:00:00' ) ) ) . ' 00:00:00' : '',
				'to'           => ( ! empty( $to ) ) ? gmdate( 'Y-m-d', Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $format_to . ' 23:59:59' ) ) ) . ' 23:59:59' : '',
				'search'       => $search,
			);

			$visitors        = $this->get_visitors_data( $args );
			$customers_count = $this->get_customers_data( $args );
			$payouts         = $this->get_payouts_data( $args );
			$kpis            = $this->get_kpis_data( $args );
			$refunds         = $this->get_refunds_data( $args );
			$referrals       = $this->get_referrals_data( $args );
			$products        = Affiliate_For_WooCommerce::get_products_data( $args );
			$payout_history  = Affiliate_For_WooCommerce::get_affiliates_payout_history( $args );

			$products_total = ( ! empty( $products['total_count'] ) ) ? $products['total_count'] : 0;
			$products_rows  = ( ! empty( $products['rows'] ) ) ? $products['rows'] : array();

			$gross_commission = $kpis['paid_commission'] + $kpis['unpaid_commission'] + $kpis['rejected_commission'];
			$net_commission   = $kpis['paid_commission'] + $kpis['unpaid_commission'];

			$paid_commission_percentage   = ( ( ! empty( $kpis['paid_commission'] ) && ! empty( $net_commission ) ) ? ( $kpis['paid_commission'] / $net_commission ) * 100 : 0 );
			$unpaid_commission_percentage = ( ( ! empty( $kpis['unpaid_commission'] ) && ! empty( $net_commission ) ) ? ( $kpis['unpaid_commission'] / $net_commission ) * 100 : 0 );

			$paid_commission_percentage_style   = ( empty( $paid_commission_percentage ) ) ? 'display:none;' : '';
			$unpaid_commission_percentage_style = ( empty( $unpaid_commission_percentage ) ) ? 'display:none;' : '';

			?>
			<div id="afwc_dashboard_wrapper">
				<div id="afwc_top_row_container">
					<div id="afwc_date_range_container">
						<input type="text" readonly="readonly" id="afwc_from" name="afwc_from" value="<?php echo ( ! empty( $from ) ) ? esc_attr( $from ) : ''; ?>" placeholder="<?php echo esc_attr__( 'From', 'affiliate-for-woocommerce' ); ?>">-<input type="text" readonly="readonly" id="afwc_to" name="afwc_to" value="<?php echo ( ! empty( $to ) ) ? esc_attr( $to ) : ''; ?>" placeholder="<?php echo esc_attr__( 'To', 'affiliate-for-woocommerce' ); ?>">
					</div>
				</div>
				<?php if ( ! empty( $paid_commission_percentage ) || ! empty( $unpaid_commission_percentage ) ) { ?>
					<div id="afwc_commission">
						<div id ="afwc_commission_lbl" class="afwc_kpis_text"><?php echo esc_html__( 'Total Commissions', 'affiliate-for-woocommerce' ); ?>:</div>
						<div id ="afwc_commission_container">
							<div id ="afwc_commission_bar">
								<div id ="afwc_paid_commission" class="fill_green" style="<?php echo esc_html( $paid_commission_percentage_style ) . 'width:' . esc_html( $paid_commission_percentage ) . '%'; ?>"></div>
								<div id ="afwc_unpaid_commission" class="fill_orange" style="<?php echo esc_html( $unpaid_commission_percentage_style ) . 'width:' . esc_html( $unpaid_commission_percentage ) . '%'; ?>"></div>
							</div>
							<div id ="afwc_commission_stats">
								<?php if ( ! empty( $paid_commission_percentage ) ) { ?>
									<div id="afwc_commission_stats_paid" class="afwc_kpis_text"><?php echo esc_html__( 'Paid', 'affiliate-for-woocommerce' ) . ': ' . wp_kses_post( wc_price( $kpis['paid_commission'] ) ); //phpcs:ignore ?></div>
								<?php } if ( ! empty( $unpaid_commission_percentage ) ) { ?>
									<div id="afwc_commission_stats_unpaid" class="afwc_kpis_text"><?php echo esc_html__( 'Unpaid', 'affiliate-for-woocommerce' ) . ': ' . wp_kses_post( wc_price( $kpis['unpaid_commission'] ) ); //phpcs:ignore ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div id ="afwc_kpis_container">
					<div class="afwc_kpis_inner_container">
						<div id="afwc_kpi_gross_commission" class="afwc_kpi first">
							<div class="container_parent_left flex_center">
								<div class="afwc_kpis_icon_container">
									<i class="fas fa-dollar-sign afwc_kpis_icon"></i>
								</div>
							</div>
							<div id="afwc_gross_commission" class="afwc_kpis_data flex_center">
								<div class="container_parent_right">
									<span class="afwc_kpis_price">
										<?php echo wp_kses_post( wc_price( $gross_commission ) ); //phpcs:ignore ?> • <span class="afwc_kpis_number"><?php echo esc_html( $kpis['number_of_orders'] ); ?></span>
									</span>
									<p class="afwc_kpis_text"><?php echo esc_html__( 'Gross Commission', 'affiliate-for-woocommerce' ); ?></p>
								</div>
							</div>
						</div>
						<div id="afwc_kpi_refunds" class="afwc_kpi second">
							<div class="container_parent_left flex_center">
								<div class="afwc_kpis_icon_container">
									<i class="fas fa-thumbs-down afwc_kpis_icon"></i>
								</div>
							</div>
							<div id="afwc_refunds" class="afwc_kpis_data flex_center">
								<div class="container_parent_right">
									<span class="afwc_kpis_price">
										<?php echo wp_kses_post( wc_price( $refunds['refund_amount'] ) ); //phpcs:ignore ?> • <span class="afwc_kpis_number"><?php echo esc_html( $kpis['rejected_count'] ); ?></span>
									</span>
									<p class="afwc_kpis_text"><?php echo esc_html__( 'Refunds', 'affiliate-for-woocommerce' ); ?></p>
								</div>
							</div>
						</div>
						<div id="afwc_kpi_net_commission" class="afwc_kpi third">
							<div class="container_parent_left flex_center">
								<div class="afwc_kpis_icon_container">
									<i class="fas fa-hand-holding-usd afwc_kpis_icon"></i>
								</div>
							</div>
							<div id="afwc_net_commission" class="afwc_kpis_data flex_center">
								<div class="container_parent_right">
									<span class="afwc_kpis_price">
										<?php echo wp_kses_post( wc_price( $net_commission ) ); //phpcs:ignore ?> • <span class="afwc_kpis_number"><?php echo esc_html( $kpis['paid_count'] + $kpis['unpaid_count'] ); ?></span>
									</span>
									<p class="afwc_kpis_text"><?php echo esc_html__( 'Net Commission', 'affiliate-for-woocommerce' ); ?></p>
								</div>
							</div>
						</div>
						<div id="afwc_kpi_sales" class="afwc_kpi fourth">
							<div class="container_parent_left flex_center">
								<div class="afwc_kpis_icon_container">
									<i class="fas fa-coins afwc_kpis_icon"></i>
								</div>
							</div>
							<div id="afwc_sales" class="afwc_kpis_data flex_center">
								<div class="container_parent_right">
									<span class="afwc_kpis_price">
										<?php echo wp_kses_post( wc_price( $kpis['sales'] ) ); //phpcs:ignore ?>
									</span>
									<p class="afwc_kpis_text"><?php echo esc_html__( 'Sales', 'affiliate-for-woocommerce' ); ?></p>
								</div>
							</div>
						</div>
						<div id="afwc_kpi_clicks" class="afwc_kpi fifth">
							<div class="container_parent_left flex_center">
								<div class="afwc_kpis_icon_container">
									<i class="fas fa-hand-point-up afwc_kpis_icon"></i>
								</div>
							</div>
							<div id="afwc_clicks" class="afwc_kpis_data flex_center">
								<div class="container_parent_right">
									<span class="afwc_kpis_price">
										<?php echo esc_html( $visitors['visitors'] ); ?>
									</span>
									<p class="afwc_kpis_text"><?php echo esc_html__( 'Visitors', 'affiliate-for-woocommerce' ); ?></p>
								</div>
							</div>
						</div>
						<div id="afwc_kpi_conversion" class="afwc_kpi sixth afwc_kpi_last">
							<div class="container_parent_left flex_center">
								<div class="afwc_kpis_icon_container">
									<i class="fas fa-handshake afwc_kpis_icon"> </i>
								</div>
							</div>
							<div id="afwc_conversion" class="afwc_kpis_data flex_center">
								<div class="container_parent_right">
									<span class="afwc_kpis_price">
										<?php echo esc_html( number_format( ( ( ! empty( $visitors['visitors'] ) ) ? ( $customers_count['customers'] * 100 / $visitors['visitors'] ) : 0 ), 2 ) ) . '%'; ?> • <span class="afwc_kpis_number"><?php echo esc_html( $kpis['number_of_orders'] ); ?></span>
									</span>
									<p class="afwc_kpis_text"><?php echo esc_html__( 'Conversion', 'affiliate-for-woocommerce' ); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="afwc-table-header"><?php echo esc_html__( 'Products', 'affiliate-for-woocommerce' ); ?></div>
				<table class="afwc_products">
					<thead>
						<tr>
							<th class="product-name"><?php echo esc_html__( 'Product', 'affiliate-for-woocommerce' ); ?></th>
							<th class="sales"><?php echo esc_html__( 'Sales', 'affiliate-for-woocommerce' ); ?></th>
							<th class="qty"><?php echo esc_html__( 'Qty', 'affiliate-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( ! empty( $products_rows ) ) { ?>
							<?php
							foreach ( $products_rows as $product ) {
									$product_name = ( strlen( $product['product'] ) > 30 ) ? substr( $product['product'], 0, 30 ) . '...' : $product['product'];
								?>
							<tr>
								<td class="product-name" data-title="<?php echo esc_html__( 'Product', 'affiliate-for-woocommerce' ); ?>"  title = "<?php echo esc_html( $product['product'] ); ?>"><?php echo esc_html( $product_name ); ?></td>
								<td class="sales" data-title="<?php echo esc_html__( 'Sales', 'affiliate-for-woocommerce' ); ?>"><?php echo wp_kses_post( wc_price( $product['sales'] ) ); // phpcs:ignore ?></td>
								<td class="qty" data-title="<?php echo esc_html__( 'Qty', 'affiliate-for-woocommerce' ); ?>"><?php echo esc_html( $product['qty'] ); ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td colspan="3"><?php echo esc_html__( 'No products to show', 'affiliate-for-woocommerce' ); ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<?php if ( $products_total > count( $products_rows ) ) { ?>
						<tfoot>
							<tr>
								<td colspan="3">
									<a id="afwc_load_more_products" data-max_record="<?php echo esc_attr( $products_total ); ?>"><?php echo esc_html__( 'Load more', 'affiliate-for-woocommerce' ); ?></a>
								</td>
							</tr>
						</tfoot>
					<?php } ?>
				</table>
				<?php
					$is_show_customer_column = apply_filters( 'afwc_account_show_customer_column', true, array( 'source' => $this ) );
					$payout_colspan          = ( true === $is_show_customer_column ) ? 4 : 3;
				?>
				<div class="afwc-table-header"><?php echo esc_html__( 'Referrals', 'affiliate-for-woocommerce' ); ?></div>
				<table class="afwc_referrals">
					<thead>
						<tr>
							<th><?php echo esc_html__( 'Date', 'affiliate-for-woocommerce' ); ?></th>
							<?php if ( true === $is_show_customer_column ) { ?>
							<th><?php echo esc_html__( 'Customer', 'affiliate-for-woocommerce' ); ?></th>
							<?php } ?>
							<th><?php echo esc_html__( 'Commission', 'affiliate-for-woocommerce' ); ?></th>
							<th><?php echo esc_html__( 'Payout status', 'affiliate-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( ! empty( $referrals['rows'] ) ) { ?>
							<?php
							foreach ( $referrals['rows'] as $referral ) {

								// $referral_status is passed like this so it is available in the translation.
								if ( 'paid' === $referral['status'] ) {
									$referral_status = __( 'Paid', 'affiliate-for-woocommerce' );
									$status_color    = 'green';
								} elseif ( 'unpaid' === $referral['status'] ) {
									$referral_status = __( 'Unpaid', 'affiliate-for-woocommerce' );
									$status_color    = 'orange';
								} elseif ( 'rejected' === $referral['status'] ) {
									$referral_status = __( 'Rejected', 'affiliate-for-woocommerce' );
									$status_color    = 'red';
								}

								$customer_name = ( strlen( $referral['display_name'] ) > 20 ) ? substr( $referral['display_name'], 0, 19 ) . '...' : $referral['display_name'];
								?>
							<tr>
								<td data-title="<?php echo esc_html__( 'Date', 'affiliate-for-woocommerce' ); ?>"><?php echo esc_html( gmdate( $date_format, Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $referral['datetime'] ) ) ) ); ?></td>
								<?php if ( true === $is_show_customer_column ) { ?>
								<td data-title="<?php echo esc_html__( 'Customer', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $referral['display_name'] ); ?>"><?php echo esc_html( $customer_name ); ?></td>
							<?php } ?>
								<td data-title="<?php echo esc_html__( 'Commission', 'affiliate-for-woocommerce' ); ?>"><?php echo wp_kses_post( wc_price( $referral['amount'] ) ); // phpcs:ignore ?></td>
								<td data-title="<?php echo esc_html__( 'Payout status', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $referral_status ); ?>"><div class="circle <?php echo esc_attr( 'fill_' . $status_color ); ?>"></div></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td colspan="<?php echo esc_attr( $payout_colspan ); ?>"><?php echo esc_html__( 'No referrals to show', 'affiliate-for-woocommerce' ); ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<?php if ( $referrals['total_count'] > count( $referrals['rows'] ) ) { ?>
						<tfoot>
							<tr>
								<td colspan="<?php echo esc_attr( $payout_colspan ); ?>">
									<a id="afwc_load_more_referrals" data-max_record="<?php echo esc_attr( $referrals['total_count'] ); ?>"><?php echo esc_html__( 'Load more', 'affiliate-for-woocommerce' ); ?></button>
								</td>
							</tr>
						</tfoot>
					<?php } ?>
				</table>
				<div class="afwc-table-header"><?php echo esc_html__( 'Payout History', 'affiliate-for-woocommerce' ); ?></div>
				<table class="afwc_payout_history">
					<thead>
						<tr>
							<th><?php echo esc_html__( 'Date', 'affiliate-for-woocommerce' ); ?></th>
							<th><?php echo esc_html__( 'Amount', 'affiliate-for-woocommerce' ); ?></th>
							<th><?php echo esc_html__( 'Method', 'affiliate-for-woocommerce' ); ?></th>
							<th><?php echo esc_html__( 'Notes', 'affiliate-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( ! empty( $payout_history['payouts'] ) ) { ?>
							<?php
							foreach ( $payout_history['payouts'] as $payout ) {
								?>
							<tr>
								<td data-title="<?php echo esc_html__( 'Date', 'affiliate-for-woocommerce' ); ?>" ><?php echo esc_html( gmdate( $date_format, Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $payout['datetime'] ) ) ) ); ?></td>
								<td data-title="<?php echo esc_html__( 'Amount', 'affiliate-for-woocommerce' ); ?>" ><?php echo wp_kses_post( wc_price( $payout['amount'] ) ); // phpcs:ignore ?></td>
								<td data-title="<?php echo esc_html__( 'Method', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $payout['method'] ); ?>"><?php echo esc_html( ucfirst( str_replace( '-', ' ', $payout['method'] ) ) ); ?></td>
								<td data-title="<?php echo esc_html__( 'Notes', 'affiliate-for-woocommerce' ); ?>" title = "<?php echo esc_html( $payout['payout_notes'] ); ?>"><?php echo esc_html( $payout['payout_notes'] ); ?></td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td colspan="4"><?php echo esc_html__( 'No payouts to show', 'affiliate-for-woocommerce' ); ?></td>
						</tr>
					<?php } ?>
					</tbody>
					<?php if ( $payout_history['total_count'] > count( $payout_history['payouts'] ) ) { ?>
						<tfoot>
							<tr>
								<td colspan="4">
									<a id="afwc_load_more_payouts" data-max_record="<?php echo esc_attr( $payout_history['total_count'] ); ?>"><?php echo esc_html__( 'Load more', 'affiliate-for-woocommerce' ); ?></button>
								</td>
							</tr>
						</tfoot>
					<?php } ?>
				</table>
			</div>
			<?php
		}

		/**
		 * Function to get visitors data
		 *
		 * @param array $args arguments.
		 * @return array visitors data
		 */
		public function get_visitors_data( $args = array() ) {
			global $wpdb;

			$from         = ( ! empty( $args['from'] ) ) ? $args['from'] : '';
			$to           = ( ! empty( $args['to'] ) ) ? $args['to'] : '';
			$affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? $args['affiliate_id'] : 0;

			if ( ! empty( $from ) && ! empty( $to ) ) {
				$visitors_result = $wpdb->get_var( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																FROM {$wpdb->prefix}afwc_hits
																WHERE affiliate_id = %d
																	AND (datetime BETWEEN %s AND %s)",
													$affiliate_id,
													$from,
													$to
												)
				);
			} else {
				$visitors_result = $wpdb->get_var( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																FROM {$wpdb->prefix}afwc_hits
																WHERE affiliate_id = %d",
													$affiliate_id
												)
				);
			}

			return apply_filters( 'afwc_my_account_clicks_result', array( 'visitors' => $visitors_result ), $args );
		}

		/**
		 * Function to get customers data
		 *
		 * @param array $args arguments.
		 * @return array customers data
		 */
		public function get_customers_data( $args = array() ) {
			global $wpdb;

			$from         = ( ! empty( $args['from'] ) ) ? $args['from'] : '';
			$to           = ( ! empty( $args['to'] ) ) ? $args['to'] : '';
			$affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? $args['affiliate_id'] : 0;

			if ( ! empty( $from ) && ! empty( $to ) ) {
				$customers_result = $wpdb->get_var( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																FROM {$wpdb->prefix}afwc_referrals
																WHERE affiliate_id = %d
																	AND (datetime BETWEEN %s AND %s)",
													$affiliate_id,
													$from,
													$to
												)
				);
			} else {
				$customers_result = $wpdb->get_var( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																FROM {$wpdb->prefix}afwc_referrals
																WHERE affiliate_id = %d",
													$affiliate_id
												)
				);
			}

			return apply_filters( 'afwc_my_account_customers_result', array( 'customers' => $customers_result ), $args );
		}

		/**
		 * Function to get payouts data
		 *
		 * @param array $args arguments.
		 * @return array $payouts_result payouts data
		 */
		public function get_payouts_data( $args = array() ) {
			global $wpdb;

			$from         = ( ! empty( $args['from'] ) ) ? $args['from'] : '';
			$to           = ( ! empty( $args['to'] ) ) ? $args['to'] : '';
			$affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? $args['affiliate_id'] : 0;

			if ( ! empty( $from ) && ! empty( $to ) ) {
				$payouts_result = $wpdb->get_var( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT SUM(amount)
															FROM {$wpdb->prefix}afwc_payouts
															WHERE affiliate_id = %d
																AND (datetime BETWEEN %s AND %s)",
													$affiliate_id,
													$from,
													$to
												)
				);
			} else {
				$payouts_result = $wpdb->get_var( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT SUM(amount)
															FROM {$wpdb->prefix}afwc_payouts
															WHERE affiliate_id = %d",
													$affiliate_id
												)
				);
			}

			return apply_filters( 'afwc_my_account_payouts_result', array( 'payouts' => $payouts_result ), $args );

		}

		/**
		 * Function to get kpis data
		 *
		 * @param array $args arguments.
		 * @return array $kpis kpis data
		 */
		public function get_kpis_data( $args = array() ) {
			global $wpdb;

			$from         = ( ! empty( $args['from'] ) ) ? $args['from'] : '';
			$to           = ( ! empty( $args['to'] ) ) ? $args['to'] : '';
			$affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? $args['affiliate_id'] : 0;

			$prefixed_statuses   = afwc_get_prefixed_order_statuses();
			$option_order_status = 'afwc_order_statuses_' . uniqid();
			update_option( $option_order_status, implode( ',', $prefixed_statuses ), 'no' );

			if ( ! empty( $from ) && ! empty( $to ) ) {
				// Need to consider all order_statuses to get correct rejected_commission and hence not passing order_statuses.
				$kpis_result = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(count(pm.post_id), 0) AS number_of_orders,
																	IFNULL(SUM(CASE WHEN afwcr.status = %s THEN afwcr.amount END), 0) AS paid_commission,
																	IFNULL(SUM(CASE WHEN afwcr.status = %s THEN afwcr.amount END), 0) AS unpaid_commission,
																	IFNULL(SUM(CASE WHEN afwcr.status = %s THEN afwcr.amount END), 0) AS rejected_commission,
																	IFNULL(COUNT(CASE WHEN afwcr.status = %s THEN 1 END), 0) AS paid_count,
																	IFNULL(COUNT(CASE WHEN afwcr.status = %s THEN 1 END), 0) AS unpaid_count,
																	IFNULL(COUNT(CASE WHEN afwcr.status = %s THEN 1 END), 0) AS rejected_count
																FROM {$wpdb->prefix}afwc_referrals AS afwcr
																	JOIN {$wpdb->postmeta} AS pm
																		ON (afwcr.post_id = pm.post_id
																				AND pm.meta_key = %s
																				AND afwcr.affiliate_id = %d)
																WHERE (afwcr.datetime BETWEEN %s AND %s)",
														'paid',
														'unpaid',
														'rejected',
														'paid',
														'unpaid',
														'rejected',
														'_order_total',
														$affiliate_id,
														$from,
														$to
													),
					'ARRAY_A'
				);

				$order_total =  $wpdb->get_results( // phpcs:ignore
									$wpdb->prepare( // phpcs:ignore
										"SELECT IFNULL(SUM(pm.meta_value), 0) AS order_total
												FROM {$wpdb->prefix}afwc_referrals AS afwcr
												JOIN {$wpdb->postmeta} AS pm
												ON (afwcr.post_id = pm.post_id
													AND pm.meta_key = %s
													AND afwcr.affiliate_id = %d)
												JOIN {$wpdb->posts} AS posts
													ON (posts.ID = afwcr.post_id
													AND posts.post_type = %s 
												   	AND FIND_IN_SET ( post_status, ( SELECT option_value
																						FROM {$wpdb->prefix}options
																						WHERE option_name = %s ) ) )
													WHERE (afwcr.datetime BETWEEN %s AND %s)",
										'_order_total',
										$affiliate_id,
										'shop_order',
										$option_order_status,
										$from,
										$to
									),
					'ARRAY_A'
				);
			} else {
				$kpis_result = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(count(pm.post_id), 0) AS number_of_orders,
																			IFNULL(SUM(CASE WHEN afwcr.status = %s THEN afwcr.amount END), 0) AS paid_commission,
																			IFNULL(SUM(CASE WHEN afwcr.status = %s THEN afwcr.amount END), 0) AS unpaid_commission,
																			IFNULL(SUM(CASE WHEN afwcr.status = %s THEN afwcr.amount END), 0) AS rejected_commission,
																			IFNULL(COUNT(CASE WHEN afwcr.status = %s THEN 1 END), 0) AS paid_count,
																			IFNULL(COUNT(CASE WHEN afwcr.status = %s THEN 1 END), 0) AS unpaid_count,
																			IFNULL(COUNT(CASE WHEN afwcr.status = %s THEN 1 END), 0) AS rejected_count
																	FROM {$wpdb->prefix}afwc_referrals AS afwcr
																		JOIN {$wpdb->postmeta} AS pm
																			ON (afwcr.post_id = pm.post_id
																					AND pm.meta_key = %s
																					AND afwcr.affiliate_id = %d)",
														'paid',
														'unpaid',
														'rejected',
														'paid',
														'unpaid',
														'rejected',
														'_order_total',
														$affiliate_id
													),
					'ARRAY_A'
				);

				$order_total =  $wpdb->get_results( // phpcs:ignore
									$wpdb->prepare( // phpcs:ignore
										"SELECT IFNULL(SUM(pm.meta_value), 0) AS order_total
												FROM {$wpdb->prefix}afwc_referrals AS afwcr
												JOIN {$wpdb->postmeta} AS pm
												ON (afwcr.post_id = pm.post_id
													AND pm.meta_key = %s
													AND afwcr.affiliate_id = %d)
												JOIN {$wpdb->posts} AS posts
													ON (posts.ID = afwcr.post_id
													AND posts.post_type = %s 
												   	AND FIND_IN_SET ( post_status, ( SELECT option_value
																						FROM {$wpdb->prefix}options
																						WHERE option_name = %s ) ) )",
										'_order_total',
										$affiliate_id,
										'shop_order',
										$option_order_status
									),
					'ARRAY_A'
				);
			}
			delete_option( $option_order_status );

			$kpis_result[0]['order_total'] = ( isset( $order_total[0]['order_total'] ) ) ? $order_total[0]['order_total'] : 0;

			$args['kpis_result'] = $kpis_result;

			$kpis = array(
				'sales'               => ( isset( $kpis_result[0]['order_total'] ) ) ? $kpis_result[0]['order_total'] : 0,
				'number_of_orders'    => ( isset( $kpis_result[0]['number_of_orders'] ) ) ? $kpis_result[0]['number_of_orders'] : 0,
				'paid_commission'     => ( isset( $kpis_result[0]['paid_commission'] ) ) ? $kpis_result[0]['paid_commission'] : 0,
				'unpaid_commission'   => ( isset( $kpis_result[0]['unpaid_commission'] ) ) ? $kpis_result[0]['unpaid_commission'] : 0,
				'rejected_commission' => ( isset( $kpis_result[0]['rejected_commission'] ) ) ? $kpis_result[0]['rejected_commission'] : 0,
				'paid_count'          => ( isset( $kpis_result[0]['paid_count'] ) ) ? $kpis_result[0]['paid_count'] : 0,
				'unpaid_count'        => ( isset( $kpis_result[0]['unpaid_count'] ) ) ? $kpis_result[0]['unpaid_count'] : 0,
				'rejected_count'      => ( isset( $kpis_result[0]['rejected_count'] ) ) ? $kpis_result[0]['rejected_count'] : 0,
			);

			return apply_filters( 'afwc_my_account_kpis_result', $kpis, $args );

		}

		/**
		 * Function to get refunds data
		 *
		 * @param array $args arguments.
		 * @return array $refunds refunds.
		 */
		public function get_refunds_data( $args = array() ) {
			global $wpdb;

			$from         = ( ! empty( $args['from'] ) ) ? $args['from'] : '';
			$to           = ( ! empty( $args['to'] ) ) ? $args['to'] : '';
			$affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? $args['affiliate_id'] : 0;

			if ( ! empty( $from ) && ! empty( $to ) ) {

				$refunds_result = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(SUM(pm.meta_value), 0) AS refund_amount,
																			IFNULL(COUNT(DISTINCT p.post_parent), 0) AS refund_order_count
																	FROM {$wpdb->posts} AS p
																		JOIN {$wpdb->postmeta} AS pm
																			ON (pm.post_id = p.ID
																					AND pm.meta_key = %s
																					AND p.post_type = %s)
																		JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																			ON (afwcr.post_id = p.post_parent)
																	WHERE afwcr.affiliate_id = %d
																		AND (afwcr.datetime BETWEEN %s AND %s) ",
														'_refund_amount',
														'shop_order_refund',
														$affiliate_id,
														$from,
														$to
													),
					'ARRAY_A'
				);
			} else {
				$refunds_result = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(SUM(pm.meta_value), 0) AS refund_amount,
																			IFNULL(COUNT(DISTINCT p.post_parent), 0) AS refund_order_count
																	FROM {$wpdb->posts} AS p
																		JOIN {$wpdb->postmeta} AS pm
																			ON (pm.post_id = p.ID
																					AND pm.meta_key = %s
																					AND p.post_type = %s)
																		JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																			ON (afwcr.post_id = p.post_parent)
																	WHERE afwcr.affiliate_id = %d",
														'_refund_amount',
														'shop_order_refund',
														$affiliate_id
													),
					'ARRAY_A'
				);
			}
			$refunds = array(
				'refund_amount'      => ( isset( $refunds_result[0]['refund_amount'] ) ) ? $refunds_result[0]['refund_amount'] : 0,
				'refund_order_count' => ( isset( $refunds_result[0]['refund_order_count'] ) ) ? $refunds_result[0]['refund_order_count'] : 0,
			);

			return apply_filters( 'afwc_my_account_refunds_result', $refunds, $args );

		}

		/**
		 * Function to get referrals data
		 *
		 * @param array $args arguments.
		 * @return array $referrals referrals data
		 */
		public function get_referrals_data( $args = array() ) {
			global $wpdb;

			$from         = ( ! empty( $args['from'] ) ) ? $args['from'] : '';
			$to           = ( ! empty( $args['to'] ) ) ? $args['to'] : '';
			$affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? $args['affiliate_id'] : 0;
			$limit        = apply_filters( 'afwc_my_account_referrals_per_page', get_option( 'afwc_my_account_referrals_per_page', 5 ) );
			$offset       = ( ! empty( $args['offset'] ) ) ? $args['offset'] : 0;

			$args['limit']  = $limit;
			$args['offset'] = $offset;

			if ( ! empty( $from ) && ! empty( $to ) ) {

				$referrals_result = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT afwcr.datetime,
																			   IFNULL( u.display_name, %s ) AS display_name,
																			   afwcr.amount,
																			   afwcr.currency_id,
																			   afwcr.status
																		FROM {$wpdb->prefix}afwc_referrals AS afwcr
																				LEFT JOIN {$wpdb->users} AS u
																					ON (afwcr.user_id = u.ID)
																		WHERE afwcr.affiliate_id = %d
																			AND (afwcr.datetime BETWEEN %s AND %s)
																		ORDER BY afwcr.datetime DESC
																		LIMIT %d OFFSET %d",
															esc_sql( __( 'Guest', 'affiliate-for-woocommerce' ) ),
															$affiliate_id,
															$from,
															$to,
															$limit,
															$offset
														),
					'ARRAY_A'
				);

				$referrals_total_count = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT COUNT(*)
																		FROM {$wpdb->prefix}afwc_referrals AS afwcr
																				LEFT JOIN {$wpdb->users} AS u
																					ON (afwcr.user_id = u.ID)
																		WHERE afwcr.affiliate_id = %d
																			AND (afwcr.datetime BETWEEN %s AND %s)",
															$affiliate_id,
															$from,
															$to
														)
				);
			} else {
				$referrals_result = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT afwcr.datetime,
																			   IFNULL( u.display_name, %s ) AS display_name,
																			   afwcr.amount,
																			   afwcr.currency_id,
																			   afwcr.status
																		FROM {$wpdb->prefix}afwc_referrals AS afwcr
																				LEFT JOIN {$wpdb->users} AS u
																					ON (afwcr.user_id = u.ID)
																		WHERE afwcr.affiliate_id = %d
																		ORDER BY afwcr.datetime DESC
																		LIMIT %d OFFSET %d",
															esc_sql( __( 'Guest', 'affiliate-for-woocommerce' ) ),
															$affiliate_id,
															$limit,
															$offset
														),
					'ARRAY_A'
				);

				$referrals_total_count = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT COUNT(*)
																		FROM {$wpdb->prefix}afwc_referrals AS afwcr
																				LEFT JOIN {$wpdb->users} AS u
																					ON (afwcr.user_id = u.ID)
																		WHERE afwcr.affiliate_id = %d",
															$affiliate_id
														)
				);
			}

			$referrals = array(
				'rows'        => $referrals_result,
				'total_count' => $referrals_total_count,
			);

			return apply_filters( 'afwc_my_account_referrals_result', $referrals, $args );

		}

		/**
		 * Function to show content resources
		 *
		 * @param WP_User $user The user object.
		 */
		public function resources_content( $user = null ) {

			if ( ! is_object( $user ) || empty( $user->ID ) ) {
				return;
			}

			if ( ! wp_script_is( 'jquery' ) ) {
				wp_enqueue_script( 'jquery' );
			}

			if ( ! class_exists( 'WC_AJAX' ) ) {
				include_once WP_PLUGIN_DIR . '/woocommerce/includes/class-wc-ajax.php';
			}

			$pname           = get_option( 'afwc_pname', 'ref' );
			$pname           = ( ! empty( $pname ) ) ? $pname : 'ref';
			$date_format     = get_option( 'date_format' );
			$affiliate_id    = get_affiliate_id_based_on_user_id( $user->ID );
			$afwc_ref_url_id = get_user_meta( $user->ID, 'afwc_ref_url_id', true );
			$affiliate_id    = ( ! empty( $afwc_ref_url_id ) ) ? $afwc_ref_url_id : $affiliate_id;
			$affiliate_link  = add_query_arg( $pname, $affiliate_id, trailingslashit( home_url() ) );

			$use_referral_coupons = get_option( 'afwc_use_referral_coupons', 'no' );
			$afwc_coupon          = AFWC_Coupon::get_instance();
			$referral_coupon_code = $afwc_coupon->get_referral_coupon( array( 'user_id' => $user->ID ) );

			$paypal_api_settings = AFWC_Paypal::get_instance()->get_api_setting_status();
			if ( 'yes' === $paypal_api_settings['value'] ) {
				$afwc_paypal_email = get_user_meta( $user->ID, 'afwc_paypal_email', true );
			}

			$afwc_admin_contact_email               = get_option( 'afwc_contact_admin_email_address', '' );
			$afwc_allow_custom_affiliate_identifier = get_option( 'afwc_allow_custom_affiliate_identifier', 'yes' );

			?>
			<style type="text/css" media="screen">
				.afwc_save_account_status {
					display: inline-block;
					visibility: visible;
					vertical-align: middle;
					width: 2em;
					height: 2em;
					background-size: 2em;
				}
				.afwc_status_spinner {
					background: url(<?php echo esc_url( admin_url( 'images/spinner.gif' ) ); ?>) no-repeat center;
				}
				.afwc_status_yes {
					background: url(<?php echo esc_url( admin_url( 'images/yes.png' ) ); ?>) no-repeat center;
				}
				.afwc_status_no {
					background: url(<?php echo esc_url( admin_url( 'images/no.png' ) ); ?>) no-repeat center;
				}
				#afwc_id_msg.afwc_sucess {
					color: green;
				}
				#afwc_id_msg.afwc_error {
					color: #e2401c;
				}
				#afwc_id_save_wrap input {
					width: 30% !important;
					margin-right: 0.5em;
				}
			</style>
			<script type="text/javascript">
				jQuery(function(){
					jQuery('#afwc_resources_wrapper').on('change, keyup', '#afwc_affiliate_link', function(){
						let start = '<?php echo esc_url( trailingslashit( home_url() ) ); ?>';
						let path = jQuery(this).val();
						let affiliate_id = jQuery('#afwc_id_change_wrap code').text();
						let affiliate_link = '';
						if ( -1 !== path.indexOf( '?' ) ) {
							affiliate_link = start + path + '&<?php echo esc_js( $pname ) . '='; ?>' + affiliate_id ;
						} else {
							affiliate_link = start + path + '?<?php echo esc_js( $pname ) . '='; ?>' + affiliate_id ;
						}
						jQuery('#afwc_generated_affiliate_link').text( affiliate_link );
					});
					jQuery('#afwc_save_account_button').on('click', function(){
						let form_data      = jQuery('#afwc_account_form').serialize();
						let status_element = jQuery('#afwc_account_form .afwc_save_account_status');
						status_element.removeClass('afwc_status_yes').removeClass('afwc_status_no').removeClass('afwc_status_spinner').addClass('afwc_status_spinner');
						jQuery.ajax({
							url: '<?php echo esc_url( WC_AJAX::get_endpoint( 'afwc_save_account_details' ) ); ?>',
							type: 'post',
							dataType: 'json',
							data: {
								security: '<?php echo esc_js( wp_create_nonce( 'afwc-save-account-details' ) ); ?>',
								user_id: '<?php echo esc_attr( $user->ID ); ?>',
								form_data: decodeURIComponent( form_data )
							},
							success: function( response ) {
								if ( response.success ) {
									if ( 'yes' === response.success ) {
										status_element.removeClass('afwc_status_yes').removeClass('afwc_status_no').removeClass('afwc_status_spinner').addClass('afwc_status_yes');
									} else if ( 'no' === response.success ) {
										status_element.removeClass('afwc_status_yes').removeClass('afwc_status_no').removeClass('afwc_status_spinner').addClass('afwc_status_no');
										if ( response.message ) {
											alert( response.message );
										}
									}
								}
							}
						});
					});
					jQuery('#afwc_resources_wrapper').on( 'click', '#afwc_change_identifier', function( e ) {
						e.preventDefault();
						jQuery('#afwc_id_change_wrap, #afwc_id_save_wrap').toggle();
					});

					jQuery('#afwc_resources_wrapper').on( 'click', '#afwc_save_identifier', function( e ) {
						e.preventDefault();
						var id = jQuery(this)[0].id;
						var ref_url_id = jQuery('#afwc_ref_url_id').val();
						if ( 'afwc_save_identifier' === id && ref_url_id !== '' ) {
							// Validate ref_url_id.
							if ( !isNaN(parseFloat(ref_url_id)) && isFinite(ref_url_id) ) {
								var msg = "<?php echo esc_html__( 'Numeric values are not allowed.', 'affiliate_for_woocommerce' ); ?>" ;
								jQuery( '#afwc_id_msg' ).html( msg ).addClass( 'afwc_error' ).show();
								return;
							}
							var regx = /[!@#$%^&*.: ]/g ;
							if ( regx.test(ref_url_id) ) {
								var msg = "<?php echo esc_html__( 'Special characters are not allowed.', 'affiliate_for_woocommerce' ); ?>" ;
								jQuery( '#afwc_id_msg' ).html( msg ).addClass( 'afwc_error' ).show();
								return;
							}
							jQuery('#afwc_save_id_loader').show();
							// Ajax call to save id.
							jQuery.ajax({
								url: '<?php echo esc_url( WC_AJAX::get_endpoint( 'afwc_save_ref_url_identifier' ) ); ?>',
								type: 'post',
								dataType: 'json',
								data: {
									security: '<?php echo esc_js( wp_create_nonce( 'afwc-save-ref-url-identifier' ) ); ?>',
									user_id: '<?php echo esc_attr( $user->ID ); ?>',
									ref_url_id: ref_url_id
								},
								success: function( response ) {
									jQuery('#afwc_save_id_loader').hide();
									if ( response.success ) {
										if ( 'yes' === response.success ) {
											jQuery('#afwc_id_change_wrap, #afwc_id_save_wrap').toggle();
											jQuery( '#afwc_id_msg' ).html( response.message ).addClass( 'afwc_sucess' ).removeClass( 'afwc_error' ).show();
											jQuery('#afwc_id_change_wrap').find('code').text(ref_url_id);
											jQuery('.afwc_ref_id_span').text(ref_url_id);
											let affiliate_link = '<?php echo esc_url( trailingslashit( home_url() ) ); ?>' + '?<?php echo esc_js( $pname ) . '='; ?>' + ref_url_id ;
											jQuery('#afwc_affiliate_link_label').text(affiliate_link);
										} else if ( 'no' === response.success ) {
											jQuery( '#afwc_id_msg' ).html( response.message ).addClass( 'afwc_error' ).removeClass( 'afwc_sucess' ).show();
										}
									}
									setTimeout( function(){ jQuery( '#afwc_id_msg' ).hide(); }, 10000);
								}
							});
						}


					})
				});
				function afwc_copy_affiliate_link( obj ) {
					let element = jQuery("<input>");
					jQuery("body").append(element);
					element.val(jQuery(obj).text()).select();
					document.execCommand("copy");
					element.remove();
				}
			</script>
			<div id="afwc_resources_wrapper">
				<div id="afwc_referral_url_container">
					<p id="afwc_id_change_wrap">
						<?php echo esc_html__( 'Your affiliate identifier is: ', 'affiliate-for-woocommerce' ) . '<code>' . esc_html( $affiliate_id ) . '</code>'; ?>
						<?php
						if ( 'yes' === $afwc_allow_custom_affiliate_identifier ) {
							?>
							<a href="#" id="afwc_change_identifier" title="<?php echo esc_attr__( 'Click to change', 'affiliate-for-woocommerce' ); ?>"><i class="fa fa-pencil-alt  "></i></a>
							<?php
						}
						?>
					</p>
					<?php
					if ( 'yes' === $afwc_allow_custom_affiliate_identifier ) {
						?>
					<p id="afwc_id_save_wrap" style="display: none" ><?php echo esc_html__( 'Change affiliate identifier: ', 'affiliate-for-woocommerce' ); ?>
						<input type="text" id="afwc_ref_url_id" value="<?php echo esc_attr( $affiliate_id ); ?>"/>
						<button type="button" id="afwc_save_identifier" name="afwc_save_identifier"><?php echo esc_html__( 'Save', 'affiliate-for-woocommerce' ); ?></button>
					</p>
					<p id="afwc_id_msg" style="display: none"></p>
					<p id="afwc_save_id_loader" style="display: none"><img src=" <?php echo esc_url( WC()->plugin_url() . '/assets/images/wpspin-2x.gif' ); ?>" ></p>
					<p><?php echo esc_html__( 'You can change above identifier to anything like your name, brand name.', 'affiliate-for-woocommerce' ); ?></p>
						<?php
					}
					?>
					<p><?php echo esc_html__( 'Your referral URL is: ', 'affiliate-for-woocommerce' ); ?>
						<code id="afwc_affiliate_link_label" title="<?php echo esc_attr__( 'Click to copy', 'affiliate-for-woocommerce' ); ?>" onclick="afwc_copy_affiliate_link(this)"><?php echo esc_url( trailingslashit( home_url() ) . '?' . $pname . '=' ); ?><span class="afwc_ref_id_span"><?php echo esc_attr( $affiliate_id ); ?></code>
					</p>
					<?php if ( 'yes' === $use_referral_coupons ) { ?>
							<?php
							if ( empty( $referral_coupon_code ) ) {
								if ( ( ! empty( $afwc_admin_contact_email ) ) ) {
									?>
									<p>
										<?php echo esc_html__( 'Want an exclusive coupon to promote?', 'affiliate-for-woocommerce' ); ?>
										<a href="mailto:<?php echo esc_attr( $afwc_admin_contact_email ); ?>?subject=[Affiliate Partner] Send me an exclusive coupon&body=Hi%20there%0D%0A%0D%0APlease%20send%20me%20a%20affiliate%20coupon%20for%20running%20a%20promotion.%0D%0A%0D%0AThanks%0D%0A%0D%0A">
											<?php echo esc_html__( 'Request store admin for a coupon', 'affiliate-for-woocommerce' ); ?>
										</a>
									</p>
									<?php
								}
							} else {
								?>
								<p>
									<?php echo esc_html__( 'Your referral coupon details: ', 'affiliate-for-woocommerce' ); ?>
									<table class="woocommerce-table shop_table afwc_coupons">
										<thead>
											<tr>
												<th>
													<?php echo esc_html__( 'Coupon code', 'affiliate-for-woocommerce' ); ?>
												</th>
												<th>
													<?php echo esc_html__( 'Amount', 'affiliate-for-woocommerce' ); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $referral_coupon_code as $coupon_id => $coupon_code ) { ?>
												<tr>
													<td>
														<code id="afwc_referral_coupon" title="<?php echo esc_attr__( 'Click to copy', 'affiliate-for-woocommerce' ); ?>" onclick="afwc_copy_affiliate_link(this)"><?php echo esc_html( $coupon_code ); ?></code>
													</td>
													<td>
														<span>
															<?php
																$coupon_params = $afwc_coupon->get_coupon_params( $coupon_code );
															if ( ! empty( $coupon_params ) ) {
																$coupon_discount_amount = $coupon_params['discount_amount'];
																$coupon_discount_type   = $coupon_params['discount_type'];
																if ( in_array( $coupon_discount_type, array( 'percent', 'sign_up_fee_percent', 'recurring_percent' ), true ) ) {
																	$coupon_with_discount = wp_kses_post( $coupon_discount_amount ) . '%';
																} else {
																	$coupon_with_discount = wp_kses_post( AFWC_CURRENCY ) . wc_format_decimal( $coupon_discount_amount, wc_get_price_decimals() );
																}
																echo esc_attr__( $coupon_with_discount ); // phpcs:ignore
															}
															?>
														</span>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</p>
								<?php
							}
					}
					?>
				</div>
				<div id="afwc_custom_referral_url_container">
					<p><strong><?php echo esc_html__( 'Referral URL generator', 'affiliate-for-woocommerce' ); ?></strong></p>
					<p><?php echo esc_html__( 'Page URL', 'affiliate-for-woocommerce' ); ?>:
						<span id="afwc_custom_referral_url">
							<?php echo esc_url( trailingslashit( home_url() ) ); ?>
							<input type="text" id="afwc_affiliate_link" name="afwc_affiliate_link" placeholder="<?php echo esc_html__( 'Enter target path here...', 'affiliate-for-woocommerce' ); ?>">
							<?php echo esc_url( '?' . $pname . '=' ); ?><span class="afwc_ref_id_span"><?php echo esc_attr( $affiliate_id ); ?></span>
						</span>
					</p>
					<p><?php echo esc_html__( 'Referral URL: ', 'affiliate-for-woocommerce' ); ?>
						<code id="afwc_generated_affiliate_link" title="<?php echo esc_attr__( 'Click to copy', 'affiliate-for-woocommerce' ); ?>" onclick="afwc_copy_affiliate_link(this)"><?php echo esc_url( trailingslashit( home_url() ) . '?' . $pname . '=' ); ?><span class="afwc_ref_id_span"><?php echo esc_attr( $affiliate_id ); ?></span></code>
					</p>
				</div>
				<?php
				if ( 'yes' === $paypal_api_settings['value'] ) {
					?>
						<hr>
						<form id="afwc_account_form" action="" method="post">
							<h4><?php echo esc_html__( 'Payment setting', 'affiliate-for-woocommerce' ); ?></h4>
							<div id="afwc_payment_wrapper">
								<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label for="afwc_affiliate_paypal_email"><?php esc_html_e( 'PayPal email address', 'affiliate-for-woocommerce' ); ?></label>
									<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="afwc_affiliate_paypal_email" id="afwc_affiliate_paypal_email" value="<?php echo esc_attr( $afwc_paypal_email ); ?>" /><br>
									<em><?php esc_html_e( 'You will receive your affiliate commission on the above PayPal email address.', 'affiliate-for-woocommerce' ); ?></em>
								</p>
								<p>
									<button type="button" id="afwc_save_account_button" name="afwc_save_account_button"><?php echo esc_html__( 'Save', 'affiliate-for-woocommerce' ); ?></button>
									<span class="afwc_save_account_status"></span>
								</p>
							</div>
						</form>
						<?php
				}
				if ( ! empty( $afwc_admin_contact_email ) ) {
					?>
					<div id="afwc_contact_admin_container">
						<?php echo esc_html__( 'Have any queries?', 'affiliate-for-woocommerce' ); ?>
						<a href="mailto:<?php echo esc_attr( $afwc_admin_contact_email ); ?>">
							<?php echo esc_html__( 'Contact store admin', 'affiliate-for-woocommerce' ); ?>
						</a>
					</p>
					<?php
				}
				?>
			</div>
			<?php

		}

		/**
		 * Function to save account details
		 */
		public function afwc_save_account_details() {
			check_ajax_referer( 'afwc-save-account-details', 'security' );

			$form_data = ( ! empty( $_POST['form_data'] ) ) ? sanitize_text_field( wp_unslash( $_POST['form_data'] ) ) : '';
			$user_id   = ( ! empty( $_POST['user_id'] ) ) ? absint( $_POST['user_id'] ) : 0;

			if ( empty( $form_data ) || empty( $user_id ) ) {
				wp_send_json(
					array(
						'success' => 'no',
						'message' => __(
							'Missing data',
							'affiliate-for-woocommerce'
						),
					)
				);
			}

			if ( ! empty( $form_data ) ) {
				parse_str( $form_data, $data );
			}

			if ( isset( $data['afwc_affiliate_paypal_email'] ) && ! empty( $user_id ) ) {
				update_user_meta( $user_id, 'afwc_paypal_email', $data['afwc_affiliate_paypal_email'] );
			}

			wp_send_json( array( 'success' => 'yes' ) );
		}

		/**
		 * Function to save referral URL identifier
		 */
		public function afwc_save_ref_url_identifier() {
			check_ajax_referer( 'afwc-save-ref-url-identifier', 'security' );

			$user_id    = ( ! empty( $_POST['user_id'] ) ) ? absint( $_POST['user_id'] ) : 0;
			$ref_url_id = ( ! empty( $_POST['ref_url_id'] ) ) ? wc_clean( wp_unslash( $_POST['ref_url_id'] ) ) : ''; // phpcs:ignore
			if ( empty( $ref_url_id ) || empty( $user_id ) ) {
				wp_send_json(
					array(
						'success' => 'no',
						'message' => __(
							'Missing data',
							'affiliate-for-woocommerce'
						),
					)
				);
			}

			if ( is_numeric( $ref_url_id ) ) {
				wp_send_json(
					array(
						'success' => 'no',
						'message' => __(
							'Numberic values are not allowed.',
							'affiliate-for-woocommerce'
						),
					)
				);
			}

			$user_with_ref_url_id = get_users(
				array(
					'meta_key'   => 'afwc_ref_url_id', // phpcs:ignore
					'meta_value' => $ref_url_id, // phpcs:ignore
					'number'     => 1,
					'fields'     => 'ids',
				)
			);
			$user_with_ref_url_id = reset( $user_with_ref_url_id );

			if ( ! empty( $user_with_ref_url_id ) && $user_id !== $user_with_ref_url_id ) {
				wp_send_json(
					array(
						'success' => 'no',
						'message' => __(
							'This URL identifier already exists. Please choose a diiferent identifier',
							'affiliate-for-woocommerce'
						),
					)
				);
			} else {
				update_user_meta( $user_id, 'afwc_ref_url_id', $ref_url_id );
				wp_send_json(
					array(
						'success' => 'yes',
						'message' => __(
							'Identifier saved successfully.',
							'affiliate-for-woocommerce'
						),
					)
				);
			}
		}

		/**
		 * Hooks for endpoint
		 */
		public function endpoint_hooks() {
			$affiliate_for_woocommerce = Affiliate_For_WooCommerce::get_instance();
			if ( $affiliate_for_woocommerce->is_wc_gte_34() ) {
				add_filter( 'woocommerce_get_settings_advanced', array( $this, 'add_endpoint_account_settings' ) );
			} else {
				add_filter( 'woocommerce_account_settings', array( $this, 'add_endpoint_account_settings' ) );
			}
		}

		/**
		 * Add UI option for changing Affiliate endpoints in WC settings
		 *
		 * @param mixed $settings Existing settings.
		 * @return mixed $settings
		 */
		public function add_endpoint_account_settings( $settings ) {
			$affiliate_endpoint_setting = array(
				'title'    => __( 'Affiliate', 'affiliate-for-woocommerce' ),
				'desc'     => __( 'Endpoint for the My Account &rarr; Affiliate page', 'affiliate-for-woocommerce' ),
				'id'       => 'woocommerce_myaccount_afwc_dashboard_endpoint',
				'type'     => 'text',
				'default'  => 'afwc-dashboard',
				'desc_tip' => true,
			);

			$after_key = 'woocommerce_myaccount_view_order_endpoint';

			$after_key = apply_filters(
				'afwc_endpoint_account_settings_after_key',
				$after_key,
				array(
					'settings' => $settings,
					'source'   => $this,
				)
			);

			Affiliate_For_WooCommerce::insert_setting_after( $settings, $after_key, $affiliate_endpoint_setting );

			return $settings;
		}

		/**
		 * Function to show content resources
		 *
		 * @param WP_User $user The user object.
		 */
		public function campaigns_content( $user = null ) {
			if ( ! is_object( $user ) || empty( $user->ID ) ) {
				return;
			}
			?>
			<div id="afw-campaigns"></div>

			<?php
		}

	}

}

AFWC_My_Account::get_instance();
