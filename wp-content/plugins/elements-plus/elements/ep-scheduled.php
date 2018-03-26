<?php
add_action( 'elementor/element/before_section_start', function( $element, $section_id, $args ) {

	$type = get_class( $element );

	switch ( $type ) {
		case 'Elementor\Element_Column':
		case 'Elementor\Element_Section':
			$show_before_id = 'section_advanced';
			break;
		default:
			// Section '_section_style' is common to all widgets, registered in Widget_Common::_register_controls()
			$show_before_id = '_section_style';
	}

	if ( $show_before_id === $section_id ) {
		ep_scheduled_controls( $element );
	}

}, 10, 3 );

function ep_scheduled_controls( $element ) {
	$element->start_controls_section(
		'schedule',
		[
			'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			'label' => __( 'Scheduled visibility', 'elements-plus' ),
		]
	);

	$element->add_control(
		'enable_schedule_from',
		[
			'label'        => __( 'Visible from:', 'elements-plus' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => '',
			'label_on'     => 'On',
			'label_off'    => 'Off',
			'return_value' => 'yes',
			'separator'    => 'none',
		]
	);

	$element->add_control(
		'schedule_from_date',
		[
			'type'       => \Elementor\Controls_Manager::DATE_TIME,
			'label'      => __( 'From Date/Time', 'elements-plus' ),
			'show_label' => false,
			'default'    => current_time( 'mysql' ),
			'condition'  => [
				'enable_schedule_from!' => '',
			],
			'separator'  => 'none',
		]
	);

	$element->add_control(
		'enable_schedule_to',
		[
			'label'        => __( 'Visible until:', 'elements-plus' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => '',
			'label_on'     => 'On',
			'label_off'    => 'Off',
			'return_value' => 'yes',
			'separator'    => 'none',
		]
	);

	$element->add_control(
		'schedule_to_date',
		[
			'type'       => \Elementor\Controls_Manager::DATE_TIME,
			'label'      => __( 'Until Date/Time', 'elements-plus' ),
			'show_label' => false,
			'default'    => current_time( 'mysql' ),
			'condition'  => [
				'enable_schedule_to!' => '',
			],
			'separator'  => 'none',
		]
	);

	$element->end_controls_section();
}

add_action( 'elementor/frontend/widget/before_render', 'ep_schedule_before_render' );
add_action( 'elementor/frontend/element/before_render', 'ep_schedule_before_render' );
function ep_schedule_before_render( \Elementor\Element_Base $element ) {
	if ( ! ep_scheduled_show_element( $element ) ) {
		ob_start();

		// This isn't really needed, as the whole HTML markup is discarded by ob_end_clean()
		// It's left as a safety net however, in case other plugins fiddle incorrectly the the output buffers.
		$element->add_render_attribute( '_wrapper', [
			'style' => 'display:none',
		] );
	}
}


add_action( 'elementor/frontend/widget/after_render', 'ep_schedule_after_render' );
add_action( 'elementor/frontend/element/after_render', 'ep_schedule_after_render' );
function ep_schedule_after_render( \Elementor\Element_Base $element ) {
	if ( ! ep_scheduled_show_element( $element ) ) {
		ob_end_clean();
	}
}

function ep_scheduled_show_element( \Elementor\Element_Base $element ) {
	// Only affect elements where scheduling is enabled.
	if ( 'yes' !== $element->get_settings( 'enable_schedule_from' ) && 'yes' !== $element->get_settings( 'enable_schedule_to' ) ) {
		return true;
	}

	$from = 'yes' === $element->get_settings( 'enable_schedule_from' ) ? $element->get_settings( 'schedule_from_date' ) : '';
	$to   = 'yes' === $element->get_settings( 'enable_schedule_to' ) ? $element->get_settings( 'schedule_to_date' ) : '';

	$now     = current_time( 'timestamp' );
	$from_dt = ! empty( $from ) ? strtotime( $from ) : false;
	$to_dt   = ! empty( $to ) ? strtotime( $to ) : false;

	$show = false;

	if ( ! empty( $from_dt ) && ! empty( $to_dt ) ) {
		if ( $now >= $from_dt && $now <= $to_dt ) {
			$show = true;
		} else {
			$show = false;
		}
	} elseif ( ! empty( $from_dt ) && $now >= $from_dt ) {
		$show = true;
	} elseif ( ! empty( $to_dt ) && $now <= $to_dt ) {
		$show = true;
	} else {
		$show = false;
	}

	return $show;
}
