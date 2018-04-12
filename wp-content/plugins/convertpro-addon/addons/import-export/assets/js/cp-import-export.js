(function( $ ) {
	
	/**
	 * JavaScript class for working with import export of designs.
	 *
	 * @since 0.0.1
	 */

	"use strict";

	var file_frame, attachment;
	var loader = '';
	
	_wpPluploadSettings['defaults']['multipart_params']['admin_page'] = 'import';

	var CPPRO_IMPORT_EXPORT = {
		
		/**
		 * Initialize
		 *
		 * @return void
		 * @since 0.0.1
		 */
		init: function() {
			var body = $('body');
			loader = $( '.cp-import-overlay' );
			$( document ).on( 'click', '#cp-edit-dropdown a.cp-export-action', this._export );
			$( document ).on( 'click', '.cp-edit-settings', this._assignID );
			$( document ).on( 'click', '.cp-import-btn', this._import );
		},

		_import: function( event ) {
			event.preventDefault();

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({

				title: 'Upload Your Exported file',
				button: {
					text: 'Upload',
				},
				library: {
					type: 'application/zip'
				},
				multiple: false
			});

			// When the file is selected, run a callback.
		    file_frame.on( 'select', function() {


		    	console.log( "on file select" );

				// We set multiple to false so only get one file from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				var file = attachment,
					msg = jQuery(".message");
				loader.show();
				jQuery("body").css( "overflow", "hidden" );

				CPPRO_IMPORT_EXPORT.ajaxCall( {
					action  : 'cp_import_design',
					file : file
				}, CPPRO_IMPORT_EXPORT._importComplete );
		    });
		    
			if ( file_frame ) {
				file_frame.open();
				return;
			}

		    
		},

		_importComplete: function( result ) {
			console.log(result);
			$( 'body' ).addClass( 'cp-loaded' );
			if( ! result.data.error ) {
				window.location = result.data.edit_url;
			}
		},

		/**
		 * Submit form to export the design
		 *
		 * @return void
		 * @since 0.0.1
		 */
		_export: function() {
			$( '.cp_export_form' ).submit();
		},

		/**
		 * Assign Post ID to hidden field
		 *
		 * @return void
		 * @since 0.0.1
		 */
		_assignID: function() {

			var thisObj 	= $( this ),
				dropdownObj = $( '#cp-edit-dropdown' ),
				postID 		= thisObj.closest( '.cp-row' ).data( 'id' );

			console.log(thisObj);
			if( typeof postID != 'undefined' ) {
				dropdownObj.find( '.cp_export_form input[name=popup_id]' ).val( postID );
			}
		},

		/**
		 * AJAX call to services.
		 *
		 * @param {Object} args Arguments to AJAX call.
		 * @param func: Callback function name.
		 * @return void
		 * @since 0.0.1
		 */
		ajaxCall: function( args, func ) {

			$.ajax( {
				data: args,
				action: args.action,
				url: cp_import_export.url,
				success: func,
				method: 'post',
				success  : func
			});

		},
	};

	$ ( function() {
		CPPRO_IMPORT_EXPORT.init();
	});

})( jQuery );