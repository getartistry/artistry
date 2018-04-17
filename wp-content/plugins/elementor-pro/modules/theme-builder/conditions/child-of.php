<?php
namespace ElementorPro\Modules\ThemeBuilder\Conditions;

use ElementorPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Child_Of extends Condition_Base {

	public static function get_type() {
		return 'singular';
	}

	public function get_name() {
		return 'child_of';
	}

	public function get_label() {
		return __( 'Child Of', 'elementor-pro' );
	}

	public function check( $args ) {
		$id = (int) $args['id'];
		$parent_id = wp_get_post_parent_id( get_the_ID() );

		return ( ! $id && 0 < $parent_id ) || ( $parent_id === $id );
	}

	protected function _register_controls() {
		$this->add_control(
			'parent_id',
			[
				'section' => 'settings',
				'type' => QueryModule::QUERY_CONTROL_ID,
				'multiple' => false,
				'filter_type' => 'post',
				'object_type' => 'page',
			]
		);
	}
}
