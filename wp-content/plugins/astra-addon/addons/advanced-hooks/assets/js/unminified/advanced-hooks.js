(function($){

	/**
	 * Advanced Hooks 
	 *
	 * @class AstraAdvancedHooks
	 * @since 1.0
	 */
	AstraAdvancedHooks = {
		
		/**
		 * Initializes a Advanced Hooks.
		 *
		 * @since 1.0
		 * @method init
		 */ 
		init: function()
		{
			// Init backgrounds.
			AstraAdvancedHooks.bind();
			AstraAdvancedHooks.php_snippet_area();
			AstraAdvancedHooks.action_description();
			AstraAdvancedHooks.bind_tooltip();
			AstraAdvancedHooks.initLayoutSettings();
		},
		bind: function()
		{
			
			// $( '#ast-advanced-hook-action' ).on( 'change', AstraAdvancedHooks.stickyHeaderChanged );
			$( 'input[name="ast-advanced-hook-header[sticky]"]' ).on( 'change', AstraAdvancedHooks.stickyHeaderChanged );
			$( 'input[name="ast-advanced-hook-footer[sticky]"]' ).on( 'change', AstraAdvancedHooks.stickyFooterChanged );
			$( 'select[name="ast-advanced-hook-layout"]' ).on( 'change', AstraAdvancedHooks.layoutChanged );
		},

		bind_tooltip: function() {

			// Call Tooltip
			$('.ast-advanced-hook-heading-help').tooltip({
				content: function() {
					return $(this).prop('title');
				},
				tooltipClass: 'ast-advanced-hook-ui-tooltip',
				position: {
					my: 'center top',
					at: 'center bottom+10',
				},
				hide: {
					duration: 200,
				},
				show: {
					duration: 200,
				},
			});
		},
		
		php_snippet_area: function() {
			var self = this;

			$(document).on( 'click', '.ast-advanced-hook-enable-php-btn', function(e) {
				
				e.preventDefault();
				var icon  = $(this).children('i'),
					label = $(this).children('.ast-btn-title'),
					input = $(this).children( '.ast-advanced-hook-with-php' );
			
				$(this).toggleClass('ast-active');

				if( $(this).hasClass('ast-active') ) {
					icon.attr( 'class', 'dashicons dashicons-edit' );
					label.text( $(this).data( 'disable-text' ) );
					input.val('enabled');
					$('body').addClass( 'astra-php-snippt-enabled' );
				} else {
					icon.attr( 'class', 'dashicons dashicons-editor-code' );
					label.text( $(this).data( 'enable-text' ) );
					input.val('');
					$('body').removeClass( 'astra-php-snippt-enabled' );
				}

			});
		},

		action_description: function() {
			$('#ast-advanced-hook-action').on('change', function(e) {
				var desc_wrap    = $(this).next('.ast-advanced-hook-action-desc'),
					desc_content = $(this).find('option:selected').attr('data-desc');

				if ( 'undefined' != typeof desc_content && '' != desc_content ) {
					desc_wrap.removeClass('ast-no-desc');
					desc_wrap.text(desc_content);
				} else {
					desc_wrap.addClass('ast-no-desc');
					desc_wrap.text('');
				}
			});
		},

		/**
		 * Init the layout settings based on layout.
		 *
		 * @since 1.0
		 * @method initLayoutSettings
		 */
		initLayoutSettings: function()
		{
			var layout      = $( '#ast-advanced-hook-layout' ).val(),
			    sticky_header      = $( 'input[name="ast-advanced-hook-header[sticky]"]' );
			    sticky_footer      = $( 'input[name="ast-advanced-hook-footer[sticky]"]' );
			if( 'header' == layout ){
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-header-required' ).show();
				$( '.ast-layout-required' ).show();
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-404-layout-required' ).hide();
			} else if( 'hooks' == layout ){
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-hooks-required' ).show();
				$( '.ast-layout-required' ).show();
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-404-layout-required' ).hide();
			} else if( 'footer' == layout ) {
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-footer-required' ).show();
				$( '.ast-layout-required' ).show();
				$( '.ast-404-layout-required' ).hide();
			} else if ( '404-page' == layout ) {
				$( '.ast-404-layout-required' ).show();
				$( '.ast-target-rules-user' ).show();
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-target-rules-display' ).hide();
				$( '.ast-target-rules-exclude' ).hide();
			} else {
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-required' ).hide();
				$( '.ast-404-layout-required' ).hide();
			}

			if( sticky_header.is(':checked') && 'header' == layout ){
				$( '.ast-layout-header-sticky-required' ).show();
			}
			else{
				$( '.ast-layout-header-sticky-required' ).hide();
			}

			if( sticky_footer.is(':checked') && 'footer' == layout ){
				$( '.ast-layout-footer-sticky-required' ).show();
			}
			else{
				$( '.ast-layout-footer-sticky-required' ).hide();
			}
				
		},

		stickyHeaderChanged: function()
		{
			if( $(this).is(':checked') ){
				$( '.ast-layout-header-sticky-required' ).show();
			}
			else{
				$( '.ast-layout-header-sticky-required' ).hide();
			}
			
		},

		stickyFooterChanged: function()
		{
			if( $(this).is(':checked') ){
				$( '.ast-layout-footer-sticky-required' ).show();
			}
			else{
				$( '.ast-layout-footer-sticky-required' ).hide();
			}
			
		},

		layoutChanged: function()
		{
			var val     = $(this).val(),
			    sticky_header  = $( 'input[name="ast-advanced-hook-header[sticky]"]' ),
			    sticky_footer  = $( 'input[name="ast-advanced-hook-footer[sticky]"]' );

			if( 'header' == val ){
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-layout-header-required' ).show();
				$( '.ast-layout-required' ).show();
				$( '.ast-404-layout-required' ).hide();
			} else if( 'hooks' == val ){
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-layout-hooks-required' ).show();
				$( '.ast-layout-required' ).show();
				$( '.ast-404-layout-required' ).hide();
			} else if( 'footer' == val ) {
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-footer-required' ).show();
				$( '.ast-layout-required' ).show();
				$( '.ast-404-layout-required' ).hide();
			} else if ( '404-page' == val ) {
				$( '.ast-404-layout-required' ).show();
				$( '.ast-target-rules-user' ).show();
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-target-rules-display' ).hide();
				$( '.ast-target-rules-exclude' ).hide();
			} else {
				$( '.ast-layout-header-required' ).hide();
				$( '.ast-layout-footer-required' ).hide();
				$( '.ast-layout-hooks-required' ).hide();
				$( '.ast-layout-required' ).hide();
				$( '.ast-404-layout-required' ).hide();
			}

			if( sticky_header.is(':checked') && 'header' == val ){
				$( '.ast-layout-header-sticky-required' ).show();
			}
			else{
				$( '.ast-layout-header-sticky-required' ).hide();
			}

			if( sticky_footer.is(':checked') && 'footer' == val ){
				$( '.ast-layout-footer-sticky-required' ).show();
			}
			else{
				$( '.ast-layout-footer-sticky-required' ).hide();
			}
			
		},
	}

	/* Initializes the Advanced Hooks. */
	$(function(){
		AstraAdvancedHooks.init();
	});

})(jQuery);