<?php
include 'mo-ga-sso-license-page.php';
include 'mo-ga-sso-faqs-page.php';
include 'mo-ga-sso-support-page.php';
include 'mo-ga-sso-idp-setup-page.php';
include 'mo-ga-sso-registration-page.php';
include 'mo-ga-sso-attr-role-mapping-page.php';
function mo_register_ga_sso() {
	if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
	} else if(mo_ga_login_is_customer_registered() && mo_ga_sso_is_sp_configured()) {
		$active_tab = 'general';
	} else if(mo_ga_login_is_customer_registered()) {
		$active_tab = 'config';
	} else {
		$active_tab = 'login';
	}
	?>
	<?php
		if(!mo_ga_sso_is_curl_installed()) {
			?>
			<p><font color="#FF0000">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled)</font></p>
			<?php
		}
		
		if(!mo_ga_sso_is_openssl_installed()) {
			?>
			<p><font color="#FF0000">(Warning: <a href="http://php.net/manual/en/openssl.installation.php" target="_blank">PHP openssl extension</a> is not installed or disabled)</font></p>
			<?php
		}
		
		if(!mo_ga_sso_is_mcrypt_installed()) {
			?>
				<p><font color="#FF0000">(Warning: <a href="http://php.net/manual/en/mcrypt.setup.php" target="_blank">PHP Mcrypt extension</a> is not installed or disabled. You will not be able to login.)</font></p>
			<?php
		}
	?>
<div id="mo_ga_sso_settings">
	<div class="miniorange_container">
	<table style="width:100%;">
		<tr>
			<h2 class="nav-tab-wrapper">
				<?php if(!mo_ga_login_is_customer_registered()) {?>
				<a class="nav-tab <?php echo $active_tab == 'login' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Account Setup</a>
				<?php }?>
				<a class="nav-tab <?php echo $active_tab == 'config' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'config'), $_SERVER['REQUEST_URI'] ); ?>">How to Setup SP in Google Apps</a>
				<a class="nav-tab <?php echo $active_tab == 'save' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] ); ?>">IDP Setup</a>
				<?php if(mo_ga_login_is_customer_registered()) {?>
				<a class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'general'), $_SERVER['REQUEST_URI'] ); ?>">SSO Login Settings</a>
				<?php }?>
				<a class="nav-tab <?php echo $active_tab == 'opt' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'opt'), $_SERVER['REQUEST_URI'] ); ?>">Attribute/Role Mapping</a>
				<a class="nav-tab <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'help'), $_SERVER['REQUEST_URI'] ); ?>">Help & FAQ</a>
				<a class="nav-tab <?php echo $active_tab == 'licensing' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'licensing'), $_SERVER['REQUEST_URI'] ); ?>">Licensing Plans</a>
			
			</h2>
			<td style="vertical-align:top;width:65%;">
			<?php
				if($active_tab == 'save') {
					mo_ga_sso_saml_setup();
				} else if($active_tab == 'opt') {
					mo_ga_sso_attr_role_mapping();
				} else if($active_tab == 'help') {
					mo_ga_sso_show_faqs();
				} else if($active_tab == 'config'){
					mo_ga_sso_configuration_steps();
				} else if($active_tab == 'general'){
					mo_ga_sso_login_settings();
				} else if($active_tab == 'licensing'){
					mo_ga_sso_show_pricing_page();
					'<style>#support-form{ display:none;}</style>';
				}else {
					if (get_option ( 'mo_ga_sso_verify_customer' ) == 'true') {
						mo_ga_sso_show_verify_password_page();
					} else if (trim ( get_option ( 'mo_ga_sso_admin_email' ) ) != '' && trim ( get_option ( 'mo_ga_sso_admin_api_key' ) ) == '' && get_option ( 'mo_ga_sso_new_registration' ) != 'true') {
						mo_ga_sso_show_verify_password_page();
					}else if(get_option('mo_ga_sso_registration_status') == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option('mo_ga_sso_registration_status') == 'MO_OTP_DELIVERED_SUCCESS_PHONE' || get_option('mo_ga_sso_registration_status') == 'MO_OTP_VALIDATION_FAILURE_EMAIL' || get_option('mo_ga_sso_registration_status') == 'MO_OTP_VALIDATION_FAILURE_PHONE' || get_option('mo_ga_sso_registration_status') == 'MO_OTP_DELIVERED_FAILURE' ){
						mo_ga_sso_show_otp_verification();
					}	else if (! mo_ga_login_is_customer_registered()) {
						delete_option ( 'password_mismatch' );
						mo_ga_sso_show_new_registration_page();
					} else {
						mo_ga_sso_login_settings();
					}
				}
			?>
			</td>
			<td style="vertical-align:top;padding-left:1%;" id= "support-form">
				<?php echo mo_ga_sso_support_form(); ?>	
			</td>
		</tr>
	</table>
	</div>
			
<?php			
}

function mo_ga_sso_is_curl_installed() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return 1;
    } else 
        return 0;
}
function mo_ga_sso_is_openssl_installed() {
	if  (in_array  ('openssl', get_loaded_extensions())) {
		return 1;
	} else
		return 0;
}
function mo_ga_sso_is_mcrypt_installed() {
	if  (in_array  ('mcrypt', get_loaded_extensions())) {
		return 1;
	} else
		return 0;
}


function mo_ga_sso_configuration_steps() {
	?>
	<!-- <form  name="mo_ga_sso_saml_form_am" method="post" action="" id="mo_ga_sso_idp_config">-->
		<input type="hidden" name="option" value="mo_ga_sso_idp_config" />
		<div id="instructions_idp"></div>
		<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:2%;">
		<tr>
			<?php if(!mo_ga_login_is_customer_registered()) { ?>
				<td colspan="2"><div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Register or Login with miniOrange</a> to configure the miniOrange Google Apps Login Plugin.</div></td>
			<?php } ?>
		</tr>
		
		<tr>
			<td colspan="2">
			 
				<h3><b>Step 1:</b></h3><h4>You will need the following information to configure a Service Provider in Google Apps Domain. Copy it and keep it handy:</h4>
				<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px; margin:2px; border-collapse: collapse; width:98%">
					<tr>
						<td style="width:40%; padding: 15px;"><b>SP-EntityID / Issuer</b></td>
						<td style="width:60%; padding: 15px;"><?php echo plugins_url().'/miniorange-google-apps-login/'?></td>
					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>ACS (AssertionConsumerService) URL</b></td>
						<td style="width:60%;  padding: 15px;"><?php echo site_url().'/'?></td>
					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>Audience URI</b></td>
						<td style="width:60%; padding: 15px;"><?php echo plugins_url().'/miniorange-google-apps-login/'?></td>
					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>NameID format</b></td>
						<td style="width:60%; padding: 15px;">urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</td>
					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>Recipient URL</b></td>
						<td style="width:60%;  padding: 15px;"><?php echo site_url().'/'?></td>
					</tr>
					<tr>
						<td style="width:40%; padding: 15px;"><b>Destination URL</b></td>
						<td style="width:60%;  padding: 15px;"><?php echo site_url().'/'?></td>
					</tr>
					
						<tr>
							<td style="width:40%; padding: 15px;"><b>Default Relay State (Optional)</b></td>
							<td style="width:60%;  padding: 15px;">Available in the <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>premium<b></b> standard and <b>enterprise </b></a> version</td>
						</tr>
						
						<tr>
							<td style="width:40%; padding: 15px;"><b>Certificate (Optional)</b></td>
							<td style="width:60%;  padding: 15px;">Available in the <a href="<?php echo admin_url('admin.php?page=mo_ga_sso_settings&tab=licensing');?>"><b>premium</b><b> standard and </b><b>enterprise</b></a> version</td>
						</tr>
					
				</table>
				
				</td>
				</tr>

				<tr>
					<td colspan="2">
						<p><h3><b>Step 2:</b></h3>
							With the help of information given in <b>Step 1</b>, configure Google Apps. Then come back to <b>Step 3</b>. 
							<br/><br/>
							<a href="<?php echo plugins_url().'/miniorange-google-apps-login/resources/guide/googleapps_as_idp.pdf'; ?>" target='_blank'>Click Here to see the Guide for Configuring <b>Google Apps</b> as IdP.</a>
						</p>
						
					</td>
				</tr>
		
				<tr>
				<td colspan="2">
				<h3><b>Step 3:</b></h3><h4> Assuming that you are now done with Step 2, please note down the following information from your IdP admin screen and keep it handy to configure your Service provider.</h4>
				<ol>
					<li><b>X.509 certificate</b></li>
					<li><b>SAML Login URL (Single Sign On URL)</b></li>
					<li><b>IdP Entity ID (IdP Issuer)</b></li>
					<li><b>Is Response signed</b> by your IdP?</li>
					<li><b>Is Assertion signed</b> by your IdP?</li>
					</li>
				</ol>
				<a href="#" id="idp_details_link" >[ Cannot find the above information? ]</a>
				<div hidden id="idp_details_desc" class="mo_ga_sso_help_desc">
				  <ol><li>X.509 certificate is enclosed in <code>X509Certificate</code> tag in IdP-Metadata XML file. (parent tag: <code>KeyDescriptor use="signing"</code>)</li> 
				  <li>SAML Login URL is enclosed in <code>SingleSignOnService</code> tag (Binding type: HTTP-Redirect) in IdP-Metadata XML file.</li>
				  <li>EntityID is the value of the <code>entityID</code> attribute of <code>EntityDescriptor</code> tag in IdP-Metadata XML file. </li></ol>
					Still Cannot find the above information?<br/> You can contact us using the support form on the right and we will help you.
				</div><br/><br/>
				<input type="checkbox"  <?php if(!mo_ga_login_is_customer_registered()) echo 'disabled'?> onchange="window.location='<?php echo admin_url(); ?>/admin.php?page=mo_ga_sso_settings&tab=save'" /> Check this option if you have the above information. You will be redirected to configure the Service Provider. 
			</td>
			</tr>
		</table>
	<?php
}

function mo_ga_sso_get_test_url(){
	
	$url = site_url(). '/?option=testConfig';
	return $url;
}

function mo_ga_login_is_customer_registered() {
			$email 			= get_option('mo_ga_sso_admin_email');
			$customerKey 	= get_option('mo_ga_sso_admin_customer_key');
			if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
				return 0;
			} else {
				return 1;
			}
}

function mo_ga_sso_is_sp_configured() {
	$mo_ga_sso_saml_login_url = get_option('mo_ga_sso_saml_login_url');
	if( !empty($mo_ga_sso_saml_login_url)) {
		return 1;
	} else {
		return 0;
	}
}




?>
