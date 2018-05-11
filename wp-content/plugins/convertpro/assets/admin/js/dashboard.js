/* CP Insights accordion */

jQuery(document).ready(function() {

	function close_accordion_section() {
		jQuery('.cp-accordion .cp-accordion-section-title').removeClass('active');
		jQuery('.cp-accordion .cp-accordion-section-content').slideUp( 300, function() {
			jQuery(this).removeClass('open');
		});
	}

	jQuery('.cp-accordion-section-title').click( function(e) {
		// Grab current anchor value
		var currentAttrValue = jQuery(this).attr('data-title');
		var currentClass = jQuery(e.target).attr('class');

		if( !jQuery(e.target).is('.dashicons-trash, .dashicons-edit, .cp-edit-campaign-text' ) ) {
			var target = ( jQuery(e.target).is('.cp-accordion-section-title') ) ? e.target : jQuery(e.target).closest('.cp-accordion-section-title');
			target;

			if(jQuery(target).is('.active')) {
				close_accordion_section();
			} else {
				close_accordion_section();

				// Add active class to section title
				jQuery(this).addClass('active');
				// Open up the hidden content panel
				jQuery('.cp-accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
			}
		}
		e.preventDefault();
	});

	/* Fetch Templates */
	jQuery( ".cp-refresh-button" ).on( "click", function(e) {
		e.preventDefault();

		var btn 			= jQuery(this);
		var template_type	= btn.data('modal-type');

		btn.find('i').addClass('cp-reloading-icon');
		
		jQuery.ajax({
			url: cp_ajax.url,
			data: { 
				action: 'cp_v2_refresh',
				template_type: template_type
			},
			type: 'POST',
			dataType:'JSON',
			success:function( data ){
				btn.removeClass('button-secondary').addClass('cp-btn-primary cp-button-style');
				btn.find('i').removeClass('cp-reloading-icon dashicons-update').addClass('dashicons-yes');
				btn.find('span').text( cp_pro.refreshed );
				location.reload();
			},
			error:function(){
				console.log( 'Error' );
			}
		});
	});

	

});

jQuery(document).on( "cpro_switch_change", function(e, selector, name, value ) {

	if( name.indexOf("style_status") >= 0 ) { 

		var style_status = value;
		var style_id = jQuery(selector).data("style");

		jQuery.ajax({
			url:cp_ajax.url,
			data: { 
				action: 'cp_update_style_status', 
				style_id: style_id,
				style_status: style_status
			},
			type: 'POST',
			dataType:'JSON',
			success:function(result){
				console.log(result);	
			},
			error:function(err){
				console.log(err);
			}
		});

	}

	if( name.indexOf("ab_test_status_") >= 0 ) { 

		var ab_test_status_ = value;
		var test_id = jQuery(selector).data("test");

		jQuery.ajax({
			url:cp_ajax.url,
			data: { 
				action: 'cp_update_ab_test_status', 
				test_id: test_id,
				status: ab_test_status_
			},
			type: 'POST',
			dataType:'JSON',
			success:function(result){
				console.log(result);	
			},
			error:function(err){
				console.log(err);
			}
		});

	}

});

/* Edit campaign name*/

jQuery( ".cp-campaign-edit-link" ).click(function() {
  jQuery(this).addClass('open-icon');
  jQuery(this).siblings(".cp-edit-campaign-title").addClass('open');
  jQuery(this).siblings( ".cp-campaign-name" ).addClass('hidden');
  jQuery('.cp-edit-campaign-text').select();
});

jQuery(document).on( "click", ".cp-accordion-section-title", function(e) {

	// Check if not clicked on popup contet

	if( jQuery('.cp-campaign-title-wrap, .cp-switch-wrap').find(e.target).length === 0  ) {
	    jQuery(".cp-campaign-name").removeClass("hidden");
	    jQuery(".cp-campaign-edit-link").removeClass('open-icon');
	}
});

jQuery(document).on('mouseup', 'body:not(#cp-edit-dropdown a)', function(e) {
	if( jQuery('html').hasClass('cp-edit-action-in') ) {
		jQuery('html').removeClass('cp-edit-action-in')
		var el_dropdown = jQuery('#cp-edit-dropdown');
		el_dropdown.removeClass( 'cp-edit-show cp-edit-below cp-edit-above' );
		jQuery('.cp-edit-settings').removeClass('active');
	}
});
/* Edit campaign actions */
jQuery(document).on( "click", ".cp-edit-settings", function(e){

	jQuery('.cp-edit-settings').removeClass('active');
	jQuery('html').addClass('cp-edit-action-in');

	var el_this = jQuery(this);
	
	var el_dropdown = jQuery('#cp-edit-dropdown');

	if (  el_this.data('ab-test') ) {
		el_dropdown.addClass('cp-edit-ab-test');
		el_dropdown.find( '.cp-delete-action .has-tip' ).show();
		el_dropdown.find( '.cp-delete-action .without-has-tip' ).hide();
		el_dropdown.find( '.cp-delete-action' ).data( 'allow-delete', '1' );
	}else{
		el_dropdown.removeClass('cp-edit-ab-test');
		el_dropdown.find( '.cp-delete-action .has-tip' ).hide();
		el_dropdown.find( '.cp-delete-action .without-has-tip' ).show();
		el_dropdown.find( '.cp-delete-action' ).data( 'allow-delete', '0' );
	}

	el_this.addClass('active');

	var el_parent = el_this.closest('.cp-row');
	var el_window = jQuery(window);
	
	var el_parent_top 		= el_parent.offset().top - el_window.scrollTop();
	var el_parent_bottom 	= el_parent_top + el_parent.outerHeight();
	
	var el_dropdown_height = el_dropdown.outerHeight( true );

	var class_css = 'cp-edit-below';
	var right_css = el_window.width() - ( el_this.offset().left + el_this.outerWidth() + 10 );
	var top_css = el_parent_bottom - 10;


	if ( el_parent_top > el_dropdown_height ) {
		class_css = 'cp-edit-above';
		top_css = el_parent_top - el_dropdown_height;
	}

	el_dropdown.addClass(class_css);
	el_dropdown.css({
		'top':top_css+'px',
		'right':right_css+'px',
	});
	el_dropdown.addClass('cp-edit-show');

	el_parent.closest( '.cp-accordion-section' )
	
	el_dropdown.data('post-id', el_parent.data('id') );
	el_dropdown.data('post-name', el_parent.data('name') );
	el_dropdown.data('term', el_parent.closest( '.cp-accordion-section' ).data('term') );
});

var cp_edit_action_modal = function( el_modal ) {

	jQuery(".cp-md-overlay").addClass("cp-show");
	el_modal.addClass("cp-show");
	el_modal.find(".cp-save-animate-container").removeClass("cp-zoomOut").addClass(" cp-animated cp-zoomIn");
}

/* Open popuon dropdown menu click */
jQuery(document).on( "click", "#cp-edit-dropdown a", function(e){

	var $this 		= jQuery(this);
	var el_modal 	= jQuery('.cp-edit-action-modal');
	var el_dropdown = jQuery("#cp-edit-dropdown");
	var post_id 	= el_dropdown.data('post-id');
	var post_name 	= el_dropdown.data('post-name');
	var term 		= el_dropdown.data('term');

	if( !$this.hasClass( 'cp-export-action' ) ) {
		e.preventDefault();
	
		el_modal.removeClass( 'cp-campaign-action cp-rename-action cp-duplicate-action' );
		
		if ( $this.hasClass('cp-campaign-action') ) {
			
			el_modal.addClass( 'cp-campaign-action' );

			var el_modal_inner 	= el_modal.find('.campaign-action'); 
			
			el_modal_inner.find('.select-campaign').val( term );
		 	el_modal_inner.find(".cp_campaign_name").val('');
		 	el_modal_inner.find('.cp-error').text('');
		 	el_modal_inner.find('.cp-error').removeClass( 'cpro-open' );
			
			cp_edit_action_modal( el_modal );
		}
		else if ( $this.hasClass('cp-rename-action') ) {
			
			el_modal.addClass( 'cp-rename-action' );

			var el_modal_inner 	= el_modal.find('.rename-action'); 
			
			el_modal_inner.find("#cp_style_title").val(post_name);
		 	el_modal_inner.find('.cp-error').text('');
		 	el_modal_inner.find('.cp-error').removeClass( 'cpro-open' );
			
			cp_edit_action_modal( el_modal );

			setTimeout(function() {
		 		el_modal_inner.find("#cp_style_title").focus().select();
			}, 200);
		} else if ( $this.hasClass('cp-edit-action') ) {
			
			var edit_link = jQuery('.cp-hidden-action-panel-'+ post_id ).val();
			
			window.location.href = edit_link;

		} else if ( $this.hasClass('cp-duplicate-action') ) {
			
			var post_wrap 	   = jQuery('.cp-row-'+post_id);
			var count	  	   = parseInt( jQuery('.title-count').text() ) + 1;
			var copy_post_name = 'copy of ' + post_name;

			jQuery(".cp-duplicate-btn").text( cp_pro.duplicate );

			setTimeout(function() {
				jQuery("#cp_dup_style_title").val( copy_post_name ).focus().select();
			}, 100 );

			el_modal.addClass( 'cp-duplicate-action' );
			jQuery(".cp-duplicate-btn").removeAttr( 'disabled' );
			cp_edit_action_modal( el_modal );

			
		} else if ( $this.hasClass('cp-delete-action') ) {
			if( $this.data( 'allow-delete' ) != 0 ) {
				return false;
			}

			if( ! confirm( cp_pro.confirm_delete_design ) ) {
				return false;
			}
			
			var post_wrap 	= jQuery('.cp-row-'+post_id);
			var count	  	= parseInt( jQuery('.title-count').text() ) - 1;

			var mainContent = post_wrap.closest( '.cp-accordion-section-content' );
			var otherAccordion = jQuery( '.cp-accordion-section-content' );

			jQuery.ajax({
				url:cp_ajax.url,
				data: { 
					action: 'cp_delete_popup', 
					popup_id: post_id
				},
				type: 'POST',
				dataType:'JSON',
				success:function(result){
					
					if( result.message == 'success' ) {
						
						if( result.message == 'success' ) {
							post_wrap.addClass('cp-delete-wrap');					
							setTimeout(function(){
							 	post_wrap.remove();
							 	if( mainContent.find( '.cp-popup-row' ).length == 0 ) {
							 		mainContent.find('.cp-insights-label-row').remove();
							 		if( otherAccordion.length <= 1 ) {
							 			jQuery( '.cp-no-design' ).removeClass( 'cp-hidden' );
								 		jQuery( '.cp-accordion-section' ).remove();
							 		} else {
							 			mainContent.closest( '.cp-accordion-section' ).remove();
							 		}
								 		
							 	}
						 	}, 400);

							jQuery('.title-count').text( count );
						}
					}
				},
				error:function(err){
					console.log(err);
				}
			});
		}
	}
});

jQuery(document).on( "click", ".cp-cancel-campaign-btn, .cp-cancel-rename-btn", function(e){
	jQuery(".cp-md-overlay").trigger('click');
});

jQuery(document).on( "click", ".cp-save-campaign-btn", function(e){
	e.preventDefault();
	var el_modal 		= jQuery('.cp-edit-action-modal'); 
	var el_modal_inner 	= el_modal.find('.campaign-action'); 

	var el_dropdown 	= jQuery("#cp-edit-dropdown");
	var prev_campaign 	= el_dropdown.data('term');
	var post_id 		= el_dropdown.data('post-id');
	var campaign_id 	= el_modal_inner.find('.select-campaign').val();
	var campaign_name 	= el_modal_inner.find('#cp_campaign_name').val();

	if ( jQuery('.cp-create-campaign.active').length > 0 ) {
		campaign_id = false;
	} 

	if ( prev_campaign == campaign_id ) {
		el_modal_inner.find('.cp-error').text( cp_pro.select_diff_camp )
		el_modal_inner.find('.cp-error').addClass( 'cpro-open' );
		return;
	}
	if ( campaign_id == false && campaign_name == '' ) {
		el_modal_inner.find('.cp-error').text( cp_pro.empty_campaign )
		el_modal_inner.find('.cp-error').addClass( 'cpro-open' );
		return;
	}

	jQuery.ajax({
		url: cp_ajax.url,
		data: { 
			action: 'cp_update_campaign', 
			post_id: post_id,
			campaign_id: campaign_id,
			campaign_name: campaign_name
		},
		type: 'POST',
		dataType:'JSON',
		success:function(result){
			console.log( result );
			if ( result.success ) {
				location.reload();
			}else{
				el_modal_inner.find('.cp-error').text( result.data.message );
				el_modal_inner.find('.cp-error').addClass( 'cpro-open' );
			}
		},
		error:function(err){
			console.log(err);
		}
	});
});

jQuery(document).on( "click", ".cp-save-rename-btn", function(e){
	e.preventDefault();
	
	var el_modal 		= jQuery('.cp-edit-action-modal'); 
	var el_modal_inner 	= el_modal.find('.rename-action'); 

	var el_dropdown 	= jQuery("#cp-edit-dropdown");
	var post_id 		= el_dropdown.data('post-id');
	var prev_name 		= el_dropdown.data('post-name');
	var campaign_name 	= el_modal_inner.find('#cp_style_title').val();

	if ( prev_name == campaign_name ) {
		el_modal_inner.find('.cp-error').text( cp_pro.already_exists_camp )
		el_modal_inner.find('.cp-error').addClass( 'cpro-open' );
		return;
	}

	if ( campaign_name == '' ) {
		el_modal_inner.find('.cp-error').text( cp_pro.empty_design )
		el_modal_inner.find('.cp-error').addClass( 'cpro-open' );
		return;
	}

	jQuery(this).text( cp_pro.saving );

	jQuery.ajax({
		url: cp_ajax.url,
		data: { 
			action: 'cp_rename_popup', 
			popup_id: post_id,
			popup_name: campaign_name
		},
		type: 'POST',
		dataType:'JSON',
		success:function(result){
			console.log(result);
			if( result.success ) {
				location.reload();
			}
		},
		error:function(err){
			console.log(err);
		}
	});
});

jQuery('.cp-edit-action-modal #cp_campaign_name').on('keydown', function(e) {
	
	if ( e.which == 13 ) {
		jQuery('.cp-save-campaign-btn').trigger('click');
	}
})


jQuery('.cp-edit-action-modal #cp_style_title').on('keydown', function(e) {
	
	if ( e.which == 13 ) {
		jQuery('.cp-save-rename-btn').trigger('click');
	}
});

/* Info Modal */

jQuery(document).on( "click", ".cp-info-popup", function() {

	var parentDiv = jQuery(".cp-info-dashboard-modal"),
		settings = jQuery(this).data("settings");

	parentDiv.find(".cp-info-section").html( settings );

    parentDiv.addClass("cp-show");
    jQuery(".cp-md-overlay").addClass("cp-show");

    parentDiv.find(".cp-save-animate-container").removeClass("cp-zoomOut").addClass(" cp-animated cp-zoomIn");

 });

// close popup on click of overlay and close link
jQuery(document).on( "click", ".cp-new-design-modal, .cp-close-popup, .cp-modal-close, .cp-md-overlay, .cp-cancel-btn", function(e) {

	// Check if not clicked on popup contet
	var target_class = jQuery(e.target).attr("class");
		
	if( typeof target_class !== 'undefined' && target_class !== '' ) {
		target_class = target_class.split(' ');
		if( target_class.length > 0 ){
			target_class = target_class[1];
		}	
	}	

	jQuery('.cp-common-modal .cp-md-content').removeClass('cp-zoomIn');

	if( jQuery('.cp-mdl-main-wrap').find(e.target).length === 0 || target_class =='dashicons-no-alt') {
		
		jQuery(".ab-test-cp .cp-save-animate-container").addClass("cp-animated cp-zoomOut");
	
		setTimeout(function() {
	        jQuery(".ab-test-cp .cp-new-design-modal").removeClass("cp-show");

	        jQuery(".ab-test-cp .cp-save-animate-container").addClass("cp-animated cp-zoomOut");
	    }, 200);
	}

	// close google anlytics popup 
	if( jQuery('.cp-ga-modal-wrapper').find(e.target).length === 0 || target_class =='dashicons-no-alt') {
		
		jQuery(".cp-insights-page .cp-save-animate-container").addClass("cp-animated cp-zoomOut");
	
		setTimeout(function() {
	        jQuery(".cp-insights-page .cp-new-design-modal").removeClass("cp-show");

	        jQuery(".cp-insights-page .cp-save-animate-container").addClass("cp-animated cp-zoomOut");
	    }, 200);
	}

	if( typeof target_class !== 'undefined' && ( jQuery('.cp-mdl-main-wrap').find(e.target).length === 0 || target_class =='dashicons-no-alt' ) ) {

		jQuery(".cp-save-animate-container").addClass("cp-animated cp-zoomOut");
	
        jQuery(".cp-md-overlay").removeClass("cp-show");        
		setTimeout(function() {
	        jQuery(".cp-new-design-modal").removeClass("cp-show active");
	        jQuery(".cp-common-modal").removeClass("cp-show active");	
	    }, 200);
	}
});

/* Input field one ui style */

jQuery('.cp-form-input input').on('change',function(){
	var cp_input_check = jQuery(this).val() || 0;
	if( cp_input_check != 0 ) {
		jQuery(this).parents('.cp-form-input').addClass('has-input');
		jQuery('.cp-form-input input').addClass('cp-valid');
	} else {
		jQuery(this).parents('.cp-form-input').removeClass('has-input');
	}
});


jQuery(document).on( "click", ".cp-inline-edit", function(e){

	var style_id = jQuery(this).data("id");

	jQuery(this).siblings(".cp_edit_post_link").hide();

	// open inline edit container
	jQuery("#cp-edit-title-"+ style_id ).addClass('open');
	jQuery( this ).addClass('open-icon');
	jQuery('.cp-edit-popup-text').select();
});


jQuery(document).on( 'mouseup keypress', function (e) {

    var container = jQuery(".cp-edit-popup-title");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0 || e.which == 13 ) // ... nor a descendant of the container
    {
    	// if popup name edit is in progress
    	if( jQuery(".cp-edit-popup-title.open").length > 0 ) {

	    	jQuery(".cp-edit-popup-title.open").siblings(".cp_edit_post_link").show();	
	    	var popup_id = jQuery(".cp-edit-popup-title.open").siblings(".cp-inline-edit").data('id');	
	    	var popup_name = jQuery(".cp-edit-popup-title.open input").val();

	    	var editLink = jQuery(".cp-edit-popup-title.open").siblings(".cp_edit_post_link");
	    	var old_text = editLink.html();
	        
	        editLink.html(popup_name);

	        var infoSpan = jQuery(".cp-edit-popup-title.open").closest( '.cp-popup-row' ).find( '.cp-view-insight span:first a' );
	        
	        var data_settings = infoSpan.data( 'settings' );
	        var new_data = data_settings.replace( old_text, popup_name );

	        infoSpan.data( 'settings', new_data );

	        jQuery(".cp-edit-popup-title.open").removeClass('open');
	        jQuery(".cp-inline-edit").removeClass('open-icon');
	        jQuery(".cp-inline-edit[data-id='"+ popup_id +"']").siblings(".cp_edit_post_link").text('.....');

	        jQuery.ajax({
				url: cp_ajax.url,
				data: { 
					action: 'cp_rename_popup', 
					popup_id: popup_id,
					popup_name: popup_name
				},
				type: 'POST',
				dataType:'JSON',
				success:function(result){
					if( result.success == true ) {
						jQuery(".cp-inline-edit[data-id='"+ popup_id +"']").siblings(".cp_edit_post_link").text(result.data.new_title);
					}
				},
				error:function(err){
					console.log(err);
				}
			});
		}

	}

	var container = jQuery(".cp-edit-campaign-title");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0 || e.which == 13 ) // ... nor a descendant of the container
    {
		// if campaign edit is in progress
		if( jQuery(".cp-edit-campaign-title.open").length > 0 ) {

	    	jQuery(".cp-edit-campaign-title.open").siblings(".cp_edit_post_link").show();	
	    	var campaign_id = jQuery(".cp-edit-campaign-title.open").siblings(".cp-campaign-edit-link").data('id');	
	    	var campaign_name = jQuery(".cp-edit-campaign-title.open input").val();
	        jQuery(".cp-edit-campaign-title.open").removeClass('open');
	        jQuery(".cp-campaign-name.hidden").removeClass('hidden');
	        jQuery(".cp-campaign-edit-link[data-id='"+ campaign_id +"']").siblings(".cp-campaign-name").text('.....');
	        jQuery.ajax({
				url: cp_ajax.url,
				data: { 
					action: 'cp_rename_campaign', 
					campaign_id: campaign_id,
					campaign_name: campaign_name
				},
				type: 'POST',
				dataType:'JSON',
				success:function(result){
					if( result.success == true ) {
						jQuery(".cp-campaign-edit-link[data-id='"+ campaign_id +"']").siblings(".cp-campaign-name").text(result.data.new_title);
					}
				},
				error:function(err){
					console.log(err);
				}
			});
		}

    }
});

jQuery(document).on( "click", ".cp-delete-campaign", function(e) {

	var message = jQuery(this).data("notice");

	if( !confirm( message ) ) {
		return false;
	} else {
		var redirect_url = jQuery(this).attr("href");
		window.location = redirect_url;
	}
});

// Toggle class for completed AB test 

jQuery('.cp-completed-test').addClass('cp-hidden');

jQuery(".cp-test-toggle").click(function(){

	jQuery('.cp-completed-test').slideToggle('cp-hidden');
	
});

// Hide error message on focus of design name input.
jQuery(document).on( 'focus', '#cp_dup_style_title', function() {
	jQuery(".duplicate-action .cp-form-error .cp-error").removeClass("cpro-open");
});

jQuery(document).on( "click", ".cp-duplicate-btn", function(e) {
	e.preventDefault();
	
	var el_dropdown     = jQuery("#cp-edit-dropdown");
	var el_modal 		= jQuery('.cp-edit-action-modal'); 
	var el_modal_inner 	= el_modal.find('.duplicate-action'); 
	var post_id 	    = el_dropdown.data('post-id');
	var post_wrap 	    = jQuery( '.cp-row-' + post_id );
	var count	  		= parseInt( jQuery('.title-count').text() ) + 1;
	var popup_name  	= jQuery( "#cp_dup_style_title" ).val();

	if ( popup_name == '' ) {
		el_modal_inner.find('.cp-error').text( cp_pro.empty_design )
		el_modal_inner.find('.cp-error').addClass( 'cpro-open' );
		return;
	}	

	jQuery(this).text( cp_pro.duplicating );
	jQuery(this).attr( 'disabled', 'disabled' );

	jQuery.ajax({
		url:cp_ajax.url,
		data: { 
			action: 'cp_duplicate_popup', 
			popup_id: post_id,
			popup_name: popup_name
		},
		type: 'POST',
		dataType:'JSON',
		success:function(result){

			var popup_id = result.style_id;

			if( result.message == 'success' ) {
				
				setTimeout(function(){
				 	post_wrap.before( result.html );
					jQuery(".cp-popup-row[data-id="+ popup_id +"]").addClass('cp-duplicated-wrap');

					if( 'undefined' !== typeof result.module_type ) {
						var module_type = result.module_type;
						jQuery(".cp-row-" + popup_id ).find( '.cp-module-type-container' ).text( module_type );
					}

			 	}, 200);

				setTimeout(function(){
					jQuery(".cp-popup-row[data-id="+ popup_id +"]").removeClass('cp-duplicated-wrap');
			 	}, 800);

				jQuery('.title-count').text( count );
				jQuery('#cp_dup_style_title').val('');
				jQuery(".cp-md-overlay").trigger('click');
			}

		},
		error:function(err){
			console.log(err);
		}
	});
	
});

/* Template Actions */
jQuery( document ).ready(function($) {
	/* Fetch Templates */
	$( ".cp-refresh-templates" ).on( "click", function(e) {
		e.preventDefault();

		var btn 			= jQuery(this);
		var template_type	= btn.data('modal-type');

		// btn.find('i').addClass('cp-reloading-icon');
		btn.text('Refreshing...');
		
		jQuery.ajax({
			url: cp_ajax.url,
			data: { 
				action: 'cp_v2_refresh',
				template_type: template_type
			},
			type: 'POST',
			dataType:'JSON',
			success:function( data ){
				location.reload();
			},
			error:function(){
				console.log( 'Error' );
			}
		});
	});

	/* Download Template and Show Popup */
	$(".cp-template-select").on( "click", function(e) {
		var btn = $(this);
		var template_style  = btn.closest('.cp-template-style');
		var template_id 	= template_style.attr('data-id');
		var template_type 	= template_style.attr('data-modal-type');
		var template_name	= template_style.attr('data-template-name');
		var download_status	= btn.attr('data-download');
		
		template_style.addClass('active');
		btn.addClass('active');
		
		if ( download_status == 'yes'  ) {
			cp_show_create_popup();
			template_style.removeClass('active');
			// btn.removeClass('active');
		} else {
			
			btn.text('loading...');
			
			$( ".cp-template-select" ).each(function( index ) {
				$(this).attr( 'disabled', 'disabled');
			});

			action_data = { 
				action: 'cp_v2_download',
				template_id: template_id,
				template_type: template_type
			};
			
			jQuery.ajax({
				url: cp_ajax.url,
				data: action_data,
				type: 'POST',
				dataType:'JSON',
				success:function( result ){
					
					template_style.removeClass('active');
					// btn.removeClass('active');
					
					btn.text('SELECT');
					
					if( result.success == false ) {
						var message = result.data.message;
						$(".cp-template-style").each(function(i) {
							var this_style = $(this);
							var is_downloaded = this_style.find('.cp-template-select').attr('data-download');
							
							if ( is_downloaded == 'no' ) {
								this_style.find('.cp-templated-error').html( message ).removeClass("cp-hidden");
							}
							
						});

						$( ".cp-template-select" ).each(function( index ) {
							$(this).removeAttr( 'disabled' );
						});
						return false;
	 				}

					if( result.status == 'success' ) {
						btn.attr('data-download', 'yes'),
						$( ".cp-template-select" ).each(function( index ) {
							$(this).removeAttr( 'disabled' );
						});

						cp_show_create_popup();
					} else {
						btn.find('span').text( cp_pro.try_again );
						$( ".cp-template-select" ).each(function( index ) {
							$(this).removeAttr( 'disabled' );
						});
					}
				},
				error:function(){
					btn.text('SELECT');
					console.log( 'Error' );
				}
			});
		}
	});

	// close popup on click of overlay and close link
	$(document).on( "click", ".cp-common-modal .cp-cancel-btn, .cp-md-overlay", function(e) {

		$('.cp-template-select').removeClass('active');
	});
	
	$( ".cp-template-sort" ).on( "change", function(e) {
		
		$category = $(this).val();
		
		if ( $category != 'all' ) {

			$('.cp-template-style').hide();
			$('.cp-template-style[data-popup-category*="'+$category+'"]' ).show();
		}else{
			$('.cp-template-style' ).show();
		}
	});

	$(document).on( "click", ".cp-create-template-popup", function(e) {
		if( ! jQuery( this ).hasClass( 'cp-disable' ) ) {
			var module_type = jQuery(this).data("type");
			jQuery( this ).addClass( 'cp-disable' )
			cp_use_template( module_type );
		}
	});

	$(document).on("click", ".cp-create-campaign", function() {
		jQuery(".cp-select-campaign").removeClass("active");
		jQuery(this).addClass("active");
		if( jQuery(this).attr("data-id") ) {
		// if( jQuery(this).val() == "create-new" ) {
			jQuery(".cp-campaign-title-section").removeClass('cp-hidden');
			jQuery("#cp-campaign-list").addClass('cp-hidden');	
		} 

		jQuery('#cp_campaign_name').focus();

	});

	jQuery(document).on("click", ".cp-select-campaign", function() {
		jQuery(".cp-create-campaign").removeClass("active");
		jQuery(this).addClass("active");
		if( jQuery(this).attr("data-id") ) {
			jQuery("#cp-campaign-list").removeClass('cp-hidden');	
			jQuery(".cp-campaign-title-section").addClass('cp-hidden');
		} 
		jQuery('.select-campaign').focus();
	});

	function cp_show_create_popup() {

		$("#cp_style_title").val('');
		var overlay = $(".cp-md-overlay");
	    var modal = $(".cp-common-modal");
	    overlay.addClass("cp-show");
	    modal.addClass("cp-show");
		$(".cp-save-animate-container").removeClass("cp-animated cp-zoomOut");
	    $(".cp-save-animate-container").addClass("cp-animated cp-zoomIn");

	    setTimeout(function() {
			$("#cp_style_title").focus();
	    }, 200);
	}
	
	function cp_use_template( module_type ) {
			
		var btn 			= $(".cp-template-select.active"),
			template_type 	= module_type == '' ? 'modal_popup' : module_type,
			template_id 	= btn.closest('.cp-template-style').data('id'),
			template_name	= btn.closest('.cp-template-style').data('template-name'),
			create_btn      = $(".cp-create-template-popup");
			create_string   = create_btn.text().trim();
		var style_title 	= $("#cp_style_title").val();

		if ( '' == style_title ) {

			$('.cp-error').html('Design name cannot be empty').addClass('cpro-open');
			$( '.cp-create-template-popup' ).removeClass( 'cp-disable' );
			return false;
		}
		
		$('.cp-error').html('').removeClass('cpro-open');

		create_btn.text( cp_pro.creating );

		action_data = { 
			action: 'cp_v2_use_this',
			template_id: template_id,
			template_type: template_type,
			template_name: template_name,
			style_title: style_title
		};

		jQuery.ajax({
			url: cp_ajax.url,
			data: action_data,
			type: 'POST',
			dataType:'JSON',
			success:function( result ){

				if( result.success == false ) {
					var message = result.data.message;
					$(".cp-error").html( message ).removeClass("cp-hidden");
					return false;
 				}

				if( result.status == 'success' ) {
					var url = result.redirect.replace("&amp;", "&");
						url += '&save_now=true';
					window.location.href = url;
				}
			},
			error:function(){
				console.log( 'Error' );
			}
		});
	}

	/* Create popup on enter */
	$('.cp-create-template-modal #cp_style_title').on('keydown', function(e) {
		
		if ( e.which == 13 ) {
			$('.cp-create-template-popup').trigger('click');
		}
	})

	/* Delete local template data */
	$(".cp-remove-local-templates").on( "click", function(e) {
		e.preventDefault();

		var btn = jQuery(this);

		btn.text( cp_pro.deleting );
		
		jQuery.ajax({
			url: cp_ajax.url,
			data: { 
				action: 'cp_v2_remove_data'
			},
			type: 'POST',
			dataType:'JSON',
			success:function( data ){
				location.reload();
			},
			error:function(){
				console.log( 'Error' );
			}
		});
	});

	jQuery('.cp-gen-set-tabs nav a').on('click', function() {
	    show_content(jQuery(this).index());
	});

	show_content(0);

	function show_content(index) {

		// Make the content visible
		jQuery('.cp-gen-set-tabs .cp-gen-set-content.visible').removeClass('visible');
		jQuery('.cp-gen-set-tabs .cp-gen-set-content:nth-of-type(' + (index + 1) + ')').addClass('visible');

		// Set the tab to selected
		jQuery('.cp-gen-set-tabs nav a.selected').removeClass('selected');
		jQuery('.cp-gen-set-tabs nav a:nth-of-type(' + (index + 1) + ')').addClass('selected');
	}
	
	/* General Setting Tab */

	jQuery('.cp-gen-set-tabs nav a').on('click', function() {
		show_content(jQuery(this).index());
		jQuery('input[name="curr_tab"]').val(jQuery(this).index());
	});

	var tab = 0;
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	
	urlString = window.location.href;
	currTab = urlString.split('#');

	var tab = 0;
	if( typeof currTab[1] !== 'undefined' ) {
		tab = jQuery(".cp-gen-set-menu a[href='#" + currTab[1] + "']").index();
	}

	tab = ( tab == -1 ) ? 0 : tab;

	for (var i = 0; i < sURLVariables.length; i++)
	{
	    var sParameterName = sURLVariables[i].split('=');
	    if (sParameterName[0] == 'tab') 
	    {
	        tab = sParameterName[1];
	    }
	}

	show_content(parseInt(tab));
	
	function show_content(index) {
	    // Make the content visible
	    jQuery('.cp-gen-set-tabs .cp-gen-set-content.visible').removeClass('visible');
	    jQuery('.cp-gen-set-tabs .cp-gen-set-content:nth-of-type(' + (index + 1) + ')').addClass('visible');

	    // Set the tab to selected
	    jQuery('.cp-gen-set-tabs nav a.selected').removeClass('selected');
	    jQuery('.cp-gen-set-tabs nav a:nth-of-type(' + (index + 1) + ')').addClass('selected');
	  }


		jQuery(function() {
			jQuery(document).on('click', '.cp-switch-btn[data-id="cp_enable_comment_form"]', function(e){
				if(jQuery('input[name="cp_enable_comment_form"]').val() == 0){
					jQuery(this).closest('tr').siblings().hide();
				}
				else {
					jQuery(this).closest('tr').siblings().show();
				}
			});
		});

		if(jQuery('input[name="cp_enable_comment_form"]').val() == 0){
			jQuery('.cp-switch-btn[data-id="cp_enable_comment_form"]').closest('tr').siblings().hide();
		}
		else {
			jQuery('.cp-switch-btn[data-id="cp_enable_comment_form"]').closest('tr').siblings().show();
		}

	jQuery('.cp-settings-form').on('submit', function(e) {

		var urlString = window.location.href;
		var currTab = urlString.split('#');
		var has_redirect = false;

		// if saving advanced settings, redirect 
		if( 'undefined' !== typeof currTab[1] && 'advanced' == currTab[1] ) {
			has_redirect = true;
		}
		
		jQuery('.cp-submit-settings').text( cp_pro.saving );
		e.preventDefault();
		formData = jQuery(this).serializeArray();
		jQuery.ajax({
			url: ajaxurl,
			data: { 
				action: 'bsf_save_settings',
				data: formData,
				has_redirect: has_redirect,
				security: jQuery( '#cp-update-settings-nonce' ).val() 
			},
			type: 'POST',
			dataType:'JSON',
			success:function( responce ){
				setTimeout(function() {
					jQuery('.cp-submit-settings').text( cp_pro.saved );
					jQuery('.cp-submit-settings').append('<span class="dashicons-yes dashicons"></span>');
				}, 1000);
				setTimeout(function() {
					jQuery('.cp-submit-settings').text( cp_pro.save_changes );
				}, 2000);

				// if same URL to redirect, just reload the page 
				if( window.location.href.indexOf( responce.data.redirect ) !== -1 ) {
					location.reload();
				}

				if( responce.data.redirect ) {
					window.location.href = responce.data.redirect;
				}
			},
			error:function(){
				console.log( 'Error' );
			}
		});
	});

	$( document ).on( 'click', '.cp-switch-btn', function( e ) {
		var id = $( this ).data( 'id' );

		if( id == 'cpro_branding_enable_kb' ) {
			setTimeout( function() {
				if( $( 'input[name=' + id + ']' ).val() == '1' ) {
					$( '.cpro_branding_url_kb-row' ).removeClass( 'cp-hidden' );
				} else {
					$( '.cpro_branding_url_kb-row' ).addClass( 'cp-hidden' );
				}
			}, 200 );
		}

		if( id == 'cpro_branding_enable_support' ) {
			setTimeout( function() {
				if( $( 'input[name=' + id + ']' ).val() == '1' ) {
					$( '.cpro_branding_url_support-row' ).removeClass( 'cp-hidden' );
				} else {
					$( '.cpro_branding_url_support-row' ).addClass( 'cp-hidden' );
				}
			}, 200 );
		}
	} );


	$(document).on( "click", ".cp-refresh_html", function(e) {

		e.preventDefault();
		var $this = jQuery(this);

		$this.attr( 'disabled', true );
		$this.text( cp_ajax.loading_txt );
		
		jQuery.ajax({
			url: ajaxurl,
			data: { 
				action: 'cp_refresh_html',
				cp_nonce: cp_ajax.ajax_nonce
			},
			type: 'POST',
			dataType:'JSON',
			success:function( responce ){

				setTimeout(function() {
					$this.removeAttr( 'disabled' );
					$this.text( cp_ajax.cleared_cache );
					$this.append('<span class="dashicons-yes dashicons"></span>');
				}, 600);

				setTimeout(function() {
					$this.text( cp_ajax.refresh_btn_txt );
				}, 2000 );
			},
			error:function(){
				console.log( 'Error' );
			}
		});
	});


	function setup_global_fonts( set_option ) {
		
		$font_field 		= $('.cp-global-font-field');
		$font_family_select = $font_field.find( '.cp-font-family' );
		$font_weight_select = $font_field.find( '.cp-font-weights' );

		if ( $font_field.length > 0 && set_option == true ) {

			var font_weights 	= $font_family_select.find(':selected').data('weight');
			
			if( typeof font_weights !== 'undefined' ) {
			 	var font_weights_array =  font_weights.split(",");
				var weight_options     =  '';

				jQuery.each( font_weights_array, function(index, val) {
			
					var selected = '';
					
					if ( val == 'Normal' ) {
						selected = 'selected=\'selected\'';

					};
					weight_options += "<option value='"+ val +"' "+selected+">"+val+"</option>";
				});
			}	

			$font_weight_select.html(weight_options);	
		}


		var font_family = $font_family_select.val();
		var font_weight = $font_weight_select.val();

		$font_field.find('#cp_global_font').val( font_family + ":" + font_weight );
	}

	$( ".cp-font-family" ).on( "change", function(e) {
		setup_global_fonts( true );
	});


	$( ".cp-font-weights" ).on( "change", function(e) {
		setup_global_fonts( false );
	});

	jQuery(document).on( "click", ".cp-close-wrap", function(e) {
	    jQuery(".cp-md-overlay").trigger('click');
	});

});