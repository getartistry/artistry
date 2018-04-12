// -- Switcher Plugin
// @license Switcher v1.0.0 | MIT | Namogo 2018 | https://www.namogo.com
// --------------------------------
;(
	function( $, window, document, undefined ) {

		$.eeSwitcher = function(element, options) {

			var defaults = {
				scope               : $(window),
				mediaEffect         : 'swipeLeft',
				contentEffect       : 'slideLeft',
				contentEffectZoom 	: false,
				contentStagger 		: true,
				speed               : 1,
				duration            : 3,
				autoplay            : false,
				loop 				: false,
				changeBackground 	: false,
				background 			: null,
				entranceAnimation 	: true,
				cancelOnInteraction : true,
				loaderRetractSpeed 	: 0.2,
				defaultIndex        : 0,
				titleSelector       : '.ee-switcher__title',
				mediaWrapperSelector: '.ee-switcher__media',
				mediaSelector       : '.ee-switcher__media__items',
				mediaItemSelector   : '.ee-switcher__media__item',
				imageItemSelector 	: '.ee-switcher__media__item img',
				navSelector         : '.ee-switcher__nav',
				navItemSelector     : '.ee-switcher__nav__item',
				navLoaderSelector   : '.ee-loader__progress',
				contentSelector     : '.ee-switcher__items',
				contentItemSelector : '.ee-switcher__items__item',
				arrowsSelector     	: '.ee-switcher__arrows',
				arrowNextSelector 	: '.ee-arrow--next',
				arrowPrevSelector 	: '.ee-arrow--prev',
			};

			var plugin = this;

			plugin.opts = {};

			var $window         = null,
				$body 			= null,
				$viewport       = $(window),
				$element        = $(element),

				dragging        = false,
				scrolling       = false,
				resizing        = false,

				latestKnownScrollY          = -1,
				latestKnownWindowHeight     = -1,
				latestKnownContentHeight    = -1,
				currentScrollY              = 0,
				currentWindowHeight         = 0,
				currentContentHeight        = 0,
				ticking                     = false,
				updateAF                    = null,
				rafTimer                    = null,
				_rafStartTime 				= null,
				animation 					= null,

				st                  = [],

				$nav                = null,
				$navItems           = null,
				$media              = null,
				$mediaWrapper 		= null,
				$mediaItems         = null,
				$content            = null,
				$contentItems       = null,
				$imageItems			= null,
				$titleItems 		= null,
				$arrows 			= null,
				$arrowNext 			= null,
				$arrowPrev 			= null,
				$arrowCircle 		= null, 
				$arrowTimer 		= null,

				$thisNavItem        = null,
				$thisNavLoader      = null,
				$thisMediaItem      = null,
				$thisImageItem		= null,
				$thisContentItem    = null,

				$lastNavItem        = null,
				$lastMediaItem      = null,
				$lastImageItem		= null,
				$lastNavLoader      = null,
				$lastContentItem    = null,

				$background 		= null,

				_current            = 0,
				_prev               = null,
				_next               = null,
				_last               = 0,
				_total              = null,
				_direction          = null,
				_circleLength 		= 0,

				_startTime          = null, 
				_duration 			= 0, 		// Total pause duration between transitionss
				_timerCancelled 	= false, 	// Timer has been cancelled by user interaction
				_timerRunning 		= false, 	// Timer is running between transitions
				_appeared 			= false, 	// Element has appearead at least once in the viewport
				_defaultBgColor 	= 'rgba(255,255,255,0)',

				_isAnimating        = false, 	// Transition between slides is occuring
				_isPlaying 			= false, 	// Autoplay is active and is switching slides between timers

				_effects 			= null,

				_easing 			= CustomEase.create("custom", "M0,0 C0.446,0 0.034,1 1,1");


			plugin.init = function() {
				plugin.opts = $.extend({}, defaults, options);
				plugin._construct();
			};

			plugin._construct = function() {

				$window                 = plugin.opts.scope;
				$body 					= $window.find( 'body' );

				currentScrollY          = $window.scrollTop();
				currentWindowHeight     = $(window).height();

				plugin.setup();
				plugin.requestTick();
				plugin.events();

			};

			plugin.requestTick = function() {
				if ( ! ticking ) {
					updateAF = requestAnimationFrame( plugin.refresh );
				}
				ticking = true;
			};

			plugin.setEffects = function( _dir ) {
				_effects = {
					media: {
						'coverLeft' : {
							prepareImages 		: {},
							prepareItems		: { x: '100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '100%' : '-100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { x: _dir === 'next' ? '-10%' : '10%', ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'coverRight' : {
							prepareImages 		: {},
							prepareItems		: { x: '-100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '-100%' : '100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { x: _dir === 'next' ? '10%' : '-10%', ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'coverTop' : {
							prepareImages 		: {},
							prepareItems		: { y: '100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '100%' : '-100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { y: _dir === 'next' ? '-10%' : '10%', ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'coverBottom' : {
							prepareImages 		: {},
							prepareItems		: { y: '-100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '-100%' : '100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { y: _dir === 'next' ? '10%' : '-10%', ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'uncoverLeft' : {
							prepareImages		: {},
							prepareItems		: { x: '100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '-10%' : '10%', autoAlpha: 1 },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { x: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'uncoverRight' : {
							prepareImages		: {},
							prepareItems		: { x: '-100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '10%' : '-10%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { x: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'uncoverBottom' : {
							prepareImages		: {},
							prepareItems		: { y: '100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '-10%' : '10%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { y: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'uncoverTop' : {
							prepareImages		: {},
							prepareItems		: { y: '-100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '10%' : '-10%', autoAlpha: 1 },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { y: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'fade' : {
							prepareImages 		: {},
							prepareItems		: { autoAlpha: 0, scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							prepareFirst 		: { autoAlpha: ( plugin.opts.entranceAnimation ) ? 0 : 1, scale: 1 },
							prepareFirstImage 	: {},
							prepareNext 		: {},
							prepareNextImage 	: {},
							animateOut 			: { autoAlpha: 0, scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateIn 			: { autoAlpha: 1, scale: 1, ease: _easing },
							animateImageOut 	: {},
							animateImageIn 		: {},
						},
						'flipHorizontal' : {
							// wait 				: true,
							prepareImages 		: { transformStyle : "preserve-3d", rotationY : 180, scale: 0.2 },
							prepareItems		: { perspective : 1300 },
							prepareFirst 		: {},
							prepareFirstImage 	: { rotationY: ( plugin.opts.entranceAnimation ) ? 180 : 0, scale: ( plugin.opts.entranceAnimation ) ? 0.2 : 1 },
							prepareNext 		: {},
							prepareNextImage 	: { rotationY : 180, scale: 0.2 },
							animateOut 			: {},
							animateIn 			: {},
							animateImageOut 	: { rotationY : -180, scale: 0.2, ease: _easing },
							animateImageIn 		: { rotationY : 0, scale: 1, ease: _easing },
						},
						'flipVertical' : {
							// wait 				: true,
							prepareImages 		: { transformStyle : "preserve-3d", rotationX : 180, scale: 0.2 },
							prepareItems		: { perspective : 1300 },
							prepareFirst 		: {},
							prepareFirstImage 	: { rotationX: ( plugin.opts.entranceAnimation ) ? 180 : 0, scale: ( plugin.opts.entranceAnimation ) ? 0.2 : 1 },
							prepareNext 		: {},
							prepareNextImage 	: { rotationX : 180, scale: 0.2 },
							animateOut 			: {},
							animateIn 			: {},
							animateImageOut 	: { rotationX : -180, scale: 0.2, ease: _easing },
							animateImageIn 		: { rotationX : 0, scale: 1, ease: _easing },
						},
						'slideLeft' : {
							prepareImages 		: {},
							prepareItems		: { x: '100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '100%' : '-100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { x: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'slideRight' : {
							prepareImages 		: {},
							prepareItems		: { x: '-100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '-100%' : '100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { x: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'slideTop' : {
							prepareImages 		: {},
							prepareItems		: { y: '100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '100%' : '-100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { y: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'slideBottom' : {
							prepareImages 		: {},
							prepareItems		: { y: '-100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '-100%' : '100%' },
							prepareNextImage 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1 },
							animateOut 			: { y: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateImageIn 		: { scale: 1, ease: _easing },
						},
						'swipeLeft' : {
							prepareItems		: {},
							prepareImages 		: { x: _dir === 'next' ? '100%' : '-100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { x: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%', scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							prepareNextImage 	: { x: _dir === 'next' ? '-100%' : '100%', scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateOut 			: {},
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageIn 		: { x: '0%', scale: 1, ease: _easing },
						},
						'swipeRight' : {
							prepareItems		: {},
							prepareImages 		: { x: _dir === 'next' ? '-100%' : '100%' },
							prepareFirst 		: { x: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { x: ( plugin.opts.entranceAnimation ) ? '100%' : '0%', scale: 1 },
							prepareNext 		: { x: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							prepareNextImage 	: { x: _dir === 'next' ? '100%' : '-100%', scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateOut 			: {},
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateIn 			: { x: '0%', ease: _easing },
							animateImageIn 		: { x: '0%', scale: 1, ease: _easing },
						},
						'swipeTop' : {
							prepareItems		: {},
							prepareImages 		: { y: _dir === 'next' ? '100%' : '-100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '100%' : '0%' },
							prepareFirstImage	: { y: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%', scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							prepareNextImage 	: { y: _dir === 'next' ? '-100%' : '100%', scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateOut 			: {},
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageIn 		: { y: '0%', scale: 1, ease: _easing },
						},
						'swipeBottom' : {
							prepareItems		: {},
							prepareImages 		: { y: _dir === 'next' ? '-100%' : '100%' },
							prepareFirst 		: { y: ( plugin.opts.entranceAnimation ) ? '-100%' : '0%' },
							prepareFirstImage	: { y: ( plugin.opts.entranceAnimation ) ? '100%' : '0%', scale: 1 },
							prepareNext 		: { y: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							prepareNextImage 	: { y: _dir === 'next' ? '100%' : '-100%', scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateOut 			: {},
							animateImageOut 	: { scale: plugin.opts.contentEffectZoom ? 1.1 : 1, ease: _easing },
							animateIn 			: { y: '0%', ease: _easing },
							animateImageIn 		: { y: '0%', scale: 1, ease: _easing },
						},
					},
					content: {
						'slideLeft' : {
							wait 			: true,
							prepareItems 	: { autoAlpha: 0 },
							prepareFirst 	: { autoAlpha: 1 },
							prepareNext 	: { autoAlpha: 1 },
							animateLastFrom : { x: '0%', ease: _easing },
							animateLastTo 	: { x: _dir === 'next' ? '-150%' : '150%', ease: _easing },
							animateNextFrom : { x: _dir === 'next' ? '150%' : '-150%', ease: _easing },
							animateNextTo 	: { x: '0%', ease: _easing },
						},
						'slideRight' : {
							wait 			: true,
							prepareItems 	: { autoAlpha: 0 },
							prepareFirst 	: { autoAlpha: 1 },
							prepareNext 	: { autoAlpha: 1 },
							animateLastFrom : { x: '0%', ease: _easing },
							animateLastTo 	: { x: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							animateNextFrom : { x: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							animateNextTo 	: { x: '0%', ease: _easing },
						},
						'slideTop' : {
							wait 			: false,
							prepareItems 	: { autoAlpha: 0 },
							prepareFirst 	: { autoAlpha: 1 },
							prepareNext 	: { autoAlpha: 1 },
							animateLastFrom : { y: '0%', ease: _easing },
							animateLastTo 	: { y: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							animateNextFrom : { y: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							animateNextTo 	: { y: '0%', ease: _easing },
						},
						'slideBottom' : {
							wait 			: false,
							prepareItems 	: { autoAlpha: 0 },
							prepareFirst 	: { autoAlpha: 1 },
							prepareNext 	: { autoAlpha: 1 },
							animateLastFrom : { y: '0%', ease: _easing },
							animateLastTo 	: { y: _dir === 'next' ? '100%' : '-100%', ease: _easing },
							animateNextFrom : { y: _dir === 'next' ? '-100%' : '100%', ease: _easing },
							animateNextTo 	: { y: '0%', ease: _easing },
						},
						'fade' : {
							wait 			: true,
							prepareItems	: { autoAlpha: 0 },
							prepareFirst 	: { autoAlpha: 1 },
							prepareNext 	: { autoAlpha: 1 },
							animateLastFrom : { autoAlpha: 1, ease: _easing },
							animateLastTo 	: { autoAlpha: 0, ease: _easing },
							animateNextFrom : { autoAlpha: 0, ease: _easing },
							animateNextTo 	: { autoAlpha: 1, ease: _easing },
						},
						'scale' : {
							wait 			: true,
							prepareItems	: { autoAlpha: 0 },
							prepareFirst 	: { autoAlpha: 1 },
							prepareNext 	: { autoAlpha: 1 },
							animateLastFrom : { scale: 1, ease: _easing },
							animateLastTo 	: { scale: 0, ease: _easing },
							animateNextFrom : { scale: 0, ease: _easing },
							animateNextTo 	: { scale: 1, ease: _easing },
						}
					},
				};
			};

			plugin.setup = function() {

				$nav                = $element.find( plugin.opts.navSelector );
				$navItems           = $nav.find( plugin.opts.navItemSelector );
				$navLoaders         = $nav.find( plugin.opts.navLoaderSelector );
				$media              = $element.find( plugin.opts.mediaSelector );
				$mediaWrapper 		= $element.find( plugin.opts.mediaWrapperSelector );
				$imageItems 		= $element.find( plugin.opts.imageItemSelector );
				$titleItems 		= $element.find( plugin.opts.titleSelector );
				$mediaItems         = $media.find( plugin.opts.mediaItemSelector );
				$content            = $element.find( plugin.opts.contentSelector );
				$contentItems       = $content.find( plugin.opts.contentItemSelector );
				$arrows 			= $element.find( plugin.opts.arrowsSelector );
				$arrowNext 			= $arrows.find( plugin.opts.arrowNextSelector );
				$arrowPrev 			= $arrows.find( plugin.opts.arrowPrevSelector );
				$arrowCircle 		= $arrowNext.find( '.ee-arrow__svg' );
				$arrowTimer 		= $arrowCircle.find( '.ee-arrow__circle--loader' );

				$background 		= plugin.opts.background;
				
				_current    		= plugin.opts.defaultIndex;
				_total      		= $mediaItems.length - 1;
				_last       		= _current;
				_prev       		= _current < 1 ? _total : _current - 1;
				_next       		= _current >= _total ? 0 : _current + 1;
				_defaultBgColor 	= $background.css( 'backgroundColor' );

				$thisNavItem        = $navItems.eq( _current );
				$thisNavLoader      = $thisNavItem.find( plugin.opts.navLoaderSelector );
				$thisMediaItem      = $mediaItems.eq( _current );
				$thisImageItem 		= $imageItems.eq( _current );
				$thisContentItem    = $contentItems.eq( _current );

				$thisNavItem.addClass('is--active');
				$thisMediaItem.addClass('is--active is--current').data('active', true);
				$thisContentItem.addClass('is--active');

				plugin.setEffects( _direction );
				plugin.setArrowsClasses();

				// Prepare items
				TweenMax.set( $mediaItems, _effects.media[ plugin.opts.mediaEffect ].prepareItems );
				TweenMax.set( $imageItems, _effects.media[ plugin.opts.mediaEffect ].prepareImages );
				TweenMax.set( $contentItems, _effects.content[ plugin.opts.contentEffect ].prepareItems );

				// Prepare background
				if ( ! plugin.opts.entranceAnimation ) {
					plugin.prepareBackground();
				}

				// Prepare next items
				if ( ! plugin.opts.entranceAnimation ) {
					TweenMax.set( $thisMediaItem, _effects.media[ plugin.opts.mediaEffect ].prepareFirst );
					TweenMax.set( $thisImageItem, _effects.media[ plugin.opts.mediaEffect ].prepareFirstImage );
					TweenMax.set( $thisContentItem, _effects.content[ plugin.opts.contentEffect ].prepareFirst );
				} else {
					TweenMax.set( [ $nav, $arrows ], { autoAlpha: 0 } );
				}

				$element.find('.ee-switcher').addClass( 'is--loaded' );

				$contentItems.each( function( index ) {
					st[index] = new SplitText( $(this).find( plugin.opts.titleSelector ), {
						type : ['chars', 'words'],
					});

					$( st[index].chars ).addClass('ee-switcher__title__char').wrapInner('<div></div>');
				});

				currentContentHeight = $thisContentItem.outerHeight();
			};

			plugin.events = function() {

				$element.on('switcher:goto', function( e, i ) {

					var dir = i > _current ? 'next' : 'prev';

					plugin.goTo( i, dir );
				});

				$window.on('scroll', plugin.onScroll );
				$(window).on('resize', plugin.onResize );

				$titleItems.resize( plugin.onResize );

				$navItems.each( function( i, e ) {

					var $this = $(this);

					$this.on( 'click', function( e ) {

						var dir = i > _current ? 'next' : 'prev';

						plugin.onAfterInteraction();
						plugin.goTo( i, dir );
					});
				});

				$arrowNext.on( 'click', function( e ) {
					e.preventDefault();
					plugin.onArrowClick( 'next' );
				});

				$arrowPrev.on( 'click', function( e ) {
					e.preventDefault();
					plugin.onArrowClick( 'prev' );
				});

				$element._appear({ force_process: true }).on( '_appear', plugin.onAppear );

			};

			plugin.onAppear = function() {

				if ( ! _appeared ) {
					if ( plugin.opts.entranceAnimation ) {
							plugin.animate( 0, 'next' );
					} else {
						if ( plugin.opts.autoplay && ! _timerCancelled && ! _isPlaying )
							plugin.play();
					}

					_appeared = true;
				}
			};

			plugin.onArrowClick = function( direction ) {

				var _limit 		= direction === 'next' ? _total : 0,
					_direction 	= direction === 'next' ? _next : _prev;

				if ( ! plugin.opts.loop && ! _isAnimating && _current === _limit ) {
					return;
				}

				plugin.onAfterInteraction();
				plugin.goTo( _direction, direction );
			};

			plugin.onAfterInteraction = function() {
				if ( plugin.opts.cancelOnInteraction ) {
					_timerCancelled = true;
				}
			}

			plugin.onScroll = function() {
				currentScrollY = $window.scrollTop();

				plugin.requestTick();
			};

			plugin.onResize = function() {
				currentScrollY          = $window.scrollTop();
				currentContentHeight    = $thisContentItem.outerHeight();
				currentWindowHeight     = $window.height();

				plugin.requestTick();
			};

			plugin.prepareBackground = function() {

				if ( plugin.opts.changeBackground ) {

					// Set the old default color as a data attribute
					$background.attr( 'data-switcher-old-background', _defaultBgColor );

					if ( ! plugin.opts.entranceAnimation ) { // Entrance animation will take care of this

						// Set the background color directly
						TweenMax.set( $background, { backgroundColor: plugin.getCurrentBackgroundColor() } ); // Set the current item background
					}
				}
			};

			plugin.changeBackground = function( animation ) {
				animation.to( $background, plugin.opts.speed, { backgroundColor: plugin.getCurrentBackgroundColor() }, 'animateAll' );
			};

			plugin.revertBackground = function() {

				// Remove any exising data on the background elements
				$background
					// .css( 'background-color', $background.data( 'switcher-old-background' ) )
					.removeAttr( 'data-switcher-old-background' )
					.get(0).style.removeProperty('background-color');
			};

			plugin.getCurrentBackgroundColor = function() {
				
				var backgroundColor = $thisNavItem.data('switcher-background');

				if ( '' === backgroundColor || undefined === typeof backgroundColor ) {
					backgroundColor = _defaultBgColor;
				}

				return backgroundColor;
			};

			plugin.setupClasses = function() {

				$content.addClass('is--animating');

				$navItems.removeClass('is--active');
				$mediaItems.removeClass('is--active is--current').data('active', false);
				$contentItems.removeClass('is--active');

				$lastMediaItem.addClass('is--last');

				$thisNavItem.addClass('is--active');
				$thisMediaItem.addClass('is--animating is--current').data('active', true);
				$thisContentItem.addClass('is--animating');

				plugin.setArrowsClasses();
			};

			plugin.setArrowsClasses = function() {

				$arrowNext.removeClass( 'ee-arrow--disabled' );
				$arrowPrev.removeClass( 'ee-arrow--disabled' );

				if ( ! plugin.opts.loop ) {
					if ( _current === _total ) $arrowNext.addClass( 'ee-arrow--disabled' );
					if ( _current === 0 ) $arrowPrev.addClass( 'ee-arrow--disabled' );
				}
			};

			plugin.play = function() {
				_startTime = window.performance.now();
				_duration = Math.floor( plugin.opts.duration * 10 );

				_isPlaying = true;
				_timerRunning = true;

				plugin.timer();
			};

			plugin.stop = function() {
				_timerRunning = false;

				plugin.resetNavLoaders();
			};

			plugin.timer = function() {

				if ( ! _timerRunning )
					return;

				var time = window.performance.now(),
					diff = Math.round( time - _startTime ),
					percent = Math.round( diff / _duration );

				percent = percent > 100 ? 100 : percent;

				if ( percent < 100 && ! _isAnimating ) {

					plugin.setCircleLength();
					plugin.updateCircleLoader( percent );
					plugin.updateMenuLoader( percent );

					setTimeout( plugin.timer, _duration );
				} else {
					plugin.stop();
					plugin.goTo( _next, 'next' );
				}
			}

			// TODO: Revert to requestAnimationFrame method

			// plugin.play = function() {

			// 	_startTime = window.performance.now() || Date.now(),
			// 	_duration = plugin.opts.duration * 1000;

			// 	rafTimer = window.requestAnimationFrame( plugin.timer );

			// 	_timerRunning = true;
			// };

			// plugin.stop = function() {

			// 	window.cancelAnimationFrame( rafTimer );

			// 	_timerRunning = false;

			// 	plugin.resetNavLoaders();
			// };

			// plugin.timer = function( time ) {

			// 	if ( ! _rafStartTime ) {
			// 		_rafStartTime = time;
			// 	}

			// 	var diff 	= Math.round( time - _startTime ),
			// 		percent = Math.round( diff / _duration * 100 );

			// 	percent = percent > 100 ? 100 : percent;

			// 	plugin.setCircleLength();
			// 	plugin.updateCircleLoader( percent );
			// 	plugin.updateMenuLoader( percent );

			// 	if ( diff < _duration && ! _isAnimating ) {
			// 		rafTimer = window.requestAnimationFrame( plugin.timer );
			// 	} else {
			// 		_rafStartTime = null;
			// 		plugin.resetNavLoaders();
			// 		plugin.goTo( _next, 'next' );
			// 	}
			// };

			plugin.updateMenuLoader = function( percent ) {
				TweenMax.to( $thisNavLoader, 0.1, { width: percent + '%' });
			};

			plugin.updateCircleLoader = function( percent ) {
				var stroke 		= parseInt( $arrowTimer.css( 'stroke-width' ), 10 ),
					dashOffset 	= 0;

				dashOffset = _circleLength - _circleLength * ( percent / 100 );

				$arrowTimer.addClass( 'is--animating' );
				TweenMax.to( $arrowTimer, 0.05, { strokeDasharray : _circleLength, strokeDashoffset : dashOffset } );
			};

			plugin.resetNavLoaders = function( pause ) {

				var pause = pause || false;

				TweenMax.to( $arrowTimer, plugin.opts.loaderRetractSpeed, { strokeDashoffset : _circleLength, ease: _easing, onComplete: function() {
					if ( pause )
						$arrowTimer.removeClass( 'is--animating' );
				} } );

				if ( ! $lastNavLoader )
					return;

				TweenMax.set( $lastNavLoader, { float: 'right' } );
				TweenMax.to( $lastNavLoader, plugin.opts.loaderRetractSpeed, { width: '0%', ease: _easing, clearProps: "float,width" } );
			};

			plugin.setCircleLength = function() {

				if ( ! $arrowCircle.length )
					return;

				var circleWidth = parseInt( $arrowCircle.outerWidth() );

					_circleLength = Math.round( 2 * Math.PI * ( circleWidth / 2 ) ) + 2;
			};

			plugin.goTo = function( index, direction ) {

				var _is_current_active = $mediaItems.eq( index ).data('active');

				if ( ! _is_current_active && ! _isAnimating ) {
					plugin.animate( index, direction );
				}

			};

			plugin.animate = function( index, direction ) {

				plugin.onBeforeTransition( index, direction );

				var _currentIsLast = _last === _current,
					_mediaEffect = _effects.media[ plugin.opts.mediaEffect ],
					_contentEffect = _effects.content[ plugin.opts.contentEffect ],

					_staggerAmount = plugin.opts.contentStagger ? 0.05 : 0,
					_lastChars = $( st[_last].chars ).find( '> *' ),
					_thisChars = $( st[index].chars ).find( '> *' );

				if ( _currentIsLast ) {
					TweenMax.set( $thisMediaItem, _mediaEffect.prepareFirst );
					TweenMax.set( $thisImageItem, _mediaEffect.prepareFirstImage );
					TweenMax.set( $thisContentItem, _contentEffect.prepareFirst );
				} else {
					TweenMax.set( $thisMediaItem, _mediaEffect.prepareNext );
					TweenMax.set( $thisImageItem, _mediaEffect.prepareNextImage );
					TweenMax.set( $thisContentItem, _contentEffect.prepareNext );
				}

				// Animate items
				animation = new TimelineMax({ onComplete: plugin.onAfterTransition });

				var waitMedia 	= _mediaEffect.wait ? plugin.opts.speed / 2 : 0,
					mediaSpeed 	= _mediaEffect.wait ? plugin.opts.speed / 2 : plugin.opts.speed;

				// Animate items
				animation.to( $lastMediaItem, plugin.opts.speed, _mediaEffect.animateOut, 'animateAll' );
				animation.to( $lastImageItem, plugin.opts.speed, _mediaEffect.animateImageOut, 'animateAll' );
				animation.to( $thisMediaItem, plugin.opts.speed, _mediaEffect.animateIn, 'animateAll+=' + waitMedia );
				animation.to( $thisImageItem, plugin.opts.speed, _mediaEffect.animateImageIn, 'animateAll+=' + waitMedia );

				// Animate page background
				if ( plugin.opts.changeBackground ) {
					plugin.changeBackground( animation );
				}

				var outDuration 	= plugin.opts.speed,
					inDuration 		= plugin.opts.speed,
					waitDuration 	= _contentEffect.wait ? plugin.opts.speed / 2 : 0;

				// Animate chars
				if ( ! _currentIsLast ) {
					animation.staggerFromTo( _lastChars, outDuration,
						_contentEffect.animateLastFrom,
						_contentEffect.animateLastTo,
						_staggerAmount, "animateAll" );
				} else {
					waitDuration = 0;
				}

				animation.staggerFromTo( _thisChars, inDuration,
					_contentEffect.animateNextFrom,
					_contentEffect.animateNextTo,
					_staggerAmount, "animateAll+=" + waitDuration );
			};

			plugin.onBeforeTransition = function( index, direction ) {
				_last 			= _current;
				_current 		= index;

				_isAnimating 	= true;

				_prev       	= _current < 1 ? _total : _current - 1;
				_next       	= _current >= _total ? 0 : _current + 1;
				_direction  	= direction;

				plugin.setEffects( _direction );
				
				$thisNavItem        = $navItems.eq( _current );
				$thisNavLoader      = $thisNavItem.find( plugin.opts.navLoaderSelector );
				$thisMediaItem      = $mediaItems.eq( _current );
				$thisImageItem 		= $imageItems.eq( _current );
				$thisContentItem    = $contentItems.eq( _current );

				$lastNavItem        = $navItems.eq( _last );
				$lastNavLoader      = $lastNavItem.find( plugin.opts.navLoaderSelector );
				$lastMediaItem      = $mediaItems.eq( _last );
				$lastImageItem 		= $imageItems.eq( _last );
				$lastContentItem    = $contentItems.eq( _last );

				plugin.stop();
				plugin.setupClasses();
			};

			plugin.onAfterTransition = function() {
				$content.removeClass('is--animating');

				$navItems.removeClass('is--last');
				$mediaItems.removeClass('is--last');
				$contentItems.removeClass('is--last');

				$thisMediaItem.removeClass('is--animating');
				$thisContentItem.removeClass('is--animating');

				$thisMediaItem.addClass('is--active');
				$thisContentItem.addClass('is--active');

				if ( _last !== _current ) {
					TweenMax.set( $lastContentItem, { autoAlpha: 0 } );
				} else if ( plugin.opts.entranceAnimation ) {
					TweenMax.to( [ $nav, $arrows ], 0.3, { autoAlpha: 1, ease: _easing } );
				}

				_isAnimating    = false;

				if ( plugin.opts.autoplay && ! _timerCancelled ) {
					if ( ! plugin.opts.loop ) {
						if ( _current === _total ) {
							_isPlaying = false;
							return;
						}
						plugin.play();
					} else {
						plugin.play();
					}
				}
			};

			plugin.getContentHeight = function() {
				var contentHeight = 0;

				$contentItems.each( function(){
					var itemHeight = $(this).outerHeight();

					if ( itemHeight > contentHeight ) {
						contentHeight = itemHeight;
					}
				});

				return contentHeight;
			};

			plugin.setContentHeight = function( _height ) {

				var newHeight = plugin.getContentHeight();

				if ( ! _height ) {
					_height = newHeight;
				} else {
					newHeight = _height;
				}

				$content.css({ height: _height });
				latestKnownContentHeight = newHeight;
			};

			plugin.refresh = function() {

				ticking = false;

				if ( latestKnownContentHeight !== currentContentHeight ) {
					plugin.setContentHeight();
				}

				if ( ( latestKnownScrollY !== currentScrollY ) || ( latestKnownWindowHeight !== currentWindowHeight ) ) {

					latestKnownScrollY      = currentScrollY;
					latestKnownWindowHeight = currentWindowHeight;

					if ( plugin.opts.autoplay && _appeared ) {
						if ( ! $element.visible( true, false, 'vertical' ) ) {
							if ( _timerRunning ) {
								plugin.stop();
							}
						} else if ( ! _timerCancelled && ! _timerRunning && _isPlaying ) {
							plugin.play();
						}
					}
				}
			};

			plugin.destroy = function() {

				// Make sure autoplay is stopped
				plugin.stop()

				// Kill the current animation if it's defined
				if ( animation ) animation.kill();

				// Cancel animation frames
				// window.cancelAnimationFrame( updateAF );
				// window.cancelAnimationFrame( rafTimer );

				// Unbind events
				$navItems.unbind();
				$arrowNext.unbind();
				$arrowPrev.unbind();

				$window.off( 'scroll', plugin.onScroll );
				$window.off( 'mousemove', plugin.parallax );
				$viewport.off( 'resize', plugin.onResize );

				plugin.revertBackground();

				// Remove plugin from data
				$element.removeData( 'eeSwitcher' );
			};

			plugin.init();

		};

		$.fn.inlineStyle = function ( prop ) {
			return this.prop("style")[ $.camelCase( prop ) ];
	    };

		$.fn.eeSwitcher = function(options) {
			return this.each(function() {

				if (undefined === $(this).data('eeSwitcher')) {
					var plugin = new $.eeSwitcher( this, options );
					$(this).data( 'eeSwitcher', plugin );
				}

			});
		};

	}

)( jQuery, window, document );