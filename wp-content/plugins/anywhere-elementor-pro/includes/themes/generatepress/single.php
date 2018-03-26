<?php
/**
 * The Template for displaying all single posts.
 *
 * @package GeneratePress
 */

use Elementor\Plugin;
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>

	<div id="primary" <?php generate_content_class();?>>
		<main id="main" <?php generate_main_class(); ?>>
		<?php do_action('generate_before_main_content'); ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_article_schema( 'CreativeWork' ); ?>>
				<div class="inside-article">
					<?php if(Plugin::instance()->preview->is_preview_mode()){
						the_content();
					}else{
						do_action('aepro_single_data');
					}
					?>
				</div><!-- .inside-article -->
			</article><!-- #post-## -->

		<?php endwhile; // end of the loop. ?>
		<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
do_action('generate_sidebars');
get_footer();