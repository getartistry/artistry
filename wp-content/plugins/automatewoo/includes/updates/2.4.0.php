<?php
/**
 * Update AutomateWoo to 2.4
 *
 * Use tokens instead of ids for carts
 *
 * @version     2.4
 * @package     AutomateWoo/Updates
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $wpdb;

$carts = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'automatewoo_abandoned_carts' . " WHERE token='' " );

if ( $carts ) foreach( $carts as $cart )
{
	$cart = new AutomateWoo\Cart( $cart->id );
	$cart->set_token();
	$cart->save();
}




