<?php function mo_ga_sso_attr_role_mapping() { 
	global $wpdb;
	$entity_id = get_option('entity_id');
	$sso_url = get_option('sso_url');
	$cert_fp = get_option('cert_fp');
	
	$mo_ga_sso_saml_identity_name = get_option('mo_ga_sso_saml_identity_name');
	
	//Attribute mapping
	$mo_ga_sso_username = get_option('mo_ga_sso_username');	
	if($mo_ga_sso_username == NULL) {$mo_ga_sso_username = 'NameID'; }
	$mo_ga_sso_email = get_option('mo_ga_sso_email');
	if($mo_ga_sso_email == NULL) {$mo_ga_sso_email = 'NameID'; }
	$mo_ga_sso_first_name = get_option('mo_ga_sso_first_name');
	$mo_ga_sso_last_name = get_option('mo_ga_sso_last_name');
	$mo_ga_sso_group_name = get_option('mo_ga_sso_group_name');
	?>
		<form name="mo_ga_sso_saml_form_am" method="post" action="">
		<input type="hidden" name="option" value="login_widget_ga_sso_attribute_mapping" />
		<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
		  <tr>
			<td colspan="2">
				<h3>Attribute Mapping (Optional)</h3>
			</td>
		  </tr>
		  <?php if(!mo_ga_login_is_customer_registered()) { ?>
		  <tr>
			<td colspan="2"><div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to configure the miniOrange Google Apps Login Plugin.</div></td>
		  </tr>
		  <?php } ?>
		  <tr>
		  	<td colspan="2">[ <a href="#" id="attribute_mapping">Click Here</a> to know how this is useful. ]
		 		<div hidden id="attribute_mapping_desc" class="mo_ga_sso_help_desc">
					<ol>
						<li>Attributes are user details that are stored in your Identity Provider.</li>
						<li>Attribute Mapping helps you to get user attributes from your IdP and map them to WordPress user attributes like firstname, lastname etc.</li>
						<li>While auto registering the users in your WordPress site these attributes will automatically get mapped to your WordPress user details.</li>
					</ol>
				</div>
				</td>
		  </tr>
		  <tr>
			 <td colspan="2"><br/><b>NOTE: </b>Use attribute name <code>NameID</code> if Identity is in the <i>NameIdentifier</i> element of the subject statement in SAML Response.<br /><br /></td>
		  </tr>
			
			  <tr>
			  <td style="width:200px;"><strong>Login/Create Wordpress account by: </strong></td>
			  <td><select name="mo_ga_sso_account_matcher" id="mo_ga_sso_account_matcher" <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>>
				  <option value="email"<?php if(get_option('mo_ga_sso_account_matcher') == 'email') echo 'selected="selected"' ; ?> >Email</option>
				  <option value="username"<?php if(get_option('mo_ga_sso_account_matcher') == 'username') echo 'selected="selected"' ; ?> >Username</option>
				</select>
			  </td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><i>Users in Wordpress will be searched (existing wordpress users) or created (new users) based on this attribute. Use Email by default.</i></td>
			  </tr>
		
				  <tr>
					<td style="width:150px;"><span style="color:red;">*</span><strong>Username (required):</strong></td>
					<td><b>NameID</b></td>
				  </tr>
				  <tr>
					<td><span style="color:red;">*</span><strong>Email (required):</strong></td>
					<td><b>NameID</b></td>
				  </tr>	  			
		
			  <tr>
				<td><strong>First Name:</strong></td>
				<td><input type="text" name="mo_ga_sso_first_name" placeholder="Enter attribute name for First Name" style="width: 350px;" value="<?php echo $mo_ga_sso_first_name;?>" <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/></td>
			  </tr>
			  <tr>
				<td><strong>Last Name:</strong></td>
				<td><input type="text" name="mo_ga_sso_last_name" placeholder="Enter attribute name for Last Name" style="width: 350px;" value="<?php echo $mo_ga_sso_last_name;?>" <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/></td>
			  </tr>
			
			  	  <tr>
					<td><span style="color:red;">*</span><strong>Group/Role:</strong></td>
					<td><input type="text" disabled placeholder="Enter attribute name for Group/Role" style="width: 350px;background: #DCDAD1;"/></td>
				  </tr>
				
			 	  <tr>
			  		<td colspan="2"><br /><span style="color:red;">*</span>Username and Email are configurable in <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>standard, premium and enterprise</b></a> versions of the plugin.<br />Group/Role is configurable in <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>premium and enterprise</b></a> versions of the plugin.<br />Customized Attribute Mapping is configurable in the <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>premium and enterprise</b></a> versions of the plugin. Customized Attribute Mapping means you can map any attribute of the IDP to the attributes of <b>user-meta</b> table of your database.</td>
			 	  </tr>
		
			  <tr>
				<td>&nbsp;</td>
				<td><br /><input type="submit" style="width:100px;" name="submit" value="Save" class="button button-primary button-large" <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/> &nbsp; 
				<br /><br />
				</td>
			  </tr>
			 </table>
			 </form>
			 <br />
			 <form name="mo_ga_sso_saml_form_am_role_mapping" method="post" action="">
				<input type="hidden" name="option" value="login_widget_ga_sso_role_mapping" />
				<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
					<tr>
						<td colspan="2">
							<h3>Role Mapping (Optional)</h3>
						</td>
					</tr>
					 <tr>
					  	<td colspan="2">[ <a href="#" id="role_mapping">Click Here</a> to know how this is useful. ]
					 		<div hidden id="role_mapping_desc" class="mo_ga_sso_help_desc">
								<ol>
									<li>WordPress uses a concept of Roles, designed to give the site owner the ability to control what users can and cannot do within the site.</li>
									<li>WordPress has six pre-defined roles: Super Admin, Administrator, Editor, Author, Contributor and Subscriber.</li>
									<li>Role mapping helps you to assign specific roles to users of a certain group in your IdP.</li>
									<li>While auto registering, the users are assigned roles based on the group they are mapped to.</li>
								</ol>
							</div>
							</td>
					  </tr>
					<tr><td colspan="2"><br/><b>NOTE: </b>Role will be assigned only to new users. Existing Wordpress users' role remains same.<br /><br/></td></tr>
					<tr><td colspan="2"><input type="checkbox" disabled style="background: #DCDAD1;" />&nbsp;&nbsp;<span style="color:red;">*</span>Do not auto create users if roles are not mapped here.<br /></td></tr>
				
						<tr><td colspan="2"><input type="checkbox" style="background: #DCDAD1;" disabled />&nbsp;&nbsp;<span style="color:red;">*</span>Do not assign role to unlisted users.<br /><br /></td></tr>	
				
					<tr>
						<td><strong>Default Role:</strong></td>
						<td>
							<?php 
								$disabled = '';
								if(!mo_ga_login_is_customer_registered())
									$disabled = 'disabled';
							?>
								<select id="mo_ga_sso_default_user_role" name="mo_ga_sso_default_user_role" <?php echo $disabled ?> style="width:150px;" >
							 <?php 
								$default_role = get_option('mo_ga_sso_default_user_role');
								if(empty($default_role))
									$default_role = get_option('default_role');
								echo wp_dropdown_roles( $default_role );
							?> 
								</select>
							&nbsp;&nbsp;&nbsp;&nbsp;<i>Select the default role to assign to Users.</i>
						</td>
				  	</tr>
					<?php
						$is_disabled = "";
						if(!mo_ga_login_is_customer_registered()) {
							$is_disabled = "disabled";
						}
						$wp_roles = new WP_Roles();
						$roles = $wp_roles->get_names();
						$roles_configured = get_option('mo_ga_sso_role_mapping');
						foreach ($roles as $role_value => $role_name) {
							
								echo '<tr><td><span style="color:red;">*</span><b>' . $role_name .'</b></td><td><input type="text" placeholder="Semi-colon(;) separated Group/Role value for ' . $role_name . '" style="width: 400px;background: #DCDAD1" disabled /></td></tr>';
							
						}
					?>
					
					<tr>
					  	<td colspan="2"><br /><span style="color:red;">*</span>Customized Role Mapping options are configurable in the <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>premium and enterprise</b></a> versions of the plugin. In the <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>standard</b></a> version, you can only assign the default role to the user.</td>
					</tr>

					<tr>
						<td>&nbsp;</td>
						<td><br /><input type="submit" style="width:100px;" name="submit" value="Save" class="button button-primary button-large" <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/> &nbsp; 
						<br /><br />
						</td>
					</tr>
				</table>
			</form>
	<?php
}