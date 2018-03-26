(function($) {
	$(document).on('elementor/render/justified-gallery',function(e, data){
		var gallery   = $(data);
		var rowHeight = gallery.data('row-height');
		var margins   = gallery.data('margins');
		var border    = gallery.data('border');
		var lastRow   = gallery.data('last-row');
		var randomize = gallery.data('randomize');
		var selector  = gallery.data('selector');
		gallery.removeClass('hide-gallery');
		gallery.justifiedGallery({
			rowHeight: rowHeight,
			margins: margins,
			border: 0,
			lastRow: lastRow,
			randomize: randomize,
			selector: selector,
		});
	});

	$(document).on('elementor/render/ep_styled_maps',function(e, data){

		if ( typeof google === 'undefined' ) {
			var map  = $(data);
			var text = map.data('error');
			$(map.get(0)).after( text );
		}

		initMap();
		function initMap() {
			var map = $(data);
			var lat = map.data('latitude');
			var long = map.data('longitude');
			var style = map.data('style');
			var scroll = map.data('scroll');
			var location = {lat: lat, lng: long};
			var icon = map.data('icon');
			var info = map.data('info');
			var map = new google.maps.Map(document.getElementById(map.attr('id')), {
				zoom: map.data('zoom'),
				center: location,
				scrollwheel: scroll,
				draggable: scroll,
				styles: style
			});
			var infowindow = new google.maps.InfoWindow({
				content: info
			});
			var marker = new google.maps.Marker({
				position: location,
				icon: icon,
				map: map
			});
			if ( info ) {
				marker.addListener('click', function() {
					infowindow.open(map, marker);
				});
			}
		}
	});

	$(document).on('elementor/render/ep_audioigniter', function(event, element) {
		if (__CI_AUDIOIGNITER_MANUAL_INIT__) {
			var node = jQuery(element).find('.audioigniter-root').get(0);
			__CI_AUDIOIGNITER_MANUAL_INIT__(node);
		}
	});

	$(document).on('elementor/render/ep_video_slider', function(e, data) {
		var slider = $(data).find('.ep-video-slider');
		var nav = $(data).find('.ep-slider-nav');
		var sliderItem = $(data).find('.ep-slider-item');
		var position = $(data).data('position');
		var slides = $(data).data('slides');
		var prevArrow = true === position ? 'up' : 'left';
		var nextArrow = true === position ? 'down' : 'right';

		slider.slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: nav,
			infinite: false,
		});

		slider.fitVids();

		nav.on('init', function(event, slick, currentSlide, nextSlide){
			var navPrev = nav.find('.slick-prev');
			if ( slick.currentSlide === 0 ) {
				navPrev.css({ opacity: 0.2, transition: "opacity 0.3s", "pointer-events": "none"});
			}
		});

		nav.slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: slider,
			dots: false,
			focusOnSelect: true,
			infinite: false,
			edgeFriction: 0,
			vertical: position,
			prevArrow: '<a class="slick-prev"><i class="fa fa-angle-'+ prevArrow + '"></i></a>',
			nextArrow: '<a class="slick-next"><i class="fa fa-angle-' + nextArrow + '"></i></a>',
			responsive: [
				{
					breakpoint: 1025,
					settings: {
						vertical: false,
						slidesToShow: 3,
						prevArrow: '<a class="slick-prev"><i class="fa fa-angle-left"></i></a>',
						nextArrow: '<a class="slick-next"><i class="fa fa-angle-right"></i></a>',
					}
				},
				{
					breakpoint: 769,
					settings: {
						vertical: false,
						slidesToShow: 2,
						prevArrow: '<a class="slick-prev"><i class="fa fa-angle-left"></i></a>',
						nextArrow: '<a class="slick-next"><i class="fa fa-angle-right"></i></a>',
					}
				},
				{
					breakpoint: 481,
					settings: {
						vertical: false,
						slidesToShow: 2,
						prevArrow: '<a class="slick-prev"><i class="fa fa-angle-left"></i></a>',
						nextArrow: '<a class="slick-next"><i class="fa fa-angle-right"></i></a>',
					}
				}
			]
		});

		slider.on('beforeChange', function(event, slick, currentSlide, nextSlide){
			var current = slider.find('.slick-current');
			current.html(current.html());
		});

		nav.on('afterChange', function(event, slick, currentSlide, nextSlide){
			var navPrev = nav.find('.slick-prev');
			var navNext = nav.find('.slick-next');

			if ( 0 === slick.currentSlide ) {
				navPrev.css({ opacity: 0.2, transition: "opacity 0.3s", "pointer-events": "none"});
			} else {
				navPrev.css({ opacity: 1, "pointer-events": "auto"});
			}
			if ( slick.currentSlide === slick.slideCount -1 ) {
				navNext.css({ opacity: 0.2, transition: "opacity 0.3s", "pointer-events": "none"});
			} else {
				navNext.css({ opacity: 1, "pointer-events": "auto"});
			}
		});

		if (window.matchMedia("(min-width: 1025px)").matches && ! nav.hasClass("below")) {
		  sliderItem.matchHeight({
				target: slider,
			});
		}

		var resizeTimer;

		$(window).on('resize', function(e) {

			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function() {

				if (window.matchMedia("(min-width: 1025px)").matches && ! nav.hasClass("below")) {
					sliderItem.matchHeight({
						target: slider,
					});
				}

			}, 250);

		});
	});

	$(document).on('elementor/render/ep_preloader',function(e, data){
		$(window).on('load', function() {
		  $('#status').fadeOut();
		  $('#preloader').delay(350).fadeOut('slow');
		  $('body').delay(350).css({'overflow':'visible'});
		})
	});

})( jQuery );
