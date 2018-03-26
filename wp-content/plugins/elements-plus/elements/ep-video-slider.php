<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Video_Slider extends Widget_Base {

		public function get_name() {
			return 'video_slider';
		}

		public function get_title() {
			return __( 'YouTube Slideshow Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-youtube';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		/**
		 * Retrieve the list of scripts the image carousel widget depended on.
		 *
		 * Used to set scripts dependencies required to run the widget.
		 *
		 * @access public
		 *
		 * @return array Widget scripts dependencies.
		 */
		public function get_script_depends() {
			return [ 'jquery-slick' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_video_slider',
				[
					'label' => __( 'YouTube Slideshow Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'video-list',
				[
					'label'  => __( 'Video Slides', 'elements-plus' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'        => 'video_url',
							'label'       => __( 'Video URL', 'elements-plus' ),
							'type'        => Controls_Manager::URL,
							'label_block' => true,
							'default'     => [
								'url'         => '',
								'is_external' => false,
							],
							'placeholder' => __( 'http://your-video-url', 'elements-plus' ),
						],
						[
							'name'        => 'text',
							'label'       => __( 'Slide Text', 'elements-plus' ),
							'type'        => Controls_Manager::TEXT,
							'label_block' => true,
							'placeholder' => __( 'Slide Text', 'elements-plus' ),
							'default'     => '',
						],
					],
				]
			);

			$this->add_control(
			'nav_position',
				[
					'label'   => __( 'Navigation slider position', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'right',
					'options' => [
						'right' => __( 'Right', 'elements-plus' ),
						'left'  => __( 'Left', 'elements-plus' ),
						'below' => __( 'Below', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'view',
				[
					'label'   => __( 'View', 'elements-plus' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style',
				[
					'label' => __( 'Slide Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} h2.ep-nav-title',
				]
			);

			$this->start_controls_tabs( 'tabs_slide_style' );

			$this->add_control(
				'slide_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} h2.ep-nav-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					],
					'default'   => 'transparent',
					'selectors' => [
						'{{WRAPPER}} h2.ep-nav-title' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'align',
				[
					'label'     => __( 'Text Align', 'elements-plus' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => __( 'Left', 'elements-plus' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elements-plus' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'elements-plus' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} h2.ep-nav-title' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'slide_title_padding',
				[
					'label'      => __( 'Text Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .ep-nav-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'  => 'before',
				]
			);

			$this->add_control(
				'overlay_color',
				[
					'label'     => __( 'Overlay Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'default'   => 'rgba( 0, 0, 0, 0.3 )',
					'selectors' => [
						'{{WRAPPER}} .ep-nav-slide::before' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tabs();

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings();

			if ( 'left' === $settings['nav_position'] ) {
				$position = 'left';
			} elseif ( 'below' === $settings['nav_position'] ) {
				$position = 'below';
			} else {
				$position = 'right';
			}

			$slides = 'below' === $position ? '5' : '4';
			?>
			<div id="ep-vs-<?php echo esc_attr( $this->get_id() ); ?>" class="ep-slider-container" data-position="<?php echo ( 'below' === $position ) ? 'false' : 'true'; ?>" data-slides="<?php echo esc_attr( $slides ); ?>">
				<div class="ep-slider-item ep-video-slider <?php echo esc_attr( $position ); ?>">
					<?php foreach( $settings['video-list'] as $video ) {
						$url = ( empty ( $video['video_url']['url'] ) ) ? '#' : esc_url( $video['video_url']['url'] );

						?><div class="ep-video-slide"><?php echo wp_oembed_get( $url ); ?></div><?php
					} ?>
				</div>
				<div class="ep-slider-item ep-slider-nav <?php echo esc_attr( $position ); ?>">
					<?php foreach( $settings['video-list'] as $video ) {
						$url = ( empty ( $video['video_url']['url'] ) ) ? '#' : esc_url( $video['video_url']['url'] );
						$video_info = elements_plus_get_video_url_info( $url );
						$thumb = "https://img.youtube.com/vi/" . $video_info['video_id'] . "/hqdefault.jpg"
						?>
						<div>
							<div class="ep-nav-slide" style="background-image: url('<?php echo esc_attr( $thumb ); ?>');">
								<h2 class="ep-nav-title"><?php echo $video['text']; ?></h2>
							</div>
						</div>
						<?php
					} ?>
				</div>
			</div>
			<script>
				jQuery(document).ready(function () {
					jQuery(document).trigger('elementor/render/ep_video_slider', '#ep-vs-<?php echo esc_attr( $this->get_id() ); ?>');
				});
			</script>
			<?php
		}

		protected function _content_template() {}

	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Video_Slider() );
	} );
