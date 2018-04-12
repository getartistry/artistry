( function( $ ) {

	UAELModalPopup = {

		/**
		 * Place the Modal Popup on centre of screen
		 *
		 */
		_center: function() {
			setTimeout( function() {
				$( '.uael-modal-parent-wrapper' ).each( function() {
					var $this = $( this );
					var tmp_id = $this.attr( 'id' );
					var popup_id = tmp_id.replace( '-overlay', '' );
					UAELModalPopup._centerModal( popup_id );
				} );
			}, 300 );
		},

		/**
		 * Place the Modal Popup on centre of screen
		 *
		 */
		_centerModal: function ( popup_id ) {

			var popup_wrap = $('.uamodal-' + popup_id ),
				modal_popup  = '#modal-' + popup_id,
				node 		 = '.uamodal-' + popup_id,
				extra_value = 0,
				close_handle = $( '#modal-' + popup_id ).find( '.uael-modal-close' ),
				top_pos = ( ( $( window ).height() - $( '#modal-' + popup_id ).outerHeight() ) / 2 );

			if ( $( '#modal-' + popup_id ).hasClass('uael-center-modal') ) {
	        	$( '#modal-' + popup_id ).removeClass('uael-center-modal');
			}

			if( close_handle.hasClass( 'uael-close-custom-popup-top-right' ) || close_handle.hasClass( 'uael-close-custom-popup-top-left' ) ) {
				extra_value = parseInt( close_handle.outerHeight() );
			}

			if ( popup_wrap.find( '.uael-content' ).outerHeight() > $( window ).height() ) {
				top_pos = ( 20 + extra_value );
				if( $( '#modal-' + popup_id ).hasClass( 'uael-show' ) ) {
					$( 'html' ).addClass( 'uael-html-modal' );
					$( '#modal-' + popup_id ).addClass( 'uael-modal-scroll' );

					if( $( '#wpadminbar' ).length > 0 ) {
						top_pos = ( top_pos + parseInt( $( '#wpadminbar' ).outerHeight() ) );
					}
					$( modal_popup ).find( '.uael-content' ).css( 'margin-top', + top_pos +'px' );
					$( modal_popup ).find( '.uael-content' ).css( 'margin-bottom', '20px' );
				}
			} else {
				top_pos = ( parseInt( top_pos ) + 20 );
			}

			$( modal_popup ).css( 'top', + top_pos +'px' );
			$( modal_popup ).css( 'margin-bottom', '20px' );
		},

		/**
		 * Invoke show modal popup
		 *
		 */
		_show: function( popup_id ) {
			UAELModalPopup._autoPlay( popup_id );
			if( $( '#modal-' + popup_id ).hasClass( 'uael-modal-vimeo' ) || $( '#modal-' + popup_id ).hasClass( 'uael-modal-youtube' ) ) {
				setTimeout( function() { $( '#modal-' + popup_id ).addClass( 'uael-show' ); }, 300 );
			} else {
				$( '#modal-' + popup_id ).addClass( 'uael-show' );
			}
			setTimeout(
				function() {
					$( '#modal-' + popup_id ).removeClass( 'uael-effect-13' );
				},
				1000
			);
			UAELModalPopup._centerModal( popup_id );
			UAELModalPopup._afterOpen( popup_id );
		},

		/**
		 * Invoke close modal popup
		 *
		 */
		_close: function( popup_id ) {
			$( '#modal-' + popup_id ).removeClass( 'uael-show' );
			$( 'html' ).removeClass( 'uael-html-modal' );
			$( '#modal-' + popup_id ).removeClass('uael-modal-scroll');
			UAELModalPopup._stopVideo( popup_id );
		},

		/**
		 * Check all the end conditions to show modal popup
		 *
		 */
		_canShow: function( popup_id ) {
			var is_cookie = $( '.uamodal-' + popup_id ).data( 'cookies' );
			var current_cookie = Cookies.get( 'uael-modal-popup-' + popup_id );
			var display = true;

			// Check if cookies settings are set
			if ( 'undefined' !== typeof is_cookie && 'yes' === is_cookie ) {
				if( 'undefined' !== typeof current_cookie && 'true' == current_cookie ) {
					display = false;
				} else {
					Cookies.remove( 'uael-modal-popup-' + popup_id );
				}
			} else {
				Cookies.remove( 'uael-modal-popup-' + popup_id );
			}

			// Check if any other modal is opened on screen.
			if( $( '.uael-show' ).length > 0 ) {
				display = false;
			}

			// Check if this is preview or actuall load.
			if( $( '#modal-' + popup_id ).hasClass( 'uael-modal-editor' ) ) {
				display = false;
			}

			return display;
		},

		/**
		 * Auto Play video
		 *
		 */
		_autoPlay: function( popup_id ) {

			var active_popup = $( '.uamodal-' + popup_id ),
				video_autoplay = active_popup.data( 'autoplay' ),
				modal_content = active_popup.data( 'content' );


			if ( video_autoplay == 'yes' && ( modal_content == 'youtube' || modal_content == 'vimeo' ) ) {

				var modal_iframe 		= active_popup.find( 'iframe' ),
					modal_src 			= modal_iframe.attr( "src" ) + '&autoplay=1';

				modal_iframe.attr( "src",  modal_src );
			}
		},

		/**
		 * Stop playing video
		 *
		 */
		_stopVideo: function( popup_id ) {

			var active_popup = $( '.uamodal-' + popup_id ),
				modal_content = active_popup.data( 'content' );

			if ( modal_content != 'photo' ) {

				var modal_iframe 		= active_popup.find( 'iframe' ),
					modal_video_tag 	= active_popup.find( 'video' );

				if ( modal_iframe.length ) {
					var modal_src = modal_iframe.attr( "src" ).replace( "&autoplay=1", "" );
					modal_iframe.attr( "src", '' );
				    modal_iframe.attr( "src", modal_src );
				} else if ( modal_video_tag.length ) {
		        	modal_video_tag[0].pause();
					modal_video_tag[0].currentTime = 0;
				}
			}
		},

		/**
		 * Process after modal popup open event
		 *
		 */
		_afterOpen: function( popup_id ) {

			var current_cookie = Cookies.get( 'uael-modal-popup-' + popup_id );
			var cookies_days  = parseInt( $( '.uamodal-' + popup_id ).data( 'cookies-days' ) );

			if( 'undefined' === typeof current_cookie && 'undefined' !== typeof cookies_days ) {
				Cookies.set( 'uael-modal-popup-' + popup_id, true, { expires: cookies_days } );
			}
		},
	}

	/**
	 * ESC keypress event
	 *
	 */
	$( document ).on( 'keyup', function( e ) {

		if ( 27 == e.keyCode ) {

			$( '.uael-modal-parent-wrapper' ).each( function() {
				var $this = $( this );
				var tmp_id = $this.attr( 'id' );
				var popup_id = tmp_id.replace( '-overlay', '' );
				var close_on_esc = $this.data( 'close-on-esc' );

				if( 'yes' == close_on_esc ) {
					UAELModalPopup._close( popup_id );
				}
			} );
		}
	});

	/**
	 * Overlay click event
	 *
	 */
	$( document ).on( 'click touchstart', '.uael-overlay', function( e ) {

		var $this = $( this ).closest( '.uael-modal-parent-wrapper' );
		var tmp_id = $this.attr( 'id' );
		var popup_id = tmp_id.replace( '-overlay', '' );
		var close_on_overlay = $this.data( 'close-on-overlay' );

		if( 'yes' == close_on_overlay ) {
			UAELModalPopup._close( popup_id );
		}
	});

	/**
	 * Close img/icon clicked
	 *
	 */
	$( document ).on( 'click', '.uael-modal-close', function() {

		var $this = $( this ).closest( '.uael-modal-parent-wrapper' );
		var tmp_id = $this.attr( 'id' );
		var popup_id = tmp_id.replace( '-overlay', '' );
		UAELModalPopup._close( popup_id );
	} );

	/**
	 * Trigger open modal popup on click img/icon/button/text
	 *
	 */
	$( document ).on( 'click', '.uael-trigger', function() {

		var popup_id = $( this ).closest( '.elementor-element' ).data( 'id' );
		var selector = $( '.uamodal-' + popup_id );
		var trigger_on = selector.data( 'trigger-on' );

		if(
			'text' == trigger_on
			|| 'icon' == trigger_on
			|| 'photo' == trigger_on
			|| 'button' == trigger_on
		) {
			UAELModalPopup._show( popup_id );
		}
	} );

	/**
	 * Center the modal popup event
	 *
	 */
	$( document ).on( 'uael_modal_popup_init', function( e, node_id ) {
		if( $( '#modal-' + node_id ).hasClass( 'uael-show-preview' ) ) {
			setTimeout( function() {
				$( '#modal-' + node_id ).addClass( 'uael-show' );
			}, 400 );
		}
		UAELModalPopup._centerModal( node_id );
	} );

	/**
	 * Resize event
	 *
	 */
	$( window ).resize( function() {
		UAELModalPopup._center();
	} );

	/**
	 * Exit intent event
	 *
	 */
	$(document).on( 'mouseleave', function( e ) {

		if ( e.clientY > 20 ) {
            return;
        }

		$( '.uael-modal-parent-wrapper' ).each( function() {

			var $this = $( this );
			var tmp_id = $this.attr( 'id' );
			var popup_id = tmp_id.replace( '-overlay', '' );
			var trigger_on = $this.data( 'trigger-on' );
			var exit_intent = $this.data( 'exit-intent' );

			if( 'automatic' == trigger_on ) {
				if(
					'yes' == exit_intent
					&& UAELModalPopup._canShow( popup_id )
				) {
					UAELModalPopup._show( popup_id );
				}
			}
		} );
    } );

	/**
	 * Load page event
	 *
	 */
	$( document ).ready( function( e ) {

		var current_url = window.location.href;
		if( current_url.indexOf( '&action=elementor' ) <= 0 ) {
			$( '.uael-modal-parent-wrapper' ).each( function() {
				$( this ).appendTo( document.body );
			});
		}

		UAELModalPopup._center();

		$( '.uael-modal-parent-wrapper' ).each( function() {

			var $this = $( this );
			var tmp_id = $this.attr( 'id' );
			var popup_id = tmp_id.replace( '-overlay', '' );
			var trigger_on = $this.data( 'trigger-on' );
			var after_sec = $this.data( 'after-sec' );
			var after_sec_val = $this.data( 'after-sec-val' );
			var custom = $this.data( 'custom' );

			// Trigger automatically.
			if( 'automatic' == trigger_on ) {
				if(
					'yes' == after_sec
					&& 'undefined' != typeof after_sec_val
				) {
					var id = popup_id;
					setTimeout( function() {
						if( UAELModalPopup._canShow( id ) ) {
							UAELModalPopup._show( id );
						}
					}, ( parseInt( after_sec_val ) * 1000 ) );
				}
			}

			// Custom ID/Class click event
			if( 'custom' == trigger_on ) {
				if( 'undefined' != typeof custom && '' != custom ) {
					var custom_selectors = custom.split( ',' );
					if( custom_selectors.length > 0 ) {
						for( var i = 0; i < custom_selectors.length; i++ ) {
							if( 'undefined' != typeof custom_selectors[i] && '' != custom_selectors[i] ) {
								$( document ).on( 'click', custom_selectors[i], function() {
									UAELModalPopup._show( popup_id );
								} );
							}
						}
					}
				}
			}
		} );
	} );

	/**
	 * Modal popup handler Function.
	 *
	 */
	var WidgetUAELModalPopupHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope )
			return;

		$( document ).trigger( 'uael_modal_popup_init', [ $scope.data( 'id' ) ] );
	};

	$( window ).on( 'elementor/frontend/init', function () {

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-modal-popup.default', WidgetUAELModalPopupHandler );

	});

} )( jQuery );
