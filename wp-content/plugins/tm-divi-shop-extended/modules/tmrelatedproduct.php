<?php
class ET_Builder_Module_Related extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Wc Related product', 'et_builder' );
		$this->slug       = 'et_pb_related';
		$this->fb_support = true;

		$this->whitelisted_fields = array(
		
'backgroundtitle',
'text_orientation',
'module_id',
'module_class',
'overlay_icon',
'hover_overlay',
'promobg',
'promocolor',
'textcolor',
'textsize',
'force_fullwidth'
		);

		$this->fields_defaults = array(
		'text_orientation'  => array( 'left' ),
		'force_fullwidth'   => array( 'off' ),
			);
                    
                   $this->main_css_element = '%%order_class%% section.related.products>h2';
		$this->advanced_options = array(
			'background' => array(
			'label'    => esc_html__( 'Button', 'et_builder' ),
				'settings' => array(
					'color' => 'alpha',
				),
			),
		);
	}

	
	function get_fields() {
		$fields = array(
			'text_orientation' => array(
				'label'             => esc_html__( 'Title Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => esc_html__( 'This controls the how your text is aligned within the module.', 'et_builder' ),
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
			'textcolor' => array(
				            'label'    => esc_html__( 'Title Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category'   => 'layout',
				            'tab_slug' => 'advanced',
                    ),
					
						'textsize' => array(
				'label'           => esc_html__( 'Title Font Size', 'et_builder' ),
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
				
					'hover_overlay' => array(
				            'label'    => esc_html__( 'Hover Overlay Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
								
					
						'overlay_icon' => array(
				'label'             => esc_html__( 'Overlay Icon Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'description'       => esc_html__( 'Here you can define a custom color for the overlay icon', 'et_builder' ),
			),
					'force_fullwidth' => array(
				'label'             => esc_html__( 'Force Fullwidth', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => esc_html__( "No", 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'tab_slug'    => 'advanced',
			),
			
					'promobg' => array(
				            'label'    => esc_html__( 'Sale Badge Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				           'tab_slug' => 'advanced',
                    ),

					'promocolor' => array(
				            'label'    => esc_html__( 'Sale Badge Text Color', 'et_builder' ),
				            'type'     => 'color',
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
								$overlay_icon 			= $this->shortcode_atts['overlay_icon'];
								$text_orientation     = $this->shortcode_atts['text_orientation'];
								$max_width            = $this->shortcode_atts['max_width'];
								$max_width_tablet     = $this->shortcode_atts['max_width_tablet'];
								$max_width_phone      = $this->shortcode_atts['max_width_phone'];
								$max_width_last_edited = $this->shortcode_atts['max_width_last_edited'];
								$hover_overlay 	= $this->shortcode_atts['hover_overlay'];
								$promobg = $this->shortcode_atts['promobg'];
								$backgroundtitle = $this->shortcode_atts['backgroundtitle'];
								$force_fullwidth= $this->shortcode_atts['force_fullwidth'];
								$promocolor = $this->shortcode_atts['promocolor'];
								$textcolor= $this->shortcode_atts['textcolor'];
								$textsize= $this->shortcode_atts['textsize'];
								
								$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

						     if ( '' !== $overlay_icon ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .et_overlay:before',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $overlay_icon ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }

                        if ( '' !== $hover_overlay ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.et_overlay',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $hover_overlay ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $promobg ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.onsale',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $promobg ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $textcolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% section.related.products>h2',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $textcolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $textsize ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% section.related.products>h2',
				                'declaration' => sprintf(
					            'font-size: %1$s%2$spx!important;',
					        esc_html( $textsize ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
					
						if ( '' !== $promocolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.onsale',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $promocolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
			   //////////////////////////////////////////////////////////////////////
                      
                                ob_start();
                                woocommerce_output_related_products();
                                $content = ob_get_clean();
                                  
                                 //////////////////////////////////////////////////////////////////////
								 
								 if ( 'on' === $force_fullwidth ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => 'section.related.products>h2',
				'declaration' => 'width: 100% !important;',
			) );
		}
                        
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
new ET_Builder_Module_Related;
?>