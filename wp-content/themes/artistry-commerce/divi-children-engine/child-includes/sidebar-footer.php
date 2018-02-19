<?php

/**
 * Replaces the original Divi parent sidebar-footer.php file to include a fifth widget area for footers customized to have 5 widget columns.
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


	if ( ! is_active_sidebar( 'sidebar-2' ) && ! is_active_sidebar( 'sidebar-3' ) && ! is_active_sidebar( 'sidebar-4' ) && ! is_active_sidebar( 'sidebar-5' ) && ! is_active_sidebar( 'sidebar-6' ) )
		return;
?>

<div class="container">
	<div id="footer-widgets" class="clearfix">
	<?php
		$footer_area_5_active = is_active_sidebar( 'sidebar-6' );

		if ( $footer_area_5_active ) {
				$footer_sidebars = array( 'sidebar-2', 'sidebar-3', 'sidebar-4', 'sidebar-5', 'sidebar-6' );
			} else {
				$footer_sidebars = array( 'sidebar-2', 'sidebar-3', 'sidebar-4', 'sidebar-5' );
		}

		foreach ( $footer_sidebars as $key => $footer_sidebar ) :
			if ( is_active_sidebar( $footer_sidebar ) ) :
				if ( $footer_area_5_active ) {
						echo '<div class="footer-widget' . ( 4 === $key ? ' last' : '' ) . '">';
					} else {
						echo '<div class="footer-widget' . ( 3 === $key ? ' last' : '' ) . '">';
				}				
				dynamic_sidebar( $footer_sidebar );
				echo '</div> <!-- end .footer-widget -->';
			endif;
		endforeach;
	?>
	</div> <!-- #footer-widgets -->
</div>	<!-- .container -->