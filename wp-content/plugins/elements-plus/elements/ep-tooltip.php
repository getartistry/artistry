<?php

	function ep_tooltip_widgets() {
		return array( 'button', 'icon', 'heading', 'icon-box' );
	}

	add_action('elementor/element/after_section_end', function( $section, $section_id, $args ) {
		$tooltip_sections = array( 'section_button', 'section_icon', 'section_title' );

		if ( in_array( $section->get_name(), ep_tooltip_widgets(), true ) && in_array( $section_id, $tooltip_sections, true ) ) {

			$section->start_controls_section(
				'section_ep_tooltip',
				[
					'label' => __( 'Elements Plus! Tooltip', 'elements-plus' ),
				]
			);

				$section->add_control(
					'ep_tooltip' ,
					[
						'label' => __( 'Enable Tooltip', 'elements-plus' ),
						'type' => Elementor\Controls_Manager::SWITCHER,
						'default' => '',
						'return_value' => 'on',
					]
				);

				$section->add_control(
					'ep_tooltip_text',
					[
						'label'       => __( 'Tooltip Text', 'elements-plus' ),
						'type'        => Elementor\Controls_Manager::TEXT,
						'default'     => __( 'An awesome tooltip!', 'elements-plus' ),
						'placeholder' => __( 'Your tooltip text here', 'elements-plus' ),
					]
				);

				$section->add_control(
					'ep_tooltip_position',
					[
						'label'    => __( 'Tooltip Position', 'elements-plus' ),
						'type'     => Elementor\Controls_Manager::SELECT,
						'default'  => 'ept-top',
						'options'  => [
							'ept-top'    => __( 'Top', 'elements-plus' ),
							'ept-right'  => __( 'Right', 'elements-plus' ),
							'ept-bottom' => __( 'Bottom', 'elements-plus' ),
							'ept-left'   => __( 'Left', 'elements-plus' ),
						],
					]
				);

				$section->add_control(
					'ep_tooltip_bg_color',
					[
						'label' => __( 'Tooltip background color', 'elements-plus' ),
						'type' => Elementor\Controls_Manager::COLOR,
						'scheme' => [
							'type' => Elementor\Scheme_Color::get_type(),
							'value' => Elementor\Scheme_Color::COLOR_1,
						],
						'selectors' => [
							'{{WRAPPER}} .ep-tooltip[data-tooltip]:before' => 'background-color: {{VALUE}}',
						],
					]
				);

				$section->add_group_control(
					Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'ep_tooltip_border',
						'label' => __( 'Border', 'elements-plus' ),
						'placeholder' => '1px',
						'default' => '1px',
						'selector' => '{{WRAPPER}} .ep-tooltip[data-tooltip]:before',
					]
				);

				$section->add_group_control(
					Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ep_tooltip_typography',
						'label' => __( 'Typography', 'elements-plus' ),
						'scheme' => Elementor\Scheme_Typography::TYPOGRAPHY_4,
						'selector' => '{{WRAPPER}} .ep-tooltip[data-tooltip]:before',
					]
				);

				$section->add_control(
					'ep_tooltip_text_color',
					[
						'label' => __( 'Text Color', 'elements-plus' ),
						'type' => Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .ep-tooltip[data-tooltip]:before' => 'color: {{VALUE}};',
						],
					]
				);

				$section->add_control(
					'ep_tooltip_width',
					[
						'label' => __( 'Tooltip width', 'elements-plus' ),
						'type' => Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
						],
						'range' => [
							'px' => [
								'min' => 20,
								'max' => 1000,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .ep-tooltip[data-tooltip]:before' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);

			$section->end_controls_section();
		} // End if().
	}, 10, 3 );

	add_action( 'elementor/frontend/widget/before_render', function ( \Elementor\Element_Base $element ) {
		if ( in_array( $element->get_name(), ep_tooltip_widgets(), true ) ) {
			$settings = $element->get_settings();

			if ( 'on' === $settings['ep_tooltip'] && 'icon' === $element->get_name() ) {
				$element->add_render_attribute( $element->get_name() . '-wrapper', 'data-tooltip', $settings['ep_tooltip_text'] );
				$element->add_render_attribute( $element->get_name() . '-wrapper', 'class', 'ep-tooltip' );
				$element->add_render_attribute( $element->get_name() . '-wrapper', 'class', $settings['ep_tooltip_position'] );
			} elseif ( 'on' === $settings['ep_tooltip'] && 'icon-box' === $element->get_name() ) {
				$element->add_render_attribute( 'icon', 'data-tooltip', $settings['ep_tooltip_text'] );
				$element->add_render_attribute( 'icon', 'class', 'ep-tooltip' );
				$element->add_render_attribute( 'icon', 'class', $settings['ep_tooltip_position'] );
			} elseif ( 'on' === $settings['ep_tooltip'] ) {
				$element->add_render_attribute( $element->get_name(), 'data-tooltip', $settings['ep_tooltip_text'] );
				$element->add_render_attribute( $element->get_name(), 'class', 'ep-tooltip' );
				$element->add_render_attribute( $element->get_name(), 'class', $settings['ep_tooltip_position'] );
			}
		}

	});

	add_filter( 'elementor/widget/print_template', function( $template, $widget ) {
		if ( 'button' === $widget->get_name() ) {

			$template = '<div class="elementor-button-wrapper"><a <# if ( settings.ep_tooltip ) { #>data-tooltip="{{settings.ep_tooltip_text}}"<# } #> class="<# if ( settings.ep_tooltip ) { #>{{settings.ep_tooltip_position}} ep-tooltip<# } #> elementor-button elementor-size-{{settings.size}} elementor-animation-{{settings.hover_animation}}" href="{{settings.link.url}}"><span class="elementor-button-content-wrapper"><# if ( settings.icon ){#><span class="elementor-button-icon elementor-align-icon-{{settings.icon_align}}"><i class="{{settings.icon}}"></i></span><#}#><span class="elementor-button-text">{{{settings.text}}}</span></span></a></div>';
		} elseif ( 'heading' === $widget->get_name() ) {

			$template = '<{{settings.header_size}} <# if ( settings.ep_tooltip ) { #>data-tooltip="{{settings.ep_tooltip_text}}"<# } #> class="<# if ( settings.ep_tooltip ) { #>{{settings.ep_tooltip_position}} ep-tooltip<# } #> elementor-heading-title element-{{settings.size}}"><# if ( settings.link.url ) { #><a href="{{ settings.link.url"><# } #>{{ settings.title }}<# if ( settings.link.url ) { #></a><# } #></{{settings.header_size}}>';
		} elseif ( 'icon' === $widget->get_name() ) {

			$template = '<# var link = settings.link.url ? \'href="\' + settings.link.url + \'"\' : \'\', iconTag = link ? \'a\' : \'div\'; #><div class="elementor-icon-wrapper"><{{{ iconTag }}} <# if ( settings.ep_tooltip ) { #>data-tooltip="{{settings.ep_tooltip_text}}"<# } #> class="<# if ( settings.ep_tooltip ) { #>{{settings.ep_tooltip_position}} ep-tooltip<# } #> elementor-icon elementor-animation-{{ settings.hover_animation }}" {{{ link }}}><i class="{{ settings.icon }}"></i></{{{ iconTag }}}></div>';
		} elseif ( 'icon-box' === $widget->get_name() ) {

			$template = '<# var link = settings.link.url ? \'href="\' + settings.link.url + \'"\' : \'\', iconTag = link ? \'a\' : \'span\'; #><div class="elementor-icon-box-wrapper"><div class="elementor-icon-box-icon"><{{{ iconTag + \' \' + link }}} <# if ( settings.ep_tooltip ) { #>data-tooltip="{{settings.ep_tooltip_text}}"<# } #> class="<# if ( settings.ep_tooltip ) { #>{{settings.ep_tooltip_position}} ep-tooltip<# } #> elementor-icon elementor-animation-{{ settings.hover_animation }}"><i class="{{ settings.icon }}"></i></{{{ iconTag }}}></div><div class="elementor-icon-box-content"><{{{ settings.title_size }}} class="elementor-icon-box-title"><{{{ iconTag + \' \' + link }}}>{{{ settings.title_text }}}</{{{ iconTag }}}></{{{ settings.title_size }}}><p class="elementor-icon-box-description">{{{ settings.description_text }}}</p></div></div>';
		}

		return $template;
	}, 10, 2 );
