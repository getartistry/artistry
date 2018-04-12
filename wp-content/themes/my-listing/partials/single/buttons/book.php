<?php
/**
 * Book button in listing cover section.
 *
 * @since 1.6.0
 */
?>

<li>
    <a href="#book-now" class="buttons <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?> medium book-now c27-book-now">
        <?php echo do_shortcode($button['label']) ?>
    </a>
</li>