<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_CSV_Exporter', false ) ) {
	require_once WC_ABSPATH . 'includes/export/abstract-wc-csv-exporter.php';
}

class WCCT_Product_Exporter extends WC_CSV_Exporter {

	protected $export_type = 'product';

	/**
	 * WCCT_Product_Exporter constructor.
	 */
	public function __construct() {
		$this->filename = sanitize_file_name( 'wcct-product-export-' . date_i18n( 'Y-m-d_H_i_s', current_time( 'mysql' ) ) . '.csv' );
	}

	/**
	 * Prepare data for export
	 */
	public function prepare_data_to_export() {
		$data_feed    = get_option( 'wcct_data_feed' );
		$product_cat  = isset( $data_feed['product_cat'] ) ? $data_feed['product_cat'] : '';
		$exclude_type = isset( $data_feed['types'] ) ? $data_feed['types'] : '';

		$args = [
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'tax_query' => [
				[
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $product_cat,
					'operator' => 'NOT IN',
				],
				[
					'taxonomy' => 'product_type',
					'field' => 'slug',
					'terms' => $exclude_type,
					'operator' => 'NOT IN',
				],
				[
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => 'exclude-from-catalog',
					'operator' => 'NOT IN',
				],
			],
		];

		$posts = new WP_Query( $args );

		while ( $posts->have_posts() ) {
			$posts->the_post();
			global $product;

			$attachment_ids = $product->get_gallery_image_ids();
			$img_urls = [];

			if ( $attachment_ids ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$img_urls[] = wp_get_attachment_url( $attachment_id );
				}
			}

			$additional_image_link = implode( ',', $img_urls );
			$availability          = $product->is_in_stock() ? 'In stock' : 'Out of stock';
			$brand                 = strip_tags( $this->get_store_name() );

			$featured_image_id = get_post_thumbnail_id( $product->get_id() );
			$image             = wp_get_attachment_image_src( $featured_image_id, 'full' );
			$featured_image    = ! empty( $image ) ? $image[0] : '';
			$condition         = apply_filters( 'wcct_product_condition', 'New', $product );

			$this->row_data[] = [
				$product->get_id(),
				$product->get_title(),
				$product->get_description(),
				$product->get_permalink( $product->get_id() ),
				$product->get_price() . ' ' . get_woocommerce_currency(),
				$brand,
				$availability,
				$featured_image,
				$condition,
				$additional_image_link,
			];
		}

		wp_reset_postdata();
	}

	/**
	 * Get default column header
	 *
	 * @return array|string[]
	 */
	public function get_column_names() {
		return apply_filters(
			'wcct_csv_columns', [
				'id',
				'title',
				'description',
				'link',
				'price',
				'brand',
				'availability',
				'image_link',
				'condition',
				'additional_image_link',
			]
		);
	}

	/**
	 * Get store name
	 *
	 * @return array|false|int|string|null
	 */
	private function get_store_name() {
		$name = get_bloginfo( 'name' );

		if ( $name ) {
			return $name;
		}
		// Fallback to site url
		$url = get_site_url();
		if ( $url ) {
			return parse_url( $url, PHP_URL_HOST );
		}
		// If site url doesn't exist, fall back to http host.
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			return wp_kses_post( $_SERVER['HTTP_HOST'] );//phpcs:ignore
		}

		// If http host doesn't exist, fall back to local host name.
		$url = gethostname();
		return ( $url ) ? $url : 'A Store Has No Name';
	}
}

