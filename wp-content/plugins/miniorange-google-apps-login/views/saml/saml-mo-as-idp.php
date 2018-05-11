<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 16-02-2018
 * Time: 14:24
 */
?>
<div class="mo_registration_divided_layout">

    <div class="mo_gsuite_registration_table_layout">

        <?php include_once( MOV_GSUITE_DIR . "views/saml/saml-cloud-broker.php" ); ?>
        <p>
            Step by Step to Configure miniOrange IdP:</br>
            <a href="http://miniorange.com/miniorange_as_idp_wordpress" target='_blank'>Click Here to see
                the Guide for Configuring <b>miniOrange</b> as IdP.</a>
        </p>

        <h4>You will need the following information to configure your IdP. Copy it and keep it handy:</h4>
        <table border="1"
               style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px; margin:2px; border-collapse: collapse; width:98%">
            <tr>
                <td style="width:40%; padding: 15px;"><b>SP-EntityID / Issuer</b></td>
				<?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_Mo_GSuite_Utility::micr_saml() ) { ?>
                    <td style="width:60%; padding: 15px;">https://auth.miniorange.com/moas</td>
				<?php } else { ?>
                    <td style="width:60%; padding: 15px;"><?php echo site_url() . '/wp-content/plugins/miniorange-saml-20-single-sign-on/'; ?></td>
				<?php } ?>
            </tr>
            <tr>
                <td style="width:40%; padding: 15px;"><b>ACS (AssertionConsumerService) URL</b></td>
				<?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_Mo_GSuite_Utility::micr_saml() ){ ?>
                    <td style="width:60%;  padding: 15px;">https://auth.miniorange.com/moas/rest/saml/acs
                    </td>
				<?php }else{ ?>
                <td style="width:60%;  padding: 15px;"><?php echo site_url() . '/' ?></td>
            </tr>
			<?php } ?>
        </table>
    </div>
</div>
