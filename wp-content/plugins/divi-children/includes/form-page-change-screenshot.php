<?php
/*
 * Upload and change child theme screenshot form
 */


$divi_childs = $this->get_divi_childs();

if ( ! $divi_childs ){
		?>
		<h3><?php _e( 'No Divi child theme found.', 'divi-children' ); ?></h3>
		<p><?php _e( 'You need to create a Divi child theme before you can change its screenshot.', 'divi-children' ); ?></p>
		<p><a href="#create-child" class="button-primary" ><?php _e( 'Create a Divi Child Theme', 'divi-children' ); ?></a></p>
		<?php
	} else {
		?>
		<h3><?php _e( 'Change the screenshot of your existing Divi child theme', 'divi-children' ); ?></h3>
		<?php
		if ( ! empty( $screenshot_error ) ) {
			?>
			<div class="error"><?php echo $screenshot_error; ?></div>
			<?php
		}
		?>		
		<form action="<?php echo admin_url( 'themes.php?page=divi-children-page' ); ?>" method="post" id="change_screenshot_form">

			<?php wp_nonce_field( 'change-screenshot-nonce' ); ?>

			<div class="form-part">
				<label for="divi_child">
					<p><b><?php _e( '1 - Select your Divi child theme:', 'divi-children' ) ?></b></p>
				</label>
				<div class="form-part-field">
					<select name="divi_child" >
						<?php
						foreach ( $divi_childs as $slug  => $name ) {
							echo '<option value="' . $slug . '">' . $name . '</option>';
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-part">
				<label for="upload_image">
					<p><b><?php _e( '2 - Upload your new screenshot:', 'divi-children' ); ?></b></p>
				</label>
				<div class="form-part-field">
					<input id="upload_image" type="text" size="36" name="ad_image" value="http://" /> 
					<input id="upload_image_button" class="button" type="button" value="Upload or Choose Image" />
					<p><em><?php _e( '(or type its file URL if it is to be uploaded from another location)', 'divi-children' ); ?></em></p>
				</div>
			</div>
	
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Change screenshot', 'divi-children' ); ?>" />
			</p>			
			
		</form>
		<?php
}
									
?>