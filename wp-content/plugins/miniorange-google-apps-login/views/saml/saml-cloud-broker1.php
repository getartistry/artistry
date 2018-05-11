<?php
?>

<div>
	<?php if ( Mo_GSuite_Utility::micr() ) { ?>
        <form id="mo_saml_cloud_broker" method="post" action="">
        <input type="hidden" name="option" value="mo_saml_enable_cloud_broker"/>
        <p>

            <!-- Edited In this Block. Adding the third option -->
            <input type="radio"
                <?php checked( get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ); ?>
                   id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker"
                   value="miniorange"
                   onchange="document.getElementById('mo_saml_cloud_broker').submit();">Use miniOrange
            as an Identity Provider (IDP) ( <a href="#" id="help_working_title3">Click Here<a></a> to
                know how the plugin works for this case. )<br/>

                <input type="radio" <?php checked( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' ); ?>
                       id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker" value="true"
                       onchange="document.getElementById('mo_saml_cloud_broker').submit();">Use
                miniOrange Identity broker service. ( <a href="#" id="help_working_title1">Click
                    Here<a></a> to know how the plugin works for this case. )<br/>

                    <input type="radio" <?php checked( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' || get_option( 'mo_saml_enable_cloud_broker' ) == '' ); ?>
                           id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker"
                           value="false"
                           onchange="document.getElementById('mo_saml_cloud_broker').submit();">Use Your
                    own Identity Provider ( <a href="#" id="help_working_title2">Click Here<a></a> to
                        know how the plugin works for this case. )<br/>


                        <!--    <input type="checkbox" id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker" value="true"  onchange="document.getElementById('mo_saml_cloud_broker').submit();" style="-webkit-appearance: radio; -moz-appearance: radio; -ms-appearance: radio;" /> Use <b>miniOrange Single Sign on service.</b>-->

                        <!--OPTION-1-->

                        <div hidden id="help_working_desc3" class="mo_saml_help_desc">
                            <h3>Option 1 :Use miniOrange as an Identity Provider (IDP) :</h3>
                            <div style="display:block;text-align:center;">
                                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/saml_working_womo1.png' ?>"
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

                        <!--OPTION-2-->

                        <div hidden id="help_working_desc1" class="mo_saml_help_desc">
                            <h3>Option 2: Use miniOrange Identity broker service:</h3>
                            <div style="display:block;text-align:center;">
                                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/saml_working.png' ?>"
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
                                    response status (along with the logged in user's information) back
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
                                        configuration for all of them. You don't have to make seperate
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

                        <!--OPTION-3-->


                        <div hidden id="help_working_desc2" class="mo_saml_help_desc">
                            <h3>Option 3 : Use Your own Identity Provider:</h3>
                            <div style="display:block;text-align:center;">
                                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/saml_working_womo.png' ?>"
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


                        <!-- Edited In this Block. Adding the third option -->
        </p>

		<?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' && get_option( 'saml_x509_certificate' ) ){ ?>
        <div style="background-color:#CBCBCB;padding:1%;">
            You will have to follow the following steps after you change the option above:
            <ol>
                <li><b>ReConfigure your IdP settings</b>. Please refer the table below in <b>Step 1</b>
                    for updated URLs that you would need.
                </li>
                <li>After configuring your IdP go to <a
                            href="<?php echo admin_url() ?>admin.php?page=mo_saml_settings&tab=save">Service
                        Provider Tab</a> and click on the <b>save button</b> to save your configuration.
                </li>


            </ol>
			<?php }

			/* edited here */
			else if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ){ ?>
			<?php }
			else if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' ){ ?>
            <div style="background-color:#CBCBCB;padding:1%;">
				<?php if ( ! get_option( 'saml_x509_certificate' ) ) { ?>
                    Please follow the following steps:
                    <ol>
                        <li>Configure your IdP. Refer the table below in <b>Step 1</b> and keep the
                            information handy for <b>Step 2</b>.
                        </li>
                        <li>After configuring your IdP go to <a
                                    href="<?php echo admin_url() ?>admin.php?page=mo_saml_settings&tab=save">Service
                                Provider Tab</a> to configure and save your configuration.
                        </li>
                    </ol>
				<?php } else { ?>
                    You will have to follow the following steps after you change the above option:
                    <ol>
                        <li><b>ReConfigure your IdP settings</b>. Please refer the table below in <b>Step
                                1</b> for updated URLs that you would need.
                        </li>
                        <li>After configuring your IdP go to <a
                                    href="<?php echo admin_url() ?>admin.php?page=mo_saml_settings&tab=save">Service
                                Provider Tab</a> and click on the <b>save button</b> to save your
                            configuration.
                        </li>


                    </ol>
				<?php } ?>
				<?php } ?>
            </div>

        </form><?php } ?>
</div>
