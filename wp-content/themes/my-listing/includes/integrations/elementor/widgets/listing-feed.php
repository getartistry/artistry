<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Listing_Feed extends Widget_Base {

	public function get_name() {
		return 'case27-listing-feed-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Listing Feed', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-posts-grid';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		// $traits->header();
		// $traits->content();

		$this->start_controls_section(
			'the_listing_feed',
			['label' => esc_html__( 'Listing Feed', 'my-listing' ),]
		);

		$this->add_control(
			'the_template',
			[
				'label' => __( 'Template', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'grid',
				'options' => [
					'grid' => __( 'Grid', 'my-listing' ),
					'carousel' => __( 'Carousel', 'my-listing' ),
				],
				'multiple' => false,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => __( 'Number of items to show', 'my-listing' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_control(
			'select_categories',
			[
				'label' => __( 'Filter by Categories', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'options' => c27()->get_terms_dropdown_array([
					'taxonomy' => 'job_listing_category',
					'hide_empty' => false,
					]),
				'multiple' => true,
			]
		);

		$this->add_control(
			'select_listing_types',
			[
				'label' => __( 'Filter by Listing Type(s).', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'options' => c27()->get_posts_dropdown_array([
					'post_type' => 'case27_listing_type',
					'posts_per_page' => -1,
					], 'post_name'),
				'multiple' => true,
			]
		);

		$listing_count = wp_count_posts( 'job_listing', 'readable' )->publish;
		$this->add_control(
			'select_listings',
			[
				'label' => __( 'Or select a list of listings.', 'my-listing' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [[
					'name' => 'listing_id',
					'label' => $listing_count <= 100 ? __( 'Select listing', 'my-listing' ) : _x( 'Enter listing ID', 'Elementor/Listing Feed: Select a listing', 'my-listing' ),
					'type' => $listing_count <= 100 ? Controls_Manager::SELECT2 : Controls_Manager::TEXT,
					'options' => $listing_count <= 100 ? c27()->get_posts_dropdown_array( [
						'post_type' => 'job_listing',
						'posts_per_page' => -1,
						] ) : [],
				]],
				'title_field' => '{{{ listing_id }}}',
			]
		);

		$this->add_control(
			'order_by',
			[
				'label' => __( 'Order by', 'my-listing' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'my-listing' ),
					'post__in' => __( 'Included order', 'my-listing' ),
					'_case27_average_rating' => __( 'Rating', 'my-listing' ),
					'rand' => __( 'Random', 'my-listing' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'my-listing' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => __( 'Ascending', 'my-listing' ),
					'DESC' => __( 'Descending', 'my-listing' ),
				],
			]
		);

		$this->add_control(
			'behavior',
			[
				'label' => __( 'Listing behavior', 'my-listing' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'my-listing' ),
					'show_promoted_first' => __( 'Show promoted first', 'my-listing' ),
					'show_promoted_only' => __( 'Show promoted only', 'my-listing' ),
					'hide_promoted' => __( 'Hide promoted', 'my-listing' ),
				],
			]
		);

		$this->add_control(
			'show_promoted_badge',
			[
				'label' => __( 'Show badge for promoted listings?', 'my-listing' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'my-listing' ),
				'label_off' => __( 'No', 'my-listing' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'invert_nav_color',
			[
				'label' => __( 'Invert nav color?', 'my-listing' ),
				'description' => __( 'Use this option on dark section backgrounds for better visibility.', 'my-listing' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Yes', 'my-listing' ),
				'label_off' => __( 'No', 'my-listing' ),
				'return_value' => 'yes',
				'condition' => ['the_template' => 'carousel'],
			]
		);

		$traits->choose_columns('Column Count', 'column_count', [
			'heading' => ['condition' => ['the_template' => ['grid', 'fluid-grid']]],
			'general' => [
				'condition' => ['the_template' => ['grid', 'fluid-grid']],
				'min' => 1,
				'max' => 4,
			],
			'lg' => ['default' => 3], 'md' => ['default' => 3],
			'sm' => ['default' => 2], 'xs' => ['default' => 1],
		]);

		// Select Listings By: Default, Category(ies), Listing Type, Custom.

		// COUNT

		$this->end_controls_section();

		$traits->sizing();

		// $traits->footer();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('listing-feed', [
			'template' => $this->get_settings('the_template'),
			'columns' => [
				'lg' => $this->get_settings('column_count__lg'),
				'md' => $this->get_settings('column_count__md'),
				'sm' => $this->get_settings('column_count__sm'),
				'xs' => $this->get_settings('column_count__xs'),
			],
			'posts_per_page' => $this->get_settings('posts_per_page'),
			'category' => $this->get_settings('select_categories'),
			'listing_types' => $this->get_settings('select_listing_types'),
			'include' => array_filter( array_map( 'absint', array_column( (array) $this->get_settings('select_listings'), 'listing_id' ) ) ),
			'order_by' => $this->get_settings('order_by'),
			'order' => $this->get_settings('order'),
			'behavior' => $this->get_settings('behavior'),
			'show_promoted_badge' => $this->get_settings('show_promoted_badge'),
			'invert_nav_color' => $this->get_settings('invert_nav_color'),
			'is_edit_mode' => Plugin::$instance->editor->is_edit_mode(),
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Listing_Feed() );
