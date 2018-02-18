<?php
/*
 * Changed child theme screenshot results page
 */
 

?>

<div id="screenshot_changed" class="main-panel">

		<?php
		if ( ! empty( $screenshot_error ) ) {

				?>
				<div class="dc-error"><?php echo $screenshot_error; ?></div>
				<?php

			} else {

				?>
				<h3><?php _e( 'The screenshot was successfully changed', 'divi-children' ); ?></h3>
					
				<div id="new_screenshot">
					<p><?php _e( 'This is the new screenshot for your child theme', 'divi-children' ); ?><b><?php echo ' ' . $screenshot_changed['child_name'] . ': '; ?></b></p>
					<div class="theme_screenshot">
						<img src="<?php echo $screenshot_changed['new_screenshot']; ?>" alt="screenshot">
					</div>	
				</div>
				<?php

		} ?>

		<p><a href="<?php echo admin_url( 'themes.php?page=divi-children-page' ); ?>" class="button-primary"><?php _e( 'Back to Divi Children main page', 'divi-children' ); ?></a></p>

</div>

