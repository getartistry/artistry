<?php


add_shortcode('wsi-widget','wsi_shortcode_func');
/** Main shortcode
 * Usage [wsi-widget title="Any custom title(optional)" providers="facebook,google"]
 * @param $atts
 * @return string
 */
function wsi_shortcode_func($atts){
	extract( shortcode_atts( array(
			'title'     => sprintf(__('Invite your friends to join %s','wsi'), get_bloginfo('name')),
			'providers' => ''
		), $atts )
	);

	return Wsi_Public::widget( $title, $providers );

}


