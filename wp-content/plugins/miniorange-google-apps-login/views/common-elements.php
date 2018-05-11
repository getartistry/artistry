<?php

function is_gsuite_customer_registered() {
	$registration_url = add_query_arg( array('page' => 'galoginaccount'), $_SERVER['REQUEST_URI'] );
	if(Mo_GSuite_Utility::micr())  return;
	echo '<div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);
							padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
		 <a href="'.$registration_url.'">'.mo_gsuite_( "Register or Login with miniOrange") .'</a> 
		 	'. mo_gsuite_( "to Enable Login With Google").'</div>';
}

function mo_gsuite_draw_tooltip( $header, $message ) {
	echo '<span class="tooltip">
				<span class="dashicons dashicons-editor-help"></span>
				<span class="tooltiptext"><span class="header"><b><i>' . mo_gsuite_( $header ) . '</i></b></span><br/><br/>
				<span class="body">' . mo_gsuite_( $message ) . '</span></span>
			  </span>';
}

function mo_gsuite_get_country_code_dropdown() {
	echo '<select name="default_country_code" id="mo_country_code">';
	echo '<option value="" disabled selected="selected">
				--------- ' . mo_gsuite_( 'Select your Country' ) . ' -------
			  </option>';
	foreach ( Mo_Gsuite_CountryList::getCountryCodeList() as $key => $country ) {
		echo '<option data-countrycode="' . $country['countryCode'] . '" value="' . $key . '"';
		echo Mo_Gsuite_CountryList::isCountrySelected( $country['countryCode'], $country['alphacode'] ) ? 'selected' : '';
		echo '>' . $country['name'] . '</option>';
	}
	echo '</select>';
}

function mo_gsuite_get_country_code_multiple_dropdown() {
	echo '<select multiple size="5" name="allow_countries[]" id="mo_country_code">';
	echo '<option value="" disabled selected="selected">
				--------- ' . mo_gsuite_( 'Select your Countries' ) . ' -------
			  </option>';
	foreach ( Mo_Gsuite_CountryList::getCountryCodeList() as $country ) {

	}
	echo '</select>';
}


function add_plugin_switch( $toggleSwitchValue ) {

	echo '<script>
						jQuery(document).ready(function(){	
							jQuery( "#myCheckbox" ).click(function() {
								var e = jQuery("#myCheckbox").is(":checked");
							jQuery.ajax({
								url:"' . site_url() . '/?option=miniorange-toggle-oauth-saml",
								type:"POST",
								data:{
								    chekbox_value:e
								},
								crossDomain:!0,
								dataType:"json",
								success:function(e){
									var current_page= "'.$_REQUEST['page'].'";
									if(current_page=="gsuitepricing"){
								        var redirect_url = "' . site_url() . '"+"/wp-admin/admin.php?page=gsuitepricing";
									}else{
								        var redirect_url = "' . site_url() . '"+"/wp-admin/admin.php?page=mogalsettings";
									}
								    window.location.href = redirect_url;
								},
								error: function(e) {}
							});
							});
						});
					 </script>';

	echo '	<label style="color: Black;">SAML</label>
			<label class="mo-switch">
                <input type="checkbox" id="myCheckbox" value="1"' . $toggleSwitchValue . '>
                <div class="mo-slider round" id="switch_checkbox" >
                </div>
                
            </label>
            <label style="color: Black;">OAuth</label>';

}

function mo_gsuite_handle_oauth_saml_switch() {
	if ( ! array_key_exists( 'option', $_REQUEST ) ) {
		return;
	}
	if ( strcasecmp( trim( $_REQUEST['option'] ), 'miniorange-toggle-oauth-saml' ) == 0 ) {
		$checkBoxValue = $_REQUEST["chekbox_value"];
		update_mo_gsuite_option( "mo_gsuite_select_saml_oauth", isset( $checkBoxValue ) ? $checkBoxValue : 0 );
		wp_send_json( Mo_GSuite_Utility::_create_json_response( "Succesfully Toggled", Mo_Gsuite_Constants::SUCCESS_JSON_TYPE ) );
	}
}

function mo_saml_is_sp_configured() {
	$saml_login_url = get_mo_gsuite_option( 'saml_login_url' );

	if ( empty( $saml_login_url ) ) {
		error_log("return false");
		return false;
	} else {
		return true;
	}
}


function Multisite_enabled(){
	if( is_multisite()){
		return "<b><font color='green'> enabled </font></b>";
	}
	return "<b><font color='red'> disabled </font></b>";
}

add_action( 'init', 'mo_gsuite_handle_oauth_saml_switch', 1 );

function mo_saml_is_curl_installed() {
	if ( in_array( 'curl', get_loaded_extensions() ) ) {
		return true;
	} else {
		return false;
	}
}

function mo_saml_is_openssl_installed() {
	if ( in_array( 'openssl', get_loaded_extensions() ) ) {
		return true;
	} else {
		return false;
	}
}

function mo_saml_is_mcrypt_installed() {
	if ( in_array( 'mcrypt', get_loaded_extensions() ) ) {
		return true;
	} else {
		return false;
	}
}


function mo_saml_get_test_url() {
	error_log(get_option( 'mo_saml_enable_cloud_broker' ));
	if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' || get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ){
		$url = site_url() . '/?option=testConfig';
	} else {
		$url = get_option( 'host_name' ) . '/idptest/?id=' . get_option( 'mo_gsuite_customer_validation_admin_customer_key' ) . '&key=' . get_option( 'mo_gsuite_customer_validation_customer_token' );
	}
	error_log("mo_".$url);
	return $url;
}


function mo_saml_get_saml_request_url() {

	if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' ) {
		$url = home_url() . '/?option=getsamlrequest';
	} else {
		$url = get_option( 'host_name' ) . '/getsamlrequest/?id=' . get_option( 'mo_gsuite_customer_validation_admin_customer_key' ) . '&key=' . get_option( 'mo_gsuite_customer_validation_customer_token' );
	}

	return $url;
}

function mo_saml_get_saml_response_url() {
	if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' ) {
		$url = site_url() . '/?option=getsamlresponse';
	} else {
		$url = get_option( 'host_name' ) . '/getsamlresponse/?id=' . get_option( 'mo_gsuite_customer_validation_admin_customer_key' ) . '&key=' . get_option( 'mo_gsuite_customer_validation_customer_token' );
	}

	return $url;
}



function miniorange_generate_metadata() {

	$sp_base_url = get_option( 'mo_saml_sp_base_url' );
	if ( empty( $sp_base_url ) ) {
		$sp_base_url = site_url();
	}
	if ( substr( $sp_base_url, - 1 ) == '/' ) {
		$sp_base_url = substr( $sp_base_url, 0, - 1 );
	}
	$sp_entity_id = get_option( 'mo_saml_sp_entity_id' );
	if ( empty( $sp_entity_id ) ) {
		$sp_entity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
	}

	$entity_id   = $sp_entity_id;
	$acs_url     = $sp_base_url . '/';/*
	$certificate = file_get_contents( plugin_dir_path( __FILE__ ) . 'resources' . DIRECTORY_SEPARATOR . 'sp-certificate.crt' );*/


	$certificate = file_get_contents( MOV_GSUITE_DIR.'resources/saml-resources/miniorange_sp_cert.cer' );

	$certificate = Mo_SAML_Utilities::desanitize_certificate( $certificate );
	ob_clean();
	header( 'Content-Type: text/xml' );
	echo '<?xml version="1.0"?>
<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2020-10-28T23:59:59Z" cacheDuration="PT1446808792S" entityID="' . $entity_id . '">
  <md:SPSSODescriptor AuthnRequestsSigned="false" WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</md:NameIDFormat>
    <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="' . $acs_url . '" index="1"/>
  </md:SPSSODescriptor>
  <md:Organization>
    <md:OrganizationName xml:lang="en-US">miniOrange</md:OrganizationName>
    <md:OrganizationDisplayName xml:lang="en-US">miniOrange</md:OrganizationDisplayName>
    <md:OrganizationURL xml:lang="en-US">http://miniorange.com</md:OrganizationURL>
  </md:Organization>
  <md:ContactPerson contactType="technical">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@miniorange.com</md:EmailAddress>
  </md:ContactPerson>
  <md:ContactPerson contactType="support">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@miniorange.com</md:EmailAddress>
  </md:ContactPerson>
</md:EntityDescriptor>';
	exit;

}




