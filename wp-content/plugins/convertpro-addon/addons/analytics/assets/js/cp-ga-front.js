(function($) {

	var ga_element = '';

	$( window ).on( 'cp_after_popup_open', function( e, data, module_type, style_id ) {
	
		var dataslug = jQuery( '.cp-popup-container[data-style="cp_style_' + style_id + '"]' ).data( 'styleslug' );
	    cpUpdateImpressions( dataslug );
	});

	/**
	 * Google Analytics call to update impression count
	 *
	 * @param Style ID
	 * @return void
	 * @since 1.0.0
	 */
	cpUpdateImpressions = function ( style_slug ) {

	    var category = cp_ga_object.ga_category_name;
	    var action   = 'impression';
	    var label    = style_slug;

	    cpCreateGoogleAnalyticEvent( category, action, label );
	}

	/**
     * Checks if container is visible on viewport of the screen
     *
     * @param {Object}
     * @return Boolean true/false
     * @since 1.0.0
     */
	cpIsModuleOnScreen = function( obj ) {
        var win = jQuery(window);

        var viewport = {
            top : win.scrollTop(),
            left : win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();

        var bounds = obj.offset();
        bounds.right = bounds.left + obj.outerWidth();
        bounds.bottom = bounds.top + obj.outerHeight();

        return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    }

	// triggers after form submission
	$(document).on( 'cp_after_form_submit', function( e, form, response, style_slug ) {

	    // on successful submission
	    if( response.success === true ) {
	        
	        var category = cp_ga_object.ga_category_name;
	        var action   = 'conversion';
	        var label    = style_slug;

	        cpCreateGoogleAnalyticEvent( category, action, label );
	    }
	});

	/**
	* Google Analytics call to update impression count
	*
	* @param category
	* @param action
	* @param label
	* @return void
	* @since 1.0.0
	*/
	cpCreateGoogleAnalyticEvent = function( category, action, label ) {

		if( typeof ga_element !== 'undefined' ) {
		   if( typeof ga !== 'undefined' ){
		       ga_element = ga;
		    } 
		    else if( typeof _gaq !== 'undefined' ){
		        ga_element = _gaq;
		    } 
		    else if( typeof __gaTracker === "function" ){
		        ga_element = __gaTracker;
		    }
		    else if( typeof gaplusu === "function" ){
		        ga_element = gaplusu;
		    }
		}

		var auth_type = typeof cp_ga_object.ga_auth_type != 'undefined' ? cp_ga_object.ga_auth_type : 'universal-ga';

		if( 'undefined' != typeof dataLayer && 'gtm-code' == auth_type ) {

		    dataLayer.push({
		        'event' : cp_ga_object.ga_event_name,
		        'eventCategory' : category,
		        'eventAction' : action,
		        'eventLabel' : label,
		        'eventValue' : '1',
		        'nonInteraction': true
		    });
		} else if ( ( 'universal-ga' == auth_type || 'manual' == auth_type ) 
			&& 'function' == typeof ga_element ) {
		    ga_element( 'send','event', category, action, label, {	
				nonInteraction: true
			});
		}
	}

	cp_track_inline_modules = function() {

        jQuery( ".cp-popup-container.cp-module-before_after, .cp-popup-container.cp-module-inline, .cp-popup-container.cp-module-widget" ).each( function() {

            var $this    = jQuery(this);
            var style_id = $this.data("style").replace( 'cp_style_', '' );
            var is_on_screen = cpIsModuleOnScreen( $this );

            // if module is visible on screen, count impression for that module
            if( is_on_screen && ! $this.hasClass('cp-impression-counted') ) {
                var dataslug = $this.data( 'styleslug' );
                cpUpdateImpressions( dataslug );
                $this.addClass('cp-impression-counted');
            }
        });
    }

    $( document ).ready(function() {
        cp_track_inline_modules();
    });

    /* Scroll Event */
    $( document ).scroll( function(event) {
        cp_track_inline_modules();
    } );
	
})(jQuery);