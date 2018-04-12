<?php
/**
 * Migrate WCPL DB
 * This file only loaded if migration is needed.
 *
 * @since 1.0.0
 */
namespace CASE27\Integrations\Paid_Listings;

/**
 * Migrate WP Job Manager WC Paid Listing DB.
 *
 * @since 1.0.0
 */
class Migrate_WCPL {

	/**
	 * Use singleton instance.
	 */
	use \CASE27\Traits\Instantiatable;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 */
	public function __construct() {
		// Add Migration Admin Page.
		add_action( 'admin_menu', array( $this, 'add_migration_page' ), 999 );

		// Admin Notice
		add_action( 'admin_notices', array( $this, 'migrate_admin_notice' ) );
	}

	/**
	 * Add Migration Page.
	 *
	 * @since 1.0.0
	 */
	public function add_migration_page() {
		// Add admin page menu.
		add_submenu_page(
			$parent_slug = 'edit.php?post_type=job_listing',
			$page_title = esc_html__( 'Migrate WP Job Manager WC Paid Listings Package', 'my-listing' ),
			$menu_title = esc_html__( 'Migrate Old Package', 'my-listing' ),
			$capability = 'administrator',
			$menu_slug = 'case27_migrate_wcpl',
			$function = array( $this, 'migration_page' )
		);

		// Remove to hide it.
		remove_submenu_page( 'edit.php?post_type=job_listing', 'case27_migrate_wcpl' );
	}

	/**
	 * Migration Page HTML
	 *
	 * @since 1.0.0
	 */
	public function migration_page() {
		$url = add_query_arg( array(
			'post_type' => 'job_listing',
			'page'      => 'case27_migrate_wcpl',
			'_nonce'    => wp_create_nonce( 'case27_migrate_wcpl' ),
		), admin_url( 'edit.php' ) );
		?>

		<h1><?php esc_html_e( 'Migrate WP Job Manager WC Paid Listings Package', 'my-listing' ); ?></h1>

		<?php if ( ! isset( $_GET['_nonce'] ) || ! wp_verify_nonce( $_GET['_nonce'], 'case27_migrate_wcpl' ) ) : ?>
			<p><?php esc_html_e( 'Invalid security nonce.', 'my-listings' ); ?> <a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Try Again.', 'my-listings' ); ?></a></p>
			<?php return; ?>
		<?php endif; ?>

		<?php $migrated = case27_paid_listing_migrate_wpcl_user_packages(); ?>

		<p><?php printf( esc_html__( 'Migration complete. %d data migrated.', 'my-listing' ), count( $migrated ) ); ?> <a href="<?php echo esc_url( add_query_arg( 'post_type', 'case27_user_package', admin_url( 'edit.php' ) ) ); ?>"><?php esc_html_e( 'View All Packages.' ); ?></a></p>

		<?php
	}

	/**
	 * Migrate Admin Notice
	 *
	 * @since 1.0.0
	 */
	public function migrate_admin_notice() {
		$screen = get_current_screen();
		if ( 'edit-case27_user_package' !== $screen->id ) {
			return;
		}
		$url = add_query_arg( array(
			'post_type' => 'job_listing',
			'page'      => 'case27_migrate_wcpl',
			'_nonce'    => wp_create_nonce( 'case27_migrate_wcpl' ),
		), admin_url( 'edit.php' ) );
		?>
		<div class="notice notice-info is-dismissible">
			<p><?php _e( 'Old WC Paid Listing data found.', 'my-listing' ); ?> <a href="<?php echo esc_url( $url ); ?>"><?php _e( 'Start data migration.', 'my-listing' ); ?></a></p>
			<p><?php _e( 'IMPORTANT: Please backup all your database before performing this action.' ); ?></p>
		</div>
		<?php
	}

}

Migrate_WCPL::instance();
