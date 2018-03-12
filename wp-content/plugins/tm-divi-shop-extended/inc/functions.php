<?php
	

	// Dynamic css styles
	function tm_custom_css() {
	
		// Get Options
		$et_shop_bs = get_option('et_shop_bs');
		$et_shop_tr = get_option('et_shop_tr');
		$et_shop_search = get_option('et_shop_search');
		$et_shop_buttons = get_option('et_shop_buttons');
		$et_shop_pf = get_option('et_shop_pf');
		$et_shop_rr = get_option('et_shop_rr');
		$et_shop_rp = get_option('et_shop_rp');
		$et_shop_other = get_option('et_shop_other');
		
		?>
		<style>
			/* Import Fonts*/
				<?php 
					$fonts_to_import = array(
						$et_shop_bs['title_font'], $et_shop_bs['subtitle_font'], $et_shop_bs['content_font'],
						$et_shop_rr['title_font'], $et_shop_rr['subtitle_font'], $et_shop_rr['content_font'],
						$et_shop_tr['title_font'], $et_shop_tr['subtitle_font'], $et_shop_tr['content_font'],
						$et_shop_rp['title_font'], $et_shop_rp['subtitle_font'], $et_shop_rp['content_font'],
						$et_shop_search['title_font'],$et_shop_search['input_font'], $et_shop_buttons['font']
						);
					foreach ($fonts_to_import as $font) {
    					if($font != '' && ($font != 'Default')){
							echo '@import url(\'https://fonts.googleapis.com/css?family='.$font.'\');';
						}
					}
				?>
				@import url('https://fonts.googleapis.com/css?family=Roboto');

			/* - - - - - - - - - - - - -Widgets - - - - - - - - - - - - - - - - - - - - - - - - - - */
			/* Widgets Title */
				.woocommerce ul.cart_list li img, .woocommerce ul.product_list_widget li img{
					float: left !important;
					width: 60px !important;
					margin-right: 15px !important;
					margin-left: -14px !important;
				}
				
				
				/*Products Separator*/
				<?php if($et_shop_bs['separator'] == 'on'){?>
					.widget_products li{
						border-bottom:1px solid <?php echo $et_shop_bs['separator_color'];?>;
					}
					.widget_products li:last-child{
						border-bottom: none;
					}
					.widget_products h4:after{
					    content: '';
					    display: block;
					    margin-top: 15px;
					    margin-bottom: -5px;
					    width: 40px;
					    border-bottom: 1px solid <?php echo $et_shop_bs['separator_color'];?>;
					}
				<?php }?>

				/* Search Separator*/
				<?php if($et_shop_search['separator'] == 'on'){?>
					.widget_product_search h4:after{
					    content: '';
					    display: block;
					    margin-top: 15px;
					    margin-bottom: 2px;
					    width: 40px;
					    border-bottom: 1px solid <?php echo $et_shop_search['separator_color'];?>;
					}
				<?php }?>

				/*Recent Reviews Separator*/
				<?php if($et_shop_rr['separator'] == 'on'){?>
					.widget_recent_reviews li{
						border-bottom:1px solid <?php echo $et_shop_rr['separator_color'];?>;
					}
					.widget_recent_reviews li:last-child{
						border-bottom: none;
					}
					.widget_recent_reviews h4:after{
					    content: '';
					    display: block;
					    margin-top: 15px;
					    margin-bottom: -5px;
					    width: 40px;
					    border-bottom: 1px solid <?php echo $et_shop_rr['separator_color'];?>;
					}
				<?php }?>

				/*Top Rated Separator*/
				<?php if($et_shop_tr['separator'] == 'on'){?>
					.widget_top_rated_products li{
						border-bottom:1px solid <?php echo $et_shop_tr['separator_color'];?>;
					}
					.widget_top_rated_products li:last-child{
						border-bottom: none;
					}
					.widget_top_rated_products h4:after{
					    content: '';
					    display: block;
					    margin-top: 15px;
					    margin-bottom: -5px;
					    width: 40px;
					    border-bottom: 1px solid <?php echo $et_shop_tr['separator_color'];?>;
					}
				<?php }?>

				/* Products Title*/
				.widget_products h4{
					color: <?php echo $et_shop_bs['title_color'];?> !important;
					letter-spacing: <?php echo $et_shop_bs['title_letter_spacing'];?>px;
					font-size: <?php echo $et_shop_bs['title_size'];?>px !important;
					line-height: <?php echo $et_shop_bs['title_line_height'];?>px;
					font-family: <?php echo $et_shop_bs['title_font'];?>;
					font-weight: <?php if($et_shop_bs['title_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_bs['title_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_bs['title_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_bs['title_font_u']=='true') echo 'underline';?>;
				}
				/* Search Title*/
				.widget_product_search h4{
					color: <?php echo $et_shop_search['title_color'];?> !important;
					letter-spacing: <?php echo $et_shop_search['title_letter_spacing'];?>px;
					font-size: <?php echo $et_shop_search['title_size'];?>px !important;
					line-height: <?php echo $et_shop_search['title_line_height'];?>px;
					font-family: <?php echo $et_shop_search['title_font'];?>;
					font-weight: <?php if($et_shop_search['title_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_search['title_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_search['title_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_search['title_font_u']=='true') echo 'underline';?>;
				}

				/* Recent Reviews Title*/
				.widget_recent_reviews h4{
					color: <?php echo $et_shop_rr['title_color'];?> !important;
					letter-spacing: <?php echo $et_shop_rr['title_letter_spacing'];?>px;
					font-size: <?php echo $et_shop_rr['title_size'];?>px !important;
					line-height: <?php echo $et_shop_rr['title_line_height'];?>px;
					font-family: <?php echo $et_shop_rr['title_font'];?>;
					font-weight: <?php if($et_shop_rr['title_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_rr['title_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_rr['title_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_rr['title_font_u']=='true') echo 'underline';?>;
				}
				/* Top Rated Title*/
				.widget_top_rated_products h4{
					color: <?php echo $et_shop_tr['title_color'];?> !important;
					letter-spacing: <?php echo $et_shop_tr['title_letter_spacing'];?>px;
					font-size: <?php echo $et_shop_tr['title_size'];?>px !important;
					line-height: <?php echo $et_shop_tr['title_line_height'];?>px;
					font-family: <?php echo $et_shop_tr['title_font'];?>;
					font-weight: <?php if($et_shop_tr['title_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_tr['title_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_tr['title_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_tr['title_font_u']=='true') echo 'underline';?>;
				}
				/* Related Products Title*/
				section.related.products > h2{
					color: <?php echo $et_shop_rp['title_color'];?> !important;
					letter-spacing: <?php echo $et_shop_rp['title_letter_spacing'];?>px;
					font-size: <?php echo $et_shop_rp['title_size'];?>px !important;
					line-height: <?php echo $et_shop_rp['title_line_height'];?>px;
					font-family: <?php echo $et_shop_rp['title_font'];?>;
					font-weight: <?php if($et_shop_rp['title_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_rp['title_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_rp['title_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_rp['title_font_u']=='true') echo 'underline';?>;
					background-color: <?php echo $et_shop_rp['title_bg_color'];?>;
					
					padding-bottom: 16px;
					min-width: 300px;
					width: 30%;
					text-align: center;
					padding: 5px;
					margin-bottom: 10px;
				}
				

			/* Products Subtitle */
				.widget_products li .product-title{
					color: <?php echo $et_shop_bs['subtitle_color'];?> !important;
					font-family: <?php echo $et_shop_bs['subtitle_font'];?>;
					font-size: <?php echo $et_shop_bs['subtitle_size'];?>px !important;
					letter-spacing: <?php echo $et_shop_bs['subtitle_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_bs['subtitle_line_height'];?>px;
					font-weight: <?php if($et_shop_bs['subtitle_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_bs['subtitle_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_bs['subtitle_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_bs['subtitle_font_u']=='true') echo 'underline';?>;

					margin-bottom:5px;
				}
			/* Recent Reviews Subtitle */
				.widget_recent_reviews li a{
					color: <?php echo $et_shop_rr['subtitle_color'];?> !important;
					font-family: <?php echo $et_shop_rr['subtitle_font'];?>;
					font-size: <?php echo $et_shop_rr['subtitle_size'];?>px !important;
					letter-spacing: <?php echo $et_shop_rr['subtitle_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_rr['subtitle_line_height'];?>px;
					font-weight: <?php if($et_shop_rr['subtitle_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_rr['subtitle_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_rr['subtitle_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_rr['subtitle_font_u']=='true') echo 'underline';?>;

					margin-bottom:8px;
				}

			/* Top Rated Subtitle */
				.widget_top_rated_products li .product-title{
					color: <?php echo $et_shop_tr['subtitle_color'];?> !important;
					font-family: <?php echo $et_shop_tr['subtitle_font'];?>;
					font-size: <?php echo $et_shop_tr['subtitle_size'];?>px !important;
					letter-spacing: <?php echo $et_shop_tr['subtitle_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_tr['subtitle_line_height'];?>px;
					font-weight: <?php if($et_shop_tr['subtitle_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_tr['subtitle_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_tr['subtitle_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_tr['subtitle_font_u']=='true') echo 'underline';?>;

					margin-bottom:5px;
				}

			/* Related Products Subtitle */
				section.related.products ul.products li.product h2{
					color: <?php echo $et_shop_rp['subtitle_color'];?> !important;
					font-family: <?php echo $et_shop_rp['subtitle_font'];?> !important;
					font-size: <?php echo $et_shop_rp['subtitle_size'];?>px !important;
					letter-spacing: <?php echo $et_shop_rp['subtitle_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_rp['subtitle_line_height'];?>px;
					font-weight: <?php if($et_shop_rp['subtitle_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_rp['subtitle_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_rp['subtitle_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_rp['subtitle_font_u']=='true') echo 'underline';?>;

				}

			/* Products Content */
				.widget_products li .woocommerce-Price-amount, .widget_products li .woocommerce-Price-amount{
					color: <?php echo $et_shop_bs['content_color'];?> !important;
					font-family: <?php echo $et_shop_bs['content_font'];?> !important;
					font-size: <?php echo $et_shop_bs['content_size'];?>px;
					letter-spacing: <?php echo $et_shop_bs['content_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_bs['content_line_height'];?>px;
					font-weight: <?php if($et_shop_bs['content_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_bs['content_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_bs['content_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_bs['content_font_u']=='true') echo 'underline';?>;
					
				}
			/* Recent Reviews Content */
				.widget_recent_reviews li .reviewer{
					color: <?php echo $et_shop_rr['content_color'];?> !important;
					font-family: <?php echo $et_shop_rr['content_font'];?> !important;
					font-size: <?php echo $et_shop_rr['content_size'];?>px;
					letter-spacing: <?php echo $et_shop_rr['content_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_rr['content_line_height'];?>px;
					font-weight: <?php if($et_shop_rr['content_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_rr['content_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_rr['content_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_rr['content_font_u']=='true') echo 'underline';?>;
					
				}

			/* Top Rated Content */
				.widget_top_rated_products li .woocommerce-Price-amount, .widget_top_rated_products li .woocommerce-Price-amount{
					color: <?php echo $et_shop_tr['content_color'];?> !important;
					font-family: <?php echo $et_shop_tr['content_font'];?> !important;
					font-size: <?php echo $et_shop_tr['content_size'];?>px;
					letter-spacing: <?php echo $et_shop_tr['content_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_tr['content_line_height'];?>px;
					font-weight: <?php if($et_shop_tr['content_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_tr['content_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_tr['content_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_tr['content_font_u']=='true') echo 'underline';?>;
					
				}
			
			/* Related Products Content */
				section.related.products ul li span.woocommerce-Price-amount.amount{
					color: <?php echo $et_shop_rp['content_color'];?> !important;
					font-family: <?php echo $et_shop_rp['content_font'];?> !important;
					font-size: <?php echo $et_shop_rp['content_size'];?>px;
					letter-spacing: <?php echo $et_shop_rp['content_letter_spacing'];?>px;
					line-height: <?php echo $et_shop_rp['content_line_height'];?>px;
					font-weight: <?php if($et_shop_rp['content_font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_rp['content_font_i']=='true') echo 'italic';?>;
					text-transform: <?php if($et_shop_rp['content_font_tt']=='true') echo 'uppercase';?>;
					text-decoration: <?php if($et_shop_rp['content_font_u']=='true') echo 'underline';?>;
					
				}
			
			/* Top Rated Star Color */
				.widget_top_rated_products .star-rating span::before{
					color: <?php echo $et_shop_tr['star_color'];?> !important;
				}
				.widget_top_rated_products li .star-rating{
					font-size:12px;
				}
			/* Recent Reviews Star Color */
				.widget_recent_reviews .star-rating span::before{
					color: <?php echo $et_shop_rr['star_color'];?> !important;
				}
				.widget_top_rated_products li .star-rating{
					font-size:12px;
				}
			/* Related products Star Color */
				section.related.products ul li div.star-rating span::before{
					color: <?php echo $et_shop_rp['star_color'];?>!important;
				}

			/* Products IMG Hover */
				<?php if($et_shop_bs['hover_effect'] == 'on'){ ?>
					.widget_products li img:hover{
					border:2px solid <?php echo $et_shop_bs['hover_border_color']; ?> !important;
					border-radius: 2px;
					
					}

				<?php } ?>
			/* Recent Reviews IMG Hover */
				<?php if($et_shop_rr['hover_effect'] == 'on'){ ?>
					.widget_recent_reviews li img:hover{
					border:2px solid <?php echo $et_shop_rr['hover_border_color']; ?> !important;
					border-radius: 2px !important;
					
					}

				<?php } ?>
			/* Top Rated IMG Hover */
				<?php if($et_shop_tr['hover_effect'] == 'on'){ ?>
					.widget_top_rated_products li img:hover{
					border:2px solid <?php echo $et_shop_tr['hover_border_color']; ?> !important;
					border-radius: 2px !important;
					
					}

				<?php } ?>
			
			/* Widget Bullets hide */
				#footer-widgets .footer-widget li:before{
					display:none;
				}

			/* SEARCH */
				.widget_product_search{
					<?php if(!($et_shop_search['active']=='on')) echo 'display:none;';?>
				}

				<?php if($et_shop_search['custom'] == 'on') { ?>  
					.widget_product_search input.search-field{
						font-family: <?php echo $et_shop_search['input_font'];?>;
						font-size: <?php echo $et_shop_search['input_font_size'];?>px !important;
						letter-spacing: <?php echo $et_shop_search['input_letter_spacing'];?>px;
						line-height: <?php echo $et_shop_search['input_line_height'];?>px;
						font-weight: <?php if($et_shop_search['input_font_b']=='true') echo 'bold';?>;
						font-style: <?php if($et_shop_search['input_font_i']=='true') echo 'italic';?>;
						text-decoration: <?php if($et_shop_search['input_font_u']=='true') echo 'underline';?>;
						text-transform: <?php if($et_shop_search['input_font_tt']=='true') echo 'uppercase';?>;
						background-color: <?php echo $et_shop_search['input_bg_color'];?>;
						color: <?php echo $et_shop_search['input_font_color'];?>;
						padding-left:5px !important;
					}
					/*Search input placeholde*/
					::-webkit-input-placeholder { /* Chrome/Opera/Safari */
						color: <?php echo $et_shop_search['input_font_color'];?> !important;
					}
					::-moz-placeholder { /* Firefox 19+ */
						color: <?php echo $et_shop_search['input_font_color'];?> !important;
					}
					:-ms-input-placeholder { /* IE 10+ */
						color: <?php echo $et_shop_search['input_font_color'];?> !important;
					}
					:-moz-placeholder { /* Firefox 18- */
						color: <?php echo $et_shop_search['input_font_color'];?> !important;
					}
						
					
				<?php } ?>
				
				/* Default Color*/
				.widget_product_search input[type=submit]{
					color:#444;
				}
				<?php if($et_shop_search['button_custom_style']=='on'){?>
					.widget_product_search input[type=submit]{
						color: <?php echo $et_shop_search['button_icon_color'];?> !important;
						background-color: <?php echo $et_shop_search['button_bg_color'];?> !important;
						
					}
				<?php } ?>
				/* Search bar styles*/
				.widget_product_search form.woocommerce-product-search{
					border-radius: <?php echo $et_shop_search['bar_border_radius'];?>px;
					border: <?php echo $et_shop_search['bar_border_width'];?>px solid <?php echo $et_shop_search['bar_border_color'];?>
				}
				

			/* BUTTONS */
			<?php if($et_shop_buttons['custom']=='on'){?>
			.button:not(.et-module-different).product_type_simple.add_to_cart_button.ajax_add_to_cart,.woocommerce .cart .button:not(.et-module-different),a.checkout-button.button:not(.et-module-different).alt.wc-forward,input#place_order.button:not(.et-module-different).alt,a.button:not(.et-module-different).product_type_variable.add_to_cart_button, #tm_contend .product .button, .woocommerce-message a.button.wc-forward,.woocommerce ul.products li.product a.product_type_external:not(.et-module-different), .woocommerce ul.products li.product a.product_type_variable:not(.et-module-different)
			{
				font-size: <?php echo $et_shop_buttons['font_size']; ?>px !important;
				color: <?php echo $et_shop_buttons['font_color']; ?> !important;
				font-family: <?php echo $et_shop_buttons['font']; ?> !important;
				letter-spacing: <?php echo $et_shop_buttons['letter_spacing']; ?>px !important;
				background-color: <?php echo $et_shop_buttons['bg_color']; ?>;
				border: <?php echo $et_shop_buttons['border_width']; ?>px solid <?php echo $et_shop_buttons['border_color']; ?> !important;
				border-radius: <?php echo $et_shop_buttons['border_radius']; ?>px !important;
				-webkit-border-radius: <?php echo $et_shop_buttons['border_radius']; ?>px !important;
				font-weight: <?php if($et_shop_buttons['font_b']=='true') echo 'bold';?>;
				font-style: <?php if($et_shop_buttons['font_i']=='true') echo 'italic';?>;
				text-decoration: <?php if($et_shop_buttons['font_u']=='true') echo 'underline';?>;
				text-transform: <?php if($et_shop_buttons['font_tt']=='true') echo 'uppercase';?>;
				-webkit-transition: all 0s ease-in-out;
				-moz-transition: all 0s ease-in-out;
				transition: all 0s ease-in-out;
			}
			.button:not(.et-module-different).product_type_simple.add_to_cart_button.ajax_add_to_cart:hover,a.checkout-button:not(.et-module-different).button.alt.wc-forward:hover,input#place_order.button.alt:hover,.woocommerce .cart .button:not(.et-module-different):hover,a.button.product_type_variable.add_to_cart_button:not(.et-module-different):hover,#tm_contend .product .button:hover, .woocommerce-message a.button.wc-forward:hover,.woocommerce ul.products li.product a.product_type_external:not(.et-module-different):hover, .woocommerce ul.products li.product a.product_type_variable:not(.et-module-different):hover
			{

				color: <?php echo $et_shop_buttons['hover_font_color']; ?> !important;
				background-color: <?php echo $et_shop_buttons['hover_bg_color']; ?>;
				border: <?php echo $et_shop_buttons['hover_border_width']; ?>px solid <?php echo $et_shop_buttons['hover_border_color']; ?> !important;
				border-radius: <?php echo $et_shop_buttons['hover_border_radius']; ?>px !important;
				-webkit-border-radius: <?php echo $et_shop_buttons['hover_border_radius']; ?>px !important;
				-webkit-transform: scale(1);
				-ms-transform: scale(1);
				transform: scale(1);
				

			}
			
			.woocommerce .quantity input.qty{
				font-size:<?php echo strval($et_shop_buttons['font_size']+5); ?>px !important;
				height: auto !important;
				min-height: 34px;
				padding: 0.3em 1em !important;
			}
			.woocommerce-cart table.cart td.actions .coupon .input-text{
				padding:<?php echo strval($et_shop_buttons['font_size']-7); ?>px 12px !important;
			}
			.woocommerce .cart .button:not(.et-module-different){
				margin-top:1px !important;
			}
			<?php } ?>
			<?php if($et_shop_buttons['icon_active']!='on')
					echo '.woocommerce a.button:not(.et-module-different):after, button:not(.et-module-different).single_add_to_cart_button:after{
								display:none;
								visibility:hidden;
							}
							.woocommerce a.button:not(.et-module-different):hover, .woocommerce-page a.button:not(.et-module-different):hover, .woocommerce div.product form.cart .button:not(.et-module-different):hover, .woocommerce .cart .button:not(.et-module-different):hover, .woocommerce .cart input.button:not(.et-module-different):hover, .woocommerce #payment #place_order:hover, .woocommerce-page #payment #place_order:hover,button:not(.et-module-different).single_add_to_cart_button:hover{
								/*padding:5px 10px !important;*/
								padding: 0.3em 1em !important;
							}';
			?>
			/*Buttons Icon Change*/
				.woocommerce a.button:not(.et-module-different):after, button:not(.et-module-different).single_add_to_cart_button:after{
					    
					    content: "\e07a" !important;
					    font-size:<?php echo $et_shop_buttons['font_size']; ?>px !important;
					    top: 29%;
					    left: 82%;
					    font-family: "ETmodules" !important;
					    font-weight: normal;
					    font-style: normal;
					    font-variant: normal;
					    -webkit-font-smoothing: antialiased;
					    -moz-osx-font-smoothing: grayscale;
					    line-height: 1;
					    text-transform: none;
					    speak: none;
					    margin-left: 3px;
				}
			/*Cupon Input font size*/
				.woocommerce-cart table.cart td.actions .coupon .input-text{
					font-size:<?php echo $et_shop_buttons['font_size']; ?>px !important;
					height:auto !important;
					margin-top: 1px;
				}
			/* Overlay effect color */
			.et_overlay:before{
				color:<?php echo $et_shop_buttons['bg_color']; ?> !important;
			}
			/*Price Filter*/
				.widget_price_filter h4{
					color: <?php echo $et_shop_pf['text_color']; ?>;
					font-family: <?php echo $et_shop_pf['font']; ?>;
					font-size: <?php echo $et_shop_pf['font_size']; ?>px !important;
					font-weight: <?php if($et_shop_pf['font_b']=='true') echo 'bold';?>;
					font-style: <?php if($et_shop_pf['font_i']=='true') echo 'italic';?>;
					text-decoration: <?php if($et_shop_pf['font_u']=='true') echo 'underline';?>;
					text-transform: <?php if($et_shop_pf['font_tt']=='true') echo 'uppercase';?>;
				}
				.widget_price_filter .button{
					background-color: <?php echo $et_shop_pf['button_bg_color']; ?> !important;
					border-color: <?php echo $et_shop_pf['button_border_color']; ?> !important;
					border-radius: <?php echo $et_shop_pf['button_border_radius']; ?>px !important;
					color:<?php echo $et_shop_pf['button_font_color']; ?> !important;
				}
				.widget_price_filter .button:hover{
					background-color: <?php echo $et_shop_pf['button_hover_bg_color']; ?> !important;
					border-color: <?php echo $et_shop_pf['button_hover_border_color']; ?> !important;
					color: <?php echo $et_shop_pf['button_hover_font_color']; ?> !important;
				}
				.woocommerce .widget_price_filter .ui-slider .ui-slider-range{
					background-color: <?php echo $et_shop_pf['line_color']; ?>; 
				}
				.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content{
					background-color: <?php echo $et_shop_pf['line_bg_color']; ?>;
				}
				.woocommerce .widget_price_filter .ui-slider .ui-slider-handle{
					background-color: <?php echo $et_shop_pf['circle_color']; ?>;
				}
				.woocommerce .widget_price_filter .button:after{
					top:-1px !important;
				}
				
				div.product{
					overflow:hidden !important;
				}
				.woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del{
					display:inline-block !important;
				}
				
				/* Shop Module Fix*/
				.et_pb_shop:not(.et_pb_shop_tm) ul.products li.product .price del{
					display:block !important;
				}

				.woocommerce ul.products li.product .button:not(.et-module-different){
					width:auto;
					/*height: 16px;*/
					/*font-size: 16px !important;*/
					height:auto;
					padding-left:16px;
					display:inline-block;
				}
				.woocommerce ul.products li.product .button:after{
					/*display:none;*/
					/*font-size: 15px !important;*/

				}
				.woocommerce ul.products li.product{
					text-align:center;
				}
				.woocommerce ul.products li.product .star-rating{
					display:inline-block;
				}
				
				/* Sale label Style*/
				<?php if($et_shop_other['onsale']=='Diagonal'){?>
				
					.woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale{
						left:40% !important;
					}
					.woocommerce ul.products li.product .onsale{
						font-size:18px !important;
					} 
					div.product > span.onsale{
							transform: rotate(-45deg);
							left: -12% !important;
							width: 40%;
							top: 3% !important;
					}
					.woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale{
							right: auto !important;
							margin: 0 !important;
							transform: rotate(45deg);
							left: 40% !important;
							top: 3% !important;
							width: 90%;
							height: 38px;
							font-size: 16px;
					}

					@media screen and (max-width: 870px){
						div.product > span.onsale{
							width:30%;
							left:-10% !important;
							top: 2% !important;
							
						}
					}
					@media screen and (max-width: 768px){
						div.product > span.onsale{
							width:50%;
							left:-10% !important;
							top: 2% !important;
							
						}
					}
					
				<?php } ?>

				<?php if($et_shop_other['onsale']=='Left'){?>
					.woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale{
						border-radius:0px !important;
						padding:5px !important;
						font-weight:bold;
						color:white !important;
						font-size:16px;
					}
					div.product > span.onsale{
						color:white !important;
						font-weight:bold;
						font-size:16px;
						padding: 5px !important;
						-webkit-border-radius: 0px !important;
						-moz-border-radius: 0px !important;
						border-radius: 0px !important;
						width: 60px !important;
						top: 22px !important;
   						left: 50px !important;
						border-radius:0px !important;
					}
					.woocommerce ul.products li.product .onsale{
						border-radius: 0px !important;
					}
				<?php } ?>

				<?php if($et_shop_other['onsale']=='Right'){?>
					.woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale{
						border-radius:0px !important;
						padding:5px !important;
						font-weight:bolder;
						color:white !important;
						font-size:14px;
						font-family: 'Source Sans Pro', 'sans serif';
						left: 65% !important;
					}
					div.product > span.onsale{
						color:white !important;
						font-weight:bolder;
						font-size:14px;
						padding: 5px !important;
						-webkit-border-radius: 0px !important;
						-moz-border-radius: 0px !important;
						border-radius: 0px !important;
						font-family: 'Source Sans Pro', 'sans serif';
						left:31% !important;
						top:20px !important;
						width: 70px !important;
						border-radius:0px !important;
					}
					.woocommerce ul.products li.product .onsale{
						border-radius: 0px !important;
					}
					.et_pb_shop_tm ul.products li.product span.onsale
					{
						left:15px !important;
					}
					@media screen and (max-width: 768px){
						div.product > span.onsale{
							left:75% !important;
						}
					}
				<?php } ?>

				/* Label Color if isset */
				.woocommerce span.onsale{
					color: <?php echo $et_shop_other['onsale_font_color'].' !important';?>;
					background-color:<?php echo $et_shop_other['onsale_bg_color'].' !important';?>;
				}

		</style>
		<?php
		do_action('tm_dynamic_css');
	}

	// Admin/back-end scripts and style enqueue
	function tm_admin_enqueue_scripts() {

		wp_enqueue_script(
			'tm-admin-js',
			TM_PLUGIN_URI . '/js/admin.js', // Update to where you put the file.
			array(),
			null,
			true
		);
	}

	function tm_admin_enqueue_styles() {
		
		wp_enqueue_style(
			'tm-admin-style',
			TM_PLUGIN_URI . '/css/tm_admin.css', // Update to where you put the file.
			array() // You must include these here.
		);
	}

	// User front end script enqueue
	function tm_user_enqueue_scripts(){
		wp_enqueue_script(
			'tm-user-js',
			TM_PLUGIN_URI . '/js/tm-user.js',
			array(),
			null,
			true
		);
	}

	// User front end style enqueue
	function tm_user_enqueue_styles(){
		wp_enqueue_style(
			'tm-user-style',
			TM_PLUGIN_URI . '/css/tm-user.css', 
			array()
		);
	}

	// Activate add to cart button after products on shop page
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 20 );

	function tm_image_zoom(){
	// Activate Image zoom on products
		wp_enqueue_style(
			'tm-zoom-magnify',
			TM_PLUGIN_URI . '/css/magnify.css', 
			array()
		);
		wp_enqueue_script(
			'tm-jquery-lens-js',
			TM_PLUGIN_URI . '/js/jquery.magnify.js',
			array(),
			null,
			true
		);
	}

	/*function tm_img_module_zoom(){
		wp_enqueue_script(
			'tm-jquery-activate-zoom-js',
			TM_PLUGIN_URI . '/js/tm-activate-image-zoom.js',
			array(),
			null,
			true
		);
	}*/

	function tm_single_product_img_zoom(){
		wp_enqueue_script(
			'tm-jquery-product-simple-zoom',
			TM_PLUGIN_URI . '/js/tm-product-image-zoom.js',
			array(),
			null,
			true
		);
	}


	
?>