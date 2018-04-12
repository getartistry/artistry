<?php
/**
 * My Packages
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="element" style="margin-top: 20px;">
	<div class="pf-head round-icon">
		<div class="title-style-1">
			<i class="material-icons">view_carousel</i>
			<h5><?php _e( 'My Packages', 'my-listing' ) ?></h5>
		</div>
	</div>
	<div class="pf-body">
		<table class="shop_table my_account_job_packages my_account_wc_paid_listing_packages c27-table-style-1">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Package Name', 'my-listing' ); ?></th>
					<th scope="col"><?php _e( 'Remaining', 'my-listing' ); ?></th>
					<?php if ( 'job_listing' === $type ) : ?>
						<th scope="col"><?php _e( 'Listing Duration', 'my-listing' ); ?></th>
					<?php endif; ?>
					<th scope="col"><?php _e( 'Featured?', 'my-listing' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $packages as $package ) :
					$package = wc_paid_listings_get_package( $package );
					?>
					<tr>
						<td><?php echo esc_html( $package->get_title() ) ?></td>
						<td><?php echo $package->get_limit() ? absint( $package->get_limit() - $package->get_count() ) : __( 'Unlimited', 'my-listing' ); ?></td>
						<?php if ( 'job_listing' === $type ) : ?>
							<td><?php echo $package->get_duration() ? sprintf( _n( '%d day', '%d days', $package->get_duration(), 'my-listing' ), $package->get_duration() ) : '-'; ?></td>
						<?php endif; ?>
						<td><?php echo $package->is_featured() ? __( 'Yes', 'my-listing' ) : __( 'No', 'my-listing' ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
