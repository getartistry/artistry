( function( $ ) {

	var isAdminBar		= $('body').is('.admin-bar'),
		_control_prefix = '.elementor-control-';

	var WidgetControlHandler = function( panel, model, view ) {

		var $control 		= null,
			$element 		= view.$el;
	};

	$( window ).on( 'elementor:init', function() {
		// elementor.hooks.addAction( 'panel/open_editor/widget/widget', WidgetControlHandler );
	} );

} )( jQuery );
