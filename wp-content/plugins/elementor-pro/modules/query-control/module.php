<?php
namespace ElementorPro\Modules\QueryControl;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use ElementorPro\Base\Module_Base;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementorPro\Modules\QueryControl\Controls\Query;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	const QUERY_CONTROL_ID = 'query';

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * @param Widget_Base $widget
	 */
	public static function add_exclude_controls( $widget ) {
		$widget->add_control(
			'exclude',
			[
				'label' => __( 'Exclude', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'current_post' => __( 'Current Post', 'elementor-pro' ),
					'manual_selection' => __( 'Manual Selection', 'elementor-pro' ),
				],
				'label_block' => true,
			]
		);

		$widget->add_control(
			'exclude_ids',
			[
				'label' => _x( 'Search & Select', 'Posts Query Control', 'elementor-pro' ),
				'type' => Module::QUERY_CONTROL_ID,
				'post_type' => '',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'filter_type' => 'by_id',
				'condition' => [
					'exclude' => 'manual_selection',
				],
			]
		);
	}

	public function get_name() {
		return 'query-control';
	}

	public function ajax_posts_filter_autocomplete() {
		Plugin::elementor()->editor->verify_ajax_nonce();

		if ( empty( $_POST['filter_type'] ) || empty( $_POST['q'] ) ) {
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$results = [];

		if ( 'taxonomy' === $_POST['filter_type'] ) {
			$query_params = [
				'taxonomy' => $_POST['object_type'],
				'search' => $_POST['q'],
			];

			$terms = get_terms( $query_params );

			foreach ( $terms as $term ) {
				$results[] = [
					'id' => $term->term_id,
					'text' => $term->name,
				];
			}
		} elseif ( 'by_id' === $_POST['filter_type'] ) {
			$query_params = [
				'post_type' => $_POST['object_type'],
				's' => $_POST['q'],
				'posts_per_page' => -1,
			];

			$query = new \WP_Query( $query_params );

			foreach ( $query->posts as $post ) {
				$results[] = [
					'id' => $post->ID,
					'text' => $post->post_title,
				];
			}
		} elseif ( 'author' === $_POST['filter_type'] ) {
			$query_params = [
				'who' => 'authors',
				'has_published_posts' => true,
				'fields' => [
					'ID',
					'display_name',
				],
				'search' => '*' . $_POST['q'] . '*',
				'search_columns' => [
					'user_login',
					'user_nicename',
				],
			];

			$user_query = new \WP_User_Query( $query_params );

			foreach ( $user_query->get_results() as $author ) {
				$results[] = [
					'id' => $author->ID,
					'text' => $author->display_name,
				];
			}
		}

		wp_send_json_success(
			[
				'results' => $results,
			]
		);
	}

	public function ajax_posts_control_value_titles() {
		Plugin::elementor()->editor->verify_ajax_nonce();

		$ids = (array) $_POST['value'];

		$results = [];

		if ( 'taxonomy' === $_POST['filter_type'] ) {

			$terms = get_terms(
				[
					'include' => $ids,
				]
			);

			foreach ( $terms as $term ) {
				$results[ $term->term_id ] = $term->name;
			}
		} elseif ( 'by_id' === $_POST['filter_type'] ) {
			$query = new \WP_Query(
				[
					'post_type' => 'any',
					'post__in' => $ids,
					'posts_per_page' => -1,
				]
			);

			foreach ( $query->posts as $post ) {
				$results[ $post->ID ] = $post->post_title;
			}
		} elseif ( 'author' === $_POST['filter_type'] ) {
			$query_params = [
				'who' => 'authors',
				'has_published_posts' => true,
				'fields' => [
					'ID',
					'display_name',
				],
				'include' => $ids,
			];

			$user_query = new \WP_User_Query( $query_params );

			foreach ( $user_query->get_results() as $author ) {
				$results[ $author->ID ] = $author->display_name;
			}
		}

		wp_send_json_success( $results );
	}

	public function register_controls() {
		$controls_manager = Plugin::elementor()->controls_manager;

		$controls_manager->add_group_control( Group_Control_Posts::get_type(), new Group_Control_Posts() );

		$controls_manager->register_control( self::QUERY_CONTROL_ID, new Query() );
	}

	public static function get_query_args( $control_id, $settings ) {
		$defaults = [
			$control_id . '_post_type' => 'post',
			$control_id . '_posts_ids' => [],
			'orderby' => 'date',
			'order' => 'desc',
			'posts_per_page' => 3,
			'offset' => 0,
		];

		$settings = wp_parse_args( $settings, $defaults );

		$post_type = $settings[ $control_id . '_post_type' ];

		$query_args = [
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish', // Hide drafts/private posts for admins
		];

		if ( 'by_id' === $post_type ) {
			$query_args['post_type'] = 'any';
			$query_args['post__in']  = $settings[ $control_id . '_posts_ids' ];

			if ( empty( $query_args['post__in'] ) ) {
				// If no selection - return an empty query
				$query_args['post__in'] = [ 0 ];
			}
		} else {
			$query_args['post_type'] = $post_type;
			$query_args['posts_per_page'] = $settings['posts_per_page'];
			$query_args['tax_query'] = [];

			if ( 0 < $settings['offset'] ) {
				/**
				 * Due to a WordPress bug, the offset will be set later, in $this->fix_query_offset()
				 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
				 */
				$query_args['offset_to_fix'] = $settings['offset'];
			}

			$taxonomies = get_object_taxonomies( $post_type, 'objects' );

			foreach ( $taxonomies as $object ) {
				$setting_key = $control_id . '_' . $object->name . '_ids';

				if ( ! empty( $settings[ $setting_key ] ) ) {
					$query_args['tax_query'][] = [
						'taxonomy' => $object->name,
						'field' => 'term_id',
						'terms' => $settings[ $setting_key ],
					];
				}
			}
		}

		if ( ! empty( $settings[ $control_id . '_authors' ] ) ) {
			$query_args['author__in'] = $settings[ $control_id . '_authors' ];
		}

		if ( ! empty( $settings['exclude'] ) ) {
			$post__not_in = [];
			if ( in_array( 'current_post', $settings['exclude'] ) ) {
				if ( Utils::is_ajax() && ! empty( $_REQUEST['post_id'] ) ) {
					$post__not_in[] = $_REQUEST['post_id'];
				} elseif ( is_singular() ) {
					$post__not_in[] = get_queried_object_id();
				}
			}

			if ( in_array( 'manual_selection', $settings['exclude'] ) && ! empty( $settings['exclude_ids'] ) ) {
				$post__not_in = array_merge( $post__not_in, $settings['exclude_ids'] );
			}

			$query_args['post__not_in'] = $post__not_in;
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
		add_action( 'wp_ajax_elementor_pro_panel_posts_control_filter_autocomplete', [ $this, 'ajax_posts_filter_autocomplete' ] );
		add_action( 'wp_ajax_elementor_pro_panel_posts_control_value_titles', [ $this, 'ajax_posts_control_value_titles' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		/**
		 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
		 */
		add_action( 'pre_get_posts', [ $this, 'fix_query_offset' ], 1 );
		add_filter( 'found_posts', [ $this, 'fix_query_found_posts' ], 1, 2 );
	}
}
