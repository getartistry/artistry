<?php

echo '	<div class="wrap">
			<div><img style="float:left;" src="' . MOV_GSUITE_LOGO_URL . '"></div>
			
			<div class="mo-gsuite-header">
				' . mo_gsuite_( "OTP Verification" ) . '
				<a class="add-new-h2" href="' . $profile_url . '">' . mo_gsuite_( "Account" ) . '</a>
				<a class="add-new-h2" href="' . $help_url . '">' . mo_gsuite_( "FAQs" ) . '</a>
				<a class="mo-gsuite-license add-new-h2" href="' . $license_url . '">' . mo_gsuite_( "Upgrade" ) . '</a>';

add_plugin_switch();

echo '				
				</div>	
		</div>';

echo '	<div id="tab">
			<h2 class="nav-tab-wrapper">';

echo '	
		<a class="nav-tab ' . ( $active_tab == 'mogalsettings' ? 'nav-tab-active' : '' ) . '" href="' . $settings . '">
																						' . mo_gsuite_( "Form").'</a>
				<a class="nav - tab '.($active_tab == 'otpsettings'? 'nav - tab - active' : '').'" href="'.$otpsettings	.'">
																						'.mo_gsuite_("OTP Settings").'</a>
				<a class="nav - tab '.($active_tab == 'config'   	 ? 'nav - tab - active' : '').'" href="'.$config		.'">
																						'.mo_gsuite_("SMS / Email Templates").'</a>
				<a class="nav - tab '.($active_tab == 'messages' 	 ? 'nav - tab - active' : '').'" href="'.$messages		.'">
																						'.mo_gsuite_("Messages").'</a>
				<a class="nav - tab '.($active_tab == 'design' 	 ? 'nav - tab - active' : '').'" href="'.$design		.'">
																						'.mo_gsuite_("Design").'</a>';
			do_action('mo_gsuite_SAML_nav_bar_after' , $active_tab);
echo
			'</h2>
		</div>';


