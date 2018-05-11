jQuery( document ).ready( function( $ ) {

	cpro_on_countdown_expire = function( e ) {
		
		var $this 		= $( this ),
			element 	= $this.closest( '.cp-field-html-data' ),
			parent 		= $this.closest( '.cp-popup-container' ),
			self_id 	= element.attr( 'id' ),
			style_name 	= parent.attr( 'data-style' ),
			timer_type = element.attr( 'data-timer-type' ),
			timezone = element.attr( 'data-timezone' ),
			timer_data = $this.closest( '.cp-countdown-field' ).attr( 'data-' + timer_type ),
			expiry_action = element.attr( 'data-' + timer_type + '-action' ),
			time_arr = timer_data.split( "|" );

			if( 'undefined' == typeof timezone || '' == timezone ) {
				var curr_date = new Date();
				timezone = ( ( -1 * curr_date.getTimezoneOffset() ) / 60 );
			}



		if ( expiry_action == 'hide_popup' ) {

			var id      = element.closest( ".cp-popup-wrapper" ).find( 'input[name=style_id]' ).val(),
			modal   = $( '.cpro-onload[data-class-id=' + id + ']' );

			setTimeout(function() {
				jQuery( document ).trigger( 'closePopup', [modal, id] );
			}, 300);

		} else if( expiry_action == 'reset_timer' ) {

			cp_cookies.remove( style_name + '-'+self_id );
			cp_cookies.remove( style_name + '-'+self_id + '-timeInSec' );
			cp_cookies.remove( style_name + '-'+self_id + '-timePeriod' );

			var el_day = time_arr[0],
				el_hrs = time_arr[1],
				el_min = time_arr[2],
				el_sec = time_arr[3],
				currdate = '',
				timevar = 0;

			timevar = parseFloat( el_day*24*60*60 ) + parseFloat( el_hrs*60*60 ) + parseFloat( el_min*60 ) + parseFloat( el_sec );
			
			untilTime = '+' + timevar;
			$this.countdown( 'option', {until: untilTime} );
		}
	}

	cpro_invoke_countdown = function( countdowns ) {

		countdowns.each( function ( i ) {
			
			var $this 		= $( this ),
				parent 		= $this.closest( '.cp-popup-container' ),
				self_id 	= $this.attr( 'id' ),
				style_name 	= parent.attr( 'data-style' ),
				targetCountdown = $( this ).find( '.cp-target' ),
				timer_type = $this.attr( 'data-timer-type' ),
				timezone = $this.attr( 'data-timezone' ),
				timer_data = $this.attr( 'data-' + timer_type ),
				expiry_action = $this.attr( 'data-' + timer_type + '-action' ),
				untilTime = false,
				timerFormat = 'ODHMS',
				time_arr = timer_data.split( "|" ),
				timer_labels = cp_ajax.timer_labels,
				timer_labels_singular = cp_ajax.timer_labels_singular;

			
			if ( timer_type == 'evergreen' ) {

				timezone = '';

				var el_day = time_arr[0],
					el_hrs = time_arr[1],
					el_min = time_arr[2],
					el_sec = time_arr[3],
					currdate = '',
					timevar = 0;

				timevar = parseFloat( el_day*24*60*60 ) + parseFloat( el_hrs*60*60 ) + parseFloat( el_min*60 ) + parseFloat( el_sec );
				
				untilTime = '+' + timevar;
				
				if( cp_cookies.get( style_name + '-' + self_id ) == undefined ) {
					var time_in_sec = Math.round( new Date() / 1000 );
					cp_cookies.set( style_name + '-' + self_id, true );
					cp_cookies.set( style_name + '-' + self_id + "-timeInSec", time_in_sec );
					cp_cookies.set( style_name + '-' + self_id + "-timePeriod", timevar );
					
				} else {
					
					var cookie_time = cp_cookies.get( style_name + '-' + self_id + '-timeInSec' ),
						current_time = Math.round( new Date() / 1000 ),
						cookie_time_diff = current_time - cookie_time,
						remaining_time_period = timevar - cookie_time_diff;

					if ( remaining_time_period > 0 ) {
						untilTime = '+' + remaining_time_period;
					} else {
						
						if( expiry_action == 'reset_timer' ) {
							var time_in_sec = Math.round(new Date() / 1000);
							cp_cookies.set( style_name + '-' + self_id, true);
							cp_cookies.set( style_name + '-' + self_id + "-timeInSec", time_in_sec );
							cp_cookies.set( style_name + '-' + self_id + "-timePeriod", timevar);
						} else {
							return;
						}
					}
				}

				timerFormat = 'DHMS'
				
			} else {

				var el_year 	= time_arr[0],
					el_month 	= time_arr[1],
					el_day 		= time_arr[2],
					el_hrs 		= time_arr[3],
					el_min 		= time_arr[4];

				untilTime = new Date( el_year, el_month - 1, el_day, el_hrs, el_min );
				timerFormat = 'ODHMS';
			}

			if ( untilTime == 'Invalid Date' ) {
				return;
			}
			
			if( 'undefined' != typeof targetCountdown ) {
				if( 'undefined' == typeof timezone || '' == timezone ) {
					var curr_date = new Date();
					timezone = ( ( -1 * curr_date.getTimezoneOffset() ) / 60 );
				}

				targetCountdown.countdown( {
					until: untilTime,
					format: timerFormat,
					timeSeparator: ':',
					timezone: timezone,
					labels: timer_labels.split(","),
					labels1: timer_labels_singular.split(","),
					onExpiry: cpro_on_countdown_expire,
				    layout:
				    	'<div class="cp-countdown-holding">'
							+ '<div class="cp-countdown-digit-wrap">'
								+ '<span class="cp-countdown-digit">{onn}</span>'
							+ '</div>'
							+ '<div class="cp-countdown-unit-wrap">'
								+ '<span class="cp-countdown-unit">{ol}</span>'
							+ '</div>'
						+ '</div>'
						+'<div class="cp-countdown-holding">'
							+ '<div class="cp-countdown-digit-wrap">'
								+ '<span class="cp-countdown-digit">{dnn}</span>'
							+ '</div>'
							+ '<div class="cp-countdown-unit-wrap">'
								+ '<span class="cp-countdown-unit">{dl}</span>'
							+ '</div>'
						+ '</div>'
						+ '<div class="cp-countdown-holding">'
							+ '<div class="cp-countdown-digit-wrap">'
								+ '<span class="cp-countdown-digit">{hnn}</span>'
							+ '</div>'
							+ '<div class="cp-countdown-unit-wrap">'
								+ '<span class="cp-countdown-unit">{hl}</span>'
							+ '</div>'
						+ '</div>'
						+ '<div class="cp-countdown-holding">'
							+ '<div class="cp-countdown-digit-wrap">'
								+ '<span class="cp-countdown-digit">{mnn}</span>'
							+ '</div>'
							+ '<div class="cp-countdown-unit-wrap">'
								+ '<span class="cp-countdown-unit">{ml}</span>'
							+ '</div>'
						+ '</div>'
						+ '<div class="cp-countdown-holding">'
							+ '<div class="cp-countdown-digit-wrap">'
								+ '<span class="cp-countdown-digit">{snn}</span>'
							+ '</div>'
							+ '<div class="cp-countdown-unit-wrap">'
								+ '<span class="cp-countdown-unit">{sl}</span>'
							+ '</div>'
						+ '</div>'
				} );
			}
		} );
	}	

	jQuery( window ).on( 'cp_after_popup_open', function( e, modal, module_type, style ) {
		var countdowns = $( '.cp_style_' + style + ' .cp-countdown-field' ),
	    	cp_cookies = global_cp_cookies;

		if ( countdowns.length < 1 ) {
			return;
		}
		cpro_invoke_countdown( countdowns );
	} );

	var inline_countdowns = $( '.cpro-open .cp-countdown-field' ),
    	cp_cookies = global_cp_cookies;
    	
	if ( inline_countdowns.length < 1 ) {
		return;
	}

	cpro_invoke_countdown( inline_countdowns );
} );