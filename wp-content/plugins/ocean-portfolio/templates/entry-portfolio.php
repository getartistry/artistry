 <?php
/**
 * Entry portfolio items
 */

// Vars
$title_cat_position = get_theme_mod( 'op_portfolio_title_cat_position' );
$title_cat_position = $title_cat_position ? $title_cat_position : 'outside';
$title 				= get_theme_mod( 'op_portfolio_title' );
$title 				= $title ? $title : 'on';
$heading 			= get_theme_mod( 'op_portfolio_title_tag' );
$heading 			= $heading ? $heading : 'h3';
$category 			= get_theme_mod( 'op_portfolio_category' );
$category 			= $category ? $category : 'on';
$img_size 			= get_theme_mod( 'op_portfolio_size' );
$img_size 			= $img_size ? $img_size : 'medium';
$img_width 			= get_theme_mod( 'op_portfolio_img_width' );
$img_width 			= $img_width ? $img_width : '';
$img_height 		= get_theme_mod( 'op_portfolio_img_height' );
$img_height 		= $img_height ? $img_height : '';
$overlay_icons 		= get_theme_mod( 'op_portfolio_img_overlay_icons' );
$overlay_icons 		= $overlay_icons ? $overlay_icons : 'on';

// If external link
$meta = get_post_meta( get_the_ID(), 'op_external_url', true );
if ( ! empty( $meta ) ) {
	$link = $meta;
} else {
	$link = get_the_permalink();
}

// External link target
$target = get_post_meta( get_the_ID(), 'op_external_url_target', true );
$target = $target ? $target: 'self'; ?>

<div class="portfolio-entry-inner clr">

	<?php
	// Featured image
	if ( has_post_thumbnail() ) { ?>

		<div class="portfolio-entry-thumbnail">

			<a href="<?php echo esc_url( $link ); ?>" class="thumbnail-link" target="_<?php echo esc_attr( $target ); ?>">

				<?php
				// Image attr
				$img_id 	= get_post_thumbnail_id( get_the_ID(), 'full' );
				$img_url 	= wp_get_attachment_image_src( $img_id, 'full', true );

            	// If Ocean Extra is active
            	if ( class_exists( 'Ocean_Extra' ) ) {

					// Image attrs
					$img_atts 	= ocean_extra_image_attributes( $img_url[1], $img_url[2], $img_width, $img_height );

					// Display post thumbnail
					if ( 'custom' == $img_size
						&& ! empty( $img_atts ) ) { ?>
						<img src="<?php echo ocean_extra_resize( $img_url[0], $img_atts[ 'width' ], $img_atts[ 'height' ], $img_atts[ 'crop' ], true, $img_atts[ 'upscale' ] ); ?>" alt="<?php esc_attr( the_title() ); ?>" width="<?php echo esc_attr( $img_width ); ?>" height="<?php echo esc_attr( $img_height ); ?>" itemprop="image" />
					<?php
					} else {
						the_post_thumbnail( $img_size, array(
							'alt'		=> get_the_title(),
							'itemprop' 	=> 'image',
						) );
					}

				} ?>

				<div class="overlay"></div>
				
			</a>

			<?php
			// Overlay content
			if ( 'on' == $overlay_icons || 'inside' == $title_cat_position ) { ?>
				<div class="portfolio-overlay-content">
					<?php
					// Overlay icons
					if ( 'on' == $overlay_icons ) { ?>
						<ul class="portfolio-overlay-icons">
							<li>
								<a href="<?php echo esc_url( $link ); ?>" target="_<?php echo esc_attr( $target ); ?>"><i class="fa fa-link" aria-hidden="true"></i></a>
							</li>
							<li>
								<a href="<?php echo esc_url( wp_get_attachment_url( $img_id ) ); ?>" class="portfolio-lightbox"><i class="fa fa-search" aria-hidden="true"></i></a>
							</li>
						</ul>
					<?php }

					// If title or category
					if ( 'inside' == $title_cat_position 
						&& ( 'on' == $title || 'on' == $category ) ) {

						// Class
						$class = '';
						if ( 'on' == $overlay_icons ) {
							$class = ' has-icons';
						} ?>

						<div class="portfolio-inside-content clr<?php echo esc_attr( $class ); ?>">
							<?php
							// If title
							if ( 'on' == $title ) { ?>
							<<?php echo esc_attr( $heading ); ?> class="portfolio-entry-title entry-title">
								<a href="<?php echo esc_url( $link ); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" target="_<?php echo esc_attr( $target ); ?>"><?php the_title(); ?></a>
							</<?php echo esc_attr( $heading ); ?>>
							<?php
							}

							// If category
							if ( 'on' == $category ) {
								if ( $categories = op_portfolio_category_meta() ) {?>
									<div class="categories"><?php echo $categories; ?></div>
								<?php }
							} ?>
						</div>

					<?php } ?>
				</div>
			<?php } ?>

			<?php
			// If title or category
			if ( 'outside' == $title_cat_position 
				&& ( 'on' == $title || 'on' == $category ) ) { ?>
				<div class="triangle-wrap"></div>
			<?php } ?>

		</div>

	<?php
	}

	// If title or category
	if ( 'outside' == $title_cat_position 
		&& ( 'on' == $title || 'on' == $category ) ) { ?>

		<div class="portfolio-content clr">
			<?php
			// If title
			if ( 'on' == $title ) { ?>
			<<?php echo esc_attr( $heading ); ?> class="portfolio-entry-title entry-title">
				<a href="<?php echo esc_url( $link ); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" target="_<?php echo esc_attr( $target ); ?>"><?php the_title(); ?></a>
			</<?php echo esc_attr( $heading ); ?>>
			<?php
			}

			// If category
			if ( 'on' == $category ) {
				if ( $categories = op_portfolio_category_meta() ) {?>
					<div class="categories"><?php echo $categories; ?></div>
				<?php }
			} ?>
		</div>

	<?php } ?>

</div>