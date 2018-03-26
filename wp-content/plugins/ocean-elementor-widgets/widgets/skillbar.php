<?php
/**
 * Skillbar Module
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class OEW_Widget_Skillbar extends Widget_Base {

	public function get_name() {
		return 'oew-skillbar';
	}

	public function get_title() {
		return __( 'Skillbar', 'ocean-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-skill-bar';
	}

	public function get_categories() {
		return [ 'oceanwp-elements' ];
	}

	public function get_script_depends() {
		return [ 'oew-skillbar', 'appear' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_alert',
			[
				'label' 		=> __( 'Skillbar', 'ocean-elementor-widgets' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' 		=> __( 'Title', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Web Design', 'ocean-elementor-widgets' ),
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'percent',
			[
				'label' 		=> __( 'Percentage', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SLIDER,
				'default' 		=> [
					'size' 		=> 85,
					'unit' 		=> '%',
				],
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'display_percent',
			[
				'label' 		=> __( 'Display % Number', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'style',
			[
				'label' 		=> __( 'Style', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'inner',
				'options' 		=> [
					'inner' 	=> __( 'Inner', 'ocean-elementor-widgets' ),
					'outside' 	=> __( 'Outside', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'display_stripe',
			[
				'label' 		=> __( 'Display Stripe', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' 		=> __( 'View', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::HIDDEN,
				'default' 		=> 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' 		=> __( 'Skill Bar', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background',
			[
				'label' 		=> __( 'Background', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-skillbar-container' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' 		=> __( 'Bar Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-skillbar-bar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'box_shadow',
			[
				'label' 		=> __( 'Inset Shadow', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Yes', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'No', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'skillbar_title',
				'selector' 		=> '{{WRAPPER}} .oew-skillbar',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();

		// Vars
		$elements_style = $settings['style'];
		$percent 		= $settings['percent']['size'];
		$title 			= $settings['title'];
		$show_percent 	= $settings['display_percent'];
		$show_stripe 	= $settings['display_stripe'];

		// Wrapper classes
		$wrap_classes = array( 'oew-skillbar', 'clr' );
		if ( 'false' == $settings['box_shadow'] ) {
			$wrap_classes[] = 'disable-box-shadow';
		}
		if ( $elements_style ) {
			$wrap_classes[] = 'style-' . $elements_style;
		}
		if ( 'inner' == $elements_style ) {
		   $wrap_classes[] = 'oew-skillbar-container';
		}

		// Turn wrap classes into a string
		$wrap_classes = implode( ' ', $wrap_classes ); ?>

		<div class="<?php echo esc_attr( $wrap_classes ); ?>" data-percent="<?php echo esc_attr( $percent ); ?>&#37;">

			<?php if ( 'inner' == $elements_style ) { ?>

				<div class="oew-skillbar-title">

					<div class="oew-skillbar-title-inner">
						<?php echo esc_attr( $title ); ?>
					</div><!-- .oew-skillbar-title-inner -->

				</div><!-- .oew-skillbar-title -->

			<?php } else if ( 'outside' == $elements_style ) { ?>

				<div class="oew-skillbar-title">
					<?php echo esc_attr( $title ); ?>
				</div><!-- .oew-skillbar-title-inner -->

				<?php if ( 'true' == $show_percent ) { ?>
					<div class="oew-skill-bar-percent"><?php echo esc_attr( $percent ); ?>&#37;</div>
				<?php } ?>

				<div style="clear:both"></div>

			<?php } ?>

			<?php if ( $settings['percent'] ) { ?>

				<?php if ( 'outside' == $elements_style ) { ?>
					<div class="oew-skillbar-container clr">
				<?php } ?>

					<div class="oew-skillbar-bar">
						<?php if ( 'true' == $show_percent && 'inner' == $elements_style ) { ?>
							<div class="oew-skill-bar-percent"><?php echo esc_attr( $percent ); ?>&#37;</div>
						<?php } ?>
						<?php if ( 'true' == $show_stripe ) { ?>
							<div class="oew-skill-bar-stripe"></div>
						<?php } ?>
					</div><!-- .oew-skillbar -->

				<?php if ( 'outside' == $elements_style ) { ?>
					</div>
				<?php } ?>

			<?php } ?>

		</div><!-- .oew-skillbar -->

	<?php
	}

	protected function _content_template() { ?>
		<#
			var wrap_classes = 'oew-skillbar clr';

			if ( 'false' == settings.box_shadow ) {
				wrap_classes += ' disable-box-shadow';
			}
			if ( '' !== settings.style ) {
				wrap_classes += ' style-' + settings.style;
			}
			if ( 'inner' == settings.style ) {
				wrap_classes += ' oew-skillbar-container';
			}
		#>

		<div class="{{ wrap_classes }}" data-percent="{{ settings.percent.size }}&#37;">

			<# if ( 'inner' == settings.style ) { #>

				<div class="oew-skillbar-title">

					<div class="oew-skillbar-title-inner">
						{{{ settings.title }}}
					</div><!-- .oew-skillbar-title-inner -->

				</div><!-- .oew-skillbar-title -->

			<# } else if ( 'outside' == settings.style ) { #>

				<div class="oew-skillbar-title">
					{{{ settings.title }}}
				</div><!-- .oew-skillbar-title-inner -->

				<# if ( 'true' == settings.display_percent ) { #>
					<div class="oew-skill-bar-percent">{{ settings.percent.size }}&#37;</div>
				<# } #>

				<div style="clear:both"></div>

			<# } #>

			<# if ( settings.percent ) { #>

				<# if ( 'outside' == settings.style ) { #>
					<div class="oew-skillbar-container clr">
				<# } #>

					<div class="oew-skillbar-bar">
						<# if ( 'true' == settings.display_percent && 'inner' == settings.style ) { #>
							<div class="oew-skill-bar-percent">{{ settings.percent.size }}&#37;</div>
						<# } #>
						<# if ( 'true' == settings.display_stripe ) { #>
							<div class="oew-skill-bar-stripe"></div>
						<# } #>
					</div><!-- .oew-skillbar -->

				<# if ( 'outside' == settings.style ) { #>
					</div>
				<# } #>

			<# } #>

		</div><!-- .oew-skillbar -->
	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new OEW_Widget_Skillbar() );