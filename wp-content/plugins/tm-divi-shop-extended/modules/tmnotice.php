<?php 
class ET_Builder_Module_Notice extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Wc Cart Notices', 'et_builder' );
		$this->slug       = 'et_pb_notice';
		$this->fb_support = true;

		$this->whitelisted_fields = array(
			'text_orientation',
			'admin_label',
			'module_id',
			'module_class',
			'max_width',
			'max_width_tablet',
			'max_width_phone',
			'max_width_last_edited',
			'buttomcolor',
			'backgbuttom',
			'hover_bbut',
			'buttomcolorhover',
			'borderb_radius',
			'borderb_radiushover',
			'borderb_color',
			'borderbhover_color',
			'border_width_button_hover',
			'border_width_button',
			
		);

		$this->fields_defaults = array(
			'text_orientation'  => array( 'left' ),
		);

		 $this->main_css_element = '%%order_class%% .woocommerce-message ';
		$this->advanced_options = array(
			'fonts' => array(
				'text'   => array(
					'label'    => esc_html__( 'Notices', 'et_builder' ),
					'css'      => array(
						'selector' => "{$this->main_css_element} %%order_class%% .woocommerce-message",
						'important' => 'all',
						
					),
					'font_size' => array('default' => '14px'),
                     'line_height' => array('default' => '1.5em'),
					
				),
			),
			'background' => array(
				'css' => array(
					'important' => 'all',
				),
			'label'    => esc_html__( 'Button', 'et_builder' ),
				'settings' => array(
					'color' => 'alpha',
						'selector' => '.woocommerce-message',
				),
			),
			
				'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
				),
			);
		
	}

	function get_fields() {
		$fields = array(
'buttomcolor' => array(
				            'label'    => esc_html__( 'Button Text Color', 'et_builder' ),
				            'type'     => 'color',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'backgbuttom' => array(
				            'label'    => esc_html__( 'Button Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'hover_bbut' => array(
				            'label'    => esc_html__( 'Button Hover Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				         'tab_slug' => 'advanced',
                    ),
					
					
						'buttomcolorhover' => array(
				            'label'    => esc_html__( 'Button Hover Text Color', 'et_builder' ),
				            'type'     => 'color',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'borderb_radius' => array(
				'label'           => esc_html__( 'Button Border Radius', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'default'         => '3',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'tab_slug'        => 'advanced',
                'font_size' => array(
						'default' => '16px',
					),
			),
			
					'border_width_button' => array(
				'label'           => esc_html__( 'Border width Button', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'default'         => '0',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'tab_slug' => 'advanced',


			),
				'borderb_radiushover' => array(
				'label'           => esc_html__( 'Button Hover Border Radius', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'default'         => '3',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'tab_slug'        => 'advanced',
                'font_size' => array(
						'default' => '16px',
					),
			),

					'border_width_button_hover' => array(
				'label'           => esc_html__( 'Border width Button Hover', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'default'         => '0',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'tab_slug' => 'advanced',


			),
			
			'borderb_color' => array(
				            'label'    => esc_html__( 'Button Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'borderbhover_color' => array(
				            'label'    => esc_html__( 'Button Hover Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),

			'max_width' => array(
				'label'           => esc_html__( 'Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
			'max_width_tablet' => array(
				'type'      => 'skip',
				'tab_slug'  => 'advanced',
			),
			'max_width_phone' => array(
				'type'      => 'skip',
				'tab_slug'  => 'advanced',
			),
			'max_width_last_edited' => array(
				'type'     => 'skip',
				'tab_slug' => 'advanced',
			),
			'disabled_on' => array(
				'label'           => esc_html__( 'Disable on', 'et_builder' ),
				'type'            => 'multiple_checkboxes',
				'options'         => array(
					'phone'   => esc_html__( 'Phone', 'et_builder' ),
					'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
					'desktop' => esc_html__( 'Desktop', 'et_builder' ),
				),
				'additional_att'  => 'disable_on',
				'option_category' => 'configuration',
				'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
		);

		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id            = $this->shortcode_atts['module_id'];
		$module_class         = $this->shortcode_atts['module_class'];
		$text_orientation     = $this->shortcode_atts['text_orientation'];
		$max_width            = $this->shortcode_atts['max_width'];
		$max_width_tablet     = $this->shortcode_atts['max_width_tablet'];
		$max_width_phone      = $this->shortcode_atts['max_width_phone'];
		$max_width_last_edited = $this->shortcode_atts['max_width_last_edited'];
		$backgroundmessage 	= $this->shortcode_atts['backgroundmessage'];
		$size_text 	= $this->shortcode_atts['size_text'];
		$text_color 	= $this->shortcode_atts['text_color'];
		$buttomcolor= $this->shortcode_atts['buttomcolor'];
		$backgbuttom =$this->shortcode_atts['backgbuttom'];
		$hover_bbut 	= $this->shortcode_atts['hover_bbut'];
		$buttomcolorhover= $this->shortcode_atts['buttomcolorhover'];
		$borderb_radius= $this->shortcode_atts['borderb_radius'];
		$borderb_radiushover= $this->shortcode_atts['borderb_radiushover'];
		$borderb_color= $this->shortcode_atts['borderb_color'];
		$borderbhover_color= $this->shortcode_atts['borderbhover_color'];
		$border_width_button= $this->shortcode_atts['border_width_button'];
		$border_width_button_hover= $this->shortcode_atts['border_width_button_hover'];
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

					
						if ( '' !== $buttomcolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% a.button.wc-forward',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $buttomcolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $backgbuttom ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $backgbuttom ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $hover_bbut ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward:hover',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $hover_bbut ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $buttomcolorhover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward:hover',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $buttomcolorhover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_radius ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward',
				                'declaration' => sprintf(
					            'border-radius: %1$s%2$spx!important;',
					        esc_html( $borderb_radius ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_radiushover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward:hover',
				                'declaration' => sprintf(
					            'border-radius: %1$s%2$spx!important;',
					        esc_html( $borderb_radiushover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $borderb_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderbhover_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward:hover',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $borderbhover_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }

		                	if ( '' !== $border_width_button ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $border_width_button ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
						if ( '' !== $border_width_button_hover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-message a.button.wc-forward:hover',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $border_width_button_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
		$this->shortcode_content = et_builder_replace_code_content_entities( $this->shortcode_content );

		if ( '' !== $max_width_tablet || '' !== $max_width_phone || '' !== $max_width ) {
			$max_width_responsive_active = et_pb_get_responsive_status( $max_width_last_edited );

			$max_width_values = array(
				'desktop' => $max_width,
				'tablet'  => $max_width_responsive_active ? $max_width_tablet : '',
				'phone'   => $max_width_responsive_active ? $max_width_phone : '',
			);

			et_pb_generate_responsive_css( $max_width_values, '%%order_class%%', 'max-width', $function_name );
		}

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$class = " et_pb_text_align_{$text_orientation}";
		 //////////////////////////////////////////////////////////////////////
                                  
                                ob_start();
                              do_action( 'woocommerce_before_single_product',get_the_ID() );
                                $content = ob_get_clean();
                                  
                                 //////////////////////////////////////////////////////////////////////

		$output = sprintf(
'<div%5$s class="%1$s%3$s%6$s">
                                                    %2$s
                                                %4$s',
                                                'clearfix ',
                                                $content,
                                                esc_attr( $class ),
                                                '</div>',
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}
new ET_Builder_Module_Notice;
?>