<?php

/*
 * Divi Children plugin page, containing forms for child theme creation and updating under different tabs.
 */

?>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>	

<?php

$thissitename = get_bloginfo( 'name' );
$theme_name = ucwords( $thissitename );
$theme_uri = home_url();
$theme_version = '1.0';
$theme_description = 'A child theme of Divi. This is a custom child theme created for our site ' . $thissitename . '.';
$theme_author = wp_get_current_user()->display_name;
$theme_authoruri = home_url();
 
?>

<div class="wrap">
	
	<div id="dch_title">

		<div id="dch_logo">
			<img src="<?php echo plugins_url( '../images/divi-children-logo-300.jpg' , __FILE__ ); ?>" />
		</div>

		<div id="share_message">
		
			<p>
			<?php _e( 'To stay tuned to news about this plugin, please subscribe to the', 'divi-children') ?><a href="http://divi4u.com/blog/" target="_blank"> Divi4u Blog</a>
			</p>		

			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="float:right">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="PBT5Z5PGN63AC">
				<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
				<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
			</form>
	
		</div>

	</div>

	<?php	
	// Check whether Divi is installed in this site, and display a warning message if not detected
	if ( ! file_exists( get_theme_root() . '/Divi/style.css' ) ) {
		?>
		<div class="notice notice-error" style="background-color: #fee;">
			<h3><?php _e( 'Wait! You need to install Divi if you want to use the child theme you are going to create!', 'divi-children' ); ?></h3>
			<p><em><?php _e( 'Maybe your parent Divi installation is not standard? You should have a /Divi/ theme folder within your WP themes folder.', 'divi-children' ); ?></em></p>
		</div>
		<?php
	}
	?>

	<div id="dch-tabs">

		<ul class="feature-tabs">
		  <li><a href="#create-child"><?php _e( 'Create New Divi Child Theme', 'divi-children' ); ?></a></li>
		  <li><a href="#change-screenshot"><?php _e( 'Change Screenshot', 'divi-children' ); ?></a></li>
		  <li><a href="#update-child"><?php _e( 'Update Child Theme', 'divi-children' ); ?></a></li>
		</ul>


		<div id="create-child" class="divi-children-form">

			<h2><?php _e( 'Fill out the following information for your new Divi child theme:', 'divi-children' ); ?></h2>

			<form action="<?php echo admin_url( 'themes.php?page=divi-children-page' ); ?>" method="post" id="child_theme_form">

				<?php wp_nonce_field( 'child-create-nonce' ); ?>
							
				<table>
					<tr>
						<th scope="row">
							<label for="theme_name">
								<?php _e( 'Theme Name:', 'divi-children' ) ?>
							</label>
						</th>
						<td>
							<input type="text" name="theme_name" size="30" value="<?php echo $theme_name; ?>" />
							<span><em><?php _e( 'This field should not be left blank.', 'divi-children' ) ?></em></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="theme_uri">
								<?php _e( 'Theme URI:', 'divi-children' ) ?>
							</label>
						</th>
						<td>
							<input type="text" name="theme_uri" size="30" value="<?php echo $theme_uri; ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="theme_version">
								<?php _e( 'Version:', 'divi-children' ) ?>
							</label>						
						</th>
						<td>
							<input type="text" name="theme_version" size="6" value="<?php echo $theme_version; ?>" />
						</td>
					<tr>
						<th scope="row">
							<label for="theme_description">
								<?php _e( 'Description:', 'divi-children' ) ?>
							</label>				
						</th>
						<td>
							<textarea name="theme_description" value="<?php echo $theme_description; ?>" rows="3" cols="50"/><?php echo $theme_description; ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="theme_authorname">
								<?php _e( 'Author:', 'divi-children' ) ?>
							</label>				
						</th>
						<td>
							<input type="text" name="theme_authorname" size="30" value="<?php echo $theme_author; ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="theme_authoruri">
								<?php _e( 'Author URI:', 'divi-children' ) ?>
							</label>				
						</th>
						<td>
							<input type="text" name="theme_authoruri" size="30" value="<?php echo $theme_authoruri; ?>" />
						</td>
					</tr>			
				</table>

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Create Divi Child Theme', 'divi-children' ); ?>" />
				</p>
					
			</form>

		</div>


		<div id="change-screenshot" class="divi-children-form">

			<?php require('form-page-change-screenshot.php'); ?>

		</div>


		<div id="update-child"  class="divi-children-form">

			<?php require('form-page-update-child.php'); ?>

		</div>


	</div>
	
</div>

