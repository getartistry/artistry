<?php
// === Add update hook ===
function booster_update_check() {
	global $wtfdivi;
	
	// Get the old and new version
	$old = get_option(BOOSTER_VERSION_OPTION);
	$new = BOOSTER_VERSION;
	
	// Run update actions
    if ($old!=$new) { 
		do_action('booster_update', $wtfdivi, $old, $new); 
		update_option(BOOSTER_VERSION_OPTION, $new);
	} 
	
}
add_action('plugins_loaded', 'booster_update_check');

// === v1.9.4: db074 - Add 0.7 opacity to old colors ===
function db074_add_alpha($plugin, $old, $new) {
	if (version_compare($old, '1.9.4', '<')) {
		
		// set alpha value to 0.7 - default for divi
		$fulloption = get_option('wtfdivi');
		$col = $fulloption['fixes']['074-set-header-menu-hover-color']['col'];
		
		// convert from hex to rgba
		if (preg_match("/^#?([0-9a-f]{3,6})$/", $col, $matches)) { 
			$hex = $matches[1];
			list($r,$g,$b) = str_split($hex,(strlen($hex)==6)?2:1);
			$r=hexdec($r); $g=hexdec($g); $b=hexdec($b);
		
			// Update the option with the rgba form of the color
			$fulloption['fixes']['074-set-header-menu-hover-color']['col'] = "rgba($r,$g,$b,0.7)";
			update_option('wtfdivi', $fulloption);
		}
	}
}
add_action('booster_update', 'db074_add_alpha', 10, 3);

// === v2.6.5: db135,db138 - split module content width feature into two, maintaining user choice ===
function db135_enable_footer_content_width($plugin, $old, $new) {
	if (version_compare($old, '2.6.5', '<')) {
		
		// Copy settings from mobile width option to footer width option
		$option = get_option('wtfdivi');
		if (isset($option['fixes']) && isset($option['fixes']['135-set-mobile-content-width'])) {
			$option['fixes']['138-set-footer-content-width'] = $option['fixes']['135-set-mobile-content-width'];
		}
		update_option('wtfdivi', $option);
	}
}
add_action('booster_update', 'db135_enable_footer_content_width', 10, 3);


// === v2.6.5: db095 - Add 0.7 opacity to old colors ===
function db095_add_alpha($plugin, $old, $new) {
	if (version_compare($old, '2.6.5', '<')) {
		
		// set alpha value to 0.7 - default for divi
		$fulloption = get_option('wtfdivi');
		$col = $fulloption['fixes']['095-secondary-nav-hover-color']['hovercol'];

		// convert from hex to rgba
		if (preg_match("/^#?([0-9a-f]{3,6})$/", $col, $matches)) { 
			$hex = $matches[1];
			list($r,$g,$b) = str_split($hex,(strlen($hex)==6)?2:1);
			$r=hexdec($r); $g=hexdec($g); $b=hexdec($b);
			
			// Update the option with the rgba form of the color
			$fulloption['fixes']['095-secondary-nav-hover-color']['hovercol'] = "rgba($r,$g,$b,0.7)";
			update_option('wtfdivi', $fulloption);
		}
	}
}
add_action('booster_update', 'db095_add_alpha', 10, 3);