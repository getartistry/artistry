<?php function mo_ga_sso_show_new_registration_page() {
	update_option ( 'mo_ga_sso_new_registration', 'true' );
	$user = wp_get_current_user();
	?>
			<!--Register with miniOrange-->
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_ga_sso_register_customer" />
			<div class="mo_ga_sso_table_layout">
				<div id="toggle1" class="panel_toggle">
					<h3>Register with miniOrange</h3>
				</div>
				<div id="panel1">
					<p><a href="#" id="help_register_link">[ Why should I register? ]</a></p>
					<div hidden id="help_register_desc" class="mo_ga_sso_help_desc">
						You should register so that in case you need help, we can help you with step by step instructions. We support all known IdPs - <b>ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2 etc</b>.
					</div>
					</p>
					<table class="mo_ga_sso_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_ga_sso_table_textbox" type="email" name="email"
								required placeholder="person@example.com"
								value="<?php echo (get_option('mo_ga_sso_admin_email') == '') ? get_option('admin_email') : get_option('mo_ga_sso_admin_email');?>" /></td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font>Company/Organisation:</b></td>
							<td><input class="mo_ga_sso_table_textbox" type="text" name="company"
								required placeholder="Your company name"
								value="<?php echo (get_option('mo_ga_sso_admin_company') == '') ? site_url() : get_option('mo_ga_sso_admin_company');?>" /></td>
						</tr>
						<tr>
							<td><b>First Name:</b></td>
							<td><input class="mo_ga_sso_table_textbox" type="text" name="first_name"
								placeholder="First Name"
								value="<?php echo (get_option('mo_ga_sso_admin_first_name') == '') ? $user->first_name : get_option('mo_ga_sso_admin_first_name');?>" /></td>
						</tr>
						<tr>
							<td><b>Last Name:</b></td>
							<td><input class="mo_ga_sso_table_textbox" type="text" name="last_name"
								placeholder="Last Name"
								value="<?php echo (get_option('mo_ga_sso_admin_last_name') == '') ? $user->last_name : get_option('mo_ga_sso_admin_last_name');?>" /></td>
						</tr>
						<tr>
							<td><b>Phone number:</b></td>
							<td><input class="mo_ga_sso_table_textbox" type="tel" id="phone_contact" style="width:80%;"
								pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" class="mo_ga_sso_table_textbox" name="phone" 
								title="Phone with country code eg. +1xxxxxxxxxx"
								placeholder="Phone with country code eg. +1xxxxxxxxxx"
								value="<?php echo get_option('mo_ga_sso_admin_phone');?>" /></td>
						</tr>
							<tr>
								<td></td>
								<td>We will call only if you need support.</td>
							</tr> 
						<tr>
							<td><b><font color="#FF0000">*</font>Password:</b></td>
							<td><input class="mo_ga_sso_table_textbox" required type="password"
								name="password" placeholder="Choose your password (Min. length 6)" /></td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
							<td><input class="mo_ga_sso_table_textbox" required type="password"
								name="confirmPassword" placeholder="Confirm your password" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><br><input type="submit" name="submit" value="Register"
								class="button button-primary button-large" /></td>
						</tr>
					</table>
				</div>
			</div>
		</form>
		<?php
}
function mo_ga_sso_show_verify_password_page() {
	?>
			<!--Verify password with miniOrange-->
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_ga_sso_verify_customer" />
			<div class="mo_ga_sso_table_layout">
				<div id="toggle1" class="panel_toggle">
					<h3>Login with miniOrange</h3>
				</div>
				<div id="panel1">
					<p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password.<br/> <a href="#mo_ga_sso_forgot_password_link">Click here if you forgot your password?</a></b></p>
					<br/>
					<table class="mo_ga_sso_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_ga_sso_table_textbox" type="email" name="email"
								required placeholder="person@example.com"
								value="<?php echo get_option('mo_ga_sso_admin_email');?>" /></td>
						</tr>
						<tr>
						<td><b><font color="#FF0000">*</font>Password:</b></td>
						<td><input class="mo_ga_sso_table_textbox" required type="password"
							name="password" placeholder="Choose your password" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
							<input type="submit" name="submit"
								class="button button-primary button-large" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" name="mo_ga_sso_goback" id="mo_ga_sso_goback" value="Back" class="button button-primary button-large" />
						</tr>
					</table>
				</div>
			</div>
		</form>
		<form name="f" method="post" action="" id="mo_ga_sso_goback_form">
				<input type="hidden" name="option" value="mo_ga_sso_go_back"/>
		</form>
		<form name="f" method="post" action="" id="mo_ga_sso_forgotpassword_form">
				<input type="hidden" name="option" value="mo_ga_sso_forgot_password_form_option"/>
		</form>
		<script>
			jQuery('#mo_ga_sso_goback').click(function(){
				jQuery('#mo_ga_sso_goback_form').submit();
			});
			jQuery("a[href=\"#mo_ga_sso_forgot_password_link\"]").click(function(){
				jQuery('#mo_ga_sso_forgotpassword_form').submit();
			});
		</script>
		<?php
}

function mo_ga_sso_show_otp_verification(){
	?>
		<!-- Enter otp -->
		<form name="f" method="post" id="otp_form" action="">
			<input type="hidden" name="option" value="mo_ga_sso_validate_otp" />
			<div class="mo_ga_sso_table_layout">
				<table class="mo_ga_sso_settings_table">
					<h3>Verify Your Email</h3>
					<tr>
						<td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
						<td colspan="2"><input class="mo_ga_sso_table_textbox" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:61%;" />
						 &nbsp;&nbsp;<a style="cursor:pointer;" onclick="document.getElementById('resend_otp_form').submit();">Resend OTP</a></td>
					</tr>
					<tr><td colspan="3"></td></tr>
					<tr>

						<td>&nbsp;</td>
						<td style="width:17%">
						<input type="submit" name="submit" value="Validate OTP" class="button button-primary button-large" /></td>

		</form>
		<form name="f" method="post">
						<td style="width:18%">
							<input type="hidden" name="option" value="mo_ga_sso_go_back"/>
							<input type="submit" name="submit"  value="Back" class="button button-primary button-large" />
						</td>
		</form>
		<form name="f" id="resend_otp_form" method="post" action="">
						<td>
			<?php if(get_option('mo_ga_sso_registration_status') == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option('mo_ga_sso_registration_status') == 'MO_OTP_VALIDATION_FAILURE_EMAIL') { ?>
				<input type="hidden" name="option" value="mo_ga_sso_resend_otp_email"/>
			<?php } else { ?>
				<input type="hidden" name="option" value="mo_ga_sso_resend_otp_phone"/>
			<?php } ?>
						</td>

		</form>
		</tr>
			</table>
		<?php if(get_option('mo_ga_sso_registration_status') == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option('mo_ga_sso_registration_status') == 'MO_OTP_VALIDATION_FAILURE_EMAIL') { ?>
			<hr>

				<h3>I did not recieve any email with OTP . What should I do ?</h3>
				<form id="mo_ga_sso_register_with_phone_form" method="post" action="">
					<input type="hidden" name="option" value="mo_ga_sso_register_with_phone_option" />
					 If you can't see the email from miniOrange in your mails, please check your <b>SPAM</b> folder. If you don't see an email even in the SPAM folder, verify your identity with our alternate method.
					 <br><br>
						<b>Enter your valid phone number here and verify your identity using one time passcode sent to your phone.</b><br><br>
						<input class="mo_ga_sso_table_textbox" type="tel" id="phone_contact" style="width:40%;"
								pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" class="mo_ga_sso_table_textbox" name="phone" 
								title="Phone with country code eg. +1xxxxxxxxxx" required
								placeholder="Phone with country code eg. +1xxxxxxxxxx"
								value="<?php echo get_option('mo_ga_sso_admin_phone');?>" />
						<br /><br /><input type="submit" value="Send OTP" class="button button-primary button-large" />
				
				</form>
		<?php } ?>
	</div>
	
<?php
}
?>