<?php
class ET_Builder_Module_information extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Wc Additional Information', 'et_builder' );
		$this->slug       = 'et_pb_infoad';
		$this->fb_support = true;

		$this->whitelisted_fields = array(

'module_id',
'module_class',
'text_color',
		);

		$this->fields_defaults = array();

		$this->main_css_element = '%%order_class%%';
		
        $this->advanced_options = array(
			'fonts' => array(
				'header'   => array(
					'label'    => esc_html__( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
					),
					'font_size' => array('default' => '20px'),
                    'line_height' => array('default' => '1.5em'),
				),
				'text'   => array(
					'label'    => esc_html__( 'Table', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .shop_attributes",
					),
					'font_size' => array('default' => '14px'),
                    'line_height' => array('default' => '1.5em'),
				),
			),
			
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'text_color' => array(
				            'label'    => esc_html__( 'Table text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
		);
	}

	
	function get_fields() {
		$fields = array(		
		
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
								$text_color 	= $this->shortcode_atts['text_color'];
								
								$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

						     if ( '' !== $text_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce table.shop_attributes',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $text_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }

                
			   //////////////////////////////////////////////////////////////////////
                      
                                ob_start();
                                woocommerce_product_additional_information_tab();
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
new ET_Builder_Module_information;
?>