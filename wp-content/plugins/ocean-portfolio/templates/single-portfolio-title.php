<?php
/**
 * Displays the title
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<header class="entry-header clr">
	<h2 class="single-portfolio-title entry-title"<?php oceanwp_schema_markup( 'headline' ); ?>><?php the_title(); ?></h2><!-- .single-portfolio-title -->
</header><!-- .entry-header -->