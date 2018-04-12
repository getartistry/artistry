<?php
/**
 * Claim Listings Functions
 *
 * @since 1.0.0
 */

/**
 * Is listing claimed.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $listing_id Post ID.
 * @return bool
 */
function case27_paid_listing_is_claimed( $listing_id = null ) {
	$listing = get_post( $listing_id );
	if ( 'job_listing' !== $listing->post_type ) {
		return false;
	}

	// Always claimed if set.
	if ( $listing->_claimed ) {
		return true;
	}

	return false;
}

/**
 * Claim URL
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $listing_id Post ID.
 * @return string|null
 */
function case27_paid_listing_claim_url( $listing_id = null ) {
	$post = get_post( $listing_id );
	$page_id = get_option( 'job_manager_claim_listing_page_id' );
	$page_url = $page_id ? get_permalink( $page_id ) : '';

	// Bail if not set/incorrect.
	if ( 'job_listing' !== $post->post_type || case27_paid_listing_is_claimed( $listing_id ) || ! $page_url || absint( get_current_user_id() ) === absint( $post->post_author ) || $post->_user_package_id ) {
		return '';
	}

	return esc_url( add_query_arg( 'listing_id', $post->ID, $page_url ) );
}

/**
 * Get valid claim statuses
 *
 * @todo: make this filterable and use this in ACF.
 * @since 1.0.0
 *
 * @return array
 */
function case27_paid_listing_claim_get_valid_statuses() {
	$statuses = array(
		'pending'  => esc_html__( 'Pending', 'my-listing' ),
		'approved' => esc_html__( 'Approved', 'my-listing' ),
		'declined' => esc_html__( 'Declined', 'my-listing' ),
	);
	return $statuses;
}

/**
 * Get claim status label.
 *
 * @since 1.0.0
 *
 * @param int $claim_id Post ID.
 * @return string Status label.
 */
function case27_paid_listing_claim_get_status( $claim_id ) {
	$statuses = case27_paid_listing_claim_get_valid_statuses();
	$status = get_post_meta( $claim_id, '_status', true );
	return $status && isset( $statuses[ $status ] ) ? $statuses[ $status ] : $statuses['pending'];
}

/**
 * Get Claim Products.
 *
 * @since 1.0.0
 *
 * @param int $listing_id Post ID.
 * @return array
 */
function case27_paid_listing_claim_get_products( $listing_id ) {
	// Listing.
	$post = $listing_id ? get_post( $listing_id ) : false;

	// Bail if listing not set.
	if ( ! $post || 'job_listing' !== $post->post_type ) {
		return array();
	}

	$claim_product_ids = array();

	// Listing type claim products.
	$listing_type = $post->_case27_listing_type;
	if ( $listing_type ) {
		$listing_type_products = case27_paid_listing_get_listing_type_products( $listing_type );
		if ( $listing_type_products ) {
			foreach ( $listing_type_products as $product_id ) {
				if ( 'yes' === get_post_meta( $product_id, '_use_for_claims', true ) ) {
					$claim_product_ids[] = $product_id;
				}
			}
			return $claim_product_ids;
		}
	}

	// Get all claim products.
	$claim_product_ids = case27_paid_listing_get_products( array(
		'meta_query' => array(
			array(
				'key'     => '_use_for_claims',
				'value'   => 'yes',
				'compare' => '=',
			),
		),
	) );

	return $claim_product_ids;
}

/**
 * Get claim user package.
 *
 * @since 1.0.0
 *
 * @param int $listing_id Post ID.
 * @return array
 */
function case27_paid_listing_claim_get_user_packages( $listing_id ) {
	// Listing.
	$post = $listing_id ? get_post( $listing_id ) : false;

	// Bail if listing not set.
	if ( ! $post || 'job_listing' !== $post->post_type || ! is_user_logged_in() ) {
		return array();
	}

	$args = array(
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => '_use_for_claims',
				'value'   => '1',
				'compare' => '=',
			),
			array(
				'key'     => '_user_id',
				'value'   => get_current_user_id(),
				'compare' => '=',
			),
		),
	);

	// Listing type claim products.
	$listing_type = $post->_case27_listing_type;
	if ( $listing_type ) {
		$listing_type_products = case27_paid_listing_get_listing_type_products( $listing_type );
		if ( $listing_type_products ) {
			$claim_product_ids = array();
			foreach ( $listing_type_products as $product_id ) {
				if ( 'yes' === get_post_meta( $product_id, '_use_for_claims', true ) ) {
					$claim_product_ids[] = $product_id;
				}
			}
			if ( ! $claim_product_ids ) {
				return array();
			}
			$args['meta_query'][] = array(
				'key'     => '_product_id',
				'value'   => $claim_product_ids,
				'compare' => 'IN',
			);
		}
	}

	$packages = case27_paid_listing_get_user_packages( $args );
	return $packages;
}

/**
 * Get claim of a listing and user.
 *
 * @since 1.0.0
 *
 * @param array $args Get Claims Args.
 * @return array
 */
function case27_paid_listing_claim_get_claims( $args = array() ) {
	$defaults = array(
		'listing_id' => false,
		'user_id'    => false,
		'limit'      => -1,
	);
	$args = wp_parse_args( $args, $defaults );

	$query_args = array(
		'post_type'        => 'claim',
		'posts_per_page'   => $args['limit'],
		'post__in'         => array(),
		'suppress_filters' => false,
		'fields'           => 'ids',
	);

	if ( $args['listing_id'] || $args['user_id'] ) {
		$query_args['meta_query'] = array(
			'relation' => 'AND',
		);

		if ( $args['listing_id'] ) {
			$query_args['meta_query'][] = array(
				'key'     => '_listing_id',
				'value'   => absint( $args['listing_id'] ),
				'compare' => '=',
			);
		}
		if ( $args['user_id'] ) {
			$query_args['meta_query'][] = array(
				'key'     => '_user_id',
				'value'   => absint( $args['user_id'] ),
				'compare' => '=',
			);
		}
	}

	return get_posts( $query_args );
}

/**
 * Create New Claim
 *
 * @since 1.0.0
 *
 * @param array $args Create claim args.
 * @return int|false Claim ID on success. False on failure.
 */
function case27_paid_listing_claim_create_claim( $args = array() ) {
	$defaults = array(
		'listing_id'       => false,
		'user_id'          => get_current_user_id(),
		'user_package_id'  => false,
		'status'           => get_option( 'case27_claim_requires_approval', true ) ? 'pending' : 'approved',
	);
	$args = wp_parse_args( $args, $defaults );

	// First, check if required data available.
	if ( ! $args['listing_id'] && ! $args['user_id'] ) {
		return false;
	}

	// Check if claim already exists for this user.
	$claim = case27_paid_listing_claim_get_claims( array(
		'listing_id' => $args['listing_id'],
		'user_id'    => $args['user_id'],
	) );

	// Claim found. Bail. Use it.
	if ( $claim && isset( $claim[0] ) ) {
		return absint( $claim[0] );
	}

	// Create claim.
	$data = array(
		'post_author'  => 0,
		'post_title'   => '',
		'post_type'    => 'claim',
		'post_status'  => 'publish',
	);
	$claim_id = wp_insert_post( $data );

	// Fail to create claim.
	if ( ! $claim_id || is_wp_error( $claim_id ) ) {
		return false;
	}

	// Success. Set claim datas.
	add_post_meta( $claim_id, '_status', $args['status'] );
	add_post_meta( $claim_id, '_listing_id', absint( $args['listing_id'] ) );
	add_post_meta( $claim_id, '_user_id', absint( $args['user_id'] ) );
	add_post_meta( $claim_id, '_user_package_id', absint( $args['user_package_id'] ) );

	// On approved claim.
	if ( 'approved' === $args['status'] ) {
		case27_paid_listing_claim_approve_claim( $claim_id );
	} else { // Send email, approved function already send email.
		case27_paid_listing_claim_send_claim_email( $claim_id );
	}

	return $claim_id;
}

/**
 * Approve a Claim
 *
 * @since 1.0.0
 *
 * @param int $claim_id Claim Post ID.
 */
function case27_paid_listing_claim_approve_claim( $claim_id ) {
	$claim = get_post( $claim_id );
	if ( ! $claim || 'claim' !== $claim->post_type || ! $claim->_listing_id ) {
		return false;
	}

	// Implement user package, and set listing to approved/publish listing.
	if ( $claim->_user_package_id ) {
		case27_paid_listing_use_user_package_to_listing( $claim->_user_package_id, $claim->_listing_id, 'publish' );
	}

	// Update claim status & listing author.
	update_post_meta( absint( $claim->_listing_id ), '_claimed', 1 );

	if ( $claim->_user_id ) {
		wp_update_post( array(
			'ID'            => absint( $claim->_listing_id ),
			'post_author'   => absint( $claim->_user_id ),
		) );
	}

	// Send approved email.
	case27_paid_listing_claim_send_claim_email( $claim_id );
}

/**
 * Claim Info for claimer.
 *
 * @since 1.0.0
 *
 * @param int $claim_id   Claim Post ID.
 * @param int $listing_id Listing Post ID.
 */
function case27_paid_listing_claim_info( $claim_id, $listing_id ) {
	// Check listing.
	$listing = get_post( $listing_id );
	if ( ! $listing || 'job_listing' !== $listing->post_type ) {
		echo '<div class="job-manager-error">' . __( 'Invalid listing.', 'my-listing' ) . '</div>';
		return;
	}

	// Already claimed.
	if ( $listing->_claimed && absint( get_current_user_id() ) !== absint( $listing->post_author ) ) {
		echo '<div class="job-manager-error">' . __( 'Invalid request. Listing already claimed.', 'my-listing' ) . '</div>';
		return;
	}

	// Check claim.
	$claim = get_post( $claim_id );
	if ( ! $claim || 'claim' !== $claim->post_type || absint( $listing_id ) !== absint( $claim->_listing_id ) || absint( get_current_user_id() ) !== absint( $claim->_user_id ) ) {
		echo '<div class="job-manager-error">' . __( 'Invalid claim.', 'my-listing' ) . '</div>';
		return;
	}

	?>
	<fieldset class="fieldset">
		<label><?php _e( 'Listing to claim', 'my-listing' ); ?></label>
		<div class="field">
			<a href="<?php echo esc_url( get_permalink( $listing_id ) ); ?>"><strong><?php echo get_the_title( $listing_id ); ?></strong></a>
			<input type="hidden" value="<?php echo intval( $listing_id ); ?>" name="listing_id">
		</div>
	</fieldset>

	<fieldset>
		<label><?php _e( 'Claimed by', 'my-listing' ); ?></label>
		<div class="field">
			<?php echo get_userdata( $claim->_user_id )->data->display_name; ?>
		</div>
	</fieldset>

	<fieldset>
		<label><?php _e( 'Claim status', 'my-listing' ); ?></label>
		<div class="field">
			<?php echo case27_paid_listing_claim_get_status( $claim_id ); ?>
		</div>
	</fieldset>

	<fieldset>
		<label><?php _e( 'Submitted on', 'my-listing' ); ?></label>
		<div class="field">
			<?php echo get_the_date( get_option( 'date_format' ), $claim_id ); ?>
		</div>
	</fieldset>

	<?php
}

/**
 * Send Status Update Notification to Claimer.
 *
 * @since 1.0.0
 *
 * @param int    $claim_id Post ID.
 * @param string $old_status Old status.
 * @return bool
 */
function case27_paid_listing_claim_send_claim_email( $claim_id ) {
	$claim = get_post( $claim_id );
	if ( ! $claim || 'claim' !== $claim->post_type || ! $claim->_user_id || ! $claim->_listing_id || ! $claim->_status ) {
		return false;
	}

	$claimer = get_userdata( $claim->_user_id );
	$listing = get_post( $claim->_listing_id );
	if ( ! $claimer || ! $listing || 'job_listing' !== $listing->post_type ) {
		return false;
	}

	$statuses = case27_paid_listing_claim_get_valid_statuses();
	$data = array(
		'claimer_email' => sanitize_email( $claimer->data->user_email ),
		'claimer_name'  => $claimer->data->display_name,
		'listing_title' => strip_tags( $listing->post_title ),
		'listing_url'   => get_permalink( $listing->ID ),
		'claim_status'  => isset( $statuses[ $claim->_status ] ) ? $statuses[ $claim->_status ] : $claim->_status,
	);
	$args = array(
		'to'          => '%claimer_email%',
		'subject'     => __( 'Your Claim For "%listing_title%" is %claim_status%', 'my-listing' ),
		'message'     => __(
			'Hi %claimer_name%,' . "\n" .
			"Here's the details about your claim:" . "\n\n" .
			'Listing URL: %listing_url%' . "\n" .
			'Claimed by: %claimer_name%' . "\n" .
			'Claim Status: %claim_status%' . "\n\n" .
			'Thank you.' . "\n"
		, 'my-listing' ),
	);

	// Add data to mail content.
	foreach ( $data as $key => $val ) {
		$args['to']      = str_replace( "%{$key}%", $val, $args['to'] );
		$args['subject'] = str_replace( "%{$key}%", $val, $args['subject'] );
		$args['message'] = str_replace( "%{$key}%", $val, $args['message'] );
	}

	return case27_paid_listing_send_mail( $args, "send_claim_email" );
}
