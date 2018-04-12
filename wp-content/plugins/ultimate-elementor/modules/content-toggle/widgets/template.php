<?php
/**
 * UAEL Radio Button Switcher Module Template.
 *
 * @package UAEL
 */

// Wrapper.
if ( 'yes' === $settings['heading_layout'] ) {
	$this->add_render_attribute(
		'rbs_wrapper', 'class', [
			'uael-ct-desktop-stack--yes',
			'uael-rbs-wrapper',
		]
	);
} else {
	$this->add_render_attribute(
		'rbs_wrapper', 'class', [
			'uael-ct-desktop-stack--no',
			'uael-rbs-wrapper',
		]
	);
}
// Toggle Headings.
$this->add_render_attribute( 'rbs_toggle', 'class', 'uael-rbs-toggle' );
// Toggle Headings inner.
$this->add_render_attribute( 'sec_1', 'class', 'uael-sec-1' );
$this->add_render_attribute( 'sec_2', 'class', 'uael-sec-2' );
// Inline Editing Heading 1.
$this->add_inline_editing_attributes( 'rbs_section_heading_1', 'basic' );
$this->add_render_attribute( 'rbs_section_heading_1', 'class', 'uael-rbs-head-1' );
// Inline Editing Heading 2.
$this->add_inline_editing_attributes( 'rbs_section_heading_2', 'basic' );
$this->add_render_attribute( 'rbs_section_heading_2', 'class', 'uael-rbs-head-2' );
$this->add_render_attribute( 'main_btn', 'class', 'uael-main-btn' );
$this->add_render_attribute( 'main_btn', 'data-switch-type', $settings['rbs_select_switch'] );
// Toggle Sections.
$this->add_render_attribute( 'rbs_toggle_sections', 'class', 'uael-rbs-toggle-sections' );
if ( 'content' === $settings['rbs_select_section_1'] ) {
	$this->add_render_attribute( 'rbs_section_1', 'class', 'uael-rbs-content-1' );
}
if ( 'content' === $settings['rbs_select_section_2'] ) {
	$this->add_render_attribute( 'rbs_section_2', 'class', 'uael-rbs-content-2' );
}
if ( 'on' === $settings['rbs_default_switch'] ) {
	$this->add_render_attribute( 'rbs_section_1', 'style', 'display: none;' );
} else {
	$this->add_render_attribute( 'rbs_section_2', 'style', 'display: none;' );
}
$this->add_render_attribute( 'rbs_section_1', 'class', 'uael-rbs-section-1' );
$this->add_render_attribute( 'rbs_section_2', 'class', 'uael-rbs-section-2' );
// Toggle Switch - Round 1.
$this->add_render_attribute( 'rbs_switch_label', 'class', 'uael-rbs-switch-label' );
$this->add_render_attribute(
	'rbs_switch_round_1', 'class', [
		'uael-rbs-switch',
		'uael-switch-round-1',
		'elementor-clickable',
	]
);
$this->add_render_attribute( 'rbs_switch_round_1', 'type', 'checkbox' );
$this->add_render_attribute(
	'rbs_span_round_1', 'class', [
		'uael-rbs-slider',
		'uael-rbs-round',
		'elementor-clickable',
	]
);
// Toggle Switch - Round 2.
$this->add_render_attribute( 'rbs_div_round_2', 'class', 'uael-toggle' );
$this->add_render_attribute(
	'rbs_input_round_2', 'class', [
		'uael-switch-round-2',
		'elementor-clickable',
	]
);
$this->add_render_attribute( 'rbs_input_round_2', 'type', 'checkbox' );
$this->add_render_attribute( 'rbs_input_round_2', 'name', 'group1' );
$this->add_render_attribute( 'rbs_input_round_2', 'id', 'toggle_' . $node_id );
$this->add_render_attribute( 'rbs_label_round_2', 'for', 'toggle_' . $node_id );
$this->add_render_attribute( 'rbs_label_round_2', 'class', 'elementor-clickable' );
// Toggle Switch - Rectangle.
$this->add_render_attribute( 'rbs_label_rect', 'class', 'uael-rbs-switch-label' );
$this->add_render_attribute(
	'rbs_input_rect', 'class', [
		'uael-rbs-switch',
		'uael-switch-rectangle',
		'elementor-clickable',
	]
);
$this->add_render_attribute( 'rbs_input_rect', 'type', 'checkbox' );
$this->add_render_attribute( 'rbs_span_rect', 'class', 'uael-rbs-slider' );
$this->add_render_attribute( 'rbs_span_rect', 'class', 'elementor-clickable' );
// Toggle Switch - Label Box.
$this->add_render_attribute(
	'rbs_div_label_box', 'class', [
		'uael-label-box',
		'elementor-clickable',
	]
);
$this->add_render_attribute( 'rbs_input_label_box', 'type', 'checkbox' );
$this->add_render_attribute( 'rbs_input_label_box', 'name', 'uael-label-box' );
$this->add_render_attribute(
	'rbs_input_label_box', 'class', [
		'uael-label-box-checkbox',
		'uael-switch-label-box',
		'elementor-clickable',
	]
);
$this->add_render_attribute( 'rbs_input_label_box', 'id', 'myonoffswitch_' . $node_id );
$this->add_render_attribute( 'rbs_label_label_box', 'class', 'uael-label-box-label' );
$this->add_render_attribute( 'rbs_label_label_box', 'for', 'myonoffswitch_' . $node_id );
$this->add_render_attribute( 'rbs_span_inner_label_box', 'class', 'uael-label-box-inner' );
$this->add_render_attribute( 'rbs_span_inactive_label_box', 'class', 'uael-label-box-inactive' );
$this->add_render_attribute( 'rbs_span_label_box', 'class', 'uael-label-box-switch' );
$this->add_render_attribute( 'rbs_span_active_label_box', 'class', 'uael-label-box-active' );
?>

<div <?php echo $this->get_render_attribute_string( 'rbs_wrapper' ); ?>>
	<div <?php echo $this->get_render_attribute_string( 'rbs_toggle' ); ?>>
		<div <?php echo $this->get_render_attribute_string( 'sec_1' ); ?>>
			<<?php echo $settings['rbs_header_size']; ?> <?php echo $this->get_render_attribute_string( 'rbs_section_heading_1' ); ?> data-elementor-inline-editing-toolbar="basic"><?php echo $settings['rbs_section_heading_1']; ?></<?php echo $settings['rbs_header_size']; ?>>
		</div>
		<div <?php echo $this->get_render_attribute_string( 'main_btn' ); ?>>

			<?php $switch_html = ''; ?>
			<?php $is_checked  = ( 'on' === $settings['rbs_default_switch'] ) ? 'checked' : ''; ?>
			<?php
			switch ( $settings['rbs_select_switch'] ) {
				case 'round_1':
					$switch_html = '<label ' . $this->get_render_attribute_string( 'rbs_switch_label' ) . '><input ' . $this->get_render_attribute_string( 'rbs_switch_round_1' ) . ' ' . $is_checked . '><span ' . $this->get_render_attribute_string( 'rbs_span_round_1' ) . '></span></label>';
					break;

				case 'round_2':
					$switch_html = '<div ' . $this->get_render_attribute_string( 'rbs_div_round_2' ) . '><input ' . $this->get_render_attribute_string( 'rbs_input_round_2' ) . ' ' . $is_checked . '><label ' . $this->get_render_attribute_string( 'rbs_label_round_2' ) . '></label></div>';
					break;

				case 'rectangle':
					$switch_html = '<label ' . $this->get_render_attribute_string( 'rbs_label_rect' ) . '><input ' . $this->get_render_attribute_string( 'rbs_input_rect' ) . ' ' . $is_checked . '><span ' . $this->get_render_attribute_string( 'rbs_span_rect' ) . '></span></label>';
					break;

				case 'label_box':
					$switch_html = '<div ' . $this->get_render_attribute_string( 'rbs_div_label_box' ) . '><input ' . $this->get_render_attribute_string( 'rbs_input_label_box' ) . ' ' . $is_checked . '><label ' . $this->get_render_attribute_string( 'rbs_label_label_box' ) . '"><span ' . $this->get_render_attribute_string( 'rbs_span_inner_label_box' ) . '><span ' . $this->get_render_attribute_string( 'rbs_span_inactive_label_box' ) . '><span ' . $this->get_render_attribute_string( 'rbs_span_label_box' ) . '>ON</span></span><span ' . $this->get_render_attribute_string( 'rbs_span_active_label_box' ) . '><span ' . $this->get_render_attribute_string( 'rbs_span_label_box' ) . '>OFF</span></span></span></label></div>';
					break;

				default:
					break;
			}
			?>

			<!-- Display Switch -->
			<?php echo $switch_html; ?>

		</div>
		<div <?php echo $this->get_render_attribute_string( 'sec_2' ); ?>>
			<<?php echo $settings['rbs_header_size']; ?> <?php echo $this->get_render_attribute_string( 'rbs_section_heading_2' ); ?> data-elementor-inline-editing-toolbar="basic"><?php echo $settings['rbs_section_heading_2']; ?></<?php echo $settings['rbs_header_size']; ?>>
		</div>
	</div>
	<div <?php echo $this->get_render_attribute_string( 'rbs_toggle_sections' ); ?>>
		<div <?php echo $this->get_render_attribute_string( 'rbs_section_1' ); ?>>
			<?php echo do_shortcode( $this->get_modal_content( $settings, $node_id, 'rbs_select_section_1' ) ); ?>
		</div>
		<div <?php echo $this->get_render_attribute_string( 'rbs_section_2' ); ?>>
			<?php echo do_shortcode( $this->get_modal_content( $settings, $node_id, 'rbs_select_section_2' ) ); ?>
		</div>
	</div>
</div>
