<?php
/**
 * UAEL ContentToggle.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\ContentToggle\Widgets;


// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class ContentToggle.
 */
class ContentToggle extends Common_Widget {

	/**
	 * Retrieve Radio Button Switcher Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'ContentToggle' );
	}

	/**
	 * Retrieve Radio Button Switcher Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'ContentToggle' );
	}

	/**
	 * Retrieve Radio Button Switcher Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'ContentToggle' );
	}

	/**
	 * Retrieve the list of scripts the Radio Button Switcher widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'uael-frontend-script', 'uael-content-toggle' ];
	}

	/**
	 * Register General Content controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {
		$this->register_general_content_controls();
	}

	/**
	 * Render button widget classes names.
	 *
	 * @since 0.0.1
	 * @param array  $settings The settings array.
	 * @param int    $node_id The node id.
	 * @param string $section Section one or two.
	 * @return string Concatenated string of classes
	 * @access public
	 */
	public function get_modal_content( $settings, $node_id, $section ) {

		$content_type = $settings[ $section ];
		if ( 'rbs_select_section_1' === $section ) {
			switch ( $content_type ) {
				case 'content':
					global $wp_embed;
					return '<div>' . wpautop( $wp_embed->autoembed( $settings['section_content_1'] ) ) . '</div>';
				break;
				case 'saved_rows':
					return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['section_saved_rows_1'] );
				break;
				case 'saved_page_templates':
					return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['section_saved_pages_1'] );
				break;
				default:
					return;
				break;
			}
		} else {
			switch ( $content_type ) {
				case 'content':
					global $wp_embed;
					return '<div>' . wpautop( $wp_embed->autoembed( $settings['section_content_2'] ) ) . '</div>';
				break;
				case 'saved_rows':
					return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['section_saved_rows_2'] );
				break;
				case 'saved_page_templates':
					return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['section_saved_pages_2'] );
				break;
				default:
					return;
				break;
			}
		}
	}

	/**
	 *  Get Saved Widgets
	 *
	 *  @param string $type Type.
	 *  @since 0.0.1
	 *  @return string
	 */
	public function get_saved_data( $type = 'page' ) {

		$saved_widgets = $this->get_post_template( $type );
		$options[-1]   = __( 'Select', 'uael' );
		if ( count( $saved_widgets ) ) {
			foreach ( $saved_widgets as $saved_row ) {
				$options[ $saved_row['id'] ] = $saved_row['name'];
			}
		} else {
			$options['no_template'] = __( 'It seems that, you have not saved any template yet.', 'uael' );
		}
		return $options;
	}

	/**
	 *  Get Templates based on category
	 *
	 *  @param string $type Type.
	 *  @since 0.0.1
	 *  @return string
	 */
	public function get_post_template( $type = 'page' ) {
		$posts = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => array(
					array(
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $type,
					),
				),
			)
		);

		$templates = array();

		foreach ( $posts as $post ) {

			$templates[] = array(
				'id'   => $post->ID,
				'name' => $post->post_title,
			);
		}

		return $templates;
	}

	/**
	 * Registers all controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {
		// Rbs heading section starts.
		$this->start_controls_section(
			'rbs_section_content_1',
			[
				'label' => __( 'Content 1', 'uael' ),
			]
		);

		// Rbs section 1 heading text.
		$this->add_control(
			'rbs_section_heading_1',
			[
				'label'   => __( 'Heading', 'uael' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Heading 1', 'uael' ),
			]
		);

		// Rbs content section 1.
		$this->add_control(
			'rbs_select_section_1',
			[
				'label'   => __( 'Section', 'uael' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			]
		);

		// Rbs content section 1 - content.
		$this->add_control(
			'section_content_1',
			[
				'label'      => __( 'Description', 'uael' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'This is your first content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'uael' ),
				'rows'       => 10,
				'show_label' => false,
				'dynamic'    => [
					'active' => true,
				],
				'condition'  => [
					'rbs_select_section_1' => 'content',
				],
			]
		);

		// Rbs content section 1 - saved rows.
		$this->add_control(
			'section_saved_rows_1',
			[
				'label'     => __( 'Select Section', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => [
					'rbs_select_section_1' => 'saved_rows',
				],
			]
		);

		// Rbs content section 1 - saved pages.
		$this->add_control(
			'section_saved_pages_1',
			[
				'label'     => __( 'Select Page', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'page' ),
				'default'   => '-1',
				'condition' => [
					'rbs_select_section_1' => 'saved_page_templates',
				],
			]
		);

		// Rbs heading section ends.
		$this->end_controls_section();

		// Rbs content sections starts.
		$this->start_controls_section(
			'rbs_sections_content_2',
			[
				'label' => __( 'Content 2', 'uael' ),
			]
		);

		// Rbs section 2 heading text.
		$this->add_control(
			'rbs_section_heading_2',
			[
				'label'   => __( 'Heading', 'uael' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Heading 2', 'uael' ),
			]
		);

		// Rbs content section 2.
		$this->add_control(
			'rbs_select_section_2',
			[
				'label'   => __( 'Section', 'uael' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			]
		);

		// Rbs content section 2 - content.
		$this->add_control(
			'section_content_2',
			[
				'label'      => __( 'Description', 'uael' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'This is your second content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'uael' ),
				'rows'       => 10,
				'show_label' => false,
				'dynamic'    => [
					'active' => true,
				],
				'condition'  => [
					'rbs_select_section_2' => 'content',
				],
			]
		);

		// Rbs content section 2 - saved rows.
		$this->add_control(
			'section_saved_rows_2',
			[
				'label'     => __( 'Select Section', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => [
					'rbs_select_section_2' => 'saved_rows',
				],
			]
		);

		// Rbs content section 2 - saved pages.
		$this->add_control(
			'section_saved_pages_2',
			[
				'label'     => __( 'Select Page', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'page' ),
				'default'   => '-1',
				'condition' => [
					'rbs_select_section_2' => 'saved_page_templates',
				],
			]
		);

		// Rbs heading section ends.
		$this->end_controls_section();

		// Switch style starts.
		$this->start_controls_section(
			'rbs_switch_style',
			[
				'label' => __( 'Switcher', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Rbs default switch mode.
		$this->add_control(
			'rbs_default_switch',
			[
				'label'        => __( 'Default Display', 'uael' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'off',
				'return_value' => 'on',
				'options'      => [
					'off' => 'Content 1',
					'on'  => 'Content 2',
				],
				'separator'    => 'before',
			]
		);

		// Rbs select switch.
		$this->add_control(
			'rbs_select_switch',
			[
				'label'   => __( 'Switch Style', 'uael' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'round_1',
				'options' => $this->get_switch_type(),
			]
		);

		// Switch - Off color.
		$this->add_control(
			'rbs_switch_color_off',
			[
				'label'     => __( 'Color 1', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-slider' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .uael-toggle input[type="checkbox"] + label:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .uael-toggle input[type="checkbox"] + label:after' => 'border: 0.3em solid {{VALUE}};',
					'{{WRAPPER}} .uael-label-box-active .uael-label-box-switch' => 'background: {{VALUE}};',

				],
			]
		);

		// Switch - On color.
		$this->add_control(
			'rbs_switch_color_on',
			[
				'label'     => __( 'Color 2', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],

				'selectors' => [
					'{{WRAPPER}} .uael-rbs-switch:checked + .uael-rbs-slider' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .uael-rbs-switch:focus + .uael-rbs-slider'     => '-webkit-box-shadow: 0 0 1px {{VALUE}};box-shadow: 0 0 1px {{VALUE}};',
					'{{WRAPPER}} .uael-toggle input[type="checkbox"]:checked + label:before'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .uael-toggle input[type="checkbox"]:checked + label:after'     => '-webkit-transform: translateX(2.5em);-ms-transform: translateX(2.5em);transform: translateX(2.5em);border: 0.3em solid {{VALUE}};',
					'{{WRAPPER}} .uael-label-box-inactive .uael-label-box-switch' => 'background: {{VALUE}};',
				],
			]
		);

		// Switch - Controller Color.
		$this->add_control(
			'rbs_switch_controller',
			[
				'label'     => __( 'Controller Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-slider:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .uael-toggle input[type="checkbox"] + label:after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} span.uael-label-box-switch' => 'color: {{VALUE}};',
				],
			]
		);

		// Switch size.
		$this->add_responsive_control(
			'rds_switch_size',
			[
				'label'     => __( 'Switch Size', 'uael' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min'  => 10,
						'max'  => 35,
						'step' => 1,
					],
				],
				'selectors' => [
					// General.
					'{{WRAPPER}} .uael-main-btn' => 'font-size: {{SIZE}}px;',
				],
			]
		);

		// Switch style ends.
		$this->end_controls_section();

		// Section heading style starts.
		$this->start_controls_section(
			'section_style_heading',
			[
				'label' => __( 'Headings', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Heading 1 - heading.
		$this->add_control(
			'section_heading_1_style',
			[
				'label'     => __( 'Heading 1', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Heading 1 - color.
		$this->add_control(
			'section_heading_1_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-head-1' => 'color: {{VALUE}};',
				],
				'separator' => 'none',
			]
		);

		// Heading 1 - typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_heading_1_typo',
				'selector' => '{{WRAPPER}} .uael-rbs-head-1',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		// Heading 2 - heading.
		$this->add_control(
			'section_heading_2_style',
			[
				'label'     => __( 'Heading 2', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Heading 2 - color.
		$this->add_control(
			'section_heading_2_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-head-2' => 'color: {{VALUE}};',
				],
				'separator' => 'none',
			]
		);

		// Heading 2 - typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_heading_2_typo',
				'selector' => '{{WRAPPER}} .uael-rbs-head-2',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'rbs_header_size',
			[
				'label'     => __( 'HTML Tag', 'uael' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default'   => 'h5',
				'separator' => 'before',
			]
		);

		// heading alignment content Alignment.
		$this->add_responsive_control(
			'rds_heading_alignment',
			[
				'label'     => __( 'Alignment', 'uael' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => [
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
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-toggle' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .uael-ct-desktop-stack--yes .uael-rbs-toggle' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_layout',
			[
				'label'        => __( 'Layout', 'uael' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Stack', 'uael' ),
				'label_off'    => __( 'Inline', 'uael' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'heading_stack_on',
			[
				'label'        => __( 'Responsive Support', 'uael' ),
				'description'  => __( 'Choose on what breakpoint the heading will stack.', 'uael' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'mobile',
				'options'      => [
					'none'   => __( 'No', 'uael' ),
					'tablet' => __( 'For Tablet & Mobile', 'uael' ),
					'mobile' => __( 'For Mobile Only', 'uael' ),
				],
				'condition'    => [
					'heading_layout!' => 'yes',
				],
				'prefix_class' => 'uael-ct-stack--',
			]
		);

		$this->add_control(
			'rbs_advance_setting',
			[
				'label'     => __( 'Advanced', 'uael' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'OFF', 'uael' ),
				'label_on'  => __( 'ON', 'uael' ),
				'default'   => 'no',
				'return'    => 'yes',
			]
		);

		// Heading background color.
		$this->add_control(
			'section_heading_bg_color',
			[
				'label'     => __( 'Background Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-toggle' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'rbs_advance_setting' => 'yes',
				],
			]
		);

		// Heading - Border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'heading_border',
				'label'     => __( 'Border', 'uael' ),
				'selector'  => '{{WRAPPER}} .uael-rbs-toggle',
				'condition' => [
					'rbs_advance_setting' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_border_radius',
			[
				'label'      => __( 'Border Radius', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-rbs-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'rbs_advance_setting' => 'yes',
				],
			]
		);

		// Overall Heading - padding.
		$this->add_responsive_control(
			'rbs_heading_padding',
			[
				'label'      => __( 'Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-rbs-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'rbs_advance_setting' => 'yes',
				],
			]
		);

		// Section heading style ends.
		$this->end_controls_section();

		// Content style starts.
		$this->start_controls_section(
			'rbs_content_style',
			[
				'label' => __( 'Content', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Content 1 - heading.
		$this->add_control(
			'section_content_1_style',
			[
				'label'     => __( 'Content 1', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'rbs_select_section_1' => 'content',
				],
			]
		);

		// Content 1 Color.
		$this->add_control(
			'section_content_1_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'rbs_select_section_1' => 'content',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-content-1.uael-rbs-section-1' => 'color: {{VALUE}};',
				],
			]
		);

		// Content 1 Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'section_content_1_typo',
				'selector'  => '{{WRAPPER}} .uael-rbs-content-1.uael-rbs-section-1',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'condition' => [
					'rbs_select_section_1' => 'content',
				],
				'separator' => 'after',
			]
		);

		// Content 2 - heading.
		$this->add_control(
			'section_content_2_style',
			[
				'label'     => __( 'Content 2', 'uael' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'rbs_select_section_2' => 'content',
				],
			]
		);

		// Content 2 Color.
		$this->add_control(
			'section_content_2_color',
			[
				'label'     => __( 'Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'rbs_select_section_2' => 'content',
				],
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-content-2.uael-rbs-section-2' => 'color: {{VALUE}};',
				],
			]
		);

		// Content 2 Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'section_content_2_typo',
				'selector'  => '{{WRAPPER}} .uael-rbs-content-2.uael-rbs-section-2',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'condition' => [
					'rbs_select_section_2' => 'content',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'rbs_content_advance_setting',
			[
				'label'     => __( 'Advanced', 'uael' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'OFF', 'uael' ),
				'label_on'  => __( 'ON', 'uael' ),
				'default'   => 'no',
				'return'    => 'yes',
			]
		);

		// Content background color.
		$this->add_control(
			'rbs_content_bg_color',
			[
				'label'     => __( 'Background Color', 'uael' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .uael-rbs-toggle-sections'     => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'rbs_content_advance_setting' => 'yes',
				],
			]
		);

		// Content - Border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'content_border',
				'label'     => __( 'Border', 'uael' ),
				'selector'  => '{{WRAPPER}} .uael-rbs-toggle-sections',
				'condition' => [
					'rbs_content_advance_setting' => 'yes',
				],
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label'      => __( 'Border Radius', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-rbs-toggle-sections' => 'overflow: hidden;border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'rbs_content_advance_setting' => 'yes',
				],
			]
		);

		// Content padding.
		$this->add_responsive_control(
			'rbs_content_padding',
			[
				'label'      => __( 'Padding', 'uael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .uael-rbs-toggle-sections' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'rbs_content_advance_setting' => 'yes',
				],
			]
		);

		// Content style ends.
		$this->end_controls_section();

		// Spacing style starts.
		$this->start_controls_section(
			'rbs_switch_spacing',
			[
				'label' => __( 'Spacing', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Spacing Headings and toggle button.
		$this->add_responsive_control(
			'rds_button_headings_spacing',
			[
				'label'     => __( 'Button & Headings', 'uael' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 5,
				],
				'selectors' => [
					// General.
					'{{WRAPPER}} .uael-ct-desktop-stack--no .uael-sec-1'         => 'margin-right: {{SIZE}}%;',
					'{{WRAPPER}} .uael-ct-desktop-stack--no .uael-sec-2'         => 'margin-left: {{SIZE}}%;',

					'{{WRAPPER}} .uael-ct-desktop-stack--yes .uael-sec-1'         => 'margin-bottom: {{SIZE}}%;',
					'{{WRAPPER}} .uael-ct-desktop-stack--yes .uael-sec-2'         => 'margin-top: {{SIZE}}%;',

					'(tablet){{WRAPPER}}.uael-ct-stack--tablet .uael-ct-desktop-stack--no .uael-sec-1'         => 'margin-bottom: {{SIZE}}%;margin-right: 0px;',
					'(tablet){{WRAPPER}}.uael-ct-stack--tablet .uael-ct-desktop-stack--no .uael-sec-2'         => 'margin-top: {{SIZE}}%;margin-left: 0px;',

					'(tablet){{WRAPPER}}.uael-ct-stack--tablet .uael-ct-desktop-stack--no .uael-rbs-toggle'         => 'flex-direction: column;',

					'(mobile){{WRAPPER}}.uael-ct-stack--mobile .uael-ct-desktop-stack--no .uael-sec-1'         => 'margin-bottom: {{SIZE}}%;margin-right: 0px;',
					'(mobile){{WRAPPER}}.uael-ct-stack--mobile .uael-ct-desktop-stack--no .uael-sec-2'         => 'margin-top: {{SIZE}}%;margin-left: 0px;',

					'(mobile){{WRAPPER}}.uael-ct-stack--mobile .uael-ct-desktop-stack--no .uael-rbs-toggle'         => 'flex-direction: column;',
				],
			]
		);

		// Spacing Headings and content.
		$this->add_responsive_control(
			'rds_headings_content_spacing',
			[
				'label'     => __( 'Content & Headings', 'uael' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					// General.
					'{{WRAPPER}} .uael-rbs-toggle' => 'margin-bottom: {{SIZE}}px;',
				],
			]
		);

		// Spacing style ends.
		$this->end_controls_section();
	}

	/**
	 * Render content type list.
	 *
	 * @since 0.0.1
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {

		$content_type = array(
			'content'              => __( 'Content', 'uael' ),
			'saved_rows'           => __( 'Saved Section', 'uael' ),
			'saved_page_templates' => __( 'Saved Page', 'uael' ),
		);

		return $content_type;
	}

	/**
	 * Render content type list.
	 *
	 * @since 0.0.1
	 * @return array Array of content type
	 * @access public
	 */
	public function get_switch_type() {

		$switch_type = array(
			'round_1'   => __( 'Round 1', 'uael' ),
			'round_2'   => __( 'Round 2', 'uael' ),
			'rectangle' => __( 'Rectangle', 'uael' ),
			'label_box' => __( 'Label Box', 'uael' ),
		);

		return $switch_type;
	}

	/**
	 * Render Radio Button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings  = $this->get_settings();
		$node_id   = $this->get_id();
		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();
		ob_start();
		include 'template.php';
		$html = ob_get_clean();
		echo $html;
	}

	/**
	 * Render Timeline output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {}

}
