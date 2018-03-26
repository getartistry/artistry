<?php
/**
 * Install demos page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class OPD_Install_Demos {

	/**
	 * Start things up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_page' ), 999 );
	}

	/**
	 * Add sub menu page for the custom CSS input
	 *
	 * @since 1.0.0
	 */
	public function add_page() {
		add_submenu_page(
			'oceanwp-panel',
			esc_html__( 'Pro Demos', 'ocean-pro-demos' ),
			'<span style="color: #36c786">' . esc_html__( 'Pro Demos', 'ocean-pro-demos' ) . '</span>',
			'manage_options',
			'oceanwp-panel-pro-demos',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Settings page output
	 *
	 * @since 1.0.0
	 */
	public function create_admin_page() {

		// Theme branding
		$brand = oceanwp_theme_branding(); ?>

		<div id="opd-demo-wrap" class="wrap">

			<h2><?php echo esc_attr( $brand ); ?> - <?php esc_attr_e( 'Install Pro Demos', 'ocean-pro-demos' ); ?></h2>

			<div class="updated error importer-notice importer-notice-1" style="display: none;">

				<p><?php esc_attr_e( 'We&rsquo;re sorry but the demo data could not be imported. It is most likely due to low PHP configurations on your server. You need to import the demo manually, look the second method from this article of the documentation:', 'ocean-pro-demos' ); ?> <a href="http://docs.oceanwp.org/article/463-how-to-import-a-pro-demo/#manual-installation" target="_blank"><?php esc_attr_e( 'Importing the sample data', 'ocean-pro-demos' ); ?></a></p>

			</div>

			<div class="updated importer-notice importer-notice-2" style="display: none;">

				<p><?php echo sprintf( esc_html__( 'Demo data successfully imported. Please check your page and make sure that everything has imported correctly. If it did, you can deactivate the %1$sOcean Pro Demos%2$s plugin, because it has done its job.', 'ocean-pro-demos' ), '<strong>', '</strong>' ); ?></p>

			</div>

			<div class="opd-important-notice notice notice-warning">

				<p><?php echo sprintf( esc_html__( 'Before you begin, make sure all the required plugins are activated. %1$sSee this article%2$s.', 'ocean-pro-demos' ), '<a href="http://docs.oceanwp.org/article/463-how-to-import-a-pro-demo#plugins" target="_blank">', '</a>' ); ?></p>

			</div>

			<div class="opd-about-description">

				<?php
				if ( is_plugin_active( 'wordpress-database-reset/wp-reset.php' ) ) {
					$plugin_link 	= admin_url( 'tools.php?page=database-reset' );
				} else {
					$plugin_link 	= admin_url( 'plugin-install.php?s=Wordpress+Database+Reset&tab=search' );
				} ?>

				<p><?php echo sprintf( esc_html__( 'Importing demo data ( post, pages, images, customizer settings, ... ) is the easiest way to setup your theme. It will allow you to quickly edit everything instead of creating content from scratch. We recommend uploading sample data on a clean installation to prevent conflicts with your current content. You can use this plugin to reset your site if needed: %1$sWordpress Database Reset%2$s.', 'ocean-pro-demos' ), '<a href="'. $plugin_link .'" target="_blank">', '</a>' ); ?></p>

			</div>

			<hr>

			<div class="theme-browser rendered">

				<?php
				// Vars
				$demos 		= OPD_Importer::get_data();
				$categories = OPD_Importer::get_all_categories( $demos ); ?>

				<?php if ( ! empty( $categories ) ) : ?>
					<div class="opd-header-bar">
						<nav class="opd-navigation">
							<ul>
								<li class="active"><a href="#all" class="opd-navigation-link"><?php esc_html_e( 'All', 'ocean-pro-demos' ); ?></a></li>
								<?php foreach ( $categories as $key => $name ) : ?>
									<li><a href="#<?php echo esc_attr( $key ); ?>" class="opd-navigation-link"><?php echo esc_html( $name ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
						<div clas="opd-search">
							<input type="text" class="opd-search-input" name="opd-search" value="" placeholder="<?php esc_html_e( 'Search demos...', 'ocean-pro-demos' ); ?>">
						</div>
					</div>
				<?php endif; ?>

				<div class="themes wp-clearfix">

					<?php
					// Loop through all demos
					foreach ( $demos as $demo => $key ) {

						$img_url = OPD_URL . '/demos/'; ?>

						<div class="theme-wrap" data-categories="<?php echo esc_attr( OPD_Importer::get_item_categories( $key ) ); ?>" data-name="<?php echo esc_attr( strtolower( $demo ) ); ?>">

							<div class="theme">

								<div class="theme-screenshot opd-install" data-demo-id="<?php echo esc_attr( $demo ); ?>">
									<img src="<?php echo esc_url( $img_url ); ?><?php echo esc_attr( $demo ); ?>/preview.jpg" />

									<div class="demo-import-loader preview-all preview-all-<?php echo esc_attr( $demo ); ?>"></div>

									<div class="demo-import-loader preview-icon preview-<?php echo esc_attr( $demo ); ?>"><i class="dashicons dashicons-update"></i></div>

									<div class="demo-import-loader success-icon success-<?php echo esc_attr( $demo ); ?>"><i class="dashicons dashicons-yes"></i></div>

									<div class="demo-import-loader warning-icon warning-<?php echo esc_attr( $demo ); ?>"><i class="dashicons dashicons-warning"></i></div>
								</div>

								<div class="theme-id-container">
		
									<h2 class="theme-name" id="<?php echo esc_attr( $demo ); ?>"><span><?php echo ucwords( $demo ); ?></span></h2>

									<div class="theme-actions">
										<a class="button button-secondary opd-install" data-demo-id="<?php echo esc_attr( $demo ); ?>" href="#"><?php _e( 'Install', 'ocean-pro-demos' ); ?></a>
										<a class="button button-primary" href="https://<?php echo esc_attr( $demo ); ?>.oceanwp.org/" target="_blank"><?php _e( 'Preview', 'ocean-pro-demos' ); ?></a>
									</div>
								</div>

							</div>

						</div>

					<?php } ?>

				</div>

			</div>

		</div>

	<?php }
}
new OPD_Install_Demos();