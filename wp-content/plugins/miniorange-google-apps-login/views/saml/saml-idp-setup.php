<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 15-02-2018
 * Time: 17:17
 */


echo '<div class="mo_registration_divided_layout">

        <div class="mo_gsuite_registration_table_layout">';

is_gsuite_customer_registered();

include_once( MOV_GSUITE_DIR . "views/saml/saml-cloud-broker.php" );

echo '        <h3><b>Step 1</b>:</h3>
            <h4>Link to Configure the Plug in:
                <a href="' . $idp_guides_link . '" target=\'_blank\'>Click Here to
                    see the Guide for Configuring the plugin</b></a></b>
                </h4>
                <h4>You will need the following
                    information to configure your IdP. Copy it and keep it handy:</h4>
                <table border="1" class="mo-idp-setup-table">

                    <tr>
                        <td style=""><b>SP-EntityID / Issuer</b></td>';

?>
<td>
	<?php echo $sp_issuer ?>
</td>
</tr>


<tr>
    <td><b>ACS (AssertionConsumerService) URL</b></td>
	<td><?php echo $acs_url; ?></td>
</tr>


<tr>
    <td><b>Audience URI</b></td>
	<td><?php echo $audience_uri?></td>
</tr>


<tr>
    <td><b>NameID format</b></td>
    <td><?php echo $name_id_format ?></td>
</tr>

<tr>
    <td><b>Recipient URL</b></td>
    <td><?php echo $recipient_url ?></td>
</tr>


<tr>
    <td><b>Destination URL</b></td>
    <td> <?php echo $destination_url ?></td>
</tr>


<?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
    <tr>
        <td><b>Default Relay State (Optional)</b></td>
		<?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && Mo_GSuite_Utility::micr() ) { ?>
            <td><?php echo site_url(); ?>
                ?option=readsamllogin&mId=<?php echo get_option( 'mo_gsuite_customer_validation_admin_customer_key' ) ?></td>
		<?php } else { ?>
            <td><?php echo site_url() . '/' ?></td>
		<?php } ?>
    </tr>
	<?php if ( get_option( 'mo_saml_enable_cloud_broker' ) != 'true' ) { ?>
        <tr>
            <td><b>Certificate (Optional)</b></td>
			<?php if ( ! Mo_GSuite_Utility::micr() ) { ?>
                <td>Download <i>(Register to download the
                        certificate)</i></td>
			<?php } else { ?>
                <td><a
                            href="<?php echo MOV_GSUITE_URL.'resources/saml-resources/miniorange_sp_cert.cer' ?>">Download</a>
                </td>
			<?php } ?>
        </tr>
	<?php } ?>
<?php } else { ?>
    <tr>
        <td><b>Default Relay State (Optional)</b></td>
        <td>Available in the <a
                    href="<?php echo admin_url( 'admin.php?page=gsuitepricing' ); ?>"><b>standard,
                    premium and enterprise</b></a> plans of the plugin.
        </td>
    </tr>
	<?php if ( get_option( 'mo_saml_enable_cloud_broker' ) != 'true' ) { ?>
        <tr>
            <td><b>Certificate (Optional)</b></td>
            <td>Available in the <a
                        href="<?php echo admin_url( 'admin.php?page=gsuitepricing' ); ?>"><b>standard,
                        premium and enterprise</b></a> plans of the plugin.
            </td>
        </tr>
	<?php } ?>
<?php } ?>
</table>


<?php
echo ' <p style="text-align: center;font-size: 13pt;font-weight: bold;">OR</p>
                <p>Provide this metadata URL to your Identity Provider or open it and save as .xml file and upload it in your idp:</p>
                
    <code><b><a target="_blank"
                href="'.$metadata_url.'">'.$metadata_url.'</a></b>
                
                </code>';


?>

</td>
</tr>

<!--STEP-2-->

<tr>
    <td colspan="2">
        <p>
        <h3><b>Step 2:</b></h3>
        With the help of information given in <b>Step 1</b>, configure your IdP. Then come back to <b>Step
            3</b>.
        <br/>
        <h4>If you are looking for an Identity Provider,you can try out <a
                    href="https://idp.miniorange.com"
                    target="_blank">miniOrange On-Premise
                IdP</a></h4>
        <a href="http://miniorange.com/miniorange_as_idp_wordpress" target='_blank'>Click Here to see
            the Guide
            for Configuring <b>miniOrange</b> as Cloud IdP.</a>
        </p>
        <div style="background-color:#CBCBCB;padding:5px;">We also have step by step <b>do-it-yourself
                guides</b> available for all known IdPs like <b>ADFS, Centrify, Okta, OneLogin, OpenAM,
                Oracle
                Identity Manager, JBoss Keycloak, Salesforce, Shibboleth, SimpleSAML, WSO2</b> etc. <a
                    href="<?php echo admin_url() . 'admin.php?page=gsuitepricing'; ?>">(
                Supported in standard, premium and enterprise versions of the plugin. )</a></div>
        <br>
        If you can't find your Idp in this list, write us the name of your Idp via <b>support form</b>
        on right.

        <p><b>For more help, checkout the <a
                        href="<?php  echo Mo_Gsuite_Constants::SAML_HELP_URL?>">Help
                    section</a>.</b></p>
    </td>
</tr>


<!--STEP-3-->


<tr>
    <td colspan="2">
        <h3><b>Step 3:</b></h3><h4> Assuming that you are now done with Step 2, please note down the
            following
            information from your IdP admin screen and keep it handy to configure your Service
            provider.</h4>
        <ol>
            <li><b>X.509 certificate</b></li>
            <li><b>SAML Login URL (Single Sign On URL)</b></li>
            <li><b>IdP Entity ID (IdP Issuer)</b></li>
            <li><b>Is Response signed</b> by your IdP?</li>
            <li><b>Is Assertion signed</b> by your IdP?</li>
            </li>
        </ol>
        <a href="#" id="idp_details_link">[ Cannot find the above information? ]</a>
        <div hidden id="idp_details_desc" class="mo_saml_help_desc">
            <ol>
                <li>X.509 certificate is enclosed in <code>X509Certificate</code> tag in IdP-Metadata
                    XML file.
                    (parent tag: <code>KeyDescriptor use="signing"</code>)
                </li>
                <li>SAML Login URL is enclosed in <code>SingleSignOnService</code> tag (Binding type:
                    HTTP-Redirect) in IdP-Metadata XML file.
                </li>
                <li>EntityID is the value of the <code>entityID</code> attribute of
                    <code>EntityDescriptor</code> tag in IdP-Metadata XML file.
                </li>
            </ol>
            Still Cannot find the above information?<br/> You can contact us using the support form on
            the right
            and we will help you.
        </div>
        <br/><br/>
        <input type="checkbox" <?php if ( ! Mo_GSuite_Utility::micr() )
			echo 'disabled' ?>
               onchange="window.location='<?php echo admin_url(); ?>admin.php?page=service_provider_saml'"/>
        Check this option if you have the above information. You will be redirected to configure the
        Service
        Provider.
    </td>
</tr>

</table>

</div>
</div>

<style>
    .mo-idp-setup-table {
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        padding: 0px 0px 0px 10px;
        margin: 2px;
        border-collapse: collapse;
        width: 98%;
    }

    .mo-idp-setup-table tr > td:first-child {
        width: 40%;
        padding: 15px;
    }

    .mo-idp-setup-table tr > td:last-child {
        width: 60%;
        padding: 15px;
    }
</style>

<script>
    jQuery("#idp_details_link").click(function (e) {
        e.preventDefault();
        jQuery("#idp_details_desc").slideToggle(400);
    });
</script>