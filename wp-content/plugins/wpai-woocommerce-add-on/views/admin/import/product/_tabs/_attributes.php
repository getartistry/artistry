<div class="panel woocommerce_options_panel" id="woocommerce_attributes" style="display:none;">
	<div class="input">
		<table class="form-table custom-params" id="attributes_table" style="max-width:95%;">
			<thead>
				<tr>
					<td><?php _e('Name', 'wpai_woocommerce_addon_plugin'); ?></td>
					<td style="padding-bottom: 5px;">
						<?php _e('Values', 'wpai_woocommerce_addon_plugin'); ?>
						<a href="#help" class="wpallimport-help" title="<?php _e('Separate multiple values with a |', 'wpai_woocommerce_addon_plugin') ?>" style="top:-1px;">?</a>
					</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($post['attribute_name'][0])):?>
					<?php foreach ($post['attribute_name'] as $i => $name): if ("" == $name) continue; ?>
						<tr class="form-field">
							<td style="width: 50%;">
								<input type="text" class="widefat" name="attribute_name[]"  value="<?php echo esc_attr($name) ?>" style="width:100%;"/>
							</td>
							<td style="width: 50%;">
								<input type="text" class="widefat" name="attribute_value[]" value="<?php echo str_replace("&amp;","&", htmlentities(htmlentities($post['attribute_value'][$i]))); ?>" style="width:100%;"/>						
								<span class="wpallimport-clear"></span>
								<div class="form-field wpallimport-radio-field" style="padding: 0 !important; position: relative; left: -100%; width: 200%;">
									
									<a href="javascript:void(0);" id="advanced_attributes_<?php echo $i; ?>" class="action advanced_attributes"><span>+</span> <?php _e('Advanced', 'wpai_woocommerce_addon_plugin') ?></a>
									<input type="hidden" value="<?php echo (empty($post['is_advanced'][$i])) ? '0' : $post['is_advanced'][$i];?>" name="is_advanced[]">

									<span class="default_attribute_settings">
										<span class='in_variations'>
											<input type="checkbox" name="in_variations[]" id="in_variations_<?php echo $i; ?>" <?php echo ($post['in_variations'][$i]) ? 'checked="checked"' : ''; ?> style="float: left;" value="1"/>
											<label for="in_variations_<?php echo $i; ?>"><?php _e('In Variations','wpai_woocommerce_addon_plugin');?></label>															
										</span>

										<span class='is_visible'>
											<input type="checkbox" name="is_visible[]" id="is_visible_<?php echo $i; ?>" <?php echo ($post['is_visible'][$i]) ? 'checked="checked"' : ''; ?> style="float: left;" value="1"/>
											<label for="is_visible_<?php echo $i; ?>"><?php _e('Is Visible','wpai_woocommerce_addon_plugin');?></label>																									
										</span>

										<span class='is_taxonomy'>
											<input type="checkbox" name="is_taxonomy[]" id="is_taxonomy_<?php echo $i; ?>" <?php echo ($post['is_taxonomy'][$i]) ? 'checked="checked"' : ''; ?> style="float: left;" value="1" class="switcher"/>
											<label for="is_taxonomy_<?php echo $i; ?>"><?php _e('Is Taxonomy','wpai_woocommerce_addon_plugin');?></label>													
										</span>

										<span class='is_create_taxonomy switcher-target-is_taxonomy_<?php echo $i; ?>'>
											<input type="checkbox" name="create_taxonomy_in_not_exists[]" id="create_taxonomy_in_not_exists_<?php echo $i; ?>" <?php echo ($post['create_taxonomy_in_not_exists'][$i]) ? 'checked="checked"' : ''; ?> style="float: left;" value="1"/>
											<label for="create_taxonomy_in_not_exists_<?php echo $i; ?>"><?php _e('Auto-Create Terms','wpai_woocommerce_addon_plugin');?></label>													
										</span>		
									</span>

									<div class="advanced_attribute_settings">

										<div class="input" style="display:inline-block;">
											<div class="input">
												<input type="radio" id="advanced_in_variations_yes_<?php echo $i; ?>" class="switcher" name="advanced_in_variations[<?php echo $i; ?>]" value="yes" <?php echo ( empty($post['advanced_in_variations'][$i]) or ( ! empty($post['advanced_in_variations'][$i]) and ! in_array($post['advanced_in_variations'][$i], array('no', 'xpath'))) ) ? 'checked="checked"': '' ?>/>
												<label for="advanced_in_variations_yes_<?php echo $i; ?>"><?php _e("In Variations", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>			
											<div class="input">
												<input type="radio" id="advanced_in_variations_no_<?php echo $i; ?>" class="switcher" name="advanced_in_variations[<?php echo $i; ?>]" value="no" <?php echo (!empty($post['advanced_in_variations'][$i]) and 'no' == $post['advanced_in_variations'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_in_variations_no_<?php echo $i; ?>"><?php _e("Not In Variations", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>
											<div class="input wpallimport-radio-field">
												<input type="radio" id="advanced_in_variations_xpath_<?php echo $i; ?>" class="switcher" name="advanced_in_variations[<?php echo $i; ?>]" value="xpath" <?php echo (!empty($post['advanced_in_variations'][$i]) and 'xpath' == $post['advanced_in_variations'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_in_variations_xpath_<?php echo $i; ?>"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
												<span class="wpallimport-clear"></span>
												<div class="switcher-target-advanced_in_variations_xpath_<?php echo $i; ?> set_with_xpath">		
													<span class="wpallimport-slide-content" style="padding-left:0;">			
														<input type="text" class="smaller-text" name="advanced_in_variations_xpath[<?php echo $i; ?>]" value="<?php echo (!empty($post['advanced_in_variations_xpath'][$i])) ? esc_attr($post['advanced_in_variations_xpath'][$i]) : ''; ?>"/>
														<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
													</span>
												</div>
											</div>
										</div>

										<div class="input" style="display:inline-block;">
											<div class="input">
												<input type="radio" id="advanced_is_visible_yes_<?php echo $i; ?>" class="switcher" name="advanced_is_visible[<?php echo $i; ?>]" value="yes" <?php echo ( empty($post['advanced_is_visible'][$i]) or ( ! empty($post['advanced_is_visible'][$i]) and ! in_array($post['advanced_is_visible'][$i], array('no', 'xpath'))) ) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_visible_yes_<?php echo $i; ?>"><?php _e("Is Visible", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>			
											<div class="input">
												<input type="radio" id="advanced_is_visible_no_<?php echo $i; ?>" class="switcher" name="advanced_is_visible[<?php echo $i; ?>]" value="no" <?php echo (!empty($post['advanced_is_visible'][$i]) and 'no' == $post['advanced_is_visible'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_visible_no_<?php echo $i; ?>"><?php _e("Not Visible", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>
											<div class="input wpallimport-radio-field">
												<input type="radio" id="advanced_is_visible_xpath_<?php echo $i; ?>" class="switcher" name="advanced_is_visible[<?php echo $i; ?>]" value="xpath" <?php echo (!empty($post['advanced_is_visible'][$i]) and 'xpath' == $post['advanced_is_visible'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_visible_xpath_<?php echo $i; ?>"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
												<span class="wpallimport-clear"></span>
												<div class="switcher-target-advanced_is_visible_xpath_<?php echo $i; ?> set_with_xpath">		
													<span class="wpallimport-slide-content" style="padding-left:0;">			
														<input type="text" class="smaller-text" name="advanced_is_visible_xpath[<?php echo $i; ?>]" value="<?php echo (!empty($post['advanced_is_visible_xpath'][$i])) ? esc_attr($post['advanced_is_visible_xpath'][$i]) : ''; ?>"/>
														<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
													</span>
												</div>
											</div>
										</div>

										<div class="input" style="display:inline-block;">
											<div class="input">
												<input type="radio" id="advanced_is_taxonomy_yes_<?php echo $i; ?>" class="switcher" name="advanced_is_taxonomy[<?php echo $i; ?>]" value="yes" <?php echo (empty($post['advanced_is_taxonomy'][$i]) or ( !empty($post['advanced_is_taxonomy'][$i]) and ! in_array($post['advanced_is_taxonomy'][$i], array('no', 'xpath'))) ) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_taxonomy_yes_<?php echo $i; ?>"><?php _e("Is Taxonomy", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>			
											<div class="input">
												<input type="radio" id="advanced_is_taxonomy_no_<?php echo $i; ?>" class="switcher" name="advanced_is_taxonomy[<?php echo $i; ?>]" value="no" <?php echo (!empty($post['advanced_is_taxonomy'][$i]) and 'no' == $post['advanced_is_taxonomy'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_taxonomy_no_<?php echo $i; ?>"><?php _e("Not Taxonomy", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>
											<div class="input wpallimport-radio-field">
												<input type="radio" id="advanced_is_taxonomy_xpath_<?php echo $i; ?>" class="switcher" name="advanced_is_taxonomy[<?php echo $i; ?>]" value="xpath" <?php echo (!empty($post['advanced_is_taxonomy'][$i]) and 'xpath' == $post['advanced_is_taxonomy'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_taxonomy_xpath_<?php echo $i; ?>"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
												<span class="wpallimport-clear"></span>
												<div class="switcher-target-advanced_is_taxonomy_xpath_<?php echo $i; ?> set_with_xpath">		
													<span class="wpallimport-slide-content" style="padding-left:0;">			
														<input type="text" class="smaller-text" name="advanced_is_taxonomy_xpath[<?php echo $i; ?>]" value="<?php echo (!empty($post['advanced_is_taxonomy_xpath'][$i])) ? esc_attr($post['advanced_is_taxonomy_xpath'][$i]) : ''; ?>"/>
														<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
													</span>
												</div>
											</div>
										</div>

										<div class="input" style="display:inline-block;">
											<div class="input">
												<input type="radio" id="advanced_is_create_terms_yes_<?php echo $i; ?>" class="switcher" name="advanced_is_create_terms[<?php echo $i; ?>]" value="yes" <?php echo (empty($post['advanced_is_create_terms'][$i]) or ( ! empty($post['advanced_is_create_terms'][$i]) and ! in_array($post['advanced_is_create_terms'][$i], array('no', 'xpath'))) ) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_create_terms_yes_<?php echo $i; ?>"><?php _e("Auto-Create Terms", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>			
											<div class="input">
												<input type="radio" id="advanced_is_create_terms_no_<?php echo $i; ?>" class="switcher" name="advanced_is_create_terms[<?php echo $i; ?>]" value="no" <?php echo ( ! empty($post['advanced_is_create_terms'][$i]) and 'no' == $post['advanced_is_create_terms'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_create_terms_no_<?php echo $i; ?>"><?php _e("Do Not Create Terms", 'wpai_woocommerce_addon_plugin'); ?></label>
											</div>
											<div class="input wpallimport-radio-field">
												<input type="radio" id="advanced_is_create_terms_xpath_<?php echo $i; ?>" class="switcher" name="advanced_is_create_terms[<?php echo $i; ?>]" value="xpath" <?php echo (!empty($post['advanced_is_create_terms'][$i]) and 'xpath' == $post['advanced_is_create_terms'][$i]) ? 'checked="checked"': '' ?>/>
												<label for="advanced_is_create_terms_xpath_<?php echo $i; ?>"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
												<span class="wpallimport-clear"></span>
												<div class="switcher-target-advanced_is_create_terms_xpath_<?php echo $i; ?> set_with_xpath">		
													<span class="wpallimport-slide-content" style="padding-left:0;">			
														<input type="text" class="smaller-text" name="advanced_is_create_terms_xpath[<?php echo $i; ?>]" value="<?php echo (!empty($post['advanced_is_create_terms_xpath'][$i])) ? esc_attr($post['advanced_is_create_terms_xpath'][$i]) : ''; ?>"/>
														<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
													</span>
												</div>
											</div>
										</div>

									</div>										

								</div>
							</td>
							<td class="action remove"><a href="#remove" style="top:9px;"></a></td>
						</tr>
					<?php endforeach ?>
				<?php else: ?>
				<tr class="form-field">
					<td style="width: 50%;">
						<input type="text" name="attribute_name[]" value="" class="widefat" style="width:100%;"/>
					</td>
					<td style="width: 50%;">
						<input type="text" name="attribute_value[]" class="widefat" vaalue="" style="width:100%;"/>
						<span class="wpallimport-clear"></span>					
						<div class="form-field wpallimport-radio-field" style="padding: 0 !important; position: relative; left: -100%; width: 200%;">

							<a href="javascript:void(0);" id="advanced_attributes_0" class="action advanced_attributes"><span>+</span> <?php _e('Advanced', 'wpai_woocommerce_addon_plugin') ?></a>
							<input type="hidden" value="0" name="is_advanced[]">

							<span class="default_attribute_settings">
								<span class='in_variations'>
									<input type="checkbox" name="in_variations[]" id="in_variations_0" checked="checked" style="float: left;" value="1"/>
									<label for="in_variations_0"><?php _e('In Variations','wpai_woocommerce_addon_plugin');?></label>											
								</span>
								<span class='is_visible'>
									<input type="checkbox" name="is_visible[]" id="is_visible_0" checked="checked" style="float: left;" value="1"/>
									<label for="is_visible_0"><?php _e('Is Visible','wpai_woocommerce_addon_plugin');?></label>
								</span>
								<span class='is_taxonomy'>
									<input type="checkbox" name="is_taxonomy[]" id="is_taxonomy_0" checked="checked" style="float: left;" value="1" class="switcher"/>
									<label for="is_taxonomy_0"><?php _e('Is Taxonomy','wpai_woocommerce_addon_plugin');?></label>
								</span>
								<span class='is_create_taxonomy switcher-target-is_taxonomy_0'>
									<input type="checkbox" name="create_taxonomy_in_not_exists[]" id="create_taxonomy_in_not_exists_0" checked="checked" style="float: left;" value="1"/>
									<label for="create_taxonomy_in_not_exists_0"><?php _e('Auto-Create Terms','wpai_woocommerce_addon_plugin');?></label>
								</span>
							</span>

							<div class="advanced_attribute_settings">

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_in_variations_yes_0" class="switcher" name="advanced_in_variations[0]" value="yes" checked="checked"/>
										<label for="advanced_in_variations_yes_0"><?php _e("In Variations", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_in_variations_no_0" class="switcher" name="advanced_in_variations[0]" value="no"/>
										<label for="advanced_in_variations_no_0"><?php _e("Not In Variations", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_in_variations_xpath_0" class="switcher" name="advanced_in_variations[0]" value="xpath"/>
										<label for="advanced_in_variations_xpath_0"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="switcher-target-advanced_in_variations_xpath_0 set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_in_variations_xpath[0]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_is_visible_yes_0" class="switcher" name="advanced_is_visible[0]" value="yes" checked="checked"/>
										<label for="advanced_is_visible_yes_0"><?php _e("Is Visible", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_is_visible_no_0" class="switcher" name="advanced_is_visible[0]" value="no"/>
										<label for="advanced_is_visible_no_0"><?php _e("Not Visible", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_is_visible_xpath_0" class="switcher" name="advanced_is_visible[0]" value="xpath"/>
										<label for="advanced_is_visible_xpath_0"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="switcher-target-advanced_is_visible_xpath_0 set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_is_visible_xpath[0]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_is_taxonomy_yes_0" class="switcher" name="advanced_is_taxonomy[0]" value="yes" checked="checked"/>
										<label for="advanced_is_taxonomy_yes_0"><?php _e("Is Taxonomy", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_is_taxonomy_no_0" class="switcher" name="advanced_is_taxonomy[0]" value="no"/>
										<label for="advanced_is_taxonomy_no_0"><?php _e("Not Taxonomy", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_is_taxonomy_xpath_0" class="switcher" name="advanced_is_taxonomy[0]" value="xpath"/>
										<label for="advanced_is_taxonomy_xpath_0"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="switcher-target-advanced_is_taxonomy_xpath_0 set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_is_taxonomy_xpath[0]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_is_create_terms_yes_0" class="switcher" name="advanced_is_create_terms[0]" value="yes" checked="checked"/>
										<label for="advanced_is_create_terms_yes_0"><?php _e("Auto-Create Terms", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_is_create_terms_no_0" class="switcher" name="advanced_is_create_terms[0]" value="no"/>
										<label for="advanced_is_create_terms_no_0"><?php _e("Do Not Create Terms", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_is_create_terms_xpath_0" class="switcher" name="advanced_is_create_terms[0]" value="xpath"/>
										<label for="advanced_is_create_terms_xpath_0"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="switcher-target-advanced_is_create_terms_xpath_0 set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_is_create_terms_xpath[0]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

							</div>	
							
						</div>
					</td>
					<td class="action remove"><a href="#remove" style="top: 9px;"></a></td>
				</tr>
				<?php endif;?>
				<tr class="form-field template">
					<td style="width: 50%;">
						<input type="text" name="attribute_name[]" value="" class="widefat" style="width:100%;"/>
					</td>
					<td style="width: 50%;">
						<input type="text" name="attribute_value[]" class="widefat" value="" style="width:100%;"/>
						<span class="wpallimport-clear"></span>
						<div class="form-field wpallimport-radio-field" style="padding: 0 !important; position: relative; left: -100%; width: 200%;">

							<a href="javascript:void(0);" id="advanced_attributes_0" class="action advanced_attributes"><span>+</span> <?php _e('Advanced', 'wpai_woocommerce_addon_plugin') ?></a>
							<input type="hidden" value="0" name="is_advanced[]">

							<span class="default_attribute_settings">
								<span class='in_variations'>
									<input type="checkbox" name="in_variations[]" checked="checked" style="float: left;" value="1"/>
									<label for=""><?php _e('In Variations','wpai_woocommerce_addon_plugin');?></label>																	
								</span>
								<span class='is_visible'>
									<input type="checkbox" name="is_visible[]" checked="checked" style="float: left;" value="1"/>
									<label for=""><?php _e('Is Visible','wpai_woocommerce_addon_plugin');?></label>																	
								</span>
								<span class='is_taxonomy'>
									<input type="checkbox" name="is_taxonomy[]" checked="checked" style="float: left;" value="1" class="switcher"/>
									<label for=""><?php _e('Is Taxonomy','wpai_woocommerce_addon_plugin');?></label>																	
								</span>
								<span class='is_create_taxonomy'>
									<input type="checkbox" name="create_taxonomy_in_not_exists[]" checked="checked" style="float: left;" value="1"/>
									<label for=""><?php _e('Auto-Create Terms','wpai_woocommerce_addon_plugin');?></label>																	
								</span>	
							</span>

							<div class="advanced_attribute_settings advanced_settings_template">

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_in_variations_yes_00" class="switcher" name="advanced_in_variations[00]" value="yes" checked="checked"/>
										<label for="advanced_in_variations_yes_00"><?php _e("In Variations", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_in_variations_no_00" class="switcher" name="advanced_in_variations[00]" value="no"/>
										<label for="advanced_in_variations_no_00"><?php _e("Not In Variations", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_in_variations_xpath_00" class="switcher" name="advanced_in_variations[00]" value="xpath"/>
										<label for="advanced_in_variations_xpath_00"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_in_variations_xpath[00]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_is_visible_yes_00" class="switcher" name="advanced_is_visible[00]" value="yes" checked="checked"/>
										<label for="advanced_is_visible_yes_00"><?php _e("Is Visible", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_is_visible_no_00" class="switcher" name="advanced_is_visible[00]" value="no"/>
										<label for="advanced_is_visible_no_00"><?php _e("Not Visible", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_is_visible_xpath_00" class="switcher" name="advanced_is_visible[00]" value="xpath"/>
										<label for="advanced_is_visible_xpath_00"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_is_visible_xpath[00]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_is_taxonomy_yes_00" class="switcher" name="advanced_is_taxonomy[00]" value="yes" checked="checked"/>
										<label for="advanced_is_taxonomy_yes_00"><?php _e("Is Taxonomy", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_is_taxonomy_no_00" class="switcher" name="advanced_is_taxonomy[00]" value="no"/>
										<label for="advanced_is_taxonomy_no_00"><?php _e("Not Taxonomy", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_is_taxonomy_xpath_00" class="switcher" name="advanced_is_taxonomy[00]" value="xpath"/>
										<label for="advanced_is_taxonomy_xpath_00"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_is_taxonomy_xpath[00]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

								<div class="input" style="display:inline-block;">
									<div class="input">
										<input type="radio" id="advanced_is_create_terms_yes_00" class="switcher" name="advanced_is_create_terms[00]" value="yes" checked="checked"/>
										<label for="advanced_is_create_terms_yes_00"><?php _e("Auto-Create Terms", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>			
									<div class="input">
										<input type="radio" id="advanced_is_create_terms_no_00" class="switcher" name="advanced_is_create_terms[00]" value="no"/>
										<label for="advanced_is_create_terms_no_00"><?php _e("Do Not Create Terms", 'wpai_woocommerce_addon_plugin'); ?></label>
									</div>
									<div class="input wpallimport-radio-field">
										<input type="radio" id="advanced_is_create_terms_xpath_00" class="switcher" name="advanced_is_create_terms[00]" value="xpath"/>
										<label for="advanced_is_create_terms_xpath_00"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
										<span class="wpallimport-clear"></span>
										<div class="set_with_xpath">		
											<span class="wpallimport-slide-content" style="padding-left:0;">			
												<input type="text" class="smaller-text" name="advanced_is_create_terms_xpath[00]" value=""/>
												<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>">?</a>					
											</span>
										</div>
									</div>
								</div>

							</div>	
															
						</div>
					</td>
					<td class="action remove"><a href="#remove" style="top: 9px;"></a></td>
				</tr>
				<tr>
					<td colspan="3"><a href="#add" title="<?php _e('add', 'wpai_woocommerce_addon_plugin')?>" class="action add-new-custom"><?php _e('Add more', 'wpai_woocommerce_addon_plugin') ?></a></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="options_group show_if_variable">
		<p class="form-field wpallimport-radio-field" style="padding-left: 10px !important;">
			<input type="hidden" name="link_all_variations" value="0" />
			<input type="checkbox" id="link_all_variations" name="link_all_variations" value="1" <?php echo $post['link_all_variations'] ? 'checked="checked"' : '' ?>/>
			<label style="width: 100px;" for="link_all_variations"><?php _e('Link all variations', 'wpai_woocommerce_addon_plugin') ?></label>
			<a href="#help" class="wpallimport-help" title="<?php _e('This option will create all possible variations for the presented attributes. Works just like the Link All Variations option inside WooCommerce.', 'wpai_woocommerce_addon_plugin') ?>" style="top:3px;">?</a>
		</p>
	</div>
</div><!-- End Product Panel -->