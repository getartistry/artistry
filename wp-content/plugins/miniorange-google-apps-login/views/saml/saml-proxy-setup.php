<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 15-02-2018
 * Time: 17:19
 */
?>
<div class="mo_registration_divided_layout">

  <form id="gsuite_saml_proxy_setting_form" width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;"
          name="saml_form" method="post" action="">
        <input <?php echo $disabled; ?> type="hidden" name="option" value="mo_saml_save_proxy_setting"/>
        <input id="action_value" type="hidden" name="action"  />
        <table style="width:100%;">
            <tr>
                <td colspan="2">
                    <h3>Configure Proxy Server
                    </h3>
                </td>
            </tr>
            <?php if ( ! Mo_GSuite_Utility::micr() ) { ?>
                <tr>
                    <td colspan="2">
                        <div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                            Please <a
                                    href="<?php echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Register
                                or Login with miniOrange</a> to configure the miniOrange SAML Plugin.
                        </div>
                    </td>
                </tr>
            <?php } ?>

            <tr>
                <td colspan="2">Enter the information to setup the proxy server.<br/><br/></td>
            </tr>
            <tr>
                <td style="width:200px;"><strong>Proxy Host Name:</strong></td>
                <td><input <?php echo $disabled; ?> type="text" name="mo_saml_proxy_host" placeholder="Enter the host name" style="width: 95%;"
                           value="<?php echo get_option( "mo_saml_proxy_host" ); ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Port Number:</strong></td>
                <td><input <?php echo $disabled; ?> type="text" name="mo_proxy_port" placeholder="Enter the port number of the proxy"
                           style="width: 95%;" value="<?php echo get_option( "mo_proxy_port" ); ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Username:</strong></td>
                <td><input <?php echo $disabled; ?> type="text" name="mo_proxy_username" placeholder="Enter the username of proxy server"
                           style="width: 95%;" value="<?php echo get_option( "mo_proxy_username" ); ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Password:</strong></td>
                <td><input <?php echo $disabled; ?> type="password" name="mo_proxy_password" placeholder="Enter the password of proxy server"
                           style="width: 95%;" value="<?php echo get_option( "mo_proxy_password" ); ?>"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><br/><input <?php echo $disabled; ?> type="button" name="btnSubmit" style="width:100px;" onclick="submit_function('mo_gsuite_proxy_setting_save')" value="Save"
                                class="button button-primary button-large"/> &nbsp;

                    <input   <?php echo $disabled; ?> type="button"
                    style=" padding-left: 20px;margin-left:  10px;width: 80px; " onclick="submit_function('mo_gsuite_proxy_setting_reset')" name="btnSubmit" value="Reset"
                    class="button button-primary button-large" />

                </td>
            </tr>
        </table>
        <br/>
        <br/><br/>
    </form>

</div>

<?php
echo '<script>
function submit_function(actionval) {
    //alert(actionval);
    document.getElementById("action_value").setAttribute(\'value\',actionval);
	document.getElementById(\'gsuite_saml_proxy_setting_form\').submit();
}
</script>';
?>