<?php
/**
 * Update AutomateWoo to 2.3
 *
 * Migrates guest info from abandoned cart table to separate guest table
 *
 * @version     2.3
 * @package     AutomateWoo/Updates
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $wpdb;

$carts = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'automatewoo_abandoned_carts' . " WHERE user_id=0 AND guest_id=0" );

if ( $carts ) foreach( $carts as $cart ) {

	$guest = new AutomateWoo\Guest();
	$guest->set_email( $cart->visitor_email );
	$guest->tracking_key = $cart->visitor_key;
	$guest->created = current_time( 'mysql', true );
	$guest->last_active = current_time( 'mysql', true );
	$guest->save();

	if ( $guest->exists ) {
		$cart = new AutomateWoo\Cart( $cart->id );
		$cart->set_guest_id( $guest->id );
		$cart->save();
	}
}


$wpdb->query("ALTER TABLE " . $wpdb->prefix . 'automatewoo_abandoned_carts' . " DROP COLUMN `visitor_email` " );
$wpdb->query("ALTER TABLE " . $wpdb->prefix . 'automatewoo_abandoned_carts' . " DROP COLUMN `visitor_key` " );



