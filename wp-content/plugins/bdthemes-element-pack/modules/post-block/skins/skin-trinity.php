<?php
namespace ElementPack\Modules\PostBlock\Skins;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Trinity extends Elementor_Skin_Base {

	public function get_id() {
		return 'trinity';
	}

	public function get_title() {
		return __( 'Trinity', 'bdthemes-element-pack' );
	}

	public function render() {
		$settings = $this->parent->get_settings();
		
		global $post;
		$id      = uniqid('bdtpbm_');
		$classes = ['bdt-post-block', 'bdt-post-block-skin-trinity'];

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

			<div id="<?php echo esc_attr($id); ?>" class="<?php echo \element_pack_helper::acssc($classes); ?>">

		  		<div class="bdt-post-block-items bdt-child-width-1-<?php echo $settings['featured_item']; ?>@m bdt-grid-<?php echo esc_attr($settings['trinity_column_gap']); ?>" bdt-grid>
					<?php
					while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

						<?php $post_thumbnail  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
			  			<div class="bdt-post-block-item featured-part">
				  			<div class="bdt-post-block-thumbnail-wrap bdt-position-relative">
				  				<div class="bdt-post-block-thumbnail">
				  					<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
					  					<img src="<?php echo esc_url($post_thumbnail[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
					  				</a>
				  				</div>
				  				<div class="bdt-overlay-primary bdt-position-cover"></div>
						  		<div class="bdt-post-block-desc bdt-text-center bdt-position-center bdt-position-medium bdt-position-z-index">
									<?php if ('yes' == $settings['featured_show_tag']) : ?>
										<div class="bdt-post-block-tag-wrap">
					                		<?php
											$tags_list = get_the_tag_list( '<span class="bdt-background-primary">', '</span> <span class="bdt-background-primary">', '</span>');
						                		if ($tags_list) :
						                    		echo  wp_kses_post($tags_list);
						                		endif; ?>
					                	</div>
									<?php endif ?>

									<?php if ('yes' == $settings['featured_show_title']) : ?>
										<h4 class="bdt-post-block-title bdt-margin-small-top">
											<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-post-block-link" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()) ; ?></a>
										</h4>
									<?php endif ?>

					            	<?php if ('yes' == $settings['featured_show_category'] or 'yes' == $settings['featured_show_date']) : ?>

										<div class="bdt-post-block-meta bdt-flex-center bdt-subnav bdt-subnav-divider">
											<?php if ('yes' == $settings['featured_show_category']) : ?>
												<?php echo '<span>'.get_the_category_list(', ').'</span>'; ?>
											<?php endif ?>

											<?php if ('yes' == $settings['featured_show_date']) : ?>
												<?php echo '<span>'.esc_attr(get_the_date('d F Y')).'</span>'; ?>
											<?php endif ?>
										</div>

									<?php endif ?>
						  		</div>
							</div>
						</div>

					<?php endwhile;

					remove_filter( 'excerpt_length', [ $this->parent, 'filter_excerpt_length' ], 20 );
					remove_filter( 'excerpt_more', [ $this->parent, 'filter_excerpt_more' ], 20 );
					wp_reset_postdata(); ?>

				</div>
			</div>
 		<?php endif;
	}
}

