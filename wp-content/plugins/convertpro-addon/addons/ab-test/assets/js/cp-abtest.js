(function( $ ) {
    
    /**
     * JavaScript class for working for AB Tests.
     *
     * @since 1.0.0
     */

    var createAbtestFrm = '',
        creatLink       = '',
        saveBtn         = '',
        styleSelect     = '',
        modal           = '';

    var ConvertPlugABTest = {
        
        /**
         * Initializes the services logic.
         *
         * @return void
         * @since 1.0.0
         */
        init: function() {
            var body            = $('body'),
                customizer_form = $( '.cp-api-integration-form' );

            this._ready();

            createAbtestFrm = $( '#cp-create-ab-test' );
            creatLink       = $( '.create-test-link' );
            modal           = $( '.cp-abtest-modal' );
            saveBtn         = modal.find( '.save-ab-test' );
            styleSelect     = modal.find( 'select.select2-ex-dropdown' );
            messageWrap     = modal.find( ".cp-notification-message .cp-error" );

            creatLink.on( 'click', this._create );
            $( document ).on( 'click', '.update-ab-test-link', this._edit );
            $( document ).on( 'click', '.remove-test', this._remove );
            $( document ).on( 'click', '.cp-stop-test-action', this._stop );
            $( document ).on( 'mouseup', 'body:not(#cp-edit-dropdown a)', this._closeEditPanel );
            $( document ).on( 'change', 'select[name=cp_parent_style]', function() {
                messageWrap.html('').removeClass('cpro-open');
            } );
            $( document ).on( 'click', '.cp-style-title a', this._editTest );
            createAbtestFrm.on( 'submit', this._submit );
            styleSelect.on( 'change', this._updateParentDropdown );
            $( document ).on( 'click', '.cp-ab-edit-settings', this._editSetting );

            $( document ).on( 'cp_after_ab_test_created', this._refresh_html );  

        },

        _editTest: function ( e ) {
            var el_this = $( this ),
                el_dropdown = $( '#cp-ab-edit-dropdown' ),
                el_parent = el_this.closest( '.cp-ab-test-row' ),
                tid = el_parent.data( 'test-id' ),
                tstatus = el_parent.data( 'test-status' ),
                tprops = el_parent.data( 'props' );

            el_dropdown.data( 'test-id', tid );
            el_dropdown.data( 'props', tprops );

            if( '2' != tstatus ) {
                ConvertPlugABTest._edit( e );
            }
        },

        _closeEditPanel: function () {
            var el_dropdown = $( '#cp-ab-edit-dropdown' );
            el_dropdown.removeClass( 'cp-edit-show cp-edit-below cp-edit-above' );
            messageWrap.html('').removeClass('cpro-open');
            $( '.cp-ab-edit-settings' ).removeClass( 'active' );
        },

        _editSetting: function () {

            var el_this = $( this ),
                completedFlag = el_this.data( 'completed' );

            if( completedFlag == 2 ) {
                $( '#cp-ab-edit-dropdown .update-ab-test-link' ).hide();
                $( '#cp-ab-edit-dropdown .cp-stop-test-action' ).hide();
            } else {
                $( '#cp-ab-edit-dropdown .update-ab-test-link' ).show();
                $( '#cp-ab-edit-dropdown .cp-stop-test-action' ).show();
            }

            var el_dropdown = $( '#cp-ab-edit-dropdown' ),
                el_parent = el_this.closest( '.cp-ab-test-row' ),
                el_window = $( window ),
                el_parent_top       = el_parent.offset().top - el_window.scrollTop(),
                el_parent_bottom    = el_parent_top + el_parent.outerHeight(),
                el_dropdown_height = el_dropdown.outerHeight( true ),
                class_css = 'cp-edit-below',
                right_css = el_window.width() - ( el_this.offset().left + el_this.outerWidth() + 10 ),
                top_css = el_parent_bottom - 10;

            $( '.cp-ab-edit-settings' ).removeClass( 'active' );
            $( 'html' ).addClass('cp-edit-action-in');

            if (  el_this.data( 'ab-test' ) ) {
                el_dropdown.addClass( 'cp-edit-ab-test' );
            } else {
                el_dropdown.removeClass( 'cp-edit-ab-test' );
            }

            el_this.addClass( 'active' );

            if ( el_parent_top > el_dropdown_height ) {
                class_css = 'cp-edit-above';
                top_css = el_parent_top - el_dropdown_height;
            }

            el_dropdown.addClass(class_css);
            el_dropdown.css( {
                'top': top_css + 'px',
                'right': right_css + 'px',
            } );
            el_dropdown.addClass('cp-edit-show');
            
            var tid = el_parent.data( 'test-id' );
            var tprops = el_parent.data( 'props' );

            el_dropdown.data( 'test-id', tid );
            el_dropdown.data( 'props', tprops );
        },

        _updateParentDropdown: function () {

            var selectedValues = styleSelect.val(),
                dd = createAbtestFrm.find( 'select[name=cp_parent_style]' ),
                lbl = '',
                last_selection = dd.val();

            messageWrap.html('').removeClass('cpro-open');

            dd.html( '' );

            dd.append( '<option value="-1">' + cp_abtest.select + '</option>' );

            if( selectedValues != null ) {
                if( selectedValues.length >= 2 ) {
                    $( '.cp-abtest-parent-wrap' ).show();
                    $.each( selectedValues,function( index, value ) {
                        lbl = styleSelect.find('option[value=' + value + ']').text();
                        dd.append( '<option value="' + value + '">' + lbl + '</option>' );
                    });
                    createAbtestFrm.find( 'select[name=cp_parent_style] option[value=' + last_selection + ']' ).prop( 'selected', true );
                } else {
                    $( '.cp-abtest-parent-wrap' ).hide();
                }
            } else {
                $( '.cp-abtest-parent-wrap' ).hide();
            }
        },

        _create: function () {

            var campaign = $( this ).data( "campaign" ),
                settings = $( this ).data( "settings" ),
                dd = createAbtestFrm.find( 'select[name=cp_parent_style]' );

            ConvertPlugABTest.ajaxCall(
                {
                    action: 'cp_get_remaining_popups'
                },
                function( response ) {
                    if( response != 'null' ) {
                        var txt = '';
                        $.each( response, function( index, val ) {
                            txt += '<option value="' + index + '">' + val + '</option>';
                        } );
                        styleSelect.html( txt );
                    }
                },
                function( err ) {
                    console.log( err );
                }
            );

            $( '.cp-abtest-parent-wrap' ).hide();

            dd.html( '' );

            dd.append( '<option value="-1">' + cp_abtest.select + '</option>' );

            saveBtn.data( "campaign", campaign );
            modal.find( ".cp-info-section" ).html( settings );
            saveBtn.text( cp_abtest.create_test ).data( "action", "cp_create_ab_test" );
            modal.find( ".cp-dashboard-modal-title" ).text( cp_abtest.create_new_test );

            // Reset form values
            createAbtestFrm.find( "#cp-test-sdate" ).val( '' );
            createAbtestFrm.find( "#cp-test-edate" ).val( '' );
            createAbtestFrm.find( "#test_title" ).val( '' );
            styleSelect.find( 'option' ).removeAttr( 'selected' );
            createAbtestFrm.find( "input[name=cp_winner_check]" ).removeAttr( 'checked' );

            // Initialize multi select option
            styleSelect.cpselect2( { placeholder: cp_abtest.select_styles } );

            // Show Modal
            modal.addClass( "cp-show" );
            $( ".cp-md-overlay" ).addClass( "cp-show" );
            modal.find( ".cp-save-animate-container" ).removeClass( "cp-zoomOut" ).addClass( " cp-animated cp-zoomIn" );
        },

        _edit: function ( e ) {

            e.preventDefault();

            var test_data = $( '#cp-ab-edit-dropdown' ).data("props"),
                test_id = $( '#cp-ab-edit-dropdown' ).data("test-id"),
                styles_data_html = "<div class='cp-abtest-block'><input type='hidden' id='cp-test-id' value='" + test_id + "'></div>",
                dd = createAbtestFrm.find( 'select[name=cp_parent_style]' ),
                lbl = '';

            styleSelect.html( '' );

            ConvertPlugABTest.ajaxCall(
            {
                action: 'cp_get_remaining_popups',
                test_id: test_id
            },
            function( response ) {
                if( response != 'null' ) {
                    var txt = '';
                    var sel = '';
                    $.each( response, function( index, val ) {
                        if( test_data.sel_styles.indexOf( parseInt( index ) ) != -1 ) {
                            sel = 'selected="selected"';
                        } else {
                            sel = '';
                        }
                        txt += '<option value="' + index + '" ' + sel + '>' + val + '</option>';
                    } );
                    styleSelect.html( txt );

                    dd.html( '' );
                    dd.append( '<option value="-1">' + cp_abtest.select + '</option>' );
 
                    modal.find( ".cp-dashboard-modal-title" ).text( cp_abtest.edit_test );

                    // Set form values
                    createAbtestFrm.find( "#cp-test-sdate" ).val( test_data.start_date );
                    createAbtestFrm.find( "#cp-test-edate" ).val( test_data.end_date );
                    createAbtestFrm.find( "#test_title" ).val( test_data.name );
                    if( test_data.winner_style == 'on' ) {
                        createAbtestFrm.find( "input[name=cp_winner_check]" ).attr( 'checked', 'checked' );
                    } else {
                        createAbtestFrm.find( "input[name=cp_winner_check]" ).removeAttr( 'checked' );
                    }


                    if( test_data.name != '' && ! createAbtestFrm.find( "#test_title" ).parent().hasClass('has-input') ) {
                        createAbtestFrm.find( "#test_title" ).parent().addClass('has-input');
                    }
                    saveBtn.text( cp_abtest.update_test ).data( "action", "cp_update_ab_test" );
                    
                    if( test_data.sel_styles.length < 2 ) {
                        $( '.cp-abtest-parent-wrap' ).hide();
                    } else {
                        $( '.cp-abtest-parent-wrap' ).show();
                    }
                    $.each( test_data.sel_styles,function( index, value ){ 
                        styleSelect.find('option[value=' + value + ']').attr( 'selected', 'selected' );
                        lbl = styleSelect.find('option[value=' + value + ']').text();
                        dd.append( '<option value="' + value + '">' + lbl + '</option>' );
                    });

                    styleSelect.cpselect2( { placeholder: cp_abtest.select_styles } );

                    if ( test_data.parent_style != '' ) {
                        dd.find( 'option[value=' + test_data.parent_style + ']' ).attr( 'selected', 'setTimeout' );
                    }

                    modal.find(".cp-style-list").after( styles_data_html );
                    modal.addClass( "cp-show" );
                    $( ".cp-md-overlay" ).addClass( "cp-show" );
                    modal.find( ".cp-save-animate-container" ).removeClass( "cp-zoomOut" ).addClass( " cp-animated cp-zoomIn" );
                    ConvertPlugABTest._closeEditPanel();
                }
            },
            function( err ) {
                console.log( err );
            } );
        },

        _submit: function ( e ) {
            e.preventDefault();

            var test_name = createAbtestFrm.find("#test_title").val(),
                campaign  = createAbtestFrm.find(".save-ab-test").data("campaign"),
                sdate     = createAbtestFrm.find("#cp-test-sdate").val(),
                edate     = createAbtestFrm.find("#cp-test-edate").val(),
                action    = createAbtestFrm.find(".save-ab-test").data("action"),
                test_id   = createAbtestFrm.find("#cp-test-id").val(),
                styles    = createAbtestFrm.find( 'select[name=cp_styles]' ).val(),
                pstyle    = createAbtestFrm.find( 'select[name=cp_parent_style]' ).val(),
                winner    = createAbtestFrm.find( 'input[name=cp_winner_check]:checked' ).length,
                message = cp_abtest.two_popups;

            if( styles == null ) {
                messageWrap.html( cp_abtest.atleast_one_design ).addClass( 'cpro-open' );
                return false;
            }

            if( styles.length < 2 ) {
                messageWrap.html( message ).addClass( 'cpro-open' );
                return false;   
            }

            if( pstyle == -1 ) {
                messageWrap.html( cp_abtest.parent_style ).addClass( 'cpro-open' );
                return false;
            }

            saveBtn.text( 'Saving...' );

            ConvertPlugABTest.ajaxCall(
                {
                    action: action, 
                    test_name: test_name,
                    test_id: test_id,
                    styles: styles,
                    campaign: campaign,
                    start_date: sdate,
                    end_date: edate,
                    cp_parent_style: pstyle,
                    cp_winner_check: ( winner == 0 ) ? 'off' : 'on',
                    security: $( '#cp-save-ab-test-nonce' ).val()
                },
                ConvertPlugABTest._submitCompleted,
                function( err ) {
                    console.log( err );
                }
            );
        },

        _submitCompleted: function ( result ) {

            var content = $( '.cp-accordion-section-content' ),
                action = createAbtestFrm.find(".save-ab-test").data("action"),
                test_id   = createAbtestFrm.find("#cp-test-id").val();

            if( typeof result.data !== 'undefined' ) {
                if( typeof result.data.message !== 'undefined' ) {
                    var message = result.data.message;

                    if( result.data.success !== true ) {
                        messageWrap.html( message ).addClass( 'cpro-open' );
                        return false;
                    }

                    if( result.data.html != '' ) {
                        if( action == 'cp_update_ab_test' ) {

                            content.find( '.cp-ab-test-row[data-test-id=' + test_id + ']' ).replaceWith( result.data.html );
                            $( '.no-tests' ).addClass( 'cp-hidden' );

                        } else {

                            if( content.find( '.cp-ab-test-row:last-child' ).length == 0 ) {

                                content.addClass( 'open' );

                                $( '.no-tests' ).addClass( 'cp-hidden' );

                                content.find( ' .cp-abtest-row' ).html( result.data.header_html );
                                
                                content.find( ' .cp-abtest-row' ).after( result.data.html );
                            } else {
                                content.find( ' .cp-ab-test-row:last-child' ).after( result.data.html );
                            }
                        }
                        
                    }
                    setTimeout(function() {
                        saveBtn.text( cp_abtest.saved );
                        saveBtn.append('<span class="dashicons-yes dashicons"></span>');
                    }, 300);
                    setTimeout(function(){
                        messageWrap.html('').removeClass('cpro-open');
                        $( '.cp-cancel-btn' ).trigger( 'click' );
                    }, 600);

                    jQuery(document).trigger( 'cp_after_ab_test_created', [test_id] );

                }
            }
        },

        _ready: function () {

            if( typeof $().datetimepicker !== 'undefined' ) {
        
                $('#cp-test-sdate').datetimepicker({
                    format: 'DD/MM/YYYY',
                    minDate : 'now'
                });

                $('#cp-test-edate').datetimepicker({
                    useCurrent: false, //Important! See issue #1075
                    format: 'DD/MM/YYYY'
                });

                $("#cp-test-sdate").on("dp.change", function (e) {
                    $('#cp-test-edate').data("DateTimePicker").minDate(e.date);
                });

                $("#cp-test-edate").on("dp.change", function (e) {
                    $('#cp-test-sdate').data("DateTimePicker").maxDate(e.date);
                });
            }
        },

        _stop: function () {

            if ( ! confirm( cp_abtest.stop_test ) ) {
                return false;
            }
            var test_id = $( '#cp-ab-edit-dropdown' ).data('test-id'),
                status = 2;

            ConvertPlugABTest.ajaxCall(
                {
                    action: 'cp_update_ab_test_status', 
                    test_id: test_id,
                    status: status
                },
                function( result ) {
                    location.reload();
                },
                function( err ) {
                    console.log( err );
                }
            );
        },

        _remove: function () {

            if ( ! confirm( cp_abtest.delete_test ) ) {
                return false;
            }

            var $this       = $( this ),
                test_id     = $( '#cp-ab-edit-dropdown' ).data( "test-id" ),
                closestWrap = $( '.cp-ab-test-row[data-test-id=' + test_id + ']' );

            closestWrap.addClass('cp-delete');

            ConvertPlugABTest.ajaxCall(
                { 
                    action: 'cp_del_ab_test', 
                    test: test_id,
                    security: $( '#cp-delete-test-nonce' ).val() 
                },
                function( result ) {
                    if( typeof result.data !== 'undefined' ) {
                        if( typeof result.data.test_id !== 'undefined' ) {

                            closestWrap.addClass('cp-delete-wrap');                 
                            setTimeout(function(){
                                var contentRow = closestWrap.closest( '.cp-accordion-section-content' ),
                                    len = contentRow.find( '.cp-ab-test-row' ).length,
                                    label_row = contentRow.find( '.cp-abtest-row' );

                                closestWrap.remove();
                                if( len == 1 ) {
                                    $( '.no-tests' ).removeClass( 'cp-hidden' );
                                    contentRow.removeClass( 'open' );
                                }
                                ConvertPlugABTest._closeEditPanel();
                            }, 400);
                        }
                    }
                },
                function( err ) {
                    console.log( err );
                }
            );
        },

        /*
         * Regenerate HTML of designs 
         * @return void
        */
        _refresh_html: function () {

            ConvertPlugABTest.ajaxCall(
                {
                    action: 'cp_refresh_html', 
                    cp_nonce: cp_abtest.ajax_nonce
                },
                function( result ) {
                    console.log( result );
                },
                function( err ) {
                    console.log( err );
                }
            );
        },

        /**
         * Serializes Form data to JSON.
         *
         * @return {Object}
         * @since 1.0.0
         */
        serializeFormJSON: function ( form ) {

            var o = {};
            var a = form.serializeArray();
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        },

        /**
         * AJAX call to services.
         *
         * @param {Object} args Arguments to AJAX call.
         * @param func: Callback function name.
         * @return void
         * @since 1.0.0
         */
        ajaxCall: function( args, success_func, error_func ) {

            $.ajax( {
                data: args,
                action: args.action,
                url: cp_ajax.url,
                success: success_func,
                error: error_func,
                type:'POST',
                dataType:'JSON'
            });
        },

    };

    /*(function (exports) {
        function valOrFunction(val, ctx, args) {
            if (typeof val == "function") {
                return val.apply(ctx, args);
            } else {
                return val;
            }
        }

        function InvalidInputHelper(input, options) {
            input.setCustomValidity(valOrFunction(options.defaultText, window, [input]));

            function changeOrInput() {
                if (input.value == "") {
                    input.setCustomValidity(valOrFunction(options.emptyText, window, [input]));
                } else {
                    input.setCustomValidity("");
                }
            }

            function invalid() {
                if (input.value == "") {
                    input.setCustomValidity(valOrFunction(options.emptyText, window, [input]));
                } else {
                   input.setCustomValidity(valOrFunction(options.invalidText, window, [input]));
                }
            }

            input.addEventListener("change", changeOrInput);
            input.addEventListener("input", changeOrInput);
            input.addEventListener("invalid", invalid);
        }
        exports.InvalidInputHelper = InvalidInputHelper;
    })(window);*/

    $( document ).ready( function() {

        google.charts.load( 'current', { 'packages': ['corechart'] } );

        $( document ).on( 'click', '.cp-ab-test-analytics', function ( e ) {
            e.preventDefault();

            var parentDiv = $("#cp-ga-abtest-modal"),
                style = $(this).data("style"),
                ab_test = $(this).data("ab-test");


            parentDiv.addClass("cp-show");
            $(".cp-md-overlay").addClass("cp-show");

         
            parentDiv.find(".cp-save-animate-container").removeClass("cp-zoomOut").addClass(" cp-animated cp-zoomIn");

            $( '.cp-ga-abtest-filter' ).data( 'style', ab_test );

            if( $( '#cp_ga_chart_div' ).length != 0 ) {
                _drawABChart( ab_test );
            }
        } );

        /*InvalidInputHelper(document.getElementById("cp-test-sdate"), {
            defaultText: "",
            emptyText: cp_abtest.start_date,
            invalidText: function (input) {
                return '';
            }
        });

        InvalidInputHelper(document.getElementById("cp-test-edate"), {
            defaultText: "",
            emptyText: cp_abtest.end_date,
            invalidText: function (input) {
                return '';
            }
        });*/

        function _drawABChart( test, filter ) {

            var jsonData = $.ajax({
                url: cp_ajax.url,
                method: 'post',
                data: {
                    action: 'cp_get_ab_ga_data',
                    test_id: test,
                    filter: filter
                },
                dataType: "json",
                async: false
            }).responseText;

            var parse_data = JSON.parse( jsonData );

            var data_content = new google.visualization.DataTable();
            data_content.addColumn('date', 'X');

            //google.load('visualization', '1', { packages: ['corechart', 'controls'] });

            for( i = 0 ; i < parse_data['cols'].length; i++ ) {
                data_content.addColumn( 'number', parse_data['cols'][i] );
            }

            for( i = 0 ; i < parse_data['rows'].length; i++ ) {
                parse_data['rows'][i][0] = new Date( parse_data['rows'][i][0] );
            }

            data_content.addRows( parse_data['rows'] );

            // Set chart options

            var options = {
                'title':'Analytics',
                'width': 640,
                'height':300,
                hAxis: {
                  title: 'Date'
                },
                vAxis: {
                  title: 'Conversions'
                },
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.LineChart(document.getElementById('cp_ga_chart_div'));
            chart.draw( data_content, options );
            setTimeout(function(){
                $('.cp-ga-filter-wrap .cp-ga-abtest-filter').removeClass('cp-show');
            }, 200);
        }

    } );

    $( function() {
        ConvertPlugABTest.init();
    });

})( jQuery );