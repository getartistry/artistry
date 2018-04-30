<?php
namespace ElementPack\Modules\TestimonialCarousel\Skins;
use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Twyla extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-twyla';
	}

	public function get_title() {
		return __( 'Twyla', 'bdthemes-element-pack' );
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

			<div id="<?php echo esc_attr($id); ?>" class="bdt-testimonial-carousel bdt-testimonial-carousel-skin-twyla">
				<div class="swiper-container">
					<div class="swiper-wrapper" bdt-height-match="target: > div > .bdt-testimonial-carousel-item-wrapper">

						<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					  		<div class="swiper-slide bdt-testimonial-carousel-item">
						  		<div class="bdt-testimonial-carousel-item-wrapper bdt-text-center">
							  		<div class="testimonial-item-header">
							  			<?php $this->parent->render_image( $post->ID ); ?>
						            </div>

					            	<?php
					            	$this->parent->render_excerpt();
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

