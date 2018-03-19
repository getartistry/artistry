<?php

register_activation_hook( __FILE__, 'tm_quick_view_activate' );
function tm_quick_view_activate(){

}


register_deactivation_hook( __FILE__, 'tm_quick_view_deactivate' );
function tm_quick_view_deactivate(){
    
}


add_action('plugins_loaded','tm_load_class_files');

function tm_load_class_files(){
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
		require_once 'classes/class.frontend.php';

		load_plugin_textdomain( 'woocommerce-quick-view', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
		

		$tm_plugin_dir_url  = plugin_dir_url( __FILE__ );
		$data                 = get_option('tm_options');
		$enable_mobile        = ($data['enable_mobile']==='1')?true:false;
		$load_frontend 	  = new tm_frontend($tm_plugin_dir_url);
	}
}





