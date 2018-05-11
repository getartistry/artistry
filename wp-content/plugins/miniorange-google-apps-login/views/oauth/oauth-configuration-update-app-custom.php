<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 13-02-2018
 * Time: 15:23
 */
?>

    <div class="mo_registration_divided_layout">

    
    
        <div class="mo_gsuite_registration_table_layout" id="attribute-mapping">
<?php if ( $is_other_app ) { ?>
        <form id="form-common" name="form-common" method="post" action="admin.php?page=mo_oauth_settings">
            <h3>Attribute Mapping</h3>
            <p style="font-size:13px;color:#dc2424">Do <b>Test Configuration</b> above to get configuration for attribute
                mapping.<br></p>
            <input type="hidden" name="option" value="mo_oauth_attribute_mapping"/>
            <input class="mo_table_textbox" required="" type="hidden" id="mo_oauth_app_name" name="mo_oauth_app_name"
                   value="<?php echo $currentappname; ?>">
            <input class="mo_table_textbox" required="" type="hidden" name="mo_oauth_custom_app_name"
                   value="<?php echo $currentappname; ?>">
            <table class="mo_settings_table">
                <tr id="mo_oauth_email_attr_div">
                    <td><strong><font color="#FF0000">*</font>Email:</strong></td>
                    <td><input class="mo_table_textbox" required="" placeholder="Enter attribute name for Email" type="text"
                               id="mo_oauth_email_attr" name="mo_oauth_email_attr"
                               value="<?php if ( isset( $currentapp['email_attr'] ) ) {
                                   echo $currentapp['email_attr'];
                               } ?>"></td>
                </tr>
                <tr id="mo_oauth_name_attr_div">
                    <td><strong><font color="#FF0000">*</font>First Name:</strong></td>
                    <td><input class="mo_table_textbox" required="" placeholder="Enter attribute name for First Name"
                               type="text" id="mo_oauth_name_attr" name="mo_oauth_name_attr"
                               value="<?php if ( isset( $currentapp['name_attr'] ) ) {
                                   echo $currentapp['name_attr'];
                               } ?>"></td>
                </tr>
    
    
                <?php
                echo '<tr>
                <td><strong>Last Name:</strong></td>
                <td>
                    <p>Advanced attribute mapping is available in <a href="admin.php?page=gsuitepricing"><b>premium</b></a> version.</p>
                    <input type="text" name="oauth_client_am_last_name" placeholder="Enter attribute name for Last Name" style="width: 350px;" value="" readonly /></td>
              </tr>
              <tr>
                <td><strong>Username:</strong></td>
                <td><input type="text" name="oauth_client_am_group_name" placeholder="Enter attribute name for Username" style="width: 350px;" value="" readonly /></td>
              </tr>
              <tr>
                <td><strong>Group/Role:</strong></td>
                <td><input type="text" name="oauth_client_am_group_name" placeholder="Enter attribute name for Group/Role" style="width: 350px;" value="" readonly /></td>
              </tr>
              <tr>
                <td><strong>Display Name:</strong></td>
                <td>
                    <select name="oauth_client_am_display_name" id="oauth_client_am_display_name" disabled style="background-color: #eee;">
                        <option value="USERNAME"';
                if ( get_option( 'oauth_client_am_display_name' ) == 'USERNAME' ) {
                    echo 'selected="selected"';
                }
                echo '>Username</option>
                        <option value="FNAME"';
                if ( get_option( 'oauth_client_am_display_name' ) == 'FNAME' ) {
                    echo 'selected="selected"';
                }
                echo '>FirstName</option>
                        <option value="LNAME"';
                if ( get_option( 'oauth_client_am_display_name' ) == 'LNAME' ) {
                    echo 'selected="selected"';
                }
                echo '>LastName</option>
                        <option value="FNAME_LNAME"';
                if ( get_option( 'oauth_client_am_display_name' ) == 'FNAME_LNAME' ) {
                    echo 'selected="selected"';
                }
                echo '>FirstName LastName</option>
                        <option value="LNAME_FNAME"';
                if ( get_option( 'oauth_client_am_display_name' ) == 'LNAME_FNAME' ) {
                    echo 'selected="selected"';
                }
                echo '>LastName FirstName</option>
                    </select>
                </td></tr>'; ?>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="Save settings"
                               class="button button-primary button-large"/></td>
                </tr>
            </table>
        </form>
        </div>


    <div class="mo_gsuite_registration_table_layout" style="background: #dfdbdb" id="role-mapping">

    <h3>Role Mapping (Optional)</h3>
    <div class="mo_oauth_premium_option_text">
			<span style="color:red;">*</span>This is a premium feature.
				<a href="admin.php?page=gsuitepricing">Click Here</a> to see our full list of Premium Features.
		</div>
    <table width="100%">
        <tr>
            <td colspan="2"><b>NOTE: </b>Role will be assigned only to non-admin users (user that do NOT have
                Administrator privileges). You will have to manually change the role of Administrator users.<br><br>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input disabled type="checkbox" id="dont_create_user_if_role_not_mapped"
                                   name="mo_oauth_client_dont_create_user_if_role_not_mapped" value="checked">&nbsp;&nbsp;Do
                not auto create users if roles are not mapped here.<br></td>
        </tr>
        <tr>
            <td colspan="2"><input disabled type="checkbox" id="dont_allow_unlisted_user_role"
                                   name="oauth_client_am_dont_allow_unlisted_user_role" value="checked">&nbsp;&nbsp;Do
                not assign role to unlisted users.<br></td>
        </tr>
        <tr>
            <td colspan="2"><input disabled type="checkbox" id="dont_update_existing_user_role"
                                   name="mo_oauth_client_dont_update_existing_user_role" value="checked">&nbsp;&nbsp;Do
                not update existing user's roles.<br><br></td>
        </tr>
        <tr>
            <td><b>Default Role:</b></td>
            <td><select disabled id="oauth_client_am_default_user_role" name="oauth_client_am_default_user_role"
                        style="width:150px;">
                    <option selected="selected" value="subscriber">Subscriber</option>
                    <option value="contributor">Contributor</option>
                    <option value="author">Author</option>
                    <option value="editor">Editor</option>
                    <option value="administrator">Administrator</option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;<i>Select the default role to assign to Users.</i>
            </td>
        </tr>
        <tr>
            <td><b>Administrator</b></td>
            <td><input readonly type="text" name="oauth_client_am_group_attr_values_administrator" value=""
                       placeholder="Semi-colon(;) separated Group/Role value for Administrator" style="width: 400px;">
            </td>
        </tr>
        <tr>
            <td><b>Editor</b></td>
            <td><input readonly type="text" name="oauth_client_am_group_attr_values_editor" value=""
                       placeholder="Semi-colon(;) separated Group/Role value for Editor" style="width: 400px;"></td>
        </tr>
        <tr>
            <td><b>Author</b></td>
            <td><input readonly type="text" name="oauth_client_am_group_attr_values_author" value=""
                       placeholder="Semi-colon(;) separated Group/Role value for Author" style="width: 400px;"></td>
        </tr>
        <tr>
            <td><b>Contributor</b></td>
            <td><input readonly type="text" name="oauth_client_am_group_attr_values_contributor" value=""
                       placeholder="Semi-colon(;) separated Group/Role value for Contributor" style="width: 400px;">
            </td>
        </tr>
        <tr>
            <td><b>Subscriber</b></td>
            <td><input readonly type="text" name="oauth_client_am_group_attr_values_subscriber" value=""
                       placeholder="Semi-colon(;) separated Group/Role value for Subscriber" style="width: 400px;"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><br><input type="submit" disabled style="width:100px;" name="submit" value="Save"
                           class="button button-primary button-large"> &nbsp;
                <br><br>
            </td>
        </tr>
        </tbody></table>


    <script>
        function testConfiguration() {
            var mo_oauth_app_name = jQuery("#mo_oauth_app_name").val();
            var myWindow = window.open('<?php echo site_url(); ?>' + '/?option=testattrmappingconfig&app=' + mo_oauth_app_name, "Test Attribute Configuration", "width=600, height=600");
        }
    </script>
    </div>
        


    </div>
<?php }