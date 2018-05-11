<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 08-02-2018
 * Time: 10:02
 */

/*echo '
	<!--<div class="mo_registration_divided_layout">
		<div class="mo_gsuite_registration_table_layout">

			<div class="mo_oauth_premium_option_text"><span style="color:red;">*</span>This is a premium feature.
				<a href="admin.php?page=gsuitepricing">Click Here</a> to see our full list of Premium Features.
			</div>
		</div>
	</div>-->';*/

echo '
<div class="mo_registration_divided_layout">
	<div class="mo_gsuite_registration_table_layout">
		
		<div class="mo_oauth_premium_option_text">
			<span style="color:red;">*</span>This is a premium feature. 
				<a href="admin.php?page=gsuitepricing">Click Here</a> to see our full list of Premium Features.
		</div>
		
		<form name="" method="post" action="" class="mo_oauth_premium_option">
		
			<table id="myTable" width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
			
		  	<tr>
		  		<td colspan="2">
		  			<h3>Attribute Mapping</h3>
		  			<hr>
		  		</td>
	        </tr>';

echo '
			<tr>
				<td style="width:200px;">
			  		<strong>Login/Create Wordpress account by: </strong>
	       		</td>
	       		
			  	<td>
			  		<select name="oauth_client_am_account_matcher" id="oauth_client_am_account_matcher">';

echo '			<option value="email" >Email</option>
				  <option value="username" >Username</option>
				</select>
			  	</td>
			  	
		  	</tr>
		    <tr>
				<td>&nbsp;</td>
				<td><i>Users in Wordpress will be searched (existing wordpress users) or created (new users) based on this attribute. Use Email by default.</i></td>
		  	</tr>
			  
		  	<tr>
				<td style="width:150px;">
				<strong>Username <span style="color:red;">*</span>:
				</strong>
				</td>
				
				<td>
				<input '.$field_disabled.' type="text" name="mo_oauth_client_am_username" placeholder="Enter attribute name for Username" style="width: 350px;" value="' . $mo_oauth_client_am_username . '" required />
				</td>
		  	</tr>
		  	
			<tr>
				<td><strong>Email <span style="color:red;">*</span>:</strong></td>
				<td><input '.$field_disabled.' type="text" name="oauth_client_am_email" placeholder="Enter attribute name for Email" style="width: 350px;" value="' . $oauth_client_am_email . '" required /></td>
		  	</tr>
			  <tr>
				<td><strong>First Name:</strong></td>
				<td><input '.$field_disabled.' type="text" name="oauth_client_am_first_name" placeholder="Enter attribute name for First Name" style="width: 350px;" value="' . $oauth_client_am_first_name . '" /></td>
			  </tr>
			  <tr>
				<td><strong>Last Name:</strong></td>
				<td><input '.$field_disabled.' type="text" name="oauth_client_am_last_name" placeholder="Enter attribute name for Last Name" style="width: 350px;" value="' . $oauth_client_am_last_name . '" /></td>
			  </tr>
			  <tr>
				<td><strong>Group/Role:</strong></td>
				<td><input '.$field_disabled.' type="text" name="oauth_client_am_group_name" placeholder="Enter attribute name for Group/Role" style="width: 350px;" value="' . $oauth_client_am_group_name . '" /></td>
			  </tr>
			  <tr>
				<td><strong>Display Name:</strong></td>
				<td>
					<select name="oauth_client_am_display_name" id="oauth_client_am_display_name" >
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
				</td>
			  </tr>
			  <tr><td colspan="2">
				<h3>Map Custom Attributes</h3>Map extra IDP attributes which you wish to be included in the user profile below
			</td><td><input '.$field_disabled.' type="button" name="add_attribute" value="+" onclick="add_custom_attribute();" class="button button-primary"  /></td>
							<td><input '.$field_disabled.' type="button" name="remove_attribute" value="-" onclick="remove_custom_attribute();" class="button button-primary"   /></td></tr>
			';
if ( get_option( 'mo_oauth_client_custom_attrs_mapping' ) ) {
	$custom_attributes = get_option( 'mo_oauth_client_custom_attrs_mapping' );
	$i                 = 0;
	foreach ( $custom_attributes as $key => $value ) {
		$i ++;
		echo '<tr class="rows"><td><input '.$field_disabled.' type="text" name="mo_oauth_client_custom_attribute_key_' . $i . '" placeholder="Enter field meta name"  value="' . $key . '" /></td>
			 <td><input '.$field_disabled.' type="text" name="mo_oauth_client_custom_attribute_value_' . $i . '" placeholder="Enter attribute name from idp" style="width:74%;" value="' . $value . '" /></td>
			 </tr>';
	}
} else {
	echo '<tr><td><input '.$field_disabled.' type="text" name="mo_oauth_client_custom_attribute_key_1" placeholder="Enter field meta name"   /></td>
			 <td><input '.$field_disabled.' type="text" name="mo_oauth_client_custom_attribute_value_1" placeholder="Enter attribute name from idp" style="width:74%;"  /></td>
			 </tr>';

}
echo '<tr id="save_config_element">
				<td><br /><input '.$field_disabled.' type="submit" style="width:100px;" name="submit" value="Save" class="button button-primary button-large" /> &nbsp;
				<br /><br />
				</td>
			  </tr>
			 </table>
			 </form>
<script>
		var countAttributes = jQuery("#myTable tr.rows").length;
		function add_custom_attribute(){
			countAttributes += 1;
			rows = "<tr id=\"row_" +countAttributes + "\"><td><input '.$field_disabled.' type=\"text\" name=\"mo_oauth_client_custom_attribute_key_" + countAttributes + "\" id=\"mo_oauth_client_custom_attribute_key_" +countAttributes + "\"  placeholder=\"Enter field meta name\"  ></td><td><input '.$field_disabled.' type=\"text\" name=\"mo_oauth_client_custom_attribute_value_" +countAttributes + "\" id=\"mo_oauth_client_custom_attribute_value_" +countAttributes + "\" placeholder=\"Enter Attribute Name from Idp\" style=\"width:74%;\" /></td></tr>";

			jQuery(rows).insertBefore(jQuery("#save_config_element"));


			}

			function remove_custom_attribute(){
				jQuery("#row_" + countAttributes).remove();
				countAttributes -= 1;
				if(countAttributes == 0)
					countAttributes = 1;
			}
			</script>
			 <br />
			 
			 <div class="mo_oauth_premium_option_text"><span style="color:red;">*</span>This is a premium feature. 
		<a href="admin.php?page=gsuitepricing">Click Here</a> to see our full list of Premium Features.</div>
		<form name="oauth_client_form_am_role_mapping" method="post" action=""  class="mo_oauth_premium_option">
				
				<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
					<tr>
						<td colspan="2">
							<h3>Role Mapping (Optional)</h3><hr>
						</td>
					</tr>
					 
					<tr><td colspan="2"><br/><b>NOTE: </b>Role will be assigned only to non-admin users (user that do NOT have Administrator privileges). You will have to manually change the role of Administrator users.<br /><br/></td></tr>
					<tr><td colspan="2"><input '.$field_disabled.' type="checkbox" id="dont_create_user_if_role_not_mapped" name="mo_oauth_client_dont_create_user_if_role_not_mapped" value="checked"'
     . get_option( 'mo_oauth_client_dont_create_user_if_role_not_mapped' );
echo '/>&nbsp;&nbsp;Do not auto create users if roles are not mapped here.<br /></td></tr>
					<tr><td colspan="2"><input '.$field_disabled.' type="checkbox" id="dont_allow_unlisted_user_role" name="oauth_client_am_dont_allow_unlisted_user_role" value="checked"' . get_option( 'oauth_client_am_dont_allow_unlisted_user_role' );
echo ' />&nbsp;&nbsp;Do not assign role to unlisted users.<br /></td></tr>
					<tr><td colspan="2"><input '.$field_disabled.' type="checkbox" id="dont_update_existing_user_role" name="mo_oauth_client_dont_update_existing_user_role" value="checked"'
     . get_option( 'oauth_client_am_dont_update_existing_user_role' );
echo '/>&nbsp;&nbsp;Do not update existing user\'s roles.<br /><br /></td></tr>
					<tr>
						<td><b>Default Role:</b></td>
						<td>';
$disabled = '';
echo '<select id="oauth_client_am_default_user_role" name="oauth_client_am_default_user_role"' . $disabled . ' style="width:150px;" >';
$default_role = get_option( 'oauth_client_am_default_user_role' );
if ( empty( $default_role ) ) {
	$default_role = get_option( 'default_role' );
}
echo wp_dropdown_roles( $default_role );
echo '	</select>
							&nbsp;&nbsp;&nbsp;&nbsp;<i>Select the default role to assign to Users.</i>
						</td>
				  	</tr>';
$is_disabled      = "";
$wp_roles         = new WP_Roles();
$roles            = $wp_roles->get_names();
$roles_configured = get_option( 'oauth_client_am_role_mapping' );
foreach ( $roles as $role_value => $role_name ) {
	echo '<tr><td><b>' . $role_name . '</b></td><td><input '.$field_disabled.' type="text" name="oauth_client_am_group_attr_values_' . $role_value . '" value="' . $roles_configured[ $role_value ] . '" placeholder="Semi-colon(;) separated Group/Role value for ' . $role_name . '" style="width: 400px;"' . $is_disabled . ' /></td></tr>';
}
echo '<tr>
						<td>&nbsp;</td>
						<td><br /><input '.$field_disabled.' type="submit" style="width:100px;" name="submit" value="Save" class="button button-primary button-large"';
echo '/> &nbsp;
						<br /><br />
						</td>
					</tr>
				</table>
			</form>
			
			</div></div>';
