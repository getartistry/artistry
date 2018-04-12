<?php
/**
 * UAEL Before After Slider.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\BaSlider\Widgets;


// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

// UltimateElementor Classes.
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Before After.
 */
class BaSlider extends Common_Widget {

	/**
	 * Retrieve Before After Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'BaSlider' );
	}

	/**
	 * Retrieve Before After Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'BaSlider' );
	}

	/**
	 * Retrieve Before After Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'BaSlider' );
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'uael-frontend-script', 'uael-twenty-twenty', 'uael-move', 'imagesloaded' ];
	}


	/**
	 * Register Before After controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_general_content_controls();
	}

	/**
	 * Register Before After General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_before',
			[
				'label' => __( 'Before', 'uael' ),
			]
		);

		$this->add_control(
			'before_src',
			[
				'label'       => __( 'Before Image Source', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'media',
				'separator'   => 'before',
				'label_block' => true,
				'options'     => [
					'media' => __( 'Media', 'uael' ),
					'url'   => __( 'URL', 'uael' ),
				],
			]
		);

		$this->add_control(
			'before_image',
			[
				'label'     => __( 'Before Photo', 'uael' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'before_src' => 'media',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'before_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `before_image_size` and `before_image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
				'condition' => [
					'before_src' => 'media',
				],
			]
		);

		$this->add_control(
			'before_img_url',
			[
				'label'       => __( 'Before Photo URL', 'uael' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => [
					'before_src' => 'url',
				],
			]
		);

		$this->add_control(
			'before_text',
			[
				'label'     => __( 'Before Label', 'uael' ),
				'type'      => Controls_Manager::TEXT,
				'selector'  => '{{WRAPPER}} .uael-infobox-title-prefix',
				'default'   => __( 'Before', 'uael' ),
				'dynamic'   => [
					'active' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-before-label:before' => 'content: "{{VALUE}}";',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_after',
			[
				'label' => __( 'After', 'uael' ),
			]
		);

		$this->add_control(
			'after_src',
			[
				'label'       => __( 'After Image Source', 'uael' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'media',
				'separator'   => 'before',
				'label_block' => true,
				'options'     => [
					'media' => __( 'Media', 'uael' ),
					'url'   => __( 'URL', 'uael' ),
				],
			]
		);

		$this->add_control(
			'after_image',
			[
				'label'     => __( 'After Photo', 'uael' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'after_src' => 'media',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'after_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `after_image_size` and `after_image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
				'condition' => [
					'after_src' => 'media',
				],
			]
		);

		$this->add_control(
			'after_img_url',
			[
				'label'       => __( 'After Photo URL', 'uael' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => [
					'after_src' => 'url',
				],
			]
		);

		$this->add_control(
			'after_text',
			[
				'label'     => __( 'After Label', 'uael' ),
				'type'      => Controls_Manager::TEXT,
				'selector'  => '{{WRAPPER}} .uael-infobox-title-prefix',
				'default'   => __( 'After', 'uael' ),
				'dynamic'   => [
					'active' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-after-label:before' => 'content: "{{VALUE}}";',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Orientation', 'uael' ),
			]
		);

		$this->add_control(
			'orientation',
			[
				'label'   => __( 'Before After Slider Orientation', 'uael' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'vertical'   => [
						'title' => __( 'Vertical', 'uael' ),
						'icon'  => 'eicon-section',
					],
					'horizontal' => [
						'title' => __( 'Horizontal', 'uael' ),
						'icon'  => 'fa fa-columns',
					],
				],
				'default' => 'horizontal',
				'toggle'  => false,
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'     => __( 'Alignment', 'uael' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'-right' => [
						'title' => __( 'Left', 'uael' ),
						'icon'  => 'fa fa-align-left',
					],
					' '      => [
						'title' => __( 'Center', 'uael' ),
						'icon'  => 'fa fa-align-center',
					],
					'-left'  => [
						'title' => __( 'Right', 'uael' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => '-right',
				'selectors' => [
					'{{WRAPPER}}' => 'margin{{VALUE}}:auto;',
				],
				'toggle'    => false,
			]
		);

		$this->add_control(
			'move_on_hover',
			[
				'label'        => __( 'Move on Hover', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_on'     => __( 'Yes', 'uael' ),
				'label_off'    => __( 'No', 'uael' ),
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => __( 'Overlay Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.5)',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_handle',
			[
				'label' => __( 'Comparison Handle', 'uael' ),
			]
		);

		$this->add_control(
			'initial_offset',
			[
				'label'       => __( 'Handle Initial Offset', 'uael' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ '%' ],
				'default'     => [
					'size' => 50,
				],
				'range'       => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator'   => 'before',
				'label_block' => true,
				'options'     => [
					'0.0' => __( '0.0', 'uael' ),
					'0.1' => __( '0.1', 'uael' ),
					'0.2' => __( '0.2', 'uael' ),
					'0.3' => __( '0.3', 'uael' ),
					'0.4' => __( '0.4', 'uael' ),
					'0.5' => __( '0.5', 'uael' ),
					'0.6' => __( '0.6', 'uael' ),
					'0.7' => __( '0.7', 'uael' ),
					'0.8' => __( '0.8', 'uael' ),
					'0.9' => __( '0.9', 'uael' ),
				],
			]
		);

		$this->add_control(
			'handle_color',
			[
				'label'     => __( 'Handle Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-handle' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle::before' => 'background:  {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle::after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'border-right-color:  {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'border-left-color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-up-arrow' => 'border-bottom-color:  {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-down-arrow' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thickness',
			[
				'label'      => __( 'Handle Thickness', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 5,
				],
				'range'      => [
					'px' => [
						'max' => 15,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle::before' => 'width: {{SIZE}}{{UNIT}}; margin-left:calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle::after' => 'width: {{SIZE}}{{UNIT}}; margin-left:calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .twentytwenty-handle' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle::before' => 'height: {{SIZE}}{{UNIT}}; margin-top:calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle::after' => 'height: {{SIZE}}{{UNIT}}; margin-top:calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'circle_width',
			[
				'label'      => __( 'Circle Width', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 40,
				],
				'range'      => [
					'px' => [
						'max' => 150,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .twentytwenty-handle' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; margin-left:calc( -{{SIZE}}{{UNIT}}/2 - {{thickness.size}}{{thickness.unit}} ); margin-top:calc( -{{SIZE}}{{UNIT}}/2 - {{thickness.size}}{{thickness.unit}} );',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:before' => 'margin-bottom: calc( ( {{SIZE}}{{UNIT}} + ( {{thickness.size}}{{thickness.unit}} * 2 ) ) / 2 );',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:after' => 'margin-top: calc( ( {{SIZE}}{{UNIT}} + ( {{thickness.size}}{{thickness.unit}} * 2 ) ) / 2 );',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:before' => 'margin-left: calc( ( {{SIZE}}{{UNIT}} + ( {{thickness.size}}{{thickness.unit}} * 2 ) ) / 2 );',
					'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:after' => 'margin-right: calc( ( {{SIZE}}{{UNIT}} + ( {{thickness.size}}{{thickness.unit}} * 2 ) ) / 2 );',
				],
			]
		);

		$this->add_control(
			'circle_radius',
			[
				'label'      => __( 'Circle Radius', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [
					'size' => 100,
					'unit' => '%',
				],
				'range'      => [
					'%' => [
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .twentytwenty-handle' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'triangle_size',
			[
				'label'      => __( 'Triangle Size', 'uael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 6,
				],
				'range'      => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'border-right-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-left-arrow, {{WRAPPER}} .twentytwenty-right-arrow, {{WRAPPER}} .twentytwenty-up-arrow, {{WRAPPER}} .twentytwenty-down-arrow' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'margin-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'margin-left: calc({{SIZE}}{{UNIT}}/2);',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Before/After Label', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'typography',
			[
				'label'     => __( 'Before/After Label', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_on',
			[
				'label'        => __( 'Show Label On', 'uael' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'hover',
				'label_block'  => true,
				'options'      => [
					'hover'  => __( 'Hover Only', 'uael' ),
					'normal' => __( 'Normal Only', 'uael' ),
					'both'   => __( 'Hover & Normal', 'uael' ),
				],
				'prefix_class' => 'uael-ba-label-',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_bg_color',
			[
				'label'     => __( 'Background Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label'      => __( 'Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'vertical_alignment',
			[
				'label'        => __( 'Alignment', 'uael' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'flex-start' => [
						'title' => __( 'Left', 'uael' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'uael' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'uael' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'      => 'flex-start',
				'selectors'    => [
					'{{WRAPPER}} .twentytwenty-before-label, {{WRAPPER}} .twentytwenty-after-label' => 'justify-content: {{VALUE}};',
				],
				'toggle'       => false,
				'condition'    => [
					'orientation' => 'vertical',
				],
				'prefix_class' => 'uael%s-ba-valign-',
			]
		);

		$this->add_responsive_control(
			'horizontal_alignment',
			[
				'label'        => __( 'Alignment', 'uael' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'flex-start' => [
						'title' => __( 'Top', 'uael' ),
						'icon'  => 'fa fa-long-arrow-up',
					],
					'center'     => [
						'title' => __( 'Center', 'uael' ),
						'icon'  => 'fa fa-arrows-v',
					],
					'flex-end'   => [
						'title' => __( 'Bottom', 'uael' ),
						'icon'  => 'fa fa-long-arrow-down',
					],
				],
				'default'      => 'flex-start',
				'selectors'    => [
					'{{WRAPPER}} .twentytwenty-before-label, {{WRAPPER}} .twentytwenty-after-label' => 'align-items: {{VALUE}};',
				],
				'prefix_class' => 'uael%s-ba-halign-',
				'toggle'       => false,
				'condition'    => [
					'orientation' => 'horizontal',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the Image URL as per source
	 *
	 * @param string $position The before/after position.
	 * @since 0.0.1
	 */
	protected function get_image_src( $position ) {
		if ( '' == $position ) {
			return;
		}

		$url      = '';
		$settings = $this->get_settings();

		if ( 'media' == $settings[ $position . '_src' ] ) {

			if ( '' != $settings[ $position . '_image' ]['id'] ) {

				$url = Group_Control_Image_Size::get_attachment_image_src( $settings[ $position . '_image' ]['id'], $position . '_image', $settings );
			} else {
				$url = $settings[ $position . '_image' ]['url'];
			}
		} else {

			$url = $settings[ $position . '_img_url' ];
		}

		return $url;
	}

	/**
	 * Render Before After output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();
		$node_id  = $this->get_id();
		ob_start();
		$before_img = $this->get_image_src( 'before' );
		$after_img  = $this->get_image_src( 'after' );
		?>
		<div class="uael-before-after-slider">
			<div class="uael-ba-container" data-move-on-hover="<?php echo $settings['move_on_hover']; ?>" data-orientation="<?php echo $settings['orientation']; ?>" data-offset="<?php echo ( $settings['initial_offset']['size'] / 100 ); ?>">
				<img class="uael-before-img" style="position: absolute;" src="<?php echo $before_img; ?>" alt="<?php echo $settings['before_text']; ?>"/>
				<img class="uael-after-img" src="<?php echo $after_img; ?>" alt="<?php echo $settings['after_text']; ?>"/>
			</div>
		</div>
		<?php
		$html = ob_get_clean();
		echo $html;
	}

	/**
	 * Render Before After Slider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		var before_img = '';
		var after_img = '';

		if( 'media' == settings.before_src ) {

			var before_image = {
				id: settings.before_image.id,
				url: settings.before_image.url,
				size: settings.before_image_size,
				dimension: settings.before_image_custom_dimension,
				model: view.getEditModel()
			};
			before_img = elementor.imagesManager.getImageUrl( before_image );
		} else {
			before_img = settings.before_img_url;
		}

		if( 'media' == settings.after_src ) {
			var after_image = {
				id: settings.after_image.id,
				url: settings.after_image.url,
				size: settings.after_image_size,
				dimension: settings.after_image_custom_dimension,
				model: view.getEditModel()
			};
			after_img = elementor.imagesManager.getImageUrl( after_image );
		} else {
			after_img = settings.after_img_url;
		}

		if ( ! before_img || ! after_img ) {
			return;
		}

		#>
		<div class="uael-before-after-slider">
			<div class="uael-ba-container" data-move-on-hover="{{settings.move_on_hover}}" data-orientation="{{settings.orientation}}" data-offset="{{settings.initial_offset.size/100}}">
				<img class="uael-before-img" style="position: absolute;" src="{{before_img}}" alt="{{settings.before_text}}"/>
				<img class="uael-after-img" src="{{after_img}}" alt="{{settings.after_text}}"/>
			</div>
		</div>
		<# elementorFrontend.hooks.doAction( 'frontend/element_ready/uael-ba-slider.default' ); #>
		<?php
	}

}

