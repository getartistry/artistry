<div class="panel woocommerce_options_panel" id="shipping_product_data" style="display:none;">

	<div class="options_group">
		<p class="form-field">
			<label><?php printf(__("Weight (%s)", 'wpai_woocommerce_addon_plugin'), get_option('woocommerce_weight_unit')); ?></label>
			<input type="text" class="short" placeholder="0.00" name="single_product_weight" style="" value="<?php echo esc_attr($post['single_product_weight']) ?>"/>
		</p>
		<p class="form-field">
			<label><?php printf(__("Dimensions (%s)", "wpai_woocommerce_addon_plugin"), get_option( 'woocommerce_dimension_unit' )); ?></label>
			<input type="text" class="short" placeholder="<?php _e('Length','wpai_woocommerce_addon_plugin');?>" name="single_product_length" style="margin-right:5px;" value="<?php echo esc_attr($post['single_product_length']) ?>"/>
			<input type="text" class="short" placeholder="<?php _e('Width','wpai_woocommerce_addon_plugin');?>" name="single_product_width" style="margin-right:5px;" value="<?php echo esc_attr($post['single_product_width']) ?>"/>
			<input type="text" class="short" placeholder="<?php _e('Height','wpai_woocommerce_addon_plugin');?>" name="single_product_height" style="" value="<?php echo esc_attr($post['single_product_height']) ?>"/>
		</p>
	</div> <!-- End options group -->

	<div class="options_group">
		
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="multiple_product_shipping_class_yes" class="switcher" name="is_multiple_product_shipping_class" value="yes" <?php echo 'no' != $post['is_multiple_product_shipping_class'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_product_shipping_class_yes"><?php _e("Shipping Class", "wpai_woocommerce_addon_plugin"); ?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-multiple_product_shipping_class_yes set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<?php					
					
						$args = array(
							'taxonomy' 			=> 'product_shipping_class',
							'hide_empty'		=> 0,
							'show_option_none' 	=> __( 'No shipping class', 'wpai_woocommerce_addon_plugin' ),
							'name' 				=> 'multiple_product_shipping_class',
							'id'				=> 'multiple_product_shipping_class',
							'selected'			=> ( ! empty($post['multiple_product_shipping_class']) and $post['multiple_product_shipping_class'] > 0 ) ? $post['multiple_product_shipping_class'] : '',
							'class'				=> 'select short'
						);

						wp_dropdown_categories( $args );
					?>
				</span>	
			</div>
		</div>
	
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="multiple_product_shipping_class_no" class="switcher" name="is_multiple_product_shipping_class" value="no" <?php echo 'no' == $post['is_multiple_product_shipping_class'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_product_shipping_class_no" style="width: 350px;"><?php _e('Set product shipping class with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-multiple_product_shipping_class_no set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_shipping_class" style="width:300px;" value="<?php echo esc_attr($post['single_product_shipping_class']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('Value should be the name, ID, or slug for the shipping class. Default slugs are \'taxable\', \'shipping\' and \'none\'.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
				</span>
			</div>
		</div>
					
	</div>	<!-- End options group -->
</div> <!-- End Product Panel -->