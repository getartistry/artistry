jQuery(document).ready(function($) {

	// Dialog opener for information on mapping attributes
    	jQuery( "#dialog" ).dialog({
      		autoOpen: false,
      		show: {
        		effect: "blind",
        		duration: 1000
      		},
      		hide: {
        		effect: "explode",
        		duration: 1000
      		}
    	});

	// Open jQuery dialog and get the right title and attribute helptext 
    	jQuery( ".opener" ).on( "click", function() {
		var id=$(this).attr('id');

                jQuery.ajax({
                        method: "POST",
                        url: ajaxurl,
                        data: { 'action': 'woosea_fieldmapping_dialog_helptext', 'field': id }
                })
                .done(function( data ) {
                        data = JSON.parse( data );
	
			jQuery("#dialogText").text(data.helptext); // set attribute helptext, get it via ajax
			$( "#dialog" ).dialog( "open" );
			$( "#dialog" ).dialog( "option", "title", id); // set title of dialog window
                })
                .fail(function( data ) {
                        console.log('Failed AJAX Call :( /// Return Data: ' + data);
                });
	});

	// Add a mapping row to the table for field mappings
	jQuery(".add-field-mapping").click(function(){
		//var rowCount = $('#woosea-fieldmapping-table >tbody >tr').length-1;
		var channel_hash = $('#channel_hash').val();
                var prevRow = $("tr.rowCount:last input[type=hidden]").val();
		var rowCount = Number(prevRow) + Number(1);

                jQuery.ajax({
                        method: "POST",
                        url: ajaxurl,
                        data: { 'action': 'woosea_fieldmapping_dropdown', 'rowCount': rowCount, 'channel_hash': channel_hash }
                })
                .done(function( data ) {
                        data = JSON.parse( data );
			
			$( '#woosea-fieldmapping-table' ).append('<tr><td><input type="hidden" name="attributes[' + rowCount + '][rowCount]" value="' + rowCount + '"><input type="checkbox" name="record" class="checkbox-field"></td><td><select name="attributes[' + rowCount + '][attribute]" class="select-field">' + data.field_options + '</select></td><td><input type="text" name="attributes[' + rowCount + '][prefix]" class="input-field-medium"></td><td><select name="attributes[' + rowCount + '][mapfrom]" class="select-field">' + data.attribute_options + '</select></td><td><input type="text" name="attributes[' + rowCount + '][suffix]" class="input-field-medium"></td></tr>');

			$('.select-field').change(function(){
				if ($(this).val() == "static_value") {
					var rownr = $(this).closest("tr").prevAll("tr").length;
					$(this).replaceWith('<input type="text" name="attributes[' + rowCount + '][mapfrom]" class="input-field-midsmall"><input type="hidden" name="attributes[' + rowCount + '][static_value]" value="true">');
				}
			});
                })
                .fail(function( data ) {
                        console.log('Failed AJAX Call :( /// Return Data: ' + data);
                });
        });


	// Add a mapping row to the table for own mappings
	jQuery(".add-own-mapping").click(function(){
//		var rowCount = $('#woosea-fieldmapping-table >tbody >tr').length-1;
		var channel_hash = $('#channel_hash').val();
                var prevRow = $("tr.rowCount:last input[type=hidden]").val();
		var rowCount = Number(prevRow) + Number(1);

                jQuery.ajax({
                        method: "POST",
                        url: ajaxurl,
                        data: { 'action': 'woosea_fieldmapping_dropdown', 'rowCount': rowCount, 'channel_hash': channel_hash }
                })
                .done(function( data ) {
                        data = JSON.parse( data );
			$( '#woosea-fieldmapping-table' ).append('<tr><td><input type="hidden" name="attributes[' + rowCount + '][rowCount]" value="' + rowCount + '"><input type="checkbox" name="record" class="checkbox-field"></td><td><input name="attributes[' + rowCount + '][attribute]" id="own-input-field" class="input-field"></td><td><input type="text" name="attributes[' + rowCount + '][prefix]" class="input-field-medium"></td><td><select name="attributes[' + rowCount + '][mapfrom]" class="select-field">' + data.attribute_options + '</select></td><td><input type="text" name="attributes[' + rowCount + '][suffix]" class="input-field-medium"></td></tr>');
       
			$('.select-field').change(function(){
				if ($(this).val() == "static_value") {
					var rownr = $(this).closest("tr").prevAll("tr").length;
					$(this).replaceWith('<input type="text" name="attributes[' + rowCount + '][mapfrom]" class="input-field-midsmall"><input type="hidden" name="attributes[' + rowCount + '][static_value]" value="true">');
				}
			});
	         })
                .fail(function( data ) {
                        console.log('Failed AJAX Call :( /// Return Data: ' + data);
                });
        });

	jQuery("#savebutton").click(function(){

  		$("#own-input-field").each(function() {
			var input=$(this).val();
			var re = /^[a-zA-Z_]*$/;
                	var minLength = 2;
                	var maxLength = 20;
			
			var is_input=re.test(input);
                	// Check for allowed characters
                	if (!is_input){
				$('form').submit(function(){
      					return false;
				});	
				$('.notice').replaceWith("<div class='notice notice-error is-dismissible'><p>Sorry, when creating new custom fields only letters are allowed (so no white spaces, numbers or any other character are allowed).</p></div>");
                	} else {
				// Check for length of fieldname
				if (input.length < minLength){
					$('form').submit(function(){
      						return false;
					});	
                                	$('.notice').replaceWith("<div class='notice notice-error is-dismissible'><p>Sorry, your custom field name needs to be at least 2 letters long.</p></div>");
                        	} else if (input.length > maxLength){
        				$('form').submit(function(){
      						return false;
					});	
					$('.notice').replaceWith("<div class='notice notice-error is-dismissible'><p>Sorry, your custom field name cannot be over 20 letters long.</p></div>");
                        	} else {
					$("#fieldmapping")[0].submit();
				}
			}
		});
	});

	jQuery('.select-field').change(function(){
		if ($(this).val() == "static_value") {
			var rownr = $(this).closest("tr").prevAll("tr").length;
			$(this).replaceWith('<input type="text" name="attributes[' + rownr + '][mapfrom]" class="input-field-midsmall"><input type="hidden" name="attributes[' + rownr + '][static_value]" value="true">');
		}
	});

	// Find and remove selected table rows
        jQuery(".delete-field-mapping").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
		
		if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });
});
