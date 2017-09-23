<?php

/**
 * Copyright 2017 Label Blanc (http://www.labelblanc.ca/)
 *
 * This file is part of the "Improved Save Button"
 * Wordpress plugin.
 *
 * The "Improved Save Button" Wordpress plugin
 * is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 'Save and duplicate' action: after saving the post,
 * duplicates it (as a draft) and redirects to the new post's
 * edit page.
 */
class LB_Save_And_Then_Action_Duplicate extends LB_Save_And_Then_Action {
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_name() {
		return _x('Save and Duplicate', 'Action name (used in settings page)', 'improved-save-button');
	}
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_id() {
		return 'labelblanc.duplicate';
	}
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_description() {
		return _x('<strong>Duplicates the current post</strong> (as a draft) after save and shows the duplicated post\'s edit page.', 'Action description (used in settings page)', 'improved-save-button');
	}
	
	/**
	 * @see LB_Save_And_Then_Action
	 */
	function get_button_label_pattern( $post ) {
		return _x('%s and Duplicate', 'Button label (used in post edit page). %s = "Publish" or "Update"', 'improved-save-button');
	}

	/**
	 * Duplicates the current post (as a draft) and returns
	 * the URL of the new post's edit page.
	 *
	 * Inspired by https://rudrastyh.com/wordpress/duplicate-post.html
	 *
	 * @see LB_Save_And_Then_Action
	 * @param  string $current_url
	 * @param  WP_Post $post
	 * @return string
	 */
	function get_redirect_url( $current_url, $post ) {
		$new_post = self::copy_post( $post );

		if( is_wp_error( $new_post ) ) {
			return $new_post;
		}

		self::copy_thumbnail( $post, $new_post );
		self::copy_taxonomies( $post, $new_post );
		self::copy_metas( $post, $new_post );

		$url_parts = LB_Save_And_Then_Utils::parse_url( $current_url );
		$params = $url_parts['query'];

		// Query params to add
		$params['post'] = $new_post->ID;
		$params['action'] = 'edit';
		$params[ LB_Save_And_Then_Messages::HTTP_PARAM_UPDATED_POST_ID ] = $post->ID;

		// Standard query params that are kept:
		// - message

		return LB_Save_And_Then_Utils::admin_url( 'post.php', $params );
	}

	/**
	 * Inserts a new post with the same values as the passed
	 * one. On success, returns the new post. If an error
	 * occured, a WP_Error is returned.
	 * 
	 * @param  WP_Post $post The post to copy
	 * @return WP_Post|WP_Error
	 */
	protected static function copy_post( $post ) {
		$insert_post_args = array(
			'post_content' => $post->post_content,
			'post_title' => $post->post_title . _x(' (copy)', 'Text added to the duplicated post\'s title (notice the space at the beginning).', 'improved-save-button'),
			'post_excerpt' => $post->post_excerpt,
			'post_status' => 'draft',
			'post_type' => $post->post_type,
			'comment_status' => $post->comment_status,
			'ping_status' => $post->ping_status,
			'post_password' => $post->post_password,
			'post_name' => '', // empty value allowed for draft posts
			'post_parent' => $post->post_parent,
			'menu_order' => $post->menu_order,
			'to_ping' => $post->to_ping,
		);

		$new_post_id = wp_insert_post( $insert_post_args, true );

		if( is_wp_error( $new_post_id) ) {
			return $new_post_id;
		}

		return get_post( $new_post_id );
	}

	/**
	 * Sets the thumbnail of the second post to the same as
	 * the first post.
	 *
	 * @param WP_Post $from_post
	 * @param WP_Post $to_post
	 */
	protected static function copy_thumbnail( $from_post, $to_post ) {
		// Set post thumbnail
		$post_thumbail_id = get_post_thumbnail_id( $from_post->ID );
		if( false != $post_thumbail_id && ! empty( $post_thumbail_id ) ) {
			$res = set_post_thumbnail( $to_post->ID, $post_thumbail_id );
		}
	}

	/**
	 * Copies all taxonomy terms from the first post to
	 * the second.
	 *
	 * @param WP_Post $from_post
	 * @param WP_Post $to_post
	 */
	protected static function copy_taxonomies( $from_post, $to_post ) {
		// wp_insert_post didn't allow to pass taxonomy terms
		// for custom post types, so we do it this way
		$taxonomies = get_object_taxonomies($from_post->post_type);
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($from_post->ID, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($to_post->ID, $post_terms, $taxonomy, false);
		}
	}

	/**
	 * Copies all meta pairs from the first post to
	 * the second.
	 *
	 * @param WP_Post $from_post
	 * @param WP_Post $to_post
	 */
	protected static function copy_metas( $from_post, $to_post ) {
		global $wpdb;

		// wp_insert_post allows to pass meta values, but since
		// we would have to make one SQL query for each meta,
		// we use those 2 SQL queries to copy them all.
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$from_post->ID");
		if ( count( $post_meta_infos ) != 0 ) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ( $post_meta_infos as $meta_info ) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes( $meta_info->meta_value );
				$sql_query_sel[]= "SELECT $to_post->ID, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
	}
}