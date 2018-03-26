<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_AudioIgniter extends Widget_Base {
		public function get_name() {
			return 'ep-audioigniter';
		}

		public function get_title() {
			return __('AudioIgniter Plus!', 'elements-plus');
		}

		public function get_icon() {
			return 'ep-icon ep-icon-audioigniter';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		public function is_reload_preview_required() {
			return true;
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_audioigniter',
				[
					'label' => __( 'AudioIgniter Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'playlist',
				[
					'label'     => __( 'Playlist', 'your-plugin' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '',
					'options'   => $this->get_playlists(),
					'separator' => 'after',
				]
			);

			$this->add_control(
				'background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai-wrap' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap .ai-volume-bar' => 'border-right-color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap .ai-track-btn' => 'border-left-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai-wrap,
						{{WRAPPER}} .ai-wrap .ai-btn,
						{{WRAPPER}} .ai-wrap ai-track-btn' => 'color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap .ai-btn svg,
						{{WRAPPER}} .ai-wrap .ai-track-no-thumb svg,
						{{WRAPPER}} .ai-wrap .ai-track-btn svg' => 'fill: {{VALUE}}',
					],
				] );

			$this->add_control(
				'accent_color',
				[
					'label'     => __( 'Accent Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai-wrap .ai-audio-control,
						{{WRAPPER}} .ai-wrap .ai-audio-control:hover,
						{{WRAPPER}} .ai-wrap .ai-audio-control:focus,
						{{WRAPPER}} .ai-wrap .ai-track-progress,
						{{WRAPPER}} .ai-wrap .ai-volume-bar.ai-volume-bar-active::before,
						{{WRAPPER}} .ai-wrap .ai-track:hover,
						{{WRAPPER}} .ai-wrap .ai-track.ai-track-active,
						{{WRAPPER}} .ai-wrap .ai-btn.ai-btn-active' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap .ai-scroll-wrap > div:last-child div' => 'background-color: {{VALUE}} !important',
						'{{WRAPPER}} .ai-wrap .ai-btn:hover,
						{{WRAPPER}} .ai-wrap .ai-btn:focus,
						{{WRAPPER}} .ai-wrap .ai-footer a,
						{{WRAPPER}} .ai-wrap .ai-footer a:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap .ai-btn:hover svg,
						{{WRAPPER}} .ai-wrap .ai-btn:focus svg' => 'fill: {{VALUE}}',
					],
				] );

			$this->add_control(
				'text_on_accent',
				[
					'label'     => __( 'Text on Accent Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai-wrap .ai-audio-control,
						{{WRAPPER}} .ai-wrap .ai-track:hover,
						{{WRAPPER}} .ai-wrap .ai-track.ai-track-active,
						{{WRAPPER}} .ai-wrap .ai-track.ai-track-active .ai-track-btn,
						{{WRAPPER}} .ai-wrap .ai-track:hover .ai-track-btn,
						{{WRAPPER}} .ai-wrap .ai-btn.ai-btn-active' => 'color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap .ai-audio-control svg,
						{{WRAPPER}} .ai-wrap .ai-track.ai-track-active .ai-track-btn svg,
						{{WRAPPER}} .ai-wrap .ai-track:hover .ai-track-btn svg,
						{{WRAPPER}} .ai-wrap .ai-btn.ai-btn-active svg' => 'fill: {{VALUE}}',
					],
				] );

			$this->add_control(
				'control_color',
				[
					'label'     => __( 'Control Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ai-wrap .ai-track-progress-bar,
						{{WRAPPER}} .ai-wrap .ai-volume-bar,
						{{WRAPPER}} .ai-wrap .ai-btn,
						{{WRAPPER}} .ai-wrap .ai-btn:hover,
						{{WRAPPER}} .ai-wrap .ai-btn:focus,
						{{WRAPPER}} .ai-wrap .ai-track,
						{{WRAPPER}} .ai-wrap .ai-track-no-thumb,
						{{WRAPPER}} .ai-wrap .ai-scroll-wrap > div:last-child' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap .ai-footer' => 'border-top-color: {{VALUE}}',
						'{{WRAPPER}} .ai-wrap.ai-is-loading .ai-control-wrap-thumb::after,
						{{WRAPPER}} .ai-wrap.ai-is-loading .ai-track-title::after,
						{{WRAPPER}} .ai-wrap.ai-is-loading .ai-track-subtitle::after' => 'background: {{VALUE}}'
					],
				]
			);

			$this->end_controls_section();
		}

		protected function get_playlists() {
			$args = array(
				'post_type'      => 'ai_playlist',
				'posts_per_page' => - 1,
			);

			$playlists = get_posts( $args );
			$options = [];

			foreach ( $playlists as $playlist ) {
				$options[ $playlist->ID ] = $playlist->post_title;
			}

			return $options;
		}

		protected function render() {
			$settings = $this->get_settings();
			$id = $settings['playlist'];

			if ( ! $id ) {
				return;
			}

			$shortcode = do_shortcode( shortcode_unautop( '[ai_playlist id="' . $id . '"]' ) );
			?>
			<div
				id="ep-ai-<?php echo esc_attr( $this->get_id() ); ?>"
				class="ep-audioigniter"
			>
				<?php echo $shortcode; ?>
			</div>

			<script>
				jQuery(document).ready(function () {
					jQuery(document).trigger('elementor/render/ep_audioigniter', '#ep-ai-<?php echo esc_attr( $this->get_id() ); ?>');
				});
			</script>
			<?php
		}

		protected function _content_template() { }
	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_AudioIgniter() );
	} );
