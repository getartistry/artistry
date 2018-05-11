<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 16-02-2018
 * Time: 14:31
 */
echo '<div class="mo_registration_divided_layout">
 	<div border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
        <table style="width:100%;">
            <tr>
                <td colspan="3">
                    <h3>Upload IDP Metadata
                        <span style="float:right;margin-right:25px;">
                            <a href="' . admin_url() . 'admin.php?page=service_provider_saml' . '">
                            <input type="button"  class="button" value="Cancel"/></a>
                        </span>
                    </h3>
                </td>
            </tr><tr><td colspan="4"><hr></td></tr>
            <tr>';

echo '
            <form name="saml_form" method="post" action="' . admin_url() . 'admin.php?page=service_provider_saml' . '" enctype="multipart/form-data">
            
                <tr>
                <td width="30%"><strong>Identity Provider Name<span style="color:red;">*</span>:</strong></td>
                <td><input type="text" '.$disabled.' name="saml_identity_metadata_provider" placeholder="Identity Provider name like ADFS, SimpleSAML" style="width: 100%;" value="" required /></td>
                </tr>';
echo '
                 <tr>';

echo '
                <input type="hidden" name="option" value="mo_saml_upload_metadata" />
                <input type="hidden" name="action" value="upload_metadata" />
            
                    <td>Upload Metadata  :</td>
                    <td colspan="2">
                    <input type="file" '.$disabled.' name="metadata_file" />
                    <input type="submit" '.$disabled.' class="button button-primary button-large" value="Upload"/></td>
                    </tr>';
echo '<tr>
                <td colspan="2"><p style="font-size:13pt;text-align:center;"><b>OR</b></p></td>
            </tr>';
echo '
            
            <tr>
                <input type="hidden" name="option" value="mo_saml_upload_metadata" />
                <input type="hidden" name="action" value="fetch_metadata" />
                <td width="20%">Enter metadata URL:</td>
                <td><input type="url" '.$disabled.' name="metadata_url" placeholder="Enter metadata URL of your IdP." style="width:100%" value=""/></td>
                <td width="20%">&nbsp;&nbsp;<input '.$disabled.' type="submit" class="button button-primary button-large" value="Fetch Metadata"/></td>
            </tr>
            </form>';
echo '</table><br />
	
	</div>';

echo '</div>';


//ToDo /*' . /*$sync_url*/ . '*/;
