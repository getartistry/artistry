// -- hotips
// @license hotips v1.0.0 | MIT | Namogo 2017 | https://www.namogo.com
// --------------------------------
;(
    function( $, window, document, undefined ) {

		$.hotips = function(element, options) {

			var defaults = {
				scope 			: null,

				position 		: 'top',
				trigger 		: 'hover',
				delayIn			: 0,
				delayOut		: 0,
				speed 			: 0.2,
				content 		: false,

				class 			: null,
			};

			var plugin = this;

			plugin.opts = {};

			var $window			= null,
				$tooltip		= null,
				$document		= null,

				target			= element,
				$target			= $(element),
				$content 		= null,

				is_open			= false,

				_tWidth			= 0,
				_tLeft			= 0,
				_tTop			= 0,
				_tBottom		= 0,
				_tRight 		= 0,
				_offset 			= -20;


			plugin.init = function() {

				if ( options.delayIn === null ) {
					options.delayIn = defaults.delayIn;
				}

				if ( options.delayOut === null ) {
					options.delayOut = defaults.delayOut;
				}

				plugin.opts = $.extend({}, defaults, options);
				plugin._construct();
			};

			plugin._construct = function() {

				if ( ! plugin.opts.scope ) {
					$window		= $(window);
					$document 	= $(document);
				} else {
					$window		= plugin.opts.scope;
					$document 	= plugin.opts.scope;
				}

				// Override position?
				if ( undefined === typeof $target.data( 'hotips-position' ) ) {
					plugin.opts.position = $target.data( 'hotips-position' );
				}

				$tooltip 	= $( '<div class="hotip-tooltip"></div>' );

				plugin.setup();
				plugin.events();

			};

			plugin.addClasses = function() {

				var classes = '',
					data_classes = $target.attr( 'data-hotips-class' ),
					opts_classes = plugin.opts.classes;

				if ( data_classes ) {
					classes += data_classes;
				} else {
					if ( opts_classes ) {
						data_classes += opts_classes;
					}
				}

				$tooltip.addClass( classes );
			}

			plugin.setup = function() {

				if ( ! plugin.setContent() )
					return;

				plugin.position();

			};

			plugin.setContent = function() {

				if ( $target.attr( 'data-hotips-content' ) ) {

					var $content_element = $document.find( $target.attr( 'data-hotips-content' ) );

					if ( ! $content_element.length || $.trim( $content_element.html() ) === '' ) {
						return false;
					}

					$content = $content_element.html();

				} else if ( plugin.opts.content ) {

					$content = plugin.opts.content;

				} else {
					return false;
				}

				return true;
			};

			plugin.events = function() {

				if ( ! $content )
					return;

				$(window).resize( plugin.position );

				if ( ! plugin.opts.trigger || plugin.opts.trigger === 'hover' ) {

					$target.on( 'mouseenter', plugin.show );
					$target.on( 'mouseleave', plugin.hide );

				} else if ( plugin.opts.trigger === 'click' ) {

					$target.on( 'click', plugin.show );
					$document.on( 'mouseup', function( event ) {
						if ( ! $tooltip.is( event.target ) && $tooltip.has( event.target ).length === 0 ) {
							plugin.hide();
						}
					});

				}
			};

			plugin.position = function() {

				if ( $window.width() < $tooltip.outerWidth() )
					$tooltip.css( 'max-width', $window.width() );

				if ( ! plugin.opts.position ) {
					plugin.opts.position = 'bottom';
				}

				var _to = plugin.opts.position,
					_at = '';

				if ( plugin.opts.position === 'bottom' ) { /* BOTTOM */

					_tTop = $target.offset().top + $target.outerHeight() + 10;
					_tLeft = $target.offset().left + ( $target.outerWidth() / 2 ) - ( $tooltip.outerWidth() / 2 );
					_offset = 20;

				} else if ( plugin.opts.position === 'top' ) { /* TOP */

					_tTop = $target.offset().top - $tooltip.outerHeight() - 10;
					_tLeft = $target.offset().left + ( $target.outerWidth() / 2 ) - ( $tooltip.outerWidth() / 2 );
					_offset = -20;

				} else if ( plugin.opts.position === 'left' ) { /* LEFT */

					_tTop = $target.offset().top + ( $target.outerHeight() / 2 ) - ( $tooltip.outerHeight() / 2 );
					_tLeft = $target.offset().left - $tooltip.outerWidth() - 10;
					_offset = -20;

				} else if ( plugin.opts.position === 'right' ) { /* RIGHT */

					_tTop = $target.offset().top + ( $target.outerHeight() / 2 ) - ( $tooltip.outerHeight() / 2 );
					_tLeft = $target.offset().left + $target.outerWidth() + 10;
					_offset = 20;
				}

				var _rTop = _tTop - $(window).scrollTop(),
					_rLeft = _tLeft,
					_rRight = $(window).width() - ( _tLeft + $tooltip.outerWidth() ),
					_rBottom = $(window).height() + $(window).scrollTop() - ( _tTop + $tooltip.outerHeight() );

				if ( _rTop < 0 ) {
					if ( plugin.opts.position === 'left' || plugin.opts.position === 'right' ) {
						_tTop = $target.offset().top;
						_at = 'top';
					} else {
						_tTop = $target.offset().top + $target.outerHeight() + 10;
						_to = 'bottom';
						_offset = 20;
					}
				}

				if ( _rBottom < 0 ) {
					if ( plugin.opts.position === 'left' || plugin.opts.position === 'right' ) {
						_tTop = $target.offset().top + $target.outerHeight() - $tooltip.outerHeight();
						_at = 'bottom';
					} else {
						_tTop = $target.offset().top - $tooltip.outerHeight() - 10;
						_to = 'top';
						_offset = -20;
					}
				}

				if ( _rLeft < 0 ) {

					if ( plugin.opts.position === 'left' ) {
						_tLeft = $target.offset().left + $target.outerWidth() + 10;
						_to = 'right';
						_offset = 20;
					} else {
						_tLeft = $target.offset().left;
						_at = 'left';
					}
				}

				if ( _rRight < 0 ) {

					if ( plugin.opts.position === 'right' ) {
						_tLeft = $target.offset().left - $tooltip.outerWidth() - 10;
						_to = 'left';
						_offset = -20;
					} else {
						_at = 'right';

						if ( plugin.opts.position === 'top' || plugin.opts.position === 'bottom' ) {
							_tLeft = $target.offset().left + $target.outerWidth() - $tooltip.outerWidth();
						} else {
							_tLeft = $target.offset().left + $target.outerWidth();
						}
					}
				}

				$tooltip.css({
					top 	: _tTop,
					left 	: _tLeft,
				});

				$tooltip.removeClass( 'to--top to--bottom to--right to--left at--left at--right at--top at--bottom' );
				$tooltip.addClass( 'to--' + _to );
				$tooltip.addClass( 'at--' + _at );
			};

			plugin.destroy = function() {

				// First remove the tooltip
				plugin.hide();

				// Unbinds
				$window.unbind( plugin.position() );
				$target.unbind( 'mouseleave', plugin.hide );
				$tooltip.unbind( 'click', plugin.hide );

				if ( ! plugin.opts.trigger || plugin.opts.trigger === 'hover' ) {
					$target.unbind( 'mouseenter', plugin.show );
				} else if ( plugin.opts.trigger === 'click' ) {
					$target.unbind( 'click', plugin.show );
				}

			};

			plugin.show = function() {

				// Exit to prevent opening when already open
				if ( is_open === true )
					return;

				if ( ! $content )
					return;

				plugin.addClasses();

				// Add html to tooltip making sure the html is not encoded
				$tooltip.html( $content );

				// Append tooltip to body
				$document.find('body').append( $tooltip );

				// Reposition if size changes
				if ( typeof $.fn.resize !== 'undefined' && plugin.opts.scope ) {
					$tooltip.resize( function() {
						plugin.position();
					});
				}

				// Update position
				plugin.position();

				// Animate it in
				TweenMax.killTweensOf( $tooltip );

				if ( plugin.opts.position === 'top' ) {
					
					TweenMax.fromTo( $tooltip, plugin.opts.speed,
						{ top : _tTop + _offset, autoAlpha : 0 },
						{ delay	: plugin.opts.delayIn, top : _tTop, autoAlpha : 1, onComplete : function() { is_open = true; }
					});

				} else if ( plugin.opts.position === 'right' ) {

					TweenMax.fromTo( $tooltip, plugin.opts.speed,
						{ left : _tLeft + _offset, autoAlpha : 0 },
						{ delay	: plugin.opts.delayIn, left : _tLeft, autoAlpha : 1, onComplete : function() { is_open = true; }
					});

				} else if ( plugin.opts.position === 'bottom' ) {
					
					TweenMax.fromTo( $tooltip, plugin.opts.speed,
						{ top : _tTop + _offset, autoAlpha : 0 },
						{ delay	: plugin.opts.delayIn, top : _tTop, autoAlpha : 1, onComplete : function() { is_open = true; }
					});

				} else if ( plugin.opts.position === 'left' ) {
					
					TweenMax.fromTo( $tooltip, plugin.opts.speed,
						{ left : _tLeft + _offset, autoAlpha : 0 },
						{ delay	: plugin.opts.delayIn, left : _tLeft, autoAlpha : 1, onComplete : function() { is_open = true; }
					});

				}
			}

			plugin.hide = function() {

				// Animate it out
				TweenMax.killTweensOf( $tooltip );

				if ( plugin.opts.position === 'top' ) {
					
					TweenMax.to( $tooltip, plugin.opts.speed, { top : _tTop + _offset, autoAlpha : 0, delay : plugin.opts.delayOut,
						onComplete : function() {
							$tooltip.remove();
							is_open = false;
						}
					});

				} else if ( plugin.opts.position === 'right' ) {

					TweenMax.to( $tooltip, plugin.opts.speed, { left : _tLeft + _offset, autoAlpha : 0, delay : plugin.opts.delayOut,
						onComplete : function() {
							$tooltip.remove();
							is_open = false;
						}
					});

				} else if ( plugin.opts.position === 'bottom' ) {
					
					TweenMax.to( $tooltip, plugin.opts.speed, { top : _tTop + _offset, autoAlpha : 0, delay : plugin.opts.delayOut,
						onComplete : function() {
							$tooltip.remove();
							is_open = false;
						}
					});

				} else if ( plugin.opts.position === 'left' ) {
					
					TweenMax.to( $tooltip, plugin.opts.speed, { left : _tLeft + _offset, autoAlpha : 0, delay : plugin.opts.delayOut,
						onComplete : function() {
							$tooltip.remove();
							is_open = false;
						}
					});

				}

			}

			plugin.init();

		};

		$.fn.hotips = function(options) {

			return this.each(function() {

				$.fn.hotips.destroy = function() {
					if( 'undefined' !== typeof( plugin ) ) {
						$(this).data('hotips').destroy();
						$(this).removeData('hotips');
					}
				}

				if (undefined === $(this).data('hotips')) {
					var plugin = new $.hotips(this, options);
					$(this).data('hotips', plugin);
				}
			});

		};

	}

)( jQuery, window, document );