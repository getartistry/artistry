window.eml = window.eml || { l10n: {} };


( function( $ ) {

    _.extend( eml.l10n, wpuxss_eml_options_l10n_data );




    $( document ).on('click', '#eml-submit-settings-cleanup', function( event ) {

        event.preventDefault();

        emlConfirmDialog( eml.l10n.cleanup_warning_title, eml.l10n.cleanup_warning_text_p1+eml.l10n.cleanup_warning_text_p2, eml.l10n.cleanup_warning_yes, eml.l10n.cancel, 'button button-primary eml-warning-button' )
        .done( function() {

            emlFullscreenSpinnerStart( eml.l10n.in_progress_cleanup_text );

            $('#eml-form-cleanup').submit();

        })
        .fail(function() {
            return false;
        });
    });


    $( document ).on( 'click', '.eml-apply-settings-to-network', function( event ) {

        var settings =  $( event.target ).attr( 'data-settings' ),
            applying_settings_text;


        event.preventDefault();

        switch ( settings ) {

            case 'media-library':
                applying_settings_text = eml.l10n.applying_media_library_settings_text;
                break;
            case 'media-taxonomies':
                applying_settings_text = eml.l10n.applying_media_taxonomies_settings_text;
                break;
            case 'mime-types':
                applying_settings_text = eml.l10n.applying_mime_types_settings_text;
                break;
            default:
                applying_settings_text = '';
        }

        emlConfirmDialog( eml.l10n.applying_settings_title, applying_settings_text + ' ' + eml.l10n.cleanup_warning_text_p2, eml.l10n.applying_settings_yes, eml.l10n.cancel, 'button button-primary eml-warning-button' )
        .done( function() {

            emlFullscreenSpinnerStart( eml.l10n.in_progress_apply_setings_text );

            $.post( ajaxurl, {
                nonce: eml.l10n.apply_to_network_nonce,
                action: 'eml-apply-settings-to-network',
                settings: settings
            },function( response ) {
                emlFullscreenSpinnerStop();
            });
        })
        .fail(function() {
            return false;
        });
    });

})( jQuery );
