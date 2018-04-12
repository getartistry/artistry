<?php do_action( 'case27_woocommerce_promoted_listings_before' ) ?>

<div class="promotion-keys-wrapper clearfix">

<?php
$promotions = CASE27\Integrations\PromotedListings\PromotedListings::instance();

// case27_wc_product => listing_promotion

$promoteListingID = isset($_GET['listing_id']) && $_GET['listing_id'] ? absint( $_GET['listing_id'] ) : false;

// Handle actions.
// if ( isset( $_GET['action'] ) && $_GET['action'] ) {
// 	$promotions->handle_action( sanitize_text_field( $_GET['action'] ) );
// }

// Get all promotion keys.
$keys = $promotions->get_promotion_keys();

if ( ! empty( $keys ) ): ?>

	<table class="c27-table-style-1 shop_table">
		<thead>
			<tr>
				<th><span><?php _e( 'Time left', 'my-listing' ) ?></span></th>
				<th><span><?php _e( 'Applied to', 'my-listing' ) ?></span></th>
				<th><span><?php _e( 'Actions', 'my-listing' ) ?></span></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($keys as $item) :
				$days = $item->get_meta('days');
				$time = $item->get_meta('case27_time_left');
				$listingID = $item->get_meta('case27_listing_promotion_listing_id');
				$daysLeft = ( (int) $promotions->get_time_left( $item ) ) / 86400;
				?>

				<tr class="round-icon">
					<td class="title-style-1">
						<i class="icon-flash"></i>
						<p><?php echo number_format( $daysLeft, 1 ) ?> <?php _e( 'days left', 'my-listing' ) ?></p>
					</td>
					<?php if ( $listingID && ( $appliedTo = get_post( $listingID ) ) ) : ?>
						<td>
							<span title="<?php echo esc_attr( sprintf( __( 'Listing ID: %d', 'my-listing' ), $listingID ) ) ?>">
								<?php echo esc_html( $appliedTo->post_title ) ?>
							</span>
						</td>
						<td>
							<a href="<?php echo esc_url( add_query_arg([
								'listing_id' => $listingID,
								'key_id' => $item->get_id(),
								'action' => 'cancel_promotion',
								], wc_get_endpoint_url( 'promotions' ) ) ) ?>"
								class="woocommerce-button buttons button-5 small"
								><?php _e( 'Cancel Promotion', 'my-listing' ) ?></a>
						</td>
					<?php else: ?>
						<td>
							<?php _e( 'None', 'my-listing' ) ?>
						</td>
						<td>
							<?php if ( $promoteListingID ): ?>
								<a href="<?php echo esc_url( add_query_arg([
									'listing_id' => $promoteListingID,
									'key_id' => $item->get_id(),
									'action' => 'promote_listing',
									], wc_get_endpoint_url( 'promotions' ) ) ) ?>"
									class="woocommerce-button buttons button-5 small"
									><?php _e( 'Use Package', 'my-listing' ) ?></a>
							<?php endif ?>
						</td>
					<?php endif ?>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>

<?php endif ?>

<?php if ( empty( $keys ) ) : ?>
	<h4 class="woocommerce-heading"><?php _e( 'You don\'t have any usable promotion package currently.', 'my-listing' ) ?></h4>
<?php endif ?>

<?php if ( $promoProductID = get_option( 'case27_promotion_product_id', false ) ): ?>
	<div class="col-md-12 text-right">
		<br>
		<a href="<?php echo esc_url( get_permalink( $promoProductID ) ) ?>" class="buttons button-2">
			<i class="mi shopping_basket"></i> <?php _e( 'Buy a Package', 'my-listing' ) ?>
		</a>
	</div>
<?php endif ?>

</div>

<?php

do_action( 'case27_woocommerce_promoted_listings_after' );
