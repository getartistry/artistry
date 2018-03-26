<?php
/**
 * Single related portfolio
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Text
$text = esc_html__( 'You Might Also Like', 'ocean-portfolio' );

// Apply filters for child theming
$text = apply_filters( 'op_related_portfolios_title', $text );

// Number of columns for entries
$op_columns = apply_filters( 'op_portfolio_related_columns', absint( get_theme_mod( 'op_portfolio_related_columns', '3' ) ) );

// Create an array of current category ID's
$cats     = wp_get_post_terms( get_the_ID(), 'ocean_portfolio_category' );
$cats_ids = array();
foreach( $cats as $related_cat ) {
	$cats_ids[] = $related_cat->term_id;
}

// Query args
$args = array(
	'post_type'      => 'ocean_portfolio',
	'posts_per_page' => apply_filters( 'op_portfolio_related_count', absint( get_theme_mod( 'op_portfolio_related_count', '3' ) ) ),
	'orderby'        => 'rand',
	'post__not_in'   => array( get_the_ID() ),
	'no_found_rows'  => true,
);

// If relates categories
if ( ! empty( $cats_ids ) ) {
	$args['tax_query'] = array( array(
		'taxonomy' => 'ocean_portfolio_category',
		'field'    => 'id',
		'terms'    => $cats_ids,
		'operator' => 'IN',
	) );
}

$args = apply_filters( 'op_portfolio_related_query_args', $args );

// Related query arguments
$op_related_query = new WP_Query( $args );

// If the custom query returns portfolio display related items section
if ( $op_related_query->have_posts() ) :

	// Wrapper classes
	$classes = 'clr';
	if ( 'full-screen' == oceanwp_post_layout() ) {
		$classes .= ' container';
	} ?>

	<div id="related-posts" class="<?php echo esc_attr( $classes ); ?>">

		<h3 class="theme-heading related-portfolio-title">
			<span class="text"><?php echo esc_html( $text ); ?></span>
		</h3>

		<div class="oceanwp-row clr">

			<?php $op_count = 0; ?>

			<?php foreach( $op_related_query->posts as $post ) : setup_postdata( $post ); ?>

				<?php $op_count++;

				// Add classes
				$classes	= array( 'related-portfolio', 'clr', 'col' );
				$classes[]	= oceanwp_grid_class( $op_columns );
				$classes[]	= 'col-'. $op_count; ?>

				<article <?php post_class( $classes ); ?>>

					<?php
					// Display post thumbnail
					if ( has_post_thumbnail() ) : ?>

						<figure class="related-portfolio-media clr">

							<a href="<?php the_permalink(); ?>" class="related-thumb">

								<?php
								// Image width
								$img_width  = apply_filters( 'op_portfolio_related_img_width', absint( get_theme_mod( 'op_portfolio_related_img_width' ) ) );
								$img_height = apply_filters( 'op_portfolio_related_img_height', absint( get_theme_mod( 'op_portfolio_related_img_height' ) ) );

			                	// Images attr
								$img_id 	= get_post_thumbnail_id( get_the_ID(), 'full' );
								$img_url 	= wp_get_attachment_image_src( $img_id, 'full', true );
								if ( OCEAN_EXTRA_ACTIVE
									&& function_exists( 'ocean_extra_image_attributes' ) ) {
									$img_atts 	= ocean_extra_image_attributes( $img_url[1], $img_url[2], $img_width, $img_height );
								}

								// If Ocean Extra is active and has a custom size
								if ( OCEAN_EXTRA_ACTIVE
									&& function_exists( 'ocean_extra_resize' )
									&& ! empty( $img_atts ) ) { ?>

									<img src="<?php echo ocean_extra_resize( $img_url[0], $img_atts[ 'width' ], $img_atts[ 'height' ], $img_atts[ 'crop' ], true, $img_atts[ 'upscale' ] ); ?>" alt="<?php the_title_attribute(); ?>" width="<?php echo esc_attr( $img_width ); ?>" height="<?php echo esc_attr( $img_height ); ?>" itemprop="image" />

								<?php
								} else {

									// Images size
									if ( 'full-width' == oceanwp_post_layout()
										|| 'full-screen' == oceanwp_post_layout() ) {
										$size = 'medium_large';
									} else {
										$size = 'medium';
									}

									// Display post thumbnail
									the_post_thumbnail( $size, array(
										'alt'		=> get_the_title(),
										'itemprop' 	=> 'image',
									) );

								} ?>
							</a>

						</figure>

					<?php endif; ?>

					<h3 class="related-portfolio-title">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h3><!-- .related-portfolio-title -->
									
					<time class="published" datetime="<?php echo esc_html( get_the_date( 'c' ) ); ?>"><i class="icon-clock"></i><?php echo esc_html( get_the_date() ); ?></time>

				</article><!-- .related-portfolio -->
				
				<?php if ( $op_columns == $op_count ) $op_count=0; ?>

			<?php endforeach; ?>

		</div><!-- .oceanwp-row -->

	</div><!-- .related-portfolio -->

<?php endif; ?>

<?php wp_reset_postdata(); ?>