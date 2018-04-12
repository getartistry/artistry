<?php

namespace MyListing;

use \MyListing\Reviews as Reviews;

/**
 * Review Admin
 *
 * @author C27
 * @since 1.5.0
 * @category Integrations
 */

/**
 * Rating Reviews Admin
 * Manage review via Comment Edit Screen
 *
 * @since 1.5.0
 */
class ReviewsAdmin {

	/**
	 * Returns the instance.
	 *
	 * @since 1.5.0
	 *
	 * @return Reviews
	 */
	public static function get_instance() {
		static $instance = null;
		if ( is_null( $instance ) ) {
			$instance = new self;
		}
		return $instance;
	}

	/**
	 * Class Constructor
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		// Add review comment meta box.
		add_action( 'add_meta_boxes_comment', array( $this, 'add_comment_review_meta_box' ) );

		// Save meta box data.
		add_action( 'edit_comment', array( $this, 'save_comment_review_meta_box' ), 10, 2 );

		// Meta box script.
		// add_action( 'admin_enqueue_scripts', array( $this, 'comment_review_meta_box_scripts' ) );
	}


	/**
	 * Add Review Meta Box.
	 *
	 * @since 1.5.0
	 *
	 * @param object $comment Comment object.
	 */
	public function add_comment_review_meta_box( $comment ) {
		// Check user caps.
		if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) ) {
			return;
		}

		// Only in job listing comments.
		if ( 'job_listing' !== get_post_type( $comment->comment_post_ID ) ) {
			return;
		}

		// Bail if not top level comment.
		if ( 0 !== intval( $comment->comment_parent ) ) {
			return;
		}

		// @todo: need to check if rating enabled in comment_post_ID.

		// Add meta box review.
		add_meta_box(
			$id         = '_case27_review',
			$title      = esc_html__( 'Listing Review', 'my-listing' ),
			$callback   = array( $this, 'review_meta_box_output' ),
			$screen     = 'comment',
			$context    = 'normal' // Only "normal" is valid for comment.
		);
	}

	/**
	 * Review Meta Box HTML Callback
	 *
	 * @since 1.5.0
	 *
	 * @param object $comment Comment Object.
	 * @param array  $box     Meta Box data.
	 */
	public function review_meta_box_output( $comment, $box ) {
		$post_id = $comment->comment_post_ID;
		$categories = Reviews::get_review_categories( $post_id );
		if ( ! $categories ) {
			return;
		}
		$rating_avg = Reviews::get_rating( $comment->comment_ID );
		$ratings = get_comment_meta( $comment->comment_ID, '_case27_ratings', true );
		$ratings = is_array( $ratings ) ? $ratings : array();
		$max_rating = Reviews::max_rating( $post_id );

		$rating_options = array(
			'0' => 0,
			'1' => 1,
			'2' => 2,
			'3' => 3,
			'4' => 4,
			'5' => 5,
			'6' => 6,
			'7' => 7,
			'8' => 8,
			'9' => 9,
			'10' => 10,
		);
		if ( 5 === $max_rating ) {
			$rating_options = array(
				'0' => 0,
				'1' => 2,
				'2' => 4,
				'3' => 6,
				'4' => 8,
				'5' => 10,
			);
		}
		?>
		<table class="form-table c27-edit-comment-review">
			<tbody>
				<tr>
					<th scope="row"><label for="rating-avg"><?php echo esc_html_e( 'Rating Average', 'my-listing' ); ?></label></th>
					<td>
						<p><strong><?php echo $rating_avg; ?> / <?php echo $max_rating; ?></strong></p>
					</td>
				</tr>
				<?php foreach ( $categories as $rating => $category ) : ?>
					<?php
					$value = isset( $ratings[ $category['id'] ] ) ? Reviews::sanitize_rating( $ratings[ $category['id'] ] ) : 0;
					$value = 5 === $max_rating ? round( $value / 2 ) : $value;
					?>
					<tr>
						<th scope="row"><label for="rating-<?php echo esc_attr( $rating ); ?>"><?php echo esc_html( $category['label'] ); ?></label></th>
						<td>
							<select id="rating-<?php echo esc_attr( $rating ); ?>" name="<?php echo esc_attr( $rating ); ?>_star_rating" autocomplete="off">
								<?php foreach( $rating_options as $k => $v ) : ?>
									<option value="<?php echo $v; ?>" <?php selected( $value, $k ); ?>>
										<?php echo $k; ?> - <?php echo str_repeat( '&#9733; ', $k ); ?><?php echo str_repeat( '&#9734; ', absint( $max_rating - $k ) ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				<?php endforeach; ?>

				<?php if ( Reviews::is_review_gallery_enabled( $post_id ) ) : ?>
					<tr>
						<th scope="row"><?php esc_html_e( 'Upload Images', 'my-listing' ); ?></th>
						<td>
							<p><input id="review-gallery-add-input" class="review-gallery-input c27-comment-gallery-input" name="review_gallery[]" multiple="multiple" type="file"></p>
						</td>
					</tr>
					<?php
					$gallery = get_comment_meta( $comment->comment_ID, '_case27_review_gallery', false );
					?>
					<?php if ( $gallery && is_array( $gallery ) ) : ?>
					<tr>
						<th scope="row"><?php esc_html_e( 'Current Gallery', 'my-listing' ); ?></th>
						<td>
							<?php foreach ( $gallery as $attachment_id ) : ?>
								<div class="review-gallery-image">
									<?php echo wp_get_attachment_image( $attachment_id ); ?>
									<input type="hidden" name="review_gallery_ids[]" value="<?php echo esc_attr( $attachment_id ); ?>">
									<a class="review-gallery-image-remove" href="#"><i class="material-icons">close</i></a>
								</div><!-- .review-gallery-image -->
							<?php endforeach; ?>
						</td>
					</tr>
					<?php endif; ?>
				<?php endif; ?>

			</tbody>
		</table>
		<?php wp_nonce_field( 'case27_comment', '_case27_review_comment_nonce' ); ?>
		<?php
	}

	/**
	 * Save Comment Review Meta Box
	 *
	 * @since 1.5.0
	 * @link https://developer.wordpress.org/reference/hooks/comment_edit_redirect/
	 *
	 * @param int    $comment_id Comment ID.
	 * @param array  $data       Comment data.
	 */
	public function save_comment_review_meta_box( $comment_id, $data ) {
		// Check user caps & parent comment.
		if ( ! current_user_can( 'edit_comment', $comment_id ) || $data['comment_parent'] ) {
			return $comment_id;
		}

		// Check nonce.
		if ( ! isset( $_POST['_case27_review_comment_nonce'] ) || ! wp_verify_nonce( $_POST['_case27_review_comment_nonce'], 'case27_comment' ) ) {
			return $comment_id;
		}

		$listing_id = $data['comment_post_ID'];

		// Ratings.
		$categories = Reviews::get_review_categories( $listing_id );
		$ratings = array();
		$ratings_total = 0;
		if ( $categories ) {
			foreach( $categories as $id => $category ) {
				$submitted_rating = Reviews::sanitize_rating( isset( $_POST[ $category['id'] . '_star_rating' ] ) ? intval( $_POST[ $category['id'] . '_star_rating' ] ) : 0 );
				if ( $submitted_rating ) {
					$ratings[ $category['id'] ] = $submitted_rating;
					$ratings_total += $submitted_rating;
				}
			}
		}
		if ( $ratings ) {
			update_comment_meta( $comment_id, '_case27_ratings', $ratings );
			update_comment_meta( $comment_id, '_case27_post_rating', Reviews::sanitize_rating( $ratings_total / count( $ratings ) ) );
		} else {
			delete_comment_meta( $comment_id, '_case27_ratings' );
			delete_comment_meta( $comment_id, '_case27_post_rating' );
		}
		update_post_meta( $listing_id, '_case27_average_rating', Reviews::get_listing_rating( $listing_id ) );

		// Uploaded image action.
		$gallery = get_comment_meta( $comment_id, '_case27_review_gallery', false );
		if ( $gallery && is_array( $gallery ) ) {

			// Re-index gallery.
			delete_comment_meta( $comment_id, '_case27_review_gallery' ); // Delete everything.
			$image_ids = isset( $_POST['review_gallery_ids'] ) && is_array( $_POST['review_gallery_ids'] ) ? $_POST['review_gallery_ids'] : array();
			if ( $image_ids ) {
				foreach( $image_ids as $id ) {
					add_comment_meta( $comment_id, '_case27_review_gallery', $id, false );
				}
			}

			// New gallery index.
			$new_gallery = get_comment_meta( $comment_id, '_case27_review_gallery', false );

			// Delete all attachment not in new index.
			foreach ( $gallery as $attachment_id ) {
				if ( ! in_array( $attachment_id, $new_gallery ) ) {

					// Check if the attachment is from this comment.
					$attachment_comment_id = intval( get_post_meta( $attachment_id, '_case27_review_gallery', true ) );
					if ( $attachment_comment_id === $comment_id ) {
						wp_delete_attachment( $attachment_id, true );
					}
				}
			}
		}

		// Gallery Upload.
		Reviews::handle_uploads( $listing_id, $comment_id );
	}

	/**
	 * Meta Box Scripts
	 *
	 * @since 1.5.0
	 *
	 * @param string $hook_suffix Page context.
	 */
	public function comment_review_meta_box_scripts( $hook_suffix ) {
		if ( 'comment.php' !== $hook_suffix ) {
			return;
		}
		// $version = time();

		// wp_enqueue_script( 'c27_comment_review_meta_box', get_template_directory_uri() . '/assets/scripts/admin/comment-review-meta-box.js', array( 'jquery' ), $version, true );
		// wp_enqueue_style( 'c27_comment_review_meta_box', get_template_directory_uri() . '/assets/styles/admin/comment-review-meta-box.css', array(), $version );
	}

}

// Load Class.
ReviewsAdmin::get_instance();
