<?php
do_action( 'case27_woocommerce_bookmarks_before' );

$_page = isset( $_GET['_page'] ) ? (int) $_GET['_page'] : 1;
$bookmark_ids = get_user_meta( get_current_user_id(), '_case27_user_bookmarks', true ) ? : [];
$endpoint_url = wc_get_endpoint_url( 'my-bookmarks' );

if ( ! $bookmark_ids ) {
	$bookmark_ids = [0];
}

// dump( $bookmark_ids );

$bookmarks = new WP_Query([
	'post_type' => 'job_listing',
	'posts_per_page' => 10,
	'post_status' => 'publish',
	'paged' => $_page,
	'post__in' => $bookmark_ids,
	]);
?>

<?php if ( $bookmarks->have_posts() ) : ?>
	<p><?php _e( 'Your bookmarks are shown in the table below.', 'my-listing' ) ?></p>
	<table class="job-manager-jobs c27-bookmarks-table shop_table">
		<thead>
			<tr>
				<th class="bookmark-photo"><i class="mi photo"></i></th>
				<th class="bookmark-title"><?php _e( 'Name', 'my-listing' ) ?></th>
				<th class="bookmark-actions"><?php _e( 'Actions', 'my-listing' ) ?></th>
			</tr>
		</thead>
		<tbody>
			<?php while ($bookmarks->have_posts()): $bookmarks->the_post(); ?>
				<tr>
					<td class="bookmark-photo"><?php echo do_action('job_manager_job_dashboard_column_c27_listing_logo', get_post()) ?></td>
					<td class="bookmark-title">
						<h5>
							<a href="<?php echo esc_url( get_permalink() ); ?>">
								<?php echo esc_html( get_the_title() ) ?>
							</a>
						</h5>
					</td>
					<td class="bookmark-actions">
						<a href="<?php echo esc_url( get_permalink() ) ?>" class="woocommerce-button buttons button-5 small"><?php _e( 'View Listing', 'my-listing' ) ?></a>
						<a href="<?php echo esc_url( add_query_arg( [ 'listing_id' => get_the_ID(), 'action' => 'remove_bookmark' ], $endpoint_url ) ) ?>"
						   class="woocommerce-button buttons button-5 small"><?php _e( 'Remove Bookmark', 'my-listing' ) ?></a>
					</td>
				</tr>
			<?php endwhile ?>
		</tbody>
	</table>


	<div class="pagination center-button">
		<?php echo paginate_links([
			'format'  => '?_page=%#%',
			'current' => $_page,
			'total'   => $bookmarks->max_num_pages,
			]);
			wp_reset_postdata(); ?>
	</div>
<?php else: ?>
	<?php _e( 'No bookmarks yet.', 'my-listing' ) ?>
<?php endif ?>

<?php do_action( 'case27_woocommerce_bookmarks_after' );