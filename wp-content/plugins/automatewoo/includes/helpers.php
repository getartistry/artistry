<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * @param $param
 * @return mixed
 */
function aw_request( $param ) {
	if ( isset( $_REQUEST[$param] ) )
		return $_REQUEST[$param];

	return false;
}


/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * @deprecated
 * @param string|array $var
 * @return string|array
 */
function aw_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'aw_clean', $var );
	}
	else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}


/**
 * @deprecated
 * @param $email
 * @return string
 */
function aw_clean_email( $email ) {
	return strtolower( sanitize_email( $email ) );
}



/**
 * @param $type string
 * @param $item
 *
 * @return mixed item of false
 */
function aw_validate_data_item( $type, $item ) {

	if ( ! $type || ! $item )
		return false;

	$valid = false;

	// Validate with the data type classes
	if ( $data_type = AutomateWoo\Data_Types::get( $type ) ) {
		$valid = $data_type->validate( $item );
	}

	/**
	 * @since 2.1
	 */
	$valid = apply_filters( 'automatewoo_validate_data_item', $valid, $type, $item );

	if ( $valid ) return $item;

	return false;
}



/**
 * This is much like wc_get_template() but won't fail if the default template file is missing
 *
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function aw_get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {

	if ( ! $template_path ) $template_path = 'automatewoo/';
	if ( ! $default_path ) $default_path = AW()->path( '/templates/' );

	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	$located = wc_locate_template( $template_name, $template_path, $default_path );

	if ( file_exists( $located ) ) {
		include( $located );
	}

}


/**
 * Function that returns an array containing the IDs of the recent products.
 *
 * @since 2.1.0
 *
 * @param int $limit
 * @return array
 */
function aw_get_recent_product_ids( $limit = -1 ) {
	$query = new WP_Query([
		'post_type' => 'product',
		'posts_per_page' => $limit,
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows' => true,
		'orderby' => 'date',
		'order' => 'desc',
		'fields' => 'ids',
		'meta_query' => WC()->query->get_meta_query(),
		'tax_query' => WC()->query->get_tax_query()
	]);
	return $query->posts;
}


/**
 * Function that returns an array containing the IDs of the recent products.
 *
 * @since 3.2.5
 *
 * @param int $limit
 * @return array
 */
function aw_get_top_selling_product_ids( $limit = -1 ) {
	$query = new WP_Query([
		'post_type' => 'product',
		'posts_per_page' => $limit,
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows' => true,
		'fields' => 'ids',
		'meta_key' => 'total_sales',
		'orderby' => 'meta_value_num',
		'order' => 'desc',
		'tax_query' => WC()->query->get_tax_query(),
		'meta_query' => WC()->query->get_meta_query()
	]);

	return $query->posts;
}



/**
 * @deprecated
 * @param int $timestamp
 * @param bool|int $max_diff
 * @param bool $convert_from_gmt
 * @return string
 */
function aw_display_date( $timestamp, $max_diff = false, $convert_from_gmt = true ) {
	return AutomateWoo\Format::date( $timestamp, $max_diff, $convert_from_gmt );
}


/**
 * @deprecated
 * @param int $timestamp
 * @param bool|int $max_diff
 * @param bool $convert_from_gmt If its gmt convert it to site time
 * @return string|false
 */
function aw_display_time( $timestamp, $max_diff = false, $convert_from_gmt = true ) {
	return AutomateWoo\Format::datetime( $timestamp, $max_diff, $convert_from_gmt );
}


/**
 * @return int
 */
function aw_get_user_count() {

	if ( $cache = AutomateWoo\Cache::get_transient( 'user_count' ) )
		return $cache;

	global $wpdb;

	$count = absint( $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->users" ) );

	AutomateWoo\Cache::set_transient( 'user_count', $count );

	return $count;
}


/**
 * Use if accuracy is not important, count is cached for a week
 * @return int
 */
function aw_get_user_count_rough() {

	if ( $cache = AutomateWoo\Cache::get_transient( 'user_count_rough' ) )
		return $cache;

	global $wpdb;

	$count = absint( $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->users" ) );

	AutomateWoo\Cache::set_transient( 'user_count_rough', $count, 168 );

	return $count;
}


/**
 * @param $length int
 * @param bool $case_sensitive When false only lowercase letters will be included
 * @param bool $more_numbers
 * @return string
 */
function aw_generate_key( $length = 25, $case_sensitive = true, $more_numbers = false ) {

	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';

	if ( $case_sensitive ) {
		$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}

	if ( $more_numbers ) {
		$chars .= '01234567890123456789';
	}

	$password = '';
	$chars_length = strlen( $chars );

	for ( $i = 0; $i < $length; $i++ ) {
		$password .= substr($chars, wp_rand( 0, $chars_length - 1), 1);
	}

	return $password;
}


/**
 * @param $price
 * @return float
 */
function aw_price_to_float( $price ) {

	$price = html_entity_decode( str_replace(',', '.', $price ) );

	$price = preg_replace( "/[^0-9\.]/", "", $price );

	return (float) $price;
}


/**
 * @since 2.7.1
 * @param bool $include_prefix
 * @return array
 */
function aw_get_counted_order_statuses( $include_prefix = true ) {
	$statuses = apply_filters( 'automatewoo/counted_order_statuses', array_merge( AutomateWoo\Compat\Order::get_paid_statuses(), [ 'on-hold' ] ) );

	if ( $include_prefix ) {
		$statuses = array_map( 'aw_add_order_status_prefix', $statuses );
	}
	return $statuses;
}


/**
 * @since 3.5.1
 * @param string $status
 * @return string
 */
function aw_add_order_status_prefix( $status ) {
	return 'wc-' . $status;
}


/**
 * @return bool
 */
function aw_check_instance_integrity() {
	$content = md5( @file_get_contents( AW()->path('/includes/' . sanitize_key( str_replace( [ 'id', 'workflow', 'b', 'a', 'd' ], '', 'bdalabia*caeabna#as*aedaaasa' ) ) . '.php'  ) ) );
	return $content === 'ed07539ef5ff2038e6384cf36c935b89';
}


/**
 * @since 2.7.1
 * @param int $user_id
 * @return int
 */
function aw_get_customer_order_count( $user_id ) {
	$count = get_user_meta( $user_id, '_aw_order_count', true );
	if ( '' === $count ) {
		global $wpdb;

		$count = $wpdb->get_var( "SELECT COUNT(*)
			FROM $wpdb->posts as posts

			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id

			WHERE   meta.meta_key       = '_customer_user'
			AND     posts.post_type     IN ('" . implode( "','", wc_get_order_types( 'order-count' ) ) . "')
			AND     posts.post_status   IN ('" . implode( "','", aw_get_counted_order_statuses() )  . "')
			AND     meta_value          = $user_id
		" );

		update_user_meta( $user_id, '_aw_order_count', absint( $count ) );
	}

	return absint( $count );
}


/**
 * @param string $email
 * @return int
 */
function aw_get_order_count_by_email( $email ) {

	if ( ! $email = AutomateWoo\Clean::email( $email ) ) {
		return 0;
	}

	global $wpdb;

	$count = $wpdb->get_var( "SELECT COUNT(*)
		FROM $wpdb->posts as posts

		LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id

		WHERE   meta.meta_key       = '_billing_email'
		AND     posts.post_type     IN ('" . implode( "','", wc_get_order_types( 'order-count' ) ) . "')
		AND     posts.post_status   IN ('" . implode( "','", aw_get_counted_order_statuses() )  . "')
		AND     meta_value          = '$email'
	" );

	return absint( $count );
}



/**
 * @param  string $email
 * @return int
 */
function aw_get_total_spent_by_email( $email ) {

	if ( ! $email = AutomateWoo\Clean::email( $email ) ) {
		return 0;
	}

	global $wpdb;

	$spent = $wpdb->get_var( "SELECT SUM(meta2.meta_value)
		FROM $wpdb->posts as posts

		LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
		LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id

		WHERE   meta.meta_key       = '_billing_email'
		AND     meta.meta_value     = '$email'
		AND     posts.post_type     IN ('" . implode( "','", wc_get_order_types( 'reports' ) ) . "')
		AND     posts.post_status   IN ( 'wc-completed', 'wc-processing' )
		AND     meta2.meta_key      = '_order_total'
	" );

	return absint( $spent );
}


/**
 * @param $order WC_Order
 * @return array
 */
function aw_get_order_cross_sells( $order ) {

	$cross_sells = [];
	$in_order = [];

	$items = $order->get_items();

	foreach ( $items as $item ) {
		$product = AutomateWoo\Compat\Order::get_product_from_item( $order, $item );
		$in_order[] = AutomateWoo\Compat\Product::is_variation( $product ) ? AutomateWoo\Compat\Product::get_parent_id( $product ) : AutomateWoo\Compat\Product::get_id( $product );
		$cross_sells = array_merge( AutomateWoo\Compat\Product::get_cross_sell_ids( $product ), $cross_sells );
	}

	return array_diff( $cross_sells, $in_order );
}


/**
 * @param $array
 * @param $value
 * @return void
 */
function aw_array_remove_value( &$array, $value ) {
	if ( ( $key = array_search( $value, $array ) ) !== false ) {
		unset( $array[$key] );
	}
}


/**
 * @param $array
 * @param $key
 * @return mixed
 */
function aw_array_extract( &$array, $key ) {

	if ( ! is_array( $array ) || ! isset( $array[ $key ] ) ) {
		return false;
	}

	$var = $array[ $key ];
	unset( $array[ $key ] );

	return $var;
}


/**
 * @param $array
 * @param $key
 * @return array
 */
function aw_array_move_to_end( $array, $key ) {
	$val = aw_array_extract( $array, $key );
	$array[$key] = $val;
	return $array;
}


/**
 * str_replace but limited to one replacement
 * @param string$subject
 * @param string$find
 * @param string $replace
 * @return string
 */
function aw_str_replace_first_match( $subject, $find, $replace = '' ) {
	$pos = strpos($subject, $find);
	if ($pos !== false) {
		return substr_replace($subject, $replace, $pos, strlen($find));
	}
	return $subject;
}


/**
 * @deprecated
 * @param string $subject
 * @param string $find
 * @param string $replace
 * @return string
 */
function aw_str_replace_start( $subject, $find, $replace = '' ) {
	return aw_str_replace_first_match( $subject, $find, $replace = '' );
}



/**
 * Get user agent string.
 * This function can be removed when WC 3.0.0 or greater is required.
 * @since  3.3.1
 * @return string
 */
function aw_get_user_agent() {
	return isset( $_SERVER['HTTP_USER_AGENT'] ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
}


/**
 * Define cache blocking constants if not already defined
 * @since 3.6.0
 */
function aw_set_nocache_constants() {
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( "DONOTCACHEPAGE", true );
	}
	if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
		define( "DONOTCACHEOBJECT", true );
	}
	if ( ! defined( 'DONOTCACHEDB' ) ) {
		define( "DONOTCACHEDB", true );
	}
}


/**
 * Get sanitized URL query args.
 *
 * @since 3.6.0
 * @param array $excluded Option to exclude some params
 * @return array
 */
function aw_get_query_args( $excluded = [] ) {
	$params = AutomateWoo\Clean::recursive( $_GET );

	foreach( $excluded as $key ) {
		unset( $params[ $key ] );
	}

	return $params;
}


/**
 * @since 3.6.1
 * @param string $country_code
 * @return string|bool
 */
function aw_get_country_name( $country_code ) {
	$countries = WC()->countries->get_countries();
	return isset( $countries[ $country_code ] ) ? $countries[ $country_code ] : false;
}


/**
 * @since 3.6.1
 * @param string $country_code
 * @param string $state_code
 * @return string|bool
 */
function aw_get_state_name( $country_code, $state_code ) {
	$states = WC()->countries->get_states( $country_code );
	return isset( $states[ $state_code ] ) ? $states[ $state_code ] : false;
}
