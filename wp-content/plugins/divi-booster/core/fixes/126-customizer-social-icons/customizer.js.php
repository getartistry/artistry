<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
<script>
jQuery(function($) {
	
	// === Setup ===
				
	// Get initial social icons
	var data = <?php echo json_encode($model); ?>;
	
	// Create boxes for each icon (from the template)
	for (var i=0; i<data.length; i++) { 
		if (data[i]['name'] !== '{{title}}') {
			db121_addIconBox(data[i]); 
		}
	}
	
	// Hide the template
	$('#db_social_icon_template').hide();
	
	// Make icon list sortable - pre WP 4.7
	$("#accordion-section-divibooster-social-icons .accordion-section-content").sortable({
			items: "li.customize-control-widget_form", 
			handle: ".widget-top",
			stop: function() { db121_refresh_model(); }
		});
		
	// Make icon list sortable - WP 4.7+
	$("#sub-accordion-section-divibooster-social-icons").sortable({
			items: "li.customize-control-widget_form", 
			handle: ".widget-top",
			stop: function() { db121_refresh_model(); }
		});
	
	// Update model on any changes
	function db121_refresh_model(changed = true) {
		
		// Add details of each social icon box to a json object
		var json = [];		
		var boxes = $('#sub-accordion-section-divibooster-social-icons .customize-control-widget_form'); 
		boxes.each(function() {
			var box = $(this);
			json.push({
				'id'	: box.find('select').val(),
				'name'	: box.find('.widget-title h4').text(),
				'url'	: box.find('.db121_choose_url input').val()
			});
		});
		
		// Update the model with the new box details
		var model_val = $('#model_icons').val(JSON.stringify(json));
		
		// Notify customizer that social icon settings have changed
		if (changed) { model_val.change(); }
	}
	
	// === Create the initial icon boxes === 

	// Function for adding new icon boxes
	function db121_addIconBox(data) {
		
		// clone the template
		var icon = $('#db_social_icon_template').clone(true,true);
		
		// Set the title and selected icon
		icon.find('.widget-title h4').text(data['name']);
		icon.find('select').val(data['id']); 	
		icon.find('.db121_choose_url input').val(data['url']); 	

		// Hide the custom icon select if not needed
		if (data['id']!='custom') { icon.find('.customize-control-upload').hide(); }
		
		// Insert into the customizer
		icon.insertBefore($('#db_social_icon_add')).show();
		
		// Refresh
		db121_refresh_model(false);
	}

	
	// === Handlers === 

	// Add handler for adding new boxes
	$('.add-new-widget').click(function(){
		db121_addIconBox({id:'', name:'(No network set)'});
	});
	
	// Handle open / close of icon boxes
	function toggleIconBox(elem) {
		var li = $(elem).closest('li');
		li.toggleClass('expanded');
		li.find('.widget-inside').slideToggle(200);
	}
	$('.widget-top').click(function(){ toggleIconBox(this); });
	
	// Handle icon box removal
	$('.widget-control-remove').click(function(){ 
		jQuery(this).closest('.customize-control-widget_form').remove(); 
		db121_refresh_model();
	});
	
	// Handle icon change
	$.fn.iconChanged = function() {
		var objs = this.each(function() { 
			var select = jQuery(this);
			var selected = select.find(':selected');
			var widget = select.closest('.widget');
			var title = widget.find('.widget-title h4');
			var upload = widget.find('.customize-control-upload');
			var url = widget.find('.db121_choose_url');
			if (selected.val()=='') { // no icon
				title.text('(No network set)');
				upload.hide();
				url.hide();
			} else if (selected.val()=='custom') { // custom icon 
				title.text('Custom Icon');
				upload.show();
				url.show();
			} else { // predefined icon
				title.text(selected.text());
				upload.hide();
				url.show();
			}
		});
		db121_refresh_model();
		return objs;
	}
	$('.db121_network_select').change(function(){ $(this).iconChanged(); });
	
	// Handle url change
	$('.db121_choose_url input').keyup(function(){ db121_refresh_model(); });

});
</script>			