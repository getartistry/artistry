<?php
namespace ElementPack\Modules\PostBlock\Skins;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Genesis extends Elementor_Skin_Base {

	public function get_id() {
		return 'genesis';
	}

	public function get_title() {
		return __( 'Genesis', 'bdthemes-element-pack' );
	}

	public function render() {
		$settings = $this->parent->get_settings();
		
		global $post;
		$id      = uniqid('bdtpbm_');
		$classes = ['bdt-post-block', 'bdt-grid', 'bdt-grid-match', 'bdt-post-block-skin-genesis'];

		$animation = ($settings['read_more_hover_animation']) ? ' elementor-animation-'.$settings['read_more_hover_animation'] : '';
		$bdt_list_divider = ( $settings['show_list_divider'] ) ? ' bdt-has-divider' : '';

		$args = array(
			'posts_per_page' => $settings['posts_limit'],
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'post_status'    => 'publish'
		);
		
		if ( 'by_name' === $settings['source'] ) :
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $settings['post_categories'],
			);
		endif;

		$wp_query = new \WP_Query($args);

		if( $wp_query->have_posts() ) :

			add_filter( 'excerpt_more', [ $this->parent, 'filter_excerpt_more' ], 20 );
			add_filter( 'excerpt_length', [ $this->parent, 'filter_excerpt_length' ], 20 );

		?> 

			<div id="<?php echo esc_attr($id); ?>" class="<?php echo \element_pack_helper::acssc($classes); ?>" bdt-grid>

				<?php $bdt_count = 0;
			
				while ( $wp_query->have_posts() ) : $wp_query->the_post();

					$bdt_count++;
					$top_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>

				  	<?php if( $bdt_count <= $settings['featured_item']) : ?>

				  		<div class="bdt-width-1-<?php echo $settings['featured_item']; ?>@m">
				  			<div class="bdt-post-block-item featured-part">
								<div class="bdt-margin-bottom">
									<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
					  					<img src="<?php echo esc_url($top_thumbnail[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
					  				</a>
								</div>
						  		
						  		<div class="bdt-post-block-desc">

									<?php if ('yes' == $settings['featured_show_title']) : ?>
										<h4 class="bdt-post-block-title">
											<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-post-block-link" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()) ; ?></a>
										</h4>
									<?php endif ?>

	            	            	<?php if ('yes' == $settings['featured_show_category'] or 'yes' == $settings['featured_show_date']) : ?>

	            						<div class="bdt-post-block-meta bdt-subnav">
	            							<?php if ('yes' == $settings['featured_show_date']) : ?>
	            								<?php echo '<span>'.esc_attr(get_the_date('d F Y')).'</span>'; ?>
	            							<?php endif ?>

	            							<?php if ('yes' == $settings['featured_show_category']) : ?>
	            								<?php echo '<span>'.get_the_category_list(', ').'</span>'; ?>
	            							<?php endif ?>
	            							
	            						</div>

	            					<?php endif ?>

									<?php if ('yes' == $settings['featured_show_excerpt']) : ?>
										<div class="bdt-post-block-excerpt"><?php echo wp_kses_post(the_excerpt()); ?></div>
									<?php endif ?>

									<?php if ('yes' == $settings['featured_show_read_more']) : ?>
										<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-post-block-read-more bdt-link-reset<?php echo esc_attr($animation); ?>"><?php echo esc_html($settings['read_more_text']); ?>
											
											<?php if ($settings['icon']) : ?>
												<span class="bdt-post-block-read-more-icon-<?php echo esc_attr($settings['icon_align']); ?>">
													<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
												</span>
											<?php endif; ?>

										</a>
									<?php endif ?>

						  		</div>

							</div>
							
						</div>
					
					<?php if ($bdt_count == $settings['featured_item']) : ?>

			  		<div class="bdt-post-block-item list-part bdt-width-1-1@m bdt-margin-medium-top">
			  			<ul class="bdt-child-width-1-<?php echo $settings['featured_item']; ?>@m<?php echo esc_attr($bdt_list_divider); ?>" bdt-grid bdt-scrollspy="cls: bdt-animation-fade; target: > .bdt-post-block-item; delay: 300;">
			  		<?php endif; ?>

					<?php else : ?>
						<?php $post_thumbnail  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); ?>
					  			<li>
						  			<div class="bdt-flex">
						  				<div class="bdt-post-block-thumbnail bdt-width-auto">
						  					<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
							  					<img src="<?php echo esc_url($post_thumbnail[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
							  				</a>
						  				</div>
								  		<div class="bdt-post-block-desc bdt-width-expand bdt-margin-small-left">
											<?php if ('yes' == $settings['list_show_title']) : ?>
												<h4 class="bdt-post-block-title">
													<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-post-block-link" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()) ; ?></a>
												</h4>
											<?php endif ?>

							            	<?php if ('yes' == $settings['list_show_category'] or 'yes' == $settings['list_show_date']) : ?>

												<div class="bdt-post-block-meta bdt-subnav">
													<?php if ('yes' == $settings['list_show_date']) : ?>
														<?php echo '<span>'.esc_attr(get_the_date('d F Y')).'</span>'; ?>
													<?php endif ?>

													<?php if ('yes' == $settings['list_show_category']) : ?>
														<?php echo '<span>'.get_the_category_list(', ').'</span>'; ?>
													<?php endif ?>
													
												</div>

											<?php endif ?>
								  		</div>
									</div>
								</li>
					<?php endif; ?>
			  
				<?php endwhile; ?>
					</ul>
				</div>
			</div>
		
		 	<?php 
				remove_filter( 'excerpt_length', [ $this->parent, 'filter_excerpt_length' ], 20 );
				remove_filter( 'excerpt_more', [ $this->parent, 'filter_excerpt_more' ], 20 );

				wp_reset_postdata(); 
			?>

 		<?php endif;
	}
}

