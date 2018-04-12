<?php
namespace ElementorExtras\Compatibility;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elemento Extras WPML Compatibility
 *
 * Registers translatable widgets
 *
 * @since 1.8.8
 */
class WPML {

	/**
	 * @since 1.8.8
	 * @var Object
	 */
	public static $instance = null;

	/**
	 * Returns the class instance
	 * 
	 * @since 1.8.8
	 *
	 * @return Object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for the class
	 *
	 * @since 1.8.8
	 *
	 * @return void
	 */
	public function __construct() {

		// WPML String Translation plugin exist check
		if ( is_wpml_string_translation_active() ) {

			$this->includes();

			add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'add_translatable_nodes' ] );
		}
	}

	/**
	 * Include widget integration classes
	 *
	 * @since 1.8.8
	 *
	 * @return void
	 */
	public function includes() {
		elementor_extras_include( 'includes/compatibility/wpml/modules/buttons.php' );
		elementor_extras_include( 'includes/compatibility/wpml/modules/hotspots.php' );
		elementor_extras_include( 'includes/compatibility/wpml/modules/timeline.php' );
		elementor_extras_include( 'includes/compatibility/wpml/modules/table.php' );
		elementor_extras_include( 'includes/compatibility/wpml/modules/switcher.php' );
	}

	/**
	 * Adds additional translatable nodes to WPML
	 *
	 * @since 1.8.8
	 *
	 * @param  array   $nodes_to_translate WPML nodes to translate
	 * @return array   $nodes_to_translate Updated nodes
	 */
	public function add_translatable_nodes( $nodes_to_translate ) {

		$nodes_to_translate[ 'ee-breadcrumbs' ] = [
			'conditions' 		=> [ 'widgetType' => 'ee-breadcrumbs' ],
			'fields'     		=> [
				[
					'field'       => 'home_text',
					'type'        => esc_html__( 'Breadcrumbs: Home Text', 'elementor-extras' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'button-group' ] = [
			'conditions' 		=> [ 'widgetType' => 'button-group' ],
			'fields'     		=> [],
			'integration-class' => '\ElementorExtras\Compatibility\WPML\Buttons',
		];

		$nodes_to_translate[ 'circle-progress' ] = [
			'conditions' 		=> [ 'widgetType' => 'circle-progress' ],
			'fields'     		=> [
				[
					'field'       => 'suffix',
					'type'        => __( 'Circle Progress: Suffix', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'text',
					'type'        => __( 'Circle Progress: Content', 'elementor-extras' ),
					'editor_type' => 'VISUAL'
				],
			],
		];

		$nodes_to_translate[ 'heading-extended' ] = [
			'conditions' 		=> [ 'widgetType' => 'heading-extended' ],
			'fields'     		=> [
				[
					'field'       => 'title',
					'type'        => __( 'Heading Extra: Title', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'text-divider' ] = [
			'conditions' 		=> [ 'widgetType' => 'text-divider' ],
			'fields'     		=> [
				[
					'field'       => 'text',
					'type'        => __( 'Text Divider: Heading', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'hotspots' ] = [
			'conditions' 		=> [ 'widgetType' => 'hotspots' ],
			'fields'     		=> [],
			'integration-class' => '\ElementorExtras\Compatibility\WPML\Hotspots',
		];

		$nodes_to_translate[ 'image-comparison' ] = [
			'conditions' 		=> [ 'widgetType' => 'image-comparison' ],
			'fields'     		=> [
				[
					'field'       => 'original_label',
					'type'        => __( 'Image Comparison: Original Label', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'modified_label',
					'type'        => __( 'Image Comparison: Modified Label', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'posts-extra' ] = [
			'conditions' 		=> [ 'widgetType' => 'posts-extra' ],
			'fields'     		=> [
				[
					'field'       => 'classic_filters_all_text',
					'type'        => __( 'Posts Extra: Filter All Text', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'timeline' ] = [
			'conditions' 		=> [ 'widgetType' => 'timeline' ],
			'fields'     		=> [],
			'integration-class' => '\ElementorExtras\Compatibility\WPML\Timeline',
		];

		$nodes_to_translate[ 'ee-switcher' ] = [
			'conditions' 		=> [ 'widgetType' => 'ee-switcher' ],
			'fields'     		=> [],
			'integration-class' => '\ElementorExtras\Compatibility\WPML\Switcher',
		];

		$nodes_to_translate[ 'table' ] = [
			'conditions' 		=> [ 'widgetType' => 'table' ],
			'fields'     		=> [],
			'integration-class' => '\ElementorExtras\Compatibility\WPML\Table',
		];

		$nodes_to_translate[ 'unfold' ] = [
			'conditions' 		=> [ 'widgetType' => 'unfold' ],
			'fields'     		=> [
				[
					'field'       => 'content',
					'type'        => __( 'Unfold: Content', 'elementor-extras' ),
					'editor_type' => 'VISUAL'
				],
				[
					'field'       => 'text_closed',
					'type'        => __( 'Unfold: Open Button Label', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'text_open',
					'type'        => __( 'Unfold: Closed Button Label', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'devices' ] = [
			'conditions' 		=> [ 'widgetType' => 'devices' ],
			'fields'     		=> [
				[
					'field'       => 'video_url',
					'type'        => __( 'Devices: MP4 URL', 'elementor-extras' ),
					'editor_type' => 'VISUAL'
				],
				[
					'field'       => 'video_url_webm',
					'type'        => __( 'Devices: Webm URL', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'video_url_ogg',
					'type'        => __( 'Devices: OGG URL', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'video_url_webm',
					'type'        => __( 'Devices: M4V URL', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'html5-video' ] = [
			'conditions' 		=> [ 'widgetType' => 'html5-video' ],
			'fields'     		=> [
				[
					'field'       => 'video_url',
					'type'        => __( 'HTML5 Video: MP4 URL', 'elementor-extras' ),
					'editor_type' => 'VISUAL'
				],
				[
					'field'       => 'video_url_webm',
					'type'        => __( 'HTML5 Video: Webm URL', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'video_url_ogg',
					'type'        => __( 'HTML5 Video: OGG URL', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'video_url_webm',
					'type'        => __( 'HTML5 Video: M4V URL', 'elementor-extras' ),
					'editor_type' => 'LINE'
				],
			],
		];

		return $nodes_to_translate;
	}

	/**
	 * Returns the class instance.
	 *
	 * @since 1.8.8
	 *
	 * @return Object
	 */
	public static function get_instance() {
		
		if ( null == self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}