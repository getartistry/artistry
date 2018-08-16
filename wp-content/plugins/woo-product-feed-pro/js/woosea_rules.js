jQuery(document).ready(function($) {

	// Add standard filters
        jQuery(".add-filter").click(function(){
		// Count amount of rows, used to create the form array field and values
		// var rowCount = $('#woosea-ajax-table >tbody >tr').length-1;

		var rowCount = Math.round(new Date().getTime() + (Math.random() * 100));

		jQuery.ajax({
     		   	method: "POST",
        		url: ajaxurl,
        		data: { 'action': 'woosea_ajax', 'rowCount': rowCount }
      		})
     	 	.done(function( data ) {
			data = JSON.parse( data );
        		$( '#woosea-ajax-table' ).append('<tr><td><input type="hidden" name="rules[' + data.rowCount + '][rowCount]" value="' + data.rowCount + '"><input type="checkbox" name="record" class="checkbox-field"></td><td><i>Standard filter:</i></td><td><select name="rules[' + data.rowCount + '][attribute]" id="rules_' + data.rowCount + '">' + data.dropdown + '</select></td><td><select name="rules[' + data.rowCount + '][condition]" class="select-field"><option value="contains">contains</option><option value="containsnot">does not contain</option><option value="=">is equal to</option><option value="!=">is not equal to</option><option value=">">is greater than</option><option value=">=">is greater or equal to</option><option value="<">is less than</option><option value="=<">is less or equal to</option><option value="empty">is empty</option></select></td><td><input type="text" name="rules[' + rowCount + '][criteria]" class="input-field-large" id="criteria_' + data.rowCount + '"></td><td><input type="checkbox" name="rules[' + rowCount + '][cs]" class="checkbox-field" alt="Case sensitive"></td><td><select name="rules[' + rowCount + '][than]" class="select-field"><optgroup label="Action">Action:<option value="exclude"> Exclude</option><option value="include_only">Include only</option></optgroup></select></td><td>&nbsp;</td></tr>');

                	// Check if user selected a data manipulation condition
                	jQuery("#rules_" + rowCount).on("change", function(){
             			if ($(this).val() == "categories") {
					jQuery.ajax({
     		   				method: "POST",
        					url: ajaxurl,
        					data: { 'action': 'woosea_categories_dropdown', 'rowCount': rowCount }
      					})

                			.done(function( data ) {
			                        data = JSON.parse( data );
                                       		jQuery("#criteria_" + rowCount).replaceWith('' + data.dropdown + '');
					});
				}
			});
      		})
      		.fail(function( data ) {
       		 	console.log('Failed AJAX Call :( /// Return Data: ' + data);
     	 	});	
	});

	// Add rules
        jQuery(".add-rule").click(function(){

		// Count amount of rows, used to create the form array field and values
		// var rowCount = $('#woosea-ajax-table >tbody >tr').length-1;
  		var rowCount = Math.round(new Date().getTime() + (Math.random() * 100));

		jQuery.ajax({
     		   	method: "POST",
        		url: ajaxurl,
        		data: { 'action': 'woosea_ajax', 'rowCount': rowCount }
      		})
     	 	.done(function( data ) {
			data = JSON.parse( data );
        		$( '#woosea-ajax-table' ).append('<tr><td><input type="hidden" name="rules2[' + data.rowCount + '][rowCount]" value="' + data.rowCount + '"><input type="checkbox" name="record" class="checkbox-field"></td><td><i>Rule:</i></td><td><select name="rules2[' + data.rowCount + '][attribute]" class="select-field">' + data.dropdown + '</select></td><td><select name="rules2[' + data.rowCount + '][condition]" class="select-field"  id="condition_' + data.rowCount + '""><option value="contains">contains</option><option value="containsnot">does not contain</option><option value="=">is equal to</option><option value="!=">is not equal to</option><option value=">">is greater than</option><option value=">=">is greater or equal to</option><option value="<">is less than</option><option value="=<">is less or equal to</option><option value="empty">is empty</option><option value="multiply">multiply</option><option value="divide">divide</option><option value="plus">plus</option><option value="minus">minus</option><option value="replace">replace</option></select></td><td><input type="text" name="rules2[' + rowCount + '][criteria]" class="input-field-large"></td><td><input type="checkbox" name="rules2[' + rowCount + '][cs]" class="checkbox-field" alt="Case sensitive" id="cs_' + data.rowCount + '"></td><td><select name="rules2[' + data.rowCount + '][than_attribute]" class="select-field" id="than_attribute_' + rowCount +'" style="width:150px;">' + data.dropdown + '</select> </td><td><input type="text" name="rules2[' + rowCount + '][newvalue]" class="input-field-large" id="is-field_' + rowCount +'"></td></tr>');

			// Check if user selected a data manipulation condition
			jQuery("#condition_" + rowCount).on("change", function(){

				var manipulators = ['multiply', 'divide', 'plus', 'minus'];
				var cond = $(this).val();	

				// User selected a data manipulation value so remove some input fields
				if(jQuery.inArray(cond, manipulators) != -1){
					jQuery("#than_attribute_" + rowCount).remove();
					jQuery("#is-field_" + rowCount).remove();
					jQuery("#cs_" + rowCount).remove();
				}
			
				// Replace pieces of string	
				var modifiers = ['replace'];
				if(jQuery.inArray(cond, modifiers) != -1){
					jQuery("#than_attribute_" + rowCount).remove();
					jQuery("#cs_" + rowCount).remove();
				}
			});


		        // Check if user created  a Google category rule
			jQuery("#than_attribute_" + rowCount).on("change", function(){

                                if ($(this).val() == "google_category") {
				       var rownr = $(this).closest("tr").prevAll("tr").length;

                                       $("#is-field_" + rowCount).replaceWith('<input type="search" name="rules2[' + rowCount + '][newvalue]" class="input-field-large js-typeahead js-autosuggest autocomplete_' + rowCount + '">');
				
       					jQuery(".js-autosuggest").click(function(){
                				var rowCount = $(this).closest("tr").prevAll("tr").length;

                				jQuery( ".autocomplete_" + rowCount ).typeahead({
                        				input: '.js-autosuggest',
                        				source: google_taxonomy,
                        				hint: true,
                       	 				loadingAnimation: true,
                        				items: 10,
                        				minLength: 2,
                        				alignWidth: false,
                        				debug: true
                				});
                				jQuery( ".autocomplete_" + rowCount ).focus();

                				jQuery(this).keyup(function (){
                        				var minimum = 5;
                        				var len = jQuery(this).val().length;
                        				if (len >= minimum){
                                				jQuery(this).closest("input").removeClass("input-field-large");
                                				jQuery(this).closest("input").addClass("input-field-large-active");
                        				} else {
                                				jQuery(this).closest("input").removeClass("input-field-large-active");
                                				jQuery(this).closest("input").addClass("input-field-large");
                        				}
                				});

                				jQuery(this).click(function (){
                        				var len = jQuery(this).val().length;
                        				if (len < 1){
                                				jQuery(this).closest("input").removeClass("input-field-large-active");
                                				jQuery(this).closest("input").addClass("input-field-large");
                        				}
                				});
        				});
				}
			});
		})
      		.fail(function( data ) {
       		 	console.log('Failed AJAX Call :( /// Return Data: ' + data);
     	 	});	
	});

        // Find and remove selected table rows
        jQuery(".delete-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });
});
