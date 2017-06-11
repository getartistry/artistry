<?php
/*
 * Functions for WooCommerce Stock Status on Archive Pages.
 */


//* Enqueue scripts
add_action( 'wp_enqueue_scripts', 'clip_wc_stock_status_archive', 11 );
function clip_wc_stock_status_archive() {

	// Activate clip styles
	wp_enqueue_style(  'clip-wc-stock-status-archive-style', 
						plugins_url( 'clip-style.css', __FILE__ ), array(),
						'1.0.0' 
	);
}

//* Add stock status to archive pages
add_action( 'woocommerce_after_shop_loop_item_title', 'clip_add_wc_stock_status_archive' );
function clip_add_wc_stock_status_archive() {

    global $product;
	$availability = $append = null;

	// Add status for single products
	if( $product->is_type( 'simple' ) ) {

		$availability = $product->get_availability();
		$class = $availability[ 'class' ];
		$output = $availability[ 'availability' ];
	}

	// Add status for variable products
	elseif( $product->is_type( 'variable' ) ) {

		$status = array();

		// Get status class for each variation
		foreach ( $product->get_children() as $child_id ) {
			
				$variation = $product->get_child( $child_id );
				$availability = $variation->get_availability();
				
				// Abandon if stock management is disabled on any variation
				if( ! array_filter( $availability ) )
					return;

				$status[] = $availability[ 'class' ];
		}

		/**
		 * Compile final output and class based on
		 * availability classes set by WooCommerce
		 */
		if( in_array( 'in-stock', $status ) ) {
			$output = __( 'In stock', 'wp-clips' );
			$class = 'in-stock';
		}
		elseif( in_array( 'available-on-backorder', $status ) ) {
			$output = __( 'Available on backorder', 'wp-clips' );
			$class = 'available-on-backorder';
		}
		elseif( in_array( 'out-of-stock', $status ) ) {
			$output = __( 'Out of stock', 'wp-clips' );
			$class = 'out-of-stock';
		}

		// Append output if some items out of stock or available on backorder
		if( ( in_array( 'available-on-backorder', $status ) && $class == 'in-stock' ) ||
			( in_array( 'out-of-stock', $status ) && $class != 'out-of-stock' ) )
			$append = ' ' . __( '(some items)', 'wp-clips' );
	}

	// Output only if set 
	if( isset( $availability ) )
		echo '<p class="stock ' . esc_attr( $class ) . '">' . esc_html( $output ) . esc_html( $append ) . '</p>';		
}
