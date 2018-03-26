<?php
/**
 * Portfolio single content
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="entry-content clr"<?php oceanwp_schema_markup( 'entry_content' ); ?>>
	<?php the_content();

	wp_link_pages( array(
		'before'      => '<div class="page-links">' . __( 'Pages:', 'ocean-portfolio' ),
		'after'       => '</div>',
		'link_before' => '<span class="page-number">',
		'link_after'  => '</span>',
	) ); ?>
</div><!-- .entry -->