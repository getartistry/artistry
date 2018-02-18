<?php
/*
 * Update child theme results page
 */
 

?>

<div id="child_update" class="main-panel">

		<?php
		if ( ! empty( $update_child_error ) ) {

				?>
				<div class="dc-error"><?php echo $update_child_error; ?></div>
				<?php

			} else {

				$theme_slug = $update_child['theme_slug'];
				$update_action = $update_child['update_action'];
				$theme_engine_version = $update_child['theme_engine_version'];
				$theme_plugin_version = $update_child['theme_plugin_version'];
				$current_engine_version = $update_child['current_engine_version'];
				$current_plugin_version = $update_child['current_plugin_version'];

				if ( 'updated' == $update_action ) {

						$new_installed_dce_version = $update_child['new_installed_dce_version'];
						$new_installed_by_version = $update_child['new_installed_by_version'];
						?>
						<h3><?php _e( 'The Divi Children Engine installed in your child theme was successfully updated', 'divi-children' ); ?></h3>
						<p><?php echo ( __( 'From: Version', 'divi-children' ) . ' ' . $theme_engine_version . ' <em>(' . __( 'Installed by Divi Children plugin version', 'divi-children' ) . ' ' . $theme_plugin_version . ')</em>' ); ?></p>
						<p><?php echo ( __( 'To: Version', 'divi-children' ) . ' <b>' . $new_installed_dce_version . '</b> <em>(' . __( 'Installed by Divi Children plugin version', 'divi-children' ) . ' ' . $new_installed_by_version . ')</em>' ); ?></p>
						<?php

					} elseif( 'none' == $update_action ) {

						?>
						<h3><?php _e( 'Your child theme is ok, no updating is required', 'divi-children' ); ?></h3>
						<p><?php _e( 'Your child theme is already equiped with the latest version of the Divi Children Engine: ', 'divi-children' ); ?></p>
						<p><?php echo ( __( 'Version', 'divi-children' ) . ' ' . $theme_engine_version . ' <em>(' . __( 'installed by Divi Children plugin version', 'divi-children' ) . ' ' . $theme_plugin_version . ')</em>' ); ?></p>
						<?php

					} elseif( 'wrong-version' == $update_action ) {

						?>
						<h3><?php _e( 'There is something wrong with your Divi Children version', 'divi-children' ); ?></h3>
						<p><b><?php _e( 'You are trying to update your Divi child theme with an outdated version of the Divi Children plugin:', 'divi-children' ); ?></b></p>
						<p><?php echo ( __( 'The Divi Children Engine installed in your child theme is version', 'divi-children' ) . ' ' . $theme_engine_version . ' ' . __( 'and was installed by Divi Children plugin version', 'divi-children' ) . ' ' . $theme_plugin_version . '.' ); ?></p>
						<p><?php echo ( __( 'But now you are trying to use the Divi Children plugin version', 'divi-children' ) . ' ' . $current_plugin_version . ', ' . __( 'which is an older version of the plugin that would install the', 'divi-children' ) . ' ' . $current_engine_version . ' ' . __( 'version of the Divi Children Engine.', 'divi-children' ) ); ?></p>
						<p><b><em><?php _e( 'Please update Divi Children before trying to update your child theme with it', 'divi-children' ); ?></em></b></p>
						<p><a href="<?php echo admin_url( 'plugins.php' ); ?>" class="button-primary"><?php _e( 'Go to Installed Plugins page', 'divi-children' ); ?></a></p>
						<?php

				}

		}

		?>
		<p><a href="<?php echo admin_url( 'themes.php?page=divi-children-page' ); ?>" class="button-primary"><?php _e( 'Back to Divi Children main page', 'divi-children' ); ?></a></p>

</div>

