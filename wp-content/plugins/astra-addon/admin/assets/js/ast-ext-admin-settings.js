/**
 * Astra Addon admin settings
 *
 * @package Astra Addon
 * @since  1.0.0
 */

(function( $ ) {

	/**
	 * AJAX Request Queue
	 *
	 * - add()
	 * - remove()
	 * - run()
	 * - stop()
	 *
	 * @since 1.2.0.8
	 */
	var AstraAddonAjaxQueue = (function() {

		var requests = [];

		return {

			/**
			 * Add AJAX request
			 *
			 * @since 1.2.0.8
			 */
			add:  function(opt) {
			    requests.push(opt);
			},

			/**
			 * Remove AJAX request
			 *
			 * @since 1.2.0.8
			 */
			remove:  function(opt) {
			    if( jQuery.inArray(opt, requests) > -1 )
			        requests.splice($.inArray(opt, requests), 1);
			},

			/**
			 * Run / Process AJAX request
			 *
			 * @since 1.2.0.8
			 */
			run: function() {
			    var self = this,
			        oriSuc;

			    if( requests.length ) {
			        oriSuc = requests[0].complete;

			        requests[0].complete = function() {
			             if( typeof(oriSuc) === 'function' ) oriSuc();
			             requests.shift();
			             self.run.apply(self, []);
			        };

			        jQuery.ajax(requests[0]);

			    } else {

			      self.tid = setTimeout(function() {
			         self.run.apply(self, []);
			      }, 1000);
			    }
			},

			/**
			 * Stop AJAX request
			 *
			 * @since 1.2.0.8
			 */
			stop:  function() {

			    requests = [];
			    clearTimeout(this.tid);
			}
		};

	}());


	ASTExtAdmin = {

		init: function() {
			/**
			 * Run / Process AJAX request
			 */
			AstraAddonAjaxQueue.run();

			$( document ).delegate( ".ast-activate-module", "click", ASTExtAdmin._activate_module );
			$( document ).delegate( ".ast-deactivate-module", "click", ASTExtAdmin._deactivate_module );

			$( document ).delegate( ".ast-activate-all", "click", ASTExtAdmin._bulk_activate_modules_activate_module );
			$( document ).delegate( ".ast-deactivate-all", "click", ASTExtAdmin._bulk_deactivate_modules_activate_module );
			
			$( document ).delegate( ".clear-cache", "click", ASTExtAdmin._clear_assets_cache );

			$( "#search-astra-addon" ).focus();
			$( "#search-astra-addon" ).bind( "keyup input", ASTExtAdmin._search_modules );
		},

		/**
		 * Activate All Modules.
		 */
		_bulk_activate_modules_activate_module: function( e ) {
			var button = $( this );

			var data = {
				action: 'astra_addon_bulk_activate_modules',
				nonce: astraAddonModules.ajax_nonce,
			};

			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}
			
			$( button ).addClass('updating-message');

			AstraAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){
					// Bulk add or remove classes to all modules.
					$('.ast-addon-list').children( "li" ).addClass( 'active' ).removeClass( 'deactive' );
					$('.ast-addon-list').children( "li" ).find('.ast-activate-module')
						.addClass('ast-deactivate-module')
						.text(astraAddonModules.deactivate)
						.removeClass('ast-activate-module');
						$( button ).removeClass('updating-message');
					}
			});
			e.preventDefault();
		},

		/**
		 * Deactivate Bulk Modules.
		 */
		_bulk_deactivate_modules_activate_module: function( e ) {
			var button = $( this );

			var data = {
				action: 'astra_addon_bulk_deactivate_modules',
				nonce: astraAddonModules.ajax_nonce,
			};

			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}
			$( button ).addClass('updating-message');

			AstraAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){
					// Bulk add or remove classes to all modules.
					$('.ast-addon-list').children( "li" ).addClass( 'deactive' ).removeClass( 'active' );
					$('.ast-addon-list').children( "li" ).find('.ast-deactivate-module')
						.addClass('ast-activate-module')
						.text(astraAddonModules.activate)
						.removeClass('ast-deactivate-module');
						$( button ).removeClass('updating-message');
					}
			});
			e.preventDefault();
		},

		/**
		 * Activate Module.
		 */
		_activate_module: function( e ) {
			var button = $( this ),
				id     = button.parents('li').attr('id'),
				title  = button.parents('li').find("h3").text();
			var data = {
				module_id : id,
				action: 'astra_addon_activate_module',
				nonce: astraAddonModules.ajax_nonce,
			};

			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}

			$( button ).addClass('updating-message');

			AstraAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					// Add active class.
					$( '#' + id ).addClass('active').removeClass( 'deactive' );
					// Change button classes & text.
					$( '#' + id ).find('.ast-activate-module')
						.addClass('ast-deactivate-module')
						.text(astraAddonModules.deactivate)
						.removeClass('ast-activate-module')
						.removeClass('updating-message');
					}
			});

			e.preventDefault();
		},

		/**
		 * Deactivate Module.
		 */
		_deactivate_module: function( e ) {
			var button = $( this ),
				id     = button.parents('li').attr('id');
			var data = {
				module_id: id,
				action: 'astra_addon_deactivate_module',
				nonce: astraAddonModules.ajax_nonce,
			};
			
			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}

			$( button ).addClass('updating-message');

			AstraAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					// Remove active class.
					$( '#' + id ).addClass( 'deactive' ).removeClass('active');
					// Remove active tabs.
					$('#ast-menu-page a.nav-tab[href*="' + id + '"]').hide();
					// Change button classes & text.
					$( '#' + id ).find('.ast-deactivate-module')
						.addClass('ast-activate-module')
						.text(astraAddonModules.activate)
						.removeClass('ast-deactivate-module')
						.removeClass('updating-message');

				}
			})
			e.preventDefault();
		},

		/**
		 * Quick Search - Search by Title
		 *
		 */
		_search_modules: function() {
			var q = $(this).val().toLowerCase();
			$('.ast-addon-wrap .ast-nothing-found').hide();
			if( q === '' ) {
	             $('.ast-addon-list li').fadeIn();
	        } else {
	            $('.ast-addon-list li').each( function(){
	                var self = $(this);

	                if( self.find('h3').html().toLowerCase().indexOf(q) > -1 ) {
	                    self.fadeIn().addClass('visible');
	                } else {
	                    self.fadeOut().removeClass('visible');
	                }
	            } );
	         // No result found message
        	if ( $('.ast-addon-list li').hasClass('visible') ) {
        		$('.ast-addon-wrap .ast-nothing-found').hide();
        	}
        	else{
        		$('.ast-addon-wrap .ast-nothing-found').fadeIn();
        	}
	        }
		},

		/**
		 * Clear Assets Cache.
		 */
		_clear_assets_cache: function() {
			var button 			= $( this );

			button.addClass('loading');

			var data = {
				action: 'astra_addon_clear_cache',
				nonce: astraAddonModules.ajax_nonce,
			};
			
			AstraAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					button.removeClass('loading');
					button.addClass('success');

					setTimeout(function() {
						button.removeClass('success');
					}, 3000);
				}
			})
		},
	}

	$( document ).ready(function() {
		ASTExtAdmin.init();
	});

})( jQuery );
