<?php
/**
 * Add some functions to make AutomateWoo compatible with older versions of woocommerce + WP
 */


// WC 2.3.4
if ( ! function_exists( 'wc_get_page_permalink' ) )
{
	function wc_get_page_permalink( $page ) {
		$permalink = get_permalink( wc_get_page_id( $page ) );

		return apply_filters( 'woocommerce_get_' . $page . '_page_permalink', $permalink );
	}
}



/**
 * Get the URL of an image attachment.
 *
 * @since 4.4.0
 *
 * @param int          $attachment_id Image attachment ID.
 * @param string|array $size          Optional. Image size to retrieve. Accepts any valid image size, or an array
 *                                    of width and height values in pixels (in that order). Default 'thumbnail'.
 * @param bool         $icon          Optional. Whether the image should be treated as an icon. Default false.
 * @return string|false Attachment URL or false if no image is available.
 */
if ( ! function_exists( 'wp_get_attachment_image_url' ) )
{
	function wp_get_attachment_image_url($attachment_id, $size = 'thumbnail', $icon = false)
	{
		$image = wp_get_attachment_image_src($attachment_id, $size, $icon);
		return isset($image['0']) ? $image['0'] : false;
	}
}



// WC 2.6
if ( ! function_exists( 'wc_get_orders' ) ) {
	function wc_get_orders($args)
	{
		$args = wp_parse_args($args, array(
			'status' => array_keys(wc_get_order_statuses()),
			'type' => wc_get_order_types('view-orders'),
			'parent' => null,
			'customer' => null,
			'email' => '',
			'limit' => get_option('posts_per_page'),
			'offset' => null,
			'page' => 1,
			'exclude' => array(),
			'orderby' => 'date',
			'order' => 'DESC',
			'return' => 'objects',
			'paginate' => false,
		));

		// Handle some BW compatibility arg names where wp_query args differ in naming.
		$map_legacy = array(
			'numberposts' => 'limit',
			'post_type' => 'type',
			'post_status' => 'status',
			'post_parent' => 'parent',
			'author' => 'customer',
			'posts_per_page' => 'limit',
			'paged' => 'page',
		);

		foreach ($map_legacy as $from => $to) {
			if (isset($args[$from])) {
				$args[$to] = $args[$from];
			}
		}

		/**
		 * Generate WP_Query args. This logic will change if orders are moved to
		 * custom tables in the future.
		 */
		$wp_query_args = array(
			'post_type' => $args['type'] ? $args['type'] : 'shop_order',
			'post_status' => $args['status'],
			'posts_per_page' => $args['limit'],
			'meta_query' => array(),
			'fields' => 'ids',
			'orderby' => $args['orderby'],
			'order' => $args['order'],
		);

		if (!is_null($args['parent'])) {
			$wp_query_args['post_parent'] = absint($args['parent']);
		}

		if (!is_null($args['offset'])) {
			$wp_query_args['offset'] = absint($args['offset']);
		} else {
			$wp_query_args['paged'] = absint($args['page']);
		}

		if (!empty($args['customer'])) {
			$values = is_array($args['customer']) ? $args['customer'] : array($args['customer']);
			$wp_query_args['meta_query'][] = _wc_get_orders_generate_customer_meta_query($values);
		}

		if (!empty($args['exclude'])) {
			$wp_query_args['post__not_in'] = array_map('absint', $args['exclude']);
		}

		if (!$args['paginate']) {
			$wp_query_args['no_found_rows'] = true;
		}

		// Get results.
		$orders = new WP_Query($wp_query_args);

		if ('objects' === $args['return']) {
			$return = array_map('wc_get_order', $orders->posts);
		} else {
			$return = $orders->posts;
		}

		if ($args['paginate']) {
			return (object)array(
				'orders' => $return,
				'total' => $orders->found_posts,
				'max_num_pages' => $orders->max_num_pages,
			);
		} else {
			return $return;
		}
	}
}


// WC 2.6
if ( ! function_exists( '_wc_get_orders_generate_customer_meta_query' ) ) {
	function _wc_get_orders_generate_customer_meta_query( $values, $relation = 'or' ) {
		$meta_query = array(
			'relation' => strtoupper( $relation ),
			'customer_emails' => array(
				'key'     => '_billing_email',
				'value'   => array(),
				'compare' => 'IN',
			),
			'customer_ids' => array(
				'key'     => '_customer_user',
				'value'   => array(),
				'compare' => 'IN',
			)
		);
		foreach ( $values as $value ) {
			if ( is_array( $value ) ) {
				$meta_query[] = _wc_get_orders_generate_customer_meta_query( $value, 'and' );
			} elseif ( is_email( $value ) ) {
				$meta_query['customer_emails']['value'][] = sanitize_email( $value );
			} else {
				$meta_query['customer_ids']['value'][] = strval( absint( $value ) );
			}
		}

		if ( empty( $meta_query['customer_emails']['value'] ) ) {
			unset( $meta_query['customer_emails'] );
			unset( $meta_query['relation'] );
		}

		if ( empty( $meta_query['customer_ids']['value'] ) ) {
			unset( $meta_query['customer_ids'] );
			unset( $meta_query['relation'] );
		}

		return $meta_query;
	}
}
