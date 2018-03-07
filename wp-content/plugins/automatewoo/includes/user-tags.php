<?php

namespace AutomateWoo;

/**
 * @class User_Tags
 * @since 2.9.10
 */
class User_Tags {

	private static $taxonomies = [];


	function __construct() {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
		add_action( 'registered_taxonomy', [ $this, 'registered_taxonomy' ], 10, 3 );
		add_action( 'admin_init', [ $this, 'admin_init' ] );

		// Menus
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_filter( 'parent_file', [ $this, 'parent_menu' ] );

		// User Profiles
		add_action( 'show_user_profile', [ $this, 'user_profile' ] );
		add_action( 'edit_user_profile', [ $this, 'user_profile' ] );
		add_action( 'personal_options_update', [ $this, 'save_profile' ] );
		add_action( 'edit_user_profile_update', [ $this, 'save_profile' ] );

		// List table
		add_filter( 'manage_users_columns', [ $this, 'inject_column_header' ] );
		add_filter( 'manage_users_custom_column', [ $this, 'inject_column_row' ], 10, 3 );
		add_action( 'pre_user_query', [ $this, 'filter_admin_query' ] );
		add_filter( 'views_users', [ $this, 'filter_user_views' ], 1, 1 );
		add_action( 'restrict_manage_users', [ $this, 'inject_bulk_actions' ], 1, 1 );
		add_action( 'admin_init', [ $this, 'catch_bulk_edit_action' ] );
	}


	/**
	 * @param string $taxonomy
	 * @param string $object
	 * @param array $args
	 */
	function registered_taxonomy( $taxonomy, $object, $args ) {
		global $wp_taxonomies;

		// Only modify user taxonomies, everything else can stay as is
		if ( $taxonomy !== 'user_tag' ) {
			return;
		}

		// We're given an array, but expected to work with an object later on
		$args = (object) $args;

		// Register any hooks/filters that rely on knowing the taxonomy now
		add_filter( "manage_edit-{$taxonomy}_columns", [ $this, 'set_user_column' ] );
		add_action( "manage_{$taxonomy}_custom_column", [ $this, 'set_user_column_values' ], 10, 3 );

		// Set the callback to update the count if not already set
		if ( empty( $args->update_count_callback ) ) {
			$args->update_count_callback = [ $this, 'update_count' ];
		}

		// We're finished, make sure we save out changes
		$wp_taxonomies[$taxonomy] = $args;
		self::$taxonomies[$taxonomy] = $args;
	}


	/**
	 * Create the user tags taxonomy
	 */
	function register_taxonomy() {
		register_taxonomy( 'user_tag', 'user', [
			'public' => false,
			'show_ui' => true,
			'labels' => [
				'name' => __( 'Tags', 'automatewoo' ),
				'singular_name' => __( 'Tag', 'automatewoo' ),
				'menu_name' => __( 'Tags', 'automatewoo' ),
				'search_items' => __( 'Search Tags', 'automatewoo' ),
				'popular_items' => __( 'Popular Tags', 'automatewoo' ),
				'all_items' => __( 'All Tags', 'automatewoo' ),
				'edit_item' => __( 'Edit Tag', 'automatewoo' ),
				'update_item' => __( 'Update Tag', 'automatewoo' ),
				'add_new_item' => __( 'Add New Tag', 'automatewoo' ),
				'new_item_name' => __( 'New Tag Name', 'automatewoo' ),
				'separate_items_with_commas' => __( 'Separate Tags with commas', 'automatewoo' ),
				'add_or_remove_items' => __( 'Add or remove Tags', 'automatewoo' ),
				'choose_from_most_used' => __( 'Choose from the most popular tags', 'automatewoo' ),
			],
			'rewrite' => false,
			'capabilities' => [
				'manage_terms' => 'edit_users',
				'edit_terms' => 'edit_users',
				'delete_terms' => 'edit_users',
				'assign_terms' => 'read',
			],
		] );
	}


	/**
	 * Admin init
	 */
	function admin_init() {
		if ( isset( $_REQUEST['eut_export_csv'] ) ) {
			include_once AW()->admin_path( '/user-tags-export.php' );
			$exporter = new User_Tags_Export();
			$exporter->set_user_tag( absint( $_REQUEST['user_tag'] ) );
			$exporter->generate_csv();
		}
	}


	/**
	 * We need to manually update the number of users for a taxonomy term
	 *
	 * @see    _update_post_term_count()
	 * @param array $terms - List of Term taxonomy IDs
	 * @param Object $taxonomy - Current taxonomy object of terms
	 */
	function update_count( $terms, $taxonomy ) {
		global $wpdb;

		foreach ( (array) $terms as $term ) {
			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );

			do_action( 'edit_term_taxonomy', $term, $taxonomy );
			$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), [ 'term_taxonomy_id' => $term ] );
			do_action( 'edited_term_taxonomy', $term, $taxonomy );
		}
	}


	/**
	 * Add each of the taxonomies to the users menu
	 */
	function admin_menu() {
		$taxonomies = self::$taxonomies;
		ksort( $taxonomies );

		foreach ( $taxonomies as $key => $taxonomy ) {
			add_users_page(
				$taxonomy->labels->menu_name,
				$taxonomy->labels->menu_name,
				$taxonomy->cap->manage_terms,
				"edit-tags.php?taxonomy={$key}"
			);
		}
	}

	/**
	 * Fix a bug with highlighting the parent menu item
	 * By default, when on the edit taxonomy page for a user taxonomy, the Posts tab is highlighted
	 * This will correct that bug
	 */
	function parent_menu( $parent = '' ) {
		global $pagenow;

		// If we're editing one of the user taxonomies
		// We must be within the users menu, so highlight that
		if ( ! empty( $_GET['taxonomy'] ) && $pagenow == 'edit-tags.php' && isset( self::$taxonomies[$_GET['taxonomy']] ) ) {
			$parent = 'users.php';
		}

		return $parent;
	}

	/**
	 * Correct the column names for user taxonomies
	 * Need to replace "Posts" with "Users"
	 *
	 * @param array $columns
	 * @return array
	 */
	function set_user_column( $columns ) {
		unset( $columns['posts'] );
		$columns['users'] = __( 'Users' );

		if ( current_user_can( 'edit_users' ) ) {
			$columns['export'] = __( 'Export', 'automatewoo' );
		}

		return $columns;
	}

	/**
	 * Set values for custom columns in user taxonomies
	 */
	function set_user_column_values( $display, $column, $term_id ) {
		if ( 'users' === $column && isset( $_GET['taxonomy'] ) ) {
			$term = get_term( $term_id, $_GET['taxonomy'] );
			echo '<a href="'.admin_url( 'users.php?user_tag='.$term->slug ).'">'.$term->count.'</a>';
		} elseif ( 'export' === $column ) {
			$url = wp_nonce_url( add_query_arg( [
				'eut_export_csv' => '1',
				'user_tag' => $term_id
			] ), 'eut_export_csv' );

			echo '<a href="'.$url.'" class="button">'.__( 'Export to CSV', 'automatewoo' ).'</a>';
		} else {
			echo '-';
		}
	}

	/**
	 * Add the taxonomies to the user view/edit screen
	 *
	 * @param \WP_User $user
	 */
	function user_profile( $user ) {

		// Using output buffering as we need to make sure we have something before outputting the header
		// But we can't rely on the number of taxonomies, as capabilities may vary
		ob_start();

		foreach ( self::$taxonomies as $taxonomy => $taxonomy_args ):

			// Check the current user can assign terms for this taxonomy
			if ( ! current_user_can( $taxonomy_args->cap->assign_terms ) )
				continue;

			// Get all the terms in this taxonomy
			$terms = get_terms( $taxonomy, [ 'hide_empty' => false ] );

			?>
          <table class="form-table">
              <tr>
                  <th>
                      <label for=""><?php printf( __( "Select %s", 'automatewoo' ), $taxonomy_args->labels->name ) ?></label>
                  </th>
                  <td>
							<?php if ( ! empty( $terms ) ): ?>
								<?php foreach ( $terms as $term ): ?>
                             <input type="checkbox" name="<?php echo $taxonomy ?>[]"
                                    id="<?php echo "{$taxonomy}-{$term->slug}" ?>"
                                    value="<?php echo $term->slug ?>" <?php checked( true, is_object_in_term( $user->ID, $taxonomy, $term ) ) ?> />
                             <label for="<?php echo "{$taxonomy}-{$term->slug}" ?>"><?php echo $term->name ?></label>
                             <br/>
								<?php endforeach; ?>
							<?php else: ?>
								<?php printf( __( "There are no %s available.", 'automatewoo' ), $taxonomy_args->labels->name ) ?>
							<?php endif; ?>
                  </td>
              </tr>
          </table>
			<?php
		endforeach;

		// Output the above if we have anything, with a heading
		$output = ob_get_clean();
		if ( ! empty( $output ) ) {
			echo '<h3>', __( 'Taxonomies', 'automatewoo' ), '</h3>';
			echo $output;
		}
	}

	/**
	 * Save the custom user taxonomies when saving a users profile
	 *
	 * @param Integer $user_id - The ID of the user to update
	 */
	function save_profile( $user_id ) {

		foreach ( self::$taxonomies as $key => $taxonomy ) {

			// Check the current user can edit this user and assign terms for this taxonomy
			if ( ! current_user_can( 'edit_user', $user_id ) && current_user_can( $taxonomy->cap->assign_terms ) )
				continue;

			$terms = [];

			// Save the data
			if ( isset( $_POST[$key] ) ) {
				$terms = array_map( 'sanitize_key', $_POST[$key] );
			}

			wp_set_object_terms( $user_id, $terms, $key, false );
			clean_object_term_cache( $user_id, $key );
		}
	}


	/**
	 * @param $columns
	 * @return array
	 */
	function inject_column_header( $columns ) {
		$pos = 5;
		$part = array_slice( $columns, 0, $pos );
		$part2 = array_slice( $columns, $pos );
		return array_merge( $part, [ 'user_tag' => __( 'Tags', 'automatewoo' ) ], $part2 );
	}


	/**
	 * @param $content
	 * @param $column
	 * @param $user_id
	 *
	 * @return string
	 */
	function inject_column_row( $content, $column, $user_id ) {
		if ( $column !== 'user_tag' ) return $content;

		if ( ! $tags = wp_get_object_terms( $user_id, $column ) ) {
			return '<span class="na">&ndash;</span>';
		} else {
			$termlist = array();
			foreach ( $tags as $tag ) {
				$termlist[] = '<a href="'.admin_url( 'users.php?user_tag='.$tag->slug ).' ">'.$tag->name.'</a>';
			}

			return implode( ', ', $termlist );
		}
	}


	/**
	 * Filter the products in admin based on options
	 *
	 * @param mixed $query
	 */
	function filter_admin_query( $query ) {
		global $wpdb, $pagenow;

		if ( is_admin() && $pagenow == 'users.php' && ! empty( $_GET['user_tag'] ) ) {
			$tag_slug = $_GET['user_tag'];
			$query->query_from .= " INNER JOIN {$wpdb->term_relationships} ON {$wpdb->users}.ID = {$wpdb->term_relationships}.object_id INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id INNER JOIN {$wpdb->terms} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id";
			$query->query_where .= " AND {$wpdb->terms}.slug = '{$tag_slug}'";
		}
	}


	/**
	 * @param array $views
	 * @return array
	 */
	function filter_user_views( $views ) {
		if ( ! empty( $_GET['user_tag'] ) ) {
			$views['all'] = str_replace( 'current', '', $views['all'] );
		}
		return $views;
	}


	/**
	 * @param $which
	 */
	function inject_bulk_actions( $which ) {

		if ( $which !== 'top' ) {
			return;
		}

		if ( current_user_can( 'edit_users' ) ) : ?>

          <label class="screen-reader-text"
                 for="add_user_tag"><?php _e( 'Add tag&hellip;', 'automatewoo' ) ?></label>
          <select name="add_user_tag" id="add_user_tag">
              <option value=""><?php _e( 'Add tag&hellip;', 'automatewoo' ) ?></option>
				 <?php self::wp_dropdown_user_tags(); ?>
          </select>

          <label class="screen-reader-text"
                 for="remove_user_tag"><?php _e( 'Remove tag&hellip;', 'automatewoo' ) ?></label>
          <select name="remove_user_tag" id="remove_user_tag">
              <option value=""><?php _e( 'Remove tag&hellip;', 'automatewoo' ) ?></option>
				 <?php self::wp_dropdown_user_tags(); ?>
          </select>

			<?php if ( class_exists( 'Members_Plugin' ) ): // fix for members v2.0 ?>
				<?php submit_button( esc_html__( 'Change', 'members' ), 'secondary', 'automatewoo-change-user-tags', false ) ?>
			<?php endif; ?>

		<?php endif;
	}

	/**
	 * Print out option html elements for role selectors.
	 *
	 * @param string $selected Slug for the role that should be already selected.
	 */
	static function wp_dropdown_user_tags( $selected = '' ) {

		$p = '';
		$r = '';

		$tags = get_terms( 'user_tag', [
			'hide_empty' => false
		] );

		foreach ( $tags as $tag ) {
			if ( $selected == $tag->term_id || $selected == $tag->slug )
				$p = "\n\t<option selected='selected' value='".esc_attr( $tag->term_id )."'>$tag->name</option>";
			else
				$r .= "\n\t<option value='".esc_attr( $tag->term_id )."'>$tag->name</option>";
		}
		echo $p.$r;
	}

	function catch_bulk_edit_action() {

		global $pagenow;

		if ( $pagenow != 'users.php' || empty( $_GET['users'] ) || ! current_user_can( 'edit_users' ) ) {
			return;
		}

		if ( empty( $_GET['changeit'] ) && empty( $_GET['automatewoo-change-user-tags'] ) ) {
			return;
		}

		$users = array_map( 'absint', $_GET['users'] );

		if ( ! empty( $_GET['add_user_tag'] ) ) {
			foreach ( $users as $user_id ) {
				wp_add_object_terms( $user_id, absint( $_GET['add_user_tag'] ), 'user_tag' );
			}
		}

		if ( ! empty( $_GET['remove_user_tag'] ) ) {
			foreach ( $users as $user_id ) {
				wp_remove_object_terms( $user_id, absint( $_GET['remove_user_tag'] ), 'user_tag' );
			}
		}

		echo '<div id="message" class="updated notice is-dismissible"><p>'.__( 'Tags updated.', 'automatewoo' ).'</p></div>';
	}


}

