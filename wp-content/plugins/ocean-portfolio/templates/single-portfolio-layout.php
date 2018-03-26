<?php
/**
 * Single portfolio layout
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article id="portfolio-<?php the_ID(); ?>">

	<?php
	// Get elements
	$elements = op_portfolio_single_elements_positioning();

	// Loop through elements
	foreach ( $elements as $element ) {

		// Featured Image
		if ( 'featured_image' == $element
			&& ! post_password_required() ) {

			$media_file = get_stylesheet_directory() . '/templates/single-portfolio-media.php';
			if ( file_exists( $media_file ) ) {
				$media_path = $media_file;
			} else {
                $media_path = OP_PATH . '/templates/single-portfolio-media.php';
            }
        	include( $media_path );

		}

		// Title
		if ( 'title' == $element ) {

			$title_file = get_stylesheet_directory() . '/templates/single-portfolio-title.php';
			if ( file_exists( $title_file ) ) {
				$title_path = $title_file;
			} else {
                $title_path = OP_PATH . '/templates/single-portfolio-title.php';
            }
        	include( $title_path );

		}

		// Meta
		if ( 'meta' == $element ) {

			$meta_file = get_stylesheet_directory() . '/templates/single-portfolio-meta.php';
			if ( file_exists( $meta_file ) ) {
				$meta_path = $meta_file;
			} else {
                $meta_path = OP_PATH . '/templates/single-portfolio-meta.php';
            }
        	include( $meta_path );

		}

		// Content
		if ( 'content' == $element ) {

			$content_file = get_stylesheet_directory() . '/templates/single-portfolio-content.php';
			if ( file_exists( $content_file ) ) {
				$content_path = $content_file;
			} else {
                $content_path = OP_PATH . '/templates/single-portfolio-content.php';
            }
        	include( $content_path );

		}

		// Tags
		if ( 'tags' == $element ) {

			$tags_file = get_stylesheet_directory() . '/templates/single-portfolio-tags.php';
			if ( file_exists( $tags_file ) ) {
				$tags_path = $tags_file;
			} else {
                $tags_path = OP_PATH . '/templates/single-portfolio-tags.php';
            }
        	include( $tags_path );

		}

		// Social Share
		if ( 'social_share' == $element
			&& OCEAN_EXTRA_ACTIVE ) {

			do_action( 'ocean_social_share' );

		}

		// Next/Prev
		if ( 'next_prev' == $element ) {

			$nav_file = get_stylesheet_directory() . '/templates/single-portfolio-next-prev.php';
			if ( file_exists( $nav_file ) ) {
				$nav_path = $nav_file;
			} else {
                $nav_path = OP_PATH . '/templates/single-portfolio-next-prev.php';
            }
        	include( $nav_path );

		}

		// Related portfolio
		if ( 'related_portfolio' == $element ) {

			$related_file = get_stylesheet_directory() . '/templates/single-portfolio-related.php';
			if ( file_exists( $related_file ) ) {
				$related_path = $related_file;
			} else {
                $related_path = OP_PATH . '/templates/single-portfolio-related.php';
            }
        	include( $related_path );

		}

		// Comments
		if ( 'single_comments' == $element ) {

			comments_template();

		}

	} ?>

</article>