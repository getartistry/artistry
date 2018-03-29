<?php function mo_ga_sso_show_pricing_page() { ?>
		<div class="mo_ga_sso_table_layout">

		<h2>Licensing Plans
		<span style="float:right;margin-right:15px;"><input type="button" name="ok_btn" class="button button-primary button-large" value="OK, Got It" onclick="window.location.href= 'admin.php?page=mo_ga_sso_settings'" /></span>
		</h2><hr>
		
		<table class="table mo_table-bordered mo_table-striped">
			<thead>
				<tr style="background-color:#93ca3a;">
					<th width="25%"><br><br><br><br><h3>Features \ Plans</h3></th>



					<th class="text-center" width="25%">
						<h3>Standard</h3>
						<p class="mo_plan-desc"></p>
						<h3>$249 per site - One Time Payment<br><br>

						<span>
							<input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now" onclick="upgradeform('wp_saml_sso_standard_plan')" />
						</span>

						</h3>
					</th>

				
					<th class="text-center" width="25%">
						<h3>Premium</h3>
						<p class="mo_plan-desc"></p>
						<h3>$449 per site - One Time Payment<br><br>
							<span>
								<input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now" onclick="upgradeform('wp_saml_sso_basic_plan')" />
							</span>
						</h3>
					</th>

					<th class="text-center" width="25%">
						<h3>Enterprise</h3>
							<p class="mo_plan-desc"></p><h3>$499 per site - One Time Payment<br><br>

							<span>
							  <a name="upgrade_btn" class="button button-default button-large" target="_blank" href="https://www.miniorange.com/contact">Contact Us</a>
							</span>
						</h3>
					</th>
	 

				</tr>
			</thead>

			<tbody class="mo_align-center mo-fa-icon">
				<tr>
					<td>Unlimited Authentications</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Basic Attribute Mapping (Username, Email, First Name, Last Name,Display Name)</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Widget,Shortcode to add IDP Login Link on your site</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>

				<tr>
					<td>Step-by-step guide to setup IDP</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Auto-Redirect to IDP from login page</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Protect your complete site (Auto-Redirect to IDP from any page)</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>

				<tr>
					<td>Change SP base Url and SP Entity ID</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Options to select SAML Request binding type</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>SAML Single Logout</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Integrated Windows Authentication (supported with AD FS)</td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Customized Role Mapping</td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Auto-sync IdP Configuration from metadata</td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>

				<tr>
					<td>Custom Attribute Mapping (Any attribute which is stored in user-meta table)</td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Store Multiple IdP Certificates</td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Custom SP Certificate</td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
				<td><i class="fa fa-check"></i></td>
				</tr>

				<tr>
					<td>Multi-Site Support *</td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Sub-site specific SSO for Multisite</td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Multiple IDP\'s Supported **</td>
					<td></td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Multi-Network SSO Support ***</td>
					<td></td>
					<td></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td><b>Add-Ons</b></td>
					<td>Purchase Separately</td>
					<td>Purchase Separately</td>
					<td>Included</td>
				</tr>
				
				<tr>
					<td>Buddypress Attribute Mapping Add-On</td>
					<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
					<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
				
				<tr>
					<td>Page Restriction Add-On</td>
					<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
					<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></i></td>
					<td><i class="fa fa-check"></i></td>
				</tr>
		</table>

		<?php echo '<form style="display:none;" id="licenseform" action="'.get_option('mo_ga_sso_host_name').'/moas/login" 
				target 	=	"_blank" 
				method 	= 	"post">

				<input type="email" name="username" value="'.get_option('mo_ga_sso_admin_email').'" />

				<input type="text" name="redirectUrl" value="'.get_option('mo_ga_sso_host_name').'/moas/initializepayment" /><input type="text" name="requestOrigin" id="requestOrigin2"  />
		
		</form>
		
		<form name="f" method="post" action="" id="mo_ga_sso_check_license">
			<input type="hidden" name="option" value="mo_ga_sso_check_license"/>
		</form>
		<script>
			function upgradeform(planType){
				jQuery("#requestOrigin2").val(planType);
				jQuery("#licenseform").submit();
			}
			
			function confirmlicenseform() {
				jQuery("#mo_ga_sso_check_license").submit();
			}
		</script>';?>
		
				<br>
					<h3>Steps to Upgrade to Premium Plugin -</h3>
						<p>1. You will be redirected to miniOrange Login Console. Enter your password with which you created an account with us. After that you will be redirected to payment page.</p>
						<p>2. Enter you card details and complete the payment. On successful payment completion, you will see the link to download the premium plugin.</p>
						<p>3. When you download the premium plugin, just unzip it and replace the folder with existing plugin. Do not delete and upload again from wordpress admin panel as your already saved settings will get lost.
						<br><br>
							<b>Note: If you are downloading the Multi-Site Plugin, then first delete existing plugin and then re-install the Multi-Site plugin.</b>
						</p>

						<p>4. From this point on, do not update the premium plugin from the Wordpress store.</p>

					<h3>* Multi-Site Support - </h3>
					
						<p>This feature has a separate licensing and plugin. Please select the Multisite option on the payment page while upgrading.</p>

					<h3>** Multiple IDP's Supported and Multi-Network SSO Support- </h3>
						<p>This feature has separate licensing. Contact us at <b>info@miniorange.com</b> to get quote for this.</p>

					<!--<h3>*** End to End Identity Provider Integration - </h3>
					<p>We will setup a Conference Call / Gotomeeting and do end to end configuration for you for IDP as well as plugin. We provide services to do the configuration on your behalf. </p> -->
					
					<h3>10 Days Return Policy -</h3>
						At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved. We will refund the whole amount within 10 days of the purchase. Please email us at info@miniorange.com for any queries regarding the return policy.

						Please email us at <b>info@miniorange.com</b> for any queries regarding the return policy.
					
					<h2>Licensing Plans (Cloud Service)</h2><hr>
						<p>If you want to use miniOrange Cloud Single Sign on service. <a style="cursor:pointer;" id="help_working_title1" >Click Here</a> to know how the plugin works for this case. Contact us at <b>info@miniorange.com</b> to get its licensing plans info.
						</p>


		<div hidden id="help_working_desc1" class="mo_saml_help_desc">
								<h3>Using miniOrange Single Sign On service:</h3>
								<div style="display:block;text-align:center;">
								<img src="<?php echo plugin_dir_url(__FILE__) . '../images/saml_working.png'?>" alt="Working of miniOrange SAML plugin" style="width: 85%;"/>
								</div>
				<ol>
									<li>miniOrange SAML SSO plugin sends a login request to miniOrange SSO Service.</li>
									<li>miniOrange SSO Service creates a SAML Request and redirects the user to your Identity Provider for authentication.</li>
									<li>Upon successful authentication, your Identity Provider sends a SAML Response back to miniOrange SSO Service.</li>
									<li>miniOrange SSO Service verifies the SAML Response and sends a response status (along with the logged in user's information) back to miniOrange SAML SSO plugin. Plugin then reads the response and logins the user.</li>
				</ol>

				<div>
									<b>Advantages:</b>
									<ol>
										<li>If you are an enterprise or business user then on using this service you will be able to take full advantage of all of miniOrange SSO features. ( For a complete list of these features <a href="http://miniorange.com/single-sign-on-sso" target="_blank">Click Here</a>)</li>
										<li>You can use Non-SAML Identity Providers for Single Sign On.</li>
										<li>If you have multiple websites then you can use the same IdP configuration for all of them. You don't have to make seperate configurations in your IdP.</li>
										<li>Some Identity Providers like ADFS do not support HTTP endpoints ( i.e. your wordpress site needs to be on HTTPS ). So, if your wordpress site is not on HTTPS then you can use this service for such IdPs.</li>
									</ol>
			</div>
				
			</div>
		If you have any doubts regarding the licensing plans, you can mail us at <a href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a> or submit a query using the <b>support form</b> on right.
		<br>
		<br>
		</div> 
<?php 
}
?>