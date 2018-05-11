<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_FlipClock extends Widget_Base {

		public function get_name() {
			return 'ep_flipclock';
		}

		public function get_title() {
			return __( 'FlipClock Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-alarm';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_flipclock',
				[
					'label' => __( 'FlipClock Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'time_out',
				[
					'label'   => __( 'Time out', 'elements-plus' ),
					'type'    => Controls_Manager::DATE_TIME,
					'default' => gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) + DAY_IN_SECONDS ),
				]
			);

			$this->add_control(
				'end_text',
				[
					'label'       => __( 'Countdown over text', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'The countdown is over!', 'elements-plus' ),
					'placeholder' => __( 'Text displayed after countdown ends.', 'elements-plus' ),
				]
			);

			$this->add_control(
				'toggle_labels',
				[
					'label'        => __( 'Toggle Labels', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'clockface',
				[
					'label'   => __( 'Clock Face', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'DailyCounter',
					'options' => [
						'DailyCounter'  => __( 'Days, Hours, Minutes', 'elements-plus' ),
						'HourlyCounter' => __( 'Hours, Minutes', 'elements-plus' ),
						'MinuteCounter' => __( 'Minutes', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'toggle_seconds',
				[
					'label'        => __( 'Toggle Seconds', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => __( 'Hides Seconds. Only available when days are visible.', 'elements-plus' ),
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => [
						'clockface' => 'DailyCounter',
					],
				]
			);

			$this->add_control(
				'align',
				[
					'label'        => __( 'Alignment', 'elements-plus' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'left'   => [
							'title' => __( 'Left', 'elements-plus' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elements-plus' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'elements-plus' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'prefix_class' => 'ep-flipclock-align-',
					'default'      => '',
				]
			);

			$this->add_control(
				'view',
				[
					'label'   => __( 'View', 'elements-plus' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'module_style',
				[
					'label' => __( 'Module Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					],
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} div.ep-flipclock-container' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'border',
					'label'       => __( 'Border', 'elements-plus' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .ep-flipclock-container',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label'      => __( 'Border Radius', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} div.ep-flipclock-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'label_box_shadow',
					'selector' => '{{WRAPPER}} .ep-flipclock-container',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'typography_style',
				[
					'label' => __( 'Label Typography', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} span.flip-clock-label',
				]
			);

			$this->add_control(
				'label_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} span.flip-clock-label' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'text_padding',
				[
					'label'      => __( 'Text Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} span.flip-clock-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'digit_style',
				[
					'label' => __( 'Digit Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'digit_color',
				[
					'label'     => __( 'Clock Digit Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ccc',
					'selectors' => [
						'{{WRAPPER}} .flip-clock-wrapper ul li a div div.inn' => 'color: {{VALUE}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_control(
				'digit_bg_color',
				[
					'label'     => __( 'Clock Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#333',
					'selectors' => [
						'{{WRAPPER}} .flip-clock-wrapper ul li a div div.inn' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'flipcard_separator',
				[
					'label'     => __( 'Clock Flipcard Separator Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(0, 0, 0, 0.4)',
					'selectors' => [
						'{{WRAPPER}} .flip-clock-wrapper ul li a div.up:after' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'clock_separator',
				[
					'label'     => __( 'Clock Separator Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#323434',
					'selectors' => [
						'{{WRAPPER}} .flip-clock-dot' => 'background: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'timeout_typography_style',
				[
					'label' => __( 'Timeout Text Typography', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'timeout_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selectors' => [
						'{{WRAPPER}} div.message',
						'{{WRAPPER}} p.expired',
					]
				]
			);

			$this->add_control(
				'timeout_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} div.message' => 'color: {{VALUE}};',
						'{{WRAPPER}} p.expired' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'timeout_text_padding',
				[
					'label'      => __( 'Text Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} div.message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} p.expired' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings  = $this->get_settings();
			$time_out  = strtotime( $settings['time_out'] );
			$clockface = $settings['clockface'];

			if ( false === $time_out ) {
				return;
			}

			$label = 'yes' === $settings['toggle_labels'] ? '' : 'no-label';
			$now   = current_time( 'timestamp' );
			$diff  = $time_out - $now;

			// 'MinuteCounter' face doesn't play nice with anything more than 99:59, so fallback to Hourly counter instead.
			if ( 'MinuteCounter' === $clockface && $diff >= 6000 ) {
				$clockface = 'HourlyCounter';
			}
			?>
			<div id="flipclock-<?php echo esc_attr( $this->get_id() ); ?>" class="ep-flipclock-container" data-time="<?php echo intval( $diff ); ?>" data-end-text="<?php echo esc_attr( $settings['end_text'] ); ?>" data-clockface="<?php echo esc_attr( $clockface ); ?>" data-seconds="<?php echo esc_attr( $settings['toggle_seconds'] ); ?>">
				<?php if ( $diff > 0 ) { ?>
					<div class="clock <?php echo esc_attr( $label ); ?>"></div>
					<div class="message"></div>
				<?php } else { ?>
					<p class="expired"><?php echo esc_html( $settings['end_text'] ); ?></p>
				<?php } ?>
			</div>
			<script>
				jQuery(document).ready(function(){
					jQuery(document).trigger('elementor/render/ep_flipclock','#flipclock-<?php echo esc_attr( $this->get_id() ); ?>');
				});
			</script>
			<?php
		}

		protected function _content_template() {}
	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_FlipClock() );
	} );
