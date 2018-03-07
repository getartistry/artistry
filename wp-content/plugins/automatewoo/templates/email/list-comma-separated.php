<?php
/**
 * Override this template by copying it to yourtheme/automatewoo/email/list-comma-separated.php
 *
 * @var WC_Product[] $products
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$links = [];

foreach( $products as $product ) {
	$links[] = '<a href="' . $product->get_permalink() .'">' . esc_attr( AutomateWoo\Compat\Product::get_name( $product ) ) . '</a>';
}

echo implode( $links, ', ' );
