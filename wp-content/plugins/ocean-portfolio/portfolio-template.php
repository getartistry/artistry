 <?php
/**
 * The template for displaying archive portfolio items
 */

get_header(); ?>

	<?php do_action( 'ocean_before_content_wrap' ); ?>

	<div id="content-wrap" class="container clr">

		<?php do_action( 'ocean_before_primary' ); ?>

		<div id="primary" class="content-area clr">

			<?php do_action( 'ocean_before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'ocean_before_content_inner' ); ?>

				<?php
				// If single portfolio item
			    if ( is_singular( 'ocean_portfolio' ) ) {

					// Start loop
					while ( have_posts() ) : the_post();

						$theme_file = get_stylesheet_directory() . '/templates/single-portfolio-layout.php';

						/**
						 * Checks if the file exists in the theme first
						 * Otherwise serve the file from the plugin
						 */
						if ( file_exists( $theme_file ) ) {
							$template_path = $theme_file;
						} else {
			                $template_path = OP_PATH . '/templates/single-portfolio-layout.php';
			            }

			        	include( $template_path );
			        	
			        endwhile;

			    }

				// If portfolio taxonomy
				else if ( op_portfolio_taxonomy() ) {

					$theme_file = get_stylesheet_directory() . '/templates/portfolio-taxonomy.php';

					/**
					 * Checks if the file exists in the theme first
					 * Otherwise serve the file from the plugin
					 */
					if ( file_exists( $theme_file ) ) {
						$template_path = $theme_file;
					} else {
		                $template_path = OP_PATH . '/templates/portfolio-taxonomy.php';
		            }

					include( $template_path );

				}

			    // If portfolio archives
			    else {

					$theme_file = get_stylesheet_directory() . '/templates/portfolio-archive.php';

					/**
					 * Checks if the file exists in the theme first
					 * Otherwise serve the file from the plugin
					 */
					if ( file_exists( $theme_file ) ) {
						$template_path = $theme_file;
					} else {
		                $template_path = OP_PATH . '/templates/portfolio-archive.php';
		            }

					include( $template_path );

				} ?>

				<?php do_action( 'ocean_after_content_inner' ); ?>

			</div><!-- #content -->

			<?php do_action( 'ocean_after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'ocean_after_primary' ); ?>

		<?php do_action( 'ocean_display_sidebar' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'ocean_after_content_wrap' ); ?>

<?php get_footer(); ?>
