<?php
namespace ElementPack\Modules\PanelSlider\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use ElementPack\Modules\QueryControl\Controls\Group_Control_Posts;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Panel Slider
 */
class Panel_Slider extends Widget_Base {

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-panel-slider';
	}

	public function get_title() {
		return esc_html__( 'Panel Slider', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'services';
		}

		return $element;
	}

	public function on_export( $element ) {
		$element = Group_Control_Posts::on_export_remove_setting_from_element( $element, 'posts' );
		return $element;
	}

	public function get_query() {
		return $this->_query;
	}

	protected function _register_controls() {
		$this->register_query_section_controls();
	}

	private function register_query_section_controls() {

		$this->start_controls_section(
			'section_content_sliders',
			[
				'label' => esc_html__( 'Sliders', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Slider Items', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'tab_title'   => esc_html__( 'Slide #1', 'bdthemes-element-pack' ),
						'tab_content' => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'tab_title'   => esc_html__( 'Slide #2', 'bdthemes-element-pack' ),
						'tab_content' => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'tab_title'   => esc_html__( 'Slide #3', 'bdthemes-element-pack' ),
						'tab_content' => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'tab_title'   => esc_html__( 'Slide #4', 'bdthemes-element-pack' ),
						'tab_content' => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
				],
				'fields' => [
					[
						'name'        => 'tab_title',
						'label'       => esc_html__( 'Title', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => esc_html__( 'Slide Title' , 'bdthemes-element-pack' ),
						'label_block' => true,
					],
					[
						'name'  => 'tab_image',
						'label' => esc_html__( 'Image', 'bdthemes-element-pack' ),
						'type'  => Controls_Manager::MEDIA,
					],
					[
						'name'       => 'tab_content',
						'label'      => esc_html__( 'Content', 'bdthemes-element-pack' ),
						'type'       => Controls_Manager::WYSIWYG,
						'default'    => esc_html__( 'Slide Content', 'bdthemes-element-pack' ),
						'show_label' => false,
					],
					[
						'name'        => 'tab_link',
						'label'       => esc_html__( 'Link', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::URL,
						'placeholder' => 'http://your-link.com',
						'default'     => [
							'url' => '#',
						],
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'bdthemes-element-pack' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
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
			'show_title',
			[
				'label'     => esc_html__( 'Show Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button',
			[
				'label'       => esc_html__( 'Show Button', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'label_on'    => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'label_off'   => esc_html__( 'On', 'bdthemes-element-pack' ),
				'description' => 'It will work when link field no null.',
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


		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'condition' => [
					'button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slider .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-panel-slider .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_slider_settings',
			[
				'label' => esc_html__( 'Slider Settings', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => esc_html__( 'Autoplay', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'     => esc_html__( 'Infinite Loop', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Animation Speed', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_slider',
			[
				'label' => esc_html__( 'Slider', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slider_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-overlay-gradient' => 'background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 40%, {{VALUE}} 100%);',
				],
			]
		);

		$this->add_control(
			'slider_opacity',
			[
				'label' => esc_html__( 'Opacity', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slider .bdt-panel-slide-item .bdt-panel-slide-thumb img' => 'opacity: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link',
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-link',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' =>'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-panel-slide-item .bdt-panel-slide-text',
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
					'{{WRAPPER}} .bdt-panel-slider .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-panel-slider .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
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
					'{{WRAPPER}} .bdt-panel-slider .swiper-button-prev, {{WRAPPER}} .bdt-panel-slider .swiper-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .bdt-panel-slider .swiper-pagination-bullets' => 'bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .bdt-panel-slider .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
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
		<div class="bdt-panel-slider">
			<div class="swiper-container">
		<?php
	}

	protected function render_loop_footer() {

		$settings = $this->get_settings();

		?>
			</div>

			<?php if ( 'none' !== $settings['navigation'] ) : ?>
				<?php if ( 'arrows' !== $settings['navigation'] ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
				
				<?php if ( 'dots' !== $settings['navigation'] ) : 
					$nav_style = ($settings['arrows_style'] == 'light') ? 'swiper-button-white' : 'swiper-button-black'; 
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
			        "paginationClickable":true,
			        "nextButton": ".elementor-element-<?php echo esc_attr($this->get_id());?> .swiper-button-next",
			        "prevButton": ".elementor-element-<?php echo esc_attr($this->get_id());?> .swiper-button-prev",
			        "autoplay": <?php echo ($settings['autoplay'] == 'yes') ? $settings['autoplay_speed'] : 'false'; ?>,
			        "loop": <?php echo ($settings['infinite'] == 'yes') ? 'true' : 'false'; ?>,
			        "speed": <?php echo esc_attr($settings['speed']); ?>,
			        "slidesPerView": <?php echo esc_attr($settings['columns']); ?>,
			        "spaceBetween":  0,
			        "breakpoints" : {
			            "1024" : {
			            	"slidesPerView": <?php echo esc_attr($settings['columns']); ?>,
			            },
			            "768" : {
			            	"slidesPerView": <?php echo esc_attr($settings['columns_tablet']); ?>,
			            },
			            "640" : {
			            	"slidesPerView": <?php echo esc_attr($settings['columns_mobile']); ?>,
			            }
			        }
			    });
			});
		</script>

		<?php
	}
	public function render() {
		$settings  = $this->get_settings();
		$url       = $target = $link_title = '';
		$classes   = ['bdt-panel-slide-item', 'swiper-slide', 'bdt-transition-toggle'];
		$animation = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';
		$this->render_loop_header();
		$counter   = 1;

		?>
		<div class="swiper-wrapper">
			<?php foreach ( $settings['tabs'] as $item ) : ?>
				<?php 
					$image_src = wp_get_attachment_image_src( $item['tab_image']['id'], 'full' );
					$image     =  ($image_src) ? $image_src[0] : BDTEP_ASSETS_URL.'/images/panel-slider.svg';
				?>
				<div class="<?php echo implode(" ", $classes); ?>">
		        	<div class="bdt-panel-slide-thumb">
		        		<img src="<?php echo  $image; ?>" alt="<?php echo esc_attr($item['tab_title']); ?>">
		        	</div>
		        	<div class="bdt-panel-slide-desc bdt-position-bottom-left bdt-position-z-index">
			        	<?php if (!empty( $item['tab_link']['url']) and ('yes' == $settings['button']))  : ?>
							<a href="<?php echo esc_url($item['tab_link']['url']); ?>" target="<?php echo esc_attr($item['tab_link']['is_external']); ?>" class="bdt-panel-slide-link bdt-link-reset bdt-transition-slide-bottom<?php echo esc_attr($animation); ?>"><?php echo esc_html($settings['button_text']); ?>
								<?php if ($settings['icon']) : ?>
									<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
										<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
									</span>
								<?php endif; ?>
							</a>
						<?php endif; ?>

						<?php if ( 'yes' == $settings['show_title'] ) : ?>
							<h3 class="bdt-panel-slide-title bdt-transition-slide-bottom"><?php echo esc_html($item['tab_title']); ?></h3>
						<?php endif; ?>

						<?php if ( '' !== $item['tab_content'] ) : ?>
							<div class="bdt-panel-slide-text bdt-transition-slide-bottom"><?php echo $this->parse_text_editor( $item['tab_content'] ); ?></div>
						<?php endif; ?>
			  		</div>
					<div class="bdt-transition-fade bdt-position-cover bdt-overlay bdt-overlay-gradient"></div>
				</div>
			<?php
				$counter++;
			endforeach; ?>
		</div>
		<?php

		$this->render_loop_footer();
	}
}
