<?php
namespace ElementPack\Modules\TestimonialCarousel\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

use ElementPack\Modules\TestimonialCarousel\Skins;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Testimonial_Carousel extends Widget_Base {

	public function get_name() {
		return 'bdt-testimonial-carousel';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Twyla( $this ) );
		$this->add_skin( new Skins\Skin_Vyxo( $this ) );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'     => esc_html__( 'Testimonial Image', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_address',
			[
				'label'     => esc_html__( 'Address', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_text',
			[
				'label'     => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'text_limit',
			[
				'label'     => esc_html__( 'Text Limit', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 80,
				'condition' => [
					'show_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_rating',
			[
				'label'     => esc_html__( 'Rating', 'bdthemes-element-pack' ),
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
				'default' => 'both',
				'options' => [
					'both'   => esc_html__( 'Arrows and Dots', 'bdthemes-element-pack' ),
					'arrows' => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
					'dots'   => esc_html__( 'Dots', 'bdthemes-element-pack' ),
					'none'   => esc_html__( 'None', 'bdthemes-element-pack' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_query',
			[
				'label' => esc_html__( 'Query', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
				'default' => 10,
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_carousel_settings',
			[
				'label' => esc_html__( 'Carousel Settings', 'bdthemes-element-pack' ),
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
				'label'              => esc_html__( 'Autoplay Speed', 'bdthemes-element-pack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'   => esc_html__( 'Infinite Loop', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', 'bdthemes-element-pack' ),
					'no'  => esc_html__( 'No', 'bdthemes-element-pack' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'              => esc_html__( 'Animation Speed', 'bdthemes-element-pack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_item',
			[
				'label' => esc_html__( 'Item', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-item-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-item',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
		
		$this->add_control(
			'item_hover_border_color',
			[
				'label' => esc_html__( 'Hover Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-item:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_box_shadow_switcher',
			[
				'label' => __( 'Box Shadow', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
		    'item_box_shadow',
		    [
		        'label' => __( 'Box Shadow', 'your-plugin' ),
		        'type' => Controls_Manager::BOX_SHADOW,
		        'default' => [
		            'color' => 'rgba(0, 0, 0, 0.08)',
		            'blur' => 8,
		            'vertical' => 2,
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-item' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
		        ],
		        'condition' => [
		        	'item_box_shadow_switcher' => 'yes',
		        ]
		    ]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-item-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_gap',
			[
				'label'   => esc_html__( 'Item Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label'     => esc_html__( 'Image', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'image_border',
				'label'       => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-img-wrapper',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'image_hover_border_color',
			[
				'label' => esc_html__( 'Hover Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'image_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-img-wrapper:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-img-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_address',
			[
				'label'     => esc_html__( 'Address', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_address' => 'yes',
				],
			]
		);

		$this->add_control(
			'address_color',
			[
				'label' => esc_html__( 'Company Name/Address Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-address' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'address_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-address',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label'     => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_top_border_color',
			[
				'label' => esc_html__( 'Top Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-text' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'_skin' => '',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-testimonial-carousel .bdt-testimonial-carousel-text',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_rating',
			[
				'label'     => esc_html__( 'Rating', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e7e7e7',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-rating .bdt-rating-item' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'active_rating_color',
			[
				'label' => esc_html__( 'Active Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default'   => '#FFCC00',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-rating.bdt-rating-1 .bdt-rating-item:nth-child(1)'            => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-rating.bdt-rating-2 .bdt-rating-item:nth-child(-n+2)'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-rating.bdt-rating-3 .bdt-rating-item:nth-child(-n+3)'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-rating.bdt-rating-4 .bdt-rating-item:nth-child(-n+4)'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-testimonial-carousel .bdt-rating.bdt-rating-5 .bdt-rating-item:nth-child(-n+5)'         => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label'     => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label'     => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_style',
			[
				'label'   => esc_html__( 'Arrows Style', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => [
					'dark'  => esc_html__( 'Dark', 'bdthemes-element-pack' ),
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
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-testimonial-carousel .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
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
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 80,
					],
				],
				'default' => [
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .swiper-button-prev, {{WRAPPER}} .bdt-testimonial-carousel .swiper-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
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
				'default' => '#a2a2a2',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
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
				'default' => '#14ABF4',
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .bdt-testimonial-carousel .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}} !important;',
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
				'default' => [
					'size' => 6,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-testimonial-carousel .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->end_controls_section();
	}

	public function render_image( $image_id ) {
		$settings = $this->get_settings();

		$testimonial_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $image_id ), 'medium' );
		if(( 'yes' != $settings['show_image'] ) and ( '' != $testimonial_thumb[0] )) {
			return;
		}

		?>
		<div class="bdt-width-auto">
			<div class="bdt-testimonial-carousel-img-wrapper bdt-overflow-hidden bdt-border-circle bdt-background-cover">
				<img src="<?php echo esc_url($testimonial_thumb[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
			</div>
		</div>
		<?php
	}

	public function render_title( $post_id ) {
		$settings = $this->get_settings();

		if( 'yes' != $settings['show_title'] ) {
			return;
		}

		?>
		<h4 class="bdt-testimonial-carousel-title bdt-margin-remove-bottom"><?php echo esc_attr(get_the_title( $post_id )); ?></h4>
		<?php
	}

	public function render_address( $post_id ) {
		$settings = $this->get_settings();

		if( 'yes' != $settings['show_address'] ) {
			return;
		}

		?>
        <p class="bdt-testimonial-carousel-address bdt-text-meta bdt-margin-remove">
        	<?php echo get_post_meta( $post_id, 'bdthemes_tm_company_name', true ); ?>
    	</p>
		<?php
	}

	public function render_excerpt() {
		$settings = $this->get_settings();

		if( 'yes' != $settings['show_text'] ) {
			return;
		}

		?>
		<div class="bdt-testimonial-carousel-text">
			<?php echo wp_kses_post(\element_pack_helper::custom_excerpt($settings['text_limit'])); ?>
		</div>
		<?php
	}

	public function render_rating( $post_id ) {
		$settings = $this->get_settings();

		if( 'yes' != $settings['show_rating'] ) {
			return;
		}

		?>
		<ul class="bdt-rating bdt-rating-<?php echo get_post_meta( $post_id, 'bdthemes_tm_rating', true ); ?> bdt-grid bdt-grid-collapse" bdt-grid>
			<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></li>
			<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></li>
			<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></li>
			<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></li>
			<li class="bdt-rating-item"><i class="fa fa-star" aria-hidden="true"></i></li>
		</ul>
		<?php
	}

	public function render_pagination() {
		$settings = $this->get_settings();

		if(( 'dots' != $settings['navigation'] ) and ( 'both' != $settings['navigation'] )) {
			return;
		}

		?>
		<div class="swiper-pagination"></div>
		<?php
	}

	public function render_navigation() {
		$settings = $this->get_settings();

		if(( 'arrows' != $settings['navigation'] ) and ( 'both' != $settings['navigation'] )) {
			return;
		}

		$nav_style = ($settings['arrows_style'] == 'light') ? 'swiper-button-white' : 'swiper-button-black';

		?>
		<div class="swiper-button-next bdt-visible@m <?php echo esc_attr($nav_style); ?>"></div>
		<div class="swiper-button-prev bdt-visible@m <?php echo esc_attr($nav_style); ?>"></div>
		<?php
	}

	public function render_script($id) {
		$settings = $this->get_settings();

		?>
		<script>
			jQuery(document).ready(function($) {
				"use strict";				    
				var swiper = new Swiper("#<?php echo esc_attr($id);?> .swiper-container", {
					"pagination": "#<?php echo esc_attr($id);?> .swiper-pagination",
					"paginationClickable":true,
					"nextButton": "#<?php echo esc_attr($id);?> .swiper-button-next",
					"prevButton": "#<?php echo esc_attr($id);?> .swiper-button-prev",
					"autoplay": <?php echo ($settings['autoplay'] == 'yes') ? $settings['autoplay_speed'] : 'false'; ?>,
					"loop": <?php echo ($settings['infinite'] == 'yes') ? 'true' : 'false'; ?>,
					"speed": <?php echo esc_attr($settings['speed']); ?>,
					"slidesPerView": <?php echo esc_attr($settings['columns']); ?>,
					"spaceBetween":  <?php echo esc_attr($settings['item_gap']['size']); ?>,
					"breakpoints" : {
					"1024" : {
						"slidesPerView": <?php echo esc_attr($settings['columns']); ?>,
						"spaceBetween": <?php echo esc_attr($settings['item_gap']['size']); ?>,
					},
					"768" : {
						"slidesPerView": <?php echo esc_attr($settings['columns_tablet']); ?>,
						"spaceBetween": <?php echo esc_attr($settings['item_gap']['size']); ?>,
					},
					"640" : {
						"slidesPerView": <?php echo esc_attr($settings['columns_mobile']); ?>,
						"spaceBetween": <?php echo esc_attr($settings['item_gap']['size']); ?>,
						}
					}
				});
			});
		</script>
		<?php
	}

	public function render() {
		$settings = $this->get_settings();
		$id       = 'bdt-testimonial-carousel-' .$this->get_id();

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

			<div id="<?php echo esc_attr($id); ?>" class="bdt-testimonial-carousel bdt-testimonial-carousel-skin-default">
				<div class="swiper-container">
					<div class="swiper-wrapper" bdt-height-match="target: > div > .bdt-testimonial-carousel-item-wrapper">
						<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					  		<div class="swiper-slide bdt-testimonial-carousel-item">
						  		<div class="bdt-testimonial-carousel-item-wrapper">
							  		<div class="testimonial-item-header">
							  			<div class="bdt-grid bdt-grid-small bdt-flex-middle" bdt-grid>

						               <?php
						               $this->render_image( $post->ID );

					               	if (( 'yes' == $settings['show_rating'] ) || ( 'yes' == $settings['show_text'] ) || ( 'yes' == $settings['show_address'] )) : ?>
							            	<div class="bdt-width-expand">
								               <?php
								               $this->render_title( $post->ID );
								               $this->render_address( $post->ID );
								               if (( 'yes' == $settings['show_rating'] ) && ( 'yes' != $settings['show_text'] )) : ?>
							                    	<div class="bdt-testimonial-carousel-rating bdt-margin-small-top bdt-padding-remove">
					               					<?php $this->render_rating( $post->ID ); ?>
									                </div>
					                        <?php endif; ?>
									        	</div>
			                        <?php endif; ?>
					            	</div>
					            </div>

				               <?php $this->render_excerpt(); ?>
				            	
									<?php if (( 'yes' == $settings['show_rating'] ) && ( 'yes' == $settings['show_text'] )) : ?>
										<div class="bdt-testimonial-carousel-rating">
											<?php $this->render_rating( $post->ID ); ?>
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
					$this->render_pagination();
					$this->render_navigation();
				endif; ?>
			    
			</div>
			<?php
			$this->render_script($id);
	  
		endif;
	}
}


