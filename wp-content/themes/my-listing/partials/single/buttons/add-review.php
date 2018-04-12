<?php
/**
 * Add Review button in listing cover section.
 *
 * @since 1.6.0
 */
?>

<li>
   <a href="#add-review" class="buttons <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?> medium add-review c27-add-listing-review">
       <?php echo do_shortcode( $button['label'] ) ?>
   </a>
</li>
