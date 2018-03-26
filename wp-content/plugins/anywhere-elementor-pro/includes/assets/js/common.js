/**
 * Woo Tabs
 */
jQuery( window ).on( 'elementor/frontend/init', function() {

    if(typeof elementorFrontend != 'undefined'){
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-tabs.default', function( $scope ) {
			var defaultActiveTab = $scope.find( '.ae-woo-tabs' ).data( 'active-tab' ),
                $tabsTitles = $scope.find( '.ae-woo-tab-title' ),
                $tabs = $scope.find( '.ae-woo-tab-content' ),
                $active,
                $content;

            if ( ! defaultActiveTab ) {
                defaultActiveTab = 1;
            }

            var activateTab = function( tabIndex ) {
                if ( $active ) {
                    $active.removeClass( 'active' );
                    $content.hide();
                }

                $active = $tabsTitles.filter( '[data-tab="' + tabIndex + '"]' );
                $active.addClass( 'active' );

                $content = $tabs.filter( '[data-tab="' + tabIndex + '"]' );
                $content.show();
            };

            activateTab( defaultActiveTab );
      			$tabsTitles.on( 'click', function() {
                      activateTab( this.dataset.tab );
                  });
        } );


    elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-gallery.default',function($scope){
        if($scope.parents('.elementor-editor-active').length){
            jQuery( '.woocommerce-product-gallery' ).each( function() {
                jQuery( this ).wc_product_gallery();
                wc_single_product_params.zoom_enabled = 0;
            } );
        }
    });

    elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-custom-field.default', function ($scope) {
    	if(jQuery("#elementor").hasClass('elementor-edit-mode')){
    		return;
		}
		if($scope.find('.ae-cf-wrapper').hasClass('hide')){
            $scope.find('.ae-cf-wrapper').closest('.elementor-widget-ae-custom-field').hide();
		}
    });

    elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf-gallery.slider', function( $scope ){
			outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

			wid = $scope.data('id');
			wclass = '.elementor-element-' + wid;
			//var direction = outer_wrapper.data('direction');
			var speed = outer_wrapper.data('speed');
			var autoplay = outer_wrapper.data('autoplay');
			var duration = outer_wrapper.data('duration');
			var effect = outer_wrapper.data('effect');
			var space = outer_wrapper.data('space');
			console.log(space);
			var loop = outer_wrapper.data('loop');
			if(loop == 'yes'){
				loop = true;
			}
			else{
				loop = false;
			}
			var zoom = outer_wrapper.data('zoom');
			var slides_per_view = outer_wrapper.data('slides-per-view');
			var ptype = outer_wrapper.data('ptype');
			var navigation = outer_wrapper.data('navigation');
			var clickable = outer_wrapper.data('clickable');
			var keyboard = outer_wrapper.data('keyboard');
			var scrollbar = outer_wrapper.data('scrollbar');
			adata = {
				direction:'horizontal',
				speed: speed,
				autoplay: duration,
				effect: effect,
				spaceBetween: space,
				loop: loop,
				zoom: zoom,
				keyboardControl: keyboard,
				autoHeight:false,
				height:200,
				autoplayDisableOnInteraction:false,
				wrapperClass: 'ae-swiper-wrapper',
				slideClass: 'ae-swiper-slide'
			};

			if(navigation == 'yes'){
				adata['nextButton'] = '.ae-swiper-button-next';
				adata['prevButton'] = '.ae-swiper-button-prev';
			}

			if(ptype != ''){
				adata['pagination'] = '.ae-swiper-pagination';
				adata['paginationType'] = ptype;
			}
			if(scrollbar == 'yes') {
				adata['scrollbar'] = '.ae-swiper-scrollbar';
			}
			if(clickable == 'yes') {
				adata['paginationClickable'] = true;
			}

			if(loop == false) {
				adata['autoplayStopOnLast'] = true;
			}

			var mswiper = new Swiper( '.elementor-element-' + wid  + ' .ae-swiper-container', adata);
		});

	elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf-gallery.carousel', function( $scope ){

			outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

			wid = $scope.data('id');
			wclass = '.elementor-element-' + wid;
			//var direction = outer_wrapper.data('direction');
			var speed = outer_wrapper.data('speed');
			var autoplay = outer_wrapper.data('autoplay');
			var duration = outer_wrapper.data('duration');
			var effect = outer_wrapper.data('effect');
			var space = outer_wrapper.data('space');
			//console.log(space);
			var loop = outer_wrapper.data('loop');
			if(loop == 'yes'){
				loop = true;
			}
			else{
				loop = false;
			}
			var zoom = outer_wrapper.data('zoom');
			var slides_per_view = outer_wrapper.data('slides-per-view');

			var ptype = outer_wrapper.data('ptype');
			var navigation = outer_wrapper.data('navigation');
			var clickable = outer_wrapper.data('clickable');
			var keyboard = outer_wrapper.data('keyboard');
			var scrollbar = outer_wrapper.data('scrollbar');
			adata = {
				direction: 'horizontal',
				speed: speed,
				autoHeight:true,
				autoplay: duration,
				effect: effect,
				spaceBetween: space.desktop,
				loop: loop,
				zoom: zoom,
				slidesPerView: slides_per_view.desktop,
				keyboardControl: keyboard,
				wrapperClass: 'ae-swiper-wrapper',
				slideClass: 'ae-swiper-slide',
				observer: true,
				breakpoints: {
					1024: {
						spaceBetween: space.tablet,
						slidesPerView: slides_per_view.tablet
					},
					768: {
						spaceBetween: space.mobile,
						slidesPerView: slides_per_view.mobile
					}
				}
			};

			console.log(adata);

			if(navigation == 'yes'){
				adata['nextButton'] = '.ae-swiper-button-next';
				adata['prevButton'] = '.ae-swiper-button-prev';
			}

			if(ptype != ''){
				adata['pagination'] = '.ae-swiper-pagination';
				adata['paginationType'] = ptype;
			}
			if(scrollbar == 'yes') {
				adata['scrollbar'] = '.ae-swiper-scrollbar';
			}
			if(clickable == 'yes') {
				adata['paginationClickable'] = true;
			}

			if(loop == false) {
				adata['autoplayStopOnLast'] = true;
			}

			window.mswiper = new Swiper( '.elementor-element-' + wid  + ' .ae-swiper-container', adata);

			jQuery('.elementor-element-' + wid  + ' .ae-swiper-container').css('visibility','visible');
		});

	elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-products.carousel', function( $scope ){

			outer_wrapper =  $scope.find('.ae-swiper-outer-wrapper');

			wid = $scope.data('id');
			wclass = '.elementor-element-' + wid;
			var direction = outer_wrapper.data('direction');
			var speed = outer_wrapper.data('speed');
			var autoplay = outer_wrapper.data('autoplay');
			var duration = outer_wrapper.data('duration');
			var effect = outer_wrapper.data('effect');
			var space = outer_wrapper.data('space');
			console.log(space);
			var loop = outer_wrapper.data('loop');
			if(loop == 'yes'){
				loop = true;
			}
			else{
				loop = false;
			}
			var zoom = outer_wrapper.data('zoom');
			var slides_per_view = outer_wrapper.data('slides-per-view');
			var ptype = outer_wrapper.data('ptype');
			var navigation = outer_wrapper.data('navigation');
			var clickable = outer_wrapper.data('clickable');
			var keyboard = outer_wrapper.data('keyboard');
			var scrollbar = outer_wrapper.data('scrollbar');
			adata = {
				direction: direction,
				speed: speed,
				autoplay: duration,
				effect: effect,
				spaceBetween: space,
				loop: loop,
				zoom: zoom,
				slidesPerView: slides_per_view,
				keyboardControl: keyboard,
				wrapperClass: 'ae-swiper-wrapper',
				slideClass: 'ae-swiper-slide',
				onInit: function(swiper){

				}
			};

			if(navigation == 'yes'){
				adata['nextButton'] = '.ae-swiper-button-next';
				adata['prevButton'] = '.ae-swiper-button-prev';
			}

			if(ptype != ''){
				adata['pagination'] = '.ae-swiper-pagination';
				adata['paginationType'] = ptype;
			}
			if(scrollbar == 'yes') {
				adata['scrollbar'] = '.ae-swiper-scrollbar';
			}
			if(clickable == 'yes') {
				adata['paginationClickable'] = true;
			}

			if(loop == false) {
				adata['autoplayStopOnLast'] = true;
			}
			console.log(adata);


			window.mswiper = new Swiper( '.elementor-element-' + wid  + ' .ae-swiper-container', adata);
			jQuery('.elementor-element-' + wid  + ' .ae-swiper-container').css('visibility','visible');

		});

	elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-acf-gallery.grid', function( $scope ){

			if($scope.find('.ae-grid-wrapper').hasClass('ae-masonry-yes')){
				var grid = $scope.find('.ae-grid');
				var $grid_obj = grid.masonry({
				});

				$grid_obj.imagesLoaded().progress(function(){
					$grid_obj.masonry('layout');
				});
			}

			$scope.find('.ae-grid-item-inner').hover(function(){
				jQuery(this).find('.ae-grid-overlay').addClass('animated');
			});
		});

        elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-post-blocks.default', function( $scope ){

            if($scope.find('.ae-post-widget-wrapper').hasClass('ae-masonry-yes')){
                var grid = $scope.find('.ae-post-list-wrapper');
                var $grid_obj = grid.masonry();

                $grid_obj.imagesLoaded().progress(function(){
                    $grid_obj.masonry('layout');
                });
            }
        });

	elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-woo-products.grid', function( $scope ){

			if($scope.hasClass('ae-masonry-yes')){
				var grid = $scope.find('.ae-grid');
				var $grid_obj = grid.masonry({

				});

				$grid_obj.imagesLoaded().progress(function(){
					$grid_obj.masonry('layout');
				});

				jQuery(window).resize(function(){
					// Todo:: Overlap on render mode
					//$grid_obj.masonry('layout');
				});
			}

			$scope.find('.ae-grid-item-inner').hover(function(){
				jQuery(this).find('.ae-grid-overlay').addClass('animated');
			});
		});

	elementorFrontend.hooks.addAction( 'frontend/element_ready/ae-cf-google-map.default', function( $scope ) {
            map = new_map($scope.find('.ae-cf-gmap'));



            function new_map( $el ) {
                var zoom = $scope.find('.ae-cf-gmap').data('zoom');
                var $markers = $el.find('.marker');
                var styles = $scope.find('.ae-cf-gmap').data('styles');

                // vars
                var args = {
                    zoom		: zoom,
                    center		: new google.maps.LatLng(0, 0),
                    mapTypeId	: google.maps.MapTypeId.ROADMAP,
					styles		: styles
                };

                // create map
                var map = new google.maps.Map( $el[0], args);

                // add a markers reference
                map.markers = [];

                // add markers
                $markers.each(function(){
                    add_marker( jQuery(this), map );
                });

                // center map
                center_map( map, zoom );

                // return
                return map;
            }

            function add_marker( $marker, map ) {
                // var
                var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

                // create marker
                var marker = new google.maps.Marker({
                    position	: latlng,
                    map			: map,
                });

                // add to array
                map.markers.push( marker );

                // if marker contains HTML, add it to an infoWindow

                if( $marker.html() )
                {
                    // create info window
                    var infowindow = new google.maps.InfoWindow({
                        content		: $marker.html()
                    });

                    // show info window when marker is clicked
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open( map, marker );
                    });
                }
            }

            function center_map( map, zoom ) {

                // vars
                var bounds = new google.maps.LatLngBounds();
                // loop through all markers and create bounds
                jQuery.each( map.markers, function( i, marker ){
                    var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
                    bounds.extend( latlng );
                });

                // only 1 marker?
                if( map.markers.length == 1 )
                {
                    // set center of map
                    map.setCenter( bounds.getCenter() );
                    map.setZoom( zoom );
                }
                else
                {
                    // fit to bounds
                    map.fitBounds( bounds );
                }
            }
        });





        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {

            if ( $scope.data( 'ae-bg' ) ){
                $scope.css('background-image','url(' + $scope.data( 'ae-bg' ) + ')');
            }

            if ( $scope.data( 'box-link' ) ){
                $scope.click(function(){
                    window.location.href = $scope.data( 'box-link' );
                });
            }
        } );

    }

    if(typeof elementorFrontend != 'undefined'){
        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {

            var aepro_slides = [];
            var aepro_slides_json = [];
            var aepro_transition;
            var aepro_animation;
            var aepro_custom_overlay;
            var aepro_overlay;
            var aepro_cover;
            var aepro_delay;
            var aepro_timer;
            var slider_wrapper = $scope.children( '.aepro-section-bs').children('.aepro-section-bs-inner');

            if ( slider_wrapper && slider_wrapper.data('aepro-bg-slider')){

                slider_images = slider_wrapper.data('aepro-bg-slider');
                aepro_transition = slider_wrapper.data('aepro-bg-slider-transition');
                aepro_animation = slider_wrapper.data('aepro-bg-slider-animation');
                aepro_custom_overlay = slider_wrapper.data('aepro-bg-custom-overlay');
                if(aepro_custom_overlay == 'yes'){
                        aepro_overlay = aepro_editor.plugin_url + '/includes/assets/lib/vegas/overlays/' + slider_wrapper.data('aepro-bg-slider-overlay');
                }else{
                    if(slider_wrapper.data('aepro-bg-slider-overlay')){
                        aepro_overlay = aepro_editor.plugin_url + '/includes/assets/lib/vegas/overlays/' + slider_wrapper.data('aepro-bg-slider-overlay');
                    }else{
                        aepro_overlay = aepro_editor.plugin_url + '/includes/assets/lib/vegas/overlays/' + slider_wrapper.data('aepro-bg-slider-overlay');
                    }
                }

                aepro_cover = slider_wrapper.data('aepro-bg-slider-cover');
                aepro_delay = slider_wrapper.data('aepro-bs-slider-delay');
                aepro_timer = slider_wrapper.data('aepro-bs-slider-timer');

                if(typeof slider_images != 'undefined'){
                    aepro_slides = slider_images.split(",");

                    jQuery.each(aepro_slides,function(key,value){
                        var slide = [];
                        slide.src = value;
                        aepro_slides_json.push(slide);
                    });

                    slider_wrapper.vegas({
                        slides: aepro_slides_json,
                        transition:aepro_transition,
                        animation: aepro_animation,
                        overlay: aepro_overlay,
                        cover: aepro_cover,
                        delay: aepro_delay,
                        timer: aepro_timer,
						init: function(){
							if(aepro_custom_overlay == 'yes') {
								var ob_vegas_overlay = slider_wrapper.children('.vegas-overlay');
								ob_vegas_overlay.css('background-image', '');
							}
						}
                    });

                }
            }
        } );
    }
});