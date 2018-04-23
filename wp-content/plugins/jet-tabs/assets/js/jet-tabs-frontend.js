( function( $, elementor, settings ) {

	'use strict';

	var JetTabs = {

		init: function() {
			var widgets = {
				'jet-tabs.default': JetTabs.tabsInit,
				'jet-accordion.default': JetTabs.accordionInit,
				'jet-image-accordion.default': JetTabs.imageAccordionInit,
			};

			$.each( widgets, function( widget, callback ) {
				elementor.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});
		},

		tabsInit: function( $scope ) {
			var $target         = $( '.jet-tabs', $scope ).first(),
				$controlWrapper = $( '.jet-tabs__control-wrapper', $target ).first(),
				$contentWrapper = $( '.jet-tabs__content-wrapper', $target ).first(),
				$controlList    = $( '> .jet-tabs__control', $controlWrapper ),
				$contentList    = $( '> .jet-tabs__content', $contentWrapper ),
				settings        = $target.data( 'settings' ) || {},
				toogleEvents    = 'mouseenter mouseleave',
				scrollOffset;

			if ( 'click' === settings['event'] ) {
				addClickEvent();
			} else {
				addMouseEvent();
			}

			function addClickEvent() {
				$controlList.on( 'click.jetTabs', function() {
					var $this = $( this ),
						tabId = +$this.data( 'tab' );

					switchTab( tabId );
				});
			}

			function addMouseEvent() {
				if ( 'ontouchend' in window || 'ontouchstart' in window ) {
					$controlList.on( 'touchstart', function( event ) {
						scrollOffset = $( window ).scrollTop();
					} );

					$controlList.on( 'touchend', function( event ) {
						var $this = $( this ),
							tabId = +$this.data( 'tab' );

						if ( scrollOffset !== $( window ).scrollTop() ) {
							return false;
						}

						switchTab( tabId );
					} );

				} else {
					$controlList.on( 'mouseenter', function( event ) {
						var $this = $( this ),
							tabId = +$this.data( 'tab' );

						switchTab( tabId );
					} );
				}
			}

			function switchTab( curentIndex ) {

				$controlList.each( function( index ) {
					var $this    = $( this ),
						tabId    = +$this.data( 'tab' );

					if ( curentIndex === tabId ) {
						$this.addClass( 'active-tab' );
					} else {
						$this.removeClass( 'active-tab' );
					}

				} );

				$contentList.each( function( index ) {
					var $this    = $( this ),
						tabId    = +$this.data( 'tab' );

					if ( curentIndex === tabId ) {
						$this.addClass( 'active-content' );
					} else {
						$this.removeClass( 'active-content' );
					}

				} );

			}
		},// tabsInit end

		accordionInit: function( $scope ) {
			var $target       = $( '.jet-accordion', $scope ).first(),
				$controlsList = $( '> .jet-accordion__inner > .jet-toggle > .jet-toggle__control', $target ),
				settings      = $target.data( 'settings' ),
				$toggleList   = $( '> .jet-accordion__inner > .jet-toggle', $target );

			$controlsList.on( 'click.jetAccordion', function() {
				var $this   = $( this ),
					$toggle = $this.closest( '.jet-toggle' );

				if ( settings['collapsible'] ) {

					if ( ! $toggle.hasClass( 'active-toggle' ) ) {

						$toggleList.removeClass( 'active-toggle' );
						$toggle.addClass( 'active-toggle' );
					}
				} else {
					$toggle.toggleClass( 'active-toggle' );
				}

			});
		},// accordionInit end

		imageAccordionInit: function( $scope) {
			var $target  = $( '.jet-image-accordion', $scope ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );

			instance = new jetImageAccordion( $target, settings );
			instance.init();
		}// imageAccordionInit end

	};

	/**
	 * jetImageAccordion Class
	 *
	 * @return {void}
	 */
	window.jetImageAccordion = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$itemsList      = $( '.jet-image-accordion__item', $instance ),
			itemslength     = $itemsList.length,
			defaultSettings = {
				orientation: 'vertical',
				activeSize:  {
					size: 50,
					unit: '%'
				},
				duration: 500,
				activeItem: -1
			},
			settings        = settings || {};

		/**
		 * Checking options, settings and options merging
		 */
		settings = $.extend( defaultSettings, settings );

		/**
		 * Layout Build
		 */
		this.layoutBuild = function( ) {

			$itemsList.css( {
				'transition-duration': settings.duration + 'ms'
			} );

			$itemsList.each( function( index ) {
				if ( index === settings['activeItem'] ) {
					$( this ).addClass( 'active-accordion' );
					self.layoutRender();
				}
			} );

			$( '.jet-image-accordion__image-instance', $itemsList ).imagesLoaded().progress( function( instance, image ) {
				var $image      = $( image.img ),
					$parentItem = $image.closest( '.jet-image-accordion__item' ),
					$loader     = $( '.jet-image-accordion__item-loader', $parentItem );

				$image.addClass( 'loaded' );

				$loader.fadeTo( 250, 0, function() {
					$( this ).remove();
				} );
			});

			self.layoutRender();
			self.addEvents();
		}

		/**
		 * Layout Render
		 */
		this.layoutRender = function( $accordionItem ) {
			var $accordionItem = $accordionItem || false,
				activeSize     = settings.activeSize.size,
				basis          = ( 100 / itemslength ).toFixed(2),
				grow           = activeSize / ( ( 100 - activeSize  ) / ( itemslength - 1 ) );

			$( '.jet-image-accordion__item:not(.active-accordion)', $instance ).css( {
				'flex-grow': 1
			} );

			$( '.active-accordion', $instance ).css( {
				'flex-grow': grow
			} );
		}

		this.addEvents = function() {
			var toogleEvents = 'mouseenter',
				scrollOffset = $( window ).scrollTop();

			if ( 'ontouchend' in window || 'ontouchstart' in window ) {
				$itemsList.on( 'touchstart.jetImageAccordion', function( event ) {
					scrollOffset = $( window ).scrollTop();
				} );

				$itemsList.on( 'touchend.jetImageAccordion', function( event ) {
					event.stopPropagation();

					var $this = $( this );

					if ( scrollOffset !== $( window ).scrollTop() ) {
						return false;
					}

					if ( ! $this.hasClass( 'active-accordion' ) ) {
						$itemsList.removeClass( 'active-accordion' );
						$this.addClass( 'active-accordion' );
					} else {
						$itemsList.removeClass( 'active-accordion' );
					}

					self.layoutRender();
				} );
			} else {
				$itemsList.on( 'mouseenter', function( event ) {
					var $this = $( this );

					if ( ! $this.hasClass( 'active-accordion' ) ) {
						$itemsList.removeClass( 'active-accordion' );
						$this.addClass( 'active-accordion' );
					}

					self.layoutRender();
				} );
			}

			$instance.on( 'mouseleave.jetImageAccordion', function( event ) {
				$itemsList.removeClass( 'active-accordion' );

				self.layoutRender();
			} );

			/*$( document ).on( 'touchend.jetImageAccordion', function( event ) {
				$itemsList.removeClass( 'active-accordion' );
				self.layoutRender();
			} );*/
		}

		/**
		 * Init
		 */
		this.init = function() {
			self.layoutBuild();
		}
	}

	$( window ).on( 'elementor/frontend/init', JetTabs.init );

}( jQuery, window.elementorFrontend, window.JetTabsSettings ) );
