<?php

namespace MyListing;

class User extends \WP_User {

	public function get_link() {
		if ( function_exists( 'bp_core_get_user_domain' ) ) {
			return bp_core_get_user_domain( $this->ID );
		}

		return get_author_posts_url( $this->ID );
	}

	public function get_avatar() {
		return get_avatar_url( $this->ID );
	}

	public function get_name() {
		return $this->display_name;
	}
}
