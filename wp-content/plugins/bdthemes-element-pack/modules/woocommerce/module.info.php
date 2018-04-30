<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$woocommerce_exist = function_exists( 'WC' );

return [
	'title' => esc_html__( 'WooCommerce', 'bdthemes-element-pack' ),
	'required' => $woocommerce_exist,
	'default_activation' => $woocommerce_exist,
];
