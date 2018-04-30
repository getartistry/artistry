<?php
namespace ElementPack\Modules\TestimonialCarousel\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Vyxo extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-vyxo';
	}

	public function get_title() {
		return __( 'Vyxo', 'bdthemes-element-pack' );
	}


	public function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-testimonial-carousel/section_style_text/after_section_start', [ $this, 'register_vyxo_style_controls'   ] );

	}

	public function register_vyxo_style_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'text_background_color',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-text-wrap',
				'separator' => 'after',
			]
		);
	}

	public function render() {
		$id       = $this->parent->get_id();
		$settings = $this->parent->get_settings();

		global $post;

		$args = array(
			'post_type'      => $settings['source'],
			'posts_per_page' => $settings['posts'],
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'post_status'    => 'publish'
		);

		$wp_query = new \WP_Query($args);

		if( $wp_query->have_posts() ) : ?>

			<div id="<?php echo esc_attr($id); ?>" class="bdt-testimonial-carousel bdt-testimonial-carousel-skin-vyxo">
				<div class="swiper-container">
					<div class="swiper-wrapper" bdt-height-match="target: .bdt-testimonial-carousel-text-wrap">

						<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					  		<div class="swiper-slide bdt-testimonial-carousel-item bdt-text-center">
						  		<div class="bdt-testimonial-carousel-text-wrap bdt-padding bdt-background-primary">
					            	<?php $this->parent->render_excerpt(); ?>
						  		</div>
						  		<div class="bdt-testimonial-carousel-item-wrapper">
							  		<div class="testimonial-item-header bdt-position-top-center">
							  			<?php $this->parent->render_image( $post->ID ); ?>
						            </div>

					            	<?php
					            	$this->parent->render_title( $post->ID );
									$this->parent->render_address( $post->ID );

			                        if (( 'yes' == $settings['show_rating'] ) && ( 'yes' == $settings['show_text'] )) : ?>
				                    	<div class="bdt-testimonial-carousel-rating bdt-display-inline-block">
										    <?php $this->parent->render_rating( $post->ID ); ?>
						                </div>
			                        <?php endif; ?>

				                </div>
			                </div>
						<?php endwhile;
						wp_reset_postdata(); ?>

					</div>
				</div>

		        <?php
		        if ( 'none' !== $settings['navigation'] ) :
					$this->parent->render_pagination();
					$this->parent->render_navigation();
				endif;
				?>
			    
			</div>

		 	<?php $this->parent->render_script($id);
		 	
		endif;
	}
}

