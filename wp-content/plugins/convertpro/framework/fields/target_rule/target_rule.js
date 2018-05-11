;(function ( $, window, undefined ) {

	var init_target_rule_select2  = function( selector ) {
		
		$(selector).cpselect2({

			placeholder: cp_pro.group_filters,

			ajax: {
			    url: cp_admin_ajax.url,
			    dataType: 'json',
			    method: 'post',
			    delay: 250,
			    data: function (params) {
			      	return {
			        	q: params.term, // search term
				        page: params.page,
				        module_type: $( '#cp_module_type' ).val(),
				        action: 'cpro_get_posts_by_query'
			    	};
				},
				processResults: function (data) {
		            // parse the results into the format expected by Select2.
		            // since we are using custom formatting functions we do not need to
		            // alter the remote JSON data

		            return {
		                results: data
		            };
		        },
			    cache: true
			},
			minimumInputLength: 2,
		});
	};

	var update_target_rule_input = function(wrapper) {

		var rule_input 		= wrapper.find('.cp-target_rule-input');

		var old_value = rule_input.val();
		var new_value = [];
		
		wrapper.find('.cp-target-rule-condition').each(function(i) {
			
			var $this 			= $(this);
			var temp_obj 		= {};
			var rule_condition 	= $this.find('select.target_rule-condition');
			var specific_page 	= $this.find('select.target_rule-specific-page');

			var rule_condition_val 	= rule_condition.val();
			var specific_page_val 	= specific_page.val();
			
			if ( '' != rule_condition_val ) {

				temp_obj = {
					type 	: rule_condition_val,
					specific: specific_page_val
				} 
				
				new_value.push( temp_obj );
			};
		})

		var rules_string = JSON.stringify( new_value );
		rule_input.val( rules_string );
	};

	var update_close_button = function(wrapper) {
		
		type 		= wrapper.closest('.cp-target-rule-wrapper').attr('data-type');
		rules 		= wrapper.find('.cp-target-rule-condition');
		show_close	= false;

		if ( 'display' == type ) {
			if ( rules.length > 1 ) {
				show_close = true;
			}
		}else{
			show_close = true;
		}

		rules.each(function() {
			
			if ( show_close ) {
				jQuery(this).find('.target_rule-condition-delete').removeClass('cp-hidden');
			}else{
				jQuery(this).find('.target_rule-condition-delete').addClass('cp-hidden');
			}
		});
	};

	var update_exclusion_button = function( force_show, force_hide ) {
		
		var display_on = $('.cp-target-rule-display-on-wrap');
		var exclude_on = $('.cp-target-rule-exclude-on-wrap');
		
		var exclude_field_wrap = exclude_on.closest('.cp-element-container');
		var add_exclude_block  = $('.target_rule-add-exclusion-rule');
		var exclude_val = exclude_on.find( '.cp-target_rule-input' ).val();

		if ( true == force_hide ) {
			exclude_field_wrap.addClass( 'cp-hidden' );
			add_exclude_block.removeClass( 'cp-hidden' );
		}else if( true == force_show ){
			exclude_field_wrap.removeClass( 'cp-hidden' );
			add_exclude_block.addClass( 'cp-hidden' );
		}else{
			
			if ( '' == exclude_val || '[]' == exclude_val ) {
				exclude_field_wrap.addClass( 'cp-hidden' );
				add_exclude_block.removeClass( 'cp-hidden' );
			}else{
				exclude_field_wrap.removeClass( 'cp-hidden' );
				add_exclude_block.addClass( 'cp-hidden' );
			}
		}
	};

	$(document).ready(function($) {

		jQuery( '.cp-target-rule-condition' ).each( function() {


			var $this 			= $( this ),
				condition 		= $this.find('select.target_rule-condition'),
				condition_val 	= condition.val(),
				specific_page 	= $this.find('.target_rule-specific-page-wrap');

			if( 'specifics' == condition_val ) {
				specific_page.slideDown( 300 );
			} else {
				specific_page.slideUp( 300 );
			}
		} );

		
		jQuery('select.target-rule-select2').each(function(index, el) {
			
			init_target_rule_select2( el );
		});

		jQuery('.cp-target-rule-selector-wrapper').each(function() {
			update_close_button( jQuery(this) );
		})

		/* Show hide exclusion button */
		update_exclusion_button();

		jQuery( document ).on( 'change', '.cp-target-rule-condition select.target_rule-condition' , function( e ) {
			
			var $this 		= jQuery(this),
				this_val 	= $this.val(),
				field_wrap 	= $this.closest('.cp-target-rule-wrapper');

			if( 'specifics' == this_val ) {
				$this.closest( '.cp-target-rule-condition' ).find( '.target_rule-specific-page-wrap' ).slideDown( 300 );
			} else {
				$this.closest( '.cp-target-rule-condition' ).find( '.target_rule-specific-page-wrap' ).slideUp( 300 );
				
				// var sl2 = jQuery( this ).closest( '.cp-element-container' ).find( '.cp-select2-wrap select.select2-group_filters-dropdown' );
				// sl2.val( null );
				// sl2.trigger( 'change' );
			}

			update_target_rule_input( field_wrap );
		} );

		jQuery( '.cp-target-rule-selector-wrapper' ).on( 'change', '.target-rule-select2', function(e) {
			var $this 		= jQuery( this ),
				field_wrap 	= $this.closest('.cp-target-rule-wrapper');

			update_target_rule_input( field_wrap );
		});
		
		jQuery( '.cp-target-rule-selector-wrapper' ).on( 'click', '.target_rule-add-rule-wrap a', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $this 	= jQuery( this ),
				id 		= $this.attr( 'data-rule-id' ),
				new_id 	= parseInt(id) + 1,
				type 	= $this.attr( 'data-rule-type' ),
				rule_wrap = $this.closest('.cp-target-rule-selector-wrapper').find('.target_rule-builder-wrap'),
				template  = wp.template( 'cp-target-rule-condition' ),
				field_wrap 		= $this.closest('.cp-target-rule-wrapper');

			rule_wrap.append( template( { id : new_id, type : type } ) );
			
			init_target_rule_select2( '.cp-target-rule-'+type+'-on .cp-target-rule-'+new_id + ' .target-rule-select2' );
			
			$this.attr( 'data-rule-id', new_id );

			update_close_button( field_wrap );
		});

		jQuery( '.cp-target-rule-selector-wrapper' ).on( 'click', '.target_rule-condition-delete', function(e) {
			var $this 			= jQuery( this ),
				rule_condition 	= $this.closest('.cp-target-rule-condition'),
				field_wrap 		= $this.closest('.cp-target-rule-wrapper');
				cnt 			= 0,
				data_type 		= field_wrap.attr( 'data-type' );

			if ( 'exclude' == data_type && field_wrap.find('.cp-target-rule-condition').length == 1 && '0' == rule_condition.attr('data-rule') ) {
				
				field_wrap.find('.target_rule-condition').val('');
				field_wrap.find('.target_rule-specific-page').val('');
				field_wrap.find('.target_rule-condition').trigger('change');
				update_exclusion_button( false, true );

			}else{
				rule_condition.remove();
			}

			field_wrap.find('.cp-target-rule-condition').each(function(i) {
				var condition 	= jQuery( this )
					old_rule_id = condition.attr('data-rule');
					
				condition.attr( 'data-rule', i );

				condition.removeClass('cp-target-rule-'+old_rule_id).addClass('cp-target-rule-'+i);

				cnt = i;
			});

			field_wrap.find('.target_rule-add-rule-wrap a').attr( 'data-rule-id', cnt )

			update_close_button( field_wrap );
			update_target_rule_input( field_wrap );
		});
		
		jQuery( '.cp-target-rule-selector-wrapper' ).on( 'click', '.target_rule-add-exclusion-rule a', function(e) {
			e.preventDefault();
			e.stopPropagation();
			update_exclusion_button( true );
		});
		
	});

}(jQuery, window));