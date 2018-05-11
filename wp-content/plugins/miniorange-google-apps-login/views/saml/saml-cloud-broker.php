<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 15-02-2018
 * Time: 18:57
 */

echo '<div>';
echo ' <form id="mo_saml_cloud_broker" method="post" action="">
            
            <input '.$disabled.'  type="hidden" name="option" value="mo_saml_enable_cloud_broker"/>
            <p>
                <input '.$disabled.'  type="radio" '.$is_mo_as_idp_radio.'
                       id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker"
                       value="miniorange"
                       onchange="document.getElementById(\'mo_saml_cloud_broker\').submit();">Use miniOrange
                as an Identity Provider (IDP) ( <a href="#" id="help_working_title3">Click Here<a></a> to
                    know how the plugin works for this case. )<br/> 


                    <input '.$disabled.'  type="radio" '.$is_mo_as_broker_service_radio.' 
                           id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker" value="true"
                           onchange="document.getElementById(\'mo_saml_cloud_broker\').submit();">Use
                    miniOrange Identity broker service. ( <a href="#" id="help_working_title1">Click
                        Here<a></a> to know how the plugin works for this case. )<br/>


                        <input '.$disabled.'  type="radio" '.$is_your_idp_radio.'
                               id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker"
                               value="false"
                               onchange="document.getElementById(\'mo_saml_cloud_broker\').submit();">Use Your
                        own Identity Provider ( <a href="#" id="help_working_title2">Click Here<a></a> to
                            know how the plugin works for this case. )<br/> '; ?>

            
            
            <div hidden id="help_working_desc3" class="mo_saml_help_desc">
                            <h3>Option 1 :Use miniOrange as an Identity Provider (IDP) :</h3>
                            <div style="display:block;text-align:center;">
                                <img src="<?php echo MOV_GSUITE_URL. 'includes/images/SAML/saml_working_womo1.png'; ?>"
                                     alt="Working of miniOrange SAML plugin" style="width: 55%;"/>
                            </div>
                            <ol>
                                <li>miniOrange SAML SSO plugin sends a login request to MiniOrange for
                                    authentication.
                                </li>
                                <li>Upon successful authentication, MiniOrange sends a SAML Response
                                    back to miniOrange SAML SSO plugin. Plugin then reads the response
                                    and login the user.
                                </li>
                            </ol>
                            <div>
                                <b>Advantages:</b>
                                <ol>
                                    <li>The number of messages required for Single Sign On is less as
                                        your website is directly interacting with the Identity Provider.
                                    </li>
                                </ol>
                            </div>
                        </div>
                        
                        <!---- OPTION 2 ---->
                        
                        <div hidden id="help_working_desc1" class="mo_saml_help_desc">
                            <h3>Option 2: Use miniOrange Identity broker service:</h3>
                            <div style="display:block;text-align:center;">
                                <img src="<?php echo MOV_GSUITE_URL. 'includes/images/SAML/saml_working.png' ?>""
                                     alt="Working of miniOrange SAML plugin" style="width: 85%;"/>
                            </div>
                            <ol>
                                <li>miniOrange SAML SSO plugin sends a login request to miniOrange SSO
                                    Service.
                                </li>
                                <li>miniOrange SSO Service creates a SAML Request and redirects the user
                                    to your Identity Provider for authentication.
                                </li>
                                <li>Upon successful authentication, your Identity Provider sends a SAML
                                    Response back to miniOrange SSO Service.
                                </li>
                                <li>miniOrange SSO Service verifies the SAML Response and sends a
                                    response status (along with the logged in user\'s information) back
                                    to miniOrange SAML SSO plugin. Plugin then reads the response and
                                    logins the user.
                                </li>
                            </ol>
                            <div>
                                <b>Advantages:</b>
                                <ol>
                                    <li>If you are an enterprise or business user then on using this
                                        service you will be able to take full advantage of all of
                                        miniOrange SSO features. ( For a complete list of these features
                                        <a href="http://miniorange.com/single-sign-on-sso"
                                           target="_blank">Click Here</a>)
                                    </li>
                                    <li>You can use Non-SAML Identity Providers for Single Sign On.</li>
                                    <li>If you have multiple websites then you can use the same IdP
                                        configuration for all of them. You don\'t have to make seperate
                                        configurations in your IdP.
                                    </li>
                                    <li>Some Identity Providers like ADFS do not support HTTP endpoints
                                        ( i.e. your wordpress site needs to be on HTTPS ). So, if your
                                        wordpress site is not on HTTPS then you can use this service for
                                        such IdPs.
                                    </li>
                                </ol>
                            </div>
                        </div>

            <!------    OPTION 3 ----------->
            <div hidden id="help_working_desc2" class="mo_saml_help_desc">
                            <h3>Option 3 : Use Your own Identity Provider:</h3>
                            <div style="display:block;text-align:center;">
                                <img src="<?php echo MOV_GSUITE_URL. 'includes/images/SAML/saml_working_womo1.png'; ?>"
                                     alt="Working of miniOrange SAML plugin" style="width: 55%;"/>
                            </div>
                            <ol>
                                <li>miniOrange SAML SSO plugin sends a login request to your Identity
                                    Provider for authentication.
                                </li>
                                <li>Upon successful authentication, your Identity Provider sends a SAML
                                    Response back to miniOrange SAML SSO plugin. Plugin then reads the
                                    response and login the user.
                                </li>
                            </ol>

                            <div>
                                <b>Advantages:</b>
                                <ol>
                                    <li>The number of messages required for Single Sign On is less as
                                        your website is directly interacting with the Identity Provider.
                                    </li>
                                </ol>
                            </div>
                        </div>
                        
            </p> 

        </form>
</div>
<?php
echo '<script>
jQuery("#help_working_title1").click(function () {
    jQuery("#help_working_desc2").hide();
    jQuery("#help_working_desc3").hide();
    jQuery("#help_working_desc1").slideToggle(400);
});

jQuery("#help_working_title2").click(function () {
    jQuery("#help_working_desc1").hide();
    jQuery("#help_working_desc3").hide();
    jQuery("#help_working_desc2").slideToggle(400);
});

jQuery("#help_working_title3").click(function () {
    jQuery("#help_working_desc1").hide();
    jQuery("#help_working_desc2").hide();
    jQuery("#help_working_desc3").slideToggle(400);
});
</script>';
?>