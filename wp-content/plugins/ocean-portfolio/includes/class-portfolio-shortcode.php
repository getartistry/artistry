<?php
/**
 * Portfolio Shortcode
 */

if ( ! class_exists( 'OceanWP_Portfolio_Shortcode' ) ) {

	class OceanWP_Portfolio_Shortcode {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_shortcode( 'oceanwp_portfolio', array( $this, 'portfolio_shortcode' ) );
		}

		/**
		 * Build the front end
		 */
		public function portfolio_display( $id ) {

			// Vars
			$posts_per_page 	= get_post_meta( $id, 'op_portfolio_posts_per_page', true );
			$posts_per_page 	= $posts_per_page ? $posts_per_page : '12';
			$columns 			= get_post_meta( $id, 'op_portfolio_columns', true );
			$columns 			= $columns ? $columns : '3';
			$tablet_columns 	= get_post_meta( $id, 'op_portfolio_tablet_columns', true );
			$tablet_columns    	= $tablet_columns ? $tablet_columns : '2';
			$mobile_columns 	= get_post_meta( $id, 'op_portfolio_mobile_columns', true );
			$mobile_columns    	= $mobile_columns ? $mobile_columns : '1';
			$masonry 			= get_post_meta( $id, 'op_portfolio_masonry', true );
			$masonry 			= $masonry ? $masonry : 'off';
			$title_cat_position = get_post_meta( $id, 'op_portfolio_title_cat_position', true );
			$title_cat_position = $title_cat_position ? $title_cat_position : 'outside';
			$title 				= get_post_meta( $id, 'op_portfolio_title', true );
			$title 				= $title ? $title : 'on';
			$heading 			= get_post_meta( $id, 'op_portfolio_title_tag', true );
			$heading 			= $heading ? $heading : 'h3';
			$category 			= get_post_meta( $id, 'op_portfolio_category', true );
			$category 			= $category ? $category : 'on';
			$pagination 		= get_post_meta( $id, 'op_portfolio_pagination', true );
			$pagination 		= $pagination ? $pagination : 'off';
			$pagination_pos 	= get_post_meta( $id, 'op_portfolio_pagination_position', true );
			$pagination_pos 	= $pagination_pos ? $pagination_pos : 'center';
			$filter 			= get_post_meta( $id, 'op_portfolio_filter', true );
			$filter 			= $filter ? $filter : 'off';
			$all_filter 		= get_post_meta( $id, 'op_portfolio_all_filter', true );
			$all_filter 		= $all_filter ? $all_filter : 'on';
			$filter_pos 		= get_post_meta( $id, 'op_portfolio_filter_position', true );
			$filter_pos 		= $filter_pos ? $filter_pos : 'center';
			$filter_tax 		= get_post_meta( $id, 'op_portfolio_filter_taxonomy', true );
			$filter_tax 		= $filter_tax ? $filter_tax : 'categories';
			$img_size 			= get_post_meta( $id, 'op_portfolio_size', true );
			$img_size 			= $img_size ? $img_size : 'medium';
			$img_width 			= get_post_meta( $id, 'op_portfolio_img_width', true );
			$img_width 			= $img_width ? $img_width : '';
			$img_height 		= get_post_meta( $id, 'op_portfolio_img_height', true );
			$img_height 		= $img_height ? $img_height : '';
			$overlay_icons 		= get_post_meta( $id, 'op_portfolio_img_overlay_icons', true );
			$overlay_icons 		= $overlay_icons ? $overlay_icons : 'on';
			$authors 			= get_post_meta( $id, 'op_portfolio_authors', true );
			$authors 			= $authors ? $authors : '';
			$category_ids 		= get_post_meta( $id, 'op_portfolio_category_ids', true );
			$category_ids 		= $category_ids ? $category_ids : '';
			$tags 				= get_post_meta( $id, 'op_portfolio_tags', true );
			$tags 				= $tags ? $tags : '';
			$offset 			= get_post_meta( $id, 'op_portfolio_offset', true );
			$offset 			= $offset ? $offset : '';
			$order 				= get_post_meta( $id, 'op_portfolio_order', true );
			$order 				= $order ? $order : 'DESC';
			$orderby 			= get_post_meta( $id, 'op_portfolio_orderby', true );
			$orderby 			= $orderby ? $orderby : 'date';
			$exclude_cat 		= get_post_meta( $id, 'op_portfolio_exclude_category', true );
			$exclude_cat 		= $exclude_cat ? $exclude_cat : '';

			// Wrap classes
			$wrap_classes 	   	= array( 'portfolio-entries', 'clr', 'tablet-col', 'mobile-col' );
			$wrap_classes[] 	= 'tablet-' . $tablet_columns . '-col';
			$wrap_classes[] 	= 'mobile-' . $mobile_columns . '-col';

			// Is masonry
			if ( 'on' == $masonry && 'off' == $filter ) {
				$wrap_classes[] = 'masonry-grid';
			}

			// Enable isotope if filter
			if ( 'on' == $filter ) {
				$wrap_classes[] = 'isotope-grid';
			}

			// Add class if no overlay icon
			if ( 'on' != $overlay_icons ) {
				$wrap_classes[] = 'no-lightbox';
			}

			$wrap_classes 		= implode( ' ', $wrap_classes );

			// Query args
			$args = array(
				'post_type'      	=> 'ocean_portfolio',
				'posts_per_page' 	=> $posts_per_page,
				'order'				=> $order,
				'orderby'			=> $orderby,
				'post_status' 		=> 'publish',
				'tax_query' 		=> array(
					'relation' 		=> 'AND',
				),
			);

		    // Authors
			if ( ! empty( $authors ) ) {

				// Convert to array
				$authors = implode( ',', $authors );
				$authors = explode( ',', $authors );

				// Add to query arg
				$args['author__in'] = $authors;

			}

		    // Categories IDs
			if ( ! empty( $category_ids ) ) {

				// Convert to array
				$category_ids = implode( ',', $category_ids );
				$category_ids = explode( ',', $category_ids );

				// Add to query arg
				$args['tax_query'][] = array(
					'taxonomy' => 'ocean_portfolio_category',
					'field'    => 'slug',
					'terms'    => $category_ids,
					'operator' => 'IN',
				);

			}

		    // Tags
			if ( ! empty( $tags ) ) {

				// Convert to array
				$tags = implode( ',', $tags );
				$tags = explode( ',', $tags );

				// Add to query arg
				$args['tax_query'][] = array(
					'taxonomy' => 'ocean_portfolio_tag',
					'field'    => 'slug',
					'terms'    => $tags,
					'operator' => 'IN',
				);

			}

		    // Offset
			if ( ! empty( $offset ) && $offset > 0 ) {
				$args['offset'] = $offset;
			}

		    // Exclude category
			if ( ! empty( $exclude_cat ) ) {

				// Convert to array
				$exclude_cat = implode( ',', $exclude_cat );
				$exclude_cat = explode( ',', $exclude_cat );

				// Add to query arg
				$args['tax_query'][] = array(
					'taxonomy' => 'ocean_portfolio_category',
					'field'    => 'slug',
					'terms'    => $exclude_cat,
					'operator' => 'NOT IN',
				);

			}

			// If filter
			if ( 'on' == $filter ) {

				// Get taxonomy
				if ( 'categories' == $filter_tax ) {
					$taxonomy = 'ocean_portfolio_category';
					$tax = 'cat';
				} else if ( 'tags' == $filter_tax ) {
					$taxonomy = 'ocean_portfolio_tag';
					$tax = 'tag';
				}

				// Filter args
				$filter_args = array(
					'taxonomy' 	 => $taxonomy,
					'hide_empty' => 1,
				);

				// If categories IDs, tags or exclude category
				if ( ! empty( $category_ids ) || ! empty( $tags ) || ! empty( $exclude_cat ) ) {

					if ( ! empty( $category_ids ) ) {
						$term_arg 	= 'include';
						$get_term 	= $category_ids;
						$term_tax 	= 'ocean_portfolio_category';
					} else if ( ! empty( $tags ) ) {
						$term_arg 	= 'include';
						$get_term 	= $tags;
						$term_tax 	= 'ocean_portfolio_tag';
					} else if ( ! empty( $exclude_cat ) ) {
						$term_arg 	= 'exclude';
						$get_term 	= $exclude_cat;
						$term_tax 	= 'ocean_portfolio_category';
					}

					// Convert to array
					$get_term = implode( ',', $get_term );
					$get_term = explode( ',', $get_term );

					// Array
					$term_ids = array();

					// Get terms by ID
					foreach ( $get_term as $cat_id ) {
						$term_objects = get_term_by( 'slug', $cat_id, $term_tax );
						$term_ids[]   = $term_objects->term_id;
				    }

				    // Add to filter arg
				    $filter_args[$term_arg] = $term_ids;

				}

				// Get filter terms
				$filter_terms = get_terms( $filter_args );

			}

			// If pagination
			if ( 'on' == $pagination && ! is_single() ) {
				$paged_query 	= is_front_page() ? 'page' : 'paged';
				$args['paged'] 	= get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			}

			$oceanwp_query = new WP_Query( $args );

			// Output posts
			if ( $oceanwp_query->have_posts() ) : ?>

				<div id="portfolio-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $wrap_classes ); ?>">

					<?php
					// Filter
					if ( 'on' == $filter && ! empty( $filter_terms ) ) {

						// Class
						$filter_classes 	   	= array( 'portfolio-filters' );

						// Filter position
						if ( 'center' != $filter_pos ) {
							$filter_classes[] 	= 'filter-pos-' . $filter_pos;
						}

						$filter_classes 		= implode( ' ', $filter_classes ); ?>

						<ul class="<?php echo esc_attr( $filter_classes ); ?>">
							<?php
							// Filter
							if ( 'on' == $all_filter ) { ?>
								<li class="portfolio-filter active"><a href="#" data-filter="*"><?php echo esc_html_e( 'All', 'ocean-portfolio' ); ?></a></li>
							<?php }
							foreach ( $filter_terms as $term ) { ?>
								<li class="portfolio-filter"><a href="#" data-filter=".<?php echo $tax; ?>-<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a></li>
							<?php } ?>
						</ul>

					<?php }

					// If masonry
					if ( 'on' == $masonry ) {
						$data = 'masonry';
					} else {
						$data = 'fitRows';
					} ?>

					<div class="portfolio-wrap" data-layout="<?php echo esc_attr( $data ); ?>">

						<?php
						// Define counter for clearing floats
						$op_count = 0;

						// Start loop
						while ( $oceanwp_query->have_posts() ) : $oceanwp_query->the_post();

							// Add to counter
							$op_count++;

							// Inner classes
							$inner_classes 		= array( 'portfolio-entry', 'clr', 'col' );
							$inner_classes[] 	= 'column-'. $columns;
							$inner_classes[] 	= 'col-'. $op_count;

							// If title
							if ( 'on' == $title ) {
								$inner_classes[] = 'has-title';
							}

							// If filter
							if ( 'on' == $filter && ! empty( $filter_terms ) ) {

								$terms_list = wp_get_post_terms( get_the_ID(), $taxonomy );

								foreach ( $terms_list as $term ) {
									$inner_classes[] = $tax . '-' . $term->term_id;
								}

							}

							$inner_classes 		= implode( ' ', $inner_classes );

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

							<article id="post-<?php the_ID(); ?>" class="<?php echo esc_attr( $inner_classes ); ?>">

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

							</article>

						<?php
						// Reset counter to clear floats
						if ( $columns == $op_count ) {
							$op_count=0;
						}

						// End entry loop
						endwhile; ?>

					</div>

				    <?php
					// Pagination
					if ( 'on' == $pagination && ! is_single() ) {
						op_portfolio_pagination( $oceanwp_query->max_num_pages, $pagination_pos );
					}

					// Reset the post data to prevent conflicts with WP globals
					wp_reset_postdata(); ?>

					<?php
					// Styling vars
					$full_filter_links 						= get_post_meta( $id, 'op_portfolio_responsive_filter_links', true );
					$full_filter_links 						= $full_filter_links ? $full_filter_links : '480';
					$custom_full_filter_links 				= get_post_meta( $id, 'op_portfolio_responsive_filter_links_custom', true );
					$filter_margin 							= get_post_meta( $id, 'op_portfolio_filter_margin', true );
					$filter_links_margin 					= get_post_meta( $id, 'op_portfolio_filter_links_margin', true );
					$filter_links_padding 					= get_post_meta( $id, 'op_portfolio_filter_links_padding', true );
					$filter_links_bg 						= get_post_meta( $id, 'op_portfolio_filter_links_bg', true );
					$filter_links_color 					= get_post_meta( $id, 'op_portfolio_filter_links_color', true );
					$filter_active_link_bg 					= get_post_meta( $id, 'op_portfolio_filter_active_link_bg', true );
					$filter_active_link_color 				= get_post_meta( $id, 'op_portfolio_filter_active_link_color', true );
					$filter_hover_links_bg 					= get_post_meta( $id, 'op_portfolio_filter_hover_links_bg', true );
					$filter_hover_links_color 				= get_post_meta( $id, 'op_portfolio_filter_hover_links_color', true );
					$img_overlay_color 						= get_post_meta( $id, 'op_portfolio_img_overlay_color', true );
					$img_overlay_icons_width 				= get_post_meta( $id, 'op_portfolio_img_overlay_icons_width', true );
					$img_overlay_icons_height 				= get_post_meta( $id, 'op_portfolio_img_overlay_icons_height', true );
					$img_overlay_icons_size 				= get_post_meta( $id, 'op_portfolio_img_overlay_icons_size', true );
					$img_overlay_icons_bg 					= get_post_meta( $id, 'op_portfolio_img_overlay_icons_bg', true );
					$img_overlay_icons_hover_bg 			= get_post_meta( $id, 'op_portfolio_img_overlay_icons_hover_bg', true );
					$img_overlay_icons_color 				= get_post_meta( $id, 'op_portfolio_img_overlay_icons_color', true );
					$img_overlay_icons_hover_color 			= get_post_meta( $id, 'op_portfolio_img_overlay_icons_hover_color', true );
					$img_overlay_icons_border_radius 		= get_post_meta( $id, 'op_portfolio_img_overlay_icons_border_radius', true );
					$img_overlay_icons_border_width 		= get_post_meta( $id, 'op_portfolio_img_overlay_icons_border_width', true );
					$img_overlay_icons_border_style 		= get_post_meta( $id, 'op_portfolio_img_overlay_icons_border_style', true );
					$img_overlay_icons_border_color 		= get_post_meta( $id, 'op_portfolio_img_overlay_icons_border_color', true );
					$img_overlay_icons_hover_border_color 	= get_post_meta( $id, 'op_portfolio_img_overlay_icons_hover_border_color', true );
					$item_margin 							= get_post_meta( $id, 'op_portfolio_item_margin', true );
					$item_padding 							= get_post_meta( $id, 'op_portfolio_item_padding', true );
					$item_border_radius 					= get_post_meta( $id, 'op_portfolio_item_border_radius', true );
					$item_border_width 						= get_post_meta( $id, 'op_portfolio_item_border_width', true );
					$item_border_style 						= get_post_meta( $id, 'op_portfolio_item_border_style', true );
					$item_border_color 						= get_post_meta( $id, 'op_portfolio_item_border_color', true );
					$item_bg 								= get_post_meta( $id, 'op_portfolio_item_bg', true );
					$outside_content_padding 				= get_post_meta( $id, 'op_portfolio_outside_content_padding', true );
					$outside_content_bg 					= get_post_meta( $id, 'op_portfolio_outside_content_bg', true );
					$title_color 							= get_post_meta( $id, 'op_portfolio_title_color', true );
					$title_hover_color 						= get_post_meta( $id, 'op_portfolio_title_hover_color', true );
					$category_color 						= get_post_meta( $id, 'op_portfolio_category_color', true );
					$category_hover_color 					= get_post_meta( $id, 'op_portfolio_category_hover_color', true );

					// Typography
					$filter_font_family 					= get_post_meta( $id, 'op_portfolio_filter_typo_font_family', true );
					$filter_font_size 						= get_post_meta( $id, 'op_portfolio_filter_typo_font_size', true );
					$filter_font_weight 					= get_post_meta( $id, 'op_portfolio_filter_typo_font_weight', true );
					$filter_font_style 						= get_post_meta( $id, 'op_portfolio_filter_typo_font_style', true );
					$filter_text_transform 					= get_post_meta( $id, 'op_portfolio_filter_typo_transform', true );
					$filter_line_height 					= get_post_meta( $id, 'op_portfolio_filter_typo_line_height', true );
					$filter_letter_spacing 					= get_post_meta( $id, 'op_portfolio_filter_typo_spacing', true );
					$title_font_family 						= get_post_meta( $id, 'op_portfolio_title_typo_font_family', true );
					$title_font_size 						= get_post_meta( $id, 'op_portfolio_title_typo_font_size', true );
					$title_font_weight 						= get_post_meta( $id, 'op_portfolio_title_typo_font_weight', true );
					$title_font_style 						= get_post_meta( $id, 'op_portfolio_title_typo_font_style', true );
					$title_text_transform 					= get_post_meta( $id, 'op_portfolio_title_typo_transform', true );
					$title_line_height 						= get_post_meta( $id, 'op_portfolio_title_typo_line_height', true );
					$title_letter_spacing 					= get_post_meta( $id, 'op_portfolio_title_typo_spacing', true );
					$cat_font_family 						= get_post_meta( $id, 'op_portfolio_category_typo_font_family', true );
					$cat_font_size 							= get_post_meta( $id, 'op_portfolio_category_typo_font_size', true );
					$cat_font_weight 						= get_post_meta( $id, 'op_portfolio_category_typo_font_weight', true );
					$cat_font_style 						= get_post_meta( $id, 'op_portfolio_category_typo_font_style', true );
					$cat_text_transform 					= get_post_meta( $id, 'op_portfolio_category_typo_transform', true );
					$cat_line_height 						= get_post_meta( $id, 'op_portfolio_category_typo_line_height', true );
					$cat_letter_spacing 					= get_post_meta( $id, 'op_portfolio_category_typo_spacing', true );

					// Tablet device
					$tablet_item_margin 					= get_post_meta( $id, 'op_portfolio_tablet_item_margin', true );
					$tablet_item_padding 					= get_post_meta( $id, 'op_portfolio_tablet_item_padding', true );
					$tablet_item_border_radius 				= get_post_meta( $id, 'op_portfolio_tablet_item_border_radius', true );
					$tablet_item_border_width 				= get_post_meta( $id, 'op_portfolio_tablet_item_border_width', true );
					$tablet_filter_font_size 				= get_post_meta( $id, 'op_portfolio_tablet_filter_typo_font_size', true );
					$tablet_filter_text_transform 			= get_post_meta( $id, 'op_portfolio_tablet_filter_typo_transform', true );
					$tablet_filter_line_height 				= get_post_meta( $id, 'op_portfolio_tablet_filter_typo_line_height', true );
					$tablet_filter_letter_spacing 			= get_post_meta( $id, 'op_portfolio_tablet_filter_typo_spacing', true );
					$tablet_title_font_size 				= get_post_meta( $id, 'op_portfolio_tablet_title_typo_font_size', true );
					$tablet_title_text_transform 			= get_post_meta( $id, 'op_portfolio_tablet_title_typo_transform', true );
					$tablet_title_line_height 				= get_post_meta( $id, 'op_portfolio_tablet_title_typo_line_height', true );
					$tablet_title_letter_spacing 			= get_post_meta( $id, 'op_portfolio_tablet_title_typo_spacing', true );
					$tablet_cat_font_size 					= get_post_meta( $id, 'op_portfolio_tablet_category_typo_font_size', true );
					$tablet_cat_font_style 					= get_post_meta( $id, 'op_portfolio_tablet_category_typo_font_style', true );
					$tablet_cat_text_transform 				= get_post_meta( $id, 'op_portfolio_tablet_category_typo_transform', true );
					$tablet_cat_line_height 				= get_post_meta( $id, 'op_portfolio_tablet_category_typo_line_height', true );
					$tablet_cat_letter_spacing 				= get_post_meta( $id, 'op_portfolio_tablet_category_typo_spacing', true );

					// Mobile device
					$mobile_item_margin 					= get_post_meta( $id, 'op_portfolio_mobile_item_margin', true );
					$mobile_item_padding 					= get_post_meta( $id, 'op_portfolio_mobile_item_padding', true );
					$mobile_item_border_radius 				= get_post_meta( $id, 'op_portfolio_mobile_item_border_radius', true );
					$mobile_item_border_width 				= get_post_meta( $id, 'op_portfolio_mobile_item_border_width', true );
					$mobile_filter_font_size 				= get_post_meta( $id, 'op_portfolio_mobile_filter_typo_font_size', true );
					$mobile_filter_text_transform 			= get_post_meta( $id, 'op_portfolio_mobile_filter_typo_transform', true );
					$mobile_filter_line_height 				= get_post_meta( $id, 'op_portfolio_mobile_filter_typo_line_height', true );
					$mobile_filter_letter_spacing 			= get_post_meta( $id, 'op_portfolio_mobile_filter_typo_spacing', true );
					$mobile_title_font_size 				= get_post_meta( $id, 'op_portfolio_mobile_title_typo_font_size', true );
					$mobile_title_text_transform 			= get_post_meta( $id, 'op_portfolio_mobile_title_typo_transform', true );
					$mobile_title_line_height 				= get_post_meta( $id, 'op_portfolio_mobile_title_typo_line_height', true );
					$mobile_title_letter_spacing 			= get_post_meta( $id, 'op_portfolio_mobile_title_typo_spacing', true );
					$mobile_cat_font_size 					= get_post_meta( $id, 'op_portfolio_mobile_category_typo_font_size', true );
					$mobile_cat_font_style 					= get_post_meta( $id, 'op_portfolio_mobile_category_typo_font_style', true );
					$mobile_cat_text_transform 				= get_post_meta( $id, 'op_portfolio_mobile_category_typo_transform', true );
					$mobile_cat_line_height 				= get_post_meta( $id, 'op_portfolio_mobile_category_typo_line_height', true );
					$mobile_cat_letter_spacing 				= get_post_meta( $id, 'op_portfolio_mobile_category_typo_spacing', true );

					// Define css var
					$css 						= '';
					$overlay_icons_css 			= '';
					$overlay_icons_hover_css 	= '';
					$border_css 				= '';
					$filter_typo_css 			= '';
					$title_typo_css 			= '';
					$cat_typo_css 				= '';
					$tablet_css 				= '';
					$tablet_filter_typo_css 	= '';
					$tablet_title_typo_css 		= '';
					$tablet_cat_typo_css 		= '';
					$mobile_css 				= '';
					$mobile_filter_typo_css 	= '';
					$mobile_title_typo_css 		= '';
					$mobile_cat_typo_css 		= '';

					// Add full width filter links in reponsive
					if ( ! empty( $full_filter_links ) ) {

						if ( 'custom' == $full_filter_links && ! empty( $custom_full_filter_links ) ) {
							$full_filter_links = $custom_full_filter_links;
						}

						$css .= '@media (max-width: '. $full_filter_links .'px) {#portfolio-'. $id .'.portfolio-entries .portfolio-filters li{width:100%;}}';
					}

					// Add filter margin
					if ( ! empty( $filter_margin ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-filters{margin:'. $filter_margin .';}';
					}

					// Add filter links margin
					if ( ! empty( $filter_links_margin ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-filters li{margin:'. $filter_links_margin .';}';
					}

					// Add filter links padding
					if ( ! empty( $filter_links_padding ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-filters li a{padding:'. $filter_links_padding .';}';
					}

					// Add filter links background
					if ( ! empty( $filter_links_bg ) && '#f6f6f6' != $filter_links_bg ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-filters li a{background-color:'. $filter_links_bg .';}';
					}

					// Add filter links color
					if ( ! empty( $filter_links_color ) && '#444444' != $filter_links_color ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-filters li a{color:'. $filter_links_color .';}';
					}

					// Add filter active link background
					if ( ! empty( $filter_active_link_bg ) && '#13aff0' != $filter_active_link_bg ) {
						$css .= 'body #portfolio-'. $id .'.portfolio-entries .portfolio-filters li.active a{background-color:'. $filter_active_link_bg .';}';
					}

					// Add filter active link color
					if ( ! empty( $filter_active_link_color ) && '#ffffff' != $filter_active_link_color ) {
						$css .= 'body #portfolio-'. $id .'.portfolio-entries .portfolio-filters li.active a{color:'. $filter_active_link_color .';}';
					}

					// Add filter hover links background
					if ( ! empty( $filter_hover_links_bg ) && '#13aff0' != $filter_hover_links_bg ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-filters li a:hover{background-color:'. $filter_hover_links_bg .';}';
					}

					// Add filter hover links color
					if ( ! empty( $filter_hover_links_color ) && '#ffffff' != $filter_hover_links_color ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-filters li a:hover{color:'. $filter_hover_links_color .';}';
					}

					// Add images overlay color
					if ( ! empty( $img_overlay_color ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .overlay{background-color:'. $img_overlay_color .';}';
					}

					// Add images overlay icons style
					if ( 'on' == $overlay_icons ) {
						if ( ! empty( $img_overlay_icons_width ) && '45' != $img_overlay_icons_width ) {
							$overlay_icons_css .= 'width:' . $img_overlay_icons_width .'px;';
						}
						if ( ! empty( $img_overlay_icons_height ) && '45' != $img_overlay_icons_height ) {
							$overlay_icons_css .= 'height:' . $img_overlay_icons_height .'px;';
						}
						if ( ! empty( $img_overlay_icons_size ) && '16' != $img_overlay_icons_size ) {
							$overlay_icons_css .= 'font-size:' . $img_overlay_icons_size .'px;';
						}
						if ( ! empty( $img_overlay_icons_bg ) ) {
							$overlay_icons_css .= 'background-color:' . $img_overlay_icons_bg .';';
						}
						if ( ! empty( $img_overlay_icons_color ) ) {
							$overlay_icons_css .= 'color:' . $img_overlay_icons_color .';';
						}
						if ( ! empty( $img_overlay_icons_border_radius ) ) {
							$overlay_icons_css .= 'border-radius:' . $img_overlay_icons_border_radius .';';
						}
						if ( ! empty( $img_overlay_icons_border_width ) && '1px' != $img_overlay_icons_border_width ) {
							$overlay_icons_css .= 'border-width:' . $img_overlay_icons_border_width .';';
						}
						if ( ! empty( $img_overlay_icons_border_style ) && 'solid' != $img_overlay_icons_border_style ) {
							$overlay_icons_css .= 'border-style:' . $img_overlay_icons_border_style .';';
						}
						if ( ! empty( $img_overlay_icons_border_color ) ) {
							$overlay_icons_css .= 'border-color:' . $img_overlay_icons_border_color .';';
						}
						if ( ! empty( $overlay_icons_css ) ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a{'. $overlay_icons_css .'}';
						}

						if ( ! empty( $img_overlay_icons_hover_bg ) ) {
							$overlay_icons_hover_css .= 'background-color:' . $img_overlay_icons_hover_bg .';';
						}
						if ( ! empty( $img_overlay_icons_hover_color ) ) {
							$overlay_icons_hover_css .= 'color:' . $img_overlay_icons_hover_color .';';
						}
						if ( ! empty( $img_overlay_icons_hover_border_color ) ) {
							$overlay_icons_hover_css .= 'border-color:' . $img_overlay_icons_hover_border_color .';';
						}
						if ( ! empty( $overlay_icons_hover_css ) ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a:hover{'. $overlay_icons_hover_css .'}';
						}
					}

					// Add item margin
					if ( ! empty( $item_margin ) && '10px' != $item_margin ) {
						$css .= '#portfolio-'. $id .' {margin: 0 -'. $item_margin .';}';
						$css .= '#portfolio-'. $id .' .portfolio-entry{padding:'. $item_margin .';}';
					}

					// Add padding
					if ( ! empty( $item_padding ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry .portfolio-entry-inner{padding:'. $item_padding .';}';
					}

					// Add border radius
					if ( ! empty( $item_border_radius ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry .portfolio-entry-inner{border-radius:'. $item_border_radius .';overflow: hidden;}';
					}

					// Add border
					if ( ! empty( $item_border_width ) ) {
						$border_css .= 'border-width:' . $item_border_width .';';
						if ( ! empty( $item_border_style ) && 'none' != $item_border_style ) {
							$border_css .= 'border-style:' . $item_border_style .';';
						}
						if ( ! empty( $item_border_color ) ) {
							$border_css .= 'border-color:' . $item_border_color .';';
						}
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry .portfolio-entry-inner{'. $border_css .'}';
					}

					// Add background color
					if ( ! empty( $item_bg ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry .portfolio-entry-inner{background-color:'. $item_bg .';}';
					}

					// Add outside content background color
					if ( 'outside' == $title_cat_position ) {
						if ( ! empty( $outside_content_padding ) && '25px' != $outside_content_padding ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-content{padding:'. $outside_content_padding .';}';
						}
						
						if ( ! empty( $outside_content_bg ) && '#f9f9f9' != $outside_content_bg ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .triangle-wrap{border-bottom-color:'. $outside_content_bg .';}';
							$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-content{background-color:'. $outside_content_bg .';}';
						}
					}

					// Add title color
					if ( 'on' == $title ) {
						if ( ! empty( $title_color ) ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry-title a, #portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .portfolio-entry-title a{color:'. $title_color .';}';
						}

						if ( ! empty( $title_hover_color ) ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry-title a:hover, #portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .portfolio-entry-title a:hover{color:'. $title_hover_color .';}';
						}
					}

					// Add category color
					if ( 'on' == $category ) {
						if ( ! empty( $category_color ) ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .categories, #portfolio-'. $id .'.portfolio-entries .categories a, #portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories, #portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories a{color:'. $category_color .';}';
						}

						if ( ! empty( $category_hover_color ) ) {
							$css .= '#portfolio-'. $id .'.portfolio-entries .categories a:hover, #portfolio-'. $id .'.portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories a:hover{color:'. $category_hover_color .';}';
						}
					}

					// Add filter font family
					if ( ! empty( $filter_font_family ) ) {
						$filter_typo_css .= 'font-family:'. $filter_font_family .';';
					}

					// Add filter font size
					if ( ! empty( $filter_font_size ) ) {
						$filter_typo_css .= 'font-size:'. $filter_font_size .';';
					}

					// Add filter font weight
					if ( ! empty( $filter_font_weight ) ) {
						$filter_typo_css .= 'font-weight:'. $filter_font_weight .';';
					}

					// Add filter font style
					if ( ! empty( $filter_font_style ) ) {
						$filter_typo_css .= 'font-style:'. $filter_font_style .';';
					}

					// Add filter text transform
					if ( ! empty( $filter_text_transform ) ) {
						$filter_typo_css .= 'text-transform:'. $filter_text_transform .';';
					}

					// Add filter line height
					if ( ! empty( $filter_line_height ) ) {
						$filter_typo_css .= 'line-height:'. $filter_line_height .';';
					}

					// Add filter letter spacing
					if ( ! empty( $filter_letter_spacing ) ) {
						$filter_typo_css .= 'letter-spacing:'. $filter_letter_spacing .';';
					}

					// Filter typography css
					if ( ! empty( $filter_typo_css ) ) {
						$css .= '.portfolio-entries .portfolio-filters li a{'. $filter_typo_css .'}';
					}

					// Add title font family
					if ( ! empty( $title_font_family ) ) {
						$title_typo_css .= 'font-family:'. $title_font_family .';';
					}

					// Add title font size
					if ( ! empty( $title_font_size ) ) {
						$title_typo_css .= 'font-size:'. $title_font_size .';';
					}

					// Add title font weight
					if ( ! empty( $title_font_weight ) ) {
						$title_typo_css .= 'font-weight:'. $title_font_weight .';';
					}

					// Add title font style
					if ( ! empty( $title_font_style ) ) {
						$title_typo_css .= 'font-style:'. $title_font_style .';';
					}

					// Add title text transform
					if ( ! empty( $title_text_transform ) ) {
						$title_typo_css .= 'text-transform:'. $title_text_transform .';';
					}

					// Add title line height
					if ( ! empty( $title_line_height ) ) {
						$title_typo_css .= 'line-height:'. $title_line_height .';';
					}

					// Add title letter spacing
					if ( ! empty( $title_letter_spacing ) ) {
						$title_typo_css .= 'letter-spacing:'. $title_letter_spacing .';';
					}

					// Title typography css
					if ( ! empty( $title_typo_css ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .portfolio-entry-title{'. $title_typo_css .'}';
					}

					// Add category font family
					if ( ! empty( $cat_font_family ) ) {
						$cat_typo_css .= 'font-family:'. $cat_font_family .';';
					}

					// Add category font size
					if ( ! empty( $cat_font_size ) ) {
						$cat_typo_css .= 'font-size:'. $cat_font_size .';';
					}

					// Add category font weight
					if ( ! empty( $cat_font_weight ) ) {
						$cat_typo_css .= 'font-weight:'. $cat_font_weight .';';
					}

					// Add category font style
					if ( ! empty( $cat_font_style ) ) {
						$cat_typo_css .= 'font-style:'. $cat_font_style .';';
					}

					// Add category text transform
					if ( ! empty( $cat_text_transform ) ) {
						$cat_typo_css .= 'text-transform:'. $cat_text_transform .';';
					}

					// Add category line height
					if ( ! empty( $cat_line_height ) ) {
						$cat_typo_css .= 'line-height:'. $cat_line_height .';';
					}

					// Add category letter spacing
					if ( ! empty( $cat_letter_spacing ) ) {
						$cat_typo_css .= 'letter-spacing:'. $cat_letter_spacing .';';
					}

					// Category typography css
					if ( ! empty( $cat_typo_css ) ) {
						$css .= '#portfolio-'. $id .'.portfolio-entries .categories{'. $cat_typo_css .'}';
					}

					// Add tablet item margin
					if ( ! empty( $tablet_item_margin ) ) {
						$css .= '@media (max-width: 1023px) {#portfolio-'. $id .' {margin: 0 -'. $tablet_item_margin .';}}';
						$css .= '@media (max-width: 1023px) {#portfolio-'. $id .' .portfolio-entry{padding:'. $tablet_item_margin .';}}';
					}

					// Add tablet padding
					if ( ! empty( $tablet_item_padding ) ) {
						$tablet_css .= 'padding:'. $tablet_item_padding .';';
					}

					// Add tablet border radius
					if ( ! empty( $tablet_item_border_radius ) ) {
						$tablet_css .= 'border-radius:'. $tablet_item_border_radius .';overflow: hidden;';
					}

					// Add tablet border
					if ( ! empty( $item_border_width ) && ! empty( $tablet_item_border_width ) ) {
						$tablet_css .= 'border-width:' . $tablet_item_border_width .';';
					}

					// Tablet css
					if ( ! empty( $tablet_css ) ) {
						$css .= '@media (max-width: 1023px) {#portfolio-'. $id .'.portfolio-entries .portfolio-entry .portfolio-entry-inner{'. $tablet_css .'}}';
					}

					// Add tablet filter font size
					if ( ! empty( $tablet_filter_font_size ) ) {
						$tablet_filter_typo_css .= 'font-size:'. $tablet_filter_font_size .';';
					}

					// Add tablet filter text transform
					if ( ! empty( $tablet_filter_text_transform ) ) {
						$tablet_filter_typo_css .= 'text-transform:'. $tablet_filter_text_transform .';';
					}

					// Add tablet filter line height
					if ( ! empty( $tablet_filter_line_height ) ) {
						$tablet_filter_typo_css .= 'line-height:'. $tablet_filter_line_height .';';
					}

					// Add tablet filter letter spacing
					if ( ! empty( $tablet_filter_letter_spacing ) ) {
						$tablet_filter_typo_css .= 'letter-spacing:'. $tablet_filter_letter_spacing .';';
					}

					// Tablet Typo css
					if ( ! empty( $tablet_filter_typo_css ) ) {
						$css .= '@media (max-width: 1023px) {.portfolio-entries .portfolio-filters li a{'. $tablet_filter_typo_css .'}}';
					}

					// Add tablet title font size
					if ( ! empty( $tablet_title_font_size ) ) {
						$tablet_title_typo_css .= 'font-size:'. $tablet_title_font_size .';';
					}

					// Add tablet title text transform
					if ( ! empty( $tablet_title_text_transform ) ) {
						$tablet_title_typo_css .= 'text-transform:'. $tablet_title_text_transform .';';
					}

					// Add tablet title line height
					if ( ! empty( $tablet_title_line_height ) ) {
						$tablet_title_typo_css .= 'line-height:'. $tablet_title_line_height .';';
					}

					// Add tablet title letter spacing
					if ( ! empty( $tablet_title_letter_spacing ) ) {
						$tablet_title_typo_css .= 'letter-spacing:'. $tablet_title_letter_spacing .';';
					}

					// Tablet Typo css
					if ( ! empty( $tablet_title_typo_css ) ) {
						$css .= '@media (max-width: 1023px) {.portfolio-entries .portfolio-entry-title{'. $tablet_title_typo_css .'}}';
					}

					// Add tablet category font size
					if ( ! empty( $tablet_cat_font_size ) ) {
						$tablet_cat_typo_css .= 'font-size:'. $tablet_cat_font_size .';';
					}

					// Add tablet category text transform
					if ( ! empty( $tablet_cat_text_transform ) ) {
						$tablet_cat_typo_css .= 'text-transform:'. $tablet_cat_text_transform .';';
					}

					// Add tablet category line height
					if ( ! empty( $tablet_cat_line_height ) ) {
						$tablet_cat_typo_css .= 'line-height:'. $tablet_cat_line_height .';';
					}

					// Add tablet category letter spacing
					if ( ! empty( $tablet_cat_letter_spacing ) ) {
						$tablet_cat_typo_css .= 'letter-spacing:'. $tablet_cat_letter_spacing .';';
					}

					// Tablet category typography css
					if ( ! empty( $tablet_cat_typo_css ) ) {
						$css .= '@media (max-width: 1023px) {#portfolio-'. $id .'.portfolio-entries .categories{'. $tablet_cat_typo_css .'}}';
					}

					// Add mobile item margin
					if ( ! empty( $mobile_item_margin ) ) {
						$css .= '@media (max-width: 767px) {#portfolio-'. $id .' {margin: 0 -'. $mobile_item_margin .';}}';
						$css .= '@media (max-width: 767px) {#portfolio-'. $id .' .portfolio-entry{padding:'. $mobile_item_margin .';}}';
					}

					// Add mobile padding
					if ( ! empty( $mobile_item_padding ) ) {
						$mobile_css .= 'padding:'. $mobile_item_padding .';';
					}

					// Add mobile border radius
					if ( ! empty( $mobile_item_border_radius ) ) {
						$mobile_css .= 'border-radius:'. $mobile_item_border_radius .';overflow: hidden;';
					}

					// Add mobile border
					if ( ! empty( $item_border_width ) && ! empty( $mobile_item_border_width ) ) {
						$mobile_css .= 'border-width:' . $mobile_item_border_width .';';
					}

					// mobile css
					if ( ! empty( $mobile_css ) ) {
						$css .= '@media (max-width: 767px) {#portfolio-'. $id .'.portfolio-entries .portfolio-entry .portfolio-entry-inner{'. $mobile_css .'}}';
					}

					// Add mobile filter font size
					if ( ! empty( $mobile_filter_font_size ) ) {
						$mobile_filter_typo_css .= 'font-size:'. $mobile_filter_font_size .';';
					}

					// Add mobile filter text transform
					if ( ! empty( $mobile_filter_text_transform ) ) {
						$mobile_filter_typo_css .= 'text-transform:'. $mobile_filter_text_transform .';';
					}

					// Add mobile filter line height
					if ( ! empty( $mobile_filter_line_height ) ) {
						$mobile_filter_typo_css .= 'line-height:'. $mobile_filter_line_height .';';
					}

					// Add mobile filter letter spacing
					if ( ! empty( $mobile_filter_letter_spacing ) ) {
						$mobile_filter_typo_css .= 'letter-spacing:'. $mobile_filter_letter_spacing .';';
					}

					// Mobile typo css
					if ( ! empty( $mobile_filter_typo_css ) ) {
						$css .= '@media (max-width: 767px) {.portfolio-entries .portfolio-filters li a{'. $mobile_filter_typo_css .'}}';
					}

					// Add mobile title font size
					if ( ! empty( $mobile_title_font_size ) ) {
						$mobile_title_typo_css .= 'font-size:'. $mobile_title_font_size .';';
					}

					// Add mobile title text transform
					if ( ! empty( $mobile_title_text_transform ) ) {
						$mobile_title_typo_css .= 'text-transform:'. $mobile_title_text_transform .';';
					}

					// Add mobile title line height
					if ( ! empty( $mobile_title_line_height ) ) {
						$mobile_title_typo_css .= 'line-height:'. $mobile_title_line_height .';';
					}

					// Add mobile title letter spacing
					if ( ! empty( $mobile_title_letter_spacing ) ) {
						$mobile_title_typo_css .= 'letter-spacing:'. $mobile_title_letter_spacing .';';
					}

					// Mobile typo css
					if ( ! empty( $mobile_title_typo_css ) ) {
						$css .= '@media (max-width: 767px) {#portfolio-'. $id .'.portfolio-entries .portfolio-entry-title{'. $mobile_title_typo_css .'}}';
					}

					// Add mobile category font size
					if ( ! empty( $mobile_cat_font_size ) ) {
						$mobile_cat_typo_css .= 'font-size:'. $mobile_cat_font_size .';';
					}

					// Add mobile category text transform
					if ( ! empty( $mobile_cat_text_transform ) ) {
						$mobile_cat_typo_css .= 'text-transform:'. $mobile_cat_text_transform .';';
					}

					// Add mobile category line height
					if ( ! empty( $mobile_cat_line_height ) ) {
						$mobile_cat_typo_css .= 'line-height:'. $mobile_cat_line_height .';';
					}

					// Add mobile category letter spacing
					if ( ! empty( $mobile_cat_letter_spacing ) ) {
						$mobile_cat_typo_css .= 'letter-spacing:'. $mobile_cat_letter_spacing .';';
					}

					// Mobile category typography css
					if ( ! empty( $mobile_cat_typo_css ) ) {
						$css .= '@media (max-width: 767px) {#portfolio-'. $id .'.portfolio-entries .categories{'. $mobile_cat_typo_css .'}}';
					}

					if ( ! empty( $css ) ) { ?>
						<style type="text/css"><?php echo wp_strip_all_tags( oceanwp_minify_css( $css ) ); ?></style>
					<?php
					} ?>

				</div><!-- .portfolio-entries -->

			<?php
			// End post check
			endif;

		}

		/**
		 * Registers the function as a shortcode
		 */
		public function portfolio_shortcode( $atts, $content = null ) {

			// Attributes
			$atts = shortcode_atts( array(
				'id' => '',
			), $atts, 'oceanwp_portfolio' );

			ob_start();
			
			if ( $atts[ 'id' ] ) {
				$this->portfolio_display( $atts[ 'id' ] );
			}
			
			return ob_get_clean();

		}

	}

}
new OceanWP_Portfolio_Shortcode();