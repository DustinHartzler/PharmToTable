<?php
/**
 * Main class for Affiliate settings under user profile
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       1.0.0
 * @version     1.4.2
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

			// Delete parent chain when deleting a user.
			add_action( 'delete_user', array( $this, 'delete_parent_chain_meta' ) );

			add_action( 'admin_footer', array( $this, 'styles_scripts' ) );

			if ( ! empty( $aff_id ) ) {
				if ( false !== $this->active_affiliate ) {
					return $this->active_affiliate;
				}

				return self::get_instance( $aff_id );
			}

			add_action( 'wp_ajax_afwc_json_search_tags', array( $this, 'afwc_json_search_tags' ) );
			add_action( 'wp_ajax_afwc_json_search_parent_affiliates', array( $this, 'afwc_json_search_parent_affiliates' ) );
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
			// Register script.
			wp_register_script( 'afwc-user-profile-js', AFWC_PLUGIN_URL . '/assets/js/afwc-user-profile.js', array( 'jquery', 'wp-i18n' ), $plugin_data['Version'], true );
			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'afwc-user-profile-js', 'affiliate-for-woocommerce' );
			}

			$profile_js_params = array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'afwc_security' => wp_create_nonce( AFWC_AJAX_SECURITY ),
			);
			wp_localize_script( 'afwc-user-profile-js', 'profile_js_params', $profile_js_params );
			wp_enqueue_script( 'afwc-user-profile-js' );
			wp_nonce_field( 'afwc_affiliate_settings_security', 'afwc_affiliate_settings_security', false );
			$user_tags = wp_get_object_terms( $user_id, 'afwc_user_tags', array( 'fields' => 'id=>name' ) );
			$all_tags  = afwc_get_user_tags_id_name_map();

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
						<tr id="afwc_is_affiliate_row">
							<th><label for="afwc_affiliate_link"><?php echo esc_html__( 'Is affiliate?', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><input type="checkbox" name="afwc_is_affiliate" value="yes" 
							<?php
							if ( 'yes' === $is_affiliate ) {
								echo esc_attr( 'checked="checked"' );
							}
							?>
							>
							</td>
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
						$affiliate_id    = afwc_get_affiliate_id_based_on_user_id( $user->ID );
						$affiliate_id    = ( ! empty( $afwc_ref_url_id ) ) ? $afwc_ref_url_id : $affiliate_id;
						$affiliate_link  = add_query_arg( $pname, $affiliate_id, trailingslashit( home_url() ) );
						?>
						<tr>
							<th><label for="afwc_affiliate_link"><?php echo esc_html__( 'Affiliate URL', 'affiliate-for-woocommerce' ); ?></label></th>
							<td><label><?php echo esc_url( $affiliate_link ); ?></label></td>
						</tr>
						<?php
						$use_referral_coupons = get_option( 'afwc_use_referral_coupons', 'yes' );
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

						$paypal_api_settings = AFWC_PayPal_API::get_instance()->get_api_setting_status();
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
							/* translators: %1$s: Opening strong tag %2$s: Opening a tage for affiliate manage tag page link %3$s: closing strong tag %4$s: closing a tag for affiliate manage tag page link */
							$affiliate_tags_desc = sprintf( esc_html__( '%1$s%2$sManage affiliate tags%3$s%4$s', 'affiliate-for-woocommerce' ), '<strong>', '<a target="_blank" href="' . esc_url( $affiliate_manage_tags_link ) . '">', '</a>', '</strong>' );
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
					if ( ( 'not_registered' !== $is_affiliate ) && ( 'no' !== $is_affiliate ) ) {
						$parent_chain            = afwc_get_parent_chain( $user_id );
						$is_parent_chain_disable = ( 'yes' === $is_affiliate ) ? '' : 'disabled';
						$parent_id               = '';
						?>
						<tr>				
							<th><label for="afwc_parent_id"><?php echo esc_html__( 'Parent Affiliate', 'affiliate-for-woocommerce' ); ?></label></th>
							<td>
								<select id="afwc_parent_id" name="afwc_parent_id" style="width: 50%;" class="wc-afw-afwc-parent-name-search" data-placeholder="<?php esc_attr_e( 'Search for a Parent Affiliate', 'affiliate-for-woocommerce' ); ?>" data-allow_clear="true" data-user="<?php echo esc_attr( $user_id ); ?>" data-action="afwc_json_search_parent_affiliates" <?php echo esc_attr( $is_parent_chain_disable ); ?>>
								<?php
								if ( ! empty( $parent_chain ) && is_array( $parent_chain ) ) {

									$parent_id = current( $parent_chain );
									$parent    = get_user_by( 'id', $parent_id );

									if ( ! empty( $parent ) ) {
										$parent_string = sprintf(
											'%1$s (#%2$d &ndash; %3$s)',
											isset( $parent->display_name ) ? $parent->display_name : '',
											absint( $parent_id ),
											$parent->user_email
										);

										?>
										<option value="<?php echo esc_attr( $parent_id ); ?>"  selected="<?php echo esc_attr( 'selected' ); ?>" ><?php echo esc_html( htmlspecialchars( wp_kses_post( $parent_string ) ) ); ?></option>
										<?php
									}
								}
								?>
								</select>
								<?php
								if ( 'pending' === $is_affiliate ) {
									?>
										<input name="<?php echo esc_attr( 'afwc_pending_parent_id' ); ?>" type="<?php echo esc_attr( 'hidden' ); ?>" value="<?php echo esc_attr( $parent_id ); ?>" />
										<?php
								}
								?>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<p class="afwc-form-description afwc-update-desc"> <?php echo esc_html_e( 'Note: Click on Update User button to save changes.', 'affiliate-for-woocommerce' ); ?> </p>
			</div>
			<?php
		}

		/**
		 * Function to assign parent.
		 *
		 * @param int|string $user_id    User id.
		 * @param int|string $parent_id  Parent Id.
		 *
		 * @return void.
		 */
		public function assign_parent( $user_id = 0, $parent_id = 0 ) {
			if ( empty( $user_id ) || empty( $parent_id ) ) {
				return;
			}
			$user_id   = absint( $user_id );
			$parent_id = absint( $parent_id );

			if ( 'yes' === afwc_is_user_affiliate( $user_id ) && 'yes' === afwc_is_user_affiliate( $parent_id ) ) {
				$parent_chain = afwc_get_parent_chain( $parent_id );
				// Check if the user id is contains under parent chain.
				$user_pos = array_search( $user_id, $parent_chain ); // phpcs:ignore
				if ( false !== $user_pos ) {
					// Remove the parents after the position of user id.
					$parent_chain = array_splice( $parent_chain, 0, $user_pos );
					if ( ! empty( $parent_chain ) ) {
						// Update the parent chain of the parent.
						update_user_meta( $parent_id, 'afwc_parent_chain', implode( '|', $parent_chain ) . '|' );
					} else {
						delete_user_meta( $parent_id, 'afwc_parent_chain' );
					}
					// Update parent chain of the user.
					$this->update_parent_chain_of_children( $parent_id );
				}
				$parent_chain = ! empty( $parent_chain ) ? array_filter( $parent_chain ) : array();
				$parent_chain = ! empty( $parent_chain ) ? implode( '|', $parent_chain ) : '';
				// Concatenate parent id and parent's parent chain.
				$new_parent_chain = ( ! empty( $parent_chain ) ) ? $parent_id . '|' . $parent_chain : $parent_id;
				update_user_meta( $user_id, 'afwc_parent_chain', $new_parent_chain . '|' );
				$this->update_parent_chain_of_children( $user_id );
			}
		}

		/**
		 * Function to remove parents.
		 * Delete the parent chain of the user.
		 * Update the parent chain of the all children of the user.
		 *
		 * @param int|string $user_id  Array of parent chain.
		 * @param array      $args     The Arguments.
		 *
		 * @return void.
		 */
		public function remove_parent( $user_id = 0, $args = array() ) {

			if ( ! empty( $user_id ) ) {
				// Delete the parent chain of the user.
				delete_user_meta( $user_id, 'afwc_parent_chain' );
				// Update the parent chain of all children.
				$this->update_parent_chain_of_children( $user_id, $args );
			}
		}

		/**
		 * Function to update parent chain of all children by user_id.
		 *
		 * @param int|string $user_id  User id.
		 * @param array      $filters  The Filters.
		 *
		 * @return void.
		 */
		public function update_parent_chain_of_children( $user_id = 0, $filters = array() ) {
			if ( empty( $user_id ) ) {
				return;
			}
			// Prevent if the user is not an affiliate user.
			if ( 'yes' !== afwc_is_user_affiliate( $user_id ) ) {
				return;
			}

			$children_tree = afwc_get_children( $user_id, true );

			if ( ! empty( $children_tree ) ) {
				$user_parents = afwc_get_parent_chain( $user_id );
				foreach ( $children_tree as $child_id => $child_tree ) {
					if ( is_array( $child_tree ) ) {
						// Get the position of the user from the child tree.
						$user_pos = array_search( $user_id, $child_tree ); // phpcs:ignore
						// Get parents till the user position.
						$parents_till_user_pos = array_splice( $child_tree, 0, $user_pos + 1 );
						// Merge parents with user's parents.
						$new_parent_chain = array_merge( $parents_till_user_pos, $user_parents );
						$new_parent_chain = $this->filter_parent_chain( $new_parent_chain, $filters );
						$new_parent_chain = is_array( $new_parent_chain ) ? implode( '|', $new_parent_chain ) : '';

						if ( ! empty( $new_parent_chain ) ) {
							$new_parent_chain = $new_parent_chain . '|';
							// Update the new parent chain.
							update_user_meta( $child_id, 'afwc_parent_chain', $new_parent_chain );
						} else {
							delete_user_meta( $child_id, 'afwc_parent_chain' );
						}
					}
				}
			}
		}

		/**
		 * Filter the parent chain array
		 *
		 * @param array $chain   Array of parent chain.
		 * @param array $args    The arguments.
		 *
		 * @return array.
		 */
		public function filter_parent_chain( $chain = array(), $args = array() ) {

			if ( isset( $args['excludes'] ) ) {
				$chain = ( ! empty( $args['excludes'] ) ) ? array_diff( $chain, $args['excludes'] ) : $chain;
			}

			return $chain;
		}

		/**
		 * Save can be affiliate data
		 *
		 * @param int $user_id User ID of the user being saved.
		 */
		public function save_afwc_can_be_affiliate( $user_id = 0 ) {

			if ( empty( $user_id ) ) {
				return;
			}

			if ( ! isset( $_POST['afwc_affiliate_settings_security'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['afwc_affiliate_settings_security'] ) ), 'afwc_affiliate_settings_security' )  ) { // phpcs:ignore
				return;
			}

			$post_afwc_review_pending    = ( isset( $_POST['afwc_review_pending'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_review_pending'] ) ) : ''; // phpcs:ignore
			$post_afwc_is_affiliate      = ( isset( $_POST['afwc_is_affiliate'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_is_affiliate'] ) ) : ''; // phpcs:ignore
			$post_afwc_paypal_email      = ( isset( $_POST['afwc_paypal_email'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_paypal_email'] ) ) : ''; // phpcs:ignore
			$post_afwc_user_tags         = ( isset( $_POST['afwc_user_tags'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_user_tags'] ) ) : array(); // phpcs:ignore
			$post_afwc_parent_affiliate  = ( isset( $_POST['afwc_parent_id'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_parent_id'] ) ) : 0; // phpcs:ignore
			$post_afwc_pending_parent_id = ( isset( $_POST['afwc_pending_parent_id'] ) ) ? wc_clean( wp_unslash( $_POST['afwc_pending_parent_id'] ) ) : 0; // phpcs:ignore

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

				// Check if the pending parent id is exists.
				if ( empty( $post_afwc_pending_parent_id ) ) {
					if ( ! empty( $post_afwc_parent_affiliate ) ) {
						// Assign new parent to the user.
						$this->assign_parent( $user_id, $post_afwc_parent_affiliate );
					} else {
						// Remove parent, if $post_afwc_parent_affiliate is empty.
						$this->remove_parent( $user_id );
					}
				}
			} else {
				$afwc_is_affiliate = ( 'yes' === $post_afwc_review_pending ) ? 'pending' : 'no';
				// Check if the affiliate is disabled.
				if ( 'no' === $afwc_is_affiliate ) {
					// Delete parent chain meta.
					$this->delete_parent_chain_meta( $user_id );
				}
				// Update affiliate status.
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
		 * Delete parent chain by user id.
		 *
		 * @param int $user_id User id.
		 *
		 * @return void.
		 */
		public function delete_parent_chain_meta( $user_id = 0 ) {

			if ( ! empty( $user_id ) ) {

				$filter = array(
					'excludes' => array( $user_id ),
				);
				$this->remove_parent( $user_id, $filter );
			}
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
		 * Search for affiliate parent and return
		 *
		 * @return void
		 */
		public function afwc_json_search_parent_affiliates() {
			check_ajax_referer( AFWC_AJAX_SECURITY, 'security' );

			$term     = ( ! empty( $_GET['term'] ) ) ? (string) urldecode( stripslashes( wp_strip_all_tags( $_GET ['term'] ) ) ) : ''; // phpcs:ignore
			$user_id  = ( ! empty( $_GET['user_id'] ) ) ? absint( stripslashes( wp_strip_all_tags( $_GET ['user_id'] ) ) ) : ''; // phpcs:ignore
			if ( empty( $term ) ) {
				wp_die();
			}
			$excludes = array( $user_id );
			$excludes = apply_filters( 'afwc_exclude_parent_for_affiliate', $excludes, $user_id );
			$afwc     = Affiliate_For_WooCommerce::get_instance();
			$args     = array(
				'search'  => '*' . $term . '*',
				'exclude' => $excludes,
			);
			$users    = $afwc->get_affiliates( $args );
			$users    = ! empty( $users ) ? $users : array();

			echo wp_json_encode( $users );
			wp_die();
		}

		/**
		 * Search for affiliate tags and return
		 *
		 * @return void
		 */
		public function afwc_json_search_tags() {

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
