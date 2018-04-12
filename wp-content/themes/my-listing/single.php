<?php get_header();

while( have_posts() ): the_post();

	if ( get_post_type() == 'job_listing' ):
		get_template_part( 'templates/listing' );
	else:
		get_template_part( 'templates/content' );
	endif;

endwhile;

get_footer();