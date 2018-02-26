(function(api) {

    api.bind('ready', function() {

        function required(to, controlId, location) {

            if (to === 'horizontal' && (controlId === 'quadmenu[' + location + '_navbar_mode_horizontal]' || controlId === 'quadmenu[' + location + '_navbar_mode_horizontal_align]' || controlId === 'quadmenu[' + location + '_navbar_width]')) {
                return true;
            }

            if (to === 'vertical' && (controlId === 'quadmenu[' + location + '_navbar_mode_vertical]' || controlId === 'quadmenu[' + location + '_navbar_mode_vertical_float]')) {
                return true;
            }

            return false;

        }

        jQuery.each(quadmenu.required_options, function(settingId, o) {

            api(settingId, function(setting) {
                jQuery.each(o.controls, function(i, controlId) {
                    api.control(controlId, function(control) {

                        var visibility = function(to) {
                            control.container.toggle(required(to, controlId, o.instance));
                        };

                        visibility(setting.get());
                        setting.bind(visibility);
                    });
                });
            });
        });

    });
    
})(wp.customize);

