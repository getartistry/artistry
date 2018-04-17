<?php
namespace ElementorPro\Modules\ThemeBuilder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use ElementorPro\Modules\ThemeBuilder\Module;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Comments_Form extends Widget_Base {

	public function get_name() {
		return 'comments-form';
	}

	public function get_title() {
		return __( 'Comments Form', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	public function move_comment_bottom( $fields ) {
		$comment_field = $fields['comment'];
		// Remove from top
		unset( $fields['comment'] );

		// Add back to the bottom
		$fields['comment'] = $comment_field;

		return $fields;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_fields_content',
			[
				'label' => __( 'Form', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Leave a Comment', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Label', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'label_on' => __( 'Show', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Input Size', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'elementor-pro' ),
					'sm' => __( 'Small', 'elementor-pro' ),
					'md' => __( 'Medium', 'elementor-pro' ),
					'lg' => __( 'Large', 'elementor-pro' ),
					'xl' => __( 'Extra Large', 'elementor-pro' ),
				],
				'default' => 'sm',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_content',
			[
				'label' => __( 'Button', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Post Comment', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'elementor-pro' ),
					'sm' => __( 'Small', 'elementor-pro' ),
					'md' => __( 'Medium', 'elementor-pro' ),
					'lg' => __( 'Large', 'elementor-pro' ),
					'xl' => __( 'Extra Large', 'elementor-pro' ),
				],
				'default' => 'sm',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-pro' ),
						'icon' => 'fa fa-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon' => 'fa fa-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'elementor-pro' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-button-align-',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'show_url_field',
			[
				'label' => __( 'URL Field', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'label_on' => __( 'Show', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'is_comment_first',
			[
				'label' => __( 'Comment First', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'No', 'elementor-pro' ),
				'label_on' => __( 'Yes', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'preview_as_not_logged_in',
			[
				'label' => __( 'Preview as not logged in', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'No', 'elementor-pro' ),
				'label_on' => __( 'Yes', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label' => __( 'Custom Label', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor-pro' ),
				'label_on' => __( 'Yes', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'author_label',
			[
				'label' => __( 'Author Label', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Name', 'elementor-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_placeholder',
			[
				'label' => __( 'Author Placeholder', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Name', 'elementor-pro' ),
				'condition' => [
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'email_label',
			[
				'label' => __( 'Email Label', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Email', 'elementor-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label' => __( 'Email Placeholder', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Email', 'elementor-pro' ),
				'condition' => [
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'url_label',
			[
				'label' => __( 'URL Label', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'URL', 'elementor-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'url_placeholder',
			[
				'label' => __( 'URL Placeholder', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'URL', 'elementor-pro' ),
				'condition' => [
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'comment_label',
			[
				'label' => __( 'Comment Label', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Comment', 'elementor-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'comment_placeholder',
			[
				'label' => __( 'Comment Placeholder', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Comment', 'elementor-pro' ),
				'condition' => [
					'custom_labels' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Form', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '10',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => __( 'Label', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '0',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body {{WRAPPER}} .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-form-fields-wrapper',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Fields', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'elementor-pro' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'label' => __( 'Border', 'elementor-pro' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'elementor-pro' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function form_fields_render_attributes() {
		$settings = $this->get_settings();
		$commenter = wp_get_current_commenter();

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'elementor-form-fields-wrapper',
					],
				],
				'field-group' => [
					'class' => [
						'elementor-field-type-text',
						'elementor-field-group',
						'elementor-column',
						'elementor-col-100',
					],
				],
				'submit-group' => [
					'class' => [
						'form-submit',
						'elementor-field-group',
						'elementor-column',
						'elementor-field-type-submit',
						'elementor-col-100',
					],
				],

				'button' => [
					'class' => [
						'elementor-button',
					],
					'type' => 'submit',
					'name' => 'wp-submit',
				],

				'author_label' => [
					'for' => 'author',
					'class' => 'elementor-field-label',
				],

				'author_input' => [
					'type' => 'text',
					'name' => 'author',
					'id' => 'author',
					'maxlength' => '245',
					'value' => esc_attr( $commenter['comment_author'] ),
					'class' => [
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					],
				],

				'email_label' => [
					'for' => 'email',
					'class' => 'elementor-field-label',
				],

				'email_input' => [
					'type' => 'email',
					'name' => 'email',
					'id' => 'email',
					'value' => esc_attr( $commenter['comment_author_email'] ),
					'maxlength' => '100',
					'aria-describedby' => 'email-notes',
					'class' => [
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					],
				],

				'url_label' => [
					'for' => 'url',
					'class' => 'elementor-field-label',
				],

				'url_input' => [
					'type' => 'url',
					'name' => 'url',
					'id' => 'url',
					'value' => esc_attr( $commenter['comment_author_url'] ),
					'maxlength' => '200',
					'class' => [
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					],
				],

				'comment_label' => [
					'for' => 'comment',
					'class' => 'elementor-field-label',
				],

				'comment_input' => [
					'name' => 'comment',
					'id' => 'comment',
					'aria-required' => 'true',
					'required' => 'required',
					'maxlength' => '65525',
					'cols' => 45,
					'rows' => 8,
					'class' => [
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					],
				],
			]
		);

		$user_email_required = get_option( 'require_name_email' );

		if ( $user_email_required ) {
			$this->add_render_attribute(
				[
					'author_input' => [
						'aria-required' => 'true',
						'required' => 'required',
					],
					'email_input' => [
						'aria-required' => 'true',
						'required' => 'required',
					],
				]
			);
		}

		if ( ! $settings['show_labels'] ) {
			$this->add_render_attribute( 'label', 'class', 'elementor-screen-only' );
		}
	}

	private function render_field( $field_type, $required, $input_type = 'input' ) {
		$settings = $this->get_settings();
		$field_template = '<div ' . $this->get_render_attribute_string( 'field-group' ) . '>:label:input</div>';
		$label_key = $field_type . '_label';
		$input_key = $field_type . '_input';
		$placeholder_key = $field_type . '_placeholder';
		$required_mark = $required ? ' <span class="required">*</span>' : '';

		if ( 'yes' === $settings['custom_labels'] ) {
			$label = $settings[ $label_key ];
			$placeholder = $settings[ $placeholder_key ];
		} else {
			$control = $this->get_controls( $label_key );
			$label = $control['default'];

			$control = $this->get_controls( $placeholder_key );
			$placeholder = $control['default'];
		}

		$this->add_render_attribute( $input_key, 'placeholder', $placeholder );

		if ( 'input' === $input_type ) {
			$input = '<input size="1" ' . $this->get_render_attribute_string( $input_key ) . ' />';
		} elseif ( 'textarea' === $input_type ) {
			$input = '<textarea ' . $this->get_render_attribute_string( $input_key ) . '></textarea>';
		}

		return strtr(
			$field_template,
			[
				':label' => $settings['show_labels'] ? '<label ' . $this->get_render_attribute_string( $label_key ) . '>' . $label . $required_mark . '</label>' : '',
				':input' => $input,
			]
		);
	}

	protected function render() {
		Module::instance()->get_preview_manager()->switch_to_preview_query();
		$settings = $this->get_settings();
		$require_name_email = get_option( 'require_name_email' );

		$this->form_fields_render_attributes();
		?>
		<form class="elementor-form" method="post" action="<?php echo site_url( '/wp-comments-post.php' ); ?>">
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<?php

				$fields_types = [
					'author' => $require_name_email,
					'email' => $require_name_email,
				];

				if ( 'yes' === $settings['show_url_field'] ) {
					$fields_types['url'] = false;
				}

				$fields = [];

				foreach ( $fields_types as $field_type => $required ) {
					$fields[ $field_type ] = $this->render_field( $field_type, $required );
				}

				$button_text = '';

				if ( ! empty( $settings['button_text'] ) ) {
					$button_text = '<span class="elementor-button-text">' . $settings['button_text'] . '</span>';
				}

				$form_args = [
					'title_reply' => $settings['title'],
					'fields' => $fields,
					'comment_field' => $this->render_field( 'comment', true, 'textarea' ),
					'class_form' => 'comment-form',
					'class_submit' => 'submit',
					'name_submit' => 'submit',
					'submit_button' => '<button ' . $this->get_render_attribute_string( 'button' ) . '>' . $button_text . '</button>',
					'submit_field' => '<div ' . $this->get_render_attribute_string( 'submit-group' ) . '>%1$s %2$s</div>',
					'format' => 'html',
				];

				if ( Plugin::elementor()->editor->is_edit_mode() && 'yes' === $settings['preview_as_not_logged_in'] ) {
					global $current_user;

					$old_current_user = $current_user;
					$current_user = null;
					// 101 = after wordpress default filter
					add_filter( 'determine_current_user', '__return_false', 101 );
				}

				if ( 'yes' !== $settings['is_comment_first'] ) {
					add_filter( 'comment_form_fields', [ $this, 'move_comment_bottom' ] );
				}

				comment_form( $form_args );

				// Restore current user
				if ( isset( $old_current_user ) ) {
					$current_user = $old_current_user;
					remove_filter( 'determine_current_user', '__return_false', 101 );
				}

				?>
			</div>
		</form>
		<?php

		Module::instance()->get_preview_manager()->restore_current_query();
	}

	public function render_plain_content() {}
}
