<?php
class ET_Builder_Module_Upsell extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Wc Upsell', 'et_builder' );
		$this->slug       = 'et_pb_upsell';
		$this->fb_support = true;

		$this->whitelisted_fields = array(

'module_id',
'module_class',
'colortitle',
'colorprice',
'overlay',
'coloricon',
'coloronsale',
'backgroundonsale',
'buttomcolor',
			'backgbuttom',
			'hover_bbut',
			'buttomcolorhover',
			'borderb_radius',
			'borderb_radiushover',
			'borderb_color',
			'border_width_button_hover',
			'border_width_button',





		);

		$this->fields_defaults = array();

		$this->main_css_element = '%%order_class%%';
		
                    
                    $this->custom_css_options = array();
	}

	
	function get_fields() {
		$fields = array(				
					'colortitle' => array(
				            'label'    => esc_html__( 'Title Color', 'et_builder' ),
				            'type'     => 'color',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					
						'colorprice' => array(
				            'label'    => esc_html__( 'Price Color', 'et_builder' ),
				            'type'     => 'color',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'overlay' => array(
				            'label'    => esc_html__( 'Overlay background', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'coloricon' => array(
				            'label'    => esc_html__( 'Overlay icon color', 'et_builder' ),
				            'type'     => 'color',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'coloronsale' => array(
				            'label'    => esc_html__( 'Sale Badge Color', 'et_builder' ),
				            'type'     => 'color',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'backgroundonsale' => array(
				            'label'    => esc_html__( 'Sale Badge Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
					'buttomcolor' => array(
				            'label'    => esc_html__( 'Button text color', 'et_builder' ),
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
				            'label'    => esc_html__( 'Button Hover Text Color ', 'et_builder' ),
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
				'default'         => '1',
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
			
			'borderb_color' => array(
				            'label'    => esc_html__( 'Button Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					

								'border_width_button_hover' => array(
				'label'           => esc_html__( 'Border width Button Hover', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'default'         => '1',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'tab_slug' => 'advanced',


			),
					'borderbhover_color' => array(
				            'label'    => esc_html__( 'Button Hover Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
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

			
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			
			
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
	
	            if (get_post_type() != 'product') {
                                                return;
                                }
								$module_id         = $this->shortcode_atts['module_id'];
								$module_class      = $this->shortcode_atts['module_class'];
								$colortitle 			= $this->shortcode_atts['colortitle'];
								$colorprice 	= $this->shortcode_atts['colorprice'];
								$overlay 			= $this->shortcode_atts['overlay'];
								$coloricon 			= $this->shortcode_atts['coloricon'];
								$coloronsale 			= $this->shortcode_atts['coloronsale'];
								$backgroundonsale 			= $this->shortcode_atts['backgroundonsale'];
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

						     if ( '' !== $colortitle ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% h2.woocommerce-loop-product__title',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $colortitle ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }

                        if ( '' !== $colorprice ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.woocommerce-Price-amount',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $colorprice ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $overlay ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.et_overlay',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $overlay ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $coloricon ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .et_overlay::before',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $coloricon ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						
						if ( '' !== $coloronsale ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.onsale',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $coloronsale ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $backgroundonsale ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.onsale',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $backgroundonsale ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $buttomcolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $buttomcolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $backgbuttom ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $backgbuttom ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $hover_bbut ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart:hover',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $hover_bbut ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $buttomcolorhover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart:hover',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $buttomcolorhover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_radius ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart',
				                'declaration' => sprintf(
					            'border-radius: %1$s%2$spx!important;',
					        esc_html( $borderb_radius ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_radiushover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart:hover',
				                'declaration' => sprintf(
					            'border-radius: %1$s%2$spx!important;',
					        esc_html( $borderb_radiushover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $borderb_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderbhover_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart:hover',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $borderbhover_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
		                if ( '' !== $border_width_button ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $border_width_button ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						
						if ( '' !== $border_width_button_hover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .button.product_type_simple.add_to_cart_button.ajax_add_to_cart:hover',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $border_width_button_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}

			   //////////////////////////////////////////////////////////////////////
                      
                                ob_start();
                                woocommerce_upsell_display();
                                $content = ob_get_clean();
                                  
                                 //////////////////////////////////////////////////////////////////////
                        
                                $output = sprintf(
                                    '<div%5$s class="%1$s%3$s%6$s">
                                        %2$s
                                    %4$s',
                                    'clearfix ',
                                    $content,
                                    esc_attr( 'et_pb_module' ),
                                    '</div>',
                                    ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                                    ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
                                );
                        
                                return $output;
                }
            }
new ET_Builder_Module_Upsell;
?>