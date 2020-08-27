<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The product exporter handler class
 */
class WCCT_Product_Exporter {

    /**
     * Constructor for the WCCT_Product_Exporter Class
     */
    function __construct() {
        add_action( 'init', array( $this, 'do_export' ) );
    }

    /**
     * Export Product
     *
     * @return void
     */
    public function do_export() {
        global $wpdb;

        if ( isset( $_GET['action'] ) && $_GET['action'] == 'wcct-product-export' ) {

            $csv_columns = $this->csv_columns();
            $wpdb->hide_errors();
            set_time_limit(0);

            header( 'Content-Type: text/csv; charset=UTF-8' );
            header( 'Content-Disposition: attachment; filename=wcct-product-export-' . time() . '.csv' );
            header( 'Pragma: no-cache' );
            header( 'Expires: 0' );

            $file = new SplFileObject( 'php://output' );
            $file->fwrite( implode( ',', $csv_columns ) );

            $data_feed      = get_option( 'wcct_data_feed' );
            $product_cat    = isset( $data_feed['product_cat'] ) ? $data_feed['product_cat'] : '';
            $exclude_type   = isset( $data_feed['types'] ) ? $data_feed['types'] : '';

            $args = array(
                'post_type'         => 'product',
                'post_status'       => 'publish',
                'posts_per_page'    => -1,
                'tax_query'         => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $product_cat,
                        'operator' => 'NOT IN',
                    ),
                    array(
                        'taxonomy' => 'product_type',
                        'field'    => 'slug',
                        'terms'    => $exclude_type,
                        'operator' => 'NOT IN'
                    ),
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'exclude-from-catalog',
                        'operator' => 'NOT IN',
                    ),
                ),
            );

            $posts  = new WP_Query( $args );
            if ( ! $posts || is_wp_error( $posts ) ) {
                return;
            }

            while ( $posts->have_posts() ) {
                $posts->the_post();
                global $product;

                $attachmentIds = $product->get_gallery_image_ids();
                $imgUrls = array();

                if ( $attachmentIds ) {
                    foreach( $attachmentIds as $attachmentId ) {
                        $imgUrls[] = wp_get_attachment_url( $attachmentId );
                    }
                }

                $additional_image_link  = implode( ',', $imgUrls );
                $availability           = $product->is_in_stock() ? 'In stock' : 'Out of stock';
                $brand                  = strip_tags( $this->get_store_name() );
                $featured_image_id      = get_post_thumbnail_id( $product->get_id() );
                $image                  = wp_get_attachment_image_src( $featured_image_id, 'full' );
                $condition              = apply_filters( 'wcct_product_condition', 'New', $product );

                $row    = array(
                    $product->get_id(),
                    $product->get_title(),
                    $product->get_description(),
                    $product->get_permalink( $product->get_id() ),
                    $product->get_price() .' '. get_woocommerce_currency(),
                    $brand,
                    $availability,
                    $image[0],
                    $condition,
                    $additional_image_link
                );

                $file->fputcsv( $row );
            }

            $file = null;
            exit;
        }
    }

    /**
     * CSV columns
     *
     * @return array
     */
    public function csv_columns() {

        return array(
            'id',
            'title',
            'description',
            'link',
            'price',
            'brand',
            'availability',
            'image_link',
            'condition',
            'additional_image_link'
        );
    }

    // Return store name with sanitized apostrophe
    private function get_store_name() {
        $name = trim(str_replace(
          "'",
          "\u{2019}",
          html_entity_decode(
            get_bloginfo('name'),
            ENT_QUOTES,
            'UTF-8')));
        if ($name) {
          return $name;
        }
        // Fallback to site url
        $url = get_site_url();
        if ($url) {
          return parse_url($url, PHP_URL_HOST);
        }
        // If site url doesn't exist, fall back to http host.
        if ( isset( $_SERVER['HTTP_HOST'] ) ) {
            return wp_kses_post( $_SERVER['HTTP_HOST'] );
        }

        // If http host doesn't exist, fall back to local host name.
        $url = gethostname();
        return ($url) ? $url : 'A Store Has No Name';
    }
}