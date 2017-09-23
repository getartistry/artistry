<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
<li id="db_social_icon_template" class="customize-control customize-control-widget_form widget-rendered">
	<div class="widget">	
		<div class="widget-top ui-sortable-handle">
			<div class="widget-title-action"><a class="widget-action" href="#available-widgets"></a></div>
			<div class="widget-title"><h4>{{title}}</h4></div>
		</div>

		<div class="widget-inside">
			<div class="form">
				<div class="widget-content">
				
					<p class="db121_choose_icon">
						<label>Icon:</label> 
						<select class="db121_network_select widefat" type="text">
							<?php foreach(db121_get_networks() as $k=>$v) { ?>
							<option value="<?php esc_attr_e($k) ?>"><?php esc_html_e($v) ?></option>
							<?php } ?>
						</select>
					</p>
				
					<!--
					<div class="customize-control-upload">
					<label>Custom Icon:</label>
						<div class="current">
							<div class="container">					
								<div class="placeholder">
									<div class="inner">
										<span>No file selected</span>
									</div>
								</div>
							</div>
						</div>
						<div class="actions">
							<button type="button" class="button upload-button" id="footer_logo-button">Select File</button>
							<div style="clear:both"></div>
						</div>

						<div class="current">
							<div class="container">					
								<div class="attachment-media-view attachment-media-view-image landscape">
									<div class="thumbnail thumbnail-image">
										<img class="attachment-thumb" src="http://localhost/divi-booster/dev/wp-content/uploads/2015/11/divi-booster.png" draggable="false">
									</div>
								</div>
							</div>
						</div>
						<div class="actions">
							<button type="button" class="button remove-button">Remove</button>
							<button type="button" class="button upload-button" id="footer_logo-button">Change File</button>
							<div style="clear:both"></div>
						</div>
					</div>
					-->
					
					<p class="db121_choose_url">
						<label>Links to:</label> 
						<input class="widefat" name="widget-search[2][title]" type="text" value="" placeholder="Enter target URL">
					</p>
				</div>

				<div class="widget-control-actions">
					<div class="alignleft">
						<a class="widget-control-remove" href="javascript:void(0)">Remove</a>
					</div>
					<div class="alignright">
						<input type="submit" name="savewidget" id="widget-search-2-savewidget" class="button widget-control-save right button-secondary" value="Apply" title="Save and preview changes before publishing them.">			
						<span class="spinner"></span>
					</div>
					<br class="clear">
				</div>
			</div><!-- .form -->
		</div>
	</div>
</li>