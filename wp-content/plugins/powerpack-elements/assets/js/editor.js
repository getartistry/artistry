( function( $ ) {

	var WidgetControlHandler = function( panel, model, view ) {

		var $control 		= null,
			$element 		= view.$el;
        
	};

	$( window ).on( 'elementor:init', function() {
		//elementor.hooks.addAction( 'panel/open_editor/widget/pp-info-list', WidgetControlHandler );
        elementor.hooks.addAction( 'panel/open_editor/widget/pp-modal-popup', function( panel, model, view ) {
            console.log('test');
            var $model = model;
            $element 		= view.$el;
            var popup_elem                  = $element.find('.pp-modal-popup').eq(0);
            var $src                        = popup_elem.data('src'),
            $main_class                 = popup_elem.data('main-class'),
            $popup_layout               = popup_elem.data('popup-layout'),
            $close_button               = (popup_elem.data('close-button') === 'yes') ? true : false,
            $close_button_pos           = popup_elem.data('close-button-pos'),
            $effect                     = popup_elem.data('effect'),
            $type                       = popup_elem.data('type'),
            $iframe_class               = popup_elem.data('iframe-class'),
            $src                        = popup_elem.data('src'),
            $trigger_element            = popup_elem.data('trigger-element'),
            $delay                      = popup_elem.data('delay'),
            $trigger                    = popup_elem.data('trigger'),
            $popup_id                   = popup_elem.data('popup-id'),
            $display_after              = popup_elem.data('display-after'),
            $esc_exit                   = (popup_elem.data('esc') === 'yes') ? true : false,
            $click_exit                 = (popup_elem.data('click') === 'yes') ? true : false;
            console.log($src);
            
            //$($src).magnificPopup('open');
            
            $($trigger_element).magnificPopup({
                items: {
                    src: $src 
                },
                type: $type,
                showCloseBtn: $close_button,
                enableEscapeKey: $esc_exit,
                closeOnBgClick: $click_exit,
            }).magnificPopup('open');
        });
	} );
    
    /*$(window).on('elementor/frontend/init', function()  {
        elementor.hooks.addAction( 'panel/open_editor/widget/pp-modal-popup', function( panel, model, view ) {
            console.log('test');
        });
    });*/

} )( jQuery );
