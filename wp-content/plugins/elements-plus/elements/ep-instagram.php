<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Instagram extends Widget_Base {

		public function get_name() {
			return 'ep-instagram';
		}

		public function get_title() {
			return __( 'Instagram Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-instagram';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_instagram',
				[
					'label' => __( 'Instagram Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'instagram_username',
				[
					'label'       => __( '@username or #tag', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( '@cssigniter', 'elements-plus' ),
					'placeholder' => __( '@username or #tag', 'elements-plus' ),
				]
			);

			$this->add_control(
				'number_images',
				[
					'label'   => __( 'Number of posts to show', 'elements-plus' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 9,
					'min'     => 1,
					'max'     => 12,
					'step'    => 1,
				]
			);

			$this->add_control(
				'image_size',
				[
					'label'       => __( 'Image Size', 'elements-plus' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'large',
					'options' => [
						'thumbnail' => __( 'Thumbnail', 'elements-plus' ),
						'small'     => __( 'Small', 'elements-plus' ),
						'large'     => __( 'Large', 'elements-plus' ),
						'original'  => __( 'Original', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'layout',
				[
					'label'   => __( 'Layout', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'three-col',
					'options' => [
						'two-col'   => __( 'Two Columns', 'elements-plus' ),
						'three-col' => __( 'Three Columns', 'elements-plus' ),
						'four-col'  => __( 'Four Columns', 'elements-plus' ),
						''          => __( 'Single Row', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'link_target',
				[
					'label'        => __( 'Open in new window', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '_self',
					'label_on'     => __( 'On', 'elements-plus' ),
					'label_off'    => __( 'Off', 'elements-plus' ),
					'return_value' => '_blank',
				]
			);

			$this->add_control(
				'link_rel',
				[
					'label'        => __( 'nofollow', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => __( 'On', 'elements-plus' ),
					'label_off'    => __( 'Off', 'elements-plus' ),
					'return_value' => 'nofollow',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style',
				[
					'label' => __( 'Instagram', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'background_color',
				[
					'label' => __( 'Widget Background Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'default' => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ep-instagram-feed' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'image_padding',
				[
					'label' => __( 'Image Padding', 'elements-plus' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ep-instagram-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings           = $this->get_settings();
			$instagram_instance = new \null_instagram_widget();
			$instagram_username = $settings['instagram_username'] ? $settings['instagram_username'] : '@cssigniter';
			$feed               = array_slice( $instagram_instance->scrape_instagram( $instagram_username ), 0, $settings['number_images'], true );
			?>
				<div id="ep-in-<?php echo esc_attr( $this->get_id() ); ?>">
					<div class="ep-instagram-feed">
						<?php foreach ( $feed as $item ) { ?>
							<div class="<?php echo esc_attr( $settings['layout'] ); ?> ep-instagram-image"><a href="<?php echo esc_url( $item['link'] ); ?>" target="<?php echo esc_attr( $settings['link_target'] ); ?>" rel="<?php echo esc_attr( $settings['link_rel'] ); ?>"><img src="<?php echo esc_attr( $item[ $settings['image_size'] ] ); ?>" alt="<?php echo esc_attr( $item['description'] ); ?>"></a></div>
						<?php } ?>
					</div>
				</div>
				<script>
					jQuery(document).ready(function () {
						jQuery(document).trigger('elementor/render/ep_preloader', '#ep-in-<?php echo esc_attr( $this->get_id() ); ?>');
					});
				</script>
			<?php
		}

		protected function _content_template() {}

	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Instagram() );
	} );
