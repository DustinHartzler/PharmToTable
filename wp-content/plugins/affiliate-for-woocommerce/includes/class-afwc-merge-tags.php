<?php
/**
 * Main class for Affiliate Merge tags.
 *
 * @package    affiliate-for-woocommerce/includes/
 * @since      6.24.0
 * @version    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Merge_Tags' ) ) {

	/**
	 * Class to handle the Affiliate Merge tags.
	 */
	class AFWC_Merge_Tags {

		/**
		 * Array to hold merge tags.
		 *
		 * @var array
		 */
		public $merge_tags = array();

		/**
		 * Singleton instance of AFWC_Merge_Tags.
		 *
		 * @var AFWC_Merge_Tags|null
		 */
		private static $instance = null;

		/**
		 * Get the singleton instance of this class
		 *
		 * @return AFWC_Merge_Tags Singleton instance of this class
		 */
		public static function get_instance() {
			// Check if instance already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			// Set the merge tags.
			$this->set_tags();

			// Hook to decode the merge tags for post content.
			add_filter( 'the_content', array( $this, 'parse_content' ), 10, 2 );
		}

		/**
		 * Set the predefined merge tags.
		 */
		public function set_tags() {
			$this->merge_tags = apply_filters(
				'afwc_merge_tags',
				array(
					'afwc_affiliate_id',
					'afwc_affiliate_name',
					'afwc_affiliate_link',
				)
			);
		}

		/**
		 * Get the value of a merge tag.
		 *
		 * @param string             $tag       The merge tag to retrieve.
		 * @param AFWC_Affiliate|int $affiliate An instance of AFWC_Affiliate or affiliate ID.
		 *
		 * @return mixed Value of the merge tag.
		 */
		public function get_value( $tag = '', $affiliate = 0 ) {
			if ( empty( $tag ) || empty( $affiliate ) ) {
				return '';
			}

			// If the affiliate parameter is an ID, create an AFWC_Affiliate instance.
			$affiliate = is_numeric( $affiliate ) ? new AFWC_Affiliate( intval( $affiliate ) ) : $affiliate;

			if ( ! $affiliate instanceof AFWC_Affiliate || empty( $affiliate->ID ) ) {
				return '';
			}

			$value = '';

			switch ( $tag ) {
				case 'afwc_affiliate_id':
					$value = ! empty( $affiliate->affiliate_id ) ? $affiliate->affiliate_id : '';
					break;

				case 'afwc_affiliate_name':
					$value = ! empty( $affiliate->display_name ) ? $affiliate->display_name : '';
					break;

				case 'afwc_affiliate_link':
					$value = is_callable( array( $affiliate, 'get_affiliate_link' ) ) ? $affiliate->get_affiliate_link() : '';
					break;
			}

			// Apply filters to the merge tag value before returning.
			return apply_filters(
				"afwc_get_merge_tag_value_{$tag}",
				$value,
				array(
					'affiliate' => $affiliate,
					'source'    => $this,
				)
			);
		}

		/**
		 * Get the merge tags from the contents.
		 *
		 * @param string $content Content to check for merge tags.
		 *
		 * @return array Array of merge tags available in the provided content.
		 */
		public function get_tags_by_content( $content = '' ) {
			if ( empty( $content ) || empty( $this->merge_tags ) || ! is_array( $this->merge_tags ) ) {
				return array();
			}

			preg_match_all( '/\{([^{}]+)\}/', $content, $matches );

			return ! empty( $matches ) && ! empty( $matches[1] ) && is_array( $matches[1] ) ? array_intersect( $this->merge_tags, $matches[1] ) : array();
		}

		/**
		 * Method to decode the merge tags of the provided content.
		 *
		 * @param string $content Content to decode the merge tags.
		 * @param array  $args The arguments for merge tags.
		 *
		 * @return string Return the formatted content.
		 */
		public function parse_content( $content = '', $args = array() ) {
			if ( empty( $content ) ) {
				return $content;
			}

			$affiliate = ! empty( $args['affiliate'] ) ? $args['affiliate'] : null;

			// If the affiliate is an ID, create an AFWC_Affiliate instance.
			$affiliate = is_numeric( $affiliate ) ? new AFWC_Affiliate( intval( $affiliate ) ) : $affiliate;

			$tag_names = $this->get_tags_by_content( $content );

			// Return if no merge tag is available in the content.
			if ( empty( $tag_names ) ) {
				return $content;
			}

			$tag_values = array();
			foreach ( $tag_names as $tag_name ) {
				$encode_tag_name = '{' . $tag_name . '}';
				// Set a blank value if the current logged in user object is not an AFWC_Affiliate.
				$tag_values[ $encode_tag_name ] = $affiliate instanceof AFWC_Affiliate ? $this->get_value( $tag_name, $affiliate ) : '';
			}

			return ! empty( $tag_values ) ? str_replace( array_keys( $tag_values ), array_values( $tag_values ), $content ) : $content;
		}
	}

}

AFWC_Merge_Tags::get_instance();
