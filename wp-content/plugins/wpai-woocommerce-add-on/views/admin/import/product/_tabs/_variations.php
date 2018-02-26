<div class="panel woocommerce_options_panel" id="variable_product_options" style="display:none;">

	<div class="options_group" style="padding-bottom:0px;">
		
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="auto_matching_parent" class="switcher" name="matching_parent" value="auto" <?php echo 'auto' == $post['matching_parent'] ? 'checked="checked"': '' ?>/>
			<label for="auto_matching_parent" style="width:95%"><?php _e('All my variable products have SKUs or some other unique identifier. Each variation is linked to its parent with its parent\'s SKU or other unique identifier.', 'wpai_woocommerce_addon_plugin' )?></label>
		</p>
		<div class="switcher-target-auto_matching_parent"  style="padding-left:25px;">									
			<p class="form-field">
				<label style="width:195px; padding-top:3px;"><?php _e("SKU element for parent", "wpai_woocommerce_addon_plugin"); ?></label> 
				<input type="text" class="short" placeholder="" name="single_product_id" value="<?php echo esc_attr($post['single_product_id']) ?>"/>
				<a href="#help" class="wpallimport-help" title="<?php _e('SKU column in the below example.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative;">?</a>
			</p>
			<p class="form-field">
				<label style="width:195px; padding-top:3px;"><?php _e("Parent SKU element for variation", "wpai_woocommerce_addon_plugin"); ?></label>
				<input type="text" class="short" placeholder="" name="single_product_parent_id" value="<?php echo esc_attr($post['single_product_parent_id']) ?>"/>
				<a href="#help" class="wpallimport-help" title="<?php _e('Parent SKU column in the below example.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative;">?</a>
			</p>
			<p class="form-field">
				<strong><?php _e("Example Data For Use With This Option","wpai_woocommerce_addon_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2014/10/data-example-1.csv" tatger="_blank"><?php _e("download","wpai_woocommerce_addon_plugin");?></a>
				<span class="wpallimport-clear"></span>
				<img src="<?php echo PMWI_ROOT_URL; ?>/static/img/data-example-1.png"/>														
			</p>			
		</div>

		<div class="wpallimport-clear" style="margin-top:5px;"></div>
	
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="auto_matching_parent_first_is_parent_id" class="switcher" name="matching_parent" value="first_is_parent_id" <?php echo 'first_is_parent_id' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
			<label for="auto_matching_parent_first_is_parent_id" style="width:95%"><?php _e('All products with variations are grouped with a unique value that is the same for each variation and unique for each product.', 'wpai_woocommerce_addon_plugin' )?></label>
		</p>

		<div class="switcher-target-auto_matching_parent_first_is_parent_id"  style="padding-left:25px;">									
			<p class="form-field">
				<label style="width:105px; padding-top: 3px;"><?php _e("Unique Value", "wpai_woocommerce_addon_plugin"); ?></label> 
				<input type="text" class="short" placeholder="" name="single_product_id_first_is_parent_id" value="<?php echo esc_attr($post['single_product_id_first_is_parent_id']) ?>"/>
				<a href="#help" class="wpallimport-help" title="<?php _e('Group ID column in the below example.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative;">?</a>
			</p>
			<p class="form-field">
				<label style="width:105px; padding-top: 3px;"><?php _e("Parent SKU", "wpai_woocommerce_addon_plugin"); ?></label>
				<input type="text" class="short" placeholder="" name="single_product_first_is_parent_id_parent_sku" value="<?php echo esc_attr($post['single_product_first_is_parent_id_parent_sku']) ?>"/>
				<a href="#help" class="wpallimport-help" title="<?php _e('Leave empty to use SKU settings from general tab.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative;">?</a>
			</p>
			<p class="form-field">
				<strong><?php _e("Example Data For Use With This Option","wpai_woocommerce_addon_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2014/10/data-example-2.csv" tatger="_blank"><?php _e("download","wpai_woocommerce_addon_plugin");?></a>
				<span class="wpallimport-clear"></span>
				<img src="<?php echo PMWI_ROOT_URL; ?>/static/img/data-example-2.png"/>		
			</p>			
		</div>

		<div class="wpallimport-clear" style="margin-top:5px;"></div>
		
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="auto_matching_parent_first_is_parent_title" class="switcher" name="matching_parent" value="first_is_parent_title" <?php echo 'first_is_parent_title' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
			<label for="auto_matching_parent_first_is_parent_title" style="width:95%"><?php _e('All variations for a particular product have the same title as the parent product.', 'wpai_woocommerce_addon_plugin' )?></label>
		</p>

		<div class="switcher-target-auto_matching_parent_first_is_parent_title"  style="padding-left:25px;">
			<p class="form-field">
				<label style="width:85px; padding-top: 3px;"><?php _e("Product Title", "wpai_woocommerce_addon_plugin"); ?></label> 
				<input type="text" class="short" placeholder="" name="single_product_id_first_is_parent_title" value="<?php echo ($post['single_product_id_first_is_parent_title']) ? esc_attr($post['single_product_id_first_is_parent_title']) : ((!empty(PMXI_Plugin::$session->options['title'])) ? esc_attr(PMXI_Plugin::$session->options['title']) : ''); ?>"/>
			</p>
			<p class="form-field">
				<strong><?php _e("Example Data For Use With This Option","wpai_woocommerce_addon_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2014/10/data-example-3.csv" tatger="_blank"><?php _e("download","wpai_woocommerce_addon_plugin");?></a>
				<span class="wpallimport-clear"></span>
				<img src="<?php echo PMWI_ROOT_URL; ?>/static/img/data-example-3.png"/>			
			</p>			
		</div>	
		
		<div class="wpallimport-clear" style="margin-top:5px;"></div>
	
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="auto_matching_parent_first_is_variation" class="switcher" name="matching_parent" value="first_is_variation" <?php echo 'first_is_variation' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
			<label for="auto_matching_parent_first_is_variation" style="width:95%"><?php _e('All variations for a particular product have the same title. There are no parent products.', 'wpai_woocommerce_addon_plugin' )?></label>
		</p>
		<div class="switcher-target-auto_matching_parent_first_is_variation"  style="padding-left:25px;">
			<p class="form-field">
				<label style="width:85px; padding-top: 3px;"><?php _e("Product Title"); ?></label> 
				<input type="text" class="short" placeholder="" name="single_product_id_first_is_variation" value="<?php echo ($post['single_product_id_first_is_variation']) ? esc_attr($post['single_product_id_first_is_variation']) : ((!empty(PMXI_Plugin::$session->options['title'])) ? esc_attr(PMXI_Plugin::$session->options['title']) : ''); ?>"/>
			</p>
			<p class="form-field">
				<label style="width:105px; padding-top: 3px;"><?php _e("Parent SKU", "wpai_woocommerce_addon_plugin"); ?></label>
				<input type="text" class="short" placeholder="" name="single_product_first_is_parent_title_parent_sku" value="<?php echo esc_attr($post['single_product_first_is_parent_title_parent_sku']) ?>"/>
				<a href="#help" class="wpallimport-help" title="<?php _e('Leave empty to use SKU settings from general tab.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative;">?</a>
			</p>
			<p class="form-field">
				<strong><?php _e("Example Data For Use With This Option","wpai_woocommerce_addon_plugin");?> </strong> - <a href="http://www.wpallimport.com/wp-content/uploads/2014/10/data-example-4.csv" tatger="_blank"><?php _e("download","wpai_woocommerce_addon_plugin");?></a>
				<span class="wpallimport-clear"></span>
				<img src="<?php echo PMWI_ROOT_URL; ?>/static/img/data-example-4.png"/>			
			</p>			
		</div>																

		<div class="wpallimport-clear" style="margin-top:5px;"></div>			
		
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="xml_matching_parent" class="switcher" name="matching_parent" value="xml" <?php echo 'xml' == $post['matching_parent'] ? 'checked="checked"': '' ?> style="float:left;"/>
			<label for="xml_matching_parent" style="width:350px;"><?php _e('I\'m importing XML and my variations are child XML elements', 'wpai_woocommerce_addon_plugin' )?> </label>			
		</p>		
			
		<div class="switcher-target-xml_matching_parent" style="padding-left:25px; position:relative;">

			<div class="input">

				<p class="form-field"><a href="http://youtu.be/F1NX4po0dsc" target="_blank"><?php _e("Video Example", "wpai_woocommerce_addon_plugin");?></a></p>
				
				<p class="form-field">
					<label style="width:150px;"><?php _e("Variations XPath", "wpai_woocommerce_addon_plugin"); ?></label>
					<input type="text" class="short" placeholder="" id="variations_xpath" name="variations_xpath" value="<?php echo esc_attr($post['variations_xpath']) ?>" style="width:370px !important;"/> <a href="javascript:void(0);" id="toggle_xml_tree"><?php _e("Open XML Tree","wpai_woocommerce_addon_plugin"); ?></a>
					<div class="wpallimport-clear"></div>
					<span id="variations_console"></span>
				</p>				

				<div style="margin-right:2%;">
					
					<!--div class="options_group">
						<p class="form-field wpallimport-radio-field">
							<label style="border-right:none;" for="_variable_virtual"><?php _e('Virtual', 'wpai_woocommerce_addon_plugin');?> </label>
							<input type="checkbox" name="_variable_virtual" id="_variable_virtual" style="position:relative; top:2px; margin-left:5px;" <?php echo ($post['_variable_virtual']) ? 'checked="checked"' : ''; ?>>
						</p>
						<p class="form-field wpallimport-radio-field">
							<label for="_variable_downloadable" class="show_if_simple"><?php _e('Downloadable','wpai_woocommerce_addon_plugin');?></label>
							<input type="checkbox" name="_variable_downloadable" id="_variable_downloadable" style="position:relative; top:2px; margin-left:5px;" <?php echo ($post['_variable_downloadable']) ? 'checked="checked"' : ''; ?>>
						</p>
					</div-->					

					<div class="options_group">
						<p class="form-field">
							<label style="width:150px;"><?php _e('SKU','wpai_woocommerce_addon_plugin');?></label>
							<input type="text" value="<?php echo esc_attr($post['variable_sku']) ?>" style="" name="variable_sku" class="short">
							<span class="use_parent">
								<input type="hidden" name="variable_sku_add_parent" value="0"/>
								<input type="checkbox" name="variable_sku_add_parent" id="variable_sku_add_parent" style="margin-left:5px; margin-right:5px;" <?php echo ($post['variable_sku_add_parent']) ? 'checked="checked"' : ''; ?>>
								<label style="width: 160px;" for="variable_sku_add_parent"><?php _e("Add value to the parent SKU","wpai_woocommerce_addon_plugin"); ?></label>
								<a href="#help" class="wpallimport-help" title="<?php _e('Enable this checkbox to combine SKU from parent and variation products.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
							</span>
						</p>						
						<p class="form-field">
							<label style="width:150px;"><?php _e('Image','wpai_woocommerce_addon_plugin');?></label>
							<input type="text" value="<?php echo esc_attr($post['variable_image']) ?>" style="" name="variable_image" class="short">							
							<span class="use_parent">
								<input type="hidden" name="variable_image_use_parent" value="0"/>								
								<input type="checkbox" name="variable_image_use_parent" id="variable_image_use_parent" style="position:relative; top:1px; margin-left:5px; margin-right:5px;" <?php echo ($post['variable_image_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_image_use_parent" style="top:0px;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
								<a href="#help" class="wpallimport-help" title="<?php _e('Images are imported according to the options set in the Images section below. There you can import images to the parent products, and here you can import images to the product variations.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px; right: 30px;">?</a>
							</span>
						</p>
						<p class="form-field">
							<label style="width:150px;"><?php _e('Variation Description','wpai_woocommerce_addon_plugin');?></label>
							<input type="text" value="<?php echo esc_attr($post['variable_description']) ?>" style="" name="variable_description" class="short">
							<span class="use_parent">
								<input type="hidden" name="variable_description_use_parent" value="0"/>
								<input type="checkbox" name="variable_description_use_parent" id="variable_description_use_parent" style="position:relative; top:1px; margin-left:5px; margin-right:5px;" <?php echo ($post['variable_description_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_description_use_parent" style="top:0px;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
							</span>
						</p>
					</div>

					<div class="options_group">
						<p class="form-field wpallimport-radio-field">
							<label><?php _e("Manage stock?"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_manage_stock_yes" class="switcher" name="is_variable_product_manage_stock" value="yes" <?php echo 'yes' == $post['is_variable_product_manage_stock'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_manage_stock_yes"><?php _e("Yes"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_manage_stock_no" class="switcher" name="is_variable_product_manage_stock" value="no" <?php echo 'no' == $post['is_variable_product_manage_stock'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_manage_stock_no"><?php _e("No"); ?></label>
						</p>
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_manage_stock_xpath" class="switcher" name="is_variable_product_manage_stock" value="xpath" <?php echo 'xpath' == $post['is_variable_product_manage_stock'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_manage_stock_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-is_variable_product_manage_stock_xpath set_with_xpath" style="width:390px;">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<input type="text" class="smaller-text" name="single_variable_product_manage_stock" style="width:345px;" value="<?php echo esc_attr($post['single_variable_product_manage_stock']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
									<span class="use_parent" style="float:right; top: 2px;">
										<input type="hidden" name="single_variable_product_manage_stock_use_parent" value="0"/>
										<input type="checkbox" name="single_variable_product_manage_stock_use_parent" id="single_variable_product_manage_stock_use_parent" style="position:relative; top:1px; margin-right:5px; float: left;" <?php echo ($post['single_variable_product_manage_stock_use_parent']) ? 'checked="checked"' : ''; ?>>
										<label for="single_variable_product_manage_stock_use_parent" style="top:3px;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
									</span>
								</span>																							
							</div>						
						</div>
					</div>					

					<div class="options_group variable_stock_fields">
						<p class="form-field">
							<label style="width:150px;"><?php _e('Stock Qty', 'wpai_woocommerce_addon_plugin');?></label>
							<input type="text" value="<?php echo esc_attr($post['variable_stock']) ?>" style="" name="variable_stock" class="short">							
							<span class="use_parent">
								<input type="hidden" name="variable_stock_use_parent" value="0"/>
								<input type="checkbox" name="variable_stock_use_parent" id="variable_stock_use_parent" style="margin-left:5px; margin-right: 5px;" <?php echo ($post['variable_stock_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_stock_use_parent" style="width:120px;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
								<a href="#help" class="wpallimport-help" title="<?php _e('Enable this checkbox to determine XPath from parent element.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
							</span>
						</p>
					</div>

					<div class="options_group">
						<p class="form-field wpallimport-radio-field">
							<label><?php _e("Stock status"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_stock_status_in_stock" class="switcher" name="variable_stock_status" value="instock" <?php echo 'instock' == $post['variable_stock_status'] ? 'checked="checked"': '' ?>/>
							<label for="variable_stock_status_in_stock"><?php _e("In stock"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_stock_status_out_of_stock" class="switcher" name="variable_stock_status" value="outofstock" <?php echo 'outofstock' == $post['variable_stock_status'] ? 'checked="checked"': '' ?>/>
							<label for="variable_stock_status_out_of_stock"><?php _e("Out of stock"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_stock_status_auto" class="switcher" name="variable_stock_status" value="auto" <?php echo 'auto' == $post['variable_stock_status'] ? 'checked="checked"': '' ?>/>
							<label for="variable_stock_status_auto" style="width:100px;"><?php _e("Set automatically"); ?></label>
							<a href="#help" class="wpallimport-help" title="<?php _e('Set the stock status to In Stock for positive or blank Stock Qty values, and Out Of Stock if Stock Qty is 0.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:3px;">?</a>
						</p>
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_stock_status_xpath" class="switcher" name="variable_stock_status" value="xpath" <?php echo 'xpath' == $post['variable_stock_status'] ? 'checked="checked"': '' ?>/>
							<label for="variable_stock_status_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-variable_stock_status_xpath set_with_xpath" style="width:390px;">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<input type="text" class="smaller-text" name="single_variable_stock_status" style="width:345px;" value="<?php echo esc_attr($post['single_variable_stock_status']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'instock\', \'outofstock\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>									
								</span>																							
							</div>						
						</div>
					</div>

					<div class="options_group">
						<p class="form-field wpallimport-radio-field">
							<label><?php _e("Allow Backorders?"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_allow_backorders_no" class="switcher" name="variable_allow_backorders" value="no" <?php echo 'no' == $post['variable_allow_backorders'] ? 'checked="checked"': '' ?>/>
							<label for="variable_allow_backorders_no"><?php _e("Do not allow"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_allow_backorders_notify" class="switcher" name="variable_allow_backorders" value="notify" <?php echo 'notify' == $post['variable_allow_backorders'] ? 'checked="checked"': '' ?>/>
							<label for="variable_allow_backorders_notify"><?php _e("Allow, but notify customer"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_allow_backorders_yes" class="switcher" name="variable_allow_backorders" value="yes" <?php echo 'yes' == $post['variable_allow_backorders'] ? 'checked="checked"': '' ?>/>
							<label for="variable_allow_backorders_yes" style="width:100px;"><?php _e("Allow"); ?></label>							
						</p>
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_allow_backorders_xpath" class="switcher" name="variable_allow_backorders" value="xpath" <?php echo 'xpath' == $post['variable_allow_backorders'] ? 'checked="checked"': '' ?>/>
							<label for="variable_allow_backorders_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-variable_allow_backorders_xpath set_with_xpath" style="width:390px;">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<input type="text" class="smaller-text" name="single_variable_allow_backorders" style="width:345px;" value="<?php echo esc_attr($post['single_variable_allow_backorders']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'no\', \'notify\', \'yes\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>									
								</span>																							
							</div>						
						</div>
					</div>

					<div class="options_group">
						<p class="form-field">
							<label style="width:150px;"><?php _e('Regular Price','wpai_woocommerce_addon_plugin');?> (<?php echo get_woocommerce_currency_symbol(); ?>)</label>
							<input type="text" value="<?php echo esc_attr($post['variable_regular_price']) ?>" style="" name="variable_regular_price" class="short">
							<span class="use_parent">
								<input type="hidden" name="variable_regular_price_use_parent" value="0"/>
								<input type="checkbox" name="variable_regular_price_use_parent" id="variable_regular_price_use_parent" style="position:relative; top:1px; margin-left:5px; margin-right:5px;" <?php echo ($post['variable_regular_price_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_regular_price_use_parent" style="top:1px; position: relative;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
							</span>
						</p>
						<p class="form-field">
							<label style="width:150px;"><?php _e('Sale Price','wpai_woocommerce_addon_plugin');?> (<?php echo get_woocommerce_currency_symbol(); ?>)</label>&nbsp;
							<a id="variable_sale_price_shedule" href="javascript:void(0);" style="<?php if ($post['is_variable_sale_price_shedule']):?>display:none;<?php endif; ?>position:relative; top:-10px;"><?php _e('schedule');?></a>
							<input type="text" value="<?php echo esc_attr($post['variable_sale_price']) ?>" style="" name="variable_sale_price" class="short">
							<input type="hidden" name="is_variable_sale_price_shedule" value="<?php echo esc_attr($post['is_variable_sale_price_shedule']) ?>"/>
							<span class="use_parent">
								<input type="hidden" name="variable_sale_price_use_parent" value="0"/>
								<input type="checkbox" name="variable_sale_price_use_parent" id="variable_sale_price_use_parent" style="position:relative; top:1px; margin-right:5px;" <?php echo ($post['variable_sale_price_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_sale_price_use_parent"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
							</span>
						</p>
						<?php if ( class_exists('woocommerce_wholesale_pricing') ):?>
						<p class="form-field">
							<label style="width:150px;"><?php _e("Wholesale Price (".get_woocommerce_currency_symbol().")"); ?></label>
							<input type="text" class="short" name="variable_whosale_price" value="<?php echo esc_attr($post['variable_whosale_price']) ?>"/>								
							<span class="use_parent">
								<input type="hidden" name="variable_whosale_price_use_parent" value="0"/>
								<input type="checkbox" name="variable_whosale_price_use_parent" id="variable_whosale_price_use_parent" style="position:relative; top:1px; margin-left:5px; margin-right:5px;" <?php echo ($post['variable_whosale_price_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_whosale_price_use_parent"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
							</span>
						</p>
						<?php endif; ?>
					</div>

					<div class="options_group" <?php if ( ! $post['is_variable_sale_price_shedule']):?>style="display:none;"<?php endif; ?> id="variable_sale_price_range">
						<p class="form-field">
							<span style="vertical-align:middle">
								<label style="width:150px;"><?php _e("Variable Sale Price Dates", "wpai_woocommerce_addon_plugin"); ?></label>
								<span class="use_parent">
									<input type="hidden" name="variable_sale_dates_use_parent" value="0"/>
									<input type="checkbox" name="variable_sale_dates_use_parent" id="variable_sale_dates_use_parent" style="position:relative; top:1px; margin-left:5px; margin-right:5px;" <?php echo ($post['variable_sale_dates_use_parent']) ? 'checked="checked"' : ''; ?>>
									<label for="variable_sale_dates_use_parent"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
								</span>
								<br>
								<input type="text" class="datepicker" name="variable_sale_price_dates_from" value="<?php echo esc_attr($post['variable_sale_price_dates_from']) ?>" style="float:none;"/>
								<?php _e('and', 'wpai_woocommerce_addon_plugin') ?>
								<input type="text" class="datepicker" name="variable_sale_price_dates_to" value="<?php echo esc_attr($post['variable_sale_price_dates_to']) ?>" style="float:none;"/>
								&nbsp;<a id="cancel_variable_regular_price_shedule" href="javascript:void(0);"><?php _e('cancel');?></a>
							</span>							
						</p>
					</div>

					<div class="options_group" id="variable_virtual">
						
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_virtual_yes" class="switcher" name="is_variable_product_virtual" value="yes" <?php echo 'yes' == $post['is_variable_product_virtual'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_virtual_yes"><?php _e("Virtual"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_virtual_no" class="switcher" name="is_variable_product_virtual" value="no" <?php echo 'no' == $post['is_variable_product_virtual'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_virtual_no"><?php _e("Not Virtual"); ?></label>
						</p>
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_virtual_xpath" class="switcher" name="is_variable_product_virtual" value="xpath" <?php echo 'xpath' == $post['is_variable_product_virtual'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_virtual_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-is_variable_product_virtual_xpath set_with_xpath" style="width:390px;">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<input type="text" class="smaller-text" name="single_variable_product_virtual" style="width:300px;" value="<?php echo esc_attr($post['single_variable_product_virtual']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
									<span class="use_parent" style="float:right; top: 2px;">
										<input type="hidden" name="single_variable_product_virtual_use_parent" value="0"/>
										<input type="checkbox" name="single_variable_product_virtual_use_parent" id="single_variable_product_virtual_use_parent" style="position:relative; top:1px; margin-right:5px; float: left;" <?php echo ($post['single_variable_product_virtual_use_parent']) ? 'checked="checked"' : ''; ?>>
										<label for="single_variable_product_virtual_use_parent" style="top:3px;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
									</span>																							
								</span>																							
							</div>
						</div>						
					</div>

					<div class="options_group" id="variable_dimensions">
						<p class="form-field">
							<label style="width:150px;"><?php _e('Weight','wpai_woocommerce_addon_plugin');?></label>
							<input type="text" placeholder="0.00" value="<?php echo esc_attr($post['variable_weight']) ?>" style="" name="variable_weight" class="short">
							<span class="use_parent">
								<input type="hidden" name="variable_weight_use_parent" value="0"/>
								<input type="checkbox" name="variable_weight_use_parent" id="variable_weight_use_parent" style="position:relative; top:1px; margin-left:5px; margin-right:5px;" <?php echo ($post['variable_weight_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_weight_use_parent"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
							</span>
						</p>
						<p class="form-field">
							<label for"product_length"=""><?php _e('Dimensions (L×W×H)','wpai_woocommerce_addon_plugin');?></label>
							<span class="use_parent">
								<input type="hidden" name="variable_dimensions_use_parent" value="0"/>
								<input type="checkbox" name="variable_dimensions_use_parent" id="variable_dimensions_use_parent" style="position:relative; top:1px; margin-left:5px; margin-right:5px;" <?php echo ($post['variable_dimensions_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_dimensions_use_parent"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
							</span>
							<br>
							<input type="text" placeholder="0" value="<?php echo esc_attr($post['variable_length']) ?>" name="variable_length" class="short" style="margin-right:5px;">
							<input type="text" placeholder="0" value="<?php echo esc_attr($post['variable_width']) ?>" name="variable_width" class="short" style="margin-right:5px;">
							<input type="text" placeholder="0" value="<?php echo esc_attr($post['variable_height']) ?>" style="" name="variable_height" class="short">							
						</p>
					</div>

					<div class="options_group">
						
						<!-- Shipping class -->
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="multiple_variable_product_shipping_class_yes" class="switcher" name="is_multiple_variable_product_shipping_class" value="yes" <?php echo 'no' != $post['is_multiple_variable_product_shipping_class'] ? 'checked="checked"': '' ?>/>
							<label for="multiple_variable_product_shipping_class_yes" style="width:150px;"><?php _e("Shipping Class"); ?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-multiple_variable_product_shipping_class_yes set_with_xpath">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<?php
										$classes = get_the_terms( 0, 'product_shipping_class' );
										if ( $classes && ! is_wp_error( $classes ) ) $current_shipping_class = current($classes)->term_id; else $current_shipping_class = '';																	

										$args = array(
											'taxonomy' 			=> 'product_shipping_class',
											'hide_empty'		=> 0,
											'show_option_none' 	=> __( 'No shipping class', 'wpai_woocommerce_addon_plugin' ),
											'name' 				=> 'multiple_variable_product_shipping_class',
											'id'				=> 'multiple_variable_product_shipping_class',
											'selected'			=> (!empty($post['multiple_variable_product_shipping_class'])) ? $post['multiple_variable_product_shipping_class'] : $current_shipping_class,
											'class'				=> 'select short'
										);

										wp_dropdown_categories( $args );
									?>									
								</span>
							</div>
						</div>						
						<!-- Shipping class -->
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="multiple_variable_product_shipping_class_no" class="switcher" name="is_multiple_variable_product_shipping_class" value="no" <?php echo 'no' == $post['is_multiple_variable_product_shipping_class'] ? 'checked="checked"': '' ?>/>
							<label for="multiple_variable_product_shipping_class_no" style="width:300px;"><?php _e('Set product shipping class with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-multiple_variable_product_shipping_class_no set_with_xpath">						
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<input type="text" class="smaller-text" name="single_variable_product_shipping_class" style="width:300px;" value="<?php echo esc_attr($post['single_variable_product_shipping_class']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'taxable\', \'shipping\', \'none\').', 'wpai_woocommerce_addon_plugin') ?>" style="position: relative; top: -10px;">?</a>
									<span class="use_parent">
										<input type="hidden" name="single_variable_product_shipping_class_use_parent" value="0"/>
										<input type="checkbox" name="single_variable_product_shipping_class_use_parent" id="single_variable_product_shipping_class_use_parent" style="position:relative; top:2px; margin-left:5px; margin-right:5px;" <?php echo ($post['single_variable_product_shipping_class_use_parent']) ? 'checked="checked"' : ''; ?>>
										<label for="single_variable_product_shipping_class_use_parent" style="top:2px; position: relative;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
									</span>																					
								</span>																					
							</div>							
						</div>	
					</div>

					<div class="options_group">											
						<!-- Tax class -->
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="multiple_variable_product_tax_class_yes" class="switcher" name="is_multiple_variable_product_tax_class" value="yes" <?php echo 'no' != $post['is_multiple_variable_product_tax_class'] ? 'checked="checked"': '' ?>/>
							<label for="multiple_variable_product_tax_class_yes" style="width:150px;"><?php _e("Tax Class", "wpai_woocommerce_addon_plugin"); ?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-multiple_variable_product_tax_class_yes set_with_xpath">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<?php
									$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'woocommerce_tax_classes' ) ) ) );
									$classes_options = array();
									$classes_options[''] = __( 'Standard', 'wpai_woocommerce_addon_plugin' );
						    		if ( $tax_classes )
						    			foreach ( $tax_classes as $class )
						    				$classes_options[ sanitize_title( $class ) ] = esc_html( $class );										
									?>
									<select class="select short" name="multiple_variable_product_tax_class">
										<option value="parent" <?php echo 'parent' == $post['multiple_variable_product_tax_class'] ? 'selected="selected"': '' ?>><?php _e('Same as parent', 'wpai_woocommerce_addon_plugin');?></option>
										<?php foreach ($classes_options as $key => $value):?>
											<option value="<?php echo $key; ?>" <?php echo $key == $post['multiple_variable_product_tax_class'] ? 'selected="selected"': '' ?>><?php echo $value; ?></option>
										<?php endforeach; ?>											
									</select>																
								</span>
							</div>
						</div>
						<!-- Tax class -->								
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="multiple_variable_product_tax_class_no" class="switcher" name="is_multiple_variable_product_tax_class" value="no" <?php echo 'no' == $post['is_multiple_variable_product_tax_class'] ? 'checked="checked"': '' ?>/>
							<label for="multiple_variable_product_tax_class_no" style="width: 300px;"><?php _e('Set product tax class with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-multiple_variable_product_tax_class_no set_with_xpath">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<input type="text" class="smaller-text" name="single_variable_product_tax_class" style="width:300px;" value="<?php echo esc_attr($post['single_variable_product_tax_class']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'reduced-rate\', \'zero-rate\').', 'wpai_woocommerce_addon_plugin') ?>" style="position: relative; top:-10px;">?</a>
									<span class="use_parent">
										<input type="hidden" name="single_variable_product_tax_class_use_parent" value="0"/>
										<input type="checkbox" name="single_variable_product_tax_class_use_parent" id="single_variable_product_tax_class_use_parent" style="position:relative; top:2px; margin-left:5px; margin-right:5px;" <?php echo ($post['single_variable_product_tax_class_use_parent']) ? 'checked="checked"' : ''; ?>>
										<label for="single_variable_product_tax_class_use_parent" style="top:1px; position: relative;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
									</span>
								</span>															
							</div>
						</div>								

					</div>
	
					<!--  Downloadable -->
					<div class="options_group">
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_downloadable_yes" class="switcher" name="is_variable_product_downloadable" value="yes" <?php echo 'yes' == $post['is_variable_product_downloadable'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_downloadable_yes"><?php _e("Downloadable"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_downloadable_no" class="switcher" name="is_variable_product_downloadable" value="no" <?php echo 'no' == $post['is_variable_product_downloadable'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_downloadable_no"><?php _e("Not Downloadable"); ?></label>
						</p>
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="is_variable_product_downloadable_xpath" class="switcher" name="is_variable_product_downloadable" value="xpath" <?php echo 'xpath' == $post['is_variable_product_downloadable'] ? 'checked="checked"': '' ?>/>
							<label for="is_variable_product_downloadable_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-is_variable_product_downloadable_xpath set_with_xpath" style="width:390px;">
								<span class="wpallimport-slide-content" style="padding-left:0;">
									<input type="text" class="smaller-text" name="single_variable_product_downloadable" style="width:345px;" value="<?php echo esc_attr($post['single_variable_product_downloadable']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
									<span class="use_parent" style="float:right; top: 2px;">
										<input type="hidden" name="single_variable_product_downloadable_use_parent" value="0"/>
										<input type="checkbox" name="single_variable_product_downloadable_use_parent" id="single_variable_product_downloadable_use_parent" style="position:relative; top:1px; margin-right:5px; float: left;" <?php echo ($post['single_variable_product_downloadable_use_parent']) ? 'checked="checked"' : ''; ?>>
										<label for="single_variable_product_downloadable_use_parent" style="top:3px;"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin"); ?></label>
									</span>
								</span>															
							</div>						
						</div>
					</div>

					<div class="options_group variable_downloadable">
						<p class="form-field">
							<label style="width:150px;"><?php _e('File paths','wpai_woocommerce_addon_plugin');?></label>
							<input type="text" value="<?php echo esc_attr($post['variable_file_paths']) ?>" name="variable_file_paths" class="short" style="width:60% !important;">
							<input type="text" class="small" name="variable_product_files_delim" value="<?php echo esc_attr($post['variable_product_files_delim']) ?>" style="width:5% !important;text-align:center; margin-left:5px;"/>
							<a href="#help" class="wpallimport-help" title="<?php _e('File paths/URLs, comma separated. The delimiter option uses when xml element contains few paths/URLs (http://files.com/1.doc, http://files.com/2.doc).', 'wpai_woocommerce_addon_plugin') ?>">?</a>
						</p>
						<p class="form-field">
							<label style="width:150px;"><?php _e("File names"); ?></label>
							<input type="text" class="short" name="variable_file_names" value="<?php echo esc_attr($post['variable_file_names']) ?>" style="width:60% !important;"/>
							<input type="text" class="small" name="variable_product_files_names_delim" value="<?php echo esc_attr($post['variable_product_files_names_delim']) ?>" style="width:5% !important;text-align:center; margin-left:5px;"/>
							<a href="#help" class="wpallimport-help" title="<?php _e('File names, comma separated. The delimiter is used when an XML element contains multiple names - i.e. <code>1.doc, 2.doc</code>.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
						</p>
						<p class="form-field">
							<label style="width:150px;"><?php _e('Download Limit','wpai_woocommerce_addon_plugin');?></label>
							<input type="text" value="<?php echo esc_attr($post['variable_download_limit']) ?>" style="" name="variable_download_limit" class="short">
							<span class="use_parent">
								<input type="hidden" name="variable_download_limit_use_parent" value="0"/>
								<input type="checkbox" name="variable_download_limit_use_parent" id="variable_download_limit_use_parent" style="margin-left:5px; margin-right:5px;" <?php echo ($post['variable_download_limit_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_download_limit_use_parent"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin");?></label>
							</span>
						</p>
						<p class="form-field">
							<label style="width:150px;"><?php _e('Download Expiry','wpai_woocommerce_addon_plugin');?></label>
							<input type="text" value="<?php echo esc_attr($post['variable_download_expiry']) ?>" style="" name="variable_download_expiry" class="short">
							<span class="use_parent">
								<input type="hidden" name="variable_download_expiry_use_parent" value="0"/>
								<input type="checkbox" name="variable_download_expiry_use_parent" id="variable_download_expiry_use_parent" style="margin-left:5px; margin-right:5px;" <?php echo ($post['variable_download_expiry_use_parent']) ? 'checked="checked"' : ''; ?>>
								<label for="variable_download_expiry_use_parent"><?php _e("XPath Is From Parent","wpai_woocommerce_addon_plugin");?></label>
							</span>
						</p>
					</div>					

					<div class="options_group">

						<label style="width:150px; padding-left:0px;"><?php _e('Variation Enabled','wpai_woocommerce_addon_plugin'); ?></label>		

						<span class="wpallimport-clear"></span>				
						
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_product_enabled_yes" class="switcher" name="is_variable_product_enabled" value="yes" <?php echo 'yes' == $post['is_variable_product_enabled'] ? 'checked="checked"': '' ?>/>
							<label for="variable_product_enabled_yes"><?php _e("Yes"); ?></label>
						</p>
						<p class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_product_enabled_no" class="switcher" name="is_variable_product_enabled" value="no" <?php echo 'no' == $post['is_variable_product_enabled'] ? 'checked="checked"': '' ?>/>
							<label for="variable_product_enabled_no"><?php _e("No"); ?></label>
						</p>
						<div class="form-field wpallimport-radio-field">
							<input type="radio" id="variable_product_enabled_xpath" class="switcher" name="is_variable_product_enabled" value="xpath" <?php echo 'xpath' == $post['is_variable_product_enabled'] ? 'checked="checked"': '' ?>/>
							<label for="variable_product_enabled_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
							<span class="wpallimport-clear"></span>
							<div class="switcher-target-variable_product_enabled_xpath set_with_xpath">
								<span class="wpallimport-slide-content" style="padding-left:0;">	
									<input type="text" class="smaller-text" name="single_variable_product_enabled" style="width:300px; " value="<?php echo esc_attr($post['single_variable_product_enabled']) ?>"/>
									<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
								</span>									
							</div>
						</div>						

					</div>

					<div class="options_group variation_attributes">
						
						<label style="width:150px; padding-left:0px;"><?php _e('Variation Attributes','wpai_woocommerce_addon_plugin');?></label>
						
						<span class="wpallimport-clear"></span>

						<div class="input">																
							<table class="form-table custom-params" style="max-width:95%;">
								<thead>
									<tr>
										<td><?php _e('Name', 'wpai_woocommerce_addon_plugin') ?></td>
										<td><?php _e('Values', 'wpai_woocommerce_addon_plugin') ?></td>
										<td></td>
									</tr>
								</thead>
								<tbody>
									<?php if ( ! empty($post['variable_attribute_name'][0])):?>
										<?php foreach ($post['variable_attribute_name'] as $i => $name): if ("" == $name) continue; ?>
											<tr class="form-field">
												<td style="width: 50%;">
													<input type="text" class="widefat" name="variable_attribute_name[]"  value="<?php echo esc_attr($name) ?>" style="width:95% !important;"/>
												</td>
												<td style="width: 50%;">
													<input type="text" class="widefat" name="variable_attribute_value[]" value="<?php echo esc_attr($post['variable_attribute_value'][$i]); ?>" style="width: 100% !important;"/>
													
													<span class="wpallimport-clear"></span>
													<p class="form-field wpallimport-radio-field" style="padding: 0 !important; position: relative; left: -100%; width: 200%;">
														<span class='in_variations' style="margin-left:0px;">
															<input type="checkbox" name="variable_in_variations[]" id="variable_in_variations_<?php echo $i; ?>" <?php echo ($post['variable_in_variations'][$i]) ? 'checked="checked"' : ''; ?> style="float:left;" value="1"/>
															<label for="variable_in_variations_<?php echo $i; ?>"><?php _e('In Variations','wpai_woocommerce_addon_plugin');?></label>															
														</span>

														<span class='is_visible'>
															<input type="checkbox" name="variable_is_visible[]" id="variable_is_visible_<?php echo $i; ?>" <?php echo ($post['variable_is_visible'][$i]) ? 'checked="checked"' : ''; ?> style="float: left;" value="1"/>
															<label for="variable_is_visible_<?php echo $i; ?>"><?php _e('Is Visible','wpai_woocommerce_addon_plugin');?></label>																		
														</span>

														<span class='is_taxonomy'>
															<input type="checkbox" name="variable_is_taxonomy[]" id="variable_is_taxonomy_<?php echo $i; ?>" <?php echo ($post['variable_is_taxonomy'][$i]) ? 'checked="checked"' : ''; ?> style="float: left;" value="1"/>
															<label for="variable_is_taxonomy_<?php echo $i; ?>"><?php _e('Taxonomy','wpai_woocommerce_addon_plugin');?></label>																	
														</span>

														<span class='is_create_taxonomy'>
															<input type="checkbox" name="variable_create_taxonomy_in_not_exists[]" id="variable_create_taxonomy_in_not_exists_<?php echo $i;?>" <?php echo ($post['variable_create_taxonomy_in_not_exists'][$i]) ? 'checked="checked"' : ''; ?> style="float:left;" value="1"/>
															<label for="variable_create_taxonomy_in_not_exists_<?php echo $i; ?>"><?php _e('Auto-Create Terms','wpai_woocommerce_addon_plugin');?></label>
														</span>
													</p>																								
												</td>
												<td class="action remove"><a href="#remove" style="top: 9px;"></a></td>
											</tr>
										<?php endforeach ?>
									<?php else: ?>
									<tr class="form-field">
										<td style="width: 50%;">
											<input type="text" name="variable_attribute_name[]" value="" style="width:95% !important;"/>
										</td>
										<td style="width: 50%;">
											<input type="text" class="widefat" name="variable_attribute_value[]" value="" style="width: 100% !important;"/>													
											
											<span class="wpallimport-clear"></span>
											<p class="form-field wpallimport-radio-field" style="padding: 0 !important; position: relative; left: -100%; width: 200%;">
												<span class='in_variations' style="margin-left:0px;">
													<input type="checkbox" name="variable_in_variations[]" id="variable_in_variations_0" checked="checked" style="float: left;" value="1"/>
													<label for="variable_in_variations_0"><?php _e('In Variations','wpai_woocommerce_addon_plugin');?></label>																	
												</span>
												<span class='is_visible'>
													<input type="checkbox" name="variable_is_visible[]" id="variable_is_visible_0" checked="checked" style="float:left;" value="1"/>
													<label for="variable_is_visible_0"><?php _e('Is Visible','wpai_woocommerce_addon_plugin');?></label>																								
												</span>
												<span class='is_taxonomy'>
													<input type="checkbox" name="variable_is_taxonomy[]" id="variable_is_taxonomy_0" checked="checked" style="float:left;" value="1"/>
													<label for="variable_is_taxonomy_0"><?php _e('Taxonomy','wpai_woocommerce_addon_plugin');?></label>																	
												</span>
												<span class='is_create_taxonomy'>
													<input type="checkbox" name="variable_create_taxonomy_in_not_exists[]" id="variable_create_taxonomy_in_not_exists_0" checked="checked" style="float:left;" value="1"/>
													<label for="variable_create_taxonomy_in_not_exists_0"><?php _e('Auto-Create Terms','wpai_woocommerce_addon_plugin');?></label>
												</span>																
											</p>
										</td>
										<td class="action remove"><a href="#remove" style="top: 9px;"></a></td>
									</tr>
									<?php endif;?>
									<tr class="form-field template">
										<td style="width: 50%;">
											<input type="text" name="variable_attribute_name[]" value="" style="width:95% !important;"/>
										</td>
										<td style="width: 50%;">
											<input type="text" class="widefat" name="variable_attribute_value[]" value="" style="width: 100% !important;"/>													
											
											<span class="wpallimport-clear"></span>
											<p class="form-field wpallimport-radio-field" style="padding: 0 !important; position: relative; left: -100%; width: 200%;">
												<span class='in_variations' style="margin-left:0px;">
													<input type="checkbox" name="variable_in_variations[]" checked="checked" style="float: left;" value="1"/>
													<label for=""><?php _e('In Variations','wpai_woocommerce_addon_plugin');?></label>																	
												</span>
												<span class='is_visible'>
													<input type="checkbox" name="variable_is_visible[]" checked="checked" style="float: left;" value="1"/>
													<label for=""><?php _e('Is Visible','wpai_woocommerce_addon_plugin');?></label>	
												</span>
												<span class='is_taxonomy'>
													<input type="checkbox" name="variable_is_taxonomy[]" checked="checked" style="float: left;" value="1"/>
													<label for=""><?php _e('Taxonomy','wpai_woocommerce_addon_plugin');?></label>
												</span>
												<span class='is_create_taxonomy'>
													<input type="checkbox" name="variable_create_taxonomy_in_not_exists[]" checked="checked" style="float: left;" value="1"/>
													<label for=""><?php _e('Auto-Create Terms','wpai_woocommerce_addon_plugin');?></label>
												</span>	
											</p>
										</td>
										<td class="action remove"><a href="#remove" style="top: 9px;"></a></td>
									</tr>
									<tr>
										<td colspan="3"><a href="#add" title="<?php _e('add', 'wpai_woocommerce_addon_plugin')?>" class="action add-new-custom"><?php _e('Add more', 'wpai_woocommerce_addon_plugin') ?></a></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>										
				<div id="variations_tag" class="options_group show_if_variable">
					<a href="javascript:void(0)" id="close_xml_tree"></a>
					<div class="variations_tree">													
						<div id="variations_xml">
							<div class="variations_tag">
								<input type="hidden" name="variations_tagno" value="<?php echo (!empty($tagno)) ? $tagno : 0; ?>" />
								<div class="title">
									<?php printf(__('No matching elements found for XPath expression specified', 'wpai_woocommerce_addon_plugin'), (!empty($tagno)) ? $tagno : 0, (!empty($variation_list_count)) ? $variation_list_count : 0); ?>
								</div>
								<div class="clear"></div>
								<div class="xml resetable"></div>
							</div>
						</div>
					</div>
				</div>										
			</div>
		</div>
		
		<div class="clear" style="margin-top:5px;"></div>			

	</div>

	<div class="options_group variations_are_not_child_elements">
		<p class="form-field wpallimport-radio-field">
			<label><?php _e("Manage stock?"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="is_variation_product_manage_stock_yes" class="switcher" name="is_variation_product_manage_stock" value="yes" <?php echo 'yes' == $post['is_variation_product_manage_stock'] ? 'checked="checked"': '' ?>/>
			<label for="is_variation_product_manage_stock_yes"><?php _e("Yes"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="is_variation_product_manage_stock_no" class="switcher" name="is_variation_product_manage_stock" value="no" <?php echo 'no' == $post['is_variation_product_manage_stock'] ? 'checked="checked"': '' ?>/>
			<label for="is_variation_product_manage_stock_no"><?php _e("No"); ?></label>
		</p>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="is_variation_product_manage_stock_xpath" class="switcher" name="is_variation_product_manage_stock" value="xpath" <?php echo 'xpath' == $post['is_variation_product_manage_stock'] ? 'checked="checked"': '' ?>/>
			<label for="is_variation_product_manage_stock_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-is_variation_product_manage_stock_xpath set_with_xpath" style="width:390px;">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_variation_product_manage_stock" style="width:345px;" value="<?php echo esc_attr($post['single_variation_product_manage_stock']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>									
				</span>																							
			</div>						
		</div>
	</div>					

	<div class="options_group variation_stock_fields variations_are_not_child_elements">
		<p class="form-field">
			<label style="width:150px;"><?php _e('Stock Qty', 'wpai_woocommerce_addon_plugin');?></label>
			<input type="text" value="<?php echo esc_attr($post['variation_stock']) ?>" style="" name="variation_stock" class="short">
		</p>
	</div>

	<div class="options_group variations_are_not_child_elements">
		<p class="form-field wpallimport-radio-field">
			<label><?php _e("Stock status"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="variation_stock_status_in_stock" class="switcher" name="variation_stock_status" value="instock" <?php echo 'instock' == $post['variation_stock_status'] ? 'checked="checked"': '' ?>/>
			<label for="variation_stock_status_in_stock"><?php _e("In stock"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="variation_stock_status_out_of_stock" class="switcher" name="variation_stock_status" value="outofstock" <?php echo 'outofstock' == $post['variation_stock_status'] ? 'checked="checked"': '' ?>/>
			<label for="variation_stock_status_out_of_stock"><?php _e("Out of stock"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="variation_stock_status_auto" class="switcher" name="variation_stock_status" value="auto" <?php echo 'auto' == $post['variation_stock_status'] ? 'checked="checked"': '' ?>/>
			<label for="variation_stock_status_auto" style="width:100px;"><?php _e("Set automatically"); ?></label>
			<a href="#help" class="wpallimport-help" title="<?php _e('Set the stock status to In Stock for positive or blank Stock Qty values, and Out Of Stock if Stock Qty is 0.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:3px;">?</a>
		</p>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="variation_stock_status_xpath" class="switcher" name="variation_stock_status" value="xpath" <?php echo 'xpath' == $post['variation_stock_status'] ? 'checked="checked"': '' ?>/>
			<label for="variation_stock_status_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-variation_stock_status_xpath set_with_xpath" style="width:390px;">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_variation_stock_status" style="width:345px;" value="<?php echo esc_attr($post['single_variation_stock_status']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'instock\', \'outofstock\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>									
				</span>																							
			</div>						
		</div>
	</div>

	<div class="options_group">

		<p class="form-field"><?php _e('Variation Enabled','wpai_woocommerce_addon_plugin');?><a href="#help" class="wpallimport-help" title="<?php _e('This option is the same as the Enabled checkbox when editing an individual variation in WooCommerce.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:0px;">?</a></p>			
			
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="product_enabled_yes" class="switcher" name="is_product_enabled" value="yes" <?php echo 'yes' == $post['is_product_enabled'] ? 'checked="checked"': '' ?>/>
			<label for="product_enabled_yes"><?php _e("Yes"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="product_enabled_no" class="switcher" name="is_product_enabled" value="no" <?php echo 'no' == $post['is_product_enabled'] ? 'checked="checked"': '' ?>/>
			<label for="product_enabled_no"><?php _e("No"); ?></label>
		</p>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="product_enabled_xpath" class="switcher" name="is_product_enabled" value="xpath" <?php echo 'xpath' == $post['is_product_enabled'] ? 'checked="checked"': '' ?>/>
			<label for="product_enabled_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-product_enabled_xpath set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_enabled" style="width:300px;" value="<?php echo esc_attr($post['single_product_enabled']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
				</span>
			</div>
		</div>
	</div>
	
	<div class="options_group">

		<div class="clear"></div>					

		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="set_default_yes" class="switcher" name="is_default_attributes" value="1" <?php echo $post['is_default_attributes'] ? 'checked="checked"': '' ?>/>
			<label for="set_default_yes" style="width: 400px;"><?php _e("Set the default selection in the attributes dropdowns.", 'wpai_woocommerce_addon_plugin'); ?></label>
			<a href="#help" class="wpallimport-help" title="<?php _e('The attributes for the first variation will be automatically selected on the frontend.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
		</p>
		<div class="switcher-target-set_default_yes set_with_xpath" style="padding-left:17px;">
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="set_default_first" name="default_attributes_type" value="first" <?php echo ($post['default_attributes_type'] == 'first') ? 'checked="checked"': '' ?>/>
				<label for="set_default_first" style="width: 90%;"><?php _e("Set first variation as the default selection.", "wpai_woocommerce_addon_plugin"); ?></label>
			</p>
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="set_default_instock" name="default_attributes_type" value="instock" <?php echo ($post['default_attributes_type'] == 'instock') ? 'checked="checked"': '' ?>/>
				<label for="set_default_instock" style="width: 90%;"><?php _e("Set first in stock variation as the default selection.", "wpai_woocommerce_addon_plugin"); ?></label>
			</p>	
		</div>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="set_default_no" class="switcher" name="is_default_attributes" value="0" <?php echo ! $post['is_default_attributes'] ? 'checked="checked"': '' ?>/>
			<label for="set_default_no" style="width: 90%;"><?php _e("Do not set default selections for the dropdowns.", "wpai_woocommerce_addon_plugin"); ?></label>
		</p>

		<p class="form-field wpallimport-radio-field">
			<input type="hidden" name="make_simple_product" value="0" />
			<input type="checkbox" id="make_simple_product" name="make_simple_product" value="1" <?php echo $post['make_simple_product'] ? 'checked="checked"' : '' ?> />
			<label for="make_simple_product" style="width:340px;"><?php _e('Create products with no variations as simple products.', 'wpai_woocommerce_addon_plugin') ?></label>								
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="hidden" name="put_variation_image_to_gallery" value="0" />
			<input type="checkbox" id="put_variation_image_to_gallery" name="put_variation_image_to_gallery" value="1" <?php echo $post['put_variation_image_to_gallery'] ? 'checked="checked"' : '' ?> />
			<label for="put_variation_image_to_gallery" style="width:340px;"><?php _e('Save variation image to the gallery.', 'wpai_woocommerce_addon_plugin') ?></label>								
		</p>
		<?php
			if ( function_exists( 'is_plugin_active' ) ) {
				if ( is_plugin_active( "woocommerce-additional-variation-images/woocommerce-additional-variation-images.php" ) ) {
				?>
					<p class="form-field wpallimport-radio-field">
						<input type="hidden" name="import_additional_variation_images" value="0" />
						<input type="checkbox" id="import_additional_variation_images" name="import_additional_variation_images" value="1" <?php echo $post['import_additional_variation_images'] ? 'checked="checked"' : '' ?> />
						<label for="import_additional_variation_images" style="width:340px;"><?php _e('Import additional variation images.', 'wpai_woocommerce_addon_plugin') ?></label>								
					</p>	
				<?php
				}
			}
		?>
		<p class="form-field wpallimport-radio-field set_parent_stock_option" style="display:none;">
			<input type="hidden" name="set_parent_stock" value="0" />
			<input type="checkbox" id="set_parent_stock" name="set_parent_stock" value="1" <?php echo $post['set_parent_stock'] ? 'checked="checked"' : '' ?> />
			<label for="set_parent_stock" style="width: 435px;"><?php _e('Set _stock value for parent product to the _stock value for the first variation.', 'wpai_woocommerce_addon_plugin') ?></label>								
			<a href="#help" class="wpallimport-help" title="<?php _e('This option works when there are no parent products in your feed ( cases 2 and 4 on Variations tab).', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:1px;">?</a>
		</p>

	</div>

</div><!-- End Product Panel -->