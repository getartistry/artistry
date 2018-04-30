<?php
namespace ElementPack\Modules\Countdown\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Utils;

use Elementor\Scheme_Color;

use ElementPack\Modules\Countdown\Skins;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Countdown extends Widget_Base {

	public function get_name() {
		return 'bdt-countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_skins() {
		
		if(is_plugin_active('the-events-calendar/the-events-calendar.php')) {
			$this->add_skin( new Skins\Skin_Event_Countdown( $this ) );
		}
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'due_date',
			[
				'label'       => esc_html__( 'Due Date', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'bdthemes-element-pack' ), Utils::get_timezone_string() ),
				'condition'   => [
					'_skin' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_count',
			[
				'label' => esc_html__( 'Count Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'count_gap',
			[
				'label'   => esc_html__( 'Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''         => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'small'    => esc_html__( 'Small', 'bdthemes-element-pack' ),
					'medium'   => esc_html__( 'Medium', 'bdthemes-element-pack' ),
					'large'    => esc_html__( 'Large', 'bdthemes-element-pack' ),
					'collapse' => esc_html__( 'Collapse', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_responsive_control(
			'number_label_gap',
			[
				'label'   => esc_html__( 'Number & Label Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}}.bdt-countdown--label-block .bdt-countdown-number'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.bdt-countdown--label-inline .bdt-countdown-number' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'        => __( 'Text Alignment', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'      => 'center',
			]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label'   => esc_html__( 'Container Width', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
					'size' => 70,
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
				],
			]
		);

		$this->add_control(
			'content_align',
			[
				'label'       => __( 'Content Align', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'left' => [
						'title' => __( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-wrapper' => 'margin-{{VALUE}}: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'count_column',
			[
				'label'          => esc_html__( 'Count Column', 'bdthemes-element-pack' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '2',
				'options'        => [
					''  => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'1' => esc_html__( '1 Columns', 'bdthemes-element-pack' ),
					'2' => esc_html__( '2 Columns', 'bdthemes-element-pack' ),
					'3' => esc_html__( '3 Column', 'bdthemes-element-pack' ),
					'4' => esc_html__( '4 Columns', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'_skin' => '',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional',
			[
				'label' => esc_html__( 'Additional Options', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'label_display',
			[
				'label'   => esc_html__( 'View', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'block'  => esc_html__( 'Block', 'bdthemes-element-pack' ),
					'inline' => esc_html__( 'Inline', 'bdthemes-element-pack' ),
				],
				'default'      => 'block',
				'prefix_class' => 'bdt-countdown--label-',
			]
		);

		$this->add_control(
			'show_days',
			[
				'label'   => esc_html__( 'Days', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_hours',
			[
				'label'   => esc_html__( 'Hours', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_minutes',
			[
				'label'   => esc_html__( 'Minutes', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				]
		);

		$this->add_control(
			'show_seconds',
			[
				'label'   => esc_html__( 'Seconds', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label'   => esc_html__( 'Show Label', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label'        => esc_html__( 'Custom Label', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_days',
			[
				'label'       => esc_html__( 'Days', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Days', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Days', 'bdthemes-element-pack' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_days'      => 'yes',
				],
			]
		);

		$this->add_control(
			'label_hours',
			[
				'label'       => esc_html__( 'Hours', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hours', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Hours', 'bdthemes-element-pack' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_hours'     => 'yes',
				],
			]
		);

		$this->add_control(
			'label_minutes',
			[
				'label'       => esc_html__( 'Minutes', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Minutes', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Minutes', 'bdthemes-element-pack' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_minutes'   => 'yes',
				],
			]
		);

		$this->add_control(
			'label_seconds',
			[
				'label'       => esc_html__( 'Seconds', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Seconds', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'Seconds', 'bdthemes-element-pack' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_seconds'   => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_style',
			[
				'label' => esc_html__( 'Count Style', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'count_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'count_border',
				'label'    => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'selector' => '{{WRAPPER}} .bdt-countdown-item',
			]
		);

		$this->add_control(
			'count_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'count_shadow',
				'selector' => '{{WRAPPER}} .bdt-countdown-item',
			]
		);

		$this->add_responsive_control(
			'count_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_number_style',
			[
				'label' => esc_html__( 'Number', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_background',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-number' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'number_color',
			[
				'label'  => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'number_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-countdown-number',
			]
		);

		$this->add_responsive_control(
			'number_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'number_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-countdown-number',
			]
		);

		$this->add_responsive_control(
			'number_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .bdt-countdown-number',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_label_style',
			[
				'label'     => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_background',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'label_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-countdown-label',
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'label_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-countdown-label',
			]
		);

		$this->add_responsive_control(
			'label_border_radius',
			[
				'label'      => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-countdown-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .bdt-countdown-label',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	public function get_strftime( $settings ) {
		$string = '';
		if ( $settings['show_days'] ) {
			$string .= $this->render_countdown_item( $settings, 'label_days', 'bdt-countdown-days' );
		}
		if ( $settings['show_hours'] ) {
			$string .= $this->render_countdown_item( $settings, 'label_hours', 'bdt-countdown-hours' );
		}
		if ( $settings['show_minutes'] ) {
			$string .= $this->render_countdown_item( $settings, 'label_minutes', 'bdt-countdown-minutes' );
		}
		if ( $settings['show_seconds'] ) {
			$string .= $this->render_countdown_item( $settings, 'label_seconds', 'bdt-countdown-seconds' );
		}

		return $string;
	}

	private $_default_countdown_labels;

	private function _init_default_countdown_labels() {
		$this->_default_countdown_labels = [
			'label_months'  => esc_html__( 'Months', 'bdthemes-element-pack' ),
			'label_weeks'   => esc_html__( 'Weeks', 'bdthemes-element-pack' ),
			'label_days'    => esc_html__( 'Days', 'bdthemes-element-pack' ),
			'label_hours'   => esc_html__( 'Hours', 'bdthemes-element-pack' ),
			'label_minutes' => esc_html__( 'Minutes', 'bdthemes-element-pack' ),
			'label_seconds' => esc_html__( 'Seconds', 'bdthemes-element-pack' ),
		];
	}

	public function get_default_countdown_labels() {
		if ( ! $this->_default_countdown_labels ) {
			$this->_init_default_countdown_labels();
		}

		return $this->_default_countdown_labels;
	}

	private function render_countdown_item( $settings, $label, $part_class ) {
		$string  = '<div class="bdt-countdown-item-wrapper">';
			$string .= '<div class="bdt-countdown-item">';
				$string .= '<span class="bdt-countdown-number ' . $part_class . ' bdt-text-'.esc_attr($this->get_settings('alignment')).'"></span>';

				if ( $settings['show_labels'] ) {
					$default_labels = $this->get_default_countdown_labels();
					$label          = ( $settings['custom_labels'] ) ? $settings[ $label ] : $default_labels[ $label ];
					$string        .= ' <span class="bdt-countdown-label bdt-text-'.esc_attr($this->get_settings('alignment')).'">' . $label . '</span>';
				}
			$string .= '</div>';
		$string .= '</div>';

		return $string;
	}

	protected function render() {
		$settings                   = $this->get_settings();
		$due_date                   = $settings['due_date'];
		$string                     = $this->get_strftime( $settings );
		$countdown                  = [];
		$countdown['class'][]       = 'bdt-grid';
		$countdown['class'][]       = ($settings['count_gap']) ? 'bdt-grid-'.$settings['count_gap'] : '';
		$countdown['class'][]       = 'bdt-child-width-1-'.$settings['count_column_mobile'];
		$countdown['class'][]       = 'bdt-child-width-1-'.$settings['count_column_tablet'] . '@s';
		$countdown['class'][]       = 'bdt-child-width-1-'.$settings['count_column'] . '@m';
		$countdown['bdt-countdown'] = json_encode(array_filter([
			'date' => esc_attr( $due_date ),
		]));
		?>
		<div class="bdt-countdown-wrapper bdt-countdown-skin-default">
			<div <?php echo \element_pack_helper::attrs($countdown); ?> bdt-grid>
				<?php echo $string; ?>
			</div>
		</div>
		<?php
	}
}
