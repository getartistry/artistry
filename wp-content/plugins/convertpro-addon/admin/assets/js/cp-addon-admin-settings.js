/**
 * Convert Pro Addon admin settings
 *
 * @package Convert Pro Addon
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
	var CPAddonAjaxQueue = (function() {

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


	CPAddonAdmin = {

		init: function() {
			/**
			 * Run / Process AJAX request
			 */
			CPAddonAjaxQueue.run();

			$( document ).delegate( ".activate-module", "click", CPAddonAdmin._activate_module );
			$( document ).delegate( ".deactivate-module", "click", CPAddonAdmin._deactivate_module );

			$( document ).delegate( ".all", "click", CPAddonAdmin._bulk_activate_modules_activate_module );
			$( document ).delegate( ".deactivate-all", "click", CPAddonAdmin._bulk_deactivate_modules_activate_module );
			$( "#search-cp-addon" ).focus();
			$( "#search-cp-addon" ).bind( "keyup input", CPAddonAdmin._search_modules );
		},

		/**
		 * Activate All Modules.
		 */
		 _bulk_activate_modules_activate_module: function() {

			var data = {
				action: 'cp_addon_bulk_activate_modules',
				nonce: cpAddonModules.ajax_nonce,
			};
			CPAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){
					// Bulk add or remove classes to all modules.
					$('.cp-addon-list').children( "li" ).addClass( 'active' ).removeClass( 'deactive' );
					$('.cp-addon-list').children( "li" ).find('.activate-module')
						.addClass('deactivate-module')
						.text('Deactivate')
						.removeClass('activate-module');
					}
			});

		},

		/**
		 * Deactivate Bulk Modules.
		 */
		 _bulk_deactivate_modules_activate_module: function() {

			var data = {
				action: 'cp_addon_bulk_deactivate_modules',
				nonce: cpAddonModules.ajax_nonce,
			};
			CPAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){
					// Bulk add or remove classes to all modules.
					$('.cp-addon-list').children( "li" ).addClass( 'deactive' ).removeClass( 'active' );
					$('.cp-addon-list').children( "li" ).find('.deactivate-module')
						.addClass('activate-module')
						.text('Activate')
						.removeClass('deactivate-module');
					}
			});
		},

		/**
		 * Activate Module.
		 */
		_activate_module: function() {
			var button = $( this ),
				id     = button.parents('li').attr('id');

			button.text( 'Activating' );
			button.append( '<div class="loader-container"><i class="cp-loader-style"></i></div>' );

			var data = {
				module_id : id,
				action: 'cp_addon_activate_module',
				nonce: cpAddonModules.ajax_nonce,
			};
			CPAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					// Add active class.
					$( '#' + id ).addClass('active').removeClass( 'deactive' );

					// Change button classes & text.
					$( '#' + id ).find('.activate-module')
						.addClass('deactivate-module')
						.text('Deactivate')
						.removeClass('activate-module');

					if( id == 'ab-test' ) {
						$( '.cp-parent-wrap .nav-tab-wrapper a:eq(1)' ).after( '<a class="nav-tab " href="' + cpAddonModules.ab_test_url + '">A/B Test</a>' );
					} else if( id == 'analytics' ) {
						window.location.reload();
					}
				}
			});
		},

		/**
		 * Deactivate Module.
		 */
		_deactivate_module: function() {
			var button = $( this ),
				id     = button.parents('li').attr('id');

			button.text( 'Deactivating' );
			button.append( '<div class="loader-container"><i class="cp-loader-style"></i></div>' );

			var data = {
				module_id: id,
				action: 'cp_addon_deactivate_module',
				nonce: cpAddonModules.ajax_nonce,
			};
			CPAddonAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					// Remove active class.
					$( '#' + id ).addClass( 'deactive' ).removeClass('active');

					// Change button classes & text.
					$( '#' + id ).find('.deactivate-module')
						.addClass('activate-module')
						.text('Activate')
						.removeClass('deactivate-module');

					if( id == 'ab-test' ) {
						$( '.cp-parent-wrap .nav-tab-wrapper a:eq(2)' ).hide();
					} else if( id == 'analytics' ) {
						window.location.reload();
					}
				}
			})
		},

		/**
		 * Quick Search - Search by Title
		 *
		 */
		 _search_modules: function() {
			var q = $(this).val().toLowerCase();
			$('.cp-addon-wrap .cp-nothing-found').hide();
			if( q === '' ) {
	             $('.cp-addon-list li').fadeIn();
	        } else {
	            $('.cp-addon-list li').each( function(){
	                var self = $(this);

	                if( self.find('h3').html().toLowerCase().indexOf(q) > -1 ) {
	                    self.fadeIn().addClass('visible');
	                } else {
	                    self.fadeOut().removeClass('visible');
	                }
	            } );
	         // No result found message
        	if ( $('.cp-addon-list li').hasClass('visible') ) {
        		$('.cp-addon-wrap .cp-nothing-found').hide();
        	}
        	else{
        		$('.cp-addon-wrap .cp-nothing-found').fadeIn();
        	}
	        }
		},
	}

	$( document ).ready(function() {
		CPAddonAdmin.init();
	});

})( jQuery );
