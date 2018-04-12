( function( $ ) {

	/**
	 * Function for Before After Slider animation.
	 *
	 */
	var UAELBASlider = function( $element ) {
		
		$element.css( 'width', '' );
		$element.css( 'height', '' );

		max = -1;

		$element.find( "img" ).each(function() {
			if( max < $(this).width() ) {
				max = $(this).width();
			}
		});

		$element.css( 'width', max + 'px' );
	}

	/**
	 * Function for Fancy Text animation.
	 *
	 */
	var UAELFancyText = function() {
		
		var id 					= $( this ).data( 'id' );
		var $this 				= $( this ).find( '.uael-fancy-text-node' );
		var animation			= $this.data( 'animation' );
		var fancystring 		= $this.data( 'strings' );
		var nodeclass           = '.elementor-element-' + id;

		var typespeed 			= $this.data( 'type-speed' );
		var backspeed 			= $this.data( 'back-speed' );
		var startdelay 			= $this.data( 'start-delay' );
		var backdelay 			= $this.data( 'back-delay' );
		var loop 				= $this.data( 'loop' );
		var showcursor 			= $this.data( 'show_cursor' );
		var cursorchar 			= $this.data( 'cursor-char' );

		var speed 				= $this.data('speed');
		var pause				= $this.data('pause');
		var mousepause			= $this.data('mousepause');
		
		if ( 'type' == animation ) {
			$( nodeclass + ' .uael-typed-main' ).typed({
				strings: fancystring,
				typeSpeed: typespeed,
				startDelay: startdelay,
				backSpeed: backspeed,
				backDelay: backdelay,
				loop: loop,
				showCursor: showcursor,
				cursorChar: cursorchar,
	        });
		} else if ( 'slide' == animation ) {
			
			$( nodeclass + ' .uael-slide-main' ).vTicker('init', {
					strings: fancystring,
					speed: speed,
					pause: pause,
					mousePause: mousepause,	
			});
		}		
	}

	/**
	 * Before After Slider handler Function.
	 *
	 */
	var WidgetUAELBASliderHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope )
			return;

		var selector = $scope.find( '.uael-ba-container' );
		var initial_offset = selector.data( 'offset' );
		var move_on_hover = selector.data( 'move-on-hover' );
		var orientation = selector.data( 'orientation' );

		$scope.css( 'width', '' );
		$scope.css( 'height', '' );

		if( 'yes' == move_on_hover ) {
			move_on_hover = true;
		} else {
			move_on_hover = false;
		}

		$scope.imagesLoaded( function() {

			UAELBASlider( $scope );

			$scope.find( '.uael-ba-container' ).twentytwenty(
	            {
	                default_offset_pct: initial_offset,
	                move_on_hover: move_on_hover,
	                orientation: orientation
	            }
	        );

	        $( window ).resize( function( e ) {
	        	UAELBASlider( $scope );
	        } );
		} );
	};

	/**
	 * Fancy text handler Function.
	 *
	 */
	var WidgetUAELFancyTextHandler = function( $scope, $ ) {
		if ( 'undefined' == typeof $scope ) {
			return;
		}
		var node_id = $scope.data( 'id' );
		var viewport_position	= 90;
		var selector = $( '.elementor-element-' + node_id );
		
		if( typeof elementorFrontend.waypoint !== 'undefined' ) {
			elementorFrontend.waypoint(
				selector,
				UAELFancyText,
				{
					offset: viewport_position + '%'
				}
			);
		}
	};

	/**
	 * Radio Button Switcher JS Function.
	 *
	 */
	var WidgetUAELContentToggleHandler = function( $scope, $ ) {
		if ( 'undefined' == typeof $scope ) {
			return;
		}
		var $this           = $scope.find( '.uael-rbs-wrapper' );
		var node_id 		= $scope.data( 'id' );
		var rbs_section_1   = $scope.find( ".uael-rbs-section-1" );
		var rbs_section_2   = $scope.find( ".uael-rbs-section-2" );
		var main_btn        = $scope.find( ".uael-main-btn" );
		var switch_type     = $( main_btn ).attr( 'data-switch-type' );
		var current_class;
		
		switch ( switch_type ) {
			case 'round_1':
				current_class = '.uael-switch-round-1';
				break;
			case 'round_2':
				current_class = '.uael-switch-round-2';
				break;
			case 'rectangle':
				current_class = '.uael-switch-rectangle';
				break;
			case 'label_box':
				current_class = '.uael-switch-label-box';
				break;
			default:
				current_class = 'No Class Selected';
				break;
		}

		var rbs_switch      = $scope.find( current_class );

		if( $( rbs_switch ).is( ':checked' ) ) {
			$( rbs_section_1 ).hide();	
		} else {
			$( rbs_section_2 ).hide();
		}

		$( rbs_switch ).click(function(){
	        $( rbs_section_1 ).toggle();
	        $( rbs_section_2 ).toggle();
	    });
	};

	$( window ).on( 'elementor/frontend/init', function () {

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-fancy-heading.default', WidgetUAELFancyTextHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-ba-slider.default', WidgetUAELBASliderHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-content-toggle.default', WidgetUAELContentToggleHandler );

		if( elementorFrontend.isEditMode() ) {

			elementor.channels.data.on( 'element:after:duplicate element:after:remove', function( e, arg ) {
				$( '.elementor-widget-uael-ba-slider' ).each( function() {
					WidgetUAELBASliderHandler( $( this ), $ );
				} );
			} );
		}
		
	});
} )( jQuery ); 