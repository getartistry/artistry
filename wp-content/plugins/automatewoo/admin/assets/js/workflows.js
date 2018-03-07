/**
 * AutomateWoo Workflows Admin
 */

(function( $, data ) {

	AW.Workflow = Backbone.Model.extend({

		getAction: function( action_name ) {
			var actions = AW.workflow.get('actions');

			if ( actions[action_name] ) {
				return actions[action_name];
			}
		}

	});


	AW.WorkflowView = Backbone.View.extend({

		el: $( 'form#post' ),

		$triggerSelect: $('.js-trigger-select'),

		$triggerDescription: $('.js-trigger-description'),


		initialize: function() {

			this.listenTo( this.model, 'change:trigger', this.changeTrigger );

			this.model.set( 'prevTrigger', this.$triggerSelect.val() );

			this.insertMetaboxHelpTips();
		},


		changeTrigger: function() {

			AW.rules.resetAvailableRules();

            $(document.body).trigger('wc-enhanced-select-init');
			AW.initTooltips();
			AW.Validate.validateAllFields();

			if ( AutomateWoo.Workflows.trigger_compatibility_warning() ){
				AW.workflowView.completeTriggerChange();
			}
		},


		completeTriggerChange: function() {

			AW.rules.clearIncompatibleRules();
			AutomateWoo.Workflows.clearIncompatibleActions();

			AutomateWoo.Modal.close();

			AutomateWoo.Workflows.refine_variables();
			AutomateWoo.Workflows.refine_action_selects();
			AutomateWoo.Workflows.maybe_disable_queueing();

			this.updateTriggerDescription();

			// update the prev trigger value
			this.model.set( 'prevTrigger', this.$triggerSelect.val() );
		},


		cancelTriggerChange: function() {
			this.$triggerSelect.val( this.model.get('prevTrigger') ).trigger('change');
		},


		/**
		 * Update the trigger description
		 */
		updateTriggerDescription: function() {
			var trigger = this.model.get('trigger');

			if ( trigger && trigger.description ) {
				this.$triggerDescription.html( '<p class="aw-field-description">' + trigger.description + '</p>' );
			}
			else {
				this.$triggerDescription.html('');
			}
		},



		initAction: function( action_name, $action ) {

			var data = this.model.getAction( action_name );

			if ( data ) {
				$action.attr( 'data-automatewoo-action-name', action_name );
				$action.attr( 'data-automatewoo-action-group', data.group );
				$action.attr( 'data-automatewoo-action-can-be-previewed', data.can_be_previewed );
			}

			// update dynamic fields
			$action.find( '[data-automatewoo-dynamic-select]' ).each(function( i, el ){
				AW.workflowView.initDynamicActionSelect( $(el), $action );
			});

			AW.initTooltips();

		},


		initDynamicActionSelect: function( $field, $action ) {

			var reference_field_name = $field.attr( 'data-automatewoo-dynamic-select-reference' );
			var $reference_field = $action.find( '.automatewoo-field[data-name="' + reference_field_name + '"]' );

			$reference_field.change(function() {
				AW.workflowView.updateDynamicActionSelect( $field, $reference_field, $action )
			});
		},


		updateDynamicActionSelect: function( $field, $reference_field, $action ) {

			if ( $field.is( '.automatewoo-field--loading' ) ) {
				return;
			}

			// remove existing options
			$field.find( "option[value!='']" ).remove();

			if ( ! $reference_field.val() ) {
				return;
			}

			var $fieldRow = $field.parents( '.automatewoo-table__row' );
			$fieldRow.addClass( 'automatewoo-field-row--loading' );

			var data = {
				action: 'aw_update_dynamic_action_select',
				action_name: $action.data( 'automatewoo-action-name' ),
				target_field_name: $field.attr( 'data-name' ),
				reference_field_value: $reference_field.val()
			};

			$.post( ajaxurl, data, function( response ) {
				$fieldRow.removeClass( 'automatewoo-field-row--loading' );

				if ( response.success ) {
					$.each( response.data, function ( value, text ) {
						$field.append( $('<option/>', {
							value: value,
							text: text
						}));
					});
				}

			});
		},


		/**
		 * Add helplinks to top of meta box
		 */
		insertMetaboxHelpTips: function() {
			var tips = this.model.get('metaBoxHelpTips');

			_.each( tips, function( tip_html, id ) {
				$metabox = $( '#aw_' + id );
				$metabox.find('h2.hndle').append(tip_html);
			});
		}


	});



	AW.TriggerCompatibilityModalView = Backbone.View.extend({

		className: 'aw-view-trigger-compatibility-modal',

		template: wp.template('aw-trigger-compatibility-modal'),

		data: null,

		initialize: function( data ) {
			this.data = data;

			this.$el.on('click', '.js-confirm', function(){
				AW.workflowView.completeTriggerChange()
			});

			this.$el.on('click', '.js-close-automatewoo-modal', function(){
				AW.workflowView.cancelTriggerChange()
			});
		},

		render: function() {
			this.$el.html( this.template( this.data ));
			return this;
		}

	});


	AW.workflow = new AW.Workflow( data );

	AW.workflowView = new AW.WorkflowView({
		model: AW.workflow
	});

	AW.Validate.init();


})( jQuery, automatewooWorkflowLocalizeScript );




// Remove sortable so it doesn't break wp-editors
jQuery('.meta-box-sortables').removeClass('meta-box-sortables');


jQuery(function($) {


	AutomateWoo.Workflows = {

		$triggers_box: $('#aw_trigger_box'),

		$actions_box: $('#aw_actions_box'),

		$actions_container: $('.aw-actions-container'),

		$action_template: $('.aw-action-template'),

		$trigger_select: $('.js-trigger-select').first(),


		init: function() {
			AutomateWoo.Workflows.init_triggers_box();
			AutomateWoo.Workflows.init_actions_box();
			AutomateWoo.Workflows.init_options_box();
		},



		/**
		 *
		 */
		init_triggers_box: function() {
			AutomateWoo.Workflows.$trigger_select.change(function(){
				AutomateWoo.Workflows.fill_trigger_fields( $(this).val() );
			});
		},


		/**
		 *
		 */
		init_actions_box: function() {


			$('.automatewoo-action.js-open').each(function(){
				AutomateWoo.Workflows.action_edit_open( $(this) );
			});


			$('.js-aw-add-action').click(function (e) {
				e.preventDefault();
				AutomateWoo.Workflows.add_new_action();
			});

			$(document).on('click', '.js-edit-action', function (e) {
				e.preventDefault();

				var $action = $(this).parents('.automatewoo-action').first();

				if ($action.is('.js-open')) {
					AutomateWoo.Workflows.action_edit_close($action);
				}
				else {
					AutomateWoo.Workflows.action_edit_open($action);
				}
			});

			$(document).on('click', '.js-delete-action', function (e) {
				e.preventDefault();
				var $action = $(this).parents('.automatewoo-action').first();
				AutomateWoo.Workflows.action_delete($action);
			});

			// Action select change
			$(document).on('change', '.js-action-select', function () {
				var $action = $(this).parents('.automatewoo-action').first();
				AutomateWoo.Workflows.fill_action_fields( $action, $(this).val() );
			});

			// preview links
			$(document).on('click', '[data-automatewoo-preview]', function(e){
				e.preventDefault();
				var $action = $(this).parents('.automatewoo-action').first();
				AutomateWoo.Workflows.preview_action($action);
			});


			if ( ! AW.workflow.get('isNew') ) {
				AutomateWoo.Workflows.refine_action_selects();
				AutomateWoo.Workflows.refine_variables();
				AutomateWoo.Workflows.maybe_disable_queueing();

				$('.automatewoo-action').each(function( i, el ) {
					AW.workflowView.initAction( $(el).find('.js-action-select').val(), $(el) );
				});

			}
		},



		init_options_box: function() {

			var $checkbox_click_tracking = $('.aw-checkbox-enable-click-tracking');

			AutomateWoo.Workflows.maybe_hide_tracking_options();

			$checkbox_click_tracking.click(function(){
				AutomateWoo.Workflows.maybe_hide_tracking_options();
			});

		},



		maybe_hide_tracking_options: function() {

			var $checkbox_click_tracking = $('.aw-checkbox-enable-click-tracking');
			var checked = $checkbox_click_tracking.is(':checked');

			if ( ! checked ) {
				$('.js-require-email-tracking').hide();
			}
			else {
				$('.js-require-email-tracking').show();
			}
		},



		/**
		 * @param trigger_name
		 */
		fill_trigger_fields: function( trigger_name ) {

			// Remove existing fields
			AutomateWoo.Workflows.$triggers_box.find('tr.aw-trigger-option').remove();

			if ( trigger_name ) {

				AutomateWoo.Workflows.$triggers_box.addClass('aw-loading');

				$.ajax({
						method: 'GET',
						url: ajaxurl,
						data: {
							action: 'aw_fill_trigger_fields',
							trigger_name: trigger_name,
							workflow_id: AW.workflow.get('id'),
							is_new_workflow: AW.workflow.get('isNew')
						}
					})
					.done(function(response){

						if ( ! response.success ) {
							return;
						}

						AutomateWoo.Workflows.$triggers_box.find('tbody').append( response.data.fields );
						AutomateWoo.Workflows.$triggers_box.removeClass('aw-loading');

						AW.workflow.set( 'trigger', response.data.trigger );
					})
				;

			}
			else {
				AW.workflow.set( 'trigger', false );
			}
		},



		add_new_action: function() {

			var $new_action,
				action_number = AutomateWoo.Workflows.get_number_of_actions() + 1;

			$('.js-aw-no-actions-message').hide();

			$new_action = AutomateWoo.Workflows.$action_template.clone();
			$new_action.removeClass('aw-action-template');
			$new_action.addClass('automatewoo-action');

			AutomateWoo.Workflows.$actions_container.append($new_action);

			$new_action.attr( 'data-action-number', action_number );

			AutomateWoo.Workflows.action_edit_open($new_action);
		},



		action_edit_open: function( $action ) {

			var action_number = $action.data('action-number');

			$action.addClass('js-open');
			$action.find('.automatewoo-action__fields').slideDown(150);

			AW.initTooltips();

			// save open state
			$.cookie( 'aw_editing_action_' + AW.workflow.get('id') + '_' + action_number , 1);
		},


		action_edit_close: function( $action ) {

			var action_number = $action.data('action-number');

			$action.removeClass('js-open');
			$action.find('.automatewoo-action__fields').slideUp(150);

			$.removeCookie( 'aw_editing_action_' + AW.workflow.get('id') + '_' + action_number );
		},


		/**
		 * @param $action
		 */
		action_delete: function( $action ) {
			$action.remove();
		},


		/**
		 *
		 */
		fill_action_fields: function( $action, selected_action ) {

			var action_number = $action.data('action-number');
			var $select = $action.find('.js-action-select');

			AutomateWoo.Workflows.$actions_box.addClass('aw-loading');

			// Remove existing fields
			$action.find('tr.automatewoo-table__row:not([data-name="action_name"])').remove();


			$.ajax({
				method: 'GET',
				url: ajaxurl,
				data: {
					action: 'aw_fill_action_fields',
					action_name: selected_action,
					action_number: action_number,
					workflow_id: AW.workflow.get('id')
				}
			})
				.done(function(response){

					$action.find('.automatewoo-table tbody').append( response.data.fields );
					AutomateWoo.Workflows.$actions_box.removeClass('aw-loading');

					// Fill select box name
					$select.attr('name', 'aw_workflow_data[actions]['+action_number+'][action_name]' );

					// Pre fill title
					$action.find('.action-title').text( response.data.title );

					$action.find('.js-action-description').html( response.data.description );

					AW.workflowView.initAction( selected_action, $action );

				})
			;

		},



		get_number_of_actions: function () {
			return $('.automatewoo-action').length;
		},



		/**
		 * Show or hide text var groups based on the selected trigger
		 */
		refine_variables: function() {

			var trigger = AW.workflow.get('trigger');

			$('.aw-variables-group').each(function( i, el ){

				var group = $(el).data( 'automatewoo-variable-group' );

				if ( $.inArray( group, trigger.supplied_data_items ) == -1 ) {
					$(el).hide();
				}
				else {
					$(el).show();
				}
			});
		},


		/**
		 * Show or hide select options based on the selected trigger
		 * Also what if a trigger is changed after an action is already added
		 */
		refine_action_selects: function() {

			$('.js-action-select').each(function(){
				$(this).find('option').each(function(){

					if ( AutomateWoo.Workflows.is_action_compatible_with_current_trigger( $(this).val() ) ) {
						$(this).removeAttr('disabled');
					}
					else {
						$(this).attr('disabled', true);
					}

				});

			});
		},


		/**
		 * Hide queue if disabled for the selected trigger
		 */
		maybe_disable_queueing: function() {
			var trigger = AW.workflow.get('trigger');

			if ( trigger && trigger.allow_queueing ) {
				$('#aw_timing_box').show();
			}
			else {
				$('#aw_timing_box').hide();
			}
		},


		/**
		 * Be sure to run this before refine_action_selects
		 *
		 * Returns false if switching back to stop the trigger change
		 *
		 * @return boolean
		 */
		trigger_compatibility_warning: function(){

			var incompatibleRules = [];
			var incompatibleActions = [];

			_.each( AW.rules.get( 'ruleOptions' ), function( ruleGroup ) {
				_.each( ruleGroup.get( 'rules' ), function( rule ) {
					if ( rule.get('name') && ! AW.rules.isRuleAvailable( rule.get('name') ) ) {
						var ruleObject = rule.get( 'object' );
						incompatibleRules.push( ruleObject.title );
					}
				});
			});


			$('.js-action-select').each(function(){
				if ( ! AutomateWoo.Workflows.is_action_compatible_with_current_trigger( $(this).val() ) ) {
					incompatibleActions.push( $(this).find('option:selected').text() )
				}
			});


			if ( incompatibleRules.length || incompatibleActions.length ) {

				incompatibleActions = _.uniq( incompatibleActions );
				incompatibleRules = _.uniq( incompatibleRules );

				var modalView = new AW.TriggerCompatibilityModalView({
					incompatibleRules: incompatibleRules,
					incompatibleActions: incompatibleActions
				});

				AutomateWoo.Modal.open();
				AutomateWoo.Modal.contents( modalView.render().el );

				return false;
			}

			return true;
		},



		clearIncompatibleActions: function() {
			$('.js-action-select').each(function(){
				if ( ! AutomateWoo.Workflows.is_action_compatible_with_current_trigger( $(this).val() ) ) {
					var $action = $(this).parents('.automatewoo-action').first();
					AutomateWoo.Workflows.action_delete( $action );
				}
			});
		},



		/**
		 *
		 * @param action_name
		 *
		 * @return boolean
		 */
		is_action_compatible_with_current_trigger: function( action_name ) {

			var compatible = true,
			 	trigger = AW.workflow.get('trigger'),
				action = AW.workflow.getAction(action_name);

			// Not a valid action
			if ( ! action ) {
				return true;
			}

			// No data items required
			if ( ! action.required_data_items.length ){
				return true;
			}

			$.each( action.required_data_items, function(i, value){

				if ( $.inArray( value, trigger.supplied_data_items ) == -1 ) {
					compatible = false
				}
			});

			return compatible;
		},



		preview_action: function( $action ) {

			var action_number = $action.data('action-number'),
				name_selector,
				preview_data = {};

			if ( AutomateWoo.isEmailPreviewOpen() ) {
				AutomateWoo.Workflows.$actions_box.addClass('aw-loading');
			}

			if ( typeof tinyMCE !== 'undefined' ) {
				tinyMCE.triggerSave();
			}

			name_selector = 'aw_workflow_data[actions]['+action_number+']';

			// get fields to preview
			$action.find('[name*="' + name_selector + '"]').each(function( i, el ){

				var name, val;

				// get the name
				name = $(el).attr('name').replace(name_selector, '').replace('[', '').replace(']', '');

				if ( $(el).attr('type') === 'checkbox' ) {
                    val = el.checked ? '1' : '';
				}
				else {
					val = $(el).val();

				}
				preview_data[name] = val;
			});

			console.log( preview_data);

			AutomateWoo.openLoadingEmailPreview(); // open the preview window before saving so that the popup is not blocked


			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'aw_save_preview_data',
					workflow_id: AW.workflow.get('id'),
					preview_data: preview_data
				},
				success: function(response) {
					if ( response.success ) {
						AutomateWoo.open_email_preview( 'workflow_action', {
							workflow_id: AW.workflow.get('id'),
							action_number: action_number
						});
					}
				},
				complete: function() {
					AutomateWoo.Workflows.$actions_box.removeClass('aw-loading');
				}
			});

		},


		/**
		 * @param id
         */
		init_ajax_wysiwyg: function( id ){

			if ( typeof tinymce === 'undefined' || typeof tinyMCEPreInit.mceInit.automatewoo_editor === 'undefined' )
				return;

			var $wrap,
				mceInit,
				qtInit,
				qtags;

			mceInit = $.extend({}, tinyMCEPreInit.mceInit.automatewoo_editor );
			qtInit = $.extend({}, tinyMCEPreInit.qtInit.automatewoo_editor );

			mceInit.selector = '#' + id;
			mceInit.id = id;
			mceInit.wp_autoresize_on = false;

			tinyMCEPreInit.mceInit[ mceInit.id ] = mceInit;


			qtInit.id = id;

			$wrap = tinymce.$( '#wp-' + id + '-wrap' );

			if ( ( $wrap.hasClass( 'tmce-active' ) || ! tinyMCEPreInit.qtInit.hasOwnProperty( id ) ) ) {

				try {

					tinymce.init( mceInit );

				} catch(e){}
			}

			try {

				qtags = quicktags( qtInit );

				this.init_wysiwyg_buttons( qtags );

			} catch(e){}

		},


		/**
		 *
		 * @param qtags
         */
		init_wysiwyg_buttons: function( qtags ) {

			var defaults = ',strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,';

			canvas = qtags.canvas;
			name = qtags.name;
			settings = qtags.settings;
			html = '';
			theButtons = {};
			use = '';

			// set buttons
			if ( settings.buttons ) {
				use = ','+settings.buttons+',';
			}

			for ( i in edButtons ) {
				if ( !edButtons[i] ) {
					continue;
				}

				id = edButtons[i].id;
				if ( use && defaults.indexOf( ',' + id + ',' ) !== -1 && use.indexOf( ',' + id + ',' ) === -1 ) {
					continue;
				}

				if ( !edButtons[i].instance || edButtons[i].instance === inst ) {
					theButtons[id] = edButtons[i];

					if ( edButtons[i].html ) {
						html += edButtons[i].html(name + '_');
					}
				}
			}

			if ( use && use.indexOf(',fullscreen,') !== -1 ) {
				theButtons.fullscreen = new qt.FullscreenButton();
				html += theButtons.fullscreen.html(name + '_');
			}


			if ( 'rtl' === document.getElementsByTagName('html')[0].dir ) {
				theButtons.textdirection = new qt.TextDirectionButton();
				html += theButtons.textdirection.html(name + '_');
			}

			qtags.toolbar.innerHTML = html;
			qtags.theButtons = theButtons;

		}


	};
	

	AutomateWoo.Workflows.init();


});