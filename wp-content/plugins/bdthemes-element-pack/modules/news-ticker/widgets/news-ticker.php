<?php
namespace ElementPack\Modules\NewsTicker\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use ElementPack\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementPack\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class News Ticker
 */
class News_Ticker extends Widget_Base {

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	public function get_name() {
		return 'bdt-news-ticker';
	}

	public function get_title() {
		return esc_html__( 'News Ticker', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_query() {
		return $this->_query;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_label',
			[
				'label'   => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'news_label',
			[
				'label'       => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'LATEST NEWS', 'bdthemes-element-pack' ),
				'placeholder' => esc_html__( 'LATEST NEWS', 'bdthemes-element-pack' ),
				'condition' => [
					'show_label' => 'yes'
				]
			]
		);

		$this->add_control(
			'news_content',
			[
				'label'   => esc_html__( 'News Content', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'excerpt' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_navigation',
			[
				'label' => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label'   => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'navigation_size',
			[
				'label' => esc_html__( 'Navigation Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 3,
						'max' => 26,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-slideshow .bdt-slidenav svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_navigation' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_query',
			[
				'label' => esc_html__( 'Query', 'bdthemes-element-pack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Posts::get_type(),
			[
				'name'  => 'posts',
				'label' => esc_html__( 'Posts', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'advanced',
			[
				'label' => esc_html__( 'Advanced', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'posts_limit',
			[
				'label'   => esc_html__( 'Posts Limit', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date'  => esc_html__( 'Date', 'bdthemes-element-pack' ),
					'post_title' => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'menu_order' => esc_html__( 'Menu Order', 'bdthemes-element-pack' ),
					'rand'       => esc_html__( 'Random', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc'  => esc_html__( 'ASC', 'bdthemes-element-pack' ),
					'desc' => esc_html__( 'DESC', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_label',
			[
				'label'     => esc_html__( 'Label', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_label' => 'yes'
				]
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label'       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label:after' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content'     => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-content',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_navigation' => 'yes'
				]
			]
		);

		$this->add_control(
			'navigation_background',
			[
				'label' => esc_html__( 'Navigation Background', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrow_style' );

		$this->start_controls_tab(
			'tab_arrow_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'navigation_color',
			[
				'label' => esc_html__( 'Navigation Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation a svg' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'arrow_border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation a',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'arrow_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrow_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrow_spacing',
			[
				'label' => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -4,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation a.bdt-slidenav-previous' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrow_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation a:hover svg' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'arrow_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-news-ticker .bdt-news-ticker-navigation a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slider_animations',
			[
				'label'     => esc_html__( 'Animations', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => [
					'slide' => esc_html__( 'Slide', 'bdthemes-element-pack' ),
					'fade'  => esc_html__( 'Fade', 'bdthemes-element-pack' ),
					'scale' => esc_html__( 'Scale', 'bdthemes-element-pack' ),
					'push'  => esc_html__( 'Push', 'bdthemes-element-pack' ),
					'pull'  => esc_html__( 'Pull', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => esc_html__( 'Autoplay', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_interval',
			[
				'label'     => esc_html__( 'Autoplay Interval', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'   => esc_html__( 'Pause on Hover', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'speed',
			[
				'label'              => esc_html__( 'Animation Speed', 'bdthemes-element-pack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
			]
		);

		$this->end_controls_section();
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	public function query_posts() {
		$query_args = Module::get_query_args( 'posts', $this->get_settings() );

		$query_args['posts_per_page'] = $this->get_settings('posts_limit');

		$this->_query = new \WP_Query( $query_args );
	}

	public function render() {
		$this->query_posts();

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->render_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post();
		}

		$this->render_footer();

		wp_reset_postdata();
	}

	protected function render_title() {
		$classes = ['bdt-news-ticker-content-title'];
		?>

		<a href="<?php echo esc_url(get_permalink()); ?>">
			<?php the_title() ?>
		</a>
		<?php
	}


	protected function render_excerpt() {
		
		?>
		<a href="<?php echo esc_url(get_permalink()); ?>">
			<?php the_excerpt(); ?>
		</a>
		<?php
	}

	protected function render_header() {
		$settings        = $this->get_settings();
		$slides_settings = [];

		$slider_settings['bdt-slideshow'] = json_encode(array_filter([
			'animation'         => $settings['slider_animations'],
			'max-height'        => 50,
			'autoplay'          => $settings['autoplay'],
			'autoplay-interval' => $settings['autoplay_interval'],
			'pause-on-hover'    => $settings['pause_on_hover'],
	    ]));
	    
		?>
		<div class="bdt-news-ticker bdt-grid bdt-grid-collapse" bdt-grid>
			<?php if ( 'yes' == $settings['show_label'] ) : ?>
				<div class="bdt-width-auto bdt-visible@s">
					<div class="bdt-news-ticker-label">
							<?php echo $settings['news_label']; ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="bdt-width-expand bdt-grid bdt-grid-collapse" <?php echo \element_pack_helper::attrs($slider_settings); ?> bdt-grid>
				<div class="bdt-width-expand">
					<ul class="bdt-slideshow-items">
		<?php
	}

	protected function render_footer() {
		?>
					</ul>
				</div>
				<?php if ( 'yes' == $this->get_settings('show_navigation') ) : ?>
					<div class="bdt-news-ticker-navigation bdt-width-auto">
						<a class="bdt-visible@m" href="#" bdt-slidenav-previous bdt-slideshow-item="previous"></a>
			    		<a class="" href="#" bdt-slidenav-next bdt-slideshow-item="next"></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	protected function render_loop_item() {
		?>
		<li class="bdt-news-ticker-item">
			<div class="bdt-news-ticker-content">

				<?php if( 'title' == $this->get_settings('news_content') ) : ?>
					<?php $this->render_title(); ?>
				<?php endif; ?>

				<?php if( 'excerpt' == $this->get_settings('news_content') )  : ?>
					<?php $this->render_excerpt(); ?>
				<?php endif; ?>

			</div>
		</li>
		<?php
	}

	protected function render_post() {
		$this->render_loop_item();
	}
}
