<?php
/**
 * Claim Listing
 *
 * @since 1.0.0
 */

namespace CASE27\Integrations\Paid_Listings;

/**
 * Claim.
 *
 * @since 1.0.0
 */
class Claim {

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
		// Register claim post type.
		add_action( 'init', array( $this, 'register_claim_post_type' ) );

		// Add this menu to listings.
		add_action( 'admin_menu',  array( $this, 'add_claim_as_listings_submenu' ), 99 );

		// Fix active menu when visiting claim screen.
		add_filter( 'parent_file', array( $this, 'set_claim_parent_menu_edit_screen' ) );
		add_filter( 'submenu_file', array( $this, 'set_claim_submenu_edit_screen' ) );

		// Add title.
		add_filter( 'the_title', array( $this, 'claim_title' ), 10, 2 );

		// Admin columns.
		add_filter( 'manage_claim_posts_columns',  array( $this, 'claim_posts_columns' ) );
		add_action( 'manage_claim_posts_custom_column',  array( $this, 'claim_posts_custom_column' ), 5, 2 );
		add_filter( 'post_row_actions', array( $this, 'remove_claim_quick_edit' ), 10, 2 );
		add_filter( 'bulk_actions-edit-claim', array( $this, 'remove_claim_bulk_action_edit' ) );

		// Status Meta Box.
		add_action( 'add_meta_boxes', array( $this, 'add_claim_status_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_claim_status_meta_box' ), 10, 2 );

		// Settings.
		add_filter( 'job_manager_settings', array( $this, 'add_claim_page_settings' ) );

		// Add button covers options.
		add_filter( 'case27\types\cover_buttons', array( $this, 'add_claim_button_option' ) );

		// Cover button output.
		add_action( 'case27\listing\cover\buttons\claim-listing', array( $this, 'display_claim_cover_button' ), 30, 2 );

		// Claimed body class.
		add_filter( 'body_class', array( $this, 'claimed_body_classes' ) );

		// Claim shortcode.
		add_action( 'init', function() {
			add_shortcode( 'claim_listing', array( $this, 'claim_listing_shortcode' ) );
		} );

		// Load claim form.
		add_action( 'template_redirect', function() {
			$page_id = get_option( 'job_manager_claim_listing_page_id' );
			if ( $page_id && is_page( $page_id ) ) {
				do_action( 'case27_claim_form_init' );
			}
		} );
		add_action( 'case27_claim_form_init', array( $this, 'claim_form_init' ), 5 );
		add_action( 'case27_claim_form_output', array( $this, 'claim_form_output' ) );
	}

	/**
	 * Register Claim Post Type
	 *
	 * @since 1.0.0
	 */
	public function register_claim_post_type() {
		$args = array(
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( '' ),
			'labels'             => array(
				'name'               => __( 'Claims', 'my-listing' ),
				'singular_name'      => __( 'Claim', 'my-listing' ),
				'menu_name'          => __( 'Claim Entries', 'my-listing' ),
				'name_admin_bar'     => __( 'Claims', 'my-listing' ),
				'add_new'            => __( 'Add New', 'my-listing' ),
				'add_new_item'       => __( 'Add New Claim', 'my-listing' ),
				'new_item'           => __( 'New Claim', 'my-listing' ),
				'edit_item'          => __( 'Edit Claim', 'my-listing' ),
				'view_item'          => __( 'View Claim', 'my-listing' ),
				'all_items'          => __( 'All Claims', 'my-listing' ),
				'search_items'       => __( 'Search Claims', 'my-listing' ),
				'parent_item_colon'  => __( 'Parent Claims:', 'my-listing' ),
				'not_found'          => __( 'No Claims found.', 'my-listing' ),
				'not_found_in_trash' => __( 'No Claims found in Trash.', 'my-listing' ),
			),
		);

		register_post_type( 'claim', $args );
	}

	/**
	 * Add Claim as Listings Submenu.
	 *
	 * @since 1.0.0
	 * @link https://shellcreeper.com/how-to-add-wordpress-cpt-admin-menu-as-sub-menu/
	 */
	public function add_claim_as_listings_submenu() {
		$cpt_obj = get_post_type_object( 'claim' );
		add_submenu_page(
			'edit.php?post_type=job_listing', // Parent slug.
			$cpt_obj->labels->name,           // Page title.
			$cpt_obj->labels->menu_name,      // Menu title.
			$cpt_obj->cap->edit_posts,        // Capability.
			'edit.php?post_type=claim'        // Menu slug.
		);
	}

	/**
	 * Set claim parent menu edit screen.
	 *
	 * @since 1.0.0
	 *
	 * @param string $parent_file Parent menu slug.
	 * @return string
	 */
	public function set_claim_parent_menu_edit_screen( $parent_file ) {
		global $current_screen;
		if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'claim' === $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=job_listing';
		}
		return $parent_file;
	}

	/**
	 * Set active sub menu when visiting claim menu edit screen.
	 *
	 * @since 1.0.0
	 *
	 * @param string $submenu_file Active submenu slug.
	 * @return string
	 */
	public function set_claim_submenu_edit_screen( $submenu_file ) {
		global $current_screen;
		if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'claim' === $current_screen->post_type ) {
			$submenu_file = 'edit.php?post_type=claim';
		}
		return $submenu_file;
	}

	/**
	 * Claim Title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $title The title string.
	 * @param int    $id    Post ID.
	 * @return string
	 */
	public function claim_title( $title, $id = null ) {
		if ( ! $id || 'claim' !== get_post_type( $id ) ) {
			return $title;
		}

		$status = case27_paid_listing_claim_get_status( $id );
		return "#{$id} - {$status}";
	}

	/**
	 * Claim Columns
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Post Columns.
	 * @return array
	 */
	public function claim_posts_columns( $columns ) {
		$new_columns = array(
			'cb'           => $columns['cb'],
			'title'        => esc_html__( 'Claim ID', 'my-listing' ),
			'listing'      => esc_html__( 'Listing', 'my-listing' ),
			'claimer'      => esc_html__( 'Claimer', 'my-listing' ),
			'user_package' => esc_html__( 'Payment Package', 'my-listing' ),
			'date'         => $columns['date'],
		);
		return $new_columns;
	}

	/**
	 * Claim Custom Columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column  Column ID.
	 * @param int    $post_id Post ID.
	 */
	public function claim_posts_custom_column(  $column, $post_id  ) {
		switch ( $column ) {

			case 'listing':
				$link = esc_html__( 'n/a', 'my-listing' );
				$listing_id = absint( get_post_meta( $post_id, '_listing_id', true ) );
				$listing = $listing_id ? get_post( $listing_id ) : false;
				if ( $listing && 'job_listing' === $listing->post_type ) {
					$link = '<a target="_blank" href="' . esc_url( get_edit_post_link( $listing_id ) ) . '">#' . $listing_id . ' - ' . $listing->post_title . '</a>';
				}
				echo $link;
			break;

			case 'claimer':
				$title = esc_html__( 'n/a', 'my-listing' );
				$user_id = absint( get_post_meta( $post_id, '_user_id', true ) );
				if ( $user_id ) {
					$user = get_userdata( $user_id );
					if ( $user ) {
						$user_id = '<a target="_blank" href="' . esc_url( get_edit_user_link( $user_id ) ) . '">#' . $user_id . '</a>';
						$title = "{$user_id} - {$user->user_login} ({$user->user_email})";
					}
				}

				echo $title;
			break;

			case 'user_package':
				$link = esc_html__( 'n/a', 'my-listing' );
				$package_id = absint( get_post_meta( $post_id, '_user_package_id', true ) );
				$package = $package_id ? get_post( $package_id ) : false;
				if ( $package && 'case27_user_package' === $package->post_type ) {
					$link = '<a target="_blank" href="' . esc_url( get_edit_post_link( $package_id ) ) . '">' . get_the_title( $package_id ) . '</a>';
				}
				echo $link;
			break;
		}
	}

	/**
	 * Remove Quick Edit.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $actions Row Actions.
	 * @param WP_Post #post    Post Object.
	 * @return array
	 */
	public function remove_claim_quick_edit( $actions, $post ) {
		if ( 'claim' === $post->post_type ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * Remove Claim Edit Bulk Actions
	 *
	 * @since 1.0.0
	 *
	 * @param array $actions Actions list.
	 * @return array
	 */
	public function remove_claim_bulk_action_edit( $actions ) {
		unset( $actions['edit'] );
		return $actions;
	}

	/**
	 * Add Claim Status Meta Box
	 *
	 * @since 1.0.0
	 */
	public function add_claim_status_meta_box() {
		add_meta_box(
			$id         = 'case27_paid_listing_claim_status_meta_box',
			$title      = __( 'Claim Status', 'my-listing' ),
			$callback   = array( $this, 'claim_status_meta_box' ),
			$screen     = array( 'claim' ),
			$context    = 'side',
			$priority   = 'high'
		);
	}

	/**
	 * Claim Status Meta Box
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post data.
	 * @param array   $box  Meta Box Data.
	 */
	public function claim_status_meta_box( $post, $box ) {
		global $user_ID, $hook_suffix;
		$post_id = $post->ID;
		$statuses = case27_paid_listing_claim_get_valid_statuses();
		$status = get_post_meta( $post_id, '_status', true );
		$status = isset( $statuses[ $status ] ) ? $status : 'pending';
		?>
		<p>
			<select id="claim-status" class="widefat" name="_status" autocomplete="off" data-old-status="<?php echo esc_attr( $status ); ?>">
				<?php foreach ( $statuses as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $status, $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php wp_nonce_field( "case27_claim_status_{$post_id}", '_claim_status_nonce' ); ?>
		</p>

		<?php if ( 'post.php' == $hook_suffix ) : // Post already saved, show notification option. ?>
			<div id="claim-notification-field" style="display:none;">
				<ul>
					<li>
						<label><input name="_send_claim_email[]" autocomplete="off" type="checkbox" value="claimer" checked="checked"> <?php esc_html_e( 'Send claimer status update via email.', 'my-listing' ); ?></label>
					</li>
				</ul>
			</div>
			<script>
				jQuery( document ).ready( function($) {
					$( '#claim-status' ).change( function() {
						if( $(this).val() !== $(this).data( 'old-status' ) ) {
							$( '#claim-notification-field' ).slideDown();
						} else {
							$( '#claim-notification-field' ).slideUp();
						}
					});
				});
			</script>
		<?php endif; ?>
		<style>
			#misc-publishing-actions{display:none !important;}
			#minor-publishing-actions{padding:0 !important;}
			#major-publishing-actions{border:none !important;}
		</style>
		<?php
	}

	/**
	 * Save Claim Status Meta Box
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post Object.
	 */
	public function save_claim_status_meta_box( $post_id, $post ) {
		if ( ! isset( $_POST['_claim_status_nonce'], $_POST['_status'] ) || ! wp_verify_nonce( $_POST['_claim_status_nonce'], "case27_claim_status_{$post_id}" ) ) {
			return;
		}

		// Save status.
		$statuses   = case27_paid_listing_claim_get_valid_statuses();
		$old_status = get_post_meta( $post_id, '_status', true );
		$new_status = $_POST['_status'];
		$new_status = isset( $statuses[ $new_status ] ) ? $new_status : $old_status;

		// Update Status.
		if ( $new_status && $new_status !== $old_status ) {
			update_post_meta( $post_id, '_status', $new_status );

			if ( 'approved' === $new_status ) {
				case27_paid_listing_claim_approve_claim( $post_id );
			}

			// Notification.
			if ( isset( $_POST['_send_claim_email'] ) && is_array( $_POST['_send_claim_email'] ) && $_POST['_send_claim_email'] ) {
				if ( in_array( 'claimer', $_POST['_send_claim_email'] ) ) {
					case27_paid_listing_claim_send_claim_email( $post_id );
				}
			}
		}
	}

	/**
	 * Add Claim Page Settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Settings configuration.
	 * @return array
	 */
	public function add_claim_page_settings( $settings ) {
		$settings['job_pages'][1][] = array(
			'name'      => 'job_manager_claim_listing_page_id',
			'std'       => '',
			'label'     => __( 'Claim Listing Page', 'my-listing' ),
			'desc'      => __( 'Select the page where you have placed the [claim_listing] shortcode (required).', 'my-listing' ),
			'type'      => 'page',
		);
		return $settings;
	}

	/**
	 * Add Claim Button Option.
	 *
	 * @since 1.0.0
	 *
	 * @param array $buttons Buttons.
	 * @return array
	 */
	public function add_claim_button_option( $buttons ) {
		$buttons['claim-listing'] = array(
			'action' => 'claim-listing',
			'label'  => esc_html__( 'Claim Listing', 'my-listing' ),
		);

		return $buttons;
	}

	/**
	 * Claim cover button output.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $button  Button data.
	 * @param object $listing Listing data.
	 */
	public function display_claim_cover_button( $button, $listing ) {
		$claim_url = case27_paid_listing_claim_url( $listing->get_id() );
		if ( ! $claim_url ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			$claim_url = add_query_arg( [
				'redirect_to' => $claim_url,
				'notice' => 'login-required',
			], wc_get_page_permalink('myaccount') );
		}

		?>
		<li>
			<a class="buttons <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?> medium" href="<?php echo esc_attr( $claim_url ) ?>">
				<?php echo do_shortcode( $button['label'] ) ?>
			</a>
		</li>
		<?php
	}

	/**
	 * Claimed Body Classes
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function claimed_body_classes( $classes ) {
		if ( is_singular( 'job_listing' ) ) {
			if ( case27_paid_listing_is_claimed( get_queried_object_id() ) ) {
				$classes[] = 'c27-verified';
			}
		}
		return $classes;
	}

	/**
	 * Claim Listing Form Shortcode
	 *
	 * @since 1.0.0
	 */
	public function claim_listing_shortcode() {
		$listing_id = absint( $_GET['listing_id'] );
		$post = get_post( $listing_id );
		if ( 'job_listing' !== $post->post_type ) {
			echo wpautop( __( 'Listing invalid or listing cannot be claimed.', 'my-listing' ) );
		} else {
			do_action( 'case27_claim_form_output' );
		}
		return ob_get_clean();
	}

	/**
	 * Claim Form Init
	 * To setup and process the data. This is loaded only on claim page.
	 *
	 * @since 1.0.0
	 */
	public function claim_form_init() {
		// Make sure WPJM Form Class is loaded.
		if ( ! class_exists( '\WP_Job_Manager_Form' ) ) {

			$path = JOB_MANAGER_PLUGIN_DIR . '/includes/abstracts/abstract-wp-job-manager-form.php';
			if ( defined( 'JOB_MANAGER_PLUGIN_DIR' ) && file_exists( $path ) ) {
				include( $path );
			}

			// Class still not exist, bail.
			if ( ! class_exists( '\WP_Job_Manager_Form' ) ) {
				return;
			}
		}

		// Load claim form.
		require_once( get_template_directory() . '/includes/integrations/27collective/paid-listings/class-claim-form.php' );
		if ( ! class_exists( __NAMESPACE__ . '\Claim_Form' ) ) {
			return;
		}

		// Make sure registration enabled and account required in claim page.
		add_filter( 'job_manager_enable_registration', '__return_true' );
		add_filter( 'job_manager_user_requires_account', '__return_true' );

		$form = Claim_Form::instance();
		$form->process();

	}

	/**
	 * Load Claim Form
	 *
	 * @since 1.0.0
	 */
	public function claim_form_output() {
		if ( class_exists( __NAMESPACE__ . '\Claim_Form' ) ) {
			$form = Claim_Form::instance();
			$form->output();
		}
	}


}

Claim::instance();
