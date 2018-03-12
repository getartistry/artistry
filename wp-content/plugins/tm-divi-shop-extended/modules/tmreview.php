<?php
class ET_Builder_Module_Review extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Wc Review', 'et_builder' );
		$this->slug       = 'et_pb_review';
		$this->fb_support = true;

		$this->whitelisted_fields = array(

'module_id',
'module_class',
'backgbuttom',
'text_color',
'hover_bbut',
'buttomcolor',
'buttomcolorhover',
'borderb_radius',
'borderb_color',
'borderbhover_color',
                   /*     
                        'buttonborderwidth',
                        'buttonbghover',
                        'buttoncolourhover',
                        'buttonbordercolourhover',*/
		);

		$this->fields_defaults = array();

		$this->main_css_element = '%%order_class%% #review_form #respond #submit';
		
                    
                    $this->custom_css_options = array();
					
					$this->advanced_options = array(
			'background' => array(
			'label'    => esc_html__( 'Button', 'et_builder' ),
				'settings' => array(
					'color' => 'alpha',
						'selector' => '#review_form #respond #submit',
						
				),
			),
		);
				
	}

	
	function get_fields() {
		$fields = array(			

'text_color' => array(
				            'label'    => esc_html__( 'Star Color', 'et_builder' ),
				            'type'     => 'color-alpha',
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
					
					'buttomcolor' => array(
				            'label'    => esc_html__( 'Button Text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
					
						'buttomcolorhover' => array(
				            'label'    => esc_html__( 'Button Hover Text Color ', 'et_builder' ),
				            'type'     => 'color-alpha',
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
								$backgbuttom 			= $this->shortcode_atts['backgbuttom'];
								$hover_bbut 	= $this->shortcode_atts['hover_bbut'];
								$text_color 	= $this->shortcode_atts['text_color'];
								$buttomcolor= $this->shortcode_atts['buttomcolor'];
								$buttomcolorhover= $this->shortcode_atts['buttomcolorhover'];
								$borderb_radius= $this->shortcode_atts['borderb_radius'];
								$borderb_color= $this->shortcode_atts['borderb_color'];
								$borderbhover_color= $this->shortcode_atts['borderbhover_color'];
								$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

						     if ( '' !== $text_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce p.stars a::before, .star-rating span:before',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $text_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }

                        if ( '' !== $text_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .comment-form-rating .stars span a::before',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $text_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }

		
	
						if ( '' !== $backgbuttom ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% #review_form #respond #submit',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $backgbuttom ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
					
						if ( '' !== $hover_bbut ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% #review_form #respond #submit:hover',
				                'declaration' => sprintf(
					            'background: %1$s%2$s!important;',
					        esc_html( $hover_bbut ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $buttomcolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% #review_form #respond #submit',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $buttomcolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $buttomcolorhover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% #review_form #respond #submit:hover',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $buttomcolorhover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_radius ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% #review_form #respond #submit',
				                'declaration' => sprintf(
					            'border-radius: %1$s%2$spx!important;',
					        esc_html( $borderb_radius ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderb_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% #review_form #respond #submit',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $borderb_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $borderbhover_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% #review_form #respond #submit:hover',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $borderbhover_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
			   //////////////////////////////////////////////////////////////////////
                      
                                ob_start();
                                comments_template();
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
new ET_Builder_Module_Review;
?>