<?php
/**
 * Empty template for single-listing page's cover section.
 *
 * @since 1.6.0
 */

// Overlay options.
$overlay_opacity = c27()->get_setting( 'single_listing_cover_overlay_opacity', '0.5' );
$overlay_color   = c27()->get_setting( 'single_listing_cover_overlay_color', '#242429' );
?>

<section class="featured-section profile-cover profile-cover-no-img">
    <div class="overlay"
         style="background-color: <?php echo esc_attr( $overlay_color ); ?>;
                opacity: <?php echo esc_attr( $overlay_opacity ); ?>;"
        >
    </div>
<!-- Omit the closing </section> tag -->