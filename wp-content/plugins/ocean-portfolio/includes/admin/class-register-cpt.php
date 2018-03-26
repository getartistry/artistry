<?php
/**
 * Register Post Type
 */

if ( ! class_exists( 'OceanWP_Portfolio_Post_Type' ) ) {

	class OceanWP_Portfolio_Post_Type {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_post_type' ) );
			add_action( 'init', array( $this, 'register_categories' ) );
			add_action( 'init', array( $this, 'register_tags' ) );
			add_action( 'init', array( $this, 'shortcodes_post_type' ) );
			if ( is_admin() ) {
				add_filter( 'manage_edit-ocean_portfolio_columns', array( $this, 'edit_columns' ) );
				add_action( 'manage_ocean_portfolio_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
				add_action( 'restrict_manage_posts', array( $this, 'categories_filter' ) );
				add_filter( 'ocean_main_metaboxes_post_types', array( $this, 'add_metabox' ), 20 );
			}
		}

		/**
		 * Register custom post type
		 *
		 * @since 1.0.0
		 */
		public static function register_post_type() {

			// Vars
			$slug 	= get_theme_mod( 'op_portfolio_slug', 'portfolio' );
			$slug 	= $slug ? $slug : 'portfolio-item';

			register_post_type( 'ocean_portfolio', apply_filters( 'ocean_portfolio_args', array(
				'labels' => array(
					'name' 					=> esc_html__( 'Portfolio', 'ocean-portfolio' ),
					'singular_name' 		=> esc_html__( 'Portfolio Item', 'ocean-portfolio' ),
					'add_new' 				=> esc_html__( 'Add New', 'ocean-portfolio' ),
					'add_new_item' 			=> esc_html__( 'Add New Item', 'ocean-portfolio' ),
					'edit_item' 			=> esc_html__( 'Edit Item', 'ocean-portfolio' ),
					'new_item' 				=> esc_html__( 'Add New Item', 'ocean-portfolio' ),
					'view_item' 			=> esc_html__( 'View Item', 'ocean-portfolio' ),
					'search_items' 			=> esc_html__( 'Search Items', 'ocean-portfolio' ),
					'not_found' 			=> esc_html__( 'No Items Found', 'ocean-portfolio' ),
					'not_found_in_trash' 	=> esc_html__( 'No Items Found In Trash', 'ocean-portfolio' )
				),
				'public' 					=> true,
				'has_archive' 				=> false,
				'can_export'            	=> true,
				'capability_type' 			=> 'post',
				'menu_icon' 				=> 'dashicons-portfolio',
				'menu_position' 			=> 20,
				'rewrite' 					=> array( 'slug' => $slug, 'with_front' => false ),
				'supports' 					=> array(
					'title',
					'editor',
					'excerpt',
					'thumbnail',
					'comments',
					'custom-fields',
					'revisions',
					'author',
					'page-attributes',
				),
			) ) );

		}

		/**
		 * Register category
		 *
		 * @since 1.0.0
		 */
		public static function register_categories() {

			// Vars
			$slug = get_theme_mod( 'op_portfolio_category_slug', 'portfolio-category' );
			$slug = $slug ? $slug : 'portfolio-category';

			// Define args and apply filters
			$args = apply_filters( 'ocean_portfolio_category_args', array(
				'labels' => array(
					'name' 							=> esc_html__( 'Portfolio Categories', 'ocean-portfolio' ),
					'singular_name' 				=> esc_html__( 'Portfolio Category', 'ocean-portfolio' ),
					'menu_name' 					=> esc_html__( 'Categories', 'ocean-portfolio' ),
					'search_items' 					=> esc_html__( 'Search','ocean-portfolio' ),
					'popular_items' 				=> esc_html__( 'Popular', 'ocean-portfolio' ),
					'all_items' 					=> esc_html__( 'All', 'ocean-portfolio' ),
					'parent_item' 					=> esc_html__( 'Parent', 'ocean-portfolio' ),
					'parent_item_colon' 			=> esc_html__( 'Parent', 'ocean-portfolio' ),
					'edit_item' 					=> esc_html__( 'Edit', 'ocean-portfolio' ),
					'update_item' 					=> esc_html__( 'Update', 'ocean-portfolio' ),
					'add_new_item' 					=> esc_html__( 'Add New', 'ocean-portfolio' ),
					'new_item_name' 				=> esc_html__( 'New', 'ocean-portfolio' ),
					'separate_items_with_commas' 	=> esc_html__( 'Separate with commas', 'ocean-portfolio' ),
					'add_or_remove_items' 			=> esc_html__( 'Add or remove', 'ocean-portfolio' ),
					'choose_from_most_used' 		=> esc_html__( 'Choose from the most used', 'ocean-portfolio' ),
				),
				'public' 							=> true,
				'show_in_nav_menus' 				=> true,
				'show_ui' 							=> true,
				'show_tagcloud' 					=> true,
				'hierarchical' 						=> true,
				'rewrite' 							=> array( 'slug' => $slug, 'with_front' => false ),
				'query_var' 						=> true
			) );

			// Register the category taxonomy
			register_taxonomy( 'ocean_portfolio_category', array( 'ocean_portfolio' ), $args );

		}

		/**
		 * Register tags
		 *
		 * @since 1.0.0
		 */
		public static function register_tags() {

			// Vars
			$slug = get_theme_mod( 'op_portfolio_tag_slug', 'portfolio-tag' );
			$slug = $slug ? $slug : 'portfolio-tag';

			// Define args and apply filters
			$args = apply_filters( 'ocean_portfolio_tag_args', array(
				'labels' => array(
					'name' 							=> esc_html__( 'Portfolio Tags', 'ocean-portfolio' ),
					'singular_name' 				=> esc_html__( 'Portfolio Tag', 'ocean-portfolio' ),
					'menu_name' 					=> esc_html__( 'Tags', 'ocean-portfolio' ),
					'search_items' 					=> esc_html__( 'Search','ocean-portfolio' ),
					'popular_items' 				=> esc_html__( 'Popular', 'ocean-portfolio' ),
					'all_items' 					=> esc_html__( 'All', 'ocean-portfolio' ),
					'parent_item' 					=> esc_html__( 'Parent', 'ocean-portfolio' ),
					'parent_item_colon' 			=> esc_html__( 'Parent', 'ocean-portfolio' ),
					'edit_item' 					=> esc_html__( 'Edit', 'ocean-portfolio' ),
					'update_item' 					=> esc_html__( 'Update', 'ocean-portfolio' ),
					'add_new_item' 					=> esc_html__( 'Add New', 'ocean-portfolio' ),
					'new_item_name' 				=> esc_html__( 'New', 'ocean-portfolio' ),
					'separate_items_with_commas' 	=> esc_html__( 'Separate with commas', 'ocean-portfolio' ),
					'add_or_remove_items' 			=> esc_html__( 'Add or remove', 'ocean-portfolio' ),
					'choose_from_most_used' 		=> esc_html__( 'Choose from the most used', 'ocean-portfolio' ),
				),
				'public' 							=> true,
				'show_in_nav_menus' 				=> true,
				'show_ui' 							=> true,
				'show_tagcloud' 					=> true,
				'hierarchical' 						=> false,
				'rewrite' 							=> array( 'slug' => $slug, 'with_front' => false ),
				'query_var' 						=> true,
			) );

			// Register the tag taxonomy
			register_taxonomy( 'ocean_portfolio_tag', array( 'ocean_portfolio' ), $args );

		}

		/**
		 * Register shortcodes post type
		 *
		 * @since 1.0.0
		 */
		public static function shortcodes_post_type() {

			register_post_type( 'portfolio_shortcodes', apply_filters( 'ocean_portfolio_shortcodes_args', array(
				'labels' => array(
					'name' 					=> esc_html__( 'Shortcodes', 'ocean-portfolio' ),
					'singular_name' 		=> esc_html__( 'Shortcode', 'ocean-portfolio' ),
				),
				'public' 					=> true,
				'hierarchical'          	=> false,
				'show_ui'               	=> true,
				'show_in_menu' 				=> 'edit.php?post_type=ocean_portfolio',
				'show_in_admin_bar'     	=> false,
				'show_in_nav_menus'     	=> false,
				'can_export'            	=> true,
				'has_archive'           	=> false,		
				'exclude_from_search'   	=> true,
				'publicly_queryable'    	=> false,
				'capability_type' 			=> 'post',
				'menu_position' 			=> 20,
				'supports' 					=> array( 'title' ),
			) ) );

		}

		/**
		 * Add categories and tags columns
		 *
		 * @since 1.0.0
		 */
		public static function edit_columns( $columns ) {
			if ( taxonomy_exists( 'ocean_portfolio_category' ) ) {
				$columns['ocean_portfolio_category'] = esc_html__( 'Categories', 'ocean-portfolio' );
			}
			if ( taxonomy_exists( 'ocean_portfolio_tag' ) ) {
				$columns['ocean_portfolio_tag']      = esc_html__( 'Tags', 'ocean-portfolio' );
			}
			return $columns;
		}

		/**
		 * Display the custom columns
		 *
		 * @since 1.0.0
		 */
		public static function custom_columns( $column, $post_id ) {

			switch ( $column ) :

				// Display the categories in the column view
				case 'ocean_portfolio_category':

					if ( $category_list = get_the_term_list( $post_id, 'ocean_portfolio_category', '', ', ', '' ) ) {
						echo $category_list;
					} else {
						echo '&mdash;';
					}

				break;

				// Display the tags in the column view
				case 'ocean_portfolio_tag':

					if ( $tag_list = get_the_term_list( $post_id, 'ocean_portfolio_tag', '', ', ', '' ) ) {
						echo $tag_list;
					} else {
						echo '&mdash;';
					}

				break;

			endswitch;

		}

		/**
		 * Adds categories filter to the portfolio admin
		 *
		 * @since 1.0.0
		 */
		public static function categories_filter() {
			global $typenow;

			if ( 'ocean_portfolio' == $typenow ) {

		        $filter = array( 'ocean_portfolio_category' );

		        foreach ( $filter as $tax_slug ) {

					if ( ! taxonomy_exists( $tax_slug ) ) {
						continue;
					}

					$current_tax 	= isset( $_GET[ $tax_slug ] ) ? esc_html( $_GET[ $tax_slug ] ) : false;
					$tax_obj 		= get_taxonomy( $tax_slug );
					$tax_name 		= $tax_obj->labels->name;
					$terms 			= get_terms( $tax_slug );

					if ( count( $terms ) > 0 ) {
						echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
						echo "<option value=''>$tax_name</option>";
						foreach ( $terms as $term ) {
							echo '<option value=' . $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
						}
						echo "</select>";
					}

		        }

		    }

		}

		/**
		 * Add the OceanWP Settings metabox into the custom post type
		 *
		 * @since 1.0.0
		 */
		public static function add_metabox( $types ) {
			$types[] = 'ocean_portfolio';
			return $types;
		}

	}

}
new OceanWP_Portfolio_Post_Type();