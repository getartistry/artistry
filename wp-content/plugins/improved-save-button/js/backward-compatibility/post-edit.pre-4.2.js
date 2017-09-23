/**
 * Copyright 2017 Label Blanc (http://www.labelblanc.ca/)
 *
 * This file is part of the "Improved Save Button"
 * Wordpress plugin.
 *
 * The "Improved Save Button" Wordpress plugin
 * is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The plugin uses functionalities of the latest Wordpress version.
 * This file is included (only) in Wordpress versions older than 4.2 to provide
 * backward compatibility.
 * 
 * This file must be included after post-edit.js.
 */

/**
 * Modifies jQuery's show() and hide() functions to send
 * an event when they are called.
 * 
 * @see http://jsfiddle.net/mofle/eZ4X3/
 */
(function($) {
	$.each( ['show', 'hide'], function( i, eventName ) {
		var originalFct = $.fn[ eventName ];

		$.fn[ eventName ] = function() {
			this.trigger( eventName );
			return originalFct.apply( this, arguments );
		};
	});
})( jQuery );

/** 
 * Starting with WP 4.2, the spinner visibility is now toggled with
 * a class change (.is-active), and not anymore with jQuery's show()/hide()
 * functions. The plugin was updated to use the new way,
 * but the following code is for backward compatibility:
 * when the spinner's show() and hide() functions are called,
 * we toggle the class .is-active.
 */
(function($) {
	var POF = window.LabelBlanc.SaveAndThen.PostEditForm,
		oldSetupSpinnerFct = POF.prototype.setupSpinner;

	POF.prototype.setupSpinner = function() {
		oldSetupSpinnerFct.apply( this, arguments );
		
		this.$spinner.on( 'show', function() {
			$(this).addClass( 'is-active' );
		});
		
		this.$spinner.on( 'hide', function() {
			$(this).removeClass( 'is-active' );
		});
	};
})( jQuery );