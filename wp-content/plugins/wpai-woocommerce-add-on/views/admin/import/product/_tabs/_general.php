<div class="panel woocommerce_options_panel" id="general_product_data">
	<div class="options_group">
		<p class="form-field">
			<label><?php _e("SKU", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" name="single_product_sku" style="" value="<?php echo esc_attr($post['single_product_sku']) ?>"/>			
		</p>
	</div>
	<div class="options_group show_if_external">
		<p class="form-field">
			<label><?php _e("Product URL", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" name="single_product_url" value="<?php echo esc_attr($post['single_product_url']) ?>"/>
			<a href="#help" class="wpallimport-help" title="<?php _e('The external/affiliate link URL to the product.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
		</p>
		<p class="form-field">
			<label><?php _e("Button text", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" name="single_product_button_text" value="<?php echo esc_attr($post['single_product_button_text']) ?>"/>
			<a href="#help" class="wpallimport-help" title="<?php _e('This text will be shown on the button linking to the external product.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
		</p>
	</div>
	<div class="options_group pricing show_if_simple show_if_external show_if_variable">
		
		<p class="form-field">
			<label><?php printf(__("Regular Price (%s)", "wpai_woocommerce_addon_plugin"), get_woocommerce_currency_symbol()); ?></label>
			<input type="text" class="short" name="single_product_regular_price" value="<?php echo esc_attr($post['single_product_regular_price']) ?>"/> <strong class="options_group show_if_variable" style="position:relative; top:4px; left:4px;">(<?php _e('required', 'wpai_woocommerce_addon_plugin'); ?>)</strong>
		</p>
		<p class="form-field">
			<label><?php printf(__("Sale Price (%s)", "wpai_woocommerce_addon_plugin"), get_woocommerce_currency_symbol()); ?></label>
			<input type="text" class="short" name="single_product_sale_price" value="<?php echo esc_attr($post['single_product_sale_price']) ?>"/>&nbsp;<a id="regular_price_shedule" href="javascript:void(0);" <?php if ($post['is_regular_price_shedule']):?>style="display:none;"<?php endif; ?>><?php _e('schedule', 'wpai_woocommerce_addon_plugin');?></a>
			<input type="hidden" name="is_regular_price_shedule" value="<?php echo esc_attr($post['is_regular_price_shedule']) ?>"/>
		</p>
		<p class="form-field" <?php if ( ! $post['is_regular_price_shedule']):?>style="display:none;"<?php endif; ?> id="sale_price_range">
			<span style="vertical-align:middle">
				<label><?php _e("Sale Price Dates", "wpai_woocommerce_addon_plugin"); ?></label>
				<input type="text" class="datepicker" name="single_sale_price_dates_from" value="<?php echo esc_attr($post['single_sale_price_dates_from']) ?>" style="float:none; width:110px;"/>
				<span><?php _e('and', 'wpai_woocommerce_addon_plugin') ?></span>
				<input type="text" class="datepicker" name="single_sale_price_dates_to" value="<?php echo esc_attr($post['single_sale_price_dates_to']) ?>" style="float:none !important; width:110px;"/>
				&nbsp;<a id="cancel_regular_price_shedule" href="javascript:void(0);"><?php _e('cancel', "wpai_woocommerce_addon_plugin");?></a>
			</span>
		</p>		

		<!-- AUTOFIX PRICES -->				

		<p class="form-field pmwi_trigger_adjust_prices" style="color:#5EAAAC;"> <strong><span><?php if (!empty($post['single_product_regular_price_adjust']) or !empty($post['single_product_sale_price_adjust'])):?>-<?php else: ?>+<?php endif; ?></span><?php _e(' Adjust Prices', "wpai_woocommerce_addon_plugin"); ?></strong><a href="#help" class="wpallimport-help" title="<?php _e('Mark prices up and down, convert prices to currencies.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top: -1px; left:4px;;">?</a></p>
		<div class="pmwi_adjust_prices" <?php if (!empty($post['single_product_regular_price_adjust']) or !empty($post['single_product_sale_price_adjust'])):?>style="display:block;"<?php endif; ?>>
			<p class="form-field">
				<label><?php printf(__("Regular Price (%s)", "wpai_woocommerce_addon_plugin"), get_woocommerce_currency_symbol()); ?></label>
				<input type="text" class="short" name="single_product_regular_price_adjust" value="<?php echo esc_attr($post['single_product_regular_price_adjust']) ?>"/>
				<select name="single_product_regular_price_adjust_type" class="pmwi_adjust_type">
					<option value="%" <?php echo ($post['single_product_regular_price_adjust_type'] == '%') ? 'selected="selected"' : ''; ?>>%</option>
					<option value="$" <?php echo ($post['single_product_regular_price_adjust_type'] == '$') ? 'selected="selected"' : ''; ?>><?php echo get_woocommerce_currency_symbol(); ?></option>
				</select>
				<a href="#help" class="wpallimport-help pmwi_percentage_prices_note" title="<?php _e('Leave blank or enter in 100% to keep the price as is. Enter in 110% to markup by 10%. Enter in 50% to cut prices in half.', 'wpai_woocommerce_addon_plugin') ?>">?</a>	
				<a href="#help" class="wpallimport-help pmwi_reduce_prices_note" title="<?php _e('Enter a negative number to reduce prices.', 'wpai_woocommerce_addon_plugin') ?>">?</a>	
				<span class="wpallimport-clear"></span>			
			</p>			

			<p class="form-field">
				<label><?php printf(__("Sale Price (%s)", "wpai_woocommerce_addon_plugin"), get_woocommerce_currency_symbol()); ?></label>
				<input type="text" class="short" name="single_product_sale_price_adjust" value="<?php echo esc_attr($post['single_product_sale_price_adjust']) ?>"/>			
				<select name="single_product_sale_price_adjust_type" class="pmwi_adjust_type">
					<option value="%" <?php echo ($post['single_product_sale_price_adjust_type'] == '%') ? 'selected="selected"' : ''; ?>>%</option>
					<option value="$" <?php echo ($post['single_product_sale_price_adjust_type'] == '$') ? 'selected="selected"' : ''; ?>><?php echo get_woocommerce_currency_symbol(); ?></option>
				</select>
				<a href="#help" class="wpallimport-help pmwi_percentage_prices_note" title="<?php _e('Leave blank or enter in 100% to keep the price as is. Enter in 110% to markup by 10%. Enter in 50% to cut prices in half.', 'wpai_woocommerce_addon_plugin') ?>">?</a>	
				<a href="#help" class="wpallimport-help pmwi_reduce_prices_note" title="<?php _e('Enter a negative number to reduce prices.', 'wpai_woocommerce_addon_plugin') ?>">?</a>	
				<span class="wpallimport-clear"></span>			
			</p>
		</div>

		<br>
		
		<p class="form-field wpallimport-radio-field">
			<input type="hidden" name="disable_prepare_price" value="0" />
			<input type="checkbox" id="disable_prepare_price" name="disable_prepare_price" value="1" <?php echo $post['disable_prepare_price'] ? 'checked="checked"' : '' ?> />
			<label for="disable_prepare_price" style="width:220px;"><?php _e('Remove currency symbols from price', 'wpai_woocommerce_addon_plugin') ?></label>
			<a href="#help" class="wpallimport-help" title="<?php _e('Attempt to remove currency symbols from prices.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:1px;">?</a>
		</p>	

		<p class="form-field wpallimport-radio-field">
			<input type="hidden" name="convert_decimal_separator" value="0" />
			<input type="checkbox" id="convert_decimal_separator" name="convert_decimal_separator" value="1" <?php echo $post['convert_decimal_separator'] ? 'checked="checked"' : '' ?> />
			<label for="convert_decimal_separator" style="width:220px;"><?php _e('Convert decimal separator to a period', 'wpai_woocommerce_addon_plugin') ?></label>
			<a href="#help" class="wpallimport-help" title="<?php _e('Prices must be imported using a period as the decimal separator. If you\'d like to change the decimal separator you can do so by editing your WooCommerce settings.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:1px;">?</a>			
		</p>

		<p class="form-field wpallimport-radio-field">
			<input type="hidden" name="prepare_price_to_woo_format" value="0" />
			<input type="checkbox" id="prepare_price_to_woo_format" name="prepare_price_to_woo_format" value="1" <?php echo $post['prepare_price_to_woo_format'] ? 'checked="checked"' : '' ?> />
			<label for="prepare_price_to_woo_format" style="width:420px;"><?php _e('Attempt to convert incorrectly formatted prices to WooCommerce format', 'wpai_woocommerce_addon_plugin') ?></label>
			<a href="#help" class="wpallimport-help" title="<?php _e('WP All Import will attempt to correct the formatting of prices presented incorrectly, but this doesn\'t always work. Try unchecking this option if your prices are not appearing correctly, or enter your prices in your import file using the same format you would when entering them in WooCommerce.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:1px;">?</a>			
		</p>	

		<p class="form-field">			
			<a href="javascript:void(0);" class="preview_prices" rel="preview_prices" style="float:left;"><?php _e('Preview Prices', 'wpai_woocommerce_addon_plugin'); ?></a>
		</p>

	</div>

	<div class="options_group show_if_variable">
		<p class="form-field">
			<label><?php _e("Variation Description"); ?></label>
			<input type="text" class="short" name="single_product_variation_description" value="<?php echo esc_attr($post['single_product_variation_description']) ?>"/>			
		</p>
	</div>

	<!--div class="options_group show_if_variable">

		<p class="form-field"><?php _e('Variation Enabled','wpai_woocommerce_addon_plugin');?><a href="#help" class="wpallimport-help" title="<?php _e('This option is the same as the Enabled checkbox when editing an individual variation in WooCommerce.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:0px;">?</a></p>			
			
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="variation_enabled_yes" class="switcher" name="is_variation_enabled" value="yes" <?php echo 'yes' == $post['is_variation_enabled'] ? 'checked="checked"': '' ?>/>
			<label for="variation_enabled_yes"><?php _e("Yes"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="variation_enabled_no" class="switcher" name="is_variation_enabled" value="no" <?php echo 'no' == $post['is_variation_enabled'] ? 'checked="checked"': '' ?>/>
			<label for="variation_enabled_no"><?php _e("No"); ?></label>
		</p>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="variation_enabled_xpath" class="switcher" name="is_variation_enabled" value="xpath" <?php echo 'xpath' == $post['is_variation_enabled'] ? 'checked="checked"': '' ?>/>
			<label for="variation_enabled_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-variation_enabled_xpath set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_variation_enabled" style="width:300px;" value="<?php echo esc_attr($post['single_variation_enabled']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
				</span>
			</div>
		</div>
	</div-->

	<div class="options_group show_if_simple show_if_external show_if_variable">
		
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="is_product_virtual_yes" class="switcher" name="is_product_virtual" value="yes" <?php echo 'yes' == $post['is_product_virtual'] ? 'checked="checked"': '' ?>/>
				<label for="is_product_virtual_yes"><?php _e("Virtual", "wpai_woocommerce_addon_plugin"); ?></label>
			</p>			
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="is_product_virtual_no" class="switcher" name="is_product_virtual" value="no" <?php echo 'no' == $post['is_product_virtual'] ? 'checked="checked"': '' ?>/>
				<label for="is_product_virtual_no"><?php _e("Not Virtual", "wpai_woocommerce_addon_plugin"); ?></label>
			</p>
			<div class="form-field wpallimport-radio-field">
				<input type="radio" id="is_product_virtual_xpath" class="switcher" name="is_product_virtual" value="xpath" <?php echo 'xpath' == $post['is_product_virtual'] ? 'checked="checked"': '' ?>/>
				<label for="is_product_virtual_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
				<span class="wpallimport-clear"></span>
				<div class="switcher-target-is_product_virtual_xpath set_with_xpath">		
					<span class="wpallimport-slide-content" style="padding-left:0;">			
						<input type="text" class="smaller-text" name="single_product_virtual" style="width:300px;" value="<?php echo esc_attr($post['single_product_virtual']) ?>"/>
						<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
					</span>
				</div>
			</div>
		
	</div>
	<div class="options_group show_if_simple show_if_external show_if_variable">
		
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="is_product_downloadable_yes" class="switcher" name="is_product_downloadable" value="yes" <?php echo 'yes' == $post['is_product_downloadable'] ? 'checked="checked"': '' ?>/>
			<label for="is_product_downloadable_yes"><?php _e("Downloadable", "wpai_woocommerce_addon_plugin"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="is_product_downloadable_no" class="switcher" name="is_product_downloadable" value="no" <?php echo 'no' == $post['is_product_downloadable'] ? 'checked="checked"': '' ?>/>
			<label for="is_product_downloadable_no"><?php _e("Not Downloadable", "wpai_woocommerce_addon_plugin"); ?></label>
		</p>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="is_product_downloadable_xpath" class="switcher" name="is_product_downloadable" value="xpath" <?php echo 'xpath' == $post['is_product_downloadable'] ? 'checked="checked"': '' ?>/>
			<label for="is_product_downloadable_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-is_product_downloadable_xpath set_with_xpath">					
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_downloadable" style="width:300px;" value="<?php echo esc_attr($post['single_product_downloadable']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>					
				</span>
			</div>
		</div>			
	</div>
	
	<div class="options_group show_if_downloadable">
		<p class="form-field">
			<label><?php _e("File paths", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" name="single_product_files" value="<?php echo esc_attr($post['single_product_files']) ?>" style="margin-right:5px;"/>
			<input type="text" class="small" name="product_files_delim" value="<?php echo esc_attr($post['product_files_delim']) ?>" style="width:5%; text-align:center;"/>
			<a href="#help" class="wpallimport-help" title="<?php _e('File paths/URLs, comma separated. The delimiter is used when an XML element contains multiple URLs/paths - i.e. <code>http://files.com/1.doc, http://files.com/2.doc</code>.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
		</p>
		<p class="form-field">
			<label><?php _e("File names", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" name="single_product_files_names" value="<?php echo esc_attr($post['single_product_files_names']) ?>" style="margin-right:5px;"/>
			<input type="text" class="small" name="product_files_names_delim" value="<?php echo esc_attr($post['product_files_names_delim']) ?>" style="width:5%; text-align:center;"/>
			<a href="#help" class="wpallimport-help" title="<?php _e('File names, comma separated. The delimiter is used when an XML element contains multiple names - i.e. <code>1.doc, 2.doc</code>.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
		</p>
		<p class="form-field">
			<label><?php _e("Download Limit", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" placeholder="Unimited" name="single_product_download_limit" value="<?php echo esc_attr($post['single_product_download_limit']) ?>"/>&nbsp;
			<a href="#help" class="wpallimport-help" title="<?php _e( 'Leave blank for unlimited re-downloads.', 'wpai_woocommerce_addon_plugin' ) ?>">?</a>
		</p>
		<p class="form-field">
			<label><?php _e("Download Expiry", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" placeholder="Never" name="single_product_download_expiry" value="<?php echo esc_attr($post['single_product_download_expiry']) ?>"/>&nbsp;
			<a href="#help" class="wpallimport-help" title="<?php _e( 'Enter the number of days before a download link expires, or leave blank.', 'wpai_woocommerce_addon_plugin' ) ?>">?</a>
		</p>
		<p class="form-field">
			<label><?php _e("Download Type", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="short" placeholder="Standard Product" name="single_product_download_type" value="<?php echo esc_attr($post['single_product_download_type']) ?>"/>&nbsp;
			<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'application\', \'music\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
		</p>				
	</div>

	<div class="options_group show_if_simple show_if_external show_if_variable">
		
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="multiple_product_tax_status_yes" class="switcher" name="is_multiple_product_tax_status" value="yes" <?php echo 'no' != $post['is_multiple_product_tax_status'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_product_tax_status_yes"><?php _e("Tax Status", "wpai_woocommerce_addon_plugin"); ?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-multiple_product_tax_status_yes set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<select class="select short" name="multiple_product_tax_status">
						<option value="taxable" <?php echo 'taxable' == $post['multiple_product_tax_status'] ? 'selected="selected"': '' ?>><?php _e('Taxable', 'wpai_woocommerce_addon_plugin');?></option>
						<option value="shipping" <?php echo 'shipping' == $post['multiple_product_tax_status'] ? 'selected="selected"': '' ?>><?php _e('Shipping only', 'wpai_woocommerce_addon_plugin');?></option>
						<option value="none" <?php echo 'none' == $post['multiple_product_tax_status'] ? 'selected="selected"': '' ?>><?php _e('None', 'wpai_woocommerce_addon_plugin');?></option>
					</select>				
				</span>
			</div>
		</div>			
		
		<div class="form-field wpallimport-radio-field">			
			<input type="radio" id="multiple_product_tax_status_no" class="switcher" name="is_multiple_product_tax_status" value="no" <?php echo 'no' == $post['is_multiple_product_tax_status'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_product_tax_status_no"><?php _e('Set tax status with XPath', 'wpai_woocommerce_addon_plugin' ); ?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-multiple_product_tax_status_no set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_tax_status" style="width:300px;" value="<?php echo esc_attr($post['single_product_tax_status']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('Value should be the slug for the tax status - \'taxable\', \'shipping\', and \'none\' are the default slugs.', 'wpai_woocommerce_addon_plugin') ?>">?</a>				
				</span>
			</div>
		</div>

	</div>
	<div class="options_group show_if_simple show_if_external show_if_variable">
		
		<div class="form-field wpallimport-radio-field">		
			<input type="radio" id="multiple_product_tax_class_yes" class="switcher" name="is_multiple_product_tax_class" value="yes" <?php echo 'no' != $post['is_multiple_product_tax_class'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_product_tax_class_yes"><?php _e("Tax Class", "wpai_woocommerce_addon_plugin"); ?></label>
		
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-multiple_product_tax_class_yes set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<?php
					$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'woocommerce_tax_classes' ) ) ) );
					$classes_options = array();
					$classes_options[''] = __( 'Standard', 'wpai_woocommerce_addon_plugin' );

		    		if ( $tax_classes ){
						foreach ( $tax_classes as $class ){
							$classes_options[ sanitize_title( $class ) ] = esc_html( $class );
						}
					}
					?>
					<select class="select short" name="multiple_product_tax_class">
						<?php foreach ($classes_options as $key => $value):?>
							<option value="<?php echo $key; ?>" <?php echo selected( esc_attr( $key ), esc_attr( $post['multiple_product_tax_class'] ), false ); ?>><?php echo $value; ?></option>
						<?php endforeach; ?>											
					</select>
				</span>
			</div>
		</div>
			
		<div class="form-field wpallimport-radio-field">	
			<input type="radio" id="multiple_product_tax_class_no" class="switcher" name="is_multiple_product_tax_class" value="no" <?php echo 'no' == $post['is_multiple_product_tax_class'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_product_tax_class_no"><?php _e('Set tax class with XPath', 'wpai_woocommerce_addon_plugin' )?></label>		
			
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-multiple_product_tax_class_no set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_tax_class" style="width:300px;" value="<?php echo esc_attr($post['single_product_tax_class']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('Value should be the slug for the tax class - \'reduced-rate\' and \'zero-rate\', are the default slugs.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
				</span>
			</div>
		</div>

	</div>

</div>