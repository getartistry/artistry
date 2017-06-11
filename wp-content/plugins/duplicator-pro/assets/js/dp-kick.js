/* 
 * DP Kickoff Script
 */

jQuery(document).ready(function ()
{
    // Start calling the thing every 15 seconds to ensure it runs in a decent amount of time
    
    var data = {action: 'duplicator_pro_process_worker'}

    dp_kickme = function ()
    {
        console.log("dp_kick");
        jQuery.ajax({
            async: true,
            type: "POST",
            url: dp_gateway.ajaxurl,
            dataType: "json",
            timeout: 10000000,
            data: data,
            complete: function () {

            },
            success: function (data) {
                if (data['status'] == 0)
                {
                    //   DupPro.Schedule.SetUpdateInterval(1);
                    //              alert("Process worker sent");
                }
                else
                {
                    //             alert("Process worker failed");
                    console.log(data);
                }
            },
            error: function (data) {
                // alert(data);
                console.log(data);

                //         console.log(data);
            }
        });
    }
    
    dp_kickme();
    window.setInterval(dp_kickme, dp_gateway.client_call_frequency);

});
