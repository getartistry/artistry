<?php
/**
 * Template to display bookmark button in the listing cover section.
 *
 * @since 1.6.0
 */

$button['classes'][] = 'c27-bookmark-button';

if ( mylisting()->bookmarks()->is_bookmarked( $listing->get_id(), get_current_user_id() ) ) {
	$button['classes'][] = 'bookmarked';
}
?>

<li>
    <a
    	href="#"
    	data-listing-id="<?php echo esc_attr( $listing->get_id() ) ?>"
    	data-nonce="<?php echo esc_attr( wp_create_nonce( 'c27_bookmark_nonce' ) ) ?>"
        class="buttons medium bookmark <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?>">
        <?php echo do_shortcode($button['label']) ?>
    </a>
</li>