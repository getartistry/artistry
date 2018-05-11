<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 15-02-2018
 * Time: 17:16
 */

	?>
<script>
    function showTestWindow() {
        var myWindow = window.open("<?php echo mo_saml_get_test_url();?>", "TEST SAML IDP", "scrollbars=1 width=800, height=600");
    }

    function showSAMLRequest() {
        var myWindow = window.open("<?php echo mo_saml_get_saml_request_url();?>", "VIEW SAML REQUEST", "scrollbars=1 width=800, height=600");
    }

    function showSAMLResponse() {
        var myWindow = window.open("<?php echo mo_saml_get_saml_response_url();?>", "VIEW SAML RESPONSE", "scrollbars=1 width=800, height=600");
    }
</script>
<?php
echo '<div class="mo_registration_divided_layout">

	<form width="98%" border="0"
	      style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;" name="saml_form"
	      method="post" action="">';
		is_gsuite_customer_registered();

echo '		<input type="hidden" name="option" value="login_widget_saml_save_settings"/>
		<table style="width:100%;">
			<tr>
				<td colspan="2">
					<h3>Configure Service Provider
						<span style="float:right;margin-right:30px;">

                            <a
								href= "'.$upload_metadata_url.'" >
                                <input  type="button" class="button button-primary button-large"
									value="Upload IDP Metadata"
								/></a>&nbsp &nbsp
                </span></h3>
			</tr>
			<tr>
				<td colspan="4">
					<hr>
				</td>
			</tr>';
?>
			<?php if ( ! Mo_GSuite_Utility::micr() ) { ?>
				<tr>
					<td colspan="2">
						<!--<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
							Please <a
								href="<?php /*echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); */?>">Register
								or Login with miniOrange</a> to configure the miniOrange SAML Plugin.
						</div>-->
					</td>
				</tr>
			<?php } ?>
			<?php if ( ! $idp_config && Mo_GSuite_Utility::micr() ) { ?>
				<!--<tr>
                    <td colspan="2"><div style="display:block;color:red;background-color:rgba(251, 251, 0, 0.43);padding:5px;border:solid 1px yellow;">You skipped a step. Please complete your Identity Provider configuration before you can enter the fields given below. If you have already completed your IdP configuration, please confirm on <a href="<?php //echo add_query_arg( array('tab' => 'config'), $_SERVER['REQUEST_URI'] ); ?>">Configure Identity Provider</a> page to remove this warning.</div></td>
                </tr>-->
			<?php } ?>
			<tr>
				<td colspan="2">Enter the information gathered from your Identity Provider<br/><br/></td>
			</tr>
			<?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ) { ?>
				<tr>
					<td colspan="2"><b>Note</b> : On miniOrange IdP, Go to <code>Apps</code> -> <code>Manage
							Apps</code> and click on <code> metadata</code> link given beside your WordPress SAML
						App to download IdP-Metadata XML file
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="width:200px;"><strong>Identity Provider Name <span style="color:red;">*</span>:</strong>
				</td>
				<td><input type="text" name="saml_identity_name"
				           placeholder="Identity Provider name like ADFS, SimpleSAML, Salesforce"
				           style="width: 95%;" value="<?php echo $saml_identity_name; ?>"
				           required <?php if ( ! Mo_GSuite_Utility::micr() )
						echo 'disabled' ?> title="Only alphabets, numbers and underscore is allowed"/></td>
			</tr>

			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><strong>IdP Entity ID or Issuer <span style="color:red;">*</span>:</strong></td>
				<td><input type="text" name="saml_issuer" placeholder="Identity Provider Entity ID or Issuer"
				           style="width: 95%;" value="<?php echo $saml_issuer; ?>"
				           required <?php if ( ! Mo_GSuite_Utility::micr() )
						echo 'disabled' ?>/></td>
			</tr>
			<tr>
				<td></td>
				<td><b>Note</b> : You can find the <b>EntityID</b> in Your IdP-Metadata XML file enclosed in <code>EntityDescriptor</code>
					tag having attribute as <code>entityID</code></td>

			</tr>

			<tr>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td><strong>SAML Login URL <span style="color:red;">*</span>:</strong></td>
				<td><input type="url" name="saml_login_url"
				           placeholder="Single Sign On Service URL (HTTP-Redirect binding) of your IdP"
				           style="width: 95%;" value="<?php echo $saml_login_url; ?>"
				           required <?php if ( ! Mo_GSuite_Utility::micr() )
						echo 'disabled' ?>/></td>
			</tr>

			<tr>
				<td></td>

				<td><b>Note</b> : You can find the <b>SAML Login URL</b> in Your IdP-Metadata XML file enclosed in
					<code>SingleSignOnService</code> tag (Binding type: HTTP-Redirect)


			</tr>

			<tr>
				<td>&nbsp;</td>
			</tr>
			<?php
			foreach ( $saml_x509_certificate as $key => $value ) {
				echo '<tr>
                <td><strong>X.509 Certificate <span style="color:red;">*</span>:</strong></td>
                <td><textarea  rows="6" cols="5" name="saml_x509_certificate[' . $key . ']" '.$disabled.' placeholder="Copy and Paste the content from the downloaded certificate or copy the content enclosed in X509Certificate tag (has parent tag KeyDescriptor use=signing) in IdP-Metadata XML file" style="width: 95%;"';
				echo ' >' . $value . '</textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><b>NOTE:</b> Format of the certificate:<br/><b>-----BEGIN CERTIFICATE-----<br/>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br/>-----END CERTIFICATE-----</b></i><br/>
                </tr>';
			}


			?>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><br/><input type="submit" name="submit" style="width:150px;margin-right: 3%;" value="Save"
				                class="button button-primary button-large"<?php if ( ! Mo_GSuite_Utility::micr() )
						echo 'disabled' ?>/>
					<input type="button" <?php echo $disabled?> name="test"
					       title="You can only test your Configuration after saving your Service Provider Settings."
					       onclick="showTestWindow();" <?php if ( ! mo_saml_is_sp_configured() || ! get_option( 'saml_x509_certificate' ) )
						echo 'disabled' ?> value="Test configuration" class="button button-primary button-large"
					       style="margin-right: 3%;width: 150px;"/>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><br/>
					<input type="button" <?php echo $disabled?>  name="saml_request"
					       title="You can only view your SAML request after saving your Service Provider Settings."
					       onclick="showSAMLRequest()"';
					<?php if ( ! mo_saml_is_sp_configured() || ! get_option( 'saml_x509_certificate' ) ) {
						echo 'disabled';
					} ?> value="Show SAML Request" class="button button-primary button-large" style="margin-right:
					3%;width:150px"/>

					<input type="button" <?php echo $disabled?>  name="saml_response"
					       title="You can only view your SAML response after saving your Service Provider Settings."
					       onclick="showSAMLResponse();"';
					<?php if ( ! mo_saml_is_sp_configured() || ! get_option( 'saml_x509_certificate' ) ) {
						echo 'disabled';
					} ?> value="Show SAML Response" class="button button-primary button-large" style="margin-right:
					3%;width:150px"/>
				</td>
			</tr>
		</table>
		<br/>
	</form>

</div>

