<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CASE27_Elementor_Widget_Blog_Feed extends Widget_Base {

	public function get_name() {
		return 'case27-blog-feed-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Blog Feed', 'my-listing' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-posts-masonry';
	}

	protected function _register_controls() {
		$traits = new \CASE27_Elementor_Traits($this);

		$this->start_controls_section(
			'the_blog_feed',
			['label' => esc_html__( 'Blog Feed', 'my-listing' ),]
		);

		$this->add_control(
			'the_template',
			[
				'label' => __( 'Template', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'default' => 'col3',
				'options' => [
					'col2' => __( 'Two Columns', 'my-listing' ),
					'col3' => __( 'Three Columns', 'my-listing' ),
				],
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
					'taxonomy' => 'category',
					'hide_empty' => false,
					]),
				'multiple' => true,
			]
		);

		$this->add_control(
			'select_posts',
			[
				'label' => __( 'Filter by Post.', 'my-listing' ),
				'type' => Controls_Manager::SELECT2,
				'options' => c27()->get_posts_dropdown_array([
					'post_type' => 'post',
					'posts_per_page' => -1,
					]),
				'multiple' => true,
			]
		);

		$this->end_controls_section();

		$traits->sizing();
	}

	protected function render( $instance = [] ) {

		// dump($this->get_settings());

		c27()->get_section('blog-feed', [
			'template' => $this->get_settings('the_template'),
			'posts_per_page' => $this->get_settings('posts_per_page'),
			'category' => $this->get_settings('select_categories'),
			'include' => $this->get_settings('select_posts'),
			'paged' => ( get_query_var('paged') ) ? get_query_var('paged') : 1,
			]);
	}

	protected function content_template() {}

	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new CASE27_Elementor_Widget_Blog_Feed() );
