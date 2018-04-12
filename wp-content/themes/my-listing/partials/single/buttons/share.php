<?php
/**
 * Share button in listing cover section.
 *
 * @since 1.6.0
 */

$links = mylisting()->sharer()->get_links( [
    'permalink' => $listing->get_link(),
    'image' => $listing->get_logo( 'large' ),
    'title' => get_the_title(),
    'description' => $listing->get_field( 'job_description' ),
] );

if ( ! $links ) {
	return false;
}
?>

<li class="dropdown">
    <a href="#" class="buttons <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?> medium show-dropdown sn-share" type="button" id="<?php echo esc_attr( $button['id'] ) ?>" data-toggle="dropdown">
        <?php echo do_shortcode( $button['label'] ) ?>
    </a>
    <ul class="i-dropdown share-options dropdown-menu" aria-labelledby="<?php echo esc_attr( $button['id'] ) ?>">
        <?php foreach ( $links as $link ): ?>
            <li><?php mylisting()->sharer()->print_link( $link ) ?></li>
        <?php endforeach ?>
    </ul>
</li>