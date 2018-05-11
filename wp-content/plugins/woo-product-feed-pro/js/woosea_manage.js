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

	// Check if user would like to create an AdTribes.io Support user account
	$('#grant_access').on('change', function(){ // on change of state
   		if(this.checked){
			// Checkbox is on
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_create_support_user', 'status': "on" }
                	})
		} else {
			// Checkbox is off
                	jQuery.ajax({
                        	method: "POST",
                        	url: ajaxurl,
                        	data: { 'action': 'woosea_create_support_user', 'status': "off" }
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
