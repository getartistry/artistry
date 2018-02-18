<?php

/**
 * Divi Children Custom Post Template.
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


get_header();


$post_layout_elements_defaults = array(
	array(
		'element' 		=> 'title',
		'field'			=> '',
		'field_value'	=> '',
	),
	array(
		'element' 		=> 'meta',
		'field'			=> '',
		'field_value'	=> '',
	),
	array(
		'element'		=> 'featured',
		'field'			=> '',
		'field_value'	=> '',
	),
	array(
		'element'		=> 'content',
		'field'			=> '',
		'field_value'	=> '',
	),
	array(
		'element'		=> 'comments',
		'field'			=> '',
		'field_value'	=> '',
	),
);
$post_layout_elements = get_theme_mod( 'dce_post_layout_elements', $post_layout_elements_defaults );


if ( 'et_full_width_page' === get_post_meta( get_the_ID(), '_et_pb_page_layout', true ) ) {
		$full_width_post = true;
		$container_class = ' dce-fullwidth';
	} else {
		$container_class = ' dce-sidebar';
}


?>

				
<div id="main-content">
	<div id="dce-custom-post" class="container<?php echo $container_class; ?>">
		<div id="content-area" class="clearfix">
			<div id="left-area">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

					<?php

					// $last_key = count( $post_layout_elements ) - 1; // Could be used to close open html tags etc

					$hero_section_open = false;
					$img_section_open = false;

					foreach ( $post_layout_elements as $key => $post_layout_element ) {

						$element = $post_layout_element['element'];
						$field = $post_layout_element['field'];
						$field_value = $post_layout_element['field_value'];


						if ( 'transparent' === $field ) {
								$section_class = 'et_pb_section et_section_transparent';
							} else {
								$section_class = 'et_pb_section';
						}
						

						if ( 'hero_begins' === $element ) {
							if ( ( 'featured' === $field ) OR ( 'featured-parallax' === $field ) ) {
									$image_url = get_the_post_thumbnail_url();
								} elseif ( ( 'url' == $field ) OR ( 'url-parallax' == $field ) ) {
									$image_url = esc_url( $field_value );
							}
							if ( ( 'featured-parallax' === $field ) OR ( 'url-parallax' === $field ) ) {
									echo '<div class="et_pb_section dce_hero_image et_post_meta_wrapper">';
									echo '<div class="et_parallax_bg et_pb_parallax_css dce_hero_overlay" style="background-image: url(' . $image_url . ')"></div>';
								} else {
									echo '<div class="et_pb_section dce_hero_image et_post_meta_wrapper dce_hero_overlay" style="background-image: url(' . $image_url . ')">';
							}
							$hero_section_open = true;
						}


						if ( $hero_section_open AND ( 'hero_ends' === $element ) ) {
							echo '</div><!-- .dce_hero_image -->';
							$hero_section_open = false;
						}


						if ( ( 'image_begins' === $element ) ) {
							if ( ( 'featured' === $field ) OR ( 'featured-parallax' === $field ) ) {
									$image_url = get_the_post_thumbnail_url();
								} elseif ( ( 'url' == $field ) OR ( 'url-parallax' == $field ) ) {
									$image_url = esc_url( $field_value );
							}
							if ( ( 'featured-parallax' === $field ) OR ( 'url-parallax' === $field ) ) {
									echo '<div class="et_pb_section dce_background_image et_post_meta_wrapper">';
									echo '<div class="et_pb_row dce_backimg">';
									echo '<div class="et_parallax_bg et_pb_parallax_css dce_backimg_overlay" style="background-image: url(' . $image_url . ')"></div>';
								} else {
									echo '<div class="et_pb_section dce_background_image et_post_meta_wrapper">';
									echo '<div class="et_pb_row dce_backimg dce_backimg_overlay" style="background-image: url(' . $image_url . ')">';
							}
							$img_section_open = true;
						}


						if ( $img_section_open AND ( 'image_ends' === $element ) ) {
							echo '</div>';
							echo '</div><!-- .dce_background_image -->';
							$img_section_open = false;
						}


						if ( 'title' === $element ) {
							if ( ! $hero_section_open AND ! $img_section_open ) {
								echo '<div class="' . $section_class . ' dce_post_title et_post_meta_wrapper">';
							}
								?>
								<div class="et_pb_row dce_row">
									<h1 class="entry-title dce-post-title"><?php the_title(); ?></h1>
								</div> <!-- .et_pb_row -->
								<?php
							if ( ! $hero_section_open AND ! $img_section_open ) {
								echo '</div> <!-- .dce_post_title -->';
							}
						}


						if ( 'meta' === $element ) {
							if ( ! post_password_required() ) {
								if ( ! $hero_section_open AND ! $img_section_open ) {
									echo '<div class="' . $section_class . ' dce_post_meta et_post_meta_wrapper">';
								}
								?>
									<div class="et_pb_row dce_row">
										<?php et_divi_post_meta(); ?>
									</div> <!-- .et_pb_row -->
								<?php
								if ( ! $hero_section_open AND ! $img_section_open ) {
									echo '</div> <!-- .dce_post_meta -->';
								}
							}
						}


						if ( ( 'featured' === $element ) AND ( 'on' === et_get_option( 'divi_thumbnails', 'on' ) ) ) {
							if ( ! post_password_required() ) {
								$thumb = '';
								$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );
								$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
								$classtext = 'et_featured_image';
								$titletext = get_the_title();
								$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
								$thumb = $thumbnail["thumb"];
								if ( '' !== $thumb ) {
									?>
									<div class="et_pb_section dce_featured_image">
										<div class="et_pb_row  dce_row">
											<?php
											print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
											?>
										</div> <!-- .et_pb_row -->
									</div> <!-- .dce_featured_image -->
									<?php
								}
							}
						}


						if ( 'content' === $element ) {
							?>
							<div class="entry-content">
								<div class="<?php echo $section_class; ?> dce_post_content">
									<div class="et_pb_row  dce_row">
										<?php
										do_action( 'et_before_content' );
										the_content();
										wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
										?>
									</div> <!-- .et_pb_row -->
								</div> <!-- .dce_post_content -->	
							</div> <!-- .entry-content -->
							<?php
						}


						if ( 'shortcode' === $element ) {
							if ( 'shortcode' === $field ) {
							?>
							<div class="et_pb_section dce_post_shortcode">
								<div class="et_pb_row  dce_row">
									<?php
									echo do_shortcode( $field_value );
									?>
								</div> <!-- .et_pb_row -->
							</div> <!-- .dce_post_shortcode -->
							<?php
							}
						}


						if ( 'code' === $element ) {
							if ( 'code' === $field ) {
							?>
							<div class="et_pb_section dce_post_code">
								<div class="et_pb_row dce_row">
									<?php
									// echo esc_html( $field_value );
									echo $field_value;
									?>
								</div> <!-- .et_pb_row -->
							</div> <!-- .dce_post_code -->
							<?php
							}
						}


						if ( 'code-fw' === $element ) {
							if ( 'code' === $field ) {
							?>
							<div class="et_pb_section dce_post_code">
								<?php
								echo $field_value;
								?>
							</div> <!-- .dce_post_code -->
							<?php
							}
						}


						if ( 'divi_layout' === $element ) {
							if ( 'layout_name' === $field ) {
								$layout = get_page_by_title( $field_value, ARRAY_A, 'et_pb_layout' );
								$layout_content = $layout['post_content'];
								// If layout content enclosed in paragraph, remove opening and closing p tags:
								$pos_open = strpos( $layout_content , '<p>' );
								$pos_close = strrpos( $layout_content , '</p>' );
								$length = strlen( $layout_content );
								if( 0 === $pos_open ) {
									$layout_content = substr( $layout_content, 3 );
								}
								if( $pos_close == $length - 4 ) {
									$layout_content = substr( $layout_content, 0, $pos_close - 3 );
								}
								?>
								<div class="dce_post_divi_layout">
									<?php
									echo do_shortcode( $layout_content );
									?>
								</div> <!-- .dce_post_divi_layout -->
								<?php
							}
						}


						if ( 'spacer' === $element ) {
							?>
							<div class="et_pb_section dce_spacer"></div>
							<?php
						}


						if ( 'comments' === $element ) {
							if ( ( comments_open() || get_comments_number() ) && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) ) {
								?>
								<div class="<?php echo $section_class; ?> dce_post_comments">
									<div class="et_pb_row  dce_row">
										<?php
										comments_template( '', true );
										?>
									</div> <!-- .et_pb_row -->
								</div> <!-- .dce_post_comments -->
								<?php
							}
						}


						if ( '468x60' === $element ) {
							if ( et_get_option('divi_468_enable') == 'on' ) {
								?>
								<div class="et_post_meta_wrapper">
									<div class="et-single-post-ad">
									<?php
									if ( et_get_option( 'divi_468_adsense' ) <> '' ) {
											echo( et_get_option('divi_468_adsense') );
										} else {
											?>
											<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
											<?php
									}
									?>
									</div> <!-- .et-single-post-ad -->
								</div> <!-- .et_post_meta_wrapper -->
								<?php
							}
						}


						// if ( $last_key != $key ) {
						// 		 // Could be used to close open html tags etc
						// 	} else {
						// 		 // Could be used to close open html tags etc
						// }


					}


					if ( et_get_option('divi_integration_single_bottom') <> '' && et_get_option( 'divi_integrate_singlebottom_enable')  == 'on' ) {
						echo( et_get_option( 'divi_integration_single_bottom' ) );
					}
					?>
				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>