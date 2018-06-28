jQuery(document).ready(function($) {
	var project_hash = null;
	var project_status = null;

	$(".dismiss-review-notification").click(function(){
		$(".review-notification").remove();	
      
	        jQuery.ajax({
                	method: "POST",
                        url: ajaxurl,
                        data: { 'action': 'woosea_review_notification' }
                })
	});

	$(".notice-dismiss").click(function(){
		$(".license-notification").remove();	

	        jQuery.ajax({
                	method: "POST",
                        url: ajaxurl,
                        data: { 'action': 'woosea_license_notification' }
                })
	});


    	$("td[colspan=8]").find("div").parents("tr").hide();

	$('.checkbox-field').change(function(index, obj){
    		project_hash = $(this).val();
		project_status = $(this).prop("checked")

                jQuery.ajax({
                	method: "POST",
                        url: ajaxurl,
                        data: { 'action': 'woosea_project_status', 'project_hash': project_hash, 'active': project_status }
                })

         	$("table tbody").find('input[name="manage_record"]').each(function(){
			var hash = this.value;
			if(hash == project_hash){
				if (project_status == false){
					$(this).parents("tr").addClass('strikethrough');
				} else {
					$(this).parents("tr").removeClass('strikethrough');
				}
                	}
            	});
	});

	// Check if user would like to enable WPML support
	$('#add_wpml_support').on('change', function(){ // on change of state
   		if(this.checked){

			// Checkbox is on
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_add_wpml', 'status': "on" }
                	})
		} else {
			// Checkbox is off
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_add_wpml', 'status': "off" }
                	})
		}
	})	

	// Check if user would like to enable Dynamic Remarketing
	$('#add_remarketing').on('change', function(){ // on change of state
   		if(this.checked){

			// Checkbox is on
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_add_remarketing', 'status': "on" }
                	})
			.done(function( data ) {
				$('#remarketing').after('<tr id="adwords_conversion_id"><td colspan="2"><span>Insert your Dynamic Remarketing Conversion tracking ID:</span>&nbsp;<input type="text" class="input-field-medium" id="adwords_conv_id" name="adwords_conv_id">&nbsp;<input type="submit" id="save_conversion_id" value="Save"></td></tr>');	
			})
                	.fail(function( data ) {
                        	console.log('Failed AJAX Call :( /// Return Data: ' + data);
                	});	
		} else {
			// Checkbox is off
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_add_remarketing', 'status': "off" }
                	})
			.done(function( data ) {
				$('#adwords_conversion_id').remove();	
			})
                	.fail(function( data ) {
                        	console.log('Failed AJAX Call :( /// Return Data: ' + data);
                	});	
		}
	})	

        // Add a mapping row to the table for field mappings
        jQuery("#save_conversion_id").click(function(){
                var adwords_conversion_id = $('#adwords_conv_id').val();
	        var re = /^[0-9]*$/;
                
		var woosea_valid_conversion_id=re.test(adwords_conversion_id);
                // Check for allowed characters
                if (!woosea_valid_conversion_id){
                        $('.notice').replaceWith("<div class='notice notice-error woosea-notice-conversion is-dismissible'><p>Sorry, only numbers are allowed for your Dynamic Remarketing Conversion tracking ID.</p></div>");
                        // Disable submit button too
                        $('#save_conversion_id').attr('disabled',true);
                } else {
                        $('.woosea-notice-conversion').remove();
                        $('#save_conversion_id').attr('disabled',false);

			// Now we need to save the conversion ID so we can use it in the dynamic remarketing JS
                        jQuery.ajax({
                                method: "POST",
                                url: ajaxurl,
                                data: { 'action': 'woosea_save_adwords_conversion_id', 'adwords_conversion_id': adwords_conversion_id }
                        })
                }	
	})


	// Check if user would like to add attributes
	$('#add_identifiers').on('change', function(){ // on change of state
   		if(this.checked){
			// Checkbox is on
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_add_identifiers', 'status': "on" }
                	})
		} else {
			// Checkbox is off
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_add_identifiers', 'status': "off" }
                	})
		}
	})	

	// Check if user would like to fix the WooCommerce structured data bug
	$('#fix_json_ld').on('change', function(){ // on change of state
   		if(this.checked){
			// Checkbox is on
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_enable_structured_data', 'status': "on" }
                	})
		} else {
			// Checkbox is off
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_enable_structured_data', 'status': "off" }
                	})
		}
	})	

	$(".actions").delegate("span", "click", function() {

   		var id=$(this).attr('id');
		var idsplit = id.split('_');
		var project_hash = idsplit[1];
		var action = idsplit[0];		

		if (action == "gear"){
    			$("tr").not(':first').click(
				function(event) {
        				var $target = $(event.target);
        				$target.closest("tr").next().find("div").parents("tr").slideDown( "slow" );                
    				}
			);
		}

		if (action == "trash"){

			var popup_dialog = confirm("Are you sure you want to delete this feed?");
			if (popup_dialog == true){
        			jQuery.ajax({
                			method: "POST",
                       	 		url: ajaxurl,
                        		data: { 'action': 'woosea_project_delete', 'project_hash': project_hash }
                		})
	
            			$("table tbody").find('input[name="manage_record"]').each(function(){
					var hash = this.value;
					if(hash == project_hash){
                    				$(this).parents("tr").remove();
                			}
            			});
            		}
		}

		if(action == "cancel"){

			var popup_dialog = confirm("Are you sure you want to cancel processing the feed?");
			if (popup_dialog == true){
        			jQuery.ajax({
                			method: "POST",
                       	 		url: ajaxurl,
                        		data: { 'action': 'woosea_project_cancel', 'project_hash': project_hash }
                		})
	
				// Replace status of project to stop processing
			        $("table tbody").find('input[name="manage_record"]').each(function(){
					var hash = this.value;
					if(hash == project_hash){
						$(".woo-product-feed-pro-blink_"+hash).text(function () {
                                       			$(this).addClass('woo-product-feed-pro-blink_me');
    							return $(this).text().replace("ready", "stop processing"); 
						});	
					}
            			});
			}
		}

		if (action == "refresh"){
		
			var popup_dialog = confirm("Are you sure you want to refresh the product feed?");
			if (popup_dialog == true){
        			jQuery.ajax({
                			method: "POST",
                       	 		url: ajaxurl,
                        		data: { 'action': 'woosea_project_refresh', 'project_hash': project_hash }
                		})

				// Replace status of project to processing
			        $("table tbody").find('input[name="manage_record"]').each(function(){
					var hash = this.value;
					if(hash == project_hash){
						$(".woo-product-feed-pro-blink_off_"+hash).text(function () {
                                        		$(this).addClass('woo-product-feed-pro-blink_me');
    							return $(this).text().replace("ready", "processing"); 
						});	
					}
            			});
			}
		}
	});
});
