<?php
/**
 * Main class for Affiliate settings under user profile
 *
 * @since       1.0.0
 * @version     1.3.7
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Affiliate' ) ) {

	/**
	 * Class for Admin Affiliate
	 */
	class AFWC_Admin_Affiliate {

		/**
		 * The affiliate id
		 *
		 * @var mixed $aff_id
		 */
		public $aff_id = null;

		/**
		 * The active affiliate
		 *
		 * @var $active_affiliate
		 */
		public $active_affiliate = null;

		/**
		 * Constructor
		 *
		 * @param mixed $aff_id The affiliate id.
		 */
		public function __construct( $aff_id = '' ) {
			global $wpdb;

			add_action( 'show_user_profile', array( $this, 'afwc_can_be_affiliate' ) );
			add_action( 'edit_user_profile', array( $this, 'afwc_can_be_affiliate' ) );

			add_action( 'personal_options_update', array( $this, 'save_afwc_can_be_affiliate' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_afwc_can_be_affiliate' ) );

			add_action( 'admin_footer', array( $this, 'styles_scripts' ) );

			if ( ! empty( $aff_id ) ) {
				if ( false !== $this->active_affiliate ) {
					return $this->active_affiliate;
				}

				return self::get_instance( $aff_id );
			}

			add_action( 'wp_ajax_afwc_json_search_tags', array( $this, 'afwc_json_search_tags' ), 1, 2 );
		}

		/**
		 * Get the instance
		 *
		 * @param  integer $aff_id The affiliate id.
		 * @return WP_User
		 */
		public function get_instance( $aff_id ) {
			if ( $aff_id === self::$aff_id && false !== self::$active_affiliate ) {
				return self::$active_affiliate;
			}

			$aff                    = new WP_User( $aff_id );
			self::$active_affiliate = $aff;
			return self::$active_affiliate;
		}

		/**
		 * Can user be affiliate?
		 * Add settings if user is affiliate
		 *
		 * @param  WP_User $user The user object.
		 */
		public function afwc_can_be_affiliate( $user ) {
			$user_id = ( ! empty( $user->ID ) ) ? $user->ID : '';

			if ( empty( $user_id ) ) {
				return;
			}

			$is_affiliate           = afwc_is_user_affiliate( $user );
			$afwc_affiliate_desc    = ( ! empty( $user_id ) ) ? get_user_meta( $user_id, 'afwc_affiliate_desc', true ) : '';
			$afwc_affiliate_contact = ( ! empty( $user_id ) ) ? get_user_meta( $user_id, 'afwc_affiliate_skype', true ) : '';
			$afwc_affiliate_contact = ( ! empty( $user_id ) && empty( $afwc_affiliate_contact ) ) ? get_user_meta( $user_id, 'afwc_affiliate_contact', true ) : $afwc_affiliate_contact;
			$plugin_data            = Affiliate_For_WooCommerce::get_plugin_data();
			wp_enqueue_style( 'afwc-admin-affiliate-style', AFWC_PLUGIN_URL . '/assets/css/afwc-admin-affiliate.css', array(), $plugin_data['Version'] );
			// Enqueue script.
			wp_enqueue_script( 'afwc-user-profile-js', AFWC_PLUGIN_URL . '/assets/js/afwc-user-profile.js', array( 'jquery' ), $plugin_data['Version'], true );
			$profile_js_params = array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'afwc_security' => wp_create_nonce( AFWC_AJAX_SECURITY ),
			);
			wp_localize_script( 'afwc-user-profile-js', 'profile_js_params', $profile_js_params );
			wp_nonce_field( 'afwc_affiliate_settings_security', 'afwc_affiliate_settings_security', false );
			$user_tags = wp_get_object_terms( $user_id, 'afwc_user_tags', array( 'fields' => 'id=>name' ) );
			$all_tags  = get_afwc_user_tags_id_name_map();

			?>
			<div class="afwc-settings-wrap">
				<h2 id="afwc-settings"><?php echo esc_html__( 'Affiliate For WooCommerce settings', 'affiliate-for-woocommerce' ); ?></h2>
				<table class="form-table" id="afwc">
						<?php
						if ( 'pending' === $is_affiliate ) {
							?>
							<tr>
								<th><label for="afwc_affiliate_action"><?php echo esc_html__( 'Action', 'affiliate-for-woocommerce' ); ?></label></th>
								<td>
									<span class="afwc-approve afwc-actions-wrap"><i class="dashicons dashicons-yes"></i><a href="#" class="afwc_actions" data-afwc_action="approve" > <?php echo esc_html__( 'Approve affiliate', 'affiliate-for-woocommerce' ); ?></a></span>
									<span class="afwc-disapprove afwc-actions-wrap"><i class="dashicons dashicons-no-alt"></i><a href="#" class="afwc_actions" data-afwc_action="disapprove"> <?php echo esc_html__( 'Reject affiliate', 'affiliate-for-woocommerce' ); ?></a></span>
									<input type="hidden" id="afwc_review_pending" name="afwc_review_pending" value="yes"/>
								</td> 
							</tr>
							<?php
						}
						?>
						<tr>
							<th><label for="afwc_affiliate_link"><?php echo esc_html__( 'Is affiliate?', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><input type="checkbox" name="afwc_is_affiliate" value="yes" 
							<?php
							if ( 'yes' === $is_affiliate ) {
								echo esc_attr( 'checked="checked"' );
							}
							?>
							></td>
						</tr>
						<?php
						if ( ! empty( $afwc_affiliate_desc ) ) {
							?>
							<tr>
							<th><label for="afwc_affiliate_desc"><?php echo esc_html__( 'About affiliate', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><div class="afwc_affiliate_desc"><?php echo esc_attr__( $afwc_affiliate_desc ); // phpcs:ignore ?></div></td>
							</tr>
							<?php
						}
						if ( ! empty( $afwc_affiliate_contact ) ) {
							?>
							<tr>
							<th><label for="afwc_affiliate_skype"><?php echo esc_html__( 'Way to contact', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><div class="afwc_affiliate_skype"><?php echo esc_attr__( $afwc_affiliate_contact ); // phpcs:ignore ?></div></td>
							</tr>
							<?php
						}
						?>
					<?php
					if ( 'yes' === $is_affiliate && ! empty( $user_id ) ) {
						$pname           = get_option( 'afwc_pname', 'ref' );
						$pname           = ( ! empty( $pname ) ) ? $pname : 'ref';
						$afwc_ref_url_id = get_user_meta( $user->ID, 'afwc_ref_url_id', true );
						$affiliate_id    = get_affiliate_id_based_on_user_id( $user->ID );
						$affiliate_id    = ( ! empty( $afwc_ref_url_id ) ) ? $afwc_ref_url_id : $affiliate_id;
						$affiliate_link  = add_query_arg( $pname, $affiliate_id, trailingslashit( home_url() ) );
						?>
						<tr>
							<th><label for="afwc_affiliate_link"><?php echo esc_html__( 'Affiliate URL', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><label><?php echo esc_url( $affiliate_link ); ?></label></td>
						</tr>
						<?php
						$use_referral_coupons = get_option( 'afwc_use_referral_coupons', 'no' );
						$afwc_coupon          = AFWC_Coupon::get_instance();
						$referral_coupons     = $afwc_coupon->get_referral_coupon( array( 'user_id' => $user_id ) );
						if ( 'yes' === $use_referral_coupons && ! empty( $referral_coupons ) && is_array( $referral_coupons ) ) {
							?>
							<tr>
								<th><label for="afwc_referral_coupon"><?php echo esc_html__( 'Affiliate coupons', 'affiliate-for-woocommerce' ); ?></label></th>
								<td><label>
									<?php
									foreach ( $referral_coupons as $coupon_id => $coupon_code ) {
										?>
										<a href="<?php echo esc_url( get_edit_post_link( $coupon_id ) ); ?>" target="_blank">
											<?php echo esc_attr__( $coupon_code ); // phpcs:ignore ?>
										</a><br>
										<?php
									}
									?>
								</label></td>
							</tr>
							<?php
						}

						$paypal_api_settings = AFWC_Paypal::get_instance()->get_api_setting_status();
						if ( 'yes' === $paypal_api_settings['value'] ) {
							$afwc_paypal_email = ( ! empty( $user_id ) ) ? get_user_meta( $user->ID, 'afwc_paypal_email', true ) : '';
							?>
							<tr>
								<th><label for="afwc_paypal_email"><?php echo esc_html__( 'PayPal email address', 'affiliate-for-woocommerce' ); ?></label></th>
								<td><input type="email" name="afwc_paypal_email" id="afwc_paypal_email" value="<?php echo esc_attr( $afwc_paypal_email ); ?>" class="regular-text" placeholder="<?php echo esc_attr__( 'Enter PayPal email address for payouts of this affiliate...', 'affiliate-for-woocommerce' ); ?>"></td>
							</tr>
							<?php
						}

						$affiliate_tags_desc        = '';
						$affiliate_manage_tags_link = admin_url( 'edit-tags.php?taxonomy=afwc_user_tags' );
						if ( ! empty( $affiliate_manage_tags_link ) ) {
							/* translators: Link to the affiliate tags */
							$affiliate_tags_desc = sprintf( esc_html__( '%s.', 'affiliate-for-woocommerce' ), '<strong><a target="_blank" href="' . esc_url( $affiliate_manage_tags_link ) . '">' . esc_html__( 'Manage tags', 'affiliate-for-woocommerce' ) . '</a></strong>' );
						}
						?>
						<tr>
							<th><label for="afwc_user_tags"><?php esc_attr_e( 'Select tags for affiliate', 'affiliate-for-woocommerce' ); ?></label><br><br><?php echo wp_kses_post( $affiliate_tags_desc ); ?></th>
							<td>
								<select id="afwc_user_tags" name="afwc_user_tags[]" style="width: 50%;" class="wc-afw-tags-search" data-placeholder="<?php esc_attr_e( 'Search tags', 'affiliate-for-woocommerce' ); ?>" data-allow_clear="true" data-action="afwc_json_search_tags" multiple="multiple">
									<?php
									$html = '';
									foreach ( $all_tags as $id => $tag ) {
										$selected = ( is_array( $user_tags ) && in_array( $tag, $user_tags, true ) ) ? ' selected="selected"' : '';
										?>
										<option value="<?php echo esc_attr( $id ); ?>" <?php echo esc_attr( $selected ); ?> > <?php echo esc_attr( $tag ); ?></option>
										<?php
									}
									?>
								</select>

							</td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		}

		/**
		 * Save can be affiliate data
		 *
		 * @param int $user_id User ID of the user being saved.
		 */
		public function save_afwc_can_be_affiliate( $user_id ) {

			if ( empty( $user_id ) ) {
				return;
			}

			if ( ! isset( $_POST['afwc_affiliate_settings_security'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['afwc_affiliate_settings_security'] ) ), 'afwc_affiliate_settings_security' )  ) { // phpcs:ignore
				return;
			}

			$post_afwc_review_pending = ( isset( $_POST['afwc_review_pending'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_review_pending'] ) ) : ''; // phpcs:ignore
			$post_afwc_is_affiliate = ( isset( $_POST['afwc_is_affiliate'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_is_affiliate'] ) ) : ''; // phpcs:ignore
			$post_afwc_paypal_email = ( isset( $_POST['afwc_paypal_email'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_paypal_email'] ) ) : ''; // phpcs:ignore
			$post_afwc_user_tags = ( isset( $_POST['afwc_user_tags'] ) ) ?  wc_clean( wp_unslash( $_POST['afwc_user_tags'] ) )  : array(); // phpcs:ignore			

			$user             = get_user_by( 'id', $user_id );
			$old_is_affiliate = afwc_is_user_affiliate( $user );

			if ( 'yes' === $post_afwc_is_affiliate ) {
				update_user_meta( $user_id, 'afwc_is_affiliate', $post_afwc_is_affiliate );
				if ( 'pending' === $old_is_affiliate ) {
					// Send welcome email to affiliate.
					$mailer = WC()->mailer();
					if ( $mailer->emails['Afwc_Welcome_Affiliate_Email']->is_enabled() ) {
						// Prepare args.
						$args = array(
							'affiliate_id' => $user_id,
						);
						// Trigger email.
						do_action( 'afwc_welcome_affiliate_email', $args );
					}
				}
			} else {
				$afwc_is_affiliate = ( 'yes' === $post_afwc_review_pending ) ? 'pending' : 'no';
				update_user_meta( $user_id, 'afwc_is_affiliate', $afwc_is_affiliate );
			}

			if ( ! empty( $post_afwc_paypal_email ) ) {
				update_user_meta( $user_id, 'afwc_paypal_email', $post_afwc_paypal_email );
			} else {
				delete_user_meta( $user_id, 'afwc_paypal_email' );
			}

			if ( ! empty( $post_afwc_user_tags ) ) {
				foreach ( $post_afwc_user_tags as $key => $value ) {
					if ( ctype_digit( $value ) ) {
						$term_name                   = get_term( $value )->name;
						$post_afwc_user_tags[ $key ] = $term_name;
					}
				}
			}
			wp_set_object_terms( $user_id, $post_afwc_user_tags, 'afwc_user_tags' );

		}

		/**
		 * Styles & scripts
		 */
		public function styles_scripts() {
			global $pagenow;

			if ( 'profile.php' === $pagenow || 'user-edit.php' === $pagenow ) {

				if ( ! wp_script_is( 'jquery' ) ) {
					wp_enqueue_script( 'jquery' );
				}

				$get_affiliate_roles = get_option( 'affiliate_users_roles' );
				?>
				<script type="text/javascript">
					jQuery(function() {
						jQuery('body').on('change', 'select#role', function(){
							let selectedRole = jQuery(this).find(':selected').val();
							let isAffiliate = jQuery('input[name="afwc_is_affiliate"]').is(':checked');
							let roles = '<?php echo wp_json_encode( $get_affiliate_roles ); ?>';
							affiliate_roles = jQuery.parseJSON( roles );
							if ( false === isAffiliate && -1 !== jQuery.inArray( selectedRole, affiliate_roles ) ) {
								jQuery('input[name="afwc_is_affiliate"]').attr( 'checked', true );
							}
						});
					});
				</script>
				<?php
			}
		}

		/**
		 * Search for attribute values and return json
		 *
		 * @param string $x string.
		 * @param string $attribute string.
		 * @return void
		 */
		public function afwc_json_search_tags( $x = '', $attribute = '' ) {

			check_ajax_referer( AFWC_AJAX_SECURITY, 'security' );

			$term = ( ! empty( $_GET['term'] ) ) ? (string) urldecode( stripslashes( wp_strip_all_tags( $_GET ['term'] ) ) ) : ''; // phpcs:ignore
			if ( empty( $term ) ) {
				die();
			}

			$tags = array();
			$args = array(
				'taxonomy'   => 'afwc_user_tags', // taxonomy name.
				'hide_empty' => false,
				'name__like' => $term,
			);

			$raw_tags = get_terms( $args );
			if ( $raw_tags ) {
				foreach ( $raw_tags as $key => $value ) {
					$tags[ $value->term_id ] = $value->name;
				}
			}
			echo wp_json_encode( $tags );
			die();

		}



	}
}

return new AFWC_Admin_Affiliate();
