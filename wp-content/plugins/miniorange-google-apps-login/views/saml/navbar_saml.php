<?php

echo '	<div class="wrap">
			<div><img style="float:left;" src="' . MOV_GSUITE_LOGO_URL . '"></div>
			
			<div class="mo-gsuite-header">
				' . mo_gsuite_( "Google Apps SAML" ) . '
				<a class="add-new-h2" href="' . $profile_url . '">' . mo_gsuite_( "Account" ) . '</a>
				<a class="add-new-h2" href="' . $help_url . '" target="_blank">' . mo_gsuite_( "FAQs" ) . '</a>
				<a class="mo-gsuite-license add-new-h2" style=" margin-right: 30%;" href="' . $license_url . '">' . mo_gsuite_( "Upgrade" ) . '</a>';

add_plugin_switch( $toggleSwitchValue );

echo '				
				</div>	
		</div>';

echo '	<div id="tab">
			<h2 class="nav-tab-wrapper">
				';

echo '	
				<a class="nav-tab ' . ( $active_tab == 'identity_provider_saml' ? 'nav-tab-active' : '' ) . '" href="' . $saml_idp_setup . '">
                                                                                   ' . mo_gsuite_( "Identity Provider" ) . '</a>
                                                                                   
                <a class="nav-tab ' . ( $active_tab == 'service_provider_saml' ? 'nav-tab-active' : '' ) . '" href="' . $saml_sp_config . '">
                                                                                   ' . mo_gsuite_( "
                                                                                   Service Provider" ) . '</a>
                                                                                  
                <a class="nav-tab ' . ( $active_tab == 'sign_in_setting_saml' ? 'nav-tab-active' : '' ) . '" href="' . $saml_sign_in_setting . '">
                                                                                   ' . mo_gsuite_( "Sign in Settings" ) . '</a>
                                                 
                <a class="nav-tab ' . ( $active_tab == 'mapping_saml' ? 'nav-tab-active' : '' ) . '" href="' . $saml_mapping . '">
                                                                                   ' . mo_gsuite_( "Attribute / Role Mapping" ) . '</a>
                                                                                  
                <a class="nav-tab ' . ( $active_tab == 'saml_import_export_config' ? 'nav-tab-active' : '' ) . '" href="' . $saml_import_export_config . '">
                                                                                   ' . mo_gsuite_( "Import Export Configuration" ) . '</a>

				<a class="nav-tab ' . ( $active_tab == 'proxy_setup' ? 'nav-tab-active' : '' ) . '" href="' . $saml_proxy . '">
                                                                                       ' . mo_gsuite_( "Proxy Setup" ) . '</a>';

/*do_action( 'mo_otp_verification_nav_bar_after', $active_tab );*/
echo
'</h2>
		</div>
		<div id="mo_gsuite_messages"></div>';