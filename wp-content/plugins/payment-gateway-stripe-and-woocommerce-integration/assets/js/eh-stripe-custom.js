
jQuery(function() { 
    jQuery('#doaction').prop('disabled', 'disabled');
    jQuery('#the-list').on('keyup','input[type="number"]', function(){
        var id=jQuery( this ).attr('id');
        var val=jQuery( this ).val();
        var cls=jQuery( this ).attr('class');
        if(cls.match('refund'))
        {
            jQuery(this).removeClass('error');
            jQuery( ".amount_refund_main_"+id ).prop('hidden', 'hidden');
            jQuery(".amount_refund_place_"+id).removeAttr('hidden');
            jQuery( ".amount_refund_place_"+id ).html( parseFloat(val).toFixed(2) );
        }
        if(val===''&&cls.match('refund'))
        {
            jQuery( ".amount_refund_place_"+id ).prop('hidden', 'hidden');
            jQuery( ".amount_refund_main_"+id ).removeAttr('hidden');
        }
    });
    jQuery('#the-list').on('click', '.payment_capture_button', function() {
        var id=jQuery( this ).attr('id');
        swal({
            title: "Stripe Alert",
            text: "Making capture payment action",
            showCancelButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Go on!",
            cancelButtonText: "No, Wait!"
        }).then(function() {
            jQuery("#order_section .loader").css("display", "block");
            jQuery.ajax({
                 type: 'post',
                 url: ajaxurl,
                 data:{
                     _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                     action: 'eh_spg_capture_payment',
                     order_id: id,
                     paged: parseInt(jQuery('input[name=paged]').val()) || '1'
                 },
                 success: function(response) {
                     swal({
                         title: 'Capture Status',
                         html: jQuery('<small>')
                           .text(response)
                       });
                     get_all_orders_js();
                 },
                 error: function(jqXHR, textStatus, errorThrown) {
                     console.log(textStatus, errorThrown);
                 }
             });
        });
    });
    jQuery('#the-list').on('click', '.payment_refund_button', function() {
       var id=jQuery( this ).attr('id');
       var amount ='';
       var mode='';
       var flag=false;
       if(jQuery('.'+id).is( ':checked' ))
       {
            mode='full';
            amount=0;
            flag=true;
       }
       else
       {
            mode='partial';
            if(jQuery('.payment_refund_text_'+id).val())
            {
                amount=jQuery('.payment_refund_text_'+id).val();
                flag=true;
            }
            else
            {
                jQuery('.payment_refund_text_'+id).addClass('error');
                flag=false;
            }
       }
       if(flag)
       {
            swal({
                 title: "Stripe Alert",
                 text: "Making Refund payment action",
                 showCancelButton: true,
                 allowOutsideClick: false,
                 allowEscapeKey: false,
                 confirmButtonColor: "#DD6B55",
                 confirmButtonText: "Yes, Go on!",
                 cancelButtonText: "No, Wait!"
             }).then(function() {
                 jQuery("#order_section .loader").css("display", "block");
                 jQuery.ajax({
                      type: 'post',
                      url: ajaxurl,
                      data:{
                          _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                          action: 'eh_spg_refund_payment',
                          order_id: id,
                          refund_amount:amount,
                          refund_mode:mode,
                          paged: parseInt(jQuery('input[name=paged]').val()) || '1'
                      },
                      success: function(response) {
                          swal({
                              title: 'Refund Status',
                              html: jQuery('<small>')
                                .text(response)
                            });
                          get_all_orders_js();
                      },
                      error: function(jqXHR, textStatus, errorThrown) {
                          console.log(textStatus, errorThrown);
                      }
                  });
             });
         }
    });
    jQuery('#the-list').on('click', '.complete_button', function() {
       var id=jQuery( this ).attr('id');
       jQuery("#order_section .loader").css("display", "block");
       jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data:{
                _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                action: 'eh_order_status_update',
                order_id: id,
                order_action : 'completed',
                paged: parseInt(jQuery('input[name=paged]').val()) || '1'
            },
            success: function(response) {
                get_all_orders_js();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });
    jQuery('#the-list').on('click', '.processing_button', function() {
       var id=jQuery( this ).attr('id');
       jQuery("#order_section .loader").css("display", "block");
       jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data:{
                _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                action: 'eh_order_status_update',
                order_id: id,
                order_action : 'processing',
                paged: parseInt(jQuery('input[name=paged]').val()) || '1'
            },
            success: function(response) {
                get_all_orders_js();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });
    jQuery( '#the-list' ).on( 'change','input[type="checkbox"]', function() 
    {
        var value=jQuery( this ).val();
        if(value==='capture')
        {
            var id=jQuery( this ).attr('class');
            if (! jQuery( this ).is( ':checked' ) ) {
                jQuery('.payment_capture_text_'+id).removeAttr('hidden');
            }
            else
            {
                jQuery('.payment_capture_text_'+id).prop('hidden', 'hidden');
            }
        }
        else
        {
            if(value==='refund')
            {
                var id=jQuery( this ).attr('class');
                if (! jQuery( this ).is( ':checked' ) ) {
                    jQuery('.payment_refund_text_'+id).removeAttr('hidden');
                }
                else
                {
                    jQuery('.payment_refund_text_'+id).prop('hidden', 'hidden');
                }
            }
        }
    }).change();
    jQuery('#the-list').on('click', '.stripe_refund_button', function() {
        var id=jQuery( this ).attr('id');
        swal({
            title: "Stripe Alert",
            text: "Making Fefund Full payment action",
            showCancelButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Go on!",
            cancelButtonText: "No, Wait!"
        }).then(function() {
            jQuery("#stripe_section .loader").css("display", "block");
            jQuery.ajax({
                 type: 'post',
                 url: ajaxurl,
                 data:{
                     _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                     action: 'eh_spg_stripe_refund_payment',
                     order_id: id,
                     paged: parseInt(jQuery('input[name=paged]').val()) || '1'
                 },
                 success: function(response) {
                     swal({
                         title: 'Refund Status',
                         html: jQuery('<small>')
                           .text(response)
                       });
                     get_all_stripe_js();
                 },
                 error: function(jqXHR, textStatus, errorThrown) {
                     console.log(textStatus, errorThrown);
                 }
             });
        });
    });
    function get_all_orders_js() {
        jQuery("#order_section .loader").css("display", "block");
        jQuery.ajax({
            url: ajaxurl,
            type:'post',
            data:{
                _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                action: 'eh_spg_get_all_order',
                paged: parseInt(jQuery('input[name=paged]').val()) || '1'
            },
            success: function(response) {
                jQuery("#order_section .loader").css("display", "none");
                var response = jQuery.parseJSON(response);
                 if (response.rows.length)
                    jQuery('#the-list').html(response.rows);
                if (response.column_headers.length)
                    jQuery('thead tr, tfoot tr').html(response.column_headers);
                if (response.pagination.bottom.length)
                    jQuery('.tablenav.top .tablenav-pages').html(jQuery(response.pagination.top).html());
                if (response.pagination.top.length)
                    jQuery('.tablenav.bottom .tablenav-pages').html(jQuery(response.pagination.bottom).html());
                list.init();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
    function get_all_stripe_js() {
        jQuery("#stripe_section .loader").css("display", "block");
        jQuery.ajax({
            type:'post',
            url: ajaxurl,
            data:{
                _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                action: 'eh_spg_get_all_stripe',
                paged: parseInt(jQuery('input[name=paged]').val()) || '1'
            },
            success: function(response) {
                jQuery("#stripe_section .loader").css("display", "none");
                var response = jQuery.parseJSON(response);
                 if (response.rows.length)
                    jQuery('#the-list').html(response.rows);
                if (response.column_headers.length)
                    jQuery('thead tr, tfoot tr').html(response.column_headers);
                if (response.pagination.bottom.length)
                    jQuery('.tablenav.top .tablenav-pages').html(jQuery(response.pagination.top).html());
                if (response.pagination.top.length)
                    jQuery('.tablenav.bottom .tablenav-pages').html(jQuery(response.pagination.bottom).html());
                list.init();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
    jQuery('#wrap_table').on('click', '#doaction', function() {
        var ids=get_bulk_ids();
        var action=jQuery('#bulk-action-selector-top').val();
        jQuery("#order_section .loader").css("display", "block");
        jQuery.ajax({
             type: 'post',
             url: ajaxurl,
             data:{
                 _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                 action: 'eh_order_status_update',
                 order_id: ids,
                 order_action : action,
                 paged: parseInt(jQuery('input[name=paged]').val()) || '1'
             },
             success: function(response) {
                 get_all_orders_js();
             },
             error: function(jqXHR, textStatus, errorThrown) {
                 console.log(textStatus, errorThrown);
             }
         });
    });
    function get_bulk_ids() {
        var chkArray = [];
        jQuery('input[name="orders[]"]:checked').each(function() {
            chkArray.push(jQuery(this).val());
        });
        var selected;
        selected = chkArray.join(',') + ",";
        if (selected.length > 1) {
            return (selected.slice(0, -1));
        } else {
            return ('');
        }
    }
    jQuery( '.tablenav' ).on( 'change','#bulk-action-selector-top', function() 
    {
        var value=jQuery('#bulk-action-selector-top').val();
        if(value==='-1')
        {
            console.log('hello');
            jQuery('#doaction').prop('disabled', 'disabled');
        }
        else
        {
            jQuery('#doaction').removeAttr('disabled');
        }
    }).change();
    jQuery('#wrap_table').on('click', '#save_dislay_count_order', function() {
        jQuery('#save_dislay_count_order').prop('disabled', 'disabled');
        var row_count=jQuery('#display_count_order').val();
        jQuery.ajax({
             type: 'post',
             url: ajaxurl,
             data: {
                 _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                 action: 'eh_order_display_count',
                 row_count:row_count
             },
             success: function(response) {
                 //get_all_orders_js();
                 location.reload();
                 jQuery('#save_dislay_count_order').removeAttr('disabled');
             },
             error: function(jqXHR, textStatus, errorThrown) {
                 console.log(textStatus, errorThrown);
             }
         });
    });
    jQuery('#wrap_table').on('click', '#save_dislay_count_stripe', function() {
        jQuery('#save_dislay_count_stripe').prop('disabled', 'disabled');
        var row_count=jQuery('#display_count_stripe').val();
        jQuery.ajax({
             type: 'post',
             url: ajaxurl,
             data: {
                 _ajax_eh_spg_nonce: jQuery('#_ajax_eh_spg_nonce').val(),
                 action: 'eh_stripe_display_count',
                 row_count:row_count
             },
             success: function(response) {
                 get_all_stripe_js();
                 jQuery('#save_dislay_count_stripe').removeAttr('disabled');
             },
             error: function(jqXHR, textStatus, errorThrown) {
                 console.log(textStatus, errorThrown);
             }
         });
    });
});
jQuery(document).ready(function() {
    jQuery('table.wp-list-table').tableSearch();
});
(function(jQuery) {
    jQuery.fn.tableSearch = function(options) {
        if (!jQuery(this).is('table')) {
            return;
        }
        var tableObj = jQuery(this),
            inputObj = jQuery('#search_id-search-input');
        inputObj.off('keyup').on('keyup', function() {
            var searchFieldVal = jQuery(this).val();
            tableObj.find('tbody tr').hide().each(function() {
                var currentRow = jQuery(this);
                currentRow.find('td').each(function() {
                    if (jQuery(this).html().indexOf(searchFieldVal) > -1) {
                        currentRow.show();
                        return false;
                    }
                });
            });
        });
    }
}(jQuery));
