<?php

echo '	<div class="wrap">
			<div><img style="float:left;" src="' . MOV_GSUITE_LOGO_URL . '"></div>
			
			<div class="mo-gsuite-header">
				' . mo_gsuite_( "Google Apps Oauth Client" ) . '
				<a class="add-new-h2" href="' . $profile_url . '">' . mo_gsuite_( "Account" ) . '</a>
				<a class="add-new-h2" href="' . $help_url . '" target="_blank">' . mo_gsuite_( "FAQs" ) . '</a>
				<a class="mo-gsuite-license add-new-h2" style=" margin-right: 24%;" href="' . $license_url . '">' . mo_gsuite_( "Upgrade" ) . '</a>';

					add_plugin_switch( $toggleSwitchValue );
echo '				
				</div>	
		</div>';

echo '	<div id="tab">
			<h2 class="nav-tab-wrapper">';

echo '	

				<a class="nav-tab ' . ( $active_tab == 'configuration_oauth' ? 'nav-tab-active' : '' ) . '" href="' . $oauth_configuration . '">
				                                                                                   ' . mo_gsuite_( "Oauth Configuration" ) . '</a>
                                                                                   
				<a class="nav-tab ' . ( $active_tab == 'customization_oauth' ? 'nav-tab-active' : '' ) . '" href="' . $oauth_customization . '">
                                                                                   ' . mo_gsuite_( "Customization" ) . '</a>
                                                                                   
                                                                                  
                <a class="nav-tab ' . ( $active_tab == 'signinsettings_oauth' ? 'nav-tab-active' : '' ) . '" href="' . $oauth_signinsetting . '">
                                                                                   ' . mo_gsuite_( "Sign In Settings" ) . '</a>
                                                 
                <a class="nav-tab ' . ( $active_tab == 'mapping_oauth' ? 'nav-tab-active' : '' ) . '" href="' . $oauth_mapping . '">
                                                                                   ' . mo_gsuite_( "Attribute / Role Mapping" ) . '</a>
                                                                                  
                <a class="nav-tab ' . ( $active_tab == 'report_oauth' ? 'nav-tab-active' : '' ) . '" href="' . $oauth_report . '">
                                                                                   ' . mo_gsuite_( "Reports" ) . '</a>';
do_action( 'mo_gsuite_oauth_nav_bar_after', $active_tab );
echo
	'</h2>
		</div>
		<div id="mo_gsuite_messages"></div>';
