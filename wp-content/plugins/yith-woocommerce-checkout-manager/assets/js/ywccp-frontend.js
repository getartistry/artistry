jQuery(document).ready(function ($) {
    "use strict";

    var input_elem = $( 'form[name="checkout"]').find( 'p.form-row > input' ),
        abbr        = ' <abbr class="required" title="required">*</abbr>',
        error       = '<span class="ywccp_error"></span>', // init error

        ywccp_ismail = function( val ){
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

            return re.test( val );
        },
        ywccp_validatevat = function( vat ) {

            var country = $('#billing_country');

            if( typeof checkVATNumber == 'undefined' || ! country.length || ! ywccp_front.vat_validation_enabled ){
                return true;
            }

            // check if vat number has country code
            var prefix       = vat.substr( 0, 2 ).toUpperCase(),
                country_val  = country.val();

            if( prefix !== country_val ) {
                //prepend country to vat
                vat = country_val + vat;
            }

            return checkVATNumber ( country_val, vat );
        },

        ywccp_error = function( elem, msg ){

            if( ! elem.next( '.ywccp_error' ).length ) {
                elem.after( error );
            }
            // add error
            elem.next( '.ywccp_error' ).html( msg );
        };

    if( input_elem.length ) {
        $.each( input_elem, function(){

            var elem    = $(this),
                tooltip = elem.data('tooltip'),
                parent  = elem.closest( 'p.form-row' );

            elem.on( 'blur', function(){

                var t     = $(this),
                    value = t.val(),
                    msg   = '';

                if( ! ywccp_front.validation_enabled ) {
                    return;
                }

                if( ! value && parent.hasClass( 'validate-required' ) ) {
                    msg = ywccp_front.err_msg;
                    ywccp_error( t, msg );
                }
                else if ( value && parent.hasClass( 'validate-vat' ) && ! ywccp_validatevat( value ) ) {
                    ywccp_error( t, ywccp_front.err_msg_vat );
                }
                else if( value && parent.hasClass( 'validate-email' ) && ! ywccp_ismail( value ) ){
                    ywccp_error( t, ywccp_front.err_msg_mail );
                }
                else {
                    elem.next( '.ywccp_error' ).remove();
                }
            });

            if( typeof tooltip != 'undefined' && tooltip != '' && typeof $.fn.qtip != 'undefined'  ) {
                elem.qtip({
                    content: { text: tooltip },
                    show: { event: 'focus' },
                    style: { classes: 'ywccp_tooltip' },
                    position: {
                        my: 'top center',
                        at: 'top center',
                        target: parent
                    }
                });
            }
        });
    }

    var select = $('.ywccp-multiselect-type, select.select'),
        datepicker = $('.ywccp-datepicker-type'),
        timepicker = $('.ywccp-timepicker-type');

    if ( select && typeof $.fn.select2 != 'undefined' ) {
        $.each( select, function () {
            var s = $(this),
                sid = s.attr('id');

            if( $('#s2id_' + sid ).length ) {
                return;
            }

            s.select2({
                placeholder: s.data('placeholder')
            });
        });
    }

    if ( typeof $.fn.datepicker != 'undefined' && datepicker ) {
        $.each( datepicker, function () {
            $(this).datepicker({
                dateFormat: $(this).data('format') || "dd-mm-yy",
                beforeShow: function(){
                    setTimeout(function(){
                        $('#ui-datepicker-div').wrap('<div class="yith_datepicker"></div>').css('z-index', 99999999999999);
                        $('#ui-datepicker-div').show();
                    }, 0);
                },
                onClose:function(){
                    $('#ui-datepicker-div').hide();
                    $('#ui-datepicker-div').unwrap();
                }
            });
        });
    }

    if ( typeof $.fn.timepicki != 'undefined' && timepicker ) {
        $.each( timepicker, function () {
            $(this).timepicki({
                reset: true,
                disable_keyboard_mobile: true,
                show_meridian: ywccp_front.time_format,
                max_hour_value: ywccp_front.time_format ? '12' : '23',
                min_hour_value: ywccp_front.time_format ? '1' : '0',
                overflow_minutes:true,
                increase_direction:'up'
            });
        });

        $(document).on('click', '.reset_time', function (ev) {
            ev.preventDefault();
        });
    }
});