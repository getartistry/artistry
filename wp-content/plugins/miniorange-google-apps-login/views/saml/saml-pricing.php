<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 19-02-2018
 * Time: 11:55
 */

function mo_saml_show_pricing_page() { ?>

	<div class ="mo_registration_divided_layout" style="width: 95%">
	<div class="mo_gsuite_registration_table_layout" >

		<h2>Licensing Plans
			<span style="float:right;margin-right:15px;"><input type="button" name="ok_btn"
			                                                    class="button button-primary button-large"
			                                                    value="OK, Got It"
			                                                    onclick="window.location.href='admin.php?page=mogalsettings'"/></span>
		</h2>
		<hr>

		<h3>
			What is a MultiSite?
		</h3>
		<p>WordPress Multisite is a feature which allows users to create a “Network” of subsites within a single install of WordPress. This allows you to use subdomains (or subdirectories) of the same root domain (site1.domain.com as a subsite of domain.com for example), within the same Network.
			<b style="font-size: 16px">Currently you have Multisite <?php echo Multisite_enabled()?> on this instance.</b><p>

		<h3>
			What is Multiple IDP?
		</h3>
		<p>
			miniOrange SAML plugin provides support for more than one Identity Providers simultaneously.Choose Enterprise version if you have multiple Identity Providers.
		</p>
		<table class="table mo_table-bordered mo_table-striped">

			<thead>
			<tr style="background-color:#93ca3a;">
				<th width="25%"><br><br><br><br>
					<h3>Features \ Plans</h3></th>
				<th class="text-center" width="25%"><h3>Standard <br><br><br></h3><p class="mo_plan-desc"></p><h3><b class="tooltip">$249 - One Time Payment *<span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>

                <input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now"
                       onclick="upgradeform('wp_saml_sso_standard_plan')"/>
                </span></h3></th>
				<th class="text-center" width="25%"><h3>Premium <br><br><br></h3><p class="mo_plan-desc"></p><h3><b class="tooltip">$449 - One Time Payment *<span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>

                <input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now"
                       onclick="upgradeform('wp_saml_sso_basic_plan')"/>


                </span></h3></th>

				<th class="text-center" width="25%"><h3>Enterprise <br></h3><p>(Multiple IDP and MultiNetwork Support)</p><p class="mo_plan-desc"></p><h3><b class="tooltip">$499 - One Time Payment *<span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>
      <a name="upgrade_btn" class="button button-default button-large" target="_blank"
         href="https://www.miniorange.com/contact">Contact Us</a></span></h3></th>

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
				<td>Multi-Site Support **</td>
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
				<td>Multiple IDP's Supported ***</td>
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
				<td>Purchase Separately</td>
			</tr>
			<tr>
				<td>Buddypress Attribute Mapping Add-On</td>
				<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
				<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
				<td><i class="fa fa-check"></i></td>
			</tr>
			<tr>
				<td>Page Restriction Add-On</td>
				<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
				<td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
				<td><i class="fa fa-check"></i></td>

		</table>

		<form style="display:none;" id="loginform"
		      action="<?php echo get_option( 'host_name' ) . '/moas/login'; ?>"
		      target="_blank" method="post">
			<input type="email" name="username" value="<?php echo get_option( 'mo_gsuite_customer_validation_admin_email' ); ?>"/>
			<input type="text" name="redirectUrl"
			       value="<?php echo get_option( 'host_name' ) . '/moas/initializepayment'; ?>"/>
			<input type="text" name="requestOrigin" id="requestOrigin"/>
		</form>
		<script>
            function upgradeform(planType) {

                jQuery('#requestOrigin').val(planType);
                jQuery('#loginform').submit();
            }
		</script>
		<br>
		<h3>Steps to Upgrade to Premium Plugin -</h3>
		<p>1. You will be redirected to miniOrange Login Console. Enter your password with which you created an account
			with us. After that you will be redirected to payment page.</p>
		<p>2. Enter you card details and complete the payment. On successful payment completion, you will see the link
			to download the premium plugin.</p>
		<p>3. When you download the premium plugin, just unzip it and replace the folder with existing plugin. Do not
			delete and upload again from wordpress admin panel as your already saved settings will get lost.<br><br>
			<b>Note: If you are downloading the Multi-Site Plugin, then first delete existing plugin and then re-install
				the Multi-Site plugin.</b></p>
		<p>4. From this point on, do not update the premium plugin from the Wordpress store.</p>

		<h3>* Cost applicable for one instance only.</h3>
		<p>You can Upgrade the number of instances by clicking on Upgrade now.</p>
		<h3>** Multi-Site Support - </h3>
		<p>This feature has a separate premium plugin and licensing is also based on number of subsites. Please select the Multisite option on the payment page while upgrading.</p>

		<h3>*** Multiple IDP\'s Supported - </h3>
		<p>This feature has a separate premium plugin and licensing is also based on number of IDPs. Contact us at <b>info@miniorange.com</b> to get quote for these.</p>

		<h3>*** Multi-Network Supported - </h3>
		<p>This feature has a separate premium plugin and licensing is also based on number of networks and subsites. Contact us at <b>info@miniorange.com</b> to get quote for these.</p>

		<!--<h3>*** End to End Identity Provider Integration - </h3>
		<p>We will setup a Conference Call / Gotomeeting and do end to end configuration for you for IDP as well as plugin. We provide services to do the configuration on your behalf. </p> -->
		<h3>10 Days Return Policy -</h3>
		At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is
		not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get
		resolved. We will refund the whole amount within 10 days of the purchase. Please email us at info@miniorange.com
		for any queries regarding the return policy.

		Please email us at <b>info@miniorange.com</b> for any queries regarding the return policy.
		<h2>Licensing Plans (Cloud Service)</h2>
		<hr>
		<p>If you want to use miniOrange Cloud Single Sign on service. <a style="cursor:pointer;"
		                                                                  id="help_working_title1">Click Here</a> to
			know how the plugin works for this case. Contact us at <b>info@miniorange.com</b> to get its licensing plans
			info.</p>
		<br>

		<div hidden id="help_working_desc1" class="mo_saml_help_desc">
			<h3>Using miniOrange Single Sign On service:</h3>
			<div style="display:block;text-align:center;">
				<img src="<?php echo MOV_GSUITE_URL.'includes/images/SAML/saml_working.png'; ?>"
				     alt="Working of miniOrange SAML plugin" style="width: 85%;"/>
			</div>
			<ol>
				<li>miniOrange SAML SSO plugin sends a login request to miniOrange SSO Service.</li>
				<li>miniOrange SSO Service creates a SAML Request and redirects the user to your Identity Provider for
					authentication.
				</li>
				<li>Upon successful authentication, your Identity Provider sends a SAML Response back to miniOrange SSO
					Service.
				</li>
				<li>miniOrange SSO Service verifies the SAML Response and sends a response status (along with the logged
					in user's information) back to miniOrange SAML SSO plugin. Plugin then reads the response and logins
					the user.
				</li>
			</ol>
			<div>
				<b>Advantages:</b>
				<ol>
					<li>If you are an enterprise or business user then on using this service you will be able to take
						full advantage of all of miniOrange SSO features. ( For a complete list of these features <a
							href="http://miniorange.com/single-sign-on-sso" target="_blank">Click Here</a>)
					</li>
					<li>You can use Non-SAML Identity Providers for Single Sign On.</li>
					<li>If you have multiple websites then you can use the same IdP configuration for all of them. You
						don't have to make seperate configurations in your IdP.
					</li>
					<li>Some Identity Providers like ADFS do not support HTTP endpoints ( i.e. your wordpress site needs
						to be on HTTPS ). So, if your wordpress site is not on HTTPS then you can use this service for
						such IdPs.
					</li>
				</ol>
			</div>
		</div>
		If you have any doubts regarding the licensing plans, you can mail us at <a
			href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a> or submit a query using the <b>support
			form</b> on right.
		<br>

		<br>

	</div>
	</div>
    <script>
    jQuery("#help_working_title1").click(function () {
        jQuery("#help_working_desc2").hide();
        jQuery("#help_working_desc3").hide();
        jQuery("#help_working_desc1").slideToggle(400);
    });
    </script>
    <style>
        .mo_saml_local_pricing_free_tab{
            background-color: rgba(34, 153, 221, 0.82)!important;
        }
        .mo_saml_local_pricing_paid_tab{
            background-color: #1a71a4 !important;
        }
        .mo_saml_local_pricing_text{
            font-size: 14px !important;
            color: #fff !important;
            font-weight: 600 !important;

        }
        .mo_saml_local_pricing_sub_header{
            margin: 2px !important;
            color: #fff !important;
        }
        .mo_saml_local_pricing_header{
            color: #fff !important;
            margin: 4px !important;
        }
        .mo_saml_local_pricing_table{
            text-align:center;
            font-size: 15px !important;
            background-color:#FFFFFF;
        }

        .mo_saml_premium_thumbnail{
            width: 350px;
            padding: 4px;
            margin-bottom: 10px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: border .2s ease-in-out;
            -o-transition: border .2s ease-in-out;
            transition: border .2s ease-in-out;
        }

        .mo-fa-icon>tr>td>i.fa{
            color:#5b8a0f;

        }

        .mo_align-center>tr>td{
            text-align:center !important;
        }

        .mo_table-bordered, .mo_table-bordered>tbody>tr>td{
            border: 1px solid #ddd;
        }

        .mo_table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .mo_table-bordered>thead>tr>th{
            vertical-align:top !important;
        }

        .mo_plan-desc{
            font-size:14px !important;
        }
        .mo-display-logs{
            color: #3c763d;
            background-color: #dff0d8;
            padding:2%;
            margin-bottom:20px;
            text-align:center;
            border:1px solid #AEDB9A;
            font-size:18pt;
        }
        .mo-display-block {
            color: #050505;
            width: -moz-available;
            min-height: 300px !important;
            overflow: auto;
            display: inline-block;
            background-color: #f6f6f6;
            padding: 2%;
            margin-bottom: 20px;
            text-align: left;
            border: 1px solid #AEDB9A;
            font-size: 12pt;
        }
        .mo_divider{
            width:5px;
            height:auto;
            display:inline-block;
        }
    </style>
<?php }
mo_saml_show_pricing_page();
echo '';
?>