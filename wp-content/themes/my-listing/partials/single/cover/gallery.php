<?php
/**
 * Gallery template for single-listing page's cover section.
 *
 * @since 1.6.0
 */

// If there are no gallery images, check if there's a cover image, or a default cover image available.
// Use the empty template if listing gallery isn't available.
if ( ! ( $gallery = $listing->get_field( 'gallery' ) ) ) {
    return require locate_template( 'partials/single/cover/image.php' );
}

// Overlay options.
$overlay_opacity = c27()->get_setting( 'single_listing_cover_overlay_opacity', '0.5' );
$overlay_color   = c27()->get_setting( 'single_listing_cover_overlay_color', '#242429' );
$image_size      = count( $gallery ) === 1 ? 'full' : 'large';
?>

<section class="featured-section profile-cover featured-section-gallery">
    <div class="header-gallery-carousel owl-carousel zoom-gallery">
        <?php foreach ( $gallery as $gallery_image ): ?>
        	<?php if ( $image = job_manager_get_resized_image( $gallery_image, $image_size ) ): ?>

        		<a class="item"
        			href="<?php echo esc_url( job_manager_get_resized_image( $gallery_image, 'full' ) ? : $image ) ?>"
        			style="background-image: url(<?php echo esc_url( $image ) ?>);"
        			>
        			<div class="overlay"
        				 style="background-color: <?php echo esc_attr( $overlay_color ); ?>;
                        		opacity: <?php echo esc_attr( $overlay_opacity ); ?>;"
                        >
                    </div>
        		</a>

        	<?php endif ?>
        <?php endforeach ?>
    </div>
<!-- Omit the closing </section> tag -->