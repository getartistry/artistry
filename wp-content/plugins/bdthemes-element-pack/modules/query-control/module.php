<?php
namespace ElementPack\Modules\QueryControl;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use ElementPack\Base\Element_Pack_Module_Base;
use ElementPack\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementPack\Modules\QueryControl\Controls\Query;
use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	const QUERY_CONTROL_ID = 'query';

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'query-control';
	}

	public function register_controls() {
		$controls_manager = Element_Pack_Loader::elementor()->controls_manager;

		$controls_manager->add_group_control( Group_Control_Posts::get_type(), new Group_Control_Posts() );

		$controls_manager->register_control( self::QUERY_CONTROL_ID, new Query() );
	}

	public static function get_query_args( $control_id, $settings ) {
		$defaults = [
			$control_id . '_post_type' => 'post',
			$control_id . '_posts_ids' => [],
			'orderby'                  => 'date',
			'order'                    => 'desc',
			'posts_per_page'           => 3,
			'offset'                   => 0,
		];


		$settings = wp_parse_args( $settings, $defaults );

		$post_type = $settings[ $control_id . '_post_type' ];

		$query_args = [
			'orderby'             => $settings['orderby'],
			'order'               => $settings['order'],
			'ignore_sticky_posts' => 1,
			'post_status'         => 'publish', // Hide drafts/private posts for admins
		];

		if ( 'by_id' === $post_type ) {
			$query_args['post_type'] = 'any';
			$query_args['post__in']  = $settings[ $control_id . '_posts_ids' ];

			if ( empty( $query_args['post__in'] ) ) {
				// If no selection - return an empty query
				$query_args['post__in'] = [ 0 ];
			}
		} else {
			$query_args['post_type']      = $post_type;
			$query_args['posts_per_page'] = $settings['posts_per_page'];
			$query_args['tax_query']      = [];

			if ( 0 < $settings['offset'] ) {
				$query_args['offset_to_fix'] = $settings['offset'];
			}

			$taxonomies = get_object_taxonomies( $post_type, 'objects' );

			foreach ( $taxonomies as $object ) {
				$setting_key = $control_id . '_' . $object->name . '_ids';

				if ( ! empty( $settings[ $setting_key ] ) ) {
					$query_args['tax_query'][] = [
						'taxonomy' => $object->name,
						'field'    => 'term_id',
						'terms'    => $settings[ $setting_key ],
					];
				}
			}
		}

		return $query_args;
	}

	/**
	 * @param \WP_Query $query
	 */
	function fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] -1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	/**
	 * @param int $found_posts
	 * @param \WP_Query $query
	 *
	 * @return mixed
	 */
	function fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'fix_pagination_offset' );

		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}


	protected function add_actions() {
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		add_action( 'pre_get_posts', [ $this, 'fix_query_offset' ], 1 );
		add_filter( 'found_posts', [ $this, 'fix_query_found_posts' ], 1, 2 );
	}
}

