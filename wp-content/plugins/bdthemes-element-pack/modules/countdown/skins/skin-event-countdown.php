<?php
namespace ElementPack\Modules\Countdown\Skins;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Event_Countdown extends Elementor_Skin_Base {
	public function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-countdown/section_number_style/before_section_start',     [ $this, 'register_title_style_controls'        ] );
		add_action( 'elementor/element/bdt-countdown/section_label_style/after_section_end',         [ $this, 'register_event_button_style_controls' ] );
		add_action( 'elementor/element/bdt-countdown/section_content_count/after_section_end',       [ $this, 'register_event_button_controls'       ] );
		add_action( 'elementor/element/bdt-countdown/section_content_layout/before_section_end',     [ $this, 'register_event_controls'              ] );
		add_action( 'elementor/element/bdt-countdown/section_content_additional/before_section_end', [ $this, 'register_event_additional_controls'   ] );

	}

	public function get_id() {
		return 'bdt-event-countdown';
	}

	public function get_title() {
		return __( 'Event Countdown', 'bdthemes-element-pack' );
	}

	public function register_event_additional_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->add_control(
			'show_event_title',
			[
				'label'   => esc_html__( 'Show Event Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_event_button',
			[
				'label'   => esc_html__( 'Show Event Button', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	}

	public static function get_event_list() {
		$event_item = get_posts(array(
			'fields'         => 'ids', // Only get post IDs
			'posts_per_page' => -1,
			'post_type'      => \Tribe__Events__Main::POSTTYPE,
		));

		$event_items = ['0' => esc_html__( 'Select Event', 'bdthemes-element-pack' ) ];

		foreach ($event_item as $key => $value) {
			$event_items[$value] = get_the_title($value);
		}

		return $event_items;
		wp_reset_postdata();
	}

	public function register_event_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->add_control(
			'event_id',
			[
				'label'       => esc_html__( 'Event List', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select your event from this list', 'bdthemes-element-pack' ),
				'options'     => self::get_event_list(),
				'default'     => '0',
			]
		);
	}

	public function register_event_button_controls() {
		$this->start_controls_section(
			'section_event_button',
			[
				'label' => esc_html__( 'Event Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'event_button_text',
			[
				'label'   => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'VIEW DETAILS', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'event_button_size',
			[
				'label'   => esc_html__( 'Size', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lg',
				'options' => element_pack_button_sizes(),
			]
		);

		$this->add_responsive_control(
			'event_button_align',
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

		$this->add_control(
			'event_button_icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'event_button_icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					$this->get_control_id( 'event_button_icon!' ) => '',
				],
			]
		);

		$this->add_control(
			'event_button_icon_indent',
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
					$this->get_control_id( 'event_button_icon!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-wrapper .bdt-event-button-icon.elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-countdown-wrapper .bdt-event-button-icon.elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_event_button_style_controls() {
		$this->start_controls_section(
			'section_style_event_button',
			[
				'label'     => esc_html__( 'Event Button', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'show_event_button' ) => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_event_button_style' );

		$this->start_controls_tab(
			'tab_event_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'event_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-event-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'event_button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-event-button',
			]
		);

		$this->add_control(
			'event_button_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-event-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'event_button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-event-button',
			]
		);

		$this->add_control(
			'event_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-event-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'event_button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-event-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_event_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'event_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-event-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'event_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-event-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'event_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-event-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'event_button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'event_button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->end_controls_section();
	}

	public function register_title_style_controls(Widget_Base $widget ) {
		$this->parent = $widget;

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Event Title', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'show_event_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'event_title_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-event-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'event_title_color',
			[
				'label'  => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-event-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'event_title_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-countdown-event-title',
			]
		);

		$this->add_responsive_control(
			'event_title_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-event-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'event_title_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-event-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'event_title_space',
			[
				'label' => esc_html__( 'Space', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min'  => -200,
						'max'  => 200,
						'step' => 5,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-countdown-event-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'event_title_typography',
				'selector' => '{{WRAPPER}} .bdt-countdown-event-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_section();
	}

	protected function render_text() {
		$event_button_attr             = [];
		$event_button_icon             = [];
		$event_button_wrapper          = [];
		
		$event_button_attr['class']    = ['elementor-button-text'];
		$event_button_icon['class']    = ['bdt-event-button-icon', 'elementor-button-icon', 'elementor-align-icon-' . $this->get_instance_value('event_button_icon_align')];
		$event_button_wrapper['class'] = ['elementor-button-content-wrapper'];
		
		?>

		<span <?php echo \element_pack_helper::attrs($event_button_wrapper); ?>>
			<?php if ( ! empty( $this->get_instance_value('event_button_icon') ) ) : ?>
			<span <?php echo \element_pack_helper::attrs($event_button_icon); ?>>
				<i class="<?php echo esc_attr( $this->get_instance_value('event_button_icon') ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo \element_pack_helper::attrs($event_button_attr); ?>><?php echo esc_html($this->get_instance_value('event_button_text')); ?></span>
		</span>
		<?php
	}

	public function render() {
		$settings = $this->parent->get_settings();
		$event_id = $this->get_instance_value('event_id');
		if ($event_id) {

			$event_button = [];
			$event_button['class'] = ['elementor-button', 'bdt-event-button'];
			$event_button['class'][] = 'elementor-size-' . $this->get_instance_value('event_button_size');

			if ($this->get_instance_value('event_button_animation')) {
				$event_button['class'][] = 'elementor-animation-' . $this->get_instance_value('event_button_animation');
			}

			$event_date  = tribe_get_start_date ( $event_id, false,  'Y-m-d H:i' );
			$event_url   = get_permalink($event_id);
			$event_title = get_the_title($event_id);

			$due_date                   = $event_date;
			$string                     = $this->parent->get_strftime( $settings );
			$countdown                  = [];
			$countdown['class'][]       = 'bdt-flex-middle bdt-flex-'.esc_attr($settings['alignment']);
			$countdown['class'][]       = ($this->get_instance_value('column_gap')) ? 'bdt-grid-'.$this->get_instance_value('column_gap') : '';
			$countdown['bdt-countdown'] = json_encode(array_filter([
				'date' => esc_attr( $due_date ),
			]));
			?>
			<div class="bdt-countdown-wrapper bdt-countdown-skin-event bdt-text-<?php echo esc_attr($settings['alignment']); ?>">
				<?php if( '' != $this->get_instance_value('event_id')  and 'yes' == $this->get_instance_value('show_event_title') ) : ?>
					<div class="bdt-countdown-event-title bdt-display-inline-block">
						<?php echo esc_attr($event_title); ?>
					</div>
				<?php endif; ?>

				<div <?php echo \element_pack_helper::attrs($countdown); ?> bdt-grid>

					<?php echo $string; ?>

					<?php if( '' != $this->get_instance_value('event_id')  and 'yes' == $this->get_instance_value('show_event_button') ) : ?>
						<div class="bdt-countdown bdt-countdown-event-button">
							<a <?php echo \element_pack_helper::attrs($event_button); ?> href="<?php echo esc_url( $event_url ); ?>">
								<?php $this->render_text(); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php 
		} else echo '<div class="bdt-alert-warning" bdt-alert><p>You couldn\'t select any event, please select a event from event list.</p></div>';
	}
}

