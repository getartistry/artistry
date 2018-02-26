<div class="panel woocommerce_options_panel" id="linked_product_data" style="display:none;">
	<div class="options_group">
		<p class="form-field">
			<label><?php _e("Up-Sells", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="" name="single_product_up_sells" style="" value="<?php echo esc_attr($post['single_product_up_sells']) ?>"/>			
			<a href="#help" class="wpallimport-help" title="<?php _e('Products can be matched by SKU, ID, or Title, and must be comma separated.', 'wpai_woocommerce_addon_plugin'); ?>">?</a>
		</p>
		<p class="form-field">
			<label><?php _e("Cross-Sells", "wpai_woocommerce_addon_plugin"); ?></label>
			<input type="text" class="" name="single_product_cross_sells" value="<?php echo esc_attr($post['single_product_cross_sells']) ?>"/>			
			<a href="#help" class="wpallimport-help" title="<?php _e('Products can be matched by SKU, ID, or Title, and must be comma separated.', 'wpai_woocommerce_addon_plugin'); ?>">?</a>
		</p>
	</div> <!-- End options group -->
	<div class="options_group grouping show_if_simple show_if_external show_if_variable">
		<?php
		$post_parents = array();
		$post_parents[''] = __( 'Choose a grouped product&hellip;', 'wpai_woocommerce_addon_plugin' );

		$posts_in = array_unique( (array) get_objects_in_term( get_term_by( 'slug', 'grouped', 'product_type' )->term_id, 'product_type' ) );
		if ( sizeof( $posts_in ) > 0 ) {
			$posts_in = array_slice($posts_in, 0, 100);
			$args = array(
				'post_type'		=> 'product',
				'post_status' 	=> 'any',
				'numberposts' 	=> 100,
				'orderby' 		=> 'title',
				'order' 		=> 'asc',
				'post_parent' 	=> 0,
				'include' 		=> $posts_in,
			);
			$grouped_products = get_posts( $args );

			if ( $grouped_products ) {
				foreach ( $grouped_products as $product ) {

					if ( $product->ID == $post->ID )
						continue;

					$post_parents[ $product->ID ] = $product->post_title;
				}
			}
		}
		?>

		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="multiple_grouping_product_yes" class="switcher" name="is_multiple_grouping_product" value="yes" <?php echo 'no' != $post['is_multiple_grouping_product'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_grouping_product_yes"><?php _e("Grouping", "wpai_woocommerce_addon_plugin"); ?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-multiple_grouping_product_yes set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<select name="multiple_grouping_product">
						<?php
						foreach ($post_parents as $parent_id => $parent_title) {
							?>
							<option value="<?php echo $parent_id; ?>" <?php if ($parent_id == $post['multiple_grouping_product']):?>selected="selected"<?php endif;?>><?php echo $parent_title;?></option>
							<?php
						}
						?>
					</select>
					<a href="#help" class="wpallimport-help" title="<?php _e('Set this option to make this product part of a grouped product.', 'wpai_woocommerce_addon_plugin'); ?>">?</a>
				</span>
			</div>
		</div>
		
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="multiple_grouping_product_no" class="switcher" name="is_multiple_grouping_product" value="no" <?php echo 'no' == $post['is_multiple_grouping_product'] ? 'checked="checked"': '' ?>/>
			<label for="multiple_grouping_product_no" style="width: 200px;"><?php _e('Manual Grouped Product Matching', 'wpai_woocommerce_addon_plugin' )?></label>
			<a href="#help" class="wpallimport-help" sstyle="top:2px;" title="<?php _e('Product will be assigned as the child of an already created product matching the specified criteria.', 'wpai_woocommerce_addon_plugin'); ?>">?</a>			
		</p>
		
		<div class="switcher-target-multiple_grouping_product_no set_with_xpath" style="padding-left: 20px;">

			<div class="form-field wpallimport-radio-field">																					
				<input type="radio" id="duplicate_indicator_xpath_grouping" class="switcher" name="grouping_indicator" value="xpath" <?php echo 'xpath' == $post['grouping_indicator'] ? 'checked="checked"': '' ?>/>
				<label for="duplicate_indicator_xpath_grouping"><?php _e('Match by Post Title', 'wpai_woocommerce_addon_plugin' )?></label>
				<span class="wpallimport-clear"></span>
				<div class="switcher-target-duplicate_indicator_xpath_grouping set_with_xpath" style="vertical-align:middle">											
					<span class="wpallimport-slide-content" style="padding-left:0;">
						<input type="text" name="single_grouping_product" value="<?php echo esc_attr($post['single_grouping_product']); ?>" style="float:none; margin:1px; " />
					</span>
				</div>										
			</div>

			<div class="form-field wpallimport-radio-field">
				<input type="radio" id="duplicate_indicator_custom_field_grouping" class="switcher" name="grouping_indicator" value="custom field" <?php echo 'custom field' == $post['grouping_indicator'] ? 'checked="checked"': '' ?>/>
				<label for="duplicate_indicator_custom_field_grouping"><?php _e('Match by Custom Field', 'wpai_woocommerce_addon_plugin' )?></label><br>
				<span class="wpallimport-clear"></span>
				<div class="switcher-target-duplicate_indicator_custom_field_grouping set_with_xpath" style="padding-left:40px;">
					<span class="wpallimport-slide-content" style="padding-left:0;">
						<label style="width: 80px;"><?php _e('Name', 'wpai_woocommerce_addon_plugin') ?></label>
						<input type="text" name="custom_grouping_indicator_name" value="<?php echo esc_attr($post['custom_grouping_indicator_name']) ?>" style="float:none; margin:1px;" />
						
						<span class="wpallimport-clear"></span>

						<label style="width: 80px;"><?php _e('Value', 'wpai_woocommerce_addon_plugin') ?></label>
						<input type="text" name="custom_grouping_indicator_value" value="<?php echo esc_attr($post['custom_grouping_indicator_value']) ?>" style="float:none; margin:1px;" />
					</span>
				</div>
			</div>
		</div>																						

	</div>
</div><!-- End Product Panel -->