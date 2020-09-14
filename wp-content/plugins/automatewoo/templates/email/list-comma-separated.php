<?php
// phpcs:ignoreFile
/**
 * Override this template by copying it to yourtheme/automatewoo/email/list-comma-separated.php
 *
 * @see https://automatewoo.com/docs/email/product-display-templates/
 *
 * @var WC_Product[] $products
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$links = [];

foreach( $products as $product ) {
	$links[] = '<a href="' . esc_url( $product->get_permalink() ) .'">' . esc_attr( $product->get_name() ) . '</a>';
}

echo implode( ', ', $links );
