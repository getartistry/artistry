<?php
namespace ElementPack\Modules\PriceTable\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Price_Table extends Widget_Base {

	public function get_name() {
		return 'bdt-price-table';
	}

	public function get_title() {
		return esc_html__( 'Price Table', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content_image',
			[
				'label' => esc_html__( 'Image', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::MEDIA,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
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
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table .bdt-price-table-image' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_header',
			[
				'label' => esc_html__( 'Header', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'heading',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Service Name', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'sub_heading',
			[
				'label' => esc_html__( 'Subtitle', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Service sub title', 'bdthemes-element-pack' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_pricing',
			[
				'label' => esc_html__( 'Pricing', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'currency_symbol',
			[
				'label' => esc_html__( 'Currency Symbol', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''             => esc_html__( 'None', 'bdthemes-element-pack' ),
					'dollar'       => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'bdthemes-element-pack' ),
					'euro'         => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'bdthemes-element-pack' ),
					'baht'         => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'bdthemes-element-pack' ),
					'franc'        => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'bdthemes-element-pack' ),
					'guilder'      => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'bdthemes-element-pack' ),
					'krona'        => 'kr ' . _x( 'Krona', 'Currency Symbol', 'bdthemes-element-pack' ),
					'lira'         => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'bdthemes-element-pack' ),
					'peseta'       => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'bdthemes-element-pack' ),
					'peso'         => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'bdthemes-element-pack' ),
					'pound'        => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'bdthemes-element-pack' ),
					'real'         => 'R$ ' . _x( 'Real', 'Currency Symbol', 'bdthemes-element-pack' ),
					'ruble'        => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'bdthemes-element-pack' ),
					'rupee'        => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'bdthemes-element-pack' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'bdthemes-element-pack' ),
					'shekel'       => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'bdthemes-element-pack' ),
					'yen'          => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'bdthemes-element-pack' ),
					'won'          => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'bdthemes-element-pack' ),
					'custom'       => esc_html__( 'Custom', 'bdthemes-element-pack' ),
				],
				'default' => 'dollar',
			]
		);

		$this->add_control(
			'currency_symbol_custom',
			[
				'label' => esc_html__( 'Custom Symbol', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => '49.99',
			]
		);

		$this->add_control(
			'sale',
			[
				'label' => esc_html__( 'Sale', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'Off', 'bdthemes-element-pack' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'original_price',
			[
				'label' => esc_html__( 'Original Price', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '79',
				'condition' => [
					'sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'period',
			[
				'label' => esc_html__( 'Period', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Monthly', 'bdthemes-element-pack' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_features',
			[
				'label' => esc_html__( 'Features', 'bdthemes-element-pack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			[
				'label'   => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'List Item', 'bdthemes-element-pack' ),
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label'   => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::ICON,
				'default' => 'fa fa-check',
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'features_list',
			[
				'type'    => Controls_Manager::REPEATER,
				'fields'  => array_values( $repeater->get_controls() ),
				'default' => [
					[
						'item_text' => esc_html__( 'List Item #1', 'bdthemes-element-pack' ),
						'item_icon' => 'fa fa-check',
					],
					[
						'item_text' => esc_html__( 'List Item #2', 'bdthemes-element-pack' ),
						'item_icon' => 'fa fa-check',
					],
					[
						'item_text' => esc_html__( 'List Item #3', 'bdthemes-element-pack' ),
						'item_icon' => 'fa fa-check',
					],
				],
				'title_field' => '{{{ item_text }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_footer',
			[
				'label' => esc_html__( 'Footer', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Select Plan', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default'     => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'footer_additional_info',
			[
				'label'   => esc_html__( 'Additional Info', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'This is footer text', 'bdthemes-element-pack' ),
				'rows'    => 2,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_ribbon',
			[
				'label' => esc_html__( 'Ribbon', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_ribbon',
			[
				'label'        => esc_html__( 'Show', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'bdthemes-element-pack' ),
				'label_off'    => esc_html__( 'No', 'bdthemes-element-pack' ),
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'ribbon_title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Popular', 'bdthemes-element-pack' ),
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->add_control(
			'ribbon_horizontal_position',
			[
				'label' => esc_html__( 'Horizontal Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
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
						'title' => esc_html__( 'Justify', 'bdthemes-element-pack' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => 'left',
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -150,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-ribbon-inner' => 'transform: translateY({{SIZE}}{{UNIT}})',
				],
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'image[url]!' => '',
				]
			]
		);

		$this->add_control(
			'image_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-image' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-price-table-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => esc_html__( 'Size (%)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => esc_html__( 'Opacity (%)', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Image Border', 'bdthemes-element-pack' ),
				'selector' => '{{WRAPPER}} .bdt-price-table img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .bdt-price-table img',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'Header', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'header_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-price-table-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_heading_style',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .bdt-price-table-heading',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_sub_heading_style',
			[
				'label'     => esc_html__( 'Sub Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sub_heading_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-subheading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_heading_typography',
				'selector' => '{{WRAPPER}} .bdt-price-table-subheading',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pricing',
			[
				'label'      => esc_html__( 'Pricing', 'bdthemes-element-pack' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'pricing_element_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-price' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'pricing_element_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-price-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-currency, {{WRAPPER}} .bdt-price-table-integer-part, {{WRAPPER}} .bdt-price-table-fractional-part' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .bdt-price-table-price',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_currency_style',
			[
				'label'     => esc_html__( 'Currency Symbol', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_control(
			'currency_size',
			[
				'label' => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-currency' => 'font-size: calc({{SIZE}}em/100)',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_control(
			'currency_vertical_position',
			[
				'label'       => esc_html__( 'Vertical Position', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'top' => [
						'title' => esc_html__( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'              => 'top',
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-currency' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_control(
			'fractional_part_style',
			[
				'label'     => esc_html__( 'Fractional Part', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'fractional-part_size',
			[
				'label' => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-fractional-part' => 'font-size: calc({{SIZE}}em/100)',
				],
			]
		);

		$this->add_control(
			'fractional_part_vertical_position',
			[
				'label'       => esc_html__( 'Vertical Position', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'top' => [
						'title' => esc_html__( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'              => 'top',
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-after-price' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_original_price_style',
			[
				'label'     => esc_html__( 'Original Price', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'sale'            => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_control(
			'original_price_color',
			[
				'label'  => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-original-price' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale'            => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'original_price_typography',
				'selector'  => '{{WRAPPER}} .bdt-price-table-original-price',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'condition' => [
					'sale'            => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_control(
			'original_price_vertical_position',
			[
				'label'       => esc_html__( 'Vertical Position', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'top' => [
						'title' => esc_html__( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'default'   => 'bottom',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-original-price' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'sale'            => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_control(
			'heading_period_style',
			[
				'label'     => esc_html__( 'Period', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_control(
			'period_color',
			[
				'label'  => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-period' => 'color: {{VALUE}}',
				],
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'period_typography',
				'selector'  => '{{WRAPPER}} .bdt-price-table-period',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_control(
			'period_position',
			[
				'label'       => esc_html__( 'Position', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'below' => 'Below',
					'beside' => 'Beside',
				],
				'default' => 'below',
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_features',
			[
				'label'      => esc_html__( 'Features', 'bdthemes-element-pack' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'features_list_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'features_list_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'features_list_color',
			[
				'label'  => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'features_list_typography',
				'selector' => '{{WRAPPER}} .bdt-price-table-features-list li',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'features_list_alignment',
			[
				'label'       => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'item_width',
			[
				'label' => esc_html__( 'Width', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 25,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-feature-inner' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				],
			]
		);

		$this->add_control(
			'list_divider',
			[
				'label'        => esc_html__( 'Divider', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'bdthemes-element-pack' ),
				'label_off'    => esc_html__( 'No', 'bdthemes-element-pack' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'   => esc_html__( 'Style', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'solid'  => esc_html__( 'Solid', 'bdthemes-element-pack' ),
					'double' => esc_html__( 'Double', 'bdthemes-element-pack' ),
					'dotted' => esc_html__( 'Dotted', 'bdthemes-element-pack' ),
					'dashed' => esc_html__( 'Dashed', 'bdthemes-element-pack' ),
				],
				'default' => 'solid',
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list li:before' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'   => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ddd',
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list li:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label'   => esc_html__( 'Weight', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list li:before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label'     => esc_html__( 'Width', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list li:before' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				],
			]
		);

		$this->add_control(
			'divider_gap',
			[
				'label'   => esc_html__( 'Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-features-list li:before' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_footer',
			[
				'label'      => esc_html__( 'Footer', 'bdthemes-element-pack' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'footer_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-footer' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'footer_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-price-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_footer_button',
			[
				'label'     => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'   => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => [
					'md' => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'sm' => esc_html__( 'Small', 'bdthemes-element-pack' ),
					'xs' => esc_html__( 'Extra Small', 'bdthemes-element-pack' ),
					'lg' => esc_html__( 'Large', 'bdthemes-element-pack' ),
					'xl' => esc_html__( 'Extra Large', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-button' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-price-table-button',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#14ABF4',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bdt-price-table-button',
				'condition' => [
					'button_text!' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-button:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_additional_info',
			[
				'label' => esc_html__( 'Additional Info', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->add_control(
			'additional_info_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-additional_info' => 'color: {{VALUE}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_info_typography',
				'selector' => '{{WRAPPER}} .bdt-price-table-additional_info',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->add_control(
			'additional_info_margin',
			[
				'label' => esc_html__( 'Margin', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 0,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-additional_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_ribbon',
			[
				'label' => esc_html__( 'Ribbon', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->add_control(
			'ribbon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#14ABF4',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-ribbon-inner' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ribbon_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-ribbon-inner' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ribbon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-price-table-ribbon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .bdt-price-table-ribbon-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .bdt-price-table-ribbon-inner',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->end_controls_section();
	}

	private function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar' => '&#36;',
			'euro' => '&#128;',
			'franc' => '&#8355;',
			'pound' => '&#163;',
			'ruble' => '&#8381;',
			'shekel' => '&#8362;',
			'baht' => '&#3647;',
			'yen' => '&#165;',
			'won' => '&#8361;',
			'guilder' => '&fnof;',
			'peso' => '&#8369;',
			'peseta' => '&#8359',
			'lira' => '&#8356;',
			'rupee' => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real' => 'R$',
			'krona' => 'kr',
		];
		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	protected function get_image() {

		$settings = $this->get_settings();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'bdt-price-table-image' );

		 ?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

		<?php
			
		echo Group_Control_Image_Size::get_attachment_image_html( $settings );

		?>
		</div>

		<?php
	}

	protected function render() {
		$settings = $this->get_settings();
		$symbol = '';
		$image = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}

		$price = explode( '.', $settings['price'] );
		$intpart = $price[0];
		$fraction = '';
		if ( 2 === sizeof( $price ) ) {
			$fraction = $price[1];
		}

		$period_position = $settings['period_position'];
		$period_class = ($period_position == 'below') ? ' bdt-price-table-period-position-below' : ' bdt-price-table-period-position-beside';

		$period_element = '<span class="bdt-price-table-period elementor-typo-excluded'.$period_class.'">' . $settings['period'] . '</span>';
		$button_size = ($settings['button_size']) ? 'elementor-size-' . $settings['button_size'] : '';

		$this->add_render_attribute( 'button', 'class', [
				'bdt-price-table-button',
				'elementor-button',
				$button_size,
			]
		);

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'button', 'href', $settings['link']['url'] );

			if ( ! empty( $settings['link']['is_external'] ) ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}
		}

		if ( ! empty( $settings['button_hover_animation'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
		?>
		<div class="bdt-price-table">
			<?php $this->get_image(); ?>
			<?php if ( $settings['heading'] || $settings['sub_heading'] ) : ?>
				<div class="bdt-price-table-header">					
					<?php if ( ! empty( $settings['heading'] ) ) : ?>
						<h3 class="bdt-price-table-heading"><?php echo esc_html($settings['heading']); ?></h3>
					<?php endif; ?>
	
					<?php if ( ! empty( $settings['sub_heading'] ) ) : ?>
						<span class="bdt-price-table-subheading"><?php echo esc_html($settings['sub_heading']); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="bdt-price-table-price">
				<?php if ( 'yes' === $settings['sale'] && ! empty( $settings['original_price'] ) ) : ?>
					<div class="bdt-price-table-original-price elementor-typo-excluded"><?php echo esc_html($symbol . $settings['original_price']); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $symbol ) && is_numeric( $intpart ) ) : ?>
					<span class="bdt-price-table-currency"><?php echo esc_attr($symbol); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $intpart ) || 0 <= $intpart ) : ?>
					<span class="bdt-price-table-integer-part"><?php echo esc_attr($intpart); ?></span>
				<?php endif; ?>

				<?php if ( 0 < $fraction || ( ! empty( $settings['period'] ) && 'beside' === $period_position ) ) : ?>
					<div class="bdt-price-table-after-price">
						<span class="bdt-price-table-fractional-part"><?php echo esc_attr($fraction); ?></span>
						<?php if ( ! empty( $settings['period'] ) && 'beside' === $period_position ) : ?>
							<?php echo wp_kses_post($period_element); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $settings['period'] ) && 'below' === $period_position ) : ?>
					<?php echo wp_kses_post($period_element); ?>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $settings['features_list'] ) ) : ?>
				<ul class="bdt-price-table-features-list">
					<?php foreach ( $settings['features_list'] as $item ) : ?>
						<li class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
							<div class="bdt-price-table-feature-inner">
								<?php if ( ! empty( $item['item_icon'] ) ) : ?>
									<i class="<?php echo esc_attr( $item['item_icon'] ); ?>"></i>
								<?php endif; ?>
								<?php if ( ! empty( $item['item_text'] ) ) :
									echo esc_html($item['item_text']);
								else :
									echo '&nbsp;';
								endif;
								?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		
			<?php if ( ! empty( $settings['button_text'] ) || ! empty( $settings['footer_additional_info'] ) ) : ?>
			<div class="bdt-price-table-footer">
				<?php if ( ! empty( $settings['button_text'] ) ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php echo esc_html($settings['button_text']); ?>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $settings['footer_additional_info'] ) ) : ?>
					<div class="bdt-price-table-additional_info"><?php echo wp_kses_post($settings['footer_additional_info']); ?></div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>

		<?php if ( 'yes' === $settings['show_ribbon'] && ! empty( $settings['ribbon_title'] ) ) :
			$this->add_render_attribute( 'ribbon-wrapper', 'class', 'bdt-price-table-ribbon' );

			if ( ! empty( $settings['ribbon_horizontal_position'] ) ) :
				$this->add_render_attribute( 'ribbon-wrapper', 'class', 'elementor-ribbon-' . $settings['ribbon_horizontal_position'] );
			endif;

			?>
			<div <?php echo $this->get_render_attribute_string( 'ribbon-wrapper' ); ?>>
				<div class="bdt-price-table-ribbon-inner"><?php echo esc_html($settings['ribbon_title']); ?></div>
			</div>
		<?php endif;
	}

	protected function _content_template() {
		?>
		<#
			var symbols = {
				dollar: '&#36;',
				euro: '&#128;',
				franc: '&#8355;',
				pound: '&#163;',
				ruble: '&#8381;',
				shekel: '&#8362;',
				baht: '&#3647;',
				yen: '&#165;',
				won: '&#8361;',
				guilder: '&fnof;',
				peso: '&#8369;',
				peseta: '&#8359;',
				lira: '&#8356;',
				rupee: '&#8360;',
				indian_rupee: '&#8377;',
				real: 'R$',
				krona: 'kr'
			};

			var symbol = '';

			if ( settings.currency_symbol ) {
				if ( 'custom' !== settings.currency_symbol ) {
					symbol = symbols[ settings.currency_symbol ] || '';
				} else {
					symbol = settings.currency_symbol_custom;
				}
			}

			var price = settings.price.split( '.' ),
				intpart = price[0],
				fraction = price[1],
				buttonSize = (settings.button_size) ? ' elementor-size-' + settings.button_size : '',
				periodElement = '<span class="bdt-price-table-period elementor-typo-excluded">' + settings.period + '</span>',
				
				buttonClasses = 'bdt-price-table-button elementor-button' + buttonSize;

			if ( settings.button_hover_animation ) {
				buttonClasses += ' elementor-animation-' + settings.button_hover_animation;
			}

			if ( '' !== settings.image.url ) {
				var image = {
					url: settings.image.url,
				};

				var image_url = elementor.imagesManager.getImageUrl( image );

				if ( ! image_url ) {
					return;
				}

				var imgClass = '';

				if ( '' !== settings.hover_animation ) {
					imgClass = 'elementor-animation-' + settings.hover_animation;
				}
			}

		#>
		<div class="bdt-price-table">
			<# if ( image_url ) { #>
				<div class="bdt-price-table-image"><img src="{{ image_url }}" class="{{ imgClass }}" /></div>
			<# } #>

			<# if ( settings.heading || settings.sub_heading ) { #>
				<div class="bdt-price-table-header">
					<# if ( settings.heading ) { #>
						<h3 class="bdt-price-table-heading">{{{ settings.heading }}}</h3>
					<# } #>
					<# if ( settings.sub_heading ) { #>
						<span class="bdt-price-table-subheading">{{{ settings.sub_heading }}}</span>
					<# } #>
				</div>
			<# } #>

			<div class="bdt-price-table-price">
				<# if ( settings.sale && settings.original_price ) { #>
					<div class="bdt-price-table-original-price elementor-typo-excluded">{{{ symbol + settings.original_price }}}</div>
				<# } #>

				<# if (  ! _.isEmpty( symbol ) && isFinite( intpart ) ) { #>
					<span class="bdt-price-table-currency">{{{ symbol }}}</span>
				<# } #>
				<# if ( intpart ) { #>
					<span class="bdt-price-table-integer-part">{{{ intpart }}}</span>
				<# } #>
				<div class="bdt-price-table-after-price">
					<# if ( fraction ) { #>
						<span class="bdt-price-table-fractional-part">{{{ fraction }}}</span>
					<# } #>
					<# if ( settings.period && 'beside' === settings.period_position ) { #>
						{{{ periodElement }}}
					<# } #>
				</div>

				<# if ( settings.period && 'below' === settings.period_position ) { #>
					{{{ periodElement }}}
				<# } #>
			</div>

			<# if ( settings.features_list ) { #>
				<ul class="bdt-price-table-features-list">
					<# _.each( settings.features_list, function( item ) { #>
						<li class="elementor-repeater-item-{{ item._id }}">
							<div class="bdt-price-table-feature-inner">
								<# if ( item.item_icon ) { #>
									<i class="{{ item.item_icon }}"></i>
								<# } #>
								<# if ( ! _.isEmpty( item.item_text.trim() ) ) { #>
									{{{ item.item_text }}}
								<# } else { #>
									&nbsp;
								<# } #>

							</div>
						</li>
					<# } ); #>
				</ul>
			<# } #>

			<# if ( settings.button_text || settings.footer_additional_info ) { #>
				<div class="bdt-price-table-footer">
					<# if ( settings.button_text ) { #>
						<a href="#" class="{{ buttonClasses }}">{{{ settings.button_text }}}</a>
					<# } #>
					<# if ( settings.footer_additional_info ) { #>
						<p class="bdt-price-table-additional_info">{{{ settings.footer_additional_info }}}</p>
					<# } #>
				</div>
			<# } #>
		</div>

		<# if ( 'yes' === settings.show_ribbon && settings.ribbon_title ) {
			var ribbonClasses = 'bdt-price-table-ribbon';
			if ( settings.ribbon_horizontal_position ) {
				ribbonClasses += ' elementor-ribbon-' + settings.ribbon_horizontal_position;
			} #>
			<div class="{{ ribbonClasses }}">
				<div class="bdt-price-table-ribbon-inner">{{{ settings.ribbon_title }}}</div>
			</div>
		<# } #>
		<?php
	}
}
