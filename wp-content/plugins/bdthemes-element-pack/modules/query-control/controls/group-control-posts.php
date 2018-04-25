<?php
namespace ElementPack\Modules\QueryControl\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;
use ElementPack\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Group_Control_Posts extends Group_Control_Base {

	const INLINE_MAX_RESULTS = 150;

	protected static $fields;

	public static function get_type() {
		return 'posts';
	}

	public static function on_export_remove_setting_from_element( $element, $control_id ) {
		unset( $element['settings'][ $control_id . '_posts_ids' ] );
		unset( $element['settings'][ $control_id . '_authors' ] );

		foreach ( self::get_post_types() as $post_type => $label ) {
			$taxonomy_filter_args = [
				'show_in_nav_menus' => true,
				'object_type' => [ $post_type ],
			];

			$taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

			foreach ( $taxonomies as $taxonomy => $object ) {
				unset( $element['settings'][ $control_id . '_' . $taxonomy . '_ids' ] );
			}
		}

		return $element;
	}

	protected function init_fields() {
		$fields = [];

		$fields['post_type'] = [
			'label' => _x( 'Source', 'Posts Query Control', 'bdthemes-element-pack' ),
			'type'  => Controls_Manager::SELECT,
		];

		return $fields;
	}

	protected function prepare_fields( $fields ) {
		$args = $this->get_args();

		$post_types = self::get_post_types( $args );

		$post_types_options = $post_types;

		$fields['post_type']['options'] = $post_types_options;

		$fields['post_type']['default'] = key( $post_types );

		//$fields['posts_ids']['object_type'] = array_keys( $post_types );

		$taxonomy_filter_args = [
			'show_in_nav_menus' => true,
		];

		if ( ! empty( $args['post_type'] ) ) {
			$taxonomy_filter_args['object_type'] = [ $args['post_type'] ];
		}

		$taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

		foreach ( $taxonomies as $taxonomy => $object ) {
			$taxonomy_args = [
				'label'       => $object->label,
				'type'        => Module::QUERY_CONTROL_ID,
				'label_block' => true,
				'multiple'    => true,
				'object_type' => $taxonomy,
				'options'     => [],
				'condition'   => [
					'post_type' => $object->object_type,
				],
			];

			$count = wp_count_terms( $taxonomy );

			$options = [];

			
			$taxonomy_args['type'] = Controls_Manager::SELECT2;

			$terms = get_terms( $taxonomy );

			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}

			$taxonomy_args['options'] = $options;
		
			$fields[ $taxonomy . '_ids' ] = $taxonomy_args;
		}

		return parent::prepare_fields( $fields );
	}

	private static function get_post_types( $args = [] ) {
		$post_type_args = [
			'show_in_nav_menus' => true,
		];

		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
		}

		$_post_types = get_post_types( $post_type_args , 'objects' );

		$post_types  = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
