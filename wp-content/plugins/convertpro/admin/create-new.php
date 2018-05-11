<?php
/**
 * Create new design file.
 *
 * @package ConvertPro
 */

$view   = isset( $_GET['view'] ) ? esc_attr( $_GET['view'] ) : '';
$coffee = isset( $_GET['cp_debug'] ) ? true : false;

if ( '' !== $view ) {
	if ( 'template' == $view ) {
		require_once( 'template.php' );
	}
} else {

	// load popup types.
	$types_dir = CP_FRAMEWORK_DIR . 'types/';

	$types = array(
		'modal_popup',
		'info_bar',
		'slide_in',
		'before_after',
		'inline',
		'widget',
		'welcome_mat',
		'full_screen',
	);

	foreach ( $types as $type ) {
		$file_path = str_replace( '_', '-', $type );
		$file_path = 'class-cp-' . $file_path;
		if ( file_exists( $types_dir . $file_path . '.php' ) ) {
			require_once( $types_dir . $file_path . '.php' );
		}
	}

?>

<div class="wrap about-wrap about-cp bend">
	<h2 class="cp-sub-head"><?php _e( 'Select a Call-to-action Type', 'convertpro' ); ?></h2>
	<div class="wrap-container">

		<div class="bend-content-wrap">

			<div class="container cp-dashboard-content">
				<?php
				$popup_types = cp_Framework::$types;

				$template_page_url = CP_V2_Tab_Menu::get_page_url( 'create-new' );
				?>

				<div class="cp-popup-container">
					<?php
					foreach ( $popup_types as $slug => $settings ) {

						$template_page_url = $template_page_url . '&view=template&type=' . $slug;

					?>
					<div class="cp-col-4 cp-popup-style">
						<div class="cp-popup-type-content">
							<a href="<?php echo esc_url( $template_page_url ); ?>">
								<?php
									$title       = $settings['title'];
									$description = $settings['description'];
								?>
								<h3 class="cp-popup-title"><?php echo $title; ?> </h3>
								<p class="cp-type-description"><?php echo $description; ?></p>
								<button class="cp-button-style cp-btn-block cp-btn-primary"><?php _e( 'Select', 'convertpro' ); ?></button>
							</a>
						</div>
					</div>

					<?php } ?>                    
				</div>
			</div><!-- cp-started-content -->
		</div><!-- bend-content-wrap -->
	</div><!-- .wrap-container -->
</div>
	<?php if ( $coffee ) { ?>
	<div class="cp-clear-template-data">
		<a href="#" style="position: absolute; bottom: 10px; right: 10px; top: auto; left: auto;" data-modal-type="all" class="cp-btn-primary cp-sm-btn cp-button-style cp-remove-local-templates"><?php _e( 'Delete Template Data', 'convertpro' ); ?></a>
	</div>
	<?php } ?>
	<?php do_action( 'cppro_create_new_footer' ); ?>
<?php }
	?>
