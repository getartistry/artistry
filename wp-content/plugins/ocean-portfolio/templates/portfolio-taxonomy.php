 <?php
/**
 * Portfolio taxonomy
 */

// Vars
$columns 			= get_theme_mod( 'op_portfolio_columns' );
$columns 			= $columns ? $columns : '3';
$tablet_columns 	= get_theme_mod( 'op_portfolio_tablet_columns' );
$tablet_columns    	= $tablet_columns ? $tablet_columns : '2';
$mobile_columns 	= get_theme_mod( 'op_portfolio_mobile_columns' );
$mobile_columns    	= $mobile_columns ? $mobile_columns : '1';
$masonry 			= get_theme_mod( 'op_portfolio_masonry' );
$masonry 			= $masonry ? $masonry : 'off';
$overlay_icons 		= get_theme_mod( 'op_portfolio_img_overlay_icons' );
$overlay_icons 		= $overlay_icons ? $overlay_icons : 'on';
$pagination_pos 	= get_theme_mod( 'op_portfolio_pagination_position' );
$pagination_pos 	= $pagination_pos ? $pagination_pos : 'center';

// Wrap classes
$wrap_classes 	   	= array( 'portfolio-entries', 'clr', 'tablet-col', 'mobile-col' );
$wrap_classes[] 	= 'tablet-' . $tablet_columns . '-col';
$wrap_classes[] 	= 'mobile-' . $mobile_columns . '-col';

// Is masonry
if ( 'on' == $masonry ) {
	$wrap_classes[] = 'masonry-grid';
}

// Add class if no overlay icon
if ( 'on' != $overlay_icons ) {
	$wrap_classes[] = 'no-lightbox';
}

$wrap_classes 		= implode( ' ', $wrap_classes );

if ( have_posts() ) :

	global $wp_query; ?>

	<div class="<?php echo esc_attr( $wrap_classes ); ?>">

		<?php
		// If masonry
		if ( 'on' == $masonry ) {
			$data = 'masonry';
		} else {
			$data = 'fitRows';
		} ?>

		<div class="portfolio-wrap" data-layout="<?php echo esc_attr( $data ); ?>">

			<?php
			$op_count = 0;

			while ( have_posts() ) : the_post();

				$op_count++;

				// Inner classes
				$inner_classes 		= array( 'portfolio-entry', 'clr', 'col' );
				$inner_classes[] 	= 'column-'. $columns;
				$inner_classes[] 	= 'col-'. $op_count;

				// If title
				if ( 'on' == $title ) {
					$inner_classes[] = 'has-title';
				}

				$inner_classes 		= implode( ' ', $inner_classes ); ?>

				<article id="post-<?php the_ID(); ?>" class="<?php echo esc_attr( $inner_classes ); ?>">

					<?php
					$theme_file = get_stylesheet_directory() . '/templates/entry-portfolio.php';

					/**
					 * Checks if the file exists in the theme first
					 * Otherwise serve the file from the plugin
					 */
					if ( file_exists( $theme_file ) ) {
						$template_path = $theme_file;
					} else {
		                $template_path = OP_PATH . '/templates/entry-portfolio.php';
		            }

		        	include( $template_path ); ?>

				</article>

				<?php
				// Reset counter to clear floats
				if ( $columns == $op_count ) {
					$op_count=0;
				}

			endwhile; ?>

		</div>

	</div>

	<?php
	// Pagination
	op_portfolio_pagination( $wp_query->max_num_pages, $pagination_pos );

// No portfolio found
else : ?>

	<p class="portfolio-not-found"><?php esc_html_e( 'You have no portfolio items', 'ocean-portfolio' ); ?></p>

<?php
endif;