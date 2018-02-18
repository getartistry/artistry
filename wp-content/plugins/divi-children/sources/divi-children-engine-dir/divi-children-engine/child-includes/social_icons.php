<?php

/**
 * Forces the use of the custom dce_get_social_icons() function from the Divi Children Engine instead of the Divi standard social_icons.php in case "Customize social icons" is selected via the Customizer.
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


if ( ! get_theme_mod( 'dce_customize_social_icons', 0 ) ) {
		include( get_template_directory() . '/includes/social_icons.php' );
	} else {
		echo dce_get_social_icons();
}

?>
