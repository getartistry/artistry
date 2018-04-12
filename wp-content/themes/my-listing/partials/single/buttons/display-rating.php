<?php
/**
 * Template to display listing rating as a cover button.
 *
 * @since 1.6.0
 */

if ( ! ( $rating = MyListing\Reviews::get_listing_rating_optimized( $listing->get_id() ) ) ) {
    return false;
}
?>

<li>
    <div class="inside-rating listing-rating <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?>">
        <span class="value"><?php echo esc_html( $rating ) ?></span>
        <sup class="out-of">/<?php echo MyListing\Reviews::max_rating( $listing->get_id() ); ?></sup>
    </div>
</li>