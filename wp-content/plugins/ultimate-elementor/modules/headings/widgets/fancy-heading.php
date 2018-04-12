<?php
/**
 * UAEL Fancy Heading.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\Headings\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;

// UltimateElementor Classes.
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Fancy_Heading.
 */
class Fancy_Heading extends Common_Widget {

	/**
	 * Retrieve Fancy Heading Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'Fancy_Heading' );
	}

	/**
	 * Retrieve Fancy Heading Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Fancy_Heading' );
	}

	/**
	 * Retrieve Fancy Heading Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Fancy_Heading' );
	}

	/**
	 * Retrieve the list of scripts the Fancy Heading widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'uael-frontend-script', 'uael-fancytext-typed', 'uael-fancytext-slidev' ];
	}

	/**
	 * Register Fancy Heading controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_headingtext_content_controls();
		$this->register_effect_content_controls();
		$this->register_general_content_controls();
		$this->register_style_content_controls();
	}

	/**
	 * Register Fancy Heading Text Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_headingtext_content_controls() {
		$this->start_controls_section(
			'section_general_field',
			[
				'label' => __( 'Heading Text', 'uael' ),
			]
		);

		$this->add_control(
			'fancytext_prefix',
			[
				'label'    => __( 'Before Text', 'uael' ),
				'type'     => Controls_Manager::TEXT,
				'selector' => '{{WRAPPER}} .uael-fancy-text-prefix',
				'dynamic'  => [
					'active' => true,
				],
				'default'  => __( 'I am', 'uael' ),
			]
		);

		$this->add_control(
			'fancytext',
			[
				'label'       => __( 'Fancy Text', 'uael' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter each word in a separate line', 'uael' ),
				'dynamic'     => [
					'active' => true,
				],
				'default'     => "Creative\nAmazing\nPassionate",
			]
		);
		$this->add_control(
			'fancytext_suffix',
			[
				'label'    => __( 'After Text', 'uael' ),
				'type'     => Controls_Manager::TEXT,
				'selector' => '{{WRAPPER}} .uael-fancy-text-suffix',
				'dynamic'  => [
					'active' => true,
				],
				'default'  => __( 'Designer', 'uael' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Fancy Heading Effect Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_effect_content_controls() {
		$this->start_controls_section(
			'section_effect_field',
			[
				'label' => __( 'Effect', 'uael' ),
			]
		);
		$this->add_control(
			'fancytext_effect_type',
			[
				'label'       => __( 'Select Effect', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'type'  => __( 'Type', 'uael' ),
					'slide' => __( 'Slide', 'uael' ),
				],
				'default'     => 'type',
				'label_block' => false,
			]
		);
		$this->add_control(
			'fancytext_type_loop',
			[
				'label'        => __( 'Enable Loop', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'fancytext_effect_type' => 'type',
				],
			]
		);
		$this->add_control(
			'fancytext_type_show_cursor',
			[
				'label'        => __( 'Show Cursor', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'fancytext_effect_type' => 'type',
				],
			]
		);
		$this->add_control(
			'fancytext_type_cursor_text',
			[
				'label'     => __( 'Cursor Text', 'uael' ),
				'type'      => Controls_Manager::TEXT,
				'selector'  => '{{WRAPPER}}',
				'default'   => __( '|', 'uael' ),
				'condition' => [
					'fancytext_effect_type'      => 'type',
					'fancytext_type_show_cursor' => 'yes',
				],
				'selector'  => '{{WRAPPER}} .typed-cursor',
			]
		);
		$this->add_control(
			'fancytext_type_cursor_blink',
			[
				'label'        => __( 'Cursor Blink Effect', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'fancytext_effect_type'      => 'type',
					'fancytext_type_show_cursor' => 'yes',
				],
				'prefix_class' => 'uael-show-cursor-',
			]
		);
		$this->add_control(
			'fancytext_type_fields',
			[
				'label'        => __( 'Advanced Settings', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'fancytext_effect_type' => 'type',
				],
			]
		);

		$this->add_control(
			'fancytext_slide_pause_hover',
			[
				'label'        => __( 'Pause on Hover', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'fancytext_effect_type' => 'slide',
				],
			]
		);
		$this->add_control(
			'fancytext_slide_anim_speed',
			[
				'label'       => __( 'Animation Speed (ms)', 'uael' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'ms' ],
				'range'       => [
					'ms' => [
						'min' => 1,
						'max' => 5000,
					],
				],
				'default'     => [
					'size' => '500',
					'unit' => 'ms',
				],
				'label_block' => true,
				'condition'   => [
					'fancytext_effect_type' => 'slide',
				],
			]
		);
		$this->add_control(
			'fancytext_slide_pause_time',
			[
				'label'       => __( 'Pause Time (ms)', 'uael' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'ms' ],
				'range'       => [
					'ms' => [
						'min' => 1,
						'max' => 5000,
					],
				],
				'default'     => [
					'size' => '2000',
					'unit' => 'ms',
				],
				'label_block' => true,
				'condition'   => [
					'fancytext_effect_type' => 'slide',
				],
			]
		);
		$this->add_control(
			'fancytext_type_speed',
			[
				'label'       => __( 'Typing Speed (ms)', 'uael' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'ms' ],
				'range'       => [
					'ms' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'     => [
					'size' => '120',
					'unit' => 'ms',
				],
				'label_block' => true,
				'condition'   => [
					'fancytext_effect_type' => 'type',
					'fancytext_type_fields' => 'yes',
				],
			]
		);
		$this->add_control(
			'fancytext_type_backspeed',
			[
				'label'       => __( 'Backspeed (ms)', 'uael' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'ms' ],
				'range'       => [
					'ms' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'     => [
					'size' => '60',
					'unit' => 'ms',
				],
				'label_block' => true,
				'condition'   => [
					'fancytext_effect_type' => 'type',
					'fancytext_type_fields' => 'yes',
				],
			]
		);

		$this->add_control(
			'fancytext_type_start_delay',
			[
				'label'       => __( 'Start Delay (ms)', 'uael' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'ms' ],
				'range'       => [
					'ms' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'default'     => [
					'size' => '0',
					'unit' => 'ms',
				],
				'label_block' => true,
				'condition'   => [
					'fancytext_effect_type' => 'type',
					'fancytext_type_fields' => 'yes',
				],
			]
		);
		$this->add_control(
			'fancytext_type_back_delay',
			[
				'label'       => __( 'Back Delay (ms)', 'uael' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'ms' ],
				'range'       => [
					'ms' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'default'     => [
					'size' => '1200',
					'unit' => 'ms',
				],
				'label_block' => true,
				'condition'   => [
					'fancytext_effect_type' => 'type',
					'fancytext_type_fields' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Register Fancy Heading General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {
		$this->start_controls_section(
			'section_structure_field',
			[
				'label' => __( 'General', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'fancytext_title_tag',
			[
				'label'   => __( 'Title Tag', 'uael' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'  => __( 'H1', 'uael' ),
					'h2'  => __( 'H2', 'uael' ),
					'h3'  => __( 'H3', 'uael' ),
					'h4'  => __( 'H4', 'uael' ),
					'h5'  => __( 'H5', 'uael' ),
					'h6'  => __( 'H6', 'uael' ),
					'div' => __( 'div', 'uael' ),
					'p'   => __( 'p', 'uael' ),
				],
				'default' => 'h3',
			]
		);
		$this->add_responsive_control(
			'fancytext_align',
			[
				'label'     => __( 'Alignment', 'uael' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'uael' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'uael' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'uael' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .uael-fancy-text-wrap ' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'fancytext_layout',
			[
				'label'        => __( 'Layout', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Stack', 'uael' ),
				'label_off'    => __( 'Inline', 'uael' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'uael-fancytext-stack-',
			]
		);
		$this->add_responsive_control(
			'fancytext_space_prefix',
			[
				'label'      => __( 'Before Spacing', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => '0',
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}}.uael-fancytext-stack-yes .uael-fancy-stack ' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.uael-fancytext-stack-yes .uael-fancy-stack .uael-fancy-heading.uael-fancy-text-main' => ' margin-left: 0px;',
					'{{WRAPPER}} .uael-fancy-text-main' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'fancytext_space_suffix',
			[
				'label'      => __( 'After Spacing', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => '0',
					'unit' => 'px',
				],
				'condition'  => [
					'fancytext_suffix!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-fancy-text-main' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.uael-fancytext-stack-yes .uael-fancy-stack ' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.uael-fancytext-stack-yes .uael-fancy-stack .uael-fancy-heading.uael-fancy-text-main' => ' margin-right: 0px;',
				],
			]
		);
		$this->add_responsive_control(
			'fancytext_min_height',
			[
				'label'      => __( 'Minimum Height', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .uael-fancy-text-wrap' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'fancytext_effect_type' => 'type',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Fancy Heading Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_typography_field',
			[
				'label' => __( 'Style', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_fancytext' );

			$this->start_controls_tab(
				'tab_heading',
				[
					'label' => __( 'Heading Text', 'uael' ),
				]
			);
			$this->add_control(
				'prefix_suffix_color',
				[
					'label'     => __( 'Text Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .uael-fancy-heading' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'prefix_suffix_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .uael-fancy-heading',
				]
			);
			$this->add_control(
				'text_adv_options',
				[
					'label'        => __( 'Advanced', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'uael' ),
					'label_off'    => __( 'No', 'uael' ),
					'return_value' => 'yes',
					'default'      => '',
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'      => 'text_bg_color',
					'label'     => __( 'Background Color', 'uael' ),
					'types'     => [ 'classic', 'gradient' ],
					'selector'  => '{{WRAPPER}} .uael-fancy-heading',
					'condition' => [
						'text_adv_options' => 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'text_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-fancy-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'text_adv_options' => 'yes',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'text_border',
					'label'       => __( 'Border', 'uael' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .uael-fancy-heading',
					'condition'   => [
						'text_adv_options' => 'yes',
					],
				]
			);
			$this->add_control(
				'text_border_radius',
				[
					'label'      => __( 'Border Radius', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-fancy-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'text_adv_options' => 'yes',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name'      => 'text_shadow',
					'selector'  => '{{WRAPPER}} .uael-fancy-heading',
					'condition' => [
						'text_adv_options' => 'yes',
					],
				]
			);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_fancy',
				[
					'label' => __( 'Fancy Text', 'uael' ),
				]
			);
			$this->add_control(
				'fancytext_color',
				[
					'label'     => __( 'Fancy Text Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'fancytext_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main',
				]
			);
			$this->add_control(
				'fancy_adv_options',
				[
					'label'        => __( 'Advanced', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'uael' ),
					'label_off'    => __( 'No', 'uael' ),
					'return_value' => 'yes',
					'default'      => '',
				]
			);
			$this->add_control(
				'fancytext_bg_color',
				[
					'label'     => __( 'Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'fancy_adv_options' => 'yes',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'      => 'fancytext_bg_color',
					'label'     => __( 'Background Color', 'uael' ),
					'types'     => [ 'classic', 'gradient' ],
					'selector'  => '{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main',
					'condition' => [
						'fancy_adv_options' => 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'fancytext_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'fancy_adv_options' => 'yes',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'fancytext_border',
					'label'       => __( 'Border', 'uael' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main',
					'condition'   => [
						'fancy_adv_options' => 'yes',
					],
				]
			);
			$this->add_control(
				'fancytext_border_radius',
				[
					'label'      => __( 'Border Radius', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'fancy_adv_options' => 'yes',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name'      => 'fancytext_shadow',
					'selector'  => '{{WRAPPER}} .uael-fancy-heading.uael-fancy-text-main',
					'condition' => [
						'fancy_adv_options' => 'yes',
					],
				]
			);
			$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

	}
	/**
	 * Get Data Attributes.
	 *
	 * @since 0.0.1
	 * @param array $settings The settings array.
	 * @return string Data Attributes
	 * @access public
	 */
	public function get_data_attrs( $settings ) {

		$effect_type  = $settings['fancytext_effect_type'];
		$strs         = explode( "\n", $settings['fancytext'] );
		$strs         = array_filter(
			$strs,
			function( $value ) {
				if ( '' !== $value ) {
					return addslashes( $value );
				}
			}
		);
		$data_strings = implode( '","', $strs );
		$data_strings = '["' . $data_strings . '"]';

		if ( 'type' == $settings['fancytext_effect_type'] ) {
			$type_speed  = ( '' != $settings['fancytext_type_speed']['size'] ) ? $settings['fancytext_type_speed']['size'] : 120;
			$back_speed  = ( '' != $settings['fancytext_type_backspeed']['size'] ) ? $settings['fancytext_type_backspeed']['size'] : 60;
			$start_delay = ( '' != $settings['fancytext_type_start_delay']['size'] ) ? $settings['fancytext_type_start_delay']['size'] : 0;
			$back_delay  = ( '' != $settings['fancytext_type_back_delay']['size'] ) ? $settings['fancytext_type_back_delay']['size'] : 1200;
			$loop        = ( 'yes' == $settings['fancytext_type_loop'] ) ? 'true' : 'false';

			if ( 'yes' == $settings['fancytext_type_show_cursor'] ) {
				$show_cursor = 'true';
				$cursor_char = ( '' != $settings['fancytext_type_cursor_text'] ) ? $settings['fancytext_type_cursor_text'] : '|';
			} else {
				$show_cursor = 'false';
				$cursor_char = '';
			}

			$data_attr  = 'data-type-speed="' . $type_speed . '" ';
			$data_attr .= 'data-animation="' . $effect_type . '" ';
			$data_attr .= 'data-back-speed="' . $back_speed . '" ';
			$data_attr .= 'data-start-delay="' . $start_delay . '" ';
			$data_attr .= 'data-back-delay="' . $back_delay . '" ';
			$data_attr .= 'data-loop="' . $loop . '" ';
			$data_attr .= 'data-show-cursor="' . $show_cursor . '" ';
			$data_attr .= 'data-cursor-char="' . $cursor_char . '" ';
			$data_attr .= 'data-strings="' . htmlspecialchars( $data_strings ) . '" ';

			return $data_attr;

		} elseif ( 'slide' == $settings['fancytext_effect_type'] ) {
			$speed = ( '' != $settings['fancytext_slide_anim_speed']['size'] ) ? $settings['fancytext_slide_anim_speed']['size'] : 35;

			$pause = ( '' != $settings['fancytext_slide_pause_time']['size'] ) ? $settings['fancytext_slide_pause_time']['size'] : 3000;

			$mousepause = ( 'yes' == $settings['fancytext_slide_pause_hover'] ) ? true : false;

			$data_attr  = 'data-animation="' . $effect_type . '" ';
			$data_attr .= 'data-speed="' . $speed . '" ';
			$data_attr .= 'data-pause="' . $pause . '" ';
			$data_attr .= 'data-mousepause="' . $mousepause . '" ';
			$data_attr .= 'data-strings="' . htmlspecialchars( $data_strings ) . '" ';

			return $data_attr;
		}
	}

	/**
	 * Render Fancy Text output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {
		$html     = '';
		$settings = $this->get_settings();

		$node_id = $this->get_id(); ?>
		<div class="uael-module-content uael-fancy-text-node" <?php echo $this->get_data_attrs( $settings ); ?>>
			<?php if ( ! empty( $settings['fancytext_effect_type'] ) ) { ?>
				<?php echo '<' . $settings['fancytext_title_tag']; ?> class="uael-fancy-text-wrap uael-fancy-text-<?php echo $settings['fancytext_effect_type']; ?>">
					<?php if ( '' != $settings['fancytext_prefix'] ) { ?>
						<span class="uael-fancy-heading uael-fancy-text-prefix"><?php echo $settings['fancytext_prefix']; ?></span>
					<?php } ?>
						<span class="uael-fancy-stack">
					<?php
					if ( 'type' == $settings['fancytext_effect_type'] ) {
						?>
						<span class="uael-fancy-heading uael-fancy-text-main uael-typed-main-wrap "><span class="uael-typed-main"></span><span class="uael-text-holder">.</span></span>
						<?php
					} elseif ( 'slide' == $settings['fancytext_effect_type'] ) {
							$order       = array( "\r\n", "\n", "\r", '<br/>', '<br>' );
							$replace     = '|';
							$str         = str_replace( $order, $replace, trim( $settings['fancytext'] ) );
							$lines       = explode( '|', $str );
							$count_lines = count( $lines );
							$output      = '';
							?>
							<span class="uael-fancy-heading uael-fancy-text-main uael-slide-main uael-adjust-width">
								<span class="uael-slide-main_ul">
									<?php foreach ( $lines as $key => $line ) { ?>
											<span class="uael-slide-block"><span class="uael-slide_text"><?php echo strip_tags( $line ); ?></span>
											</span>
											<?php if ( 1 == $count_lines ) { ?>
												<span class="uael-slide-block"><span class="uael-slide_text"><?php echo strip_tags( $line ); ?></span></span>
											<?php } ?>
										<?php } ?>
								</span>
							</span>
						<?php } ?>
						</span>
					<?php if ( '' != $settings['fancytext_suffix'] ) { ?>
						<span class="uael-fancy-heading uael-fancy-text-suffix"><?php echo $settings['fancytext_suffix']; ?></span>
					<?php } ?>
				<?php echo '</' . $settings['fancytext_title_tag'] . '>'; ?>
			<?php } ?>
		</div>
	<?php
	}

	/**
	 * Render Fancy Heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		function escapeHtml(text) {
			return text
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;")
			.replace(/'/g, "&#039;");
		}
		function addslashes(str) {
			str = str.replace(/\\/g, '\\\\');
			str = str.replace(/\'/g, '\\\'');
			str = str.replace(/\"/g, '\\"');
			str = str.replace(/\0/g, '\\0');
			return str;
		}
		function data_attributes() {

			var effect_type = settings.fancytext_effect_type;
			var ipstr 		= settings.fancytext;
			var strs        = ipstr.split( "\n" );
			strs        	= strs.filter(
				function( str ) {
					if ( '' !== str ) {
						return addslashes( str );
					}
				}
			);
			var data_strings = strs.join( '","' );
			data_strings = '["' + data_strings + '"]';

			if ( 'type' == settings.fancytext_effect_type ) {
				var type_speed  = ( '' != settings.fancytext_type_speed.size ) ? settings.fancytext_type_speed.size : 120;
				var back_speed  = ( '' != settings.fancytext_type_backspeed.size ) ? settings.fancytext_type_backspeed.size : 60;
				var start_delay = ( '' != settings.fancytext_type_start_delay.size ) ? settings.fancytext_type_start_delay.size : 0;
				var back_delay  = ( '' != settings.fancytext_type_back_delay.size ) ? settings.fancytext_type_back_delay.size : 1200;
				var loop        = ( 'yes' == settings.fancytext_type_loop ) ? 'true' : 'false';

				if ( 'yes' == settings.fancytext_type_show_cursor ) {
					var show_cursor = 'true';
					var cursor_char = ( '' != settings.fancytext_type_cursor_text ) ? settings.fancytext_type_cursor_text : '|';
				} else {
					var show_cursor = 'false';
					var cursor_char = '';
				}

				var data_attr  = 'data-type-speed="' + type_speed + '" ';
				data_attr += 'data-animation="' + effect_type + '" ';
				data_attr += 'data-back-speed="' + back_speed + '" ';
				data_attr += 'data-start-delay="' + start_delay + '" ';
				data_attr += 'data-back-delay="' + back_delay + '" ';
				data_attr += 'data-loop="' + loop + '" ';
				data_attr += 'data-show-cursor="' + show_cursor + '" ';
				data_attr += 'data-cursor-char="' + cursor_char + '" ';
				data_attr += 'data-strings="' + escapeHtml( data_strings ) + '" ';

				return data_attr;
			}
			else if ( 'slide' == settings.fancytext_effect_type ) {

				var speed = ( '' != settings.fancytext_slide_anim_speed.size ) ? settings.fancytext_slide_anim_speed.size : 35;

				var pause = ( '' != settings.fancytext_slide_pause_time.size ) ? settings.fancytext_slide_pause_time.size : 3000;

				var mousepause = ( 'yes' == settings.fancytext_slide_pause_hover ) ? true : false;

				var data_attr  = 'data-animation="' + effect_type + '" ';
				data_attr += 'data-speed="' + speed + '" ';
				data_attr += 'data-pause="' + pause + '" ';
				data_attr += 'data-mousepause="' + mousepause + '" ';
				data_attr += 'data-strings="' + escapeHtml( data_strings ) + '" ';

				return data_attr;

			}
		}
		#>
			<# var param = data_attributes(); #>
			<div class="uael-module-content uael-fancy-text-node" {{{ param }}}>				
				<# if ( '' != settings.fancytext_effect_type ) { #>				
					<{{{ settings.fancytext_title_tag }}} class="uael-fancy-text-wrap uael-fancy-text-{{{ settings.fancytext_effect_type }}}" >

						<# if ( '' != settings.fancytext_prefix ) { #>
							<span class="uael-fancy-heading uael-fancy-text-prefix">{{{ settings.fancytext_prefix }}}</span>
						<# } #>
						<span class="uael-fancy-stack">
							<# if ( 'type' == settings.fancytext_effect_type ) { #>
								<span class="uael-fancy-heading uael-fancy-text-main uael-typed-main-wrap"><span class="uael-typed-main"></span><span class="uael-text-holder">.</span></span>
							<# }
							else if ( 'slide' == settings.fancytext_effect_type ) { #>
								<#
								var str 	= settings.fancytext;
								str 		= str.trim();
								str 		= str.replace( /\r?\n|\r/g, "|" );
								var lines 	= str.split("|");
								var count_lines = lines.length;
								var output      = '';
								#>
								<span class="uael-fancy-heading uael-fancy-text-main uael-slide-main uael-adjust-width">
									<span class="uael-slide-main_ul">
										<#
										lines.forEach(function(line){ #>
											<span class="uael-slide-block"><span class="uael-slide_text">{{ line }}</span></span>

											<# if ( 1 == count_lines ) { #>
												<span class="uael-slide-block"><span class="uael-slide_text">{{ line }}</span></span>
											<# } 
										});
										#>
									</span>
								</span>
							<# } #>
						</span>
						<# if ( '' != settings.fancytext_suffix ) { #>
							<span class="uael-fancy-heading uael-fancy-text-suffix">{{{ settings.fancytext_suffix }}}</span>
						<# } #>

					</{{{ settings.fancytext_title_tag }}}>
				<# } #>
			</div>
			<# elementorFrontend.hooks.doAction( 'frontend/element_ready/uael-fancy-heading.default' ); #>
		<?php
	}

}
