<?php

class ET_Builder_Module_ButtonAddToCar extends ET_Builder_Module {
                function init() {
		$this->name       = esc_html__( 'Wc Add to Cart', 'et_builder' );
		$this->slug       = 'et_pb_button_add_to_car';
                    $this->fb_support = true;
            
                    $this->whitelisted_fields = array(
                        'button_text',
			            'background_layout',
			            'button_alignment',
                        'nro_align',
						'nro_show',
			            'admin_label',
			            'module_id',
			            'module_class',
                        'style_addtocart',
						'whith_button',
						'show_variation',
						'select_border',
						'border_color',
						//'textcolor',
						'hover_back_but_color',
						'hover_text_but',
						'hover_border_but',
						'border_radius_but',
						'select_border',
						'select_borderradius',
						'select_bordercolor',
						'border_radius_buthover',
						'border_width_button_hover',
						'border_width_button',

                    );
            
                    $this->fields_defaults = array(
                        'background_color'  => array( et_builder_accent_color(), 'add_default_setting' ),
			            'background_layout' => array( 'light' ),
                        'style_addtocart' => array( 'off' ),
						'show_variation'  => array( 'off' ),
                    );
                   $this->main_css_element = '%%order_class%% .single_add_to_cart_button.button.alt ';
		$this->advanced_options = array(
			'fonts' => array(
				'text'   => array(
					'label'    => esc_html__( 'Button Title', 'et_builder' ),
					'css'      => array(
						'selector' => "{$this->main_css_element} %%order_class%% .single_add_to_cart_button.button.alt",
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
						'selector' => '.single_add_to_cart_button.button.alt',
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
                            'button_alignment' => array(
				            'label'           => esc_html__( 'Button Alignment', 'et_builder' ),
				            'type'            => 'select',
				            'option_category' => 'configuration',
				            'options'         => array(
					            'left'   => esc_html__( 'Left', 'et_builder' ),
					            'right'  => esc_html__( 'Right', 'et_builder' ),
								'none'  => esc_html__( 'None', 'et_builder' ),
				        ),
				    'description'     => esc_html__( 'Here you can define the alignment of Button', 'et_builder' ),
                    'css' => array(
						    'main' => "{$this->main_css_element}.et_pb_button_alignment_{}",
						    'plugin_main' => "{$this->main_css_element}.et_pb_button_alignment_right .single_add_to_cart_button.button.alt",
                            'important' => 'all',
					),
			        ),

					'show_variation' => array(
				'label'             => esc_html__( 'Variable Product', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				 'tab_slug' => 'advanced',
				'options'           => array(
					'off' => esc_html__( "No", 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'description'     => esc_html__( 'Here you can display the product attributes to be selected before adding it to the cart', 'et_builder' ),
				'affects'           => array(
					'select_border',
					'select_borderradius',
					'select_bordercolor',
					
				),
			),
			'select_border' => array(
				'label'           => esc_html__( ' Select Border Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'default'         => '3',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'option_category' => 'basic_option',
				'depends_show_if' => 'on',
				'tab_slug' => 'advanced',


			),
			
			'border_width_button' => array(
				'label'           => esc_html__( 'Button Border Width', 'et_builder' ),
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
			'border_width_button_hover' => array(
				'label'           => esc_html__( 'Button Hover Border Width', 'et_builder' ),
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
			'select_borderradius' => array(
				'label'           => esc_html__( 'Select Border Radius', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'default'         => '3',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'option_category' => 'basic_option',
				'depends_show_if' => 'on',
				'tab_slug' => 'advanced',


			),
			'select_bordercolor' => array(
				            'label'    => esc_html__( 'Select Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),
			
					/*'textcolor' => array(
				            'label'    => esc_html__( 'Button Text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),*/
					'border_color' => array(
				            'label'    => esc_html__( 'Button Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
			
					'border_radius_but' => array(
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
			
			
					'hover_back_but_color' => array(
				            'label'    => esc_html__( 'Button Hover Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'border_radius_buthover' => array(
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
					'hover_text_but' => array(
				            'label'    => esc_html__( 'Button Hover Text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					'hover_border_but' => array(
				            'label'    => esc_html__( 'Button Hover Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					'nro_show' => array(
				            'label'           => esc_html__( 'Display Quantity', 'et_builder' ),
				            'type'            => 'select',
				            'option_category' => 'configuration',
				            'options'         => array(
					            'block'   => esc_html__( 'Show', 'et_builder' ),
					            'none'  => esc_html__( 'Hide', 'et_builder' ),
				        ),
				    'description'     => esc_html__( 'Here you will decide whether to display quantity next to the “Add to Cart Button” or not', 'et_builder' ),
			        ),

					'whith_button' => array(
				'label'             => esc_html__( 'Fullwidth Button', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off'     => esc_html__( 'No', 'et_builder' ),
					'on'      => esc_html__( 'Yes', 'et_builder' ),
				),
				'description'       => esc_html__( 'Here you can choose whether or not to make the add to cart button fullwidth.', 'et_builder' ),
			),
                    'background_layout' => array(
				            'label'           => esc_html__( 'Text Color', 'et_builder' ),
				            'type'            => 'select',
				            'option_category' => 'color_option',
				            'options'         => array(
					        'light' => esc_html__( 'Dark', 'et_builder' ),
					        'dark'  => esc_html__( 'Light', 'et_builder' ),
				        ),
				    'description'     => esc_html__( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			        ),
                            'admin_label' => array(
                            'label'       => __( 'Admin Label', 'et_builder' ),
                            'type'        => 'text',
                            'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
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
                                
                                if (get_post_type() != 'product') {
                                                return;
                                }
                                $button_alignment  = $this->shortcode_atts['button_alignment'];
                                $background_layout = $this->shortcode_atts['background_layout'];
                                $module_id          = $this->shortcode_atts['module_id'];
                                $module_class       = $this->shortcode_atts['module_class'];
                                $nro_align      = isset($this->shortcode_atts['nro_align']) ? $this->shortcode_atts['nro_align'] : '';
								$nro_show 		= isset($this->shortcode_atts['nro_show']) ? $this->shortcode_atts['nro_show'] : '';
								$whith_button 		= $this->shortcode_atts['whith_button'];
								$border_color = $this->shortcode_atts['border_color'];
								$textcolor 	= (isset($this->shortcode_atts['textcolor']) ? $this->shortcode_atts['textcolor'] : '');
								$hover_back_but 	= $this->shortcode_atts['hover_back_but_color'];
								$hover_text_but = $this->shortcode_atts['hover_text_but'];
								$hover_border_but = $this->shortcode_atts['hover_border_but'];
								$border_radius_but = $this->shortcode_atts['border_radius_but'];
								$show_variation = $this->shortcode_atts['show_variation'];
								$select_border = $this->shortcode_atts['select_border'];
								$select_borderradius = $this->shortcode_atts['select_borderradius'];
								$select_bordercolor  = $this->shortcode_atts['select_bordercolor'];
								$border_radius_buthover= $this->shortcode_atts['border_radius_buthover'];
								$border_width_button= $this->shortcode_atts['border_width_button'];
								$border_width_button_hover= $this->shortcode_atts['border_width_button_hover'];
                        
                                $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
                                
                        if ( '' !== $button_alignment ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt, .combo',
				                'declaration' => sprintf(
					            'float: %1$s%2$s!important;',
					        esc_html( $button_alignment ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
					
						if ( '' !== $textcolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $textcolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $border_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $border_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $hover_back_but ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt:hover',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $hover_back_but ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $hover_text_but ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt:hover',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $hover_text_but ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $hover_border_but ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt:hover',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $hover_border_but ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
                        if ( '' !== $nro_align ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% form.cart div.quantity',
				                'declaration' => sprintf(
					            'float: %1$s%2$s!important;',
					        esc_html( $nro_align ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $nro_show ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% form.cart div.quantity',
				                'declaration' => sprintf(
					            'display: %1$s%2$s!important;',
					        esc_html( $nro_show ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
                        if ( 'on' === $whith_button ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt, .combo',
								'declaration' => 'width: 100%;',
							) );
						}
					if ( '' !== $border_radius_but ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt',
								'declaration' => sprintf(
								'border-radius: %1$spx!Important;',
								   esc_html( $border_radius_but ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
						if ( '' !== $border_width_button ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $border_width_button ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
						if ( '' !== $border_width_button_hover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt:hover',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $border_width_button_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
						if ( '' !== $border_radius_buthover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .single_add_to_cart_button.button.alt:hover',
								'declaration' => sprintf(
								'border-radius: %1$spx!Important;',
								   esc_html( $border_radius_buthover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
						if ( '' !== $select_border ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .value select',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $select_border ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
						if ( '' !== $select_borderradius ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .value select',
								'declaration' => sprintf(
								'border-radius: %1$spx!Important;',
								   esc_html( $select_borderradius ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $select_bordercolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .value select',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $select_bordercolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
                                //////////////////////////////////////////////////////////////////////
                               
                               ob_start();
                               if( !is_admin() ) woocommerce_template_single_add_to_cart() ;
                                $content = ob_get_clean();
                                 //////////////////////////////////////////////////////////////////////
                                $module_class .= " et_pb_module et_pb_bg_layout_{$background_layout}";
                                $module_class .= " et_pb_module et_pb_button_alignment_{$button_alignment}";

                                $output = sprintf(
                                    '<div class="combo">
									<div%5$s class="%1$s%3$s%6$s">
                                        %2$s
                                    %4$s',
                                    'clearfix ',
                                    $content,
                                    isset($class) ? esc_attr( $class ) : '',
                                    '</div></div>',
                                    ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                                    ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
                                
                                );
                        
                                return $output;
                }
            }
        
            new ET_Builder_Module_ButtonAddToCar();