<?php
namespace ElementorExtras\Modules\Heading\Widgets;

use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Group_Control_Long_Shadow;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Heading
 *
 * @since 0.1.0
 */
class Heading extends Extras_Widget {

	public function get_name() {
		return 'heading-extended';
	}

	public function get_title() {
		return __( 'Heading Extra', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-heading-extended';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 0.1.0
	 **/
	public function get_script_depends() {
		return [ 'jquery-long-shadow' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'title',
				[
					'label' 		=> __( 'Title', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXTAREA,
					'placeholder' 	=> __( 'Enter your title', 'elementor-extras' ),
					'default' 		=> __( 'This is heading element', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'link',
				[
					'label' 		=> __( 'Link', 'elementor-extras' ),
					'type' 			=> Controls_Manager::URL,
					'placeholder' 	=> esc_url( home_url( '/' ) ),
					'default' 		=> [
						'url' 		=> '',
					],
					'separator'		=> 'before',
				]
			);

			$this->add_control(
				'size',
				[
					'label' 		=> __( 'Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'default',
					'options' 		=> [
						'default' 	=> __( 'Default', 'elementor-extras' ),
						'small' 	=> __( 'Small', 'elementor-extras' ),
						'medium' 	=> __( 'Medium', 'elementor-extras' ),
						'large' 	=> __( 'Large', 'elementor-extras' ),
						'xl' 		=> __( 'XL', 'elementor-extras' ),
						'xxl' 		=> __( 'XXL', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'header_size',
				[
					'label' 	=> __( 'HTML Tag', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'options' 	=> [
						'h1' 	=> __( 'H1', 'elementor-extras' ),
						'h2' 	=> __( 'H2', 'elementor-extras' ),
						'h3' 	=> __( 'H3', 'elementor-extras' ),
						'h4' 	=> __( 'H4', 'elementor-extras' ),
						'h5' 	=> __( 'H5', 'elementor-extras' ),
						'h6' 	=> __( 'H6', 'elementor-extras' ),
						'div'	=> __( 'div', 'elementor-extras' ),
						'span' 	=> __( 'span', 'elementor-extras' ),
						'p' 	=> __( 'p', 'elementor-extras' ),
					],
					'default' => 'h1',
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'options' 		=> [
						'left' 		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 	=> [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' 	=> 'fa fa-align-center',
						],
						'right' 	=> [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' 	=> 'fa fa-align-right',
						],
						'justify' 	=> [
							'title' => __( 'Justified', 'elementor-extras' ),
							'icon' 	=> 'fa fa-align-justify',
						],
					],
					'default' 		=> '',
					'selectors' 	=> [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'view',
				[
					'label' 	=> __( 'View', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HIDDEN,
					'default' 	=> 'traditional',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_fill',
			[
				'label' 	=> __( 'Fill', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'title_fill',
				[
					'label' 	=> __( 'Fill', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'options' 	=> [
						'solid' 	=> __( 'Color', 'elementor-extras' ),
						'gradient' 	=> __( 'Background', 'elementor-extras' ),
					],
					'default' 		=> 'solid',
					'prefix_class'	=> 'ee-heading--'
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 		=> 'gradient',
					'types' 	=> [ 'gradient', 'classic' ],
					'selector' 	=> '{{WRAPPER}} .ee-heading__text',
					'default'	=> 'gradient',
					'condition'	=> [
						'title_fill'	=> 'gradient'
					]
				]
			);

			$this->add_control(
				'title_color',
				[
					'label' 	=> __( 'Text Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
					    'type' 	=> Scheme_Color::get_type(),
					    'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-heading__text' => 'color: {{VALUE}};',
					],
					'condition' => [
						'title_fill' => 'solid'
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_type',
			[
				'label' 	=> __( 'Typography', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
			Group_Control_Typography::get_type(),
				[
					'name' 		=> 'typography',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_1,
					'selector' 	=> '{{WRAPPER}} .ee-heading',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_shadow',
			[
				'label' 	=> __( 'Shadow', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'title_classic_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-heading__text-shadow',
				]
			);

			$this->add_group_control(
				Group_Control_Long_Shadow::get_type(), [
					'name' 		=> 'title_long_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-heading__long-shadow',
				]
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['title'] ) )
			return;

		$this->add_render_attribute( 'heading', [
			'class' 		=> 'ee-heading',
			'data-title' 	=> $settings['title'],
		] );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'heading', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}

		if ( ! empty( $settings['link']['url'] ) ) { 
			?><a <?php echo $this->get_render_attribute_string( 'link' ); ?>><?php
		} ?>

			<<?php echo $settings['header_size']; ?> <?php echo $this->get_render_attribute_string('heading'); ?>><?php
				echo $this->render_heading_text();
				echo $this->render_heading_text_shadow();
				echo $this->render_heading_long_shadow();
			?></<?php echo $settings['header_size']; ?>>

		<?php if ( ! empty( $settings['link']['url'] ) ) {
			?></a><?php
		}
	}

	protected function render_heading_text() {
		$this->add_render_attribute( 'heading-text', 'class', 'ee-heading__text' );

		?><span <?php echo $this->get_render_attribute_string( 'heading-text' ); ?>>
			<?php echo $this->parse_text_editor( $this->get_settings('title') ); ?>
		</span><?php
	}

	protected function render_heading_text_shadow() {
		$this->add_render_attribute( 'heading-text-shadow', 'class', 'ee-heading__text-shadow' );

		?><span <?php echo $this->get_render_attribute_string( 'heading-text-shadow' ); ?>>
			<?php echo $this->parse_text_editor( $this->get_settings('title') ); ?>
		</span><?php
	}

	protected function render_heading_long_shadow() {

		if ( 'yes' !== $this->get_settings('title_long_shadow_enable') )
			return;

		$this->add_render_attribute( 'heading-long-shadow', 'class', 'ee-heading__long-shadow' );

		?><span <?php echo $this->get_render_attribute_string( 'heading-long-shadow' ); ?>>
			<?php echo $this->parse_text_editor( $this->get_settings('title') ); ?>
		</span><?php
	}

	protected function _content_template() { ?><#

		view.addRenderAttribute( 'heading', 'class', 'ee-heading' );
		view.addRenderAttribute( 'heading', 'data-title', settings.title );

		if ( '' !== settings.size ) {
			view.addRenderAttribute( 'heading', 'class', 'elementor-size-' + settings.size );
		}

		view.addRenderAttribute( 'heading-text', 'class', 'ee-heading__text' );
		view.addRenderAttribute( 'heading-text-shadow', 'class', 'ee-heading__text-shadow' );
		view.addRenderAttribute( 'heading-long-shadow', 'class', 'ee-heading__long-shadow' );

		if ( '' !== settings.link.url ) {
			#><a href="{{ settings.link.url }}"><#
		} #>

			<{{ settings.header_size }} {{{ view.getRenderAttributeString( 'heading' ) }}}>
				<span {{{ view.getRenderAttributeString( 'heading-text' ) }}}>{{{ settings.title }}}</span>
				<span {{{ view.getRenderAttributeString( 'heading-text-shadow' ) }}}>{{{ settings.title }}}</span>

				<# if ( '' !== settings.title_long_shadow_enable ) {
					#><span {{{ view.getRenderAttributeString( 'heading-long-shadow' ) }}}>{{{ settings.title }}}</span><#
				}

			#></{{ settings.header_size }}>

		<# if ( '' !== settings.link.url ) {
			#></a><#

		} #><?php
	}
}
