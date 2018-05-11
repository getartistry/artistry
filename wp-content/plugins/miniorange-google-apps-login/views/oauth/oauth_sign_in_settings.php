<?php
echo '
<div class="mo_registration_divided_layout">
	<div class="mo_gsuite_registration_table_layout">';

is_gsuite_customer_registered();

echo '	<h2>Sign in options</h2>
		
		<h4>Option 1: Use a Widget</h4>
		<ol>
			<li>Go to Appearances > Widgets.</li>
			<li>Select <b>"'.Mo_Gsuite_Constants::OAUTH_WIDGET_NAME.'"</b> Drag and drop to your favourite location and save.</li>
		</ol>
		
		<h4>Option 2: Use a Shortcode</h4>
		<ul>
			<li>Place shortcode <b>[mo_oauth_login]</b> in wordpress pages or posts.</li>
		</ul>
	</div>
	
			
	<div class="mo_gsuite_registration_table_layout mo_oauth_premium_option">
		
		<div class="mo_oauth_premium_option_text"><span style="color:red;">*</span>This is a premium feature.
	
		<a href="admin.php?page=gsuitepricing">Click Here</a> to see our full list of Premium Features.
	</div>
		
		<h3>Advanced Settings</h3>
		<br><br>
		<form id="role_mapping_form" name="f" method="post" action="">
		<input disabled="true" type="checkbox" name="restrict_to_logged_in_users" value="1"><strong> Restrict site to logged in users</strong> ( Users will be auto redirected to OAuth login if not logged in )
		<p><input disabled="true" type="checkbox" name="popup_login" value="1"><strong> Open login window in Popup</strong></p>
		<table class="mo_oauth_client_mapping_table" id="mo_oauth_client_role_mapping_table" style="width:90%">
		<tbody><tr>
		<td><font style="font-size:13px;font-weight:bold;">Custom redirect URL after login </font>
		</td>
		<td><input disabled="true" type="text" name="custom_after_login_url" placeholder="" style="width:100%;" value=""></td>
		</tr>
		<tr>
		<td><font style="font-size:13px;font-weight:bold;">Custom redirect URL after logout </font>
		</td>
		<td><input disabled="true" type="text" name="custom_after_logout_url" placeholder="" style="width:100%;" value=""></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
		<td><input disabled="true" type="submit" class="button button-primary button-large" value="Save Settings"></td>
		<td>&nbsp;</td>
		</tr>
		</tbody></table>
		</form>
		
	</div>
	
</div>';

echo '<style>

</style>';

/*
 * .mo_oauth_premium_option {
    background-color: rgba(168, 168, 168, 0.7);
    padding: 2%;
    opacity: 0.5;
 }
.mo_oauth_premium_option_text {
    position: absolute;
    font-weight: bold;
    margin: 5.5% 11%;
    z-index: 1;
}
 * */
