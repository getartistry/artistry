// Sticky header
var $j = jQuery.noConflict();

$j( document ).on( 'ready', function() {
	// Hooks select
	hooksSelect();
	// Cookie
	hooksCookie();
	// Sticky box
	hooksBox();
} );

/* ==============================================
HOOKS SELECT
============================================== */
function hooksSelect() {
	"use strict"

	$j( '#hook-select' ).on( 'change', function() {
		var $id = $j( this ).children( ':selected' ).attr( 'id' );

		$j( '#oh-hooks .form-table tr' ).hide();
		$j( '#oh-hooks .form-table tr' ).eq( $id ).show();
		Cookies.set( 'hookcookie', $j( '#hook-select option:selected' ).attr( 'id' ), { expires: 90, path: '' } );
		
		if ( $j( '#hook-select' ).val() == 'all' ) {
			$j( '#oh-hooks .form-table tr' ).show();
			Cookies.remove( 'hookcookie', { expires: 90, path: '' } );
		}
		
	} );

}

/* ==============================================
HOOKS COOKIE
============================================== */
function hooksCookie() {
	"use strict"

	if ( Cookies.get( 'hookcookie' ) === '' 
		|| Cookies.get( 'hookcookie' ) === undefined ) {	
		$j( '#oh-hooks .form-table tr' ).show();
		Cookies.remove( 'hookcookie', { expires: 90, path: '' } );
	} else {
		$j( '#hook-select option[id="' + Cookies.get( 'hookcookie' ) + '"]' ).attr( 'selected', 'selected' );
		$j( '#hook-select option[id="' + Cookies.get( 'hookcookie' ) + '"]' ).attr( 'selected', 'selected' );
		$j( '#oh-hooks .form-table tr' ).hide();
		$j( '#oh-hooks .form-table tr' ).eq( Cookies.get( 'hookcookie' ) ).show();
	}

}

/* ==============================================
HOOKS BOX
============================================== */
function hooksBox() {
	"use strict"

	var $top = $j( '#oh-hooks .hooks-box' ).offset().top;

	$j( window ).scroll( function ( event ) {
		var $this = $j( this ).scrollTop();

		if ( $this >= $top ) {
			$j( '#oh-hooks .hooks-box' ).addClass( 'fixed' );
		} else {
			$j( '#oh-hooks .hooks-box' ).removeClass( 'fixed');
			$j( '#oh-hooks .hooks-box' ).width( $j( '#oh-hooks .hooks-box' ).parent().width() );
		}
	} );

}

/* ==============================================
DISPLAY TEMPLATES
============================================== */
function display_condition_options( obj, oh_hook ) {
	"use strict"

	var hook_options = $j( '.options-' + oh_hook );
	var conditional_checked = $j(obj).attr('checked');

	if ( hook_options.is(':hidden') && conditional_checked ) {
    	hook_options.show();
	} else {
		hook_options.hide();
	}
    
}


/* ==============================================
DISPLAY USER ROLES
============================================== */
function display_user_roles( obj, oh_hook ) {
	"use strict"

	var hook_options = $j( '.roles-' + oh_hook );	
	var conditional_checked = $j(obj).attr('checked');
	
	if ( hook_options.is(':hidden') && conditional_checked ) {
    	hook_options.show();
	} else {
		hook_options.hide();
	}
    
}

/* ==============================================
ADD/REMOVE DISPLAY ON
============================================== */
function add_display_on( oh_hook ) {
	var template = wp.template( oh_hook + '-dispaly-on-field' );
	$j( '.' + oh_hook + '-display-on-fields' ).append( template() );
}

/* ==============================================
ADD/REMOVE HIDE ON
============================================== */
function add_hide_on( oh_hook ) {
	
	var template = wp.template( oh_hook + '-hide-on-field' );
	
	$j( '.' + oh_hook + '-hide-on-fields' ).append( template() );
}

( function( $ ) {
    
    // Remove Display 
    $j( '.condition-container' ).on( 'click', '.display-on-remove', function() {
        $j( this ).closest( '.dispaly-on' ).remove();
    } );

    $j( '.condition-container' ).on( 'click', '.hide-on-remove', function() {
        $j( this ).closest( '.hide-on' ).remove();
    } );

    // Remove User Roles
     $j( '.roles-container' ).on( 'click', '.roles-remove', function() {
        $j( this ).closest( '.roles-selector' ).remove();
    } );

} ) ( jQuery );

/* ==============================================
ADD/REMOVE USER ROLES
============================================== */
function add_user_roles( oh_hook ) {
	 var template = wp.template( oh_hook + '-roles-field' );
	 $j( '.' + oh_hook + '-roles-fields' ).append( template() );
}

function remove_user_roles( obj ) {
	 $j( obj ).closest('.roles-selector' ).remove();
}