//Go Between the Tabs
( function ( $ ){
    "use strict";
    $(".pa-settings-tabs").tabs();
    
    
    $("a.pa-tab-list-item").on("click", function () {
        var tabHref = $(this).attr('href');
        window.location.hash = tabHref;
        $("html , body").scrollTop(tabHref);
    });
    
    $(".pa-checkbox").on("click", function(){
       if($(this).prop("checked") == true) {
           $(".pa-elements-table input").prop("checked", 1);
       }else if($(this).prop("checked") == false){
           $(".pa-elements-table input").prop("checked", 0);
       }
    });
   
    $( 'form#pa-settings' ).on( 'submit', function(e) {
		e.preventDefault();
		$.ajax( {
			url: settings.ajaxurl,
			type: 'post',
			data: {
				action: 'pa_save_admin_addons_settings',
				fields: $( 'form#pa-settings' ).serialize(),
			},
            success: function( response ) {
				swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
				);
			},
			error: function() {
				swal(
				  'Oops...',
				  'Something Wrong!',
				);
			}
		} );

	} );
    
    
    $( '.pa-rollback-button' ).on( 'click', function( event ) {
				event.preventDefault();

				var $this = $( this ),
					dialogsManager = new DialogsManager.Instance();

				dialogsManager.createWidget( 'confirm', {
					headerMessage: premiumRollBackConfirm.i18n.rollback_to_previous_version,
					message: premiumRollBackConfirm.i18n.rollback_confirm,
					strings: {
						cancel: premiumRollBackConfirm.i18n.cancel,
                        confirm: premiumRollBackConfirm.i18n.yes,
					},
					onConfirm: function() {
						$this.addClass( 'loading' );

						location.href = $this.attr( 'href' );
					}
				} ).show();
			} );
    
} )(jQuery);