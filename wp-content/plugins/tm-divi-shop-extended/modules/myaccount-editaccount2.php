<?php 
class ET_Builder_Module_editaccount2 extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Wc EditAccount2', 'et_builder' );
		$this->slug       = 'et_pb_editaccount2';
		$this->fb_support = true;

		$this->whitelisted_fields = array(
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
			'max_width',
			'max_width_tablet',
			'max_width_phone',
			'max_width_last_edited',
			/*Field campo requerido*/
			'requerido',
			/*Personalizacion del boton*/
			'button_style',
			'button_bordercolor',
			'button_border_width',
			'button_borderradius',
			'button_textcolor',
			'button_background_color',
			'button_bordercolor_hover',
			'button_border_width_hover',
			'button_borderradius_hover',
			'button_textcolor_hover',
			'button_background_color',
			'button_background_color_hover',
			/*Fin personalizacion del boton*/
			/*Personalizacion del input*/
			'input_style',
			'input_bordercolor',
			'input_border_width',
			'input_borderradius',
			'input_textcolor',
			'input_background_color',
			'input_bordercolor_hover',
			'input_bordercolor_focus',
			'input_border_width_hover',
			'input_borderradius_hover',
			'input_textcolor_hover',
			'input_background_color_hover',
			/*Fin personalizacion del input*/
		);

		$this->fields_defaults = array(
			'background_layout' => array( 'light' ),
			'text_orientation'  => array( 'left' ),
		);
		
				$this->options_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Text', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text' => array(
						'title'    => esc_html__( 'Text Description', 'et_builder' ),
						'priority' => 45,
						'tabbed_subtoggles' => true,
						'bb_icons_support' => true,
						'sub_toggles' => array(
							'p' => array( 'name' => 'P', 'icon' => 'text-left'),
							'a' => array( 'name' => 'A', 'icon' => 'text-link')
							
						),
					),
					
					'width' => array(
						'title'    => esc_html__( 'Sizing', 'et_builder' ),
						'priority' => 65,
					),
				),
			),
		);

		$this->main_css_element = '%%order_class%% .woocommerce form .form-row label';
		$this->advanced_options = array(
			'fonts' => array(
				'text'   => array(
					'label'    => esc_html__( 'Text', 'et_builder' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} label",
						'color' => "{$this->main_css_element} label",
					),
					'line_height' => array(
						'default' => '1.7em',
					),
					'font_size' => array(
						'default' => '14px',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'p',
					'hide_text_align' => true,
				),
				
				'link'   => array(
					'label'    => esc_html__( 'Link', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} a",
						'color' => "{$this->main_css_element} a",
					),
					'line_height' => array(
						'default' => '1em',
					),
					'font_size' => array(
						'default' => '14px',
					),
					'toggle_slug' => 'text',
					'sub_toggle'  => 'a',
				),
				'header_2'   => array(
					'label'    => esc_html__( 'Heading 2', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
					),
					'font_size' => array(
						'default' => '26px',
					),
					'line_height' => array(
						'default' => '1em',
					),
					'toggle_slug' => 'header',
					'sub_toggle'  => 'h2',
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
			'max_width' => array(),
			'text'      => array('sub_toggle'  => 'p'),
		);		
	}

	function get_fields() {
		$fields = array(
			'background_layout' => array(
				'label'             => esc_html__( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'light' => esc_html__( 'Dark', 'et_builder' ),
					'dark'  => esc_html__( 'Light', 'et_builder' ),
				),
				'description'       => esc_html__( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
			),
			'button_style' => array(
				'label'             => esc_html__( 'Button Style', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				 'tab_slug' => 'advanced',
				'options'           => array(
					'off' => esc_html__( "No", 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'description'     => esc_html__( 'Here you can display the product attributes to be selected before adding it to the cart', 'et_builder' ),
				'affects'           => array(

					'button_bordercolor',
					'button_border_width',
					'button_borderradius',
					'button_textcolor',
					'button_background_color',
					'button_bordercolor_hover',
					'button_border_width_hover',
					'button_borderradius_hover',
					'button_textcolor_hover',
					'button_background_color_hover',
				),
			),
			'input_style' => array(
				'label'             => esc_html__( 'Input Style', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				 'tab_slug' => 'advanced',
				'options'           => array(
					'off' => esc_html__( "No", 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'description'     => esc_html__( 'Here you can display the product attributes to be selected before adding it to the cart', 'et_builder' ),
				'affects'           => array(

					'input_bordercolor',
					'input_border_width',
					'input_borderradius',
					'input_textcolor',
					'input_background_color',
					'input_bordercolor_hover',
					'input_bordercolor_focus',
					'input_border_width_hover',
					'input_borderradius_hover',
					'input_textcolor_hover',
					'input_background_color_hover',
				),
			),
			'button_bordercolor' => array(
				            'label'    => esc_html__( 'Select Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),
			'button_bordercolor_hover' => array(
				            'label'    => esc_html__( 'Select Hover Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),
			'button_border_width' => array(
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
			'button_border_width_hover' => array(
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
			'button_borderradius' => array(
				'label'           => esc_html__( 'Button Border Radius', 'et_builder' ),
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
			'button_borderradius_hover' => array(
				'label'           => esc_html__( 'Button Hover Border Radius', 'et_builder' ),
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
			'button_textcolor' => array(
				            'label'    => esc_html__( 'Select Text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),
			'button_textcolor_hover' => array(
				            'label'    => esc_html__( 'Select Hover Text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),
					
			'button_background_color' => array(
				            'label'    => esc_html__( 'Button Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
			'button_background_color_hover' => array(
				            'label'    => esc_html__( 'Button Hover Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
			'input_bordercolor' => array(
				            'label'    => esc_html__( 'Select Input Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),	
			'input_bordercolor_hover' => array(
				            'label'    => esc_html__( 'Select Hover Input Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),	
			'input_bordercolor_focus' => array(
				            'label'    => esc_html__( 'Select Focus Input Border Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),	
			'input_border_width' => array(
				'label'           => esc_html__( 'Input Border Width', 'et_builder' ),
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
			'input_border_width_hover' => array(
				'label'           => esc_html__( 'Input Hover Border Width', 'et_builder' ),
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
			'input_borderradius' => array(
				'label'           => esc_html__( 'Input Border Radius', 'et_builder' ),
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
			'input_borderradius_hover' => array(
				'label'           => esc_html__( 'Input Hover Border Radius', 'et_builder' ),
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
			'input_textcolor' => array(
				            'label'    => esc_html__( 'Select Input Text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),
			'input_textcolor_hover' => array(
				            'label'    => esc_html__( 'Select Hover Input Text Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
							'option_category' => 'basic_option',
							'depends_show_if' => 'on',
				            'tab_slug' => 'advanced',
                    ),
			'input_background_color' => array(
				            'label'    => esc_html__( 'Input Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
			'input_background_color_hover' => array(
				            'label'    => esc_html__( 'Input Hover Background Color', 'et_builder' ),
				            'type'     => 'color-alpha',
				            'custom_color'      => true,
				            'tab_slug' => 'advanced',
                    ),
			'requerido' => array(
				            'label'    => esc_html__( 'Color *', 'et_builder' ),
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
                        // Ocultar text shadow duplicado
	protected function _add_additional_text_shadow_fields() {}
	function shortcode_callback( $atts, $content = null, $function_name ) {
		
		$module_id            = $this->shortcode_atts['module_id'];
		$module_class         = $this->shortcode_atts['module_class'];
		/////////*Estilo para campos requeridos ** *//////////
		$requerido         	  = $this->shortcode_atts['requerido'];
		/*Personalizacion del botón*/
		$button_style        =$this->shortcode_atts['button_style'];
		$button_bordercolor        =$this->shortcode_atts['button_bordercolor'];
		$button_border_width         	  = $this->shortcode_atts['button_border_width'];
		$button_borderradius         	  = $this->shortcode_atts['button_borderradius'];
		$button_textcolor         	  = $this->shortcode_atts['button_textcolor'];
		$button_background_color         	  = $this->shortcode_atts['button_background_color'];
		$button_bordercolor_hover        	  = $this->shortcode_atts['button_bordercolor_hover'];
		$button_border_width_hover        	  = $this->shortcode_atts['button_border_width_hover'];
		$button_borderradius_hover        	  = $this->shortcode_atts['button_borderradius_hover'];
		$button_textcolor_hover        	  = $this->shortcode_atts['button_textcolor_hover'];
		$button_background_color_hover        	  = $this->shortcode_atts['button_background_color_hover'];
		/*Fin Personalizacion del botón*/
		
		/*Personalizacion del Input*/
		$input_style    = $this->shortcode_atts['input_style'];
		$input_bordercolor    = $this->shortcode_atts['input_bordercolor'];
		$input_border_width         	  = $this->shortcode_atts['input_border_width'];
		$input_borderradius         	  = $this->shortcode_atts['input_borderradius'];
		$input_textcolor         	  = $this->shortcode_atts['input_textcolor'];
		$input_background_color         	  = $this->shortcode_atts['input_background_color'];
		$input_bordercolor_hover    = $this->shortcode_atts['input_bordercolor_hover'];
		$input_bordercolor_focus    = $this->shortcode_atts['input_bordercolor_focus'];
		$input_border_width_hover         	  = $this->shortcode_atts['input_border_width_hover'];
		$input_borderradius_hover         	  = $this->shortcode_atts['input_borderradius_hover'];
		$input_textcolor_hover         	  = $this->shortcode_atts['input_textcolor_hover'];
		$input_background_color_hover         	  = $this->shortcode_atts['input_background_color_hover'];
		/*Fin Personalizacion del Input*/	
		$background_layout    = $this->shortcode_atts['background_layout'];
		$max_width            = $this->shortcode_atts['max_width'];
		$max_width_tablet     = $this->shortcode_atts['max_width_tablet'];
		$max_width_phone      = $this->shortcode_atts['max_width_phone'];
		$max_width_last_edited=$this->shortcode_atts['max_width_last_edited'];
		


		    		                                /////////////////////TEMPLATE WOOCOMERCE EDIT ACCOUNT/////////////////////////
                                  
                                ob_start();
                              //  wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>

	<fieldset>
		<legend><?php _e( 'Password change', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current"><?php _e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php _e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php _e( 'Confirm new password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" />
		</p>
	</fieldset>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" />
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' );

                                $content = ob_get_clean();
                                  
                              /////////////////////TEMPLATE WOOCOMERCE MY LOGIN/////////////////////////
							  
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

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
		
						if ( '' !== $requerido ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% span.required',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $requerido ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						
						if ( '' !== $button_style ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% ',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $button_style ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $button_border_width ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% input.woocommerce-Button.button',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $button_border_width ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $button_border_width_hover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% input.woocommerce-Button.button:hover',
				                'declaration' => sprintf(
					            'border-width: %1$s%2$spx!important;',
					        esc_html( $button_border_width_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $button_bordercolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% input.woocommerce-Button.button',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $button_bordercolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $button_bordercolor_hover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% input.woocommerce-Button.button:hover',
				                'declaration' => sprintf(
					            'border-color: %1$s%2$s!important;',
					        esc_html( $button_bordercolor_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }	
						if ( '' !== $button_textcolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% input.woocommerce-Button.button',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $button_textcolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $button_textcolor_hover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% input.woocommerce-Button.button:hover',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $button_textcolor_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $button_background_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% input.woocommerce-Button.button',
				                'declaration' => sprintf(
					            'background-color: %1$s%2$s!important;',
					        esc_html( $button_background_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $button_background_color_hover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% input.woocommerce-Button.button:hover',
				                'declaration' => sprintf(
					            'background-color: %1$s%2$s!important;',
					        esc_html( $button_background_color_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $button_borderradius ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% input.woocommerce-Button.button',
								'declaration' => sprintf(
								'border-radius: %1$spx!Important;',
								   esc_html( $button_borderradius ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $button_borderradius_hover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% input.woocommerce-Button.button:hover',
								'declaration' => sprintf(
								'border-radius: %1$spx!Important;',
								   esc_html( $button_borderradius_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_style ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% ',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $input_style ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_bordercolor ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-Input--text.input-text,.woocommerce-Input--email input-text,.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea',
								'declaration' => sprintf(
								 'border-color: %1$s%2$s!important;',
								   esc_html( $input_bordercolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_bordercolor_hover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-Input--text.input-text:hover,.woocommerce-Input--email input-text:hover,.woocommerce form .form-row input.input-text:hover, .woocommerce form .form-row textarea:hover',
								'declaration' => sprintf(
								 'border-color: %1$s%2$s!important;',
								   esc_html( $input_bordercolor_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_bordercolor_focus ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-Input--text.input-text:focus,.woocommerce-Input--email input-text:focus,.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus,.et_pb_editaccount_0 .woocommerce-Input--text.input-text:focus, .woocommerce-Input--email input-text:focus, .woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus',
								'declaration' => sprintf(
								 'border-color: %1$s%2$s!important;',
								   esc_html( $input_bordercolor_focus ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_border_width ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-Input--text.input-text,.woocommerce-Input--email input-text,.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $input_border_width ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_border_width_hover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-Input--text.input-text:hover,.woocommerce-Input--email input-text:hover,.woocommerce form .form-row input.input-text:hover, .woocommerce form .form-row textarea:hover',
								'declaration' => sprintf(
								'border-width: %1$spx!Important;',
								   esc_html( $input_border_width_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_borderradius ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-Input--text.input-text,.woocommerce-Input--email input-text,.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea',
								'declaration' => sprintf(
								'border-radius: %1$spx!Important;',
								   esc_html( $input_borderradius ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_borderradius_hover ) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .woocommerce-Input--text.input-text:hover,.woocommerce-Input--email input-text:hover,.woocommerce form .form-row input.input-text:hover, .woocommerce form .form-row textarea:hover',
								'declaration' => sprintf(
								'border-radius: %1$spx!Important;',
								   esc_html( $input_borderradius_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
								),
							) );
						}
						if ( '' !== $input_textcolor ) {
			                ET_Builder_Element::set_style( $function_name, array(
				               'selector'    => '%%order_class%% .woocommerce-Input--text.input-text,.woocommerce-Input--email input-text,.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $input_textcolor ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $input_textcolor_hover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                'selector'    => '%%order_class%% .woocommerce-Input--text.input-text:hover,.woocommerce-Input--email input-text:hover,.woocommerce form .form-row input.input-text:hover, .woocommerce form .form-row textarea:hover',
				                'declaration' => sprintf(
					            'color: %1$s%2$s!important;',
					        esc_html( $input_textcolor_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $input_background_color ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                 'selector'    => '%%order_class%% .woocommerce-Input--text.input-text,.woocommerce-Input--email input-text,.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea',
				                'declaration' => sprintf(
					            'background-color: %1$s%2$s!important;',
					        esc_html( $input_background_color ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }
						if ( '' !== $input_background_color_hover ) {
			                ET_Builder_Element::set_style( $function_name, array(
				                 'selector'    => '%%order_class%% .woocommerce-Input--text.input-text:hover,.woocommerce-Input--email input-text:hover,.woocommerce form .form-row input.input-text:hover, .woocommerce form .form-row textarea:hover',
				                'declaration' => sprintf(
					            'background-color: %1$s%2$s!important;',
					        esc_html( $input_background_color_hover ),
					        et_is_builder_plugin_active() ? ' !important' : ''
				            ),
			                ) );
		                }

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";
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
new ET_Builder_Module_editaccount2;
?>