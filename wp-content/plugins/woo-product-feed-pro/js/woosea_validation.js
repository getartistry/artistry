jQuery(document).ready(function($) {

	// Disable submit button, will only enable if all fields validate
	$('#goforit').attr('disabled',true);

	// Validate woosea installment months
        $( "#_woosea_installment_months" ).blur("input", function(){
		var input=$(this);
		var re = /^[0-9]*$/;
		var woosea_installment_months=re.test(input.val());
		// Check for allowed characters
		if (!woosea_installment_months){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-month is-dismissible'><p>Sorry, only numbers are allowed for the installment month field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-month').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea installment amount
        $( "#_woosea_installment_amount" ).blur("input", function(){
		var input=$(this);
		var re = /^[0-9]*$/;
		var woosea_installment_amount=re.test(input.val());
		// Check for allowed characters
		if (!woosea_installment_amount){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-amount is-dismissible'><p>Sorry, only numbers are allowed for the installment amount field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-amount').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea GTIN field
        $( "#_woosea_gtin" ).blur("input", function(){
		var input=$(this);
		var re = /^[0-9]*$/;
		var woosea_gtin=re.test(input.val());
		// Check for allowed characters
		if (!woosea_gtin){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-gtin is-dismissible'><p>Sorry, only numbers are allowed for the GTIN field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-gtin').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea MPN field
        $( "#_woosea_mpn" ).blur("input", function(){
		var input=$(this);
		var re = /^[a-zA-Z0-9-_]*$/;
		var woosea_mpn=re.test(input.val());
		// Check for allowed characters
		if (!woosea_mpn){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-mpn is-dismissible'><p>Sorry, only numbers are allowed for the MPN field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-mpn').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea UPC field
        $( "#_woosea_upc" ).blur("input", function(){
		var input=$(this);
		var re = /^[0-9]*$/;
		var woosea_upc=re.test(input.val());
		// Check for allowed characters
		if (!woosea_upc){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-upc is-dismissible'><p>Sorry, only numbers are allowed for the UPC field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-upc').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea EAN field
        $( "#_woosea_ean" ).blur("input", function(){
		var input=$(this);
		var re = /^[0-9]*$/;
		var woosea_ean=re.test(input.val());
		// Check for allowed characters
		if (!woosea_ean){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-ean is-dismissible'><p>Sorry, only numbers are allowed for the EAN field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-ean').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea Brand field
        $( "#_woosea_brand" ).blur("input", function(){
		var input=$(this);
		var re = /^[a-zA-Z0-9-_. ]*$/;
		var woosea_brand=re.test(input.val());
		// Check for allowed characters
		if (!woosea_brand){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-brand is-dismissible'><p>Sorry, only letters, numbers, whitespaces, -, . and _ are allowed for the brand field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-brand').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea unit pricing base measure field
        $( "#_woosea_unit_pricing_base_measure" ).blur("input", function(){
		var input=$(this);
		var re = /^[a-zA-Z0-9-_. ]*$/;
		var woosea_unit_pricing_base_measure=re.test(input.val());
		// Check for allowed characters
		if (!woosea_unit_pricing_base_measure){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-unit-pricing-base-measure is-dismissible'><p>Sorry, only letters, numbers, whitespaces, -, . and _ are allowed for the unit pricing base measure field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-unit-pricing-base-measure').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate woosea unit pricing measure field
        $( "#_woosea_unit_pricing_measure" ).blur("input", function(){
		var input=$(this);
		var re = /^[a-zA-Z0-9-_. ]*$/;
		var woosea_unit_pricing_measure=re.test(input.val());
		// Check for allowed characters
		if (!woosea_unit_pricing_measure){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-unit-pricing-measure is-dismissible'><p>Sorry, only letters, numbers, whitespaces, -, . and _ are allowed for the unit pricing measure field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-unit-pricing-measure').remove();
			$('#publish').attr('disabled',false);
		}	
	});




	// Validate woosea Optimized title field
        $( "#_woosea_optimized_title" ).blur("input", function(){
		var input=$(this);
		var re = /^[a-zA-Z0-9-_.àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ]*$/;
		var woosea_optimized_title=re.test(input.val());
		// Check for allowed characters
		if (!woosea_optimized_title){
			$('.notice').replaceWith("<div class='notice notice-error woosea-notice-optimized-title is-dismissible'><p>Sorry, only letters, numbers, whitespaces, -, . and _ are allowed for the optimized title field.</p></div>");
			// Disable submit button too
			$('#publish').attr('disabled',true);
		} else {
			$('.woosea-notice-optimized-title').remove();
			$('#publish').attr('disabled',false);
		}	
	});

	// Validate project name
        $( "#projectname" ).blur("input", function(){
		var input=$(this);
		var re = /^[a-zA-Z0-9-_. ]*$/;
		var minLength = 3;
		var maxLength = 30;
		var is_projectname=re.test(input.val());
		// Check for allowed characters
		if (!is_projectname){
			$('.notice').replaceWith("<div class='notice notice-error is-dismissible'><p>Sorry, only letters, numbers, whitespaces, -, . and _ are allowed for the projectname</p></div>");
			// Disable submit button too
			$('#goforit').attr('disabled',true);
		} else {
			// Check for length of projectname
			var value = $(this).val();
			if (value.length < minLength){
				$('.notice').replaceWith("<div class='notice notice-error is-dismissible'><p>Sorry, your project name needs to be at least 3 characters long.</p></div>");
				// Disable submit button too
			    	$('#goforit').attr('disabled',true);
			} else if (value.length > maxLength){
				// Disable submit button too
			    	$('#goforit').attr('disabled',true);
				$('.notice').replaceWith("<div class='notice notice-error is-dismissible'><p>Sorry, your project name cannot be over 30 characters long.</p></div>");
			} else {
				$('.notice').replaceWith("<div class='notice notice-info is-dismissible'><p>Please select the country and channel for which you would like to create a new product feed. The channel drop-down will populate with relevant country channels once you selected a country. Filling in a project name is mandatory.</p></div>");
				//$('.notice').remove();
			    	// Enable submit button
				$('#goforit').attr('disabled',false);
			}
		}
	});

	// Validate ruling values
        $( "#rulevalue" ).blur("input", function(){
		var input=$(this);
		var minLength = 1;
		var maxLength = 200;
		var value = $(this).val();
		
		if (value.length < minLength){
			$('#rulevalueerror').append("<div id='woo-product-feed-pro-errormessage'>Sorry, minimum length is 1 charachter</div>");
			// Disable submit button too
			$('#goforit').attr('disabled',true);
		} else if (value.length > maxLength){
			// Disable submit button too
			$('#goforit').attr('disabled',true);
			$('#rulevalueerror').append("<div id='woo-product-feed-pro-errormessage'>Sorry, this value cannot be over 200 characters long.</div>");
		} else {
			$('#errormessage').remove();
			// Enable submit button
			$('#goforit').attr('disabled',false);
		}
	});
});
