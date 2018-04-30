<?php
namespace ElementPack\Modules\TestimonialSlider\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Testimonial_Slider extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-testimonial-slider';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Slider', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => esc_html__( 'Source', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bdthemes-testimonial',
				'options' => [
					'jetpack-testimonial'  => esc_html__( 'Jetpack', 'bdthemes-element-pack' ),
					'bdthemes-testimonial' => esc_html__( 'BdThemes', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'posts',
			[
				'label' => esc_html__( 'Posts Per Page', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'     => esc_html__( 'Date', 'bdthemes-element-pack' ),
					'title'    => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'category' => esc_html__( 'Category', 'bdthemes-element-pack' ),
					'rand'     => esc_html__( 'Random', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => esc_html__( 'Descending', 'bdthemes-element-pack' ),
					'ASC'  => esc_html__( 'Ascending', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'thumb',
			[
				'label'     => esc_html__( 'Testimonial Image', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'company_name',
			[
				'label'     => esc_html__( 'Company Name/Address', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
				'condition' => [
					'source' => 'bdthemes-testimonial',
				],
			]
		);

		$this->add_control(
			'rating',
			[
				'label'     => esc_html__( 'Rating', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
				'condition' => [
					'source' => 'bdthemes-testimonial',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_slider_settins',
			[
				'label' => esc_html__( 'Slider Settings', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => esc_html__( 'Auto Play', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'no',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'     => esc_html__( 'Loop', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);


		$this->add_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'arrows',
				'options' => [
					'both'   => esc_html__( 'Arrows and Dots', 'bdthemes-element-pack' ),
					'arrows' => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
					'dots'   => esc_html__( 'Dots', 'bdthemes-element-pack' ),
					'none'   => esc_html__( 'None', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_style',
			[
				'label' => esc_html__( 'Style', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-meta .bdt-testimonial-title' => 'color: {{VALUE}};',
				],
				'condition' => ['title' => 'yes'],
			]
		);

		$this->add_control(
			'address_color',
			[
				'label' => esc_html__( 'Company Name/Address Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-meta .bdt-testimonial-address' => 'color: {{VALUE}};',
				],
				'condition' => [
					'company_name' => 'yes',
					'source' => 'bdthemes-testimonial',
				],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label'     => esc_html__( 'Rating Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e7e7e7',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .bdt-rating .bdt-rating-item' => 'color: {{VALUE}};',
				],
				'condition' => [
					'rating' => 'yes',
					'source' => 'bdthemes-testimonial',
				],
			]
		);

		$this->add_control(
			'active_rating_color',
			[
				'label' => esc_html__( 'Active Rating Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default'   => '#FFCC00',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .bdt-rating.bdt-rating-1 .bdt-rating-item:nth-child(1)'            => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-slider .bdt-rating.bdt-rating-2 .bdt-rating-item:nth-child(-n+2)'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-slider .bdt-rating.bdt-rating-3 .bdt-rating-item:nth-child(-n+3)'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-slider .bdt-rating.bdt-rating-4 .bdt-rating-item:nth-child(-n+4)'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-slider .bdt-rating.bdt-rating-5 .bdt-rating-item:nth-child(-n+5)'         => 'color: {{VALUE}};',
				],
				'condition' => [
					'rating' => 'yes',
					'source' => 'bdthemes-testimonial',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label' => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_style',
			[
				'label' => esc_html__( 'Arrows Style', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => [
					'dark' => esc_html__( 'Dark', 'bdthemes-element-pack' ),
					'light' => esc_html__( 'Light', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => esc_html__( 'Arrows Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-testimonial-slider .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Arrows Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 25,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .swiper-button-prev, {{WRAPPER}} .bdt-testimonial-slider .swiper-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_dots',
			[
				'label' => esc_html__( 'Dots', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => __( 'Dots Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'active_dot_color',
			[
				'label' => __( 'Active Dot Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => esc_html__( 'Dots Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -80,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .swiper-pagination-bullets' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => esc_html__( 'Dots Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-slider .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render_loop_header() {
		?>
		<div class="bdt-testimonial-slider">
			<div class="swiper-container">
				<div class="swiper-wrapper">
		<?php
	}

	protected function render_loop_footer() {
		?>
				</div>
			</div>

			<?php if ( 'none' !== $this->get_settings('navigation') ) : ?>
				<?php if ( 'arrows' !== $this->get_settings('navigation') ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
				
				<?php if ( 'dots' !== $this->get_settings('navigation') ) : 
					$nav_style = ($this->get_settings('arrows_style') == 'light') ? 'swiper-button-white' : 'swiper-button-black'; 
				?>
					<div class="swiper-button-next <?php echo esc_attr($nav_style); ?>"></div>
					<div class="swiper-button-prev <?php echo esc_attr($nav_style); ?>"></div>
				<?php endif; ?>
			<?php endif; ?>

		</div>

		<script>
			jQuery(document).ready(function($) {
			    "use strict";				    
			    var swiper = new Swiper(".elementor-element-<?php echo esc_attr($this->get_id());?> .swiper-container", {
			        "pagination": ".elementor-element-<?php echo esc_attr($this->get_id());?> .swiper-pagination",
			        "paginationClickable": true,
			        "nextButton": ".elementor-element-<?php echo esc_attr($this->get_id());?> .swiper-button-next",
			        "prevButton": ".elementor-element-<?php echo esc_attr($this->get_id());?> .swiper-button-prev",
			        "centeredSlides": true,
			        "autoplay": <?php echo ($this->get_settings('autoplay') == 'yes') ? $this->get_settings('autoplay_speed') : 'false'; ?>,
			        "loop": <?php echo ($this->get_settings('loop') == 'yes') ? 'true' : 'false'; ?>,
    				"autoplayDisableOnInteraction": false,
			        "slidesPerView": 'auto',
			    });
			});
		</script>

		<?php
	}

	public function query_posts() {

		$settings = $this->get_settings();

		$args = array(
			'post_type'      => $settings['source'],
			'posts_per_page' => $settings['posts'],
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'post_status'    => 'publish'
		);

		$this->_query = new \WP_Query( $args );
	}

	public function get_query() {
		return $this->_query;
	}

	public function render() {
		$settings  = $this->get_settings();

		$this->query_posts();

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}
			$this->render_loop_header();
		?>
			<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

		  		<div class="swiper-slide">

                	<div class="bdt-testimonial-text"><?php the_excerpt(); ?></div>
                	
                		<div class="bdt-flex bdt-flex-center bdt-flex-middle">

	                    <?php if('yes' == $settings['thumb']) : ?>
                        	<div class="bdt-testimonial-thumb"><?php echo  the_post_thumbnail('medium', array('class' => ''));  ?></div>
	                    <?php endif ?>

	                    <?php if (('yes' == $settings['title']) or ('bdthemes-testimonial' == $settings['source'])) : ?>
		                    <?php if (('yes' == $settings['title']) or ('yes' == $settings['company_name']) or ('yes' == $settings['rating'])) : ?>
							    <div class="bdt-testimonial-meta">
			                        <?php if ('yes' == $settings['title']) : ?>
			                            <div class="bdt-testimonial-title"><?php echo esc_attr(get_the_title()); ?></div>
			                        <?php endif ?>

									<?php if ( 'bdthemes-testimonial' == $settings['source']) : ?>
				                        <?php if ( 'yes' == $settings['company_name']) : ?>
				                        	<?php $separator = (( 'yes' == $settings['title'] ) and ( 'yes' == $settings['company_name'] )) ? ', ' : ''?>
				                            <span class="bdt-testimonial-address"><?php echo esc_attr( $separator ).get_post_meta(get_the_ID(), 'bdthemes_tm_company_name', true); ?></span>
				                        <?php endif ?>
				                        
				                        <?php if ('yes' == $settings['rating']) : ?>
				                            <ul class="bdt-rating bdt-rating-<?php echo get_post_meta(get_the_ID(), 'bdthemes_tm_rating', true); ?> bdt-grid bdt-grid-collapse">
							                    <li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></span></li>
												<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></span></li>
												<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></span></li>
												<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></span></li>
												<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></span></li>
							                </ul>
				                        <?php endif ?>
									<?php endif ?>

			                    </div>
			                <?php endif ?>
		                <?php endif ?>

	                </div>
                </div>
		  
			<?php endwhile;
			wp_reset_postdata();
			
		$this->render_loop_footer();
	}
}
