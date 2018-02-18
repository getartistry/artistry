<?php
/*
 * Child theme creation results page
 */

?>

<div id="child_created" class="main-panel">

		<?php
		if ( ! empty( $create_error ) ) {

				?>
				<div class="dc-error"><?php echo $create_error; ?></div>

				<p><a href="<?php echo admin_url( 'themes.php?page=divi-children-page/#create-child' ); ?>" class="button-primary"><?php _e( 'Back to Create New Divi Child Theme', 'divi-children' ); ?></a></p>

				<?php

			} else {

				?>
				<h3><?php _e( 'Your child theme was successfully created!', 'divi-children' ); ?></h3>

				<div id="created_theme">

					<div class="theme_screenshot">
						<img src="<?php echo esc_url( $created_child['theme_screenshot'] ); ?>" alt="screenshot">
					</div>
												
					<div class="theme_info">

						<h3><?php echo esc_html( $created_child['theme_name'] ); ?></h3>
						
						<h4><?php _e( 'By', 'divi-children' ); ?><?php echo ' ' . esc_html( $created_child['theme_authorname'] ); ?></h4>

						<p><em><?php _e( 'Version', 'divi-children' ); ?></em><b><?php echo ': ' . esc_html( $created_child['theme_version'] ); ?></b></p>
						<p><b><?php echo esc_html( $created_child['theme_description'] ); ?></b></p>
						<p><em><?php _e( 'Parent Theme', 'divi-children' ); ?></em><b><?php echo ': ' . esc_html( $created_child['theme_parent'] ); ?></b></p>
						<p><em><?php _e( 'Theme URI', 'divi-children' ); ?></em><b><?php echo ': ' . esc_url( $created_child['theme_uri'] ); ?></b></p>
						<p><em><?php _e( 'Author URI', 'divi-children' ); ?></em><b><?php echo ': ' . esc_url( $created_child['theme_authoruri'] ); ?></b></p>

						<a href="<?php echo admin_url( 'themes.php' ); ?>" class="button-primary"><?php _e( 'You can activate it now in the Themes Manager', 'divi-children' ); ?></a>

					</div>

				</div>
				<?php

		} ?>

</div>