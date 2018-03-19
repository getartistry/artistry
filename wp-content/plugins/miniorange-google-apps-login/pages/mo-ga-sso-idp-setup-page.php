<?php function mo_ga_sso_saml_setup() { 
	 
		global $wpdb;
		$entity_id = get_option('entity_id');
		$sso_url = get_option('sso_url');
		$cert_fp = get_option('cert_fp');
		$mo_ga_sso_saml_identity_name = get_option('mo_ga_sso_saml_identity_name');
		$mo_ga_sso_saml_login_url = get_option('mo_ga_sso_saml_login_url');
		$mo_ga_sso_saml_issuer = get_option('mo_ga_sso_saml_issuer');
		$mo_ga_sso_saml_x509_certificate = get_option('mo_ga_sso_saml_x509_certificate');
		$mo_ga_sso_saml_response_signed = get_option('mo_ga_sso_saml_response_signed');
		if($mo_ga_sso_saml_response_signed == NULL) {$mo_ga_sso_saml_response_signed = 'checked'; }
		$mo_ga_sso_saml_assertion_signed = get_option('mo_ga_sso_saml_assertion_signed');
		if($mo_ga_sso_saml_assertion_signed == NULL) {$mo_ga_sso_saml_assertion_signed = 'Yes'; }
		
		$idp_config = get_option('mo_ga_sso_idp_config_complete');
		?>
		<form width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;" name="mo_ga_sso_saml_form" method="post" action="">
		<input type="hidden" name="option" value="login_widget_ga_sso_save_settings" />
		<table style="width:100%;">
			<tr>
				<td colspan="2">
					<h3>Configure Google Apps as IDP</h3><hr>
					<p>To fill in this information you need to have admin access to any Google Apps domain. Enter the information gathered from your google apps domain here.</p>
				</td>
			</tr>
			<?php if(!mo_ga_login_is_customer_registered()) { ?>
				<tr>
					<td colspan="2"><div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to configure the miniOrange Google Apps Login Plugin.</div></td>
				</tr>
			<?php } ?>
			<tr>
				<td style="width:200px;"><strong>IDP Display Name <span style="color:red;">*</span>:</strong></td>
				<td><input type="text" name="mo_ga_sso_saml_identity_name" placeholder="Enter display name to be shown on login with google button" style="width: 95%;" value="<?php echo $mo_ga_sso_saml_identity_name;?>" required <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?> pattern="^\w*$" title="Only alphabets, numbers and underscore is allowed"/></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><strong>IdP Entity ID or Issuer <span style="color:red;">*</span>:</strong></td>
				<td><input type="text" name="mo_ga_sso_saml_issuer" placeholder="Identity Provider Entity ID or Issuer" style="width: 95%;" value="<?php echo $mo_ga_sso_saml_issuer;?>" required <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><strong>SAML Login URL <span style="color:red;">*</span>:</strong></td>
				<td><input type="url" name="mo_ga_sso_saml_login_url" placeholder="Single Sign On Service URL (HTTP-Redirect binding) of your IdP" style="width: 95%;" value="<?php echo $mo_ga_sso_saml_login_url;?>" required <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><strong>X.509 Certificate <span style="color:red;">*</span>:</strong></td>
				<td><textarea rows="4" cols="5" name="mo_ga_sso_saml_x509_certificate" placeholder="Copy and Paste the content from the downloaded certificate or copy the content enclosed in 'X509Certificate' tag (has parent tag 'KeyDescriptor use=signing') in IdP-Metadata XML file" style="width: 95%;" <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>><?php echo $mo_ga_sso_saml_x509_certificate;?></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><b>NOTE:</b> Format of the certificate:<br/><b>-----BEGIN CERTIFICATE-----<br/>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br/>-----END CERTIFICATE-----</b></i><br/>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><strong>Response Signed:</strong></td>
				<td><input type="checkbox" name="mo_ga_sso_saml_response_signed" value="Yes" <?php echo $mo_ga_sso_saml_response_signed; ?> <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/>
				Check if your IdP is signing the SAML Response. Leave checked by default.</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><strong>Assertion Signed:</strong></td>
				<td><input type="checkbox" name="mo_ga_sso_saml_assertion_signed" value="Yes" <?php echo $mo_ga_sso_saml_assertion_signed; ?> <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/>
				Check if the IdP is signing the SAML Assertion. Leave unchecked by default.</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><br /><input type="submit" name="submit" style="width:100px;" value="Save" class="button button-primary button-large" <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?>/> &nbsp; 
				<input type="button" name="test" title="You can only test your Configuration after saving your Service Provider Settings." onclick="showTestWindow();" <?php if(!mo_ga_sso_is_sp_configured()) echo 'disabled'?> value="Test configuration" class="button button-primary button-large" style="margin-right: 3%;"/>
				</td>
			</tr>
		</table><br/>
		<input type="checkbox"  <?php if(!mo_ga_sso_is_sp_configured()) echo 'disabled title="Disabled. Configure your Service Provider"'?> onchange="window.location='<?php echo admin_url(); ?>/admin.php?page=mo_ga_sso_settings&tab=general'" />Check this option if you have Configured and Tested your Service Provider settings.	
		<br/><br/>
		</form>
	<?php
}