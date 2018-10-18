<div class="panel woocommerce_options_panel" id="add_on_options" style="display:none;">
		
		<p class="form-field"><?php _e('Import options',PMWI_Plugin::TEXT_DOMAIN);?></p>
		
		<?php if ( "new" == $post['wizard_type']): ?>
		<div class="options_group hide_if_external">
			<p class="form-field wpallimport-radio-field">
				<input type="hidden" name="missing_records_stock_status" value="0" />
				<input type="checkbox" id="missing_records_stock_status" name="missing_records_stock_status" value="1" <?php echo $post['missing_records_stock_status'] ? 'checked="checked"' : '' ?> />
				<label for="missing_records_stock_status"><?php _e('Set out of stock status for missing records', PMWI_Plugin::TEXT_DOMAIN) ?></label>
				<a href="#help" class="wpallimport-help" title="<?php _e('Option to set the stock status to out of stock instead of deleting the product entirely. This option doesn\'t work when \'Delete missing records\' option is enabled.', PMWI_Plugin::TEXT_DOMAIN) ?>" style="position:relative; top:-2px;">?</a>
			</p>
		</div>
		<?php endif; ?>
		<div class="options_group">
			<p class="form-field wpallimport-radio-field">
				<input type="hidden" name="disable_auto_sku_generation" value="0" />
				<input type="checkbox" id="disable_auto_sku_generation" name="disable_auto_sku_generation" value="1" <?php echo $post['disable_auto_sku_generation'] ? 'checked="checked"' : '' ?> />
				<label for="disable_auto_sku_generation"><?php _e('Disable auto SKU generation', PMWI_Plugin::TEXT_DOMAIN) ?></label>
				<a href="#help" class="wpallimport-help" title="<?php _e('Plugin will NOT automaticaly generate the SKU for each product based on md5 algorithm, if SKU option is empty.', PMWI_Plugin::TEXT_DOMAIN) ?>" style="position:relative; top:-2px;">?</a>
			</p>
			<p class="form-field wpallimport-radio-field">
				<input type="hidden" name="disable_sku_matching" value="0" />
				<input type="checkbox" id="disable_sku_matching" name="disable_sku_matching" value="1" <?php echo $post['disable_sku_matching'] ? 'checked="checked"' : '' ?> />
				<label for="disable_sku_matching"><?php _e('Don\'t check for duplicate SKUs', PMWI_Plugin::TEXT_DOMAIN) ?></label>
				<a href="#help" class="wpallimport-help" title="<?php _e('Each product should have a unique SKU. If this box is checked, WP All Import won\'t check for duplicate SKUs, which speeds up the import process. Make sure the SKU for each of your products is unique. If this box is unchecked, WP All Import will import products with duplicate SKUs with a blank SKU.', PMWI_Plugin::TEXT_DOMAIN) ?>" style="position:relative; top:-2px;">?</a>
			</p>

		</div>
</div>