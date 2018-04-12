<?php
$listingID = $post->_report_listing_id;
$userID = $post->_report_user_id;
$reportContent = $post->_report_content;

$listing = $listingID ? get_post( $listingID ) : false;
$user = get_user_by( 'id', $userID );
?>

<div class="listing-report">
	<div class="row reported-listing">
		<span class="label"><?php _e( 'Reported Listing', 'my-listing' ) ?></span>
		<span class="value">
			<?php if ( $listing ): ?>
				<a href="<?php echo esc_url( get_permalink( $listing->ID ) ) ?>" target="_blank"><?php echo esc_html( $listing->post_title )  ?></a>
			<?php else: ?>
				<em><?php _e( 'This listing does not exist.', 'my-listing' ) ?></em>
			<?php endif ?>
		</span>
	</div>

	<div class="row report-reason">
		<span class="label"><?php _e( 'Reason', 'my-listing' ) ?></span>
		<span class="value"><?php echo nl2br( esc_html( $reportContent ) ) ?><?php echo ! $reportContent ? '<em>&mdash;</em>' : '' ?></span>
	</div>

	<div class="row reported-by">
		<span class="label"><?php _e( 'Reported By', 'my-listing' ) ?></span>
		<span class="value">
			<?php if ( $user ): ?>
				<a href="<?php echo esc_url( get_author_posts_url( $user->ID ) ) ?>" target="_blank">
					<?php echo $user->data->display_name ?>
				</a>
			<?php else: ?>
				<em><?php _e( 'This account does not exist.', 'my-listing' ) ?></em>
			<?php endif ?>
		</span>
	</div>

	<div class="row report-actions">
		<?php if ( $listing ): ?>
			<a href="<?php echo esc_url( get_permalink( $listing->ID ) ) ?>" target="_blank" class="button button-primary button-large"><?php _e( 'Review Listing', 'my-listing' ) ?></a>
		<?php endif ?>
		<a href="<?php echo esc_url( get_delete_post_link( $post->ID ) ) ?>" class="button button-large"><?php _e( 'Close this report', 'my-listing' ) ?></a>
	</div>
</div>
