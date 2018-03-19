<?php function mo_ga_sso_login_settings() { 
	 if(mo_ga_login_is_customer_registered()){ ?>
			<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;">
					<h3>Sign in options</h3>
					<input type="checkbox" style="background: #DCDAD1;" disabled /> <span style="color: red;">*</span>Redirect to IdP if user not logged in.
					<a href="#" id="registered_only_access">[What does this mean?]</a>
					<br><div hidden id="registered_only_access_desc" class="mo_ga_sso_help_desc">
					<span>Select this option if you want to restrict your site to only logged in users. Selecting this option will redirect the users to your IdP if logged in session is not found.</span>
					</div>
					<br />
					
						<input type="hidden" name="option" value="mo_ga_sso_force_authentication_option"/>
						<input type="checkbox" name="mo_ga_sso_force_authentication" style="background: #DCDAD1;" disabled /> <span style="color: red;">*</span>Force authentication with your IdP on each login attempt.
						<a href="#" id="force_authentication_with_idp">[What does this mean?]</a>
						<br><div hidden id="force_authentication_with_idp_desc" class="mo_ga_sso_help_desc">
						<span>It will force user to provide credentials on your IdP on each login attempt even if the user is already logged in to IdP. This option may require some additional setting in your IdP to force it depending on your Identity Provider.</span>
						</div>
					<br/>
					Choose how you want users to log into your WordPress website. You can choose any or all of the three options below.<br/><br/>
					<span style="font-size:15px;"><b>Option 1: Use Default WordPress LogIn</b></span>
					<div style="margin-left:17px;margin-top:2%;">
							<input type="hidden" name="option" value="mo_ga_sso_enable_login_redirect_option"/>
							
								<input type="checkbox" style="background: #DCDAD1;" <?php checked(get_option('mo_ga_sso_allow_wp_signin') == 'true');?> disabled /> <span style="color: red;">*</span>Check this option if you want to <b>auto redirect the user to IdP</b>.
								<a href="#" id="redirect_to_idp">[What does this mean?]</a>
								<br><div hidden id="redirect_to_idp_desc" class="mo_ga_sso_help_desc">
								<span>Users visiting any of the following URLs will get redirected to your configured IdP for authentication:</span>
								<br/><code><b><?php echo wp_login_url(); ?></b></code> or <code><b><?php echo admin_url(); ?></b></code>
								</div>
							
							<input type="hidden" name="option" value="mo_ga_sso_allow_wp_signin_option"/>
							<p>
							
								<input type="checkbox" style="background: #DCDAD1;" <?php checked(get_option('mo_ga_sso_allow_wp_signin') == 'true');?> disabled /> <span style="color: red;">*</span>Checking this option creates a backdoor to login to your Website using WordPress credentials incase you get locked out of your IdP.
									<i>(Note down this URL: <code><b><?php echo site_url(); ?>/wp-login.php?mo_ga_sso_saml_sso=false</b></code> )</i>
							
							</p>
					</div>
					<span style="font-size:15px;"><b>Option 2: Use a Widget</b></span>
					<div style="margin:2% 0 2% 17px;">
					<input type="checkbox" name="mo_ga_sso_add_widget" id="mo_ga_sso_add_widget" <?php if(!mo_ga_sso_is_sp_configured()) echo 'disabled title="Disabled. Configure your Service Provider"'?> value="true"> Check this option if you want to add a Widget to your page.
						<div id="mo_ga_sso_add_widget_steps"  hidden >
						<ol>
							<li>Go to Appearances > Widgets.</li>
							<li>Select "Login with <?php echo get_option('mo_ga_sso_saml_identity_name'); ?>". Drag and drop to your favourite location and save.</li>
						</ol>
						</div>
					</div>
					
						<span style="font-size:15px;"><b>Option 3: Use a ShortCode</b></span>
						<div style="margin:2% 0 2% 17px;">
							
								<input type="checkbox" style="background: #DCDAD1;" disabled <?php if(!mo_ga_sso_is_sp_configured()) echo 'disabled title="Disabled. Configure your Service Provider"'?> value="true"> <span style="color: red">*</span>Check this option if you want to add a shortcode to your page.
								<br />
						
						</div>
					<div style="display:block;text-align:center;margin:2%;">
					 <input type="button" onclick="window.location.href='<?php echo wp_logout_url(site_url());?>'" <?php if(!mo_ga_sso_is_sp_configured()) echo 'disabled title="Disabled. Configure your Service Provider"'?> class="button button-primary button-large" value="Log Out and Test"></input>
					</div>
					
						<span style="color:red;">*</span>These options are configurable in the <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>premium</b></a> version of the plugin.</h3>
						<br /><br />
				
			</div>
			<br />
			<div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
					<h3>Your Profile</h3>
					<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
						<tr>
							<td style="width:45%; padding: 10px;">miniOrange Account Email</td>
							<td style="width:55%; padding: 10px;"><?php echo get_option('mo_ga_sso_admin_email')?></td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;">Customer ID</td>
							<td style="width:55%; padding: 10px;"><?php echo get_option('mo_ga_sso_admin_customer_key')?></td>
						</tr>
					</table><br/>
			</div>
			<script>
				jQuery('#mo_ga_sso_add_widget').click(function() {
					if(document.getElementById('mo_ga_sso_add_widget').checked) {
						jQuery("#mo_ga_sso_add_widget_steps").show();
					} else {
						jQuery("#mo_ga_sso_add_widget_steps").hide();
					}
				});
			</script>
	<?php }
}