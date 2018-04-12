// -- Image Comparison
// @license Image Comparison v1.0.0 | MIT | Namogo 2017 | https://www.namogo.com
// --------------------------------
;(
	function( $, window, document, undefined ) {

		$.imageComparison = function(element, options) {

			var defaults = {
				scope 			: $(window),
				editMode 		: false,
				clickToMove 	: false,
				clickLabels 	: false,
				animateClick 	: true,
			};

			var plugin = this;

			plugin.opts = {};

			var $window			= null,
				$viewport		= $(window),
				$element		= $(element),

				$labelOriginal  = null,
				$labelModified  = null,

				dragging 		= false,
				scrolling 		= false,
				resizing 		= false;


			plugin.init = function() {
				plugin.opts = $.extend({}, defaults, options);
				plugin._construct();
			};

			plugin._construct = function() {

				$window		= plugin.opts.scope;
				$labelOriginal = $element.find('.ee-image-comparison__label[data-type="original"]');
				$labelModified = $element.find('.ee-image-comparison__label[data-type="modified"]');

				plugin.checkPosition();
				plugin.setup();
				plugin.events();

			};

			plugin.checkPosition = function() {
				if ( $element.is('.is--visible') )
					return;

				if( $window.scrollTop() + $window.height() * 1 > $element.offset().top) {
					$element.addClass('is--visible');
					TweenMax.from( $element.find('.ee-image-comparison__image'), 0.7, { width: '0%', ease: Back.easeOut.config( 0.7 ), clearProps: "all" } );
				}

				scrolling = false;
			};

			plugin.checkLabel = function() {
				plugin.updateLabel( $labelOriginal, $element.find('.ee-image-comparison__handle'), $element.find('.ee-image-comparison__image'), 'left');
				plugin.updateLabel( $labelModified, $element.find('.ee-image-comparison__handle'), $element.find('.ee-image-comparison__image'), 'right');

				resizing = false;
			};

			plugin.updateLabel = function( label, handle, resizeElement, position ) {
				if ( position == 'left' ) {
					( label.offset().left + label.outerWidth() + handle.outerWidth() / 2 < resizeElement.offset().left + resizeElement.outerWidth() ) ? label.removeClass('is--hidden') : label.addClass('is--hidden') ;
				} else {
					( label.offset().left > resizeElement.offset().left + resizeElement.outerWidth()  + handle.outerWidth() / 2 ) ? label.removeClass('is--hidden') : label.addClass('is--hidden') ;
				}
			};

			plugin.setup = function() {
				plugin.drags(
					$element.find('.ee-image-comparison__handle'),
					$element.find('.ee-image-comparison__image'),
					$element,
					$labelOriginal,
					$labelModified
				);
			};

			plugin.events = function() {

				$window.on('scroll', function() {
					if( ! scrolling ) {
						scrolling = true;
						( ! window.requestAnimationFrame )
							? setTimeout( function() { plugin.checkPosition(); }, 100 )
							: requestAnimationFrame( function() { plugin.checkPosition(); } );
					}
				});

				$window.on('resize', function(){
					if( ! resizing ) {
						resizing = true;
						( !window.requestAnimationFrame )
							? setTimeout( function() { plugin.checkLabel(); }, 100)
							: requestAnimationFrame( function() { plugin.checkLabel(); });
					}
				});

				if ( plugin.opts.clickToMove && ! plugin.opts.editMode ) {

					if ( plugin.opts.clickLabels ) {
						$labelOriginal.on( 'click', function( e ) {

							plugin.onBeforeLabelClick();
							plugin.updatePosition(
								0,
								$labelOriginal,
								$labelModified,
								$element.find('.ee-image-comparison__image'),
								true
							);
							plugin.onAfterLabelClick();
						});

						$labelModified.on( 'click', function( e ) {
							
							plugin.onBeforeLabelClick();
							plugin.updatePosition(
								$element.outerWidth(),
								$labelOriginal,
								$labelModified,
								$element.find('.ee-image-comparison__image'),
								true
							);
							plugin.onAfterLabelClick();
						});
					}

					$element.on( 'click', function( e ) {

						if ( $( e.target ).is( '.ee-image-comparison__label' ) )
							return;

						$element.find('.ee-image-comparison__image').addClass('resizable');
						$element.find('.ee-image-comparison__handle').addClass('draggable');

						var widthValue = ( e.pageX - $element.offset().left ) / $element.outerWidth() * 100 + '%';

						plugin.updatePosition(
							widthValue,
							$labelOriginal,
							$labelModified,
							$element.find('.ee-image-comparison__image'),
							plugin.opts.animateClick
						);

						$element.find('.ee-image-comparison__image').removeClass('resizable');
						$element.find('.ee-image-comparison__handle').removeClass('draggable');
					});
				}

			};

			plugin.onBeforeLabelClick = function() {
				$element.find('.ee-image-comparison__image').addClass('resizable');
				$element.find('.ee-image-comparison__handle').addClass('draggable');
			};

			plugin.onAfterLabelClick = function() {
				$element.find('.ee-image-comparison__image').removeClass('resizable');
				$element.find('.ee-image-comparison__handle').removeClass('draggable');
			};

			plugin.drags = function( dragElement, resizeElement, container, labelOriginal, labelModified ) {
				dragElement.on( "mousedown vmousedown", function( e ) {

					dragElement.addClass('draggable');
					resizeElement.addClass('resizable');

					var dragWidth 			= dragElement.outerWidth(),
						xPosition 			= dragElement.offset().left + dragWidth - e.pageX,
						containerOffset 	= container.offset().left,
						containerWidth 		= container.outerWidth(),
						minLeft 			= containerOffset - dragWidth / 2,
						maxLeft 			= containerOffset + containerWidth - dragWidth / 2;
					
					dragElement.parents().on("mousemove vmousemove", function(e) {

						if( ! dragging ) {
							dragging = true;

							( ! window.requestAnimationFrame )
								? setTimeout( function() {
									plugin.animateDraggedHandle( e, xPosition, dragWidth, minLeft, maxLeft, containerOffset, containerWidth, resizeElement, labelOriginal, labelModified);
								}, 100)
								: requestAnimationFrame( function() {
									plugin.animateDraggedHandle( e, xPosition, dragWidth, minLeft, maxLeft, containerOffset, containerWidth, resizeElement, labelOriginal, labelModified);
								} );
						}

					}).on("mouseup vmouseup", function( e ) {
						dragElement.removeClass('draggable');
						resizeElement.removeClass('resizable');
					});

					e.preventDefault();

				}).on( "mouseup vmouseup", function( e ) {
					dragElement.removeClass('draggable');
					resizeElement.removeClass('resizable');
				});				
			};

			plugin.animateDraggedHandle = function( e, xPosition, dragWidth, minLeft, maxLeft, containerOffset, containerWidth, resizeElement, labelOriginal, labelModified ) {

				var leftValue = e.pageX + xPosition - dragWidth;

				if( leftValue < minLeft ) {
					leftValue = minLeft;
				} else if ( leftValue > maxLeft ) {
					leftValue = maxLeft;
				}

				var widthValue = (leftValue + dragWidth / 2 - containerOffset) * 100 / containerWidth + '%';
				
				$element.find('.draggable').css( 'left', widthValue).on( "mouseup vmouseup", function() {
					$(this).removeClass('draggable');
					resizeElement.removeClass('resizable');
				});

				plugin.updatePosition( widthValue, labelOriginal, labelModified, resizeElement ,false );

				dragging = false;
			};

			plugin.updatePosition = function( widthValue, labelOriginal, labelModified, resizeElement, animate ) {

				var $draggable = $element.find('.draggable'),
					$resizable = $element.find('.resizable');

				if ( animate ) {
					TweenMax.to( $draggable, 0.2, { left : widthValue } );
					TweenMax.to( $resizable, 0.2, { width : widthValue } );
				} else {
					$draggable.css( 'left', widthValue ); 
					$resizable.css( 'width', widthValue ); 
				}
				plugin.updateLabel( labelModified, $element.find('.ee-image-comparison__handle'), resizeElement, 'left');
				plugin.updateLabel( labelOriginal, $element.find('.ee-image-comparison__handle'), resizeElement, 'right');
			};

			plugin.destroy = function() {

				// $window.off( 'scroll', plugin.update );
				$element.removeData( 'imageComparison' );

			};

			plugin.init();

		};

		$.fn.imageComparison = function(options) {
			return this.each(function() {
				if (undefined === $(this).data('imageComparison')) {
					var plugin = new $.imageComparison(this, options);
					$(this).data('imageComparison', plugin);
				}
			});

		};

	}

)( jQuery, window, document );