// -- unfold
// @license unfold v1.0.0 | MIT | Namogo 2017 | https://www.namogo.com
// --------------------------------
;(
    function( $, window, document, undefined ) {

		$.unfold = function(element, options) {

			var defaults = {
				scope 				: $(window),
				text_closed 		: 'Read more',
				text_open 			: 'Read less',
				isolate 			: true,
				duration_unfold		: 0.5,
				duration_fold 		: 0.5,
				easing_unfold 		: 'easeInOut',
				easing_fold 		: 'easeInOut',
				animation_unfold 	: 'Power4',
				animation_fold 		: 'Power4',
				steps_unfold 		: 10,
				steps_fold 			: 10,
				slow_unfold 		: 10,
				slow_fold 			: 10,
				visible_lines 		: false,
				visible_percentage 	: 100,
			};

			var plugin = this;

			plugin.opts = {};

			var $window			= null,
				$document		= null,
				$body 			= null,

				target			= element,
				$target			= $(element),
				$content 		= $target.find('.ee-unfold__content'),
				$mask 			= $target.find('.ee-unfold__mask'),
				$separator 		= $target.find('.ee-unfold__separator'),
				$trigger 		= $target.find('.ee-unfold__trigger .ee-button'),
				$label 			= $trigger.find('.ee-button-text'),
				$icon_closed 	= $trigger.find('.ee-unfold__icon--closed'),
				$icon_open 		= $trigger.find('.ee-unfold__icon--open'),

				latestKnownScrollY  = -1,
				latestKnownWindowW  = -1,
				currentScrollY 		= 0,
				ticking 			= false,
				updateAF			= null,
				calcAF 				= null,

				_cWidth 			= null,
				_mHeight 			= 0,
				_cHeight 			= null,

				_is_open 			= false;

			plugin.init = function() {

				plugin.opts = $.extend({}, defaults, options);
				plugin._construct();
			};

			plugin._construct = function() {

				$window				= plugin.opts.scope;
				$body 				= $('body');
				latestKnownWindowW 	= $window.width();

				plugin.setup();
				plugin.events();
				// plugin.requestTick();

			};

			plugin.hexToRgba = function( hex, alpha ) {
				var r = parseInt(hex.slice(1, 3), 16),
				g = parseInt(hex.slice(3, 5), 16),
				b = parseInt(hex.slice(5, 7), 16);

				if (alpha) {
					return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
				} else {
					return "rgb(" + r + ", " + g + ", " + b + ")";
				}
			}

			plugin.setup = function() {
				
				plugin.update();

			};

			plugin.events = function() {

				// $window.on( 'scroll', plugin.onScroll );

				$window.on( 'resize', function() {
					if ( $(this).width() !== latestKnownWindowW && ! _is_open ) {
						plugin.setup();
					}

					latestKnownWindowW = $(this).width();

					// plugin.requestTick();
				});

				$trigger.on( 'click', function() {
					if ( _is_open === true ) {
						plugin.close();
					} else {
						plugin.open();
					}
				});

			};

			plugin.onScroll = function() {
				currentScrollY = $window.scrollTop();
				plugin.requestTick();
			};

			plugin.requestTick = function() {
				
				if ( ! ticking ) {
					updateAF = requestAnimationFrame( plugin.update );
				}

				ticking = true;
			};

			plugin.getLineHeight = function( element ) {

				var style = window.getComputedStyle( element ),
					lineHeight = null,
					placeholder = document.createElement( element.nodeName );

					placeholder.setAttribute("style","margin:0px;padding:0px;font-family:" + style.fontFamily + ";font-size:" + style.fontSize);
					placeholder.innerHTML = "test";
					placeholder = element.parentNode.appendChild( placeholder );

					lineHeight = placeholder.clientHeight;

					placeholder.parentNode.removeChild( placeholder );

				return lineHeight;
			};

			plugin.update = function() {

				var $elems  = $content.find( "> *" );

				if ( false !== plugin.opts.visible_lines ) {

					var counter = 0;

					_mHeight = 0;

					$elems.each( function( index ) {

						if ( counter < plugin.opts.visible_lines ) {

							var lineHeight 	= plugin.getLineHeight( this ),
								lines 		= $(this).height() / lineHeight,
								style 		= window.getComputedStyle( this );

							if ( lines > 1 && isFinite( lines ) ) {

								var lineCounter = 0;

								for( i = 1; i <= lines; i++ ) { 


									if ( counter < plugin.opts.visible_lines ) {
										_mHeight += lineHeight;

										counter++;
										lineCounter++;
									}
								}

								if ( lineCounter === lines ) {
									_mHeight += parseInt( style.marginTop ) + parseInt( style.marginBottom );
								}

							} else {

								_mHeight += $(this).outerHeight( true );

								counter++;

							}
						}
					});
				} else {
					_mHeight = plugin.opts.visible_percentage * $content.outerHeight( true ) / 100;
				}

				_is_open = false;

				$mask.css({ height: _mHeight });
			};

			plugin.destroy = function() {

				plugin.clearProps();
				cancelAnimationFrame( updateAF );
				$window.off( 'scroll', plugin.onScroll );
				$item.removeData( 'unfold' );

			};

			plugin.open = function() {

				var _t_unfold = new TimelineLite({
						onComplete : function() {
							_is_open = true;
							$label.html( plugin.opts.text_open );
							if ( $icon_open.length ) {
								$icon_closed.hide();
								$icon_open.show();
							}
						}
					}),

					_animation = $window[0][plugin.opts.animation_unfold];

					if ( plugin.opts.animation_unfold === 'SlowMo' ) {
						_animation = _animation.config( plugin.opts.slow_unfold, 0.7, false );
					} else if ( plugin.opts.animation_unfold === 'SteppedEase' ) {
						_animation = _animation.config( plugin.opts.steps_unfold );
					} else {
						if ( plugin.opts.easing_unfold ) { _animation = _animation[plugin.opts.easing_unfold]; }
					}

				_t_unfold

				.add( 'open' )

				.to( $mask, plugin.opts.duration_unfold, {
					height 		: $content.outerHeight( true ),
					ease 		: _animation,
					clearProps	: "all",
				}, 'open' )

				.to( $separator, plugin.opts.duration_unfold / 3, {
					autoAlpha 	: 0,
					ease 		: Power1.easeIn,
				}, "-=0.1");

			}

			plugin.close = function() {

				var _t_fold = new TimelineLite({
						onComplete : function() {
							_is_open = false;
							$label.html( plugin.opts.text_closed );
							if ( $icon_open.length ) {
								$icon_closed.show();
								$icon_open.hide();
							}
						}
					})

					_animation = $window[0][plugin.opts.animation_fold];

					if ( plugin.opts.animation_fold === 'SlowMo' ) {
						_animation = _animation.config( plugin.opts.slow_fold, 0.7, false );
					} else if ( plugin.opts.animation_fold === 'SteppedEase' ) {
						_animation = _animation.config( plugin.opts.steps_fold );
					} else {
						if ( plugin.opts.easing_fold ) { _animation = _animation[plugin.opts.easing_fold]; }
					}
					
				_t_fold

				.add( 'close' )

				.to( $separator, plugin.opts.duration_fold / 3, {
					autoAlpha 	: 1,
					ease 		: Power1.easeOut,
				}, 'close')
				
				.to( $mask, plugin.opts.duration_fold, {
					height  : _mHeight,
					ease 	: _animation,
				}, '-=0.1');
			}

			plugin.init();

		};

		$.fn.unfold = function(options) {

			return this.each(function() {

				$.fn.unfold.destroy = function() {
					if( 'undefined' !== typeof( plugin ) ) {
						$(this).data('unfold').destroy();
						$(this).removeData('unfold');
					}
				}

				if (undefined === $(this).data('unfold')) {
					var plugin = new $.unfold(this, options);
					$(this).data('unfold', plugin);
				}
			});

		};

	}

)( jQuery, window, document );