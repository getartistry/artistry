<?php
/*
 * Update an existing child theme to the latest version of the Divi Children Engine
 */


$divi_childs = $this->get_divi_childs( 'updatable' );

if ( false === $divi_childs ) {

		?>
		<h3><?php _e( 'No Divi child themes found', 'divi-children' ); ?></h3>
		<p><?php _e( 'You need to create a Divi child theme before you can update it!', 'divi-children' ); ?></p>
		<p><a href="<?php echo admin_url( 'themes.php?page=divi-children-page/#create-child' ); ?>" class="button-primary" ><?php _e( 'Create a Divi Child Theme', 'divi-children' ); ?></a></p>
		<?php

	} elseif ( empty( $divi_childs ) ) {

		?>
		<h3><?php _e( 'No updatable Divi child themes found', 'divi-children' ); ?></h3>
		<p><?php _e( 'Only Divi child themes created by Divi Children 3.0.0 or later can have their Divi Children Engine automatically updated by this plugin.', 'divi-children' ); ?></p>
		<p><a href="<?php echo admin_url( 'themes.php?page=divi-children-page/#create-child' ); ?>" class="button-primary" ><?php _e( 'Back to Divi Children main page', 'divi-children' ); ?></a></p>
		<?php

	} else {

		?>
		<h3><?php _e( 'Update your Divi child theme to the latest version of the Divi Children Engine', 'divi-children' ); ?></h3>

		<p><?php _e( 'Existing Divi child themes that can be updated to get the latest customization functionalities can be selected below.', 'divi-children' ); ?></p>

		<?php
		if ( ! empty( $update_child_error ) ) {
			?>
			<div class="dc-error"><?php echo $update_child_error; ?></div>
			<?php
		}
		?>		
		<form action="<?php echo admin_url( 'themes.php?page=divi-children-page' ); ?>" method="post" id="update_child_form">

			<?php wp_nonce_field( 'child-update-nonce' ); ?>

			<div class="form-part">

				<label for="divichild_to_update">
					<span><b><?php _e( 'Select child theme:', 'divi-children' ) ?></b></span>
				</label>

				<select name="divichild_to_update" >
					<?php
					foreach ( $divi_childs as $slug  => $name ) {
						echo '<option value="' . $slug . '">' . $name . '</option>';
					}
					?>
				</select>

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Check/Update', 'divi-children' ); ?>" />						
				</p>

			</div>

		</form>
		
		<?php
}
									
?>