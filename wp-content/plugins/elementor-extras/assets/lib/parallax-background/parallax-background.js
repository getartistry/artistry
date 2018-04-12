/**
 * Parallax Background version 1.2
 * https://github.com/erensuleymanoglu/parallax-background
 *
 * by Eren Suleymanoglu
 */
;(
	function( $, window, document, undefined ) {

		if ( ! window.requestAnimationFrame ) {
			return;
		}

		$.parallaxBackground = function( element, options ) {

			var defaults = {
				parallaxResizeWatch : null,
				parallaxBgImage 	: '',
				parallaxBgPosition	: 'center center',
				parallaxBgRepeat	: 'no-repeat',
				parallaxBgSize		: 'cover',
				parallaxSpeed		: 0.5,
				parallaxDirection	: 'down'
			};

			var plugin = this;

			plugin.opts = {};

			var $element 	= $(element),
				$parallax 	= null,
				$window 	= $(window),
				$parallax_inner, d, e, h, p, s, t, w,
				pt, pb, pl, pr, hd, wd, wh, ww,
				x = 0,
				y = 0,
				z = 0,
				i = 0,
				f = 1,
				lastScrollY = ( $window.get(0).pageYOffset || document.documentElement.scrollTop )  - ( document.documentElement.clientTop || 0 ),
				frameRendered = true,
				scroll_top = 0;

			plugin.init = function() {
				plugin.opts = $.extend({}, defaults, options);
				plugin._construct();
			};

			plugin._construct = function() {

				if (plugin.opts.parallaxSpeed > 1) {
					plugin.opts.parallaxSpeed = 1;
				} else if (plugin.opts.parallaxSpeed < 0) {
					plugin.opts.parallaxSpeed = 0;
				}

				plugin.setup();
				plugin.events();

			};

			plugin.render = function() {
				if ( frameRendered !== true ) {
					plugin.move();
				}
				window.requestAnimationFrame( plugin.render );
				frameRendered = true;
			}

			plugin.setup = function() {

				// Remove background image on parent element
				$element.css( 'background-image', 'none' );

				if ($element.find('.ee-parallax').length < 1) {
					$element.prepend('<div class="ee-parallax"></div>');
				}

				$parallax = $element.find('.ee-parallax');

				if ($parallax.find('.ee-parallax__inner').length < 1) {
					$parallax.prepend('<div class="ee-parallax__inner"></div>');
				}

				$parallax_inner = $parallax.find('.ee-parallax__inner');

				d = plugin.getElementSize($parallax);
				e = plugin.repositionBackground($parallax, d);

				$element.css({
					'z-index': 0,
				});

				$parallax_inner.css({
					'position'	: 'absolute',
					'width'		: d[0],
					'height'	: d[1],
					'transform'	: 'translate3d(' + e[0] + 'px, ' + e[1] + 'px, ' + e[2] + 'px)',
					'z-index'	: '-1'
				});

				if (plugin.opts.parallaxDirection === 'left' || plugin.opts.parallaxDirection === 'right') {
					p = 0;
					s = e[0];
				}

				if (plugin.opts.parallaxDirection === 'up' || plugin.opts.parallaxDirection === 'down') {
					p = 0;
					s = e[1];
				}

				if ( $element.visible(true) ) {
					scroll_top = $window.scrollTop();
				} else {
					scroll_top = $parallax.offset().top;
				}

			};

			plugin.refresh = function() {
				// Wait for Elementor's stretch function to execute
				setTimeout( function() { plugin.adjust(); }, 1);
				plugin.move();
			};

			plugin.events = function() {

				$(document).ready(function() {
					plugin.render();
				});
				
				// Bind to window resize
				$window.on( 'resize', plugin.refresh );

				// Bind to resize of custom element
				if ( plugin.opts.parallaxResizeWatch ) {
					plugin.opts.parallaxResizeWatch.resize( plugin.refresh );
				}

				$window.on( 'scroll', function() {
					if ( frameRendered === true ) {
						lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);
					}
					frameRendered = false;
				});

				// $window.on( 'scroll', function(){
				// 	plugin.move();
				// });
			};

			plugin.getElementSize = function( parent ) {
				w = parent.width();
				h = parent.height();

				wh = $window.height();
				ww = $window.width();

				if (isMobile()) {
					f = 2;
				}

				if (plugin.opts.parallaxDirection === 'left' || plugin.opts.parallaxDirection === 'right') {
					w += f * Math.ceil( ww * parseFloat( plugin.opts.parallaxSpeed ));
				}

				if (plugin.opts.parallaxDirection === 'up' || plugin.opts.parallaxDirection === 'down') {
					h += f * Math.ceil( wh * parseFloat( plugin.opts.parallaxSpeed ));
				}

				return [w, h];
			};

			plugin._getProgress = function() {
				return ( ( lastScrollY - $parallax_inner.offset().top + wh ) / ( wh + h ) );
			};

			plugin.repositionBackground = function( el, d ) {
				pl = parseInt( el.css('padding-left').replace('px', '') );
				pr = parseInt( el.css('padding-right').replace('px', ''));
				pt = parseInt( el.css('padding-top').replace('px', ''));
				pb = parseInt( el.css('padding-bottom').replace('px', ''));

				hd = (d[1] - el.outerHeight()) / 2;
				wd = (d[0] - el.outerWidth()) / 2;

				switch (plugin.opts.parallaxDirection) {
					case 'up':
						x = -pl;
						y = -(hd + pt);
						z = 0;
						break;
					case 'down':
						x = -pl;
						y = -(hd + pt);
						z = 0;
						break;
					case 'left':
						x = -(wd + pl);
						y = -pt;
						z = 0;
						break;
					case 'right':
						x = -(wd + pl);
						y = -pt;
						z = 0;
						break;
				}

				return [x, y, z];
			};

			plugin.adjust = function() {

				d = plugin.getElementSize( $parallax );
				e = plugin.repositionBackground( $parallax, d );

				$parallax_inner.css({
					'width' 	: d[0],
					'height'	: d[1],
					'transform'	: 'translate3d(' + e[0] + 'px, ' + e[1] + 'px, ' + e[2] + 'px)'
				});

			};

			plugin.move = function() {

				i = $window.scrollTop() - scroll_top;

				p = i * ( parseFloat( plugin.opts.parallaxSpeed ) / 4 );

				if (plugin.opts.parallaxDirection === 'up') {
					s += -p;
					t = 'translate3d(' + e[0] + 'px, ' + s + 'px, ' + e[2] + 'px)';
				}

				if (plugin.opts.parallaxDirection === 'down') {
					s += p;
					t = 'translate3d(' + e[0] + 'px, ' + s + 'px, ' + e[2] + 'px)';
				}

				if (plugin.opts.parallaxDirection === 'left') {
					s += p;
					t = 'translate3d(' + s + 'px, ' + e[1] + 'px, ' + e[2] + 'px)';
				}

				if (plugin.opts.parallaxDirection === 'right') {
					s += -p;
					t = 'translate3d(' + s + 'px, ' + e[1] + 'px, ' + e[2] + 'px)';
				}

				if ( $element.visible(true) ) {
					$parallax_inner.css({
						'width'		: d[0],
						'height'	: d[1],
						'transform'	: t
					});
				}

				scroll_top = $window.scrollTop();
			};

			plugin.destroy = function() {
				$parallax.remove();
				$parallax_inner.remove();
				$element.removeData( 'parallaxBackground' );
			};

			plugin.init();

		};

		$.fn.parallaxBackground = function(options) {
			return this.each(function() {

				$.fn.parallaxBackground.destroy = function() {
					if( 'undefined' !== typeof( plugin ) ) {
						$(this).data( 'parallaxBackground' ).destroy();
						$(this).removeData( 'parallaxBackground' );
					}
				}

				if (undefined === $(this).data('parallaxBackground')) {
					var plugin = new $.parallaxBackground(this, options);
					$(this).data('parallaxBackground', plugin);
				}
			});
		};

		/**
		 * Mobile devices
		 *
		 * @returns {boolean}
		 */
		function isMobile() {
			if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) ||
				/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
				return true;
			} else {
				return false;
			}
		}

	}

)( jQuery, window, document );