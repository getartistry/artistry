<?php
class Woo_Divi_Custom_WooCommerce_Module extends ET_Builder_Module {
  function init() {
    $this->name = esc_html__( 'Woo Commerce Product', 'woodivi' );
    $this->slug = 'et_pb_woo_divi_product';
    $this->whitelisted_fields = array(
      'title',
      'button_text',
      'use_background_color',
      'background_color',
      'background_layout',
      'text_orientation',
      'content_new',
      'admin_label',
      'module_id',
      'module_class',
      'max_width',
      'woo_divi_product_id',
      'redirect_url'
    );
    $this->fields_defaults = array(
			'use_background_color' => array( 'on' ),
			'background_color'     => array( et_builder_accent_color(), 'add_default_setting' ),
			'background_layout'    => array( 'dark' ),
			'text_orientation'     => array( 'center' ),
		);
    $this->main_css_element = '%%order_class%%.et_pb_promo';
    $this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => __( 'Header', 'woodivi' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
				),
				'body'   => array(
					'label'    => __( 'Body', 'woodivi' ),
					'css'      => array(
						'line_height' => "{$this->main_css_element} p",
					),
				),
			),
			'background' => array(
				'use_background_color' => false,
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'button' => array(
				'button' => array(
					'label' => __( 'Button', 'woodivi' ),
				),
			),
		);
    $this->custom_css_options = array(
      'promo_description' => array(
        'label'    => __( 'Promo Description', 'woodivi' ),
        'selector' => '.et_pb_promo_description',
      ),
      'promo_button' => array(
        'label'    => __( 'Promo Button', 'woodivi' ),
        'selector' => '.et_pb_promo_button',
      ),
    );
  }
  function get_fields() {
    $fields = array(
      'woo_divi_product_id' => array(
        'label' => esc_html__( 'Product', 'woodivi' ),
        'type' => 'select',
        'option_category' => 'woo_divi_custom_product_id',
        'options' => $this->woo_divi_get_all_product(),
      ),
      'title' => array(
				'label'           => __( 'Title', 'woodivi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input your value to action title here.', 'woodivi' ),
			),
      'button_text' => array(
        'label'           => __( 'Button Text', 'woodivi' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => __( 'Input your desired button text, or leave blank for no button.', 'woodivi' ),
      ),
      'redirect_url' => array(
        'label'           => __( 'Redirect Url after product add to cart', 'woodivi' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => __( 'Input your redirect url after adding to cart ( www.yoursiteurl/cart ) , or leave blank for redirect to checkout.', 'woodivi' ),
      ),
      'use_background_color' => array(
				'label'           => __( 'Use Background Color', 'woodivi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'on'  => __( 'Yes', 'woodivi' ),
					'off' => __( 'No', 'woodivi' ),
				),
				'affects'           => array(
					'#et_pb_background_color',
				),
				'description'        => __( 'Here you can choose whether background color setting below should be used or not.', 'woodivi' ),
			),
      'background_color' => array(
				'label'             => __( 'Background Color', 'woodivi' ),
				'type'              => 'color-alpha',
				'depends_default'   => true,
				'description'       => __( 'Here you can define a custom background color for your CTA.', 'woodivi' ),
			),
			'background_layout' => array(
				'label'           => __( 'Text Color', 'woodivi' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'dark'  => __( 'Light', 'woodivi' ),
					'light' => __( 'Dark', 'woodivi' ),
				),
				'description' => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'woodivi' ),
			),
      'text_orientation' => array(
				'label'             => __( 'Text Orientation', 'woodivi' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => __( 'This will adjust the alignment of the module text.', 'woodivi' ),
			),
      'content_new' => array(
				'label'           => __( 'Content', 'woodivi' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => __( 'Input the main text content for your module here.', 'woodivi' ),
			),
      'admin_label' => array(
				'label'       => __( 'Admin Label', 'woodivi' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'woodivi' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'woodivi' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'woodivi' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'woodivi' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'woodivi' ),
			),
			'max_width' => array(
				'label'           => __( 'Max Width', 'woodivi' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'validate_unit'   => true,
			),
    );
    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $product_id           = $this->shortcode_atts['woo_divi_product_id'];
    $module_id            = $this->shortcode_atts['module_id'];
    $module_class         = $this->shortcode_atts['module_class'];
    $title                = $this->shortcode_atts['title'];
    $button_text          = $this->shortcode_atts['button_text'];
    $background_color     = $this->shortcode_atts['background_color'];
    $background_layout    = $this->shortcode_atts['background_layout'];
    $text_orientation     = $this->shortcode_atts['text_orientation'];
    $use_background_color = $this->shortcode_atts['use_background_color'];
    $max_width            = $this->shortcode_atts['max_width'];
    $custom_icon          = $this->shortcode_atts['button_icon'];
    $button_custom        = $this->shortcode_atts['custom_button'];
    $redirect_url         = $this->shortcode_atts['redirect_url'];

    $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

    if ( is_rtl() && 'left' === $text_orientation ) {
      $text_orientation = 'right';
    }
    if ( '' !== $max_width ) {
      ET_Builder_Element::set_style( $function_name, array(
        'selector'    => '%%order_class%%',
        'declaration' => sprintf(
          'max-width: %1$s;%2$s',
          esc_html( et_builder_process_range_value( $max_width ) ),
          ( 'center' === $text_orientation ? ' margin: 0 auto;' : '' )
        ),
      ) );
    }

    $class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";
    $output = sprintf(
      '<div%6$s class="et_pb_promo%4$s%7$s%8$s"%5$s>
        <div class="et_pb_promo_description">
          %1$s
          %2$s
        </div>
        %3$s
        <input class="'.$product_id.'-redirect-url" type="hidden" value="%9$s" />
      </div>',
      ( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
      $this->shortcode_content,
      ( '' !== $button_text
          ? sprintf( '<a class="et_pb_promo_button et_pb_button woo-divi-add-to-cart%5$s" href="%1$s"%3$s%4$s data-product-id="%6$s">%2$s</a> <div class="uil-ball-css" style="-webkit-transform:scale(0.38); display:none; margin:0 auto;" id="ajax-loader-'.$product_id.'"><div></div></div>',
          'javascript:void(0);',
            esc_html( $button_text ),
            ( '' ),
            '' !== $custom_icon && 'on' === $button_custom ? sprintf(
              ' data-icon="%1$s"',
              esc_attr( et_pb_process_font_icon( $custom_icon ) )
            ) : '',
            '' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
            $product_id
          )
          : ''
      ),
      esc_attr( $class ),
      ( 'on' === $use_background_color
        ? sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) )
        : ''
      ),
      ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
      ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
      ( 'on' !== $use_background_color ? ' et_pb_no_bg' : '' ),
      ( $redirect_url )
    );

    return $output;
  }

  function woo_divi_get_all_product() {
    $product_args = array( 'post_type' => 'product',
                            'post_status' => 'publish',
                            'posts_per_page' => -1
                          );
    $all_products = get_posts( $product_args );
    $product_field_array = array();
    foreach ( $all_products as $product ) {
      $product_field_array[$product->ID] = $product->post_title;
    }
    return $product_field_array;
  }
}
new Woo_Divi_Custom_WooCommerce_module();
