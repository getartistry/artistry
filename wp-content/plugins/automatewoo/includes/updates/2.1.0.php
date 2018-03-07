<?php
/**
 * Update AutomateWoo to 2.1.0
 *
 * Migrates from custom post types for Logs, Queue, Unsubscribes to custom tables.
 *
 * @version     2.1.0
 * @package     AutomateWoo/Updates
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $wpdb;

// get next abandoned cart id
$cart = new AutomateWoo\Cart();
$cart->visitor_email = 'test';
$cart->save();
$increment = absint($cart->id);

// changes to abandoned cart delete and re-add
$wpdb->query("DROP TABLE " . $wpdb->prefix . 'automatewoo_abandoned_carts' );

AutomateWoo\Installer::install();

// update increment
$wpdb->query("ALTER TABLE " . $wpdb->prefix . 'automatewoo_abandoned_carts' . " AUTO_INCREMENT = $increment" );



// migrate unsubscribes
$unsubscribes = get_posts(array(
	'post_type' => 'aw_unsubscribe',
	'posts_per_page' => -1,
));

if( $unsubscribes )
{
	foreach( $unsubscribes as $unsubscribe_post )
	{
		$new = new AutomateWoo\Unsubscribe();
		$new->user_id = get_post_meta( $unsubscribe_post->ID,'user_id', true );
		$new->workflow_id = get_post_meta( $unsubscribe_post->ID, 'workflow_id', true );
		$new->date = get_post_meta( $unsubscribe_post->post_date, 'date', true );
		$new->save();
		wp_delete_post($unsubscribe_post->ID);
	}
}



// migrate queue
$queue = get_posts(array(
	'post_type' => 'aw_queue',
	'posts_per_page' => -1,
));

if( $queue )
{
	foreach( $queue as $queued_event )
	{
		$new = new AutomateWoo\Queued_Event();
		$new->set_workflow_id( get_post_meta( $queued_event->ID, 'workflow_id', true ) );
		$new->data_items = get_post_meta( $queued_event->ID, 'data_items', true );
		$new->set_date_due( get_post_meta( $queued_event->ID, 'date', true ) );
		$new->set_failed( get_post_meta( $queued_event->ID, '_failed', true ) );
		$new->save();
		wp_delete_post($queued_event->ID);
	}
}



// migrate logs
$logs = get_posts(array(
	'post_type' => 'aw_log',
	'posts_per_page' => -1,
));


if ( $logs ) {
	foreach( $logs as $log ) {
		$new = new AutomateWoo\Log();
		$new->set_workflow_id( get_post_meta( $log->ID, 'workflow_id', true ) );
		$new->set_date( $log->post_date );
		$new->set_tracking_enabled( get_post_meta( $log->ID, 'conversion_tracking_enabled', true ) );
		$new->set_conversion_tracking_enabled( get_post_meta( $log->ID, 'tracking_enabled', true ) );

		$new->save();

		$fields = array( 'order_id', 'guest_email', 'user_id', 'category_id', 'tag_id',
			'wishlist_id', 'cart_id', 'order_item_id', 'tracking_data' );

		foreach ( $fields as $field )
		{
			if ( $value = get_post_meta( $log->ID, $field, true ) )
			{
				$new->add_meta( $field, $value );
			}
		}

		wp_delete_post($log->ID);
	}
}