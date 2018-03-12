<?php

	// Settings header title
	$header_title = 'Divi Shop Settings Panel';

	// Get active theme name
	$themename = 'Divi';

	// Help header label
	$help_header = esc_html__( "Help", $themename );

	// Panel Tabs
	$tabs = array(
			array(
				'desc' => esc_html__( "Products", $themename ),
				'id'   => 'products',
			),
			array(
				'desc' => esc_html__( "Top Rated", $themename ),
				'id'   => 'top-rated',
			),
			array(
				'desc' => esc_html__( "Recent Reviews", $themename ),
				'id'   => 'recent-reviews',
			),
			array(
				'desc' => esc_html__( "Product Search", $themename ),
				'id'   => 'product-search',
			),
			array(
				'desc' => esc_html__( "Price Filter", $themename ),
				'id'   => 'price-filter',
			),
			array(
				'desc' => esc_html__( "Buttons", $themename ),
				'id'   => 'buttons',
			),
			array(
				'desc' => esc_html__( "Related Products", $themename ),
				'id'   => 'related-products',
			),
			array(
				'desc' => esc_html__( "Other", $themename ),
				'id'   => 'other',
			),
			/*array(
				'desc' => esc_html__( "Mini Cart", $themename ),
				'id'   => 'mini-cart',
			),*/
	);

	// Multidimensional array of settings
	$settings = array(

		// Tab Products
			
			// ------ Misc ---------------------------------------------------------------------------------------------
				array(
	                'name'              => esc_html__( 'Product Page Layout', $themename ),
	                'type'              => 'label-header',
	                'tab_id'			=> 'products',
	            ),

				array(
					'name'              => esc_html__( 'Disable WooCommerce default template?', $themename ),
					'id'                => 'et_shop_other_custom_woo_templates',
					'std'               => '',
					'type'              => 'checkbox',
					'desc'              =>  __( 'Here you can Enable or Disable Woocommerce default product page layout.  If Enabled, you will use Woocommerce default product page layout. If Disabled, you will override and customize even more your products page layout, which means you will be able to create any type of product page layout.', $themename ),
					'main_setting_name' => 'et_shop_other',
					'sub_setting_name'  => 'custom_woo_templates',
					'tab_id'			=> 'products',
				),
			//---------------------------------------------------------------------------------------------------
            // ------ Title ---------------------------------------------------------------------------------------------    
		        array(
	                'name'              => esc_html__( 'Title', $themename ),
	                'type'              => 'label-header',
	                'tab_id'			=> 'products',
	            ),
	            //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_bs_title_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Title Font Color', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'title_color',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_bs_title_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the title', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'title_font',
		            'tab_id'			=> 'products',                  
		        ),
		        
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Size', $themename ),
		            'id'                => 'et_shop_bs_title_size',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Size in pixels for the title', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'title_size',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Letter Spacing', $themename ),
		            'id'                => 'et_shop_bs_title_letter_spacing',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Space between characters on the title', $themename ),
		           	'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'title_letter_spacing',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_bs_title_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Height of the lines on the title', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'title_line_height',
		            'tab_id'			=> 'products',
		        ),
	        //---------------------------------------------------------------------------------------------------
			//------- Sub Title -----------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Subtitle', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_bs_subtitle_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Subtitle Font Color', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'subtitle_color',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_bs_subtitle_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'subtitle_font',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font Size', $themename ),
		            'id'              => 'et_shop_bs_subtitle_size',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Size in pixels for the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'subtitle_size',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Letter Spacing', $themename ),
		            'id'              => 'et_shop_bs_subtitle_letter_spacing',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Space between characters on the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'subtitle_letter_spacing',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_bs_subtitle_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Height of the lines on the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'subtitle_line_height',
		            'tab_id'			=> 'products',
		        ),
	        //---------------------------------------------------------------------------------------------------
			//------- Content -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Content', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'products',
		        ),
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_bs_content_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Content Font Color', $themename ),
				    'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'content_color',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_bs_content_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the content', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'content_font',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font Size', $themename ),
		            'id'              => 'et_shop_bs_content_size',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Size in pixels for the content', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'content_size',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Letter Spacing', $themename ),
		            'id'              => 'et_shop_bs_content_letter_spacing',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Space between characters on the content', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'content_letter_spacing',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_bs_content_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Height of the lines on the content', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'content_line_height',
		            'tab_id'			=> 'products',
		        ),
		    //---------------------------------------------------------------------------------------------------
			//------- Hover ---------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Hover Effect', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'products',
		        ),
		        array(
		            'name'              => esc_html__( 'Active', $themename ),
		            'id'                => 'et_shop_bs_hover_effect',
		            'std'               => '',
		            'type'              => 'checkbox',
		            'desc'              => __( 'Enable or Disable hover effect for Woocommerce product widget image', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'hover_effect',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Border Color', $themename ),
		            'id'              => 'et_shop_bs_hover_border_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the hover effect on Woocommerce Product widget image border', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'hover_border_color',
		            'tab_id'			=> 'products',
		        ),
		    //---------------------------------------------------------------------------------------------------
		    //------- Divider -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Divider', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Show Line', $themename ),
		            'id'                => 'et_shop_bs_separator',
		            'std'               => '',
		            'type'              => 'checkbox',
		            'desc'              => __( 'Enable or Disable Divider line between products for Woocommerce product widget', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'separator',
		            'tab_id'			=> 'products',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Color', $themename ),
		            'id'              => 'et_shop_bs_separator_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the divider line between products', $themename ),
		            'main_setting_name' => 'et_shop_bs',
		            'sub_setting_name'  => 'separator_color',
		            'tab_id'			=> 'products',
		        ),
	        //---------------------------------------------------------------------------------------------------	
	

		// Tab Top Rated

            // ------ Title ---------------------------------------------------------------------------------------------    
		        array(
	                'name'              => esc_html__( 'Title', $themename ),
	                'type'              => 'label-header',
	                'tab_id'			=> 'top-rated',
	            ),
	            //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_tr_title_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Title Font Color', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'title_color',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_tr_title_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the title', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'title_font',
		            'tab_id'			=> 'top-rated',                 
		        ),
		        
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Size', $themename ),
		            'id'                => 'et_shop_tr_title_size',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Size in pixels for the title', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'title_size',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Letter Spacing', $themename ),
		            'id'                => 'et_shop_tr_title_letter_spacing',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Space between characters on the title', $themename ),
		           	'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'title_letter_spacing',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_tr_title_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Height of the lines on the title', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'title_line_height',
		            'tab_id'			=> 'top-rated',
		        ),
	        //---------------------------------------------------------------------------------------------------
			//------- Sub Title -----------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Subtitle', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_tr_subtitle_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Subtitle Font Color', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'subtitle_color',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_tr_subtitle_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'subtitle_font',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font Size', $themename ),
		            'id'              => 'et_shop_tr_subtitle_size',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Size in pixels for the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'subtitle_size',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Letter Spacing', $themename ),
		            'id'              => 'et_shop_tr_subtitle_letter_spacing',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Space between characters on the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'subtitle_letter_spacing',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_tr_subtitle_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Height of the lines on the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'subtitle_line_height',
		            'tab_id'			=> 'top-rated',
		        ),
	        //---------------------------------------------------------------------------------------------------
			//------- Content -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Content', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'top-rated',
		        ),
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_tr_content_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Content Font Color', $themename ),
				    'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'content_color',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_tr_content_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the content', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'content_font',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font Size', $themename ),
		            'id'              => 'et_shop_tr_content_size',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Size in pixels for the content', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'content_size',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Letter Spacing', $themename ),
		            'id'              => 'et_shop_tr_content_letter_spacing',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Space between characters on the content', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'content_letter_spacing',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_tr_content_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Height of the lines on the content', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'content_line_height',
		            'tab_id'			=> 'top-rated',
		        ),
		    //---------------------------------------------------------------------------------------------------
			//------- Hover ---------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Hover Effect', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'top-rated',
		        ),
		        array(
		            'name'              => esc_html__( 'Active', $themename ),
		            'id'                => 'et_shop_tr_hover_effect',
		            'std'               => '',
		            'type'              => 'checkbox',
		            'desc'              => __( 'Enable or Disable hover effect for Woocommerce product widget image', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'hover_effect',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Border Color', $themename ),
		            'id'              => 'et_shop_tr_hover_border_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the hover effect on Woocommerce Product widget image border', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'hover_border_color',
		            'tab_id'			=> 'top-rated',
		        ),
		    //---------------------------------------------------------------------------------------------------
		    //------- Divider -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Divider', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Show Line', $themename ),
		            'id'                => 'et_shop_tr_separator',
		            'std'               => '',
		            'type'              => 'checkbox',
		            'desc'              => __( 'Enable or Disable Divider line between products for Woocommerce product widget', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'separator',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Color', $themename ),
		            'id'              => 'et_shop_tr_separator_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the divider line between products', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'separator_color',
		            'tab_id'			=> 'top-rated',
		        ),
	        //---------------------------------------------------------------------------------------------------	
			//------- Rating Stars --------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Rating Stars', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'top-rated',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Color', $themename ),
		            'id'              => 'et_shop_tr_star_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the rating stars on the Woocommerce Top Rated widget', $themename ),
		            'main_setting_name' => 'et_shop_tr',
		            'sub_setting_name'  => 'star_color',
		            'tab_id'			=> 'top-rated',
		        ),
			//---------------------------------------------------------------------------------------------------

		
		// Recent Reviews

			// ------ Title ---------------------------------------------------------------------------------------------    
		        array(
	                'name'              => esc_html__( 'Title', $themename ),
	                'type'              => 'label-header',
	                'tab_id'			=> 'recent-reviews',
	            ),
	            //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_rr_title_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Title Font Color', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'title_color',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_rr_title_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the title', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'title_font',
		            'tab_id'			=> 'recent-reviews',                 
		        ),
		        
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Size', $themename ),
		            'id'                => 'et_shop_rr_title_size',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Size in pixels for the title', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'title_size',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Letter Spacing', $themename ),
		            'id'                => 'et_shop_rr_title_letter_spacing',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Space between characters on the title', $themename ),
		           	'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'title_letter_spacing',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_rr_title_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Height of the lines on the title', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'title_line_height',
		            'tab_id'			=> 'recent-reviews',
		        ),
	        //---------------------------------------------------------------------------------------------------
			//------- Sub Title -----------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Subtitle', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_rr_subtitle_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Subtitle Font Color', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'subtitle_color',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_rr_subtitle_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'subtitle_font',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font Size', $themename ),
		            'id'              => 'et_shop_rr_subtitle_size',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Size in pixels for the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'subtitle_size',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Letter Spacing', $themename ),
		            'id'              => 'et_shop_rr_subtitle_letter_spacing',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Space between characters on the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'subtitle_letter_spacing',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_rr_subtitle_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Height of the lines on the subtitle', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'subtitle_line_height',
		            'tab_id'			=> 'recent-reviews',
		        ),
	        //---------------------------------------------------------------------------------------------------
			//------- Content -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Content', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_rr_content_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Content Font Color', $themename ),
				    'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'content_color',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_rr_content_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the content', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'content_font',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font Size', $themename ),
		            'id'              => 'et_shop_rr_content_size',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Size in pixels for the content', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'content_size',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Letter Spacing', $themename ),
		            'id'              => 'et_shop_rr_content_letter_spacing',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Space between characters on the content', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'content_letter_spacing',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_rr_content_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'            => __( 'Height of the lines on the content', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'content_line_height',
		            'tab_id'			=> 'recent-reviews',
		        ),
		    //---------------------------------------------------------------------------------------------------
			//------- Hover ---------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Hover Effect', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        array(
		            'name'              => esc_html__( 'Active', $themename ),
		            'id'                => 'et_shop_rr_hover_effect',
		            'std'               => '',
		            'type'              => 'checkbox',
		            'desc'              => __( 'Enable or Disable hover effect for Woocommerce product widget image', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'hover_effect',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Border Color', $themename ),
		            'id'              => 'et_shop_rr_hover_border_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the hover effect on Woocommerce Product widget image border', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'hover_border_color',
		            'tab_id'			=> 'recent-reviews',
		        ),
		    //---------------------------------------------------------------------------------------------------
		    //------- Divider -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Divider', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Show Line', $themename ),
		            'id'                => 'et_shop_rr_separator',
		            'std'               => '',
		            'type'              => 'checkbox',
		            'desc'              => __( 'Enable or Disable Divider line between products for Woocommerce product widget', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'separator',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Color', $themename ),
		            'id'              => 'et_shop_rr_separator_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the divider line between products', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'separator_color',
		            'tab_id'			=> 'recent-reviews',
		        ),
	        //---------------------------------------------------------------------------------------------------	
			//------- Rating Stars --------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Rating Stars', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'recent-reviews',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Color', $themename ),
		            'id'              => 'et_shop_rr_star_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the rating stars on the Woocommerce Top Rated widget', $themename ),
		            'main_setting_name' => 'et_shop_rr',
		            'sub_setting_name'  => 'star_color',
		            'tab_id'			=> 'recent-reviews',
		        ),
			//---------------------------------------------------------------------------------------------------


		// Product Search

			array(
				'name'              => esc_html__( 'Show Search Form', $themename ),
				'id'                => 'et_shop_search_active',
				'std'               => '',
				'type'              => 'checkbox',
				'desc'              =>  __( 'Here you can choose if you want to show the search form in the header.', $themename ),
				'main_setting_name' => 'et_shop_search',
				'sub_setting_name'  => 'active',
				'tab_id'			=> 'product-search',
			),
			// ------ Title ---------------------------------------------------------------------------------------------    
		        array(
	                'name'              => esc_html__( 'Title', $themename ),
	                'type'              => 'label-header',
	                'tab_id'			=> 'product-search',
	            ),
	        	//---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_search_title_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Title Font Color', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'title_color',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_search_title_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'title_font',
		            'tab_id'			=> 'product-search',                 
		        ),
		        
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Size', $themename ),
		            'id'                => 'et_shop_search_title_size',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Size in pixels for the title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'title_size',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Letter Spacing', $themename ),
		            'id'                => 'et_shop_search_title_letter_spacing',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Space between characters on the title', $themename ),
		           	'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'title_letter_spacing',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_search_title_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Height of the lines on the title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'title_line_height',
		            'tab_id'			=> 'product-search',
		        ),
	        //---------------------------------------------------------------------------------------------------
		    // ------ Input ---------------------------------------------------------------------------------------------    
		        
		        array(
	                'name'              => esc_html__( 'Input', $themename ),
	                'type'              => 'label-header',
	                'tab_id'			=> 'product-search',
	            ),
	            //---------------------------------------------------------------------------------------------------
	            array(
					'name'              => esc_html__( 'Custom Styles', $themename ),
					'id'                => 'et_shop_search_custom',
					'std'               => '',
					'type'              => 'checkbox',
					'desc'              =>  __( 'Enables the custom styles settings for the search form.', $themename ),
					'main_setting_name' => 'et_shop_search',
					'sub_setting_name'  => 'custom',
					'tab_id'			=> 'product-search',
				),
	            //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_search_input_font_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Title Font Color', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'input_font_color',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_search_input_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'input_font',
		            'tab_id'			=> 'product-search',                 
		        ),
		        
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Size', $themename ),
		            'id'                => 'et_shop_search_input_font_size',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Size in pixels for the title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'input_font_size',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Letter Spacing', $themename ),
		            'id'                => 'et_shop_search_input_letter_spacing',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Space between characters on the title', $themename ),
		           	'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'input_letter_spacing',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Height', $themename ),
		            'id'              => 'et_shop_search_input_line_height',
		            'std'             => '',
		            'type'            => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Height of the lines on the title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'input_line_height',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Background color', $themename ),
		            'id'                => 'et_shop_search_input_bg_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select color for the search input box background', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'input_bg_color',
		            'tab_id'			=> 'product-search',
		        ),
	        //---------------------------------------------------------------------------------------------------
			// ------ Button ---------------------------------------------------------------------------------------------

		        array(
	                'name'              => esc_html__( 'Button', $themename ),
	                'type'              => 'label-header',
	                'tab_id'			=> 'product-search',
	            ),
	            //---------------------------------------------------------------------------------------------------
	            array(
					'name'              => esc_html__( 'Custom Styles', $themename ),
					'id'                => 'et_shop_search_button_custom_style',
					'std'               => '',
					'type'              => 'checkbox',
					'desc'              =>  __( 'Here you can choose if you want to use custom styles for the search form button.', $themename ),
					'main_setting_name' => 'et_shop_search',
					'sub_setting_name'  => 'button_custom_style',
					'tab_id'			=> 'product-search',
				),
				//---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Button Icon Color', $themename ),
		            'id'                => 'et_shop_search_button_icon_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Pick a color for the font of the search button.', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'button_icon_color',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Background Color', $themename ),
		            'id'                => 'et_shop_search_button_bg_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select color for the search button background', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'button_bg_color',
		            'tab_id'			=> 'product-search',
		        ),
		    //---------------------------------------------------------------------------------------------------
		    //------- Divider -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Divider', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Show Line', $themename ),
		            'id'                => 'et_shop_search_separator',
		            'std'               => '',
		            'type'              => 'checkbox',
		            'desc'              => __( 'Enable or Disable divider line below title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'separator',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Line Color', $themename ),
		            'id'              => 'et_shop_search_separator_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Select color for the divider line below title', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'separator_color',
		            'tab_id'			=> 'product-search',
		        ),	
		    //---------------------------------------------------------------------------------------------------
		    //------- Search Bar Styles -------------------------------------------------------------------------------------------
				array(
		            'name'              => esc_html__( 'Search Bar Styles', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'product-search',
			    ),
			    //---------------------------------------------------------------------------------------------------
				array(
		            'name'              => esc_html__( 'Border Width', $themename ),
		            'id'                => 'et_shop_search_bar_border_width',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Width of the search form border', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'bar_border_width',
		            'tab_id'			=> 'product-search',
		        ),
		        //---------------------------------------------------------------------------------------------------
				array(
		            'name'              => esc_html__( 'Border Radius', $themename ),
		            'id'                => 'et_shop_search_bar_border_radius',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Border radius for the search bar', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'bar_border_radius',
		            'tab_id'			=> 'product-search',
		        ),
				//---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Border Color', $themename ),
		            'id'              => 'et_shop_search_bar_border_color',
		            'std'             => '',
		            'type'            => 'color',
		            'desc'            => __( 'Pick a color for the border of the search bar', $themename ),
		            'main_setting_name' => 'et_shop_search',
		            'sub_setting_name'  => 'bar_border_color',
		            'tab_id'			=> 'product-search',
		        ),	
			//---------------------------------------------------------------------------------------------------
	    
	    // Price filter

	        //------- Title -------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Title', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'price-filter',
			    ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_pf_text_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select font color for the Woocommerce Price Range Filter widget', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'text_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_pf_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Select a font type for the Woocommerce Price Range Filter widget title', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'font',
		            'tab_id'			=> 'price-filter',                 
		        ),
		        
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Size', $themename ),
		            'id'                => 'et_shop_pf_font_size',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Font size for price filter title', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'font_size',
		            'tab_id'			=> 'price-filter',
		        ),
		    //--------------------------------------------------------------------------------------------------------
			//------- Price Range Filter Line ---------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Price Range Filter Line', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'price-filter',
			    ),
			    //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Color', $themename ),
		            'id'                => 'et_shop_pf_line_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select color for the price range filter line', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'line_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Background Color', $themename ),
		            'id'                => 'et_shop_pf_line_bg_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Pick a background color for the price filter line', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'line_bg_color',
		            'tab_id'			=> 'price-filter',
		        ),
		    //--------------------------------------------------------------------------------------------------------
		   	//------- Touch Handlers ---------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Touch Handlers', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'price-filter',
			    ),
			    array(
		            'name'              => esc_html__( 'Color', $themename ),
		            'id'                => 'et_shop_pf_circle_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select color for the price range filter slider handlers', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'circle_color',
		            'tab_id'			=> 'price-filter',
		        ),
		    //--------------------------------------------------------------------------------------------------------
		   	//------- Button ---------------------------------------------------------------------------------------------
				array(
		            'name'              => esc_html__( 'Button', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'price-filter',
			    ),
			    //-----------------------------------------------------------------------------------------------
			    array(
		            'name'              => esc_html__( 'Background Color', $themename ),
		            'id'                => 'et_shop_pf_button_bg_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Pick a background color for the price button', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'button_bg_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //-----------------------------------------------------------------------------------------------
			    array(
		            'name'              => esc_html__( 'Border Color', $themename ),
		            'id'                => 'et_shop_pf_button_border_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Pick a border color for the price button', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'button_border_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //-----------------------------------------------------------------------------------------------
			    array(
		            'name'              => esc_html__( 'Text Color', $themename ),
		            'id'                => 'et_shop_pf_button_font_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Font Color', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'button_font_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //-----------------------------------------------------------------------------------------------
			    array(
		            'name'              => esc_html__( 'Hover Text Color', $themename ),
		            'id'                => 'et_shop_pf_button_hover_font_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Hover Font Color', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'button_hover_font_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //-----------------------------------------------------------------------------------------------
			    array(
		            'name'              => esc_html__( 'Hover Background Color', $themename ),
		            'id'                => 'et_shop_pf_button_hover_bg_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Hover Background Color', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'button_hover_bg_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //-----------------------------------------------------------------------------------------------
			    array(
		            'name'              => esc_html__( 'Hover Border Color', $themename ),
		            'id'                => 'et_shop_pf_button_hover_border_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Hover Border Color', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'button_hover_border_color',
		            'tab_id'			=> 'price-filter',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Border Radius', $themename ),
		            'id'                => 'et_shop_pf_button_border_radius',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Border radius for price range filter button', $themename ),
		            'main_setting_name' => 'et_shop_pf',
		            'sub_setting_name'  => 'button_border_radius',
		            'tab_id'			=> 'price-filter',
		        ),
			//--------------------------------------------------------------------------------------------------------
		

		// Buttons
		   
		    array(
				'name'              => esc_html__( 'Use Custom Styles', $themename ),
				'id'                => 'et_shop_buttons_custom',
				'std'               => '',
				'type'              => 'checkbox',
				'desc'              =>  __( 'Enables custom styles settings for the Shop Buttons.', $themename ),
				'main_setting_name' => 'et_shop_buttons',
				'sub_setting_name'  => 'custom',
				'tab_id'			=> 'buttons',
			),
		    //------- Text -------------------------------------------------------------------------------------------
		     	array(
		            'name'              => esc_html__( 'Text', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'buttons',
			    ),

		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'            => esc_html__( 'Font', $themename ),
		            'id'              => 'et_shop_buttons_font',
		            'std'             => '',
		            'type'            => 'font-select',
		            'desc'            => __( 'Font type for the Shop buttons', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'font',
		            'tab_id'			=> 'buttons',                 
		        ),
		        
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Size', $themename ),
		            'id'                => 'et_shop_buttons_font_size',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Size in pixels for the text', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'font_size',
		            'tab_id'			=> 'buttons',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_buttons_font_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select font color for buttons', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'font_color',
		            'tab_id'			=> 'buttons',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Background Color', $themename ),
		            'id'                => 'et_shop_buttons_bg_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select background color for buttons', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'bg_color',
		            'tab_id'			=> 'buttons',
		        ),
		        //---------------------------------------------------------------------------------------------------
		        array(
		            'name'              => esc_html__( 'Border Color', $themename ),
		            'id'                => 'et_shop_buttons_border_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Border Color', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'border_color',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Border Width', $themename ),
		            'id'                => 'et_shop_buttons_border_width',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Border width for buttons', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'border_width',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Border Radius', $themename ),
		            'id'                => 'et_shop_buttons_border_radius',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Border radius for buttons', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'border_radius',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Letter Spacing', $themename ),
		            'id'                => 'et_shop_buttons_letter_spacing',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Letter spacing for buttons text', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'letter_spacing',
		            'tab_id'			=> 'buttons',
		        ),
		    //--------------------------------------------------------------------------------------------------------
			//------- Hover -------------------------------------------------------------------------------------------
		     	array(
		            'name'              => esc_html__( 'Hover', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'buttons',
			    ),
			    array(
		            'name'              => esc_html__( 'Font Color', $themename ),
		            'id'                => 'et_shop_buttons_hover_font_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Hover Font Color', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'hover_font_color',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Background Color', $themename ),
		            'id'                => 'et_shop_buttons_hover_bg_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Hover Background Color', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'hover_bg_color',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Border Color', $themename ),
		            'id'                => 'et_shop_buttons_hover_border_color',
		            'std'               => '',
		            'type'              => 'color',
		            'desc'              => __( 'Select Button Hover Border Color', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'hover_border_color',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Border Width', $themename ),
		            'id'                => 'et_shop_buttons_hover_border_width',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Button Hover Border Width', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'hover_border_width',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Border Radius', $themename ),
		            'id'                => 'et_shop_buttons_hover_border_radius',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Button Hover Border Radius', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'hover_border_radius',
		            'tab_id'			=> 'buttons',
		        ),
		        array(
		            'name'              => esc_html__( 'Letter Spacing', $themename ),
		            'id'                => 'et_shop_buttons_hover_letter_spacing',
		            'std'               => '',
		            'type'              => 'range',
		            'max'				=> '60',
		            'min'				=> '0',
		            'step'				=> '1',
		            'desc'              => __( 'Space between letters for buttons on hover effect', $themename ),
		            'main_setting_name' => 'et_shop_buttons',
		            'sub_setting_name'  => 'hover_letter_spacing',
		            'tab_id'			=> 'buttons',
		        ),
		    //--------------------------------------------------------------------------------------------------------
			//------- Button Icon -------------------------------------------------------------------------------------------
		     	array(
		            'name'              => esc_html__( 'Button Icon', $themename ),
		            'type'              => 'label-header',
		            'tab_id'			=> 'buttons',
			    ),
			    array(
					'name'              => esc_html__( 'Show Icon', $themename ),
					'id'                => 'et_shop_buttons_icon_active',
					'std'               => '',
					'type'              => 'checkbox',
					'desc'              =>  __( 'Show Icon On Hover for Button.', $themename ),
					'main_setting_name' => 'et_shop_buttons',
					'sub_setting_name'  => 'icon_active',
					'tab_id'			=> 'buttons',
				),
		    //--------------------------------------------------------------------------------------------------------
		
		
		// Related Products

			// ------ Title ---------------------------------------------------------------------------------------------    
				    array(
				        'name'              => esc_html__( 'Title', $themename ),
				        'type'              => 'label-header',
				        'tab_id'			=> 'related-products',
				    ),
				    //---------------------------------------------------------------------------------------------------
				    array(
				        'name'              => esc_html__( 'Font Color', $themename ),
				        'id'                => 'et_shop_rp_title_color',
				        'std'               => '',
				        'type'              => 'color',
				        'desc'              => __( 'Select Title Font Color', $themename ),
				        'main_setting_name' => 'et_shop_rp',
				        'sub_setting_name'  => 'title_color',
				        'tab_id'			=> 'related-products',
				    ),
				    //---------------------------------------------------------------------------------------------------
				    array(
				        'name'            => esc_html__( 'Font', $themename ),
				        'id'              => 'et_shop_rp_title_font',
				        'std'             => '',
				        'type'            => 'font-select',
				        'desc'            => __( 'Select a font type for the title', $themename ),
				        'main_setting_name' => 'et_shop_rp',
				        'sub_setting_name'  => 'title_font',
				        'tab_id'			=> 'related-products',                 
				    ),
				    
				    //---------------------------------------------------------------------------------------------------
				    array(
				        'name'              => esc_html__( 'Font Size', $themename ),
				        'id'                => 'et_shop_rp_title_size',
				        'std'               => '',
				        'type'              => 'range',
				        'max'				=> '60',
				        'min'				=> '0',
				        'step'				=> '1',
				        'desc'              => __( 'Size in pixels for the title', $themename ),
				        'main_setting_name' => 'et_shop_rp',
				        'sub_setting_name'  => 'title_size',
				        'tab_id'			=> 'related-products',
				    ),
				    //---------------------------------------------------------------------------------------------------
				    array(
				        'name'              => esc_html__( 'Letter Spacing', $themename ),
				        'id'                => 'et_shop_rp_title_letter_spacing',
				        'type'              => 'range',
				        'max'				=> '60',
				        'min'				=> '0',
				        'step'				=> '1',
				        'desc'              => __( 'Space between characters on the title', $themename ),
				       	'main_setting_name' => 'et_shop_rp',
				        'sub_setting_name'  => 'title_letter_spacing',
				        'tab_id'			=> 'related-products',
				    ),
				    //---------------------------------------------------------------------------------------------------
				    array(
				        'name'            => esc_html__( 'Line Height', $themename ),
				        'id'              => 'et_shop_rp_title_line_height',
				        'std'             => '',
				        'type'            => 'range',
				        'max'				=> '60',
				        'min'				=> '0',
				        'step'				=> '1',
				        'desc'              => __( 'Height of the lines on the title', $themename ),
				        'main_setting_name' => 'et_shop_rp',
				        'sub_setting_name'  => 'title_line_height',
				        'tab_id'			=> 'related-products',
				    ),
				    array(
			            'name'              => esc_html__( 'Background Color', $themename ),
			            'id'                => 'et_shop_rp_title_bg_color',
			            'std'               => '',
			            'type'              => 'color',
			            'desc'              => __( 'Select Title Background Color', $themename ),
			            'main_setting_name' => 'et_shop_rp',
			            'sub_setting_name'  => 'title_bg_color',
			            'tab_id'			=> 'related-products',
			        ),
			//---------------------------------------------------------------------------------------------------
			//------- Sub Title -----------------------------------------------------------------------------------------
			    array(
			        'name'              => esc_html__( 'Subtitle', $themename ),
			        'type'              => 'label-header',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'              => esc_html__( 'Font Color', $themename ),
			        'id'                => 'et_shop_rp_subtitle_color',
			        'std'               => '',
			        'type'              => 'color',
			        'desc'              => __( 'Select Subtitle Font Color', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'subtitle_color',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Font', $themename ),
			        'id'              => 'et_shop_rp_subtitle_font',
			        'std'             => '',
			        'type'            => 'font-select',
			        'desc'            => __( 'Select a font type for the subtitle', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'subtitle_font',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Font Size', $themename ),
			        'id'              => 'et_shop_rp_subtitle_size',
			        'std'             => '',
			        'type'            => 'range',
			        'max'				=> '60',
			        'min'				=> '0',
			        'step'				=> '1',
			        'desc'            => __( 'Size in pixels for the subtitle', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'subtitle_size',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Letter Spacing', $themename ),
			        'id'              => 'et_shop_rp_subtitle_letter_spacing',
			        'std'             => '',
			        'type'            => 'range',
			        'max'				=> '60',
			        'min'				=> '0',
			        'step'				=> '1',
			        'desc'            => __( 'Space between characters on the subtitle', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'subtitle_letter_spacing',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Line Height', $themename ),
			        'id'              => 'et_shop_rp_subtitle_line_height',
			        'std'             => '',
			        'type'            => 'range',
			        'max'				=> '60',
			        'min'				=> '0',
			        'step'				=> '1',
			        'desc'            => __( 'Height of the lines on the subtitle', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'subtitle_line_height',
			        'tab_id'			=> 'related-products',
			    ),
			//---------------------------------------------------------------------------------------------------
			//------- Content -------------------------------------------------------------------------------------------
			    array(
			        'name'              => esc_html__( 'Content', $themename ),
			        'type'              => 'label-header',
			        'tab_id'			=> 'related-products',
			    ),
			    array(
			        'name'              => esc_html__( 'Font Color', $themename ),
			        'id'                => 'et_shop_rp_content_color',
			        'std'               => '',
			        'type'              => 'color',
			        'desc'              => __( 'Select Content Font Color', $themename ),
				    'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'content_color',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Font', $themename ),
			        'id'              => 'et_shop_rp_content_font',
			        'std'             => '',
			        'type'            => 'font-select',
			        'desc'            => __( 'Select a font type for the content', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'content_font',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Font Size', $themename ),
			        'id'              => 'et_shop_rp_content_size',
			        'std'             => '',
			        'type'            => 'range',
			        'max'				=> '60',
			        'min'				=> '0',
			        'step'				=> '1',
			        'desc'            => __( 'Size in pixels for the content', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'content_size',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Letter Spacing', $themename ),
			        'id'              => 'et_shop_rp_content_letter_spacing',
			        'std'             => '',
			        'type'            => 'range',
			        'max'				=> '60',
			        'min'				=> '0',
			        'step'				=> '1',
			        'desc'            => __( 'Space between characters on the content', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'content_letter_spacing',
			        'tab_id'			=> 'related-products',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'            => esc_html__( 'Line Height', $themename ),
			        'id'              => 'et_shop_rp_content_line_height',
			        'std'             => '',
			        'type'            => 'range',
			        'max'				=> '60',
			        'min'				=> '0',
			        'step'				=> '1',
			        'desc'            => __( 'Height of the lines on the content', $themename ),
			        'main_setting_name' => 'et_shop_rp',
			        'sub_setting_name'  => 'content_line_height',
			        'tab_id'			=> 'related-products',
			    ),
			//---------------------------------------------------------------------------------------------------
			//------- Review Stars -------------------------------------------------------------------------------------------
				array(
					'name'              => esc_html__( 'Review Stars', $themename ),
					'type'              => 'label-header',
					'tab_id'			=> 'related-products',
				),
				array(
					'name'              => esc_html__( 'Color', $themename ),
					'id'                => 'et_shop_rp_star_color',
					'std'               => '',
					'type'              => 'color',
					'desc'              => __( 'Select color for the Review Stars on the Woocommerce Related Products widget', $themename ),
					'main_setting_name' => 'et_shop_rp',
					'sub_setting_name'  => 'star_color',
					'tab_id'			=> 'related-products',
				),
			//---------------------------------------------------------------------------------------------------
		
			// ------ Mini Cart ---------------------------------------------------------------------------------------------
				/*array(
					'name'              => esc_html__( 'Activate Mini Cart', $themename ),
					'id'                => 'et_shop_mini_cart_active',
					'std'               => '',
					'type'              => 'checkbox',
					'desc'              =>  __( 'Enables custom mini cart for the Shop.', $themename ),
					'main_setting_name' => 'et_shop_mini_cart',
					'sub_setting_name'  => 'active',
					'tab_id'			=> 'mini-cart',
				),
				// ------ Icon ------------------------------------------------------------------------------------------------    
					array(
						'name'              => esc_html__( 'Icon', $themename ),
						'type'              => 'label-header',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Icon Color', $themename ),
						'id'                => 'et_shop_mini_cart_icon_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select mini cart icon color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'icon_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Icon Hover Color', $themename ),
						'id'                => 'et_shop_mini_cart_icon_hover_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select mini cart icon color on mouse hover', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'icon_hover_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Icon Size', $themename ),
						'id'                => 'et_shop_mini_cart_icon_size',
						'std'               => '',
						'type'              => 'range',
						'max'				=> '22',
						'min'				=> '10',
						'step'				=> '1',
						'desc'              => __( 'Size in pixels for mini cart icon', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'icon_size',
						'tab_id'			=> 'mini-cart',
					),
				//----------------------------------------------------------------------------------------------------------
				// ------ Background ---------------------------------------------------------------------------------------------         
					array(
						'name'              => esc_html__( 'Background', $themename ),
						'type'              => 'label-header',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Background Color', $themename ),
						'id'                => 'et_shop_mini_cart_bg_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select Background Color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'bg_color',
						'tab_id'			=> 'mini-cart',
					),
				// ---------------------------------------------------------------------------------------------------------------    
				// ------ Product Title ------------------------------------------------------------------------------------------    
					array(
						'name'              => esc_html__( 'Product Title', $themename ),
						'type'              => 'label-header',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Color', $themename ),
						'id'                => 'et_shop_mini_cart_title_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select Title Font Color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'title_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'            => esc_html__( 'Font', $themename ),
						'id'              => 'et_shop_mini_cart_title_font',
						'std'             => '',
						'type'            => 'font-select',
						'desc'            => __( 'Select a font type for the title', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'title_font',
						'tab_id'			=> 'mini-cart',                 
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Size', $themename ),
						'id'                => 'et_shop_mini_cart_title_size',
						'std'               => '',
						'type'              => 'range',
						'max'				=> '32',
						'min'				=> '1',
						'step'				=> '1',
						'desc'              => __( 'Size in pixels for the title', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'title_size',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Letter Spacing', $themename ),
						'id'                => 'et_shop_mini_cart_title_letter_spacing',
						'type'              => 'range',
						'max'				=> '10',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Space between characters on the title', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'title_letter_spacing',
						'tab_id'			=> 'mini-cart',
					),
				//----------------------------------------------------------------------------------------------------------------
				// ---------------------------------------------------------------------------------------------------------------       
				// ------ Price Font ---------------------------------------------------------------------------------------------    
					array(
						'name'              => esc_html__( 'Price Font', $themename ),
						'type'              => 'label-header',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Color', $themename ),
						'id'                => 'et_shop_mini_cart_price_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select price font color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'price_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'            => esc_html__( 'Font', $themename ),
						'id'              => 'et_shop_mini_cart_price_font',
						'std'             => '',
						'type'            => 'font-select',
						'desc'            => __( 'Select a font type for the price', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'price_font',
						'tab_id'			=> 'mini-cart',                 
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Size', $themename ),
						'id'                => 'et_shop_mini_cart_price_size',
						'std'               => '',
						'type'              => 'range',
						'max'				=> '22',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Size in pixels for the price', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'price_size',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Letter Spacing', $themename ),
						'id'                => 'et_shop_mini_cart_price_letter_spacing',
						'type'              => 'range',
						'max'				=> '10',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Space between characters on the price', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'price_letter_spacing',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
				// ---------------------------------------------------------------------------------------------------------------       
				// ------ Subtotal Font ------------------------------------------------------------------------------------------    
					array(
						'name'              => esc_html__( 'Subtotal Font', $themename ),
						'type'              => 'label-header',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Color', $themename ),
						'id'                => 'et_shop_mini_cart_subtotal_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select subtotal font color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'subtotal_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'            => esc_html__( 'Font', $themename ),
						'id'              => 'et_shop_mini_cart_subtotal_font',
						'std'             => '',
						'type'            => 'font-select',
						'desc'            => __( 'Select a font type for the subtotal', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'subtotal_font',
						'tab_id'			=> 'mini-cart',                 
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Size', $themename ),
						'id'                => 'et_shop_mini_cart_subtotal_size',
						'std'               => '',
						'type'              => 'range',
						'max'				=> '22',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Size in pixels for the subtotal', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'subtotal_size',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Letter Spacing', $themename ),
						'id'                => 'et_shop_mini_cart_subtotal_letter_spacing',
						'type'              => 'range',
						'max'				=> '10',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Space between characters on the subtotal', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'subtotal_letter_spacing',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
				// ---------------------------------------------------------------------------------------------------------------       
				// ------ Buttons ------------------------------------------------------------------------------------------------    
					array(
						'name'              => esc_html__( 'Buttons', $themename ),
						'type'              => 'label-header',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Background Color', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_bg_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select buttons background color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_bg_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Hover Background Color', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_bg_hover_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select buttons background color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_bg_hover_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Color', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_text_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select buttons font color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_text_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Hover Font Color', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_text_hover_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select buttons font color on mouse hover', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_text_hover_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'            => esc_html__( 'Font', $themename ),
						'id'              => 'et_shop_mini_cart_buttons_text_font',
						'std'             => '',
						'type'            => 'font-select',
						'desc'            => __( 'Select a font type for buttons', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_text_font',
						'tab_id'			=> 'mini-cart',                 
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Font Size', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_text_size',
						'std'               => '',
						'type'              => 'range',
						'max'				=> '22',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Size in pixels for buttons', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_text_size',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Letter Spacing', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_text_letter_spacing',
						'type'              => 'range',
						'max'				=> '10',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Space between characters on buttons', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_text_letter_spacing',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Border Color', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_border_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select buttons border color', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_border_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Hover Border Color', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_border_hover_color',
						'std'               => '',
						'type'              => 'color',
						'desc'              => __( 'Select buttons border color on mouse hover', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_border_hover_color',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Border Size', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_border_size',
						'type'              => 'range',
						'max'				=> '5',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Size in pixels for buttons border size', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_border_size',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
					array(
						'name'              => esc_html__( 'Border Radius', $themename ),
						'id'                => 'et_shop_mini_cart_buttons_border_radius',
						'type'              => 'range',
						'max'				=> '5',
						'min'				=> '0',
						'step'				=> '1',
						'desc'              => __( 'Size in pixels for buttons border radius', $themename ),
						'main_setting_name' => 'et_shop_mini_cart',
						'sub_setting_name'  => 'buttons_border_radius',
						'tab_id'			=> 'mini-cart',
					),
					//---------------------------------------------------------------------------------------------------
				array(
						'name'              => esc_html__( 'Scroll', $themename ),
						'type'              => 'label-header',
						'tab_id'			=> 'mini-cart',
				),
				array(
			        'name'              => esc_html__( 'Theme', $themename ),
			        'type'              => 'select',
			        'id'				=> 'et_shop_mini_cart_theme',
			        'main_setting_name'	=> 'et_shop_mini_cart',
			        'sub_setting_name'	=> 'theme',
			        'options'			=> array('Light','Dark','Dotted'),
			        'desc'              => __( 'Select theme color for minicart scroll bar', $themename ),
			        'tab_id'			=> 'mini-cart',
			    ),
				*/
				// ---------------------------------------------------------------------------------------------------------------       
			// ------------------------------------------------------------------------------------------------------------------       

			// Other
			// ------ Sale Badge  ---------------------------------------------------------------------------------------------    
			    array(
			        'name'              => esc_html__( 'Sale Badge', $themename ),
			        'type'              => 'label-header',
			        'tab_id'			=> 'other',
			    ),
			    array(
			        'name'              => esc_html__( 'Orientation', $themename ),
			        'type'              => 'select',
			        'id'				=> 'et_shop_other_onsale',
			        'main_setting_name'	=> 'et_shop_other',
			        'sub_setting_name'	=> 'onsale',
			        'options'			=> array('Default','Diagonal','Left','Right'),
			        'desc'              => __( 'Select a cutom sale badge for products on sales', $themename ),
			        'tab_id'			=> 'other',
			    ),
			    //---------------------------------------------------------------------------------------------------
			    array(
			        'name'              => esc_html__( 'Background Color', $themename ),
			        'id'                => 'et_shop_other_onsale_bg_color',
			        'std'               => '',
			        'type'              => 'color',
			        'desc'              => __( 'Select color for sale badge on products', $themename ),
			        'main_setting_name' => 'et_shop_other',
			        'sub_setting_name'  => 'onsale_bg_color',
			        'tab_id'			=> 'other',
			    ),
				//---------------------------------------------------------------------------------------------------
			    array(
			        'name'              => esc_html__( 'Font Color', $themename ),
			        'id'                => 'et_shop_other_onsale_font_color',
			        'std'               => '',
			        'type'              => 'color',
			        'desc'              => __( 'Select Sale Badge Font Color', $themename ),
			        'main_setting_name' => 'et_shop_other',
			        'sub_setting_name'  => 'onsale_font_color',
			        'tab_id'			=> 'other',
			    ),
			    // ------ Product Image ---------------------------------------------------------------------------------------------    
				    array(
				        'name'              => esc_html__( 'Product Image', $themename ),
				        'type'              => 'label-header',
				        'tab_id'			=> 'other',
				    ),
				    array(
				        'name'              => esc_html__( 'Hover Effect', $themename ),
				        'type'              => 'select',
				        'id'				=> 'et_shop_other_product_img_hover',
				        'main_setting_name'	=> 'et_shop_other',
				        'sub_setting_name'	=> 'product_img_hover',
				        'options'			=> array('Default','Flip','Zoom'),
				        'desc'              => __( 'Select an effect for product image on mouse hover', $themename ),
				        'tab_id'			=> 'other',
				    ),

				

	);
	// Create plugin options if not exist
	function tm_create_options(){ 
		
		// Products Widget Style (color,font,letter_spacing,line_height,hover,stars)
			$et_shop_bs = array(
				'title_color'=>'','title_font'=>'','title_size'=>'16','title_letter_spacing'=>'0','title_line_height'=>'5','title_font_b'=>'true','title_font_i'=>'true','title_font_tt'=>'','title_font_u'=>'',

				'subtitle_color'=>'','subtitle_font'=>'','subtitle_size'=>'14','subtitle_letter_spacing'=>'0','subtitle_line_height'=>'5','subtitle_font_b'=>'normal','subtitle_font_i'=>'','subtitle_font_tt'=>'','subtitle_font_u'=>'',
				
				'content_color'=>'','content_font'=>'','content_size'=>'14','content_letter_spacing'=>'0','content_line_height'=>'5','content_font_b'=>'normal','content_font_i'=>'','content_font_tt'=>'','content_font_u'=>'',

				'hover_effect'=>'on','hover_border_color'=>'#008bdb', 'hover_border_width'=>'1px',

				'separator'=>'on','separator_color'=>'#e2e2e2'
			);
			if(!get_option('et_shop_bs')) add_option('et_shop_bs',$et_shop_bs);

		// Recent Reviews Widget Style (color,font,letter_spacing,line_height,hover,stars)
			$et_shop_rr = array(
				'title_color'=>'','title_font'=>'','title_size'=>'16','title_letter_spacing'=>'0','title_line_height'=>'5','title_font_b'=>'','title_font_i'=>'','title_font_tt'=>'','title_font_u'=>'',

				'subtitle_color'=>'','subtitle_font'=>'','subtitle_size'=>'14','subtitle_letter_spacing'=>'0','subtitle_line_height'=>'11','subtitle_font_b'=>'normal','subtitle_font_i'=>'','subtitle_font_tt'=>'','subtitle_font_u'=>'',
				
				'content_color'=>'','content_font'=>'','content_size'=>'14','content_letter_spacing'=>'0','content_line_height'=>'4','content_font_b'=>'normal','content_font_i'=>'','content_font_tt'=>'','content_font_u'=>'',

				'hover_effect'=>'on','hover_border_color'=>'#008bdb', 'hover_border_width'=>'1px',

				'separator'=>'on','separator_color'=>'#e2e2e2',

				'star_color'=>'', 
			);
			if(!get_option('et_shop_rr')) add_option('et_shop_rr',$et_shop_rr);

		// Top Rated Widget Style (color,font,letter_spacing,line_height,hover,stars)
			$et_shop_tr = array(
				'title_color'=>'','title_font'=>'','title_size'=>'16','title_letter_spacing'=>'0','title_line_height'=>'5','title_font_b'=>'','title_font_i'=>'','title_font_tt'=>'','title_font_u'=>'',

				'subtitle_color'=>'','subtitle_font'=>'','subtitle_size'=>'14','subtitle_letter_spacing'=>'0','subtitle_line_height'=>'4','subtitle_font_b'=>'none','subtitle_font_i'=>'','subtitle_font_tt'=>'','subtitle_font_u'=>'',
				
				'content_color'=>'','content_font'=>'','content_size'=>'14','content_letter_spacing'=>'0','content_line_height'=>'4','content_font_b'=>'','content_font_i'=>'','content_font_tt'=>'','content_font_u'=>'',

				'hover_effect'=>'on','hover_border_color'=>'#008bdb', 'hover_border_width'=>'1px',

				'star_color'=>'', 

				'separator'=>'on','separator_color'=>'#e2e2e2'
			);
			if(!get_option('et_shop_tr')) add_option('et_shop_tr',$et_shop_tr);

		// Search Style
			$et_shop_search = array(
				'active'=>'on',

				'title_color'=>'','title_font'=>'','title_size'=>'14','title_letter_spacing'=>'0','title_line_height'=>'5','title_font_b'=>'','title_font_i'=>'','title_font_tt'=>'','title_font_u'=>'',
				
				'custom'=>'on',
				'input_font_color'=>'','input_font'=>'','input_font_b'=>'','input_font_i'=>'','input_font_tt'=>'','input_font_u'=>'',
				'input_font_size'=>'14','input_letter_spacing'=>'0','input_line_height'=>'5', 'input_bg_color'=>'',

				'button_custom_style'=>'on',

				'button_icon_color'=>'', 'button_bg_color'=>'',

				'separator'=>'on','separator_color'=>'#e2e2e2',
				
				'bar_border_color'=>'', 'bar_border_radius'=>'0', 'bar_border_width'=>'0',
			);
			if(!get_option('et_shop_search')) add_option('et_shop_search',$et_shop_search);
		
		// Buttons Style	
			$et_shop_buttons = array(
				'custom'=>'on',

				'font_size'=>'16','font_color'=>'','font'=>'','letter_spacing'=>'0',
				'font_b'=>'','font_i'=>'','font_tt'=>'','font_u'=>'',
				'bg_color'=>'',

				'border_width'=>'1','border_color'=>'','border_radius'=>'4',

				'icon_active'=>'on','icon_code'=>'','icon_color'=>'','icon_placement'=>'','icon_only_hover'=>'',

				'hover_font_color'=>'','hover_letter_spacing'=>'0',
				'hover_bg_color'=>'','hover_border_color'=>'','hover_border_radius'=>'4',
				'hover_border_width'=>'1'
			);
			if(!get_option('et_shop_buttons')) add_option('et_shop_buttons',$et_shop_buttons);

		// Price Filter Style
			$et_shop_pf = array(
				'text_color'=>'', 'font'=>'', 'font_size'=>'14', 
				'font_b'=>'', 'font_i'=>'', 'font_tt'=>'', 'font_u'=>'',
				'button_bg_color'=>'','button_border_color'=>'','button_border_radius'=>'4',
				'button_font_color'=>'','button_hover_font_color'=>'',
				'line_color'=>'','line_bg_color'=>'','circle_color'=>'',
				'button_hover_bg_color'=>'','button_hover_border_color'=>''
			);
			if(!get_option('et_shop_pf')) add_option('et_shop_pf',$et_shop_pf);

		// Related Products Style (color,font,letter_spacing,line_height,bg_color,stars)
			$et_shop_rp = array(
				'title_color'=>'#ffffff','title_font'=>'','title_size'=>'20','title_letter_spacing'=>'0','title_line_height'=>'25','title_font_b'=>'','title_font_i'=>'','title_font_tt'=>'','title_font_u'=>'', 'title_bg_color'=>'#2ea3f2',

				'subtitle_color'=>'','subtitle_font'=>'','subtitle_size'=>'16','subtitle_letter_spacing'=>'0','subtitle_line_height'=>'5','subtitle_font_b'=>'none','subtitle_font_i'=>'','subtitle_font_tt'=>'','subtitle_font_u'=>'',
				
				'content_color'=>'','content_font'=>'','content_size'=>'16','content_letter_spacing'=>'0','content_line_height'=>'5','content_font_b'=>'','content_font_i'=>'','content_font_tt'=>'','content_font_u'=>'',

				'star_color'=>''	
			);
			if(!get_option('et_shop_rp')) add_option('et_shop_rp',$et_shop_rp);

		// Other Options
			$et_shop_other = array(
				'onsale'=>'Default', 'onsale_bg_color'=>'', 'onsale_font_color'=>'',
				'product_img_hover'=>'', 'custom_woo_templates'=>'off'
			);
			if(!get_option('et_shop_other')) add_option('et_shop_other',$et_shop_other);

		// Mini-cart
			
			$et_shop_mini_cart = array(
				'active'=>'off',

				'bg_color'=>'',

				'icon_color'=>'', 'icon_hover_color'=>'', 'icon_size'=>'16',
				
				'title_color'=>'','title_font'=>'','title_size'=>'14','title_letter_spacing'=>'0','title_font_b'=>'','title_font_i'=>'','title_font_tt'=>'','title_font_u'=>'',

				'price_color'=>'','price_font'=>'','price_size'=>'12','price_letter_spacing'=>'0','price_font_b'=>'','price_font_i'=>'','price_font_tt'=>'','price_font_u'=>'',

				'subtotal_color'=>'','subtotal_font'=>'','subtotal_size'=>'22','subtotal_letter_spacing'=>'0','subtotal_font_b'=>'','subtotal_font_i'=>'','subtotal_font_tt'=>'','subtotal_font_u'=>'',
			
				'buttons_text_color'=>'','buttons_text_font'=>'','buttons_text_size'=>'22','buttons_text_letter_spacing'=>'0','buttons_text_font_b'=>'','buttons_text_font_i'=>'','buttons_text_font_tt'=>'','buttons_text_font_u'=>'',
				'buttons_text_hover_color'=>'',
				
				'buttons_border_size'=>'1','buttons_border_radius'=>'4','buttons_border_color'=>'',
				'buttons_border_hover_color'=>'',
				
				'buttons_bg_color'=>'#fff',
				'buttons_bg_hover_color'=>'',

				'theme'=>'Light',
			);
			if(!get_option('et_shop_mini_cart')) add_option('et_shop_mini_cart',$et_shop_mini_cart);
	}
	tm_create_options();
	// get utils
	include_once(TM_PLUGIN_DIR . 'epanel/utils.php');


?>