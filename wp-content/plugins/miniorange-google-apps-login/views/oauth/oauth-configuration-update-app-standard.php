<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 13-02-2018
 * Time: 13:15
 */

echo  '<div class="mo_registration_divided_layout">
        <div class="mo_gsuite_registration_table_layout">
            <div id="toggle2" class="panel_toggle">
                <h3>Update Application</h3>
            </div>
            <form id="form-common" name="form-common" method="post" action="">
                <input type="hidden" name="option" value="mo_oauth_add_app"/>
                <table class="mo_update_app_table">
                    <tr>
                        <td><strong><font color="#FF0000">*</font>Application:</strong></td>
                        <td>
                            <input required="" type="hidden" name="mo_oauth_app_name"
                                   value="'.$appname.'">
                            <input required="" type="hidden" name="mo_oauth_custom_app_name"
                                   value="'.$appname.'">
                                   '.$appname.'
							<br><br>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><font color="#FF0000">*</font>Client ID:</strong></td>
                        <td><input required="" type="text" name="mo_oauth_client_id"
                                   value="'.$currentapp['clientid'].'"></td>
                    </tr>
                    <tr>
                        <td><strong><font color="#FF0000">*</font>Client Secret:</strong></td>
                        <td><input required="" type="text" name="mo_oauth_client_secret"
                                   value="'.$currentapp['clientsecret'].'"></td>
                    </tr>
                    <tr>
                        <td><strong>Scope:</strong></td>
                        <td><input type="text" name="mo_oauth_scope"
                                   value="'.$currentapp['scope'].'"></td>
                    </tr>		
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" name="submit" style="width:20%" value="Save settings"
                                   class="button button-primary button-large save-settings"/>
							
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>';

echo'<style>
    .mo_update_app_table{
        width: 100%;
    }
    .mo_update_app_table tr >td:first-child {
        width: 30%;
    }

    .mo_update_app_table tr >td input {
        width: 90%;
    }

    .save-settings{
        margin-left: 30%;
        margin-right: 30%;

    }
</style>';
