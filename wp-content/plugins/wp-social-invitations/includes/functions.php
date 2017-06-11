<?php

/**
 * Check if given plugin is active
 * @param $plugin
 * @return bool
 */
function wsi_is_active( $plugin ) {
	return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || wsi_is_network_active( $plugin );
}

/**
 * Check if given plugin is active on network
 * @param $plugin
 * @return bool
 */
function wsi_is_network_active( $plugin ) {
	if ( !is_multisite() )
		return false;

	$plugins = get_site_option( 'active_sitewide_plugins');
	if ( isset($plugins[$plugin]) )
		return true;

	return false;
}

/**
 * Return full url
 * @return string
 */
function wsi_current_url( ) {
	return (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}

/**
 * Get and return the located template
 * @param $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 */
function wsi_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	if ( $args && is_array($args) )
		extract( $args );

	$located = wsi_locate_template( $template_name, $template_path, $default_path );

	include( $located );
}


/**
 * Locate the template. First check if exist in theme
 * @param $template_name
 * @param string $template_path
 * @param string $default_path
 *
 * @return mixed
 */
function wsi_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	if ( ! $template_path ) $template_path  = 'wordpress-social-invitations/';
	if ( ! $default_path )  $default_path   = WSI_PLUGIN_DIR . '/templates/';

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template
	if ( ! $template )
		$template = $default_path . $template_name;

	// Return what we found
	return apply_filters('wsi/locate_template', $template, $template_name, $template_path);
}


/**
 * Get the obj Id. Used when giving point to give more flexibility to users
 * @return int
 */
function wsi_get_obj_id(){
	global $wp_query;

	// will be 0 if we are in home page or an archives page
	$id = 0;

	if( isset( $wp_query->queried_object->ID ) )
		$id =  $wp_query->queried_object->ID;

	return $id;
}

/**
 * Returns a "unique" id to be used as widgets id
 * @return string
 */
function wsi_generate_id() {
	return substr( md5(uniqid(rand(), true)) ,0 ,10);
}

/**
 * Simple function that return current logged user display name
 * @return string
 */
function wsi_get_display_name() {
	global $current_user;
	get_currentuserinfo();

	$userInfo    = wsi_get_data('user_info');

	// Check for provider display name or get current logged user
	@$displayName = isset( $userInfo->displayName ) ? $userInfo->displayName : $current_user->display_name;

	return $displayName;
}

/**
 * Retrieve data from session
 *
 * @param $data
 *
 * @return string|bool
 */
function wsi_get_data( $data ) {
	return isset($_SESSION['wsi_data'][$data]) ? $_SESSION['wsi_data'][$data] : false;
}

/**
 * Stripslashes in array
 * @param $value
 *
 * @return array|string
 */function wsi_stripslashes_deep($value)
{
	$value = is_array($value) ?
		array_map('stripslashes_deep', $value) :
		stripslashes($value);

	return $value;
}

/**
 * Filter a comma list of providers and return array
 * @param $providers
 *
 * @return mixed
 */function wsi_filter_providers( $providers){
	$passed_providers = explode(',', $providers);
	foreach( $passed_providers as $p ) {
		$np[$p] = ucfirst($p);
	}
	return $np;
}

/**
 * check if provider use mailer class or own api
 * @param $provider
 *
 * @return bool
 */function wsi_is_mailer_provider( $provider ) {
	if( empty($provider) || $provider == 'mail' ||  $provider == 'yahoo' ||  $provider == 'foursquare' ||  $provider == 'google'||  $provider == 'live' )
		return true;
	return false;
}

/**
 * Check if a user already sent invites using the plugin.
 * @param $user_id
 *
 * @return bool
 */
function wsi_user_already_invited( $user_id ) {
	global $wpdb;

	if( empty($user_id) )
		if( ! $user_id = get_current_user_id() )
			return false;

	$invites = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}wsi_invites WHERE user_id = %d", array($user_id) ) );

	return ($invites);
}

/**
 * Retrieve the total of invites users
 * @param $user_id
 *
 * @return bool|int number of invites
 */
function wsi_get_total_invites( $user_id = '' ) {

	if( empty($user_id) )
		if( ! $user_id = get_current_user_id() )
			return false;

	return get_user_meta($user_id, 'wsi_total_invites', true);
}
