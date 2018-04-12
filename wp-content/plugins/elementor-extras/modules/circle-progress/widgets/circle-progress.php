<?php
namespace ElementorExtras\Modules\CircleProgress\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Circle_Progress
 *
 * @since 0.1.0
 */
class Circle_Progress extends Extras_Widget {

	/**
	 * Returns the widget name
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	public function get_name() {
		return 'circle-progress';
	}

	/**
	 * Returns the widget title
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	public function get_title() {
		return __( 'Circle Progress', 'elementor-extras' );
	}

	/**
	 * Returns the editor panel icon
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	public function get_icon() {
		return 'nicon nicon-circle-progress';
	}

	/**
	 * Returns the editor panel category for this widget
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	/**
	 * Returns script dependencies
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	public function get_script_depends() {
		return [
			'circle-progress',
			'jquery-appear',
			'jquery-easing',
		];
	}

	/**
	 * Registers the widget controls
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_circle',
			[
				'label' => __( 'Circle', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'value_heading',
				[
					'label'			=> __( 'Value', 'elementor-extras' ),
					'type' 			=> Controls_Manager::HEADING,
					'separator'		=> 'before',
				]
			);

			$this->add_control(
				'value',
				[
					'label' 		=> __( 'Value', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 1,
							'step'	=> 0.01,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'value_position',
				[
					'label'			=> __( 'Value Position', 'elementor-extras' ),
					'description'	=> __( 'Position of the value relative to circle.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'inside',
					'options' 		=> [
						'inside' 	=> __( 'Inside', 'elementor-extras' ),
						'below' 	=> __( 'Below', 'elementor-extras' ),
						'hide' 		=> __( 'Hide', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'icon_heading',
				[
					'label'			=> __( 'Icon', 'elementor-extras' ),
					'type' 			=> Controls_Manager::HEADING,
					'separator'		=> 'before',
					'condition'		=> [
						'value_position!' => 'inside',
					],
				]
			);

			$this->add_control(
				'icon',
				[
					'label' 		=> __( 'Icon', 'elementor-extras' ),
					'type' 			=> Controls_Manager::ICON,
					'label_block' 	=> true,
					'default' 		=> '',
					'condition'		=> [
						'value_position!' => 'inside',
					],
				]
			);

			$this->add_control(
				'suffix_heading',
				[
					'label'			=> __( 'Suffix', 'elementor-extras' ),
					'type' 			=> Controls_Manager::HEADING,
					'separator'		=> 'before',
				]
			);

			$this->add_control(
				'suffix',
				[
					'type'		=> Controls_Manager::TEXT,
					'label' 	=> __( 'Suffix', 'elementor-extras' ),
					'default'	=> '%',
					'separator' => 'none'
				]
			);

			$this->add_control(
				'suffix_position',
				[
					'label'		=> __( 'Suffix Position', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'after',
					'options' 	=> [
						'after' 	=> __( 'After', 'elementor-extras' ),
						'before' 	=> __( 'Before', 'elementor-extras' ),
					],
					'prefix_class'	=> 'elementor-circle-progress-suffix-'
				]
			);

			$this->add_responsive_control(
				'suffix_vertical_align',
				[
					'label' 		=> __( 'Suffix Vertical Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'top',
					'options' 		=> [
						'top'    		=> [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-stretch',
						],
					],
					'prefix_class'		=> 'elementor-circle-progress-suffix-'
				]
			);

			$this->add_control(
				'suffix_top_adjustment',
				[
					'label' 		=> __( 'Suffix Top Offset', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '0.5',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 3,
							'step'	=> 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-circle-progress-value .suffix' => 'margin-top: {{SIZE}}em;',
					],
					'condition'	=> [
						'suffix_vertical_align' => 'top',
					]
				]
			);

			$this->add_control(
				'animation_heading',
				[
					'label'			=> __( 'Animation', 'elementor-extras' ),
					'type' 			=> Controls_Manager::HEADING,
					'separator'		=> 'before',
				]
			);

			$this->add_control(
				'reverse',
				[
					'label' 		=> __( 'Reverse', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'frontend_available' => true
				]
			);

			$this->add_control(
				'easing',
				[
					'label'		=> __( 'Easing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'easeInOutCubic',
					'options' 	=> [
						'easeInQuad' 			=> __( 'easeInQuad', 'elementor-extras' ),
						'easeOutQuad' 			=> __( 'easeOutQuad', 'elementor-extras' ),
						'easeInOutQuad' 		=> __( 'easeInOutQuad', 'elementor-extras' ),
						'easeInCubic' 			=> __( 'easeInCubic', 'elementor-extras' ),
						'easeOutCubic' 			=> __( 'easeOutCubic', 'elementor-extras' ),
						'easeInOutCubic'		=> __( 'easeInOutCubic', 'elementor-extras' ),
						'easeInQuart' 			=> __( 'easeInQuart', 'elementor-extras' ),
						'easeOutQuart' 			=> __( 'easeOutQuart', 'elementor-extras' ),
						'easeInOutQuart' 		=> __( 'easeInOutQuart', 'elementor-extras' ),
						'easeInQuint' 			=> __( 'easeInQuint', 'elementor-extras' ),
						'easeOutQuint' 			=> __( 'easeOutQuint', 'elementor-extras' ),
						'easeInOutQuint' 		=> __( 'easeInOutQuint', 'elementor-extras' ),
						'easeInSine' 			=> __( 'easeInSine', 'elementor-extras' ),
						'easeOutSine' 			=> __( 'easeOutSine', 'elementor-extras' ),
						'easeInOutSine' 		=> __( 'easeInOutSine', 'elementor-extras' ),
						'easeInExpo' 			=> __( 'easeInExpo', 'elementor-extras' ),
						'easeOutExpo' 			=> __( 'easeOutExpo', 'elementor-extras' ),
						'easeInOutExpo' 		=> __( 'easeInOutExpo', 'elementor-extras' ),
						'easeInCirc' 			=> __( 'easeInCirc', 'elementor-extras' ),
						'easeOutCirc' 			=> __( 'easeOutCirc', 'elementor-extras' ),
						'easeInOutCirc' 		=> __( 'easeInOutCirc', 'elementor-extras' ),
						'easeInElastic' 		=> __( 'easeInElastic', 'elementor-extras' ),
						'easeOutElastic' 		=> __( 'easeOutElastic', 'elementor-extras' ),
						'easeInOutElastic' 		=> __( 'easeInOutElastic', 'elementor-extras' ),
						'easeInBack' 			=> __( 'easeInBack', 'elementor-extras' ),
						'easeOutBack' 			=> __( 'easeOutBack', 'elementor-extras' ),
						'easeInOutBack' 		=> __( 'easeInOutBack', 'elementor-extras' ),
						'easeInBounce' 			=> __( 'easeInBounce', 'elementor-extras' ),
						'easeOutBounce' 		=> __( 'easeOutBounce', 'elementor-extras' ),
						'easeInOutBounce' 		=> __( 'easeInOutBounce', 'elementor-extras' ),
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'duration',
				[
					'label' 		=> __( 'Duration (ms)', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 1,
							'max' 	=> 3000,
							'step'	=> 100,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'angle',
				[
					'label' 		=> __( 'Start Angle', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 2 * M_PI,
							'step'	=> 0.001,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'appear_offset',
				[
					'label' 		=> __( 'Appear Offset', 'elementor-extras' ),
					'description'	=> __( 'Specifies the offset, relative to when the widget enteres the viewport, after which the animation starts', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 10,
							'max' 	=> 1000,
						],
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text',
			[
				'label' => __( 'Text', 'elementor-extras' ),
			]
		);

			$this->add_control(
			'text',
				[
					'label' => '',
					'type' => Controls_Manager::WYSIWYG,
					'default' => __( 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor-extras' ),
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_circle_style',
			[
				'label' => __( 'Circle', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'size',
				[
					'label' 		=> __( 'Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 100,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 10,
							'max' 	=> 1000,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'thickness',
				[
					'label' 		=> __( 'Thickness (%)', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 10,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 1,
							'max' 	=> 100,
						],
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'lineCap',
				[
					'label'		=> __( 'Line Cap', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'butt',
					'options' 	=> [
						'butt' 		=> __( 'Butt', 'elementor-extras' ),
						'round' 	=> __( 'Round', 'elementor-extras' ),
						'square' 	=> __( 'Square', 'elementor-extras' ),
					],
					'frontend_available' => true
				]
			);

			$gradient = new Repeater();

			$gradient->add_control(
				'color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
					    'type' 	=> Scheme_Color::get_type(),
					    'value' => Scheme_Color::COLOR_4,
					],
				]
			);

			$scheme = new Scheme_Color;
			$scheme_colors = $scheme->get_scheme_value();

			$this->add_control(
				'fill',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'color' => $scheme_colors[1]
						],
					],
					'fields' 		=> array_values( $gradient->get_controls() ),
					'title_field' 	=> '{{{ color }}}'
				]
			);

			$this->add_control(
				'gradient_angle',
				[
					'label'		=> __( 'Gradient Angle (&deg;)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '0',
					'options' 	=> [
						'2' 	=> __( '0', 'elementor-extras' ),
						'4' 	=> __( '45', 'elementor-extras' ),
						'0.5' 	=> __( '90', 'elementor-extras' ),
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'emptyFill',
				[
					'label' 	=> __( 'Empty Fill', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
					    'type' 	=> Scheme_Color::get_type(),
					    'value' => Scheme_Color::COLOR_1,
					],
					'frontend_available' => true
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_value_style',
			[
				'label' => __( 'Value', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'value_color',
				[
					'label' 	=> __( 'Value Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default' 	=> '',
					'selectors' => [
						'{{WRAPPER}} .elementor-circle-progress-value' => 'color: {{VALUE}};',
					],
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
				]
			);

			$this->add_control(
				'suffix_color',
				[
					'label' 	=> __( 'Suffix Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default' 	=> '',
					'selectors' => [
						'{{WRAPPER}} .elementor-circle-progress-value .suffix' => 'color: {{VALUE}};',
					],
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
				]
			);

			$this->add_control(
				'value_spacing',
				[
					'label' 		=> __( 'Value Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 200,
						],
					],
					'condition'	=> [
						'value_position!'	=> 'inside'
					],
					'selectors'	=> [
						'{{WRAPPER}}.elementor-circle-progress-position-below .elementor-circle-progress-value' => 'margin-top: {{SIZE}}{{UNIT}}',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'value_shadow',
					'selector' 	=> '{{WRAPPER}} .elementor-circle-progress-value',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'value_typography',
					'selector' 	=> '{{WRAPPER}} .elementor-circle-progress-value',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'		=> [
					'value_position!' 	=> 'inside',
					'icon!'				=> '',
				],
			]
		);

			$this->add_control(
				'icon_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default' 	=> '',
					'selectors' => [
						'{{WRAPPER}} .elementor-circle-progress-icon' => 'color: {{VALUE}};',
					],
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'condition'		=> [
						'value_position!' 	=> 'inside',
						'icon!'				=> '',
					],
				]
			);

			$this->add_control(
				'icon_size',
				[
					'label' 		=> __( 'Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 9,
							'max' 	=> 100,
						],
					],
					'range' 	=> [
						'em' 	=> [
							'min' 	=> 1,
							'max' 	=> 10,
							'step'	=> 0.1,
						],
					],
					'range' 	=> [
						'rem' 	=> [
							'min' 	=> 1,
							'max' 	=> 10,
							'step'	=> 0.1,
						],
					],
					'size_units' 	=> [ 'px', 'em', 'rem' ],
					'condition'		=> [
						'value_position!' 	=> 'inside',
						'icon!'				=> '',
					],
					'selectors'	=> [
						'{{WRAPPER}} .elementor-circle-progress-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __( 'Text', 'elementor-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'text_color',
				[
					'label' 	=> __( 'Text Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default' 	=> '',
					'selectors' => [
						'{{WRAPPER}} .elementor-circle-progress-text' => 'color: {{VALUE}};',
					],
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'text_shadow',
					'selector' 	=> '{{WRAPPER}} .elementor-circle-progress-text',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'text_typography',
					'selector' 	=> '{{WRAPPER}} .elementor-circle-progress-text',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				]
			);

		$this->end_controls_section();
		
	}

	/**
	 * Renders the template
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$circle_progress_fill = array();

		$this->add_render_attribute( 'wrapper', 'class', [
			'elementor-circle-progress',
			'elementor-circle-progress-position-' . $settings['value_position'],
		] );

		if( ! empty( $settings['suffix'] ) ) {
			$this->add_render_attribute( 'wrapper', 'data-suffix', $settings['suffix'] );
		}

		if ( $settings['appear_offset']['size'] ) {
			$this->add_render_attribute( 'wrapper', 'data-appear-top-offset', $settings['appear_offset']['size'] );
		}

		if ( count( $settings['fill'] ) > 0 ) {
			if ( count( $settings['fill'] ) === 1 ) {
				if ( ! empty( $settings['fill'][0]['color'] ) ) {
					$circle_progress_fill['color'] = $settings['fill'][0]['color'];
				}
			} else { // Gradient
				$circle_progress_fill['gradient'] = array();
				foreach (  $settings['fill'] as $fill ) {
					if ( ! empty( $fill['color'] ) ) {
						$circle_progress_fill['gradient'][] = $fill['color'];
					}
				}

				$gradient_angle = ( (int)$settings['gradient_angle'] > 0 ) ? (int)$settings['gradient_angle'] : 4;

				$circle_progress_fill['gradientAngle'] = M_PI / $gradient_angle;
			}

			if ( count( $circle_progress_fill ) > 0 ) {
				$circle_progress_settings['fill'] = json_encode( $circle_progress_fill );
				$this->add_render_attribute( 'wrapper', 'data-fill', $circle_progress_settings['fill'] );
			}
		}

		?><div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

			<?php if ( ! empty( $settings['icon'] ) && 'inside' !== $settings['value_position'] ) { $this->render_icon( $settings ); } ?>

			<?php if ( 'inside' === $settings['value_position'] ) { $this->render_value( $settings ); } ?>
		</div>

		<?php if ( 'below' === $settings['value_position'] ) { $this->render_value( $settings ); } ?>

		<?php if ( $settings['text'] ) { $this->render_text( $settings ); }
	}

	/**
	 * Renders the template for the icon
	 *
	 * @since 1.8.0
	 * @access protected
	 */
	protected function render_icon( $settings ) {

		$this->add_render_attribute( 'icon-wrapper', 'class', [ 'elementor-circle-progress-icon', 'ee-icon' ] );
		$this->add_render_attribute( 'icon', 'class', esc_attr( $settings['icon'] ) );

		?><span <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
			<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
		</span><?php
	}

	/**
	 * Renders the template for the value
	 *
	 * @since 1.8.0
	 * @access protected
	 */
	protected function render_value( $settings ) {

		$this->add_render_attribute( 'value-wrapper', 'class', 'elementor-circle-progress-value' );
		$this->add_render_attribute( 'value', 'class', 'value' );

		$this->add_inline_editing_attributes( 'suffix', 'basic' );
		$this->add_render_attribute( 'suffix', 'class', 'suffix' );

		?><div <?php echo $this->get_render_attribute_string( 'value-wrapper' ); ?>>

			<span <?php echo $this->get_render_attribute_string( 'value' ); ?>></span>

			<?php if ( $settings['suffix'] ) { ?>
				<span <?php echo $this->get_render_attribute_string( 'suffix' ); ?>>
					<?php echo $settings['suffix']; ?>
				</span>
			<?php } ?>

		</div><?php
	}

	/**
	 * Renders the template for the text
	 *
	 * @since 1.8.0
	 * @access protected
	 */
	protected function render_text( $settings ) {

		$this->add_inline_editing_attributes( 'text', 'advanced' );
		$this->add_render_attribute( 'text', 'class', 'elementor-circle-progress-text' );

		?><div <?php echo $this->get_render_attribute_string( 'text' ); ?>>
			<?php echo $this->parse_text_editor( $settings['text'] ); ?>
		</div><?php
	}

	/**
	 * Renders the main js template
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	protected function _content_template() {
		?><#

		var circle_progress_fill = {},
			entityMap = {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#39;',
				'/': '&#x2F;',
				'`': '&#x60;',
				'=': '&#x3D;'
			};

		view.addRenderAttribute( 'wrapper', 'class', [
			'elementor-circle-progress',
			'elementor-circle-progress-position-' + settings.value_position
		] );

		if ( settings.suffix ) {
			view.addRenderAttribute( 'wrapper', 'data-suffix', settings.suffix );
		}

		if ( settings.appear_offset ) {
			view.addRenderAttribute( 'wrapper', 'data-appear-top-offset', settings.appear_offset.size );
		}

		if ( settings.fill.length > 0 ) {
			if ( settings.fill.length === 1 ) {

				if ( settings.fill[0].color != '' ) {
					circle_progress_fill.color = settings.fill[0].color;
				}

			} else {

				circle_progress_fill.gradient = [];
				var gradient_angle = ( settings.gradient_angle > 0 ) ? parseInt(settings.gradient_angle) : 4;

				_.each( settings.fill, function( fill ) {
					if ( fill.color != '' ) circle_progress_fill.gradient.push( fill.color );
				});
				circle_progress_fill.gradientAngle = Math.PI / gradient_angle;
			}
		}

		if ( ! jQuery.isEmptyObject( circle_progress_fill ) ) {

			circle_progress_fill = JSON.stringify( circle_progress_fill );
			circle_progress_fill = circle_progress_fill.replace( /[&<>"'`=\/]/g, function (s) {
				return entityMap[s];
			});

			circle_progress_fill = $('<textarea />').html( circle_progress_fill ).text();

			view.addRenderAttribute( 'wrapper', 'data-fill', circle_progress_fill );
		}

		#><div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>

			<# if ( settings.icon && 'inside' !== settings.value_position ) { #>
				<?php $this->_icon_template(); ?>
			<# } #>

			<# if ( 'inside' === settings.value_position ) { #>
				<?php $this->_value_template(); ?>
			<# } #>

		</div>

		<# if ( 'below' === settings.value_position ) { #>
			<?php $this->_value_template(); ?>
		<# } #>

		<# if ( settings.text ) { #>
			<?php $this->_text_template(); ?>
		<# } #>

		<?php
	}

	/**
	 * Renders the js template for icon
	 *
	 * @since 1.8.0
	 * @access protected
	 */
	protected function _icon_template() {
		?><#

		view.addRenderAttribute( 'icon-wrapper', 'class', [ 'elementor-circle-progress-icon', 'ee-icon' ] );
		view.addRenderAttribute( 'icon', 'class', settings.icon );

		#><div {{{ view.getRenderAttributeString( 'icon-wrapper' ) }}}>
			<i {{{ view.getRenderAttributeString( 'icon' ) }}}></i>
		</div><?php
	}

	/**
	 * Renders the js template for the value
	 *
	 * @since 1.8.0
	 * @access protected
	 */
	protected function _value_template() {
		?><#

		view.addRenderAttribute( 'value-wrapper', 'class', 'elementor-circle-progress-value' );
		view.addRenderAttribute( 'value', 'class', 'value' );

		view.addInlineEditingAttributes( 'suffix', 'basic' );
		view.addRenderAttribute( 'suffix', 'class', 'suffix' );

		#><div {{{ view.getRenderAttributeString( 'value-wrapper' ) }}}>

			<span {{{ view.getRenderAttributeString( 'value' ) }}}></span>

			<# if ( settings.suffix ) { #>
				<span {{{ view.getRenderAttributeString( 'suffix' ) }}}>{{{ settings.suffix }}}</span>
			<# } #>

		</div><?php
	}

	/**
	 * Renders the js template for the wysiwyg
	 *
	 * @since 1.8.0
	 * @access protected
	 */
	protected function _text_template() {
		?><#

		view.addRenderAttribute( 'text', 'class', 'elementor-circle-progress-text' );
		view.addInlineEditingAttributes( 'text', 'advanced' );

		#><div {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</div><?php
	}
}
