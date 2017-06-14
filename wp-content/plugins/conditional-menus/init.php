<?php
/*
Plugin Name:  Conditional Menus
Plugin URI:   http://themify.me/conditional-menus
Version:      1.0.8
Author:       Themify
Author URI:   http://themify.me/
Description:  This plugin enables you to set conditional menus per posts, pages, categories, archive pages, etc.
Text Domain:  themify-cm
Domain Path:  /languages
License:      GNU General Public License v2.0
License URI:  http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */

if ( !defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, array( 'Themify_Conditional_Menus', 'activate' ) );

class Themify_Conditional_Menus {

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
		add_action( 'plugins_loaded', array( $this, 'setup' ), 10 );
		add_action( 'wpml_after_startup', array( $this, 'wpml_after_startup' ) );
	}

	public function constants() {
		if( ! defined( 'THEMIFY_CM_DIR' ) )
			define( 'THEMIFY_CM_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		if( ! defined( 'THEMIFY_CM_URI' ) )
			define( 'THEMIFY_CM_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		if( ! defined( 'THEMIFY_CM_VERSION' ) )
			define( 'THEMIFY_CM_VERSION', '1.0.1' );
	}

	public function i18n() {
		load_plugin_textdomain( 'themify-cm', false, '/languages' );
	}

	public function setup() {
		if( is_admin() ) {
			add_action( 'load-nav-menus.php', array( $this, 'init' ) );
			add_action( 'after_menu_locations_table', array( $this, 'conditions_dialog' ) );
			add_filter( 'themify_cm_conditions_post_types', array( $this, 'exclude_attachments_from_conditions' ) );
			add_action( 'wp_ajax_themify_cm_get_conditions', array( $this, 'ajax_get_conditions' ) );
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'activation_redirect' ) );
			add_action( 'wp_delete_nav_menu', array( $this, 'wp_delete_nav_menu' ) );
		} else {
			add_filter( 'wp_nav_menu_args', array( $this, 'setup_menus' ) );
			add_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod_nav_menu_locations' ) );
		}
	}

	public function get_options() {
		remove_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod_nav_menu_locations' ) );
		$options = get_theme_mod( 'themify_conditional_menus', array() );
		$options = wp_parse_args( $options, get_nav_menu_locations() );
		if( ! is_admin() ) {
			add_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod_nav_menu_locations' ) );
		}

		return $options;
	}

	public function theme_mod_nav_menu_locations( $locations = array() ) {
		if( ! empty( $locations ) ) {
			$menu_assignments = $this->get_options();
						
			foreach( $locations as $location => $menu_id ) {
				if( is_array( $menu_assignments[$location] ) && ! empty( $menu_assignments[$location] ) ) {
					foreach( $menu_assignments[$location] as $id => $new_menu ) {
						if( $new_menu['menu'] == '' ) {
							continue;
						}
						if( $this->check_visibility( $new_menu['condition'] ) ) {
							if( $new_menu[ 'menu' ] == 0 ) {
								unset( $locations[$location] );
							} else {
								$locations[$location] = $new_menu[ 'menu' ];
							}
							continue;
						}
					}
				}
			}
		}

		return $locations;
	}

	public function setup_menus( $args ) {
		$menu_assignments = $this->get_options();
		$location = $args['theme_location'];
		if( isset( $args['theme_location'] ) && ! empty( $args['theme_location'] ) && isset( $menu_assignments[$args['theme_location']] ) ) {
			if( is_array( $menu_assignments[$args['theme_location']] ) && ! empty( $menu_assignments[$args['theme_location']] ) ) {
				foreach( $menu_assignments[$args['theme_location']] as $id => $new_menu ) {
					if( $new_menu['menu'] == '' ) {
						continue;
					}
					if( $this->check_visibility( $new_menu['condition'] ) ) {
						if( $new_menu[ 'menu' ] == 0 ) {
							add_filter( 'pre_wp_nav_menu', array( $this, 'disable_menu' ), 10, 2 );
							$args['echo'] = false;
						} else {
							$args['menu'] = $new_menu[ 'menu' ];
							/* reset theme_location arg, add filter for 3rd party plugins */
							$args['theme_location'] = apply_filters( 'conditional_menus_theme_location', '', $new_menu, $args );
						}
						continue;
					}
				}
			}
		}

		return $args;
	}

	public function disable_menu( $output, $args ) {
		remove_filter( 'pre_wp_nav_menu', array( $this, 'disable_menu' ), 10, 2 );
		return '';
	}

	public function init() {
		if( isset( $_GET['action'] ) && 'locations' == $_GET['action'] ) {
			$this->save_options();
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue' ) );
		}
	}

	public function save_options() {
		if( isset( $_POST['menu-locations'] ) ) {
			set_theme_mod( 'themify_conditional_menus', $_POST['themify_cm'] );
		}
	}

	public function ajax_get_conditions() {
		$selected = array();
		if( isset( $_POST['selected'] ) ) {
			parse_str( $_POST['selected'], $selected );
		}
		echo $this->get_visibility_options( $selected );
		die;
	}

	public function admin_enqueue() {
		global $_wp_registered_nav_menus;

		wp_enqueue_style( 'themify-conditional-menus', THEMIFY_CM_URI . 'assets/admin.css', null, THEMIFY_CM_VERSION );
		wp_enqueue_script( 'themify-conditional-menus', THEMIFY_CM_URI . 'assets/admin.js', array( 'jquery', 'jquery-ui-tabs' ), THEMIFY_CM_VERSION, true );
		wp_localize_script( 'themify-conditional-menus', 'themify_cm', array(
			'nav_menus' => array_keys( $_wp_registered_nav_menus ),
			'options' => $this->get_options(),
			'lang' => array(
				'conditions' => __( '+ Conditions', 'themify-cm' ),
				'add_assignment' => __( '+ Conditional Menu', 'themify-cm' ),
				'disable_menu' => __( 'Disable Menu', 'themify-cm' ),
			),
		) );
	}

	public function get_conditions_dialog() {
		$output = '
			<div id="themify-cm-conditions" class="clearfix" style="display: none;">
				<h3 class="themify-cm-title">' . __( 'Condition', 'themify-cm' ) . '</h3>
				<a href="#" id="themify-cm-close">x</a>
				<div class="lightbox_container">
				</div>
				<a href="#" class="button uncheck-all">'. __( 'Uncheck All', 'themify-cm' ) .'</a>
				<a href="#" class="button button-primary themify-cm-save alignright">' . __( 'Save', 'themify-cm' ) . '</a>
			</div>
			<div id="themify-cm-overlay"></div>
		';

		return $output;
	}

	public function conditions_dialog() {
		echo $this->get_conditions_dialog();
		echo '<span id="themify-cm-about">' . sprintf( __( 'About <a href="%s">Conditional Menus</a>', 'themify-cm' ), admin_url( 'admin.php?page=conditional-menus' ) ) . '</span>';
	}

	function exclude_attachments_from_conditions( $post_types ) {
		unset( $post_types['attachment'] );
		return $post_types;
	}

	/**
	 * Check if an item is visible for the current context
	 *
	 * @return bool
	 */
	public function check_visibility( $logic ) {
		$visible = true;
		parse_str( $logic, $logic );
		$query_object = get_queried_object();

		// Logged-in check
		if( isset( $logic['general']['logged'] ) ) {
			if( ! is_user_logged_in() ) {
				return false;
			}
			unset( $logic['general']['logged'] );
			if( empty( $logic['general'] ) ) {
				unset( $logic['general'] );
			}
		}

		// User role check
		if( ! empty( $logic['roles'] ) ) {
			if( ! in_array( $GLOBALS['current_user']->roles[0] , array_keys( $logic['roles'] ) ) ) {
				return false; // bail early.
			}
		}
		unset( $logic['roles'] );

		if( ! empty( $logic ) ) {
			$visible = false; // if any condition is set for a hook, hide it on all pages of the site except for the chosen ones.
			$shop = $this->is_wc_shop();
			if( ( is_front_page() && isset( $logic['general']['home'] ) )
				|| ( (is_page() || $shop) && isset( $logic['general']['page'] ) && ! is_front_page() )
				|| ( is_single() && isset( $logic['general']['single'] ) )
				|| ( is_search() && isset( $logic['general']['search'] ) )
				|| ( is_author() && isset( $logic['general']['author'] ) )
				|| ( is_category() && isset( $logic['general']['category'] ) )
				|| ( is_tag() && isset($logic['general']['tag']) )
				|| ( is_date() && isset($logic['general']['date']) )
				|| ( is_year() && isset($logic['general']['year']) )
				|| ( is_month() && isset($logic['general']['month']) )
				|| ( is_day() && isset($logic['general']['day']) )
				|| ( is_singular() && isset( $logic['general'][$query_object->post_type] ) && $query_object->post_type != 'page' && $query_object->post_type != 'post' )
				|| ( is_tax() && isset( $logic['general'][$query_object->taxonomy] ) )
			) {
				$visible = true;
			} else { // let's dig deeper into more specific visibility rules
				if( ! empty( $logic['tax'] ) ) {
					if(is_singular()){
						if(isset($logic['tax']['category_single']) && !empty($logic['tax']['category_single'])){
							$cat = get_the_category();
							if(!empty($cat)){
								foreach($cat as $c){
									if($c->taxonomy == 'category' && isset($logic['tax']['category_single'][$c->slug])){
										$visible = true;
										break;
									}
								}
							}
						}
					} else {
						foreach( $logic['tax'] as $tax => $terms ) {
							$terms = array_keys( $terms );
							if( ( $tax == 'category' && is_category( $terms ) )
								|| ( $tax == 'post_tag' && is_tag( $terms ) )
								|| ( is_tax( $tax, $terms ) )
							) {
								$visible = true;
								break;
							}
						}
					}
				}

				if( ! $visible && ! empty( $logic['post_type'] ) ) {

					$slug = $shop ? $this->get_wc_shop() : $query_object->post_name;

					foreach( $logic['post_type'] as $post_type => $posts ) {
						$posts = array_keys( $posts );
						if( $post_type == 'page' && isset( $query_object->post_parent ) && $query_object->post_parent > 0 ) {
						   $slug = str_replace( site_url(), '', get_permalink( $query_object->ID ) );
						}

						if( ( $post_type == 'post' && is_single() && is_single( $posts ) )
							|| ( $post_type == 'page' && ( is_page( $posts ) || ( ! is_front_page() && is_home() &&  in_array( get_post_field( 'post_name', get_option( 'page_for_posts' ) ), $posts ) ) // check for Posts page
							) )
							|| ( (is_singular( $post_type ) || $shop) && in_array($slug, $posts ) )
						) {
							$visible = true;
							break;
						}
					}
				}
			}
		}

		return $visible;
	}

	public function is_wc_shop(){
		return ! class_exists( 'WooCommerce' ) ? false : is_shop();
	}
	
	public function get_wc_shop(){
		if( !class_exists( 'WooCommerce' ) ) {
			return false;
		}
		$shop = get_post(wc_get_page_id( 'shop' ));
		return $shop->post_name;
	}

	public function get_visibility_options( $selected = array() ) {
		$post_types = apply_filters( 'themify_hooks_visibility_post_types', get_post_types( array( 'public' => true ) ) );
		unset( $post_types['page'] );
		$post_types = array_map( 'get_post_type_object', $post_types );

		$taxonomies = apply_filters( 'themify_hooks_visibility_taxonomies', get_taxonomies( array( 'public' => true ) ) );
		unset( $taxonomies['category'] );
		$taxonomies = array_map( 'get_taxonomy', $taxonomies );

		$output = '<form id="visibility-tabs" class="ui-tabs"><ul class="clearfix">';

		/* build the tab links */
		$output .= '<li><a href="#visibility-tab-general">' . __( 'General', 'themify-cm' ) . '</a></li>';
		$output .= '<li><a href="#visibility-tab-pages">' . __( 'Pages', 'themify-cm' ) . '</a></li>';
			$output .= '<li><a href="#visibility-tab-categories-singles">' . __( 'Category Singles', 'themify-cm' ) . '</a></li>';
		$output .= '<li><a href="#visibility-tab-categories">' . __( 'Categories', 'themify-cm' ) . '</a></li>';
		$output .= '<li><a href="#visibility-tab-post-types">' . __( 'Post Types', 'themify-cm' ) . '</a></li>';
		$output .= '<li><a href="#visibility-tab-taxonomies">' . __( 'Taxonomies', 'themify-cm' ) . '</a></li>';
		$output .= '<li><a href="#visibility-tab-userroles">' . __( 'User Roles', 'themify-cm' ) . '</a></li>';
		$output .= '</ul>';

		/* build the tab items */
		$output .= '<div id="visibility-tab-general" class="themify-visibility-options clearfix">';
			$checked = isset( $selected['general']['home'] ) ? checked( $selected['general']['home'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[home]" '. $checked .' />' . __( 'Home page', 'themify-cm' ) . '</label>';
			$checked = isset( $selected['general']['page'] ) ? checked( $selected['general']['page'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[page]" '. $checked .' />' . __( 'Page views', 'themify-cm' ) . '</label>';
			$checked = isset( $selected['general']['single'] ) ? checked( $selected['general']['single'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[single]" '. $checked .' />' . __( 'Single post views', 'themify-cm' ) . '</label>';
			$checked = isset( $selected['general']['search'] ) ? checked( $selected['general']['search'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[search]" '. $checked .' />' . __( 'Search pages', 'themify-cm' ) . '</label>';
			$checked = isset( $selected['general']['category'] ) ? checked( $selected['general']['category'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[category]" '. $checked .' />' . __( 'Category archive', 'themify-cm' ) . '</label>';
			$checked = isset( $selected['general']['tag'] ) ? checked( $selected['general']['tag'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[tag]" '. $checked .' />' . __( 'Tag archive', 'themify-cm' ) . '</label>';
			$checked = isset( $selected['general']['author'] ) ? checked( $selected['general']['author'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[author]" '. $checked .' />' . __( 'Author pages', 'themify-cm' ) . '</label>';
			$checked = isset($selected['general']['date']) ? checked($selected['general']['date'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[date]" ' . $checked . ' />' . __( 'Date archive pages', 'themify-cm' ) . '</label>';
			$checked = isset($selected['general']['year']) ? checked($selected['general']['year'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[year]" ' . $checked . ' />' . __( 'Year based archive', 'themify-cm' ) . '</label>';
			$checked = isset($selected['general']['month']) ? checked($selected['general']['month'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[month]" ' . $checked . ' />' . __( 'Month based archive', 'themify-cm' ) . '</label>';
			$checked = isset($selected['general']['day']) ? checked($selected['general']['day'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[day]" ' . $checked . ' />' . __( 'Day based archive', 'themify-cm' ) . '</label>';
			$checked = isset( $selected['general']['logged'] ) ? checked( $selected['general']['logged'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[logged]" '. $checked .' />' . __( 'User logged in', 'themify-cm' ) . '</label>';

			/* General views for CPT */
			foreach( get_post_types( array( 'public' => true, 'exclude_from_search' => false, '_builtin' => false ) ) as $key => $post_type ) {
				$post_type = get_post_type_object( $key );
				$checked = isset( $selected['general'][$key] ) ? checked( $selected['general'][$key], 'on', false ) : '';
				$output .= '<label><input type="checkbox" name="general['. $key .']" '. $checked .' />' . sprintf( __( 'Single %s View', 'themify-cm' ), $post_type->labels->singular_name ) . '</label>';
			}

			/* Custom taxonomies archive view */
			foreach( get_taxonomies( array( 'public' => true, '_builtin' => false ) ) as $key => $tax ) {
				$tax = get_taxonomy( $key );
				$checked = isset( $selected['general'][$key] ) ? checked( $selected['general'][$key], 'on', false ) : '';
				$output .= '<label><input type="checkbox" name="general['. $key .']" '. $checked .' />' . sprintf( __( '%s Archive View', 'themify-cm' ), $tax->labels->singular_name ) . '</label>';
			}

		$output .= '</div>'; // tab-general
		   
		// Pages tab
		$output .= '<div id="visibility-tab-pages" class="themify-visibility-options clearfix">';
			$key = 'page';
			$posts = get_posts( array( 'post_type' => $key, 'posts_per_page' => -1, 'status' => 'published', 'order' => 'ASC', 'orderby' => 'title' ) );
			if( ! empty( $posts ) ) : foreach( $posts as $post ) :
				if($post->post_parent>0){
					 $post->post_name =  str_replace(site_url(),'',get_permalink($post->ID));
				}
				$checked = isset( $selected['post_type'][$key][$post->post_name] ) ? checked( $selected['post_type'][$key][$post->post_name], 'on', false ) : '';
				/* note: slugs are more reliable than IDs, they stay unique after export/import */
				$output .= '<label><input type="checkbox" name="post_type[' . $key . ']['. $post->post_name .']"'. $checked .' />' . $post->post_title . '</label>';
			endforeach; endif;
		$output .= '</div>'; // tab-pages
				
		// Category Singles tab
		$output .= '<div id="visibility-tab-categories-singles" class="themify-visibility-options clearfix">';
			$key = 'category_single';
			$terms = get_terms( 'category', array( 'hide_empty' => true ) );
			if( ! empty( $terms ) ) : foreach( $terms as $term ) :
				$checked = isset( $selected['tax'][$key][$term->slug] ) ? checked( $selected['tax'][$key][$term->slug], 'on', false ) : '';
				$output .= '<label><input type="checkbox" name="tax['. $key .']['. $term->slug .']" '. $checked .' />' . $term->name . '</label>';
			endforeach; endif;
		$output .= '</div>'; 
				//
		// Categories tab
		$output .= '<div id="visibility-tab-categories" class="themify-visibility-options clearfix">';
			$key = 'category';
			if( ! empty( $terms ) ) : foreach( $terms as $term ) :
				$checked = isset( $selected['tax'][$key][$term->slug] ) ? checked( $selected['tax'][$key][$term->slug], 'on', false ) : '';
				$output .= '<label><input type="checkbox" name="tax['. $key .']['. $term->slug .']" '. $checked .' />' . $term->name . '</label>';
			endforeach; endif;
		$output .= '</div>'; // tab-categories

		// Post types tab
		$output .= '<div id="visibility-tab-post-types" class="themify-visibility-options clearfix">';
			$output .= '<div id="themify-visibility-post-types-inner-tabs" class="themify-visibility-inner-tabs">';
			$output .= '<ul class="inline-tabs clearfix">';
				foreach( $post_types as $key => $post_type ) {
					$output .= '<li><a href="#visibility-tab-' . $key . '">' . $post_type->label . '</a></li>';
				}
			$output .= '</ul>';
			foreach( $post_types as $key => $post_type ) {
				$output .= '<div id="visibility-tab-' . $key . '" class="clearfix">';
				$posts = get_posts( array( 'post_type' => $key, 'posts_per_page' => -1, 'status' => 'published', 'order' => 'ASC', 'orderby' => 'title' ) );
				if( ! empty( $posts ) ) : foreach( $posts as $post ) :
					$checked = isset( $selected['post_type'][$key][$post->post_name] ) ? checked( $selected['post_type'][$key][$post->post_name], 'on', false ) : '';
					/* note: slugs are more reliable than IDs, they stay unique after export/import */
					$output .= '<label><input type="checkbox" name="post_type[' . $key . ']['. $post->post_name .']"'. $checked .' />' . $post->post_title . '</label>';
				endforeach; endif;
				$output .= '</div>';
			}
			$output .= '</div>';
		$output .= '</div>'; // tab-post-types

		// Taxonomies tab
		$output .= '<div id="visibility-tab-taxonomies" class="themify-visibility-options clearfix">';
			$output .= '<div id="themify-visibility-taxonomies-inner-tabs" class="themify-visibility-inner-tabs">';
			$output .= '<ul class="inline-tabs clearfix">';
				foreach( $taxonomies as $key => $tax ) {
					$output .= '<li><a href="#visibility-tab-' . $key . '">' . $tax->label . '</a></li>';
				}
			$output .= '</ul>';
			foreach( $taxonomies as $key => $tax ) {
				$output .= '<div id="visibility-tab-'. $key .'" class="clearfix">';
				$terms = get_terms( $key, array( 'hide_empty' => true ) );
				if( ! empty( $terms ) ) : foreach( $terms as $term ) :
					$checked = isset( $selected['tax'][$key][$term->slug] ) ? checked( $selected['tax'][$key][$term->slug], 'on', false ) : '';
					$output .= '<label><input type="checkbox" name="tax['. $key .']['. $term->slug .']" '. $checked .' />' . $term->name . '</label>';
				endforeach; endif;
				$output .= '</div>';
			}
			$output .= '</div>';
		$output .= '</div>'; // tab-taxonomies

		// User Roles tab
		$output .= '<div id="visibility-tab-userroles" class="themify-visibility-options clearfix">';
		foreach( $GLOBALS['wp_roles']->roles as $key => $role ) {
			$checked = isset( $selected['roles'][$key] ) ? checked( $selected['roles'][$key], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="roles['. $key .']" '. $checked .' />' . $role['name'] . '</label>';
		}
		$output .= '</div>'; // tab-userroles

		$output .= '</form>';
		return $output;
	}

	public function add_plugin_page() {
		add_menu_page(
			__( 'Themify Conditional Menus', 'themify-cm' ),
			__( 'Conditional Menus', 'themify-cm' ),
			'manage_options',
			'conditional-menus',
			array( $this, 'create_admin_page' )
		);
	}

	public function create_admin_page() {
		include( THEMIFY_CM_DIR . '/docs/index.html' );
	}

	public static function activate( $network_wide ) {
		If( version_compare( get_bloginfo( 'version' ), '3.9', ' < ' ) ) {
			/* the plugin requires at least 3.9 */
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate the plugin
		} else {
			if( ! $network_wide && ! isset( $_GET['activate-multi'] ) ) {
				add_option( 'themify_conditional_menus_activation_redirect', true );
			}
		}
	}

	public function activation_redirect() {
		if( get_option( 'themify_conditional_menus_activation_redirect', false ) ) {
			delete_option( 'themify_conditional_menus_activation_redirect' );
			wp_redirect( admin_url( 'admin.php?page=conditional-menus' ) );
		}
	}

	/**
	 * Disable WPML nav menu filtering in the Menu Locations manager
	 *
	 * @since 1.0.2
	 */
	public function wpml_after_startup() {
		global $pagenow;
		if( is_admin() && $pagenow === 'nav-menus.php' && isset( $_GET['action'] ) && 'locations' == $_GET['action'] ) {
			remove_all_filters( 'get_terms', 1 );
		}
	}

	/**
	 * Remove menu assignments when the menu gets deleted
	 *
	 * @since 1.0.7
	 */
	function wp_delete_nav_menu( $menu_id ) {
		$options = get_theme_mod( 'themify_conditional_menus', array() );
		if( ! empty( $options ) ) {
			foreach( $options as $location => $assignments ) {
				if( is_array( $assignments ) && ! empty( $assignments ) ) {
					foreach( $assignments as $key => $menu ) {
						if( $menu['menu'] == $menu_id ) {
							unset( $options[$location][$key] );
						}
					}
				}
			}
		}
		set_theme_mod( 'themify_conditional_menus', $options );
	}
}
$themify_cm = new Themify_Conditional_Menus;